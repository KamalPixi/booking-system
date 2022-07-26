<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\AirTicketIssueReq;
use App\Enums\TransactionEnum;

class AirTicketIssueReqListener {

    public function __construct() {}

    public function handle(AirTicketIssueReq $event) {
        $agent = auth()->user()->agent;
        if (!$agent) {
            return session()->flash('failed', 'Agent not found!');
        }

        $bookingModel = $event->booking;
        try{
            \DB::beginTransaction();
        
            $airTicket = $bookingModel->airTicket()->create([
                'amount' => $bookingModel->amount,
                'currency' => $bookingModel->currency,
                'created_by' => auth()->user()->id,
            ]);
    
            # create payment
            $payment = $bookingModel->payments()->create([
                'agent_id' => $agent->id,
                'amount' => $bookingModel->amount,
                'currency' => $bookingModel->currency,
                'method' => TransactionEnum::METHOD['ACCOUNT_BALANCE'],
                'purpose' => TransactionEnum::PURPOSE['AIR_TICKET_ISSUE'],
                'status' => TransactionEnum::STATUS['COMPLETED'], # since used account credit balance
                'remark' => TransactionEnum::STATUS['PAID'], # since used account credit balance
                'created_by' => auth()->user()->id,
            ]);
    
            # deduct agent account balance
            $account = $agent->account;
            $account->balance -= $bookingModel->amount;
            $account->save();

            # add to transaction
            $payment->transactions()->create([
                'sign' => TransactionEnum::SIGN['MINUS'],
                'amount' => $payment->amount,
                'currency' => $payment->currency,
                'purpose' => TransactionEnum::PURPOSE['AIR_TICKET_ISSUE'],
                'method' => $payment->method,
                'initiated_by' => $payment->created_by,
                'remark' => $payment->remark,
            ]);

            # mark booking as paid
            $bookingModel->update([
                'payment_status' => TransactionEnum::STATUS['PAID']
            ]);
        
            \DB::commit();
        }catch(\Exception $e){
            \DB::rollback();
            return session()->flash('failed', 'Ticket issue request failed');
        }

        return session()->flash('success', 'Ticket issue request successful');
    }
}
