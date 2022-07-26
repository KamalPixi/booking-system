<?php

namespace App\Http\Controllers\AgentControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminBankAccount;
use App\Models\TransactionMethod;
use App\Enums\TransactionEnum;

class PaymentController extends Controller {

    public function deposits() {
        $active = [4, 1];
        return view('agent.payments.deposits', compact('active'));
    }

    public function payments() {
        $active = [4, 2];
        return view('agent.payments.payments', compact('active'));
    }

    public function refunds() {
        $active = [4, 3];
        return view('agent.payments.refunds', compact('active'));
    }

    public function paymentMethodList() {
        $banks = AdminBankAccount::where('status', 1)->get();
        $methods = TransactionMethod::where('status', 1)->get()->filter(function($m) {
            if (in_array($m->key, [
                TransactionEnum::METHOD['CASH'],
                TransactionEnum::METHOD['BANK_DEPOSIT'],
                TransactionEnum::METHOD['ONLINE_BANK_TRANSFER'],
                TransactionEnum::METHOD['ONLINE_PAYMENT'],
                TransactionEnum::METHOD['ACCOUNT_BALANCE'],
            ])) {
                return false;
            }

            return true;
        });
        return view('agent.payments.methods', compact('banks', 'methods'));
    }
}
