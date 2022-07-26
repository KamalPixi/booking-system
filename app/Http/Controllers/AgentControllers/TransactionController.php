<?php

namespace App\Http\Controllers\AgentControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\TransactionHelper;
use App\Enums\TransactionEnum;
use App\Library\SslCommerz\SslCommerzNotification;
use App\Models\Transaction;


class TransactionController extends Controller {

    public function addBalanceShow() {
        return view('agent.transactions.add-balance');
    }

    public function addBalance(Request $request, SslCommerzNotification $sslC) {
        $form = $request->validate([
            'amount' => 'bail|required|numeric|digits_between:3,12'
        ]);

        $body = TransactionHelper::sslCommerzeDS();
        $this->addAmountInfo($body, $form);
        $this->addCustomerInfo($body);
        $this->addShippingInfo($body);

        # agent is required
        $agent = auth()->user()->agent;
        if (!$agent) {
            return redirect()->route('b2b.transactions.addBalance')->withFailed('Agent not found!');
        }
        
        # store transaction
        $transaction = $agent->transactions()->updateOrCreate([
            'transaction_no' => $body['tran_id']
        ], [
            'sign' => TransactionEnum::SIGN['PLUS'],
            'amount' => $form['amount'],
            'currency' => TransactionEnum::CURRENCY['BDT'],
            'purpose' => TransactionEnum::PURPOSE['ACCOUNT_TOPUP'],
            'method' => TransactionEnum::METHOD['ONLINE'],
            'status' => TransactionEnum::STATUS['PENDING'],
            'type' => TransactionEnum::TYPE['TOPUP'],
            'created_by' => auth()->user()->id
        ]);

        $payment_options = $sslC->makePayment($body, 'hosted');

        # don't know why ssl used this
        if (!is_array($payment_options)) {
            print_r($payment_options);
            $payment_options = array();
        }
        
        dd($form);
    }



    public function success(Request $request, SslCommerzNotification $sslc) {
        # store this response for debug/clarification purpose
        # TODO

        $request->validate([
            'tran_id' => 'required|max:255|exists:transactions,transaction_no',
            'amount' => 'bail|required|numeric|digits_between:3,12',
            'currency' => 'required',
        ]);

        $tran_id = $request->input('tran_id');
        $amount = $request->input('amount');
        $currency = $request->input('currency');

        #Check order status in order tabel against the transaction id or order id.
        $transaction = Transaction::where('transaction_no', $tran_id)->first();
        if (!$transaction) {
            return redirect()->route('b2b.transactions.addBalance')->withFailed('Transaction not found!');
        }

        if ($transaction->status == TransactionEnum::STATUS['PENDING']) {
            $validation = $sslc->orderValidate($request->all(), $tran_id, $amount, $currency);
            if ($validation == TRUE) {
                /*
                That means IPN did not work or IPN URL was not set in your merchant panel. Here you need to update order status
                in order table as Processing or Complete.
                Here you can also sent sms or email for successfull transaction to customer
                */
                $transaction->update(['status' => TransactionEnum::STATUS['PROCESSING']]);
                return redirect()->route('b2b.transactions.addBalance')->withSuccess('Transaction is successfully Completed');
            } else {
                /*
                That means IPN did not work or IPN URL was not set in your merchant panel and Transation validation failed.
                Here you need to update order status as Failed in order table.
                */
                $transaction->update(['status' => TransactionEnum::STATUS['FAILED']]);
                return redirect()->route('b2b.transactions.addBalance')->withSuccess('Validation failed!');
            }
        } else if ($transaction->status == TransactionEnum::STATUS['PROCESSING'] || $transaction->status == TransactionEnum::STATUS['COMPLETED']) {
            /*
             That means through IPN Order status already updated. Now you can just show the customer that transaction is completed. No need to udate database.
             */
            return redirect()->route('b2b.transactions.addBalance')->withSuccess('Transaction is successfully Completed');
        } else {
            #That means something wrong happened. You can redirect customer to your product page.
            return redirect()->route('b2b.transactions.addBalance')->withFailed('Invalid Transaction!');
        }
    }

    public function failed(Request $request) {
        $request->validate([
            'tran_id' => 'required|max:255|exists:transactions,transaction_no',
        ]);

        $tran_id = $request->input('tran_id');

        $transaction = Transaction::where('transaction_no', $tran_id)->first();
        if (!$transaction) {
            return redirect()->route('b2b.transactions.addBalance')->withFailed('Transaction not found!');
        }

        if ($transaction->status == TransactionEnum::STATUS['PENDING']) {
            $transaction->update(['status' => TransactionEnum::STATUS['FAILED']]);
            return redirect()->route('b2b.transactions.addBalance')->withFailed('Transaction is Failed!');

        } else if ($transaction->status == TransactionEnum::STATUS['PROCESSING'] || $transaction->status == TransactionEnum::STATUS['COMPLETED']) {
            return redirect()->route('b2b.transactions.addBalance')->withSuccess('Transaction is successful');

        } else {
            return redirect()->route('b2b.transactions.addBalance')->withFailed('Invalid Transaction!');
        }

    }

    public function cancel(Request $request) {
        $request->validate([
            'tran_id' => 'required|max:255|exists:transactions,transaction_no',
        ]);

        $tran_id = $request->input('tran_id');

        $transaction = Transaction::where('transaction_no', $tran_id)->first();
        if (!$transaction) {
            return redirect()->route('b2b.transactions.addBalance')->withFailed('Transaction not found!');
        }
        
        if ($transaction->status == TransactionEnum::STATUS['PENDING']) {
            $transaction->update(['status' => TransactionEnum::STATUS['CANCELED']]);
            return redirect()->route('b2b.transactions.addBalance')->withFailed('Transaction is canceled');

        } else if ($transaction->status == TransactionEnum::STATUS['PROCESSING'] || $transaction->status == TransactionEnum::STATUS['COMPLETED']) {
            return redirect()->route('b2b.transactions.addBalance')->withSuccess('Transaction is already Successful');

        } else {
            return redirect()->route('b2b.transactions.addBalance')->withFailed('Transaction is invalid!');
        }
    }


    public function sslCommerzIPN(Request $request) {
        $request->validate([
            'tran_id' => 'required|max:255|exists:transactions,transaction_no',
        ]);

        
    }

    private function addAmountInfo(&$body, $form) {
        $body['total_amount'] = (string) $form['amount'];
        $body['currency'] = TransactionEnum::CURRENCY['BDT'];
        $body['tran_id'] = uniqid();
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
