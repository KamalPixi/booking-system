<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class DepositAdminController extends Controller {


    public function deposits() {
        $active = [
            'menu' => 'deposits',
            'sub_menu' => 'deposits',
        ];
        return view('admin.deposits.deposits', compact('active'));
    }

    public function depositsOnline() {
        $active = [
            'menu' => 'deposits',
            'sub_menu' => 'deposits.online',
        ];
        return view('admin.deposits.online-deposits', compact('active'));
    }

    public function depositsManual() {
        $active = [
            'menu' => 'deposits',
            'sub_menu' => 'deposits.manual',
        ];
        return view('admin.deposits.manual-deposits', compact('active'));
    }

    public function depositsCanceled() {
        $active = [
            'menu' => 'deposits',
            'sub_menu' => 'deposits.canceled',
        ];
        return view('admin.deposits.canceled-deposits', compact('active'));
    }

}
