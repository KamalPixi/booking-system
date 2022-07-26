<?php

namespace App\Http\Livewire\Agent\Settings;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Enums\FileEnum;

/**
 * Handles CRUD of unit
 */
class Company extends Component {
    use WithFileUploads;

    public $edit = false;

    public $company = '';
    public $full_name = '';
    public $phone = '';
    public $email = '';
    public $address = '';
    public $division = '';
    public $city = '';
    public $postcode = '';
    public $logo = '';

    public function mount() {

        $agent = auth()->user()->agent;
        $this->fill([
            'company' => $agent->company,
            'full_name' => $agent->full_name,
            'phone' => $agent->phone,
            'email' => $agent->email,
            'address' => $agent->address,
            'division' => $agent->state,
            'city' => $agent->city,
            'postcode' => $agent->postcode,
        ]);
    }

    public function update() {
        $form = $this->validate([
            'company' => 'required|max:1024',
            'full_name' => 'required|max:255',
            'phone' => 'required|max:20',
            'email' => 'required|max:255|email',
            'address' => 'required|max:255',
            'division' => 'required|max:255|exists:bangladesh_divisions,name',
            'city' => 'required|max:255|exists:bangladesh_districts,name',
            'postcode' => 'required|max:10',
            'logo' => 'nullable|mimes:jpg,jpeg,png,gif|max:200', # 200kb
        ]);


        $agent = auth()->user()->agent;
        if ($form['email']) {
            if ($form['email'] != $agent->email) {
                if (Agent::where('email', $form['email'])->exists()) {
                    return session()->flash('failed', 'Email address already exists!');
                }
            }
        }

        if ($form['logo']) {
            $filename = 'logo_'. auth()->user()->id .'_'.time().'.'.$this->logo->extension();  
            $path = $this->logo->storePubliclyAs('files', $filename, 's3');
            $url = Storage::disk('s3')->url($path);
            

            $logo = $agent->logo();
            if ($logo != null) {
                $logo->update([
                    'file' => $url
                ]);
            }else {
                $agent->files()->create([
                    'file' => $url,
                    'file_key' => FileEnum::TYPE[2],
                ]);
            }
        }

        $agent['state'] = $form['division'];
        $agent->update($form);
        return session()->flash('success', 'Company information updated');
    }


    public function setEdit() {
        $this->edit = true;
    }

    public function unsetEdit() {
        $this->edit = false;
    }

    public function render() {
        return view('livewire.agent.settings.company');
    }

}
