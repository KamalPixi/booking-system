<?php

namespace App\Http\Livewire\Agent\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Mail\ResetPassword;
use App\Models\User;

class Login extends Component {

    public $formToShow = 'login';

    public $email = '';
    public $password = '';

    protected $rules = [
        'email' => 'bail|required|email|max:255|exists:users,email',
        'password' => 'required|max:255'
    ];

    public function login() {
        $body = $this->validate();
        $body['status'] = 1;

        if (Auth::attempt($body, true)) {
            return redirect('/');
        }

        return session()->flash('failed', 'Email or Password is incorrect!');
    }

    public function resetPassword() {
        $body = $this->validateOnly('email');

        $user = User::where('email', $body['email'])->first();

        $new_password = uniqid();
        $user->password = Hash::make($new_password);
        $user->save();

        Mail::to($user->email)->send(new ResetPassword($user, $new_password));
        return session()->flash('success', 'New Password has been sent to your email');
    }

    public function showResetForm() {
        $this->formToShow = 'resetForm';
    }
    public function showLogin() {
        $this->formToShow = 'login';
    }

    public function render() {
        return view('livewire.agent.auth.login');
    }
}
