<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class PaymentAdminController extends Controller {


    public function payments() {
        $active = [
            'menu' => 'payments',
            'sub_menu' => 'payments',
        ];
        return view('admin.payments.payments', compact('active'));
    }

    public function partialsRequests() {
        $active = [
            'menu' => 'payments',
            'sub_menu' => 'payments.partial-requests',
        ];
        return view('admin.payments.partial-requests', compact('active'));
    }

}
