<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class TransactionAdminController extends Controller {


    public function transactions() {
        $active = [
            'menu' => 'transactions',
            'sub_menu' => 'transactions',
        ];
        return view('admin.transactions.transactions', compact('active'));
    }

    public function refunds() {
        $active = [
            'menu' => 'transactions',
            'sub_menu' => 'refunds',
        ];
        return view('admin.transactions.refunds', compact('active'));
    }

    public function refundRequests() {
        $active = [
            'menu' => 'transactions',
            'sub_menu' => 'refunds.requests',
        ];
        return view('admin.transactions.refund-requests', compact('active'));
    }

}
