<?php

namespace App\Http\Livewire\Agent\Auth;

use Livewire\Component;
use App\Models\AgentRegistrationRequest;

class Register extends Component {

    public $full_name = '';
    public $company = '';
    public $address = '';
    public $city = '';
    public $division = '';
    public $postcode = '';
    public $phone = '';
    public $email = '';

    protected $rules = [
        'email' => 'bail|required|email|max:255|unique:agent_registration_requests,email',
        'full_name' => 'required|max:50',
        'company' => 'required|max:100',
        'address' => 'required|max:255',
        'division' => 'required|max:255|exists:bangladesh_divisions,name',
        'city' => 'required|max:255|exists:bangladesh_districts,name',
        'postcode' => 'required|max:10',
        'phone' => 'bail|required|digits_between:9,12',
    ];

    public function submit() {
        $this->dispatchBrowserEvent('contentChanged');
        $body = $this->validate();
        $body['state'] = $body['division'];
        AgentRegistrationRequest::create($body);
        $this->reset();
        return session()->flash('success', 'Request has been received. We\'ll notify you soon.');
    }

    public function render() {
        return view('livewire.agent.auth.register');
    }
}
