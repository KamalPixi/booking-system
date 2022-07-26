<?php

namespace App\Http\Livewire\Agent\Settings;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

/**
 * Handles CRUD of unit
 */
class Profile extends Component {
    use WithFileUploads;

    public $edit = false;

    public $photo = '';
    public $name = '';
    public $mobile_no = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';

    public function mount() {
        $user = auth()->user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->mobile_no = $user->mobile_no;
    }

    public function update() {
        $form = $this->validate([
            'photo' => 'nullable|image|max:1024',
            'name' => 'nullable|max:255',
            'mobile_no' => 'nullable|max:20',
            'email' => 'nullable|max:255|email',
            'password' => 'nullable|confirmed|max:255',
        ]);


        $user = auth()->user();
        if ($form['email']) {
            if ($form['email'] != $user->email) {
                if (User::where('email', $form['email'])->exists()) {
                    return session()->flash('failed', 'Email address already exists!');
                }
            }
            $user->email = $form['email'];
        }

        if ($form['photo']) {
            $filename = 'photo_'. auth()->user()->id .'_'.time().'.'.$this->photo->extension();  
            $path = $this->photo->storePubliclyAs('files', $filename, 's3');
            $url = Storage::disk('s3')->url($path);

            if ($user->photo) {
                $user->photo->update([
                    'file' => $url
                ]);
            }else {
                $user->photo()->create([
                    'file' => $url
                ]);
            }
        }
        if ($form['name']) {
            $user->name = $form['name'];
        }
        if ($form['mobile_no']) {
            $user->mobile_no = $form['mobile_no'];
        }
        if ($form['password']) {
            $user->password = Hash::make($form['password']);
        }
        
        $user->save();
        return session()->flash('success', 'Profile updated');
    }


    public function setEdit() {
        $this->edit = true;
    }

    public function unsetEdit() {
        $this->edit = false;
    }

    public function render() {
        return view('livewire.agent.settings.profile');
    }

}
