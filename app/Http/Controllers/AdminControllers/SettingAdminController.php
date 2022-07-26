<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Enums\UserEnum;

class SettingAdminController extends Controller {

    public function users() {
        $active = [
            'menu' => 'settings',
            'sub_menu' => 'users',
        ];
        return view('admin.settings.users-management', compact('active'));
    }

    public function profile() {
        $active = [
            'menu' => 'settings',
            'sub_menu' => 'profile',
        ];
        return view('admin.settings.profile', compact('active'));
    }

    public function banks() {
        $active = [
            'menu' => 'banks',
            'sub_menu' => 'banks',
        ];
        return view('admin.settings.banks-create', compact('active'));
    }

    public function adminFees() {
        $active = [
            'menu' => 'banks',
            'sub_menu' => 'admin-fees',
        ];
        return view('admin.settings.admin-fees', compact('active'));
    }

    public function paymentMethods() {
        $active = [
            'menu' => 'banks',
            'sub_menu' => 'payment-methods',
        ];
        return view('admin.settings.payment-methods', compact('active'));
    }
}
