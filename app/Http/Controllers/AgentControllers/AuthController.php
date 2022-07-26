<?php

namespace App\Http\Controllers\AgentControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Enums\UserEnum;


class AuthController extends Controller {

    public function loginShow(Request $request) {
        $allowedType = [
            UserEnum::TYPE['AGENT'],
            UserEnum::TYPE['AGENT_USER']
        ];

        if (auth()->check() && in_array(auth()->user()->type, $allowedType)) {
            return redirect()->route('b2b.dashboard');
        }

        return view('agent.auth.login');
    }

    public function logout() {
        Auth::logout();
        return redirect('/login');
    }

    public function register() {
        return view('agent.auth.register');
    }
}
