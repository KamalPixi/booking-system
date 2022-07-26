<?php

namespace App\Http\Controllers\AgentControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\SslCommerz\SslCommerzNotification;
use App\Enums\TransactionEnum;
use App\Enums\RequestTypeEnum;
use App\Helpers\SSLCommerzHelper;
use App\Models\Deposit;
use App\Models\TransactionMethod;
use Illuminate\Support\Facades\Log;
use App\Events\Deposited;

class PaymentGatewayController extends Controller {

    public function addBalanceShow() {
        return view('agent.transactions.add-balance');
    }

    public function addBalanceShowManual() {
        return view('agent.transactions.add-balance-manual');
    }

    public function addBalance(Request $request, SslCommerzNotification $sslC) {
        if (!auth()->user()->can('agent_create deposit')) {
            return view('agent.includes.unauthorized');
        };

        $form = $request->validate([
            'amount' => 'bail|required|numeric|digits_between:3,12|min:100'
        ]);

        # format amount
        $form['amount'] = number_format($form['amount'], 2, '.', '');

        $body = SSLCommerzHelper::sslCommerzDS();
        $this->addAmountInfo($body, $form);
        $this->addCustomerInfo($body);
        $this->addShippingInfo($body);
        
        # agent is required
        $agent = auth()->user()->agent;
        if (!$agent) {
            return redirect()->route('b2b.gateway.addBalance')->withFailed('Agent not found!');
        }
        
        # calculate deposit fee
        $transactionMethod = TransactionMethod::where([
            'key' => TransactionEnum::METHOD['ONLINE_PAYMENT'],
            'status' => 1
        ])->first();
        if (!$transactionMethod) {
            return redirect()->route('b2b.gateway.addBalance')->withFailed('Payment method not found!');
        }
        $fee = 0;
        $finalDepositAmount = 0;
        if ($transactionMethod->fee_type == TransactionEnum::METHOD_FEE_TYPE['PERCENTAGE']) {
            $fee = $form['amount'] / 100 * $transactionMethod->fee;
            $finalDepositAmount = number_format($form['amount'] - $fee, 2, '.', '');
        }else {
            $finalDepositAmount = number_format($form['amount'] - $transactionMethod->fee, 2, '.', '');
            $fee = $transactionMethod->fee;
        }

        # store transaction
        $deposit = $agent->deposits()->updateOrCreate([
            'transaction_no' => $body['tran_id']
        ], [
            'amount' => $finalDepositAmount,
            'fee' => $fee,
            'currency' => TransactionEnum::CURRENCY['BDT'],
            'method' => TransactionEnum::METHOD['ONLINE_PAYMENT'],
            'status' => TransactionEnum::STATUS['PENDING'],
            'deposited_by' => auth()->user()->id
        ]);
        $payment_options = $sslC->makePayment($body, 'hosted');

        # log, if fails
        if (!is_array($payment_options)) {
            Log::error('PAYMENT_GATEWAY_ERROR');
            Log::error($payment_options);
            print_r($payment_options);
            $payment_options = array();
        }
    }



    public function success(Request $request, SslCommerzNotification $sslc) {
        # store this response for debug/clarification purpose
        # TODO

        $request->validate([
            'tran_id' => 'required|max:255|exists:deposits,transaction_no',
            'amount' => 'bail|required|numeric|digits_between:3,12',
            'currency' => 'required',
        ]);

        $tran_id = $request->input('tran_id');
        $amount = $request->input('amount');
        $currency = $request->input('currency');

        #Check order status in order table against the transaction id or order id.
        $deposit = Deposit::where('transaction_no', $tran_id)->first();
        if (!$deposit) {
            return redirect()->route('b2b.gateway.addBalance')->withFailed('Transaction not found!');
        }

        # store the response for later debug
        $deposit->jsons()->create([
            'type' => RequestTypeEnum::TYPE['RESPONSE'],
            'json' => json_encode($request->all()),
        ]);

        if ($deposit->status == TransactionEnum::STATUS['PENDING']) {
            $validation = $sslc->orderValidate($request->all(), $tran_id, $amount, $currency);
            if ($validation == TRUE) {
                /*
                That means IPN did not work or IPN URL was not set in your merchant panel. Here you need to update order status
                in order table as Processing or Complete.
                Here you can also sent sms or email for successful transaction to customer
                */
                $deposit->update(['status' => TransactionEnum::STATUS['PROCESSING']]);
                
                # to create a transaction 
                event(new Deposited($deposit));
                return redirect()->route('b2b.gateway.addBalance')->withSuccess('Transaction is successfully Completed');
            } else {
                /*
                That means IPN did not work or IPN URL was not set in your merchant panel and Transaction validation failed.
                Here you need to update order status as Failed in order table.
                */
                $deposit->update(['status' => TransactionEnum::STATUS['FAILED']]);
                return redirect()->route('b2b.gateway.addBalance')->withSuccess('Validation failed!');
            }
        } else if ($deposit->status == TransactionEnum::STATUS['PROCESSING'] || $deposit->status == TransactionEnum::STATUS['COMPLETED']) {
            /*
             That means through IPN Order status already updated. Now you can just show the customer that transaction is completed. No need to udate database.
             */
            
            # to create a transaction 
            event(new Deposited($deposit));
            return redirect()->route('b2b.gateway.addBalance')->withSuccess('Transaction is successfully Completed');
        } else {
            #That means something wrong happened. You can redirect customer to your product page.
            return redirect()->route('b2b.gateway.addBalance')->withFailed('Invalid Transaction!');
        }
    }

