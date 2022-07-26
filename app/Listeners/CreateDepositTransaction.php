<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\Deposited;
use App\Enums\TransactionEnum;
use App\Enums\UserEnum;


class CreateDepositTransaction {

    public function __construct() {}

    public function handle(Deposited $event) {
        $depositModel = $event->deposit;
        $agent = $depositModel->depositedBy->agent ?? '';

        if (!$agent) {
            return session()->flash('failed', 'Agent not found!');
        }

        # return, if transaction already exists
        if ($depositModel->transaction) {
            return;
        }

        $depositModel->transaction()->create([
            'sign' => TransactionEnum::SIGN['PLUS'],
            'amount' => $depositModel->amount,
            'currency' => $depositModel->currency,
            'purpose' => TransactionEnum::PURPOSE['ACCOUNT_DEPOSIT'],
            'method' => $depositModel->method,
            'initiated_by' => $depositModel->deposited_by,
            'remark' => $depositModel->remark,
        ]);


        $account = $agent->account;
        $account->balance += $depositModel->amount;
        $account->save();
    }
}
