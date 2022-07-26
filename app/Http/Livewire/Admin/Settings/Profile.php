<?php

namespace App\Http\Livewire\Admin\Settings;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class Profile extends Component {

    public $name;
    public $mobile_no;
    public $email;

    // password form input
    public $current_password;
    public $new_password;
    public $confirm_password;

    protected $rules = [
        'name' => 'required|max:255',
        'mobile_no' => 'nullable|max:255',
        'email' => 'required|max:20',
    ];

    public function mount() {
        $user = Auth::user();
        $this->fill([
            'name' => $user->name,
            'mobile_no' => $user->mobile_no,
            'email' => $user->email,
        ]);
    }

    public function update() {
        $form = $this->validate();
        $user = auth()->user();

        if (!empty($form['email'])) {
            if ($form['email'] != $user->email) {
                if (User::where('email', $form['email'])->exists()) {
                    return session()->flash('failed', 'Email address already exists!');
                }
            }
        }

        Auth::user()->update($form);
        session()->flash('success', 'Profile has Updated.');
    }

    public function changePassword() {
        $d = $this->validate([
            'current_password' => 'required|max:100',
            'new_password' => 'required|max:100',
            'confirm_password' => 'required|max:100'
        ]);

        if (!Hash::check($d['current_password'], Auth::user()->password)) {
            return session()->flash('failed', 'Current password does not match!');
        }

        if($d['new_password'] != $d['confirm_password']) {
            return session()->flash('failed', 'Confirm password does not match!');
        }

        Auth::user()->update(['password' => bcrypt($d['new_password'])]);
        session()->flash('success', 'Password has Updated.');
    }

    public function render() {
        return view('livewire.admin.settings.profile');
    }
}