    public function failed(Request $request) {
        $request->validate([
            'tran_id' => 'required|max:255|exists:deposits,transaction_no',
        ]);

        $tran_id = $request->input('tran_id');
        $deposit = Deposit::where('transaction_no', $tran_id)->first();
        if (!$deposit) {
            return redirect()->route('b2b.gateway.addBalance')->withFailed('Transaction not found!');
        }

        if ($deposit->status == TransactionEnum::STATUS['PENDING']) {
            $deposit->update(['status' => TransactionEnum::STATUS['FAILED']]);
            return redirect()->route('b2b.gateway.addBalance')->withFailed('Transaction is failed!');

        } else if ($deposit->status == TransactionEnum::STATUS['PROCESSING'] || $deposit->status == TransactionEnum::STATUS['COMPLETED']) {
            return redirect()->route('b2b.gateway.addBalance')->withSuccess('Transaction is successful');

        } else {
            return redirect()->route('b2b.gateway.addBalance')->withFailed('Invalid transaction!');
        }

    }

    public function cancel(Request $request) {
        $request->validate([
            'tran_id' => 'required|max:255|exists:deposits,transaction_no',
        ]);

        $tran_id = $request->input('tran_id');
        $deposit = Deposit::where('transaction_no', $tran_id)->first();
        if (!$deposit) {
            return redirect()->route('b2b.gateway.addBalance')->withFailed('Transaction not found!');
        }
        
        if ($deposit->status == TransactionEnum::STATUS['PENDING']) {
            $deposit->update([
                'status' => TransactionEnum::STATUS['CANCELED'],
                'remark' => 'Canceled by user',
            ]);
            return redirect()->route('b2b.gateway.addBalance')->withFailed('Transaction is canceled');

        } else if ($deposit->status == TransactionEnum::STATUS['PROCESSING'] || $deposit->status == TransactionEnum::STATUS['COMPLETED']) {
            return redirect()->route('b2b.gateway.addBalance')->withSuccess('Transaction is already successful');

        } else {
            return redirect()->route('b2b.gateway.addBalance')->withFailed('Transaction is invalid!');
        }
    }


    public function sslCommerzIPN(Request $request) {
        $request->validate([
            'tran_id' => 'required|max:255|exists:deposits,transaction_no',
        ]);

        return $request->all();
    }

    private function addAmountInfo(&$body, $form) {
        $body['total_amount'] = (string) $form['amount'];
    }
    private function addCustomerInfo(&$body) {
        $agent = auth()->user()->agent;
        $body['cus_name'] = $agent->full_name;
        $body['cus_email'] = $agent->email;
        $body['cus_add1'] = $agent->address;
        $body['cus_city'] = $agent->city;
        $body['cus_state'] = $agent->state;
        $body['cus_postcode'] = $agent->postcode;
        $body['cus_phone'] = $agent->phone;
    }
    private function addShippingInfo(&$body) {
        $agent = auth()->user()->agent;
        $body['ship_name'] = $agent->company;
        $body['ship_add1'] = $agent->address;
        $body['ship_add2'] = $agent->state;
        $body['ship_city'] = $agent->city;
        $body['ship_state'] = $agent->state;
        $body['ship_postcode'] = $agent->postcode;
        $body['ship_phone'] = $agent->phone;
        $body['ship_country'] = "Bangladesh";
    }
}
