<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Enums\UserEnum;

class AuthAdminController extends Controller {
    public function loginForm() {
        return view('admin.login');
    }

    public function login(Request $request) {
        $body = $request->validate([
            'email' => 'bail|required|email|max:255|exists:users,email',
            'password' => 'required|max:255'
        ]);
        $body['status'] = UserEnum::STATUS['ACTIVE'];

        if (Auth::attempt($body)) {
            return redirect('/');
        }

        return redirect()->back()->withFailed('Email or Password is incorrect!');
    }

    public function logout() {
        Auth::logout();
        return redirect()->route('admin.login');
    }
}
