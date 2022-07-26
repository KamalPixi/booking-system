<?php

namespace App\Http\Livewire\Admin\Agent;

use Livewire\Component;
use App\Models\Agent;
use App\Models\User;
use App\Enums\UserEnum;
use App\Events\RegisterAgent;
use App\Events\AgentDelete;
use Illuminate\Support\Facades\Hash;


class Agents extends Component {

    public $edit = false;
    public $agent_id = '';

    public $company = '';
    public $full_name = '';
    public $phone = '';
    public $address = '';
    public $state = '';
    public $city = '';
    public $postcode = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $status = 1;

    protected $rules = [
        'company' => 'nullable:max:255',
        'full_name' => 'nullable:max:255',
        'phone' => 'required:max:255',
        'address' => 'required:max:255',
        'state' => 'required:max:255',
        'city' => 'required:max:255',
        'postcode' => 'required:max:255',
        'email' => 'bail|required|email|max:255|unique:agents,email|unique:users,email',
        'password' => 'required:|confirmed|max:255',
        'status' => 'nullable:max:255',
    ];
    

    public function addAgent() { 
        if (auth()->user()->type != UserEnum::TYPE['ADMIN']) {
            return session()->flash('failed', 'Only admin is allowed this operation!');
        }
        $form = $this->validate();
        event(new RegisterAgent($form));
    }


    public function updateAgent() {
        if (auth()->user()->type != UserEnum::TYPE['ADMIN']) {
            return session()->flash('failed', 'Only admin is allowed this operation!');
        }
        $form = $this->validate([
            'company' => 'nullable:max:255',
            'full_name' => 'nullable:max:255',
            'phone' => 'nullable:max:255',
            'address' => 'nullable:max:255',
            'state' => 'nullable:max:255',
            'city' => 'nullable:max:255',
            'postcode' => 'nullable:max:255',
            'email' => 'bail|required|email|max:255',
            'password' => 'nullable:|confirmed|max:255',
            'status' => 'nullable:max:255',
        ]);

        $agent = Agent::findOrFail($this->agent_id);
        $user = User::where('email', $agent->email)->firstOrFail();
        if ($form['email'] != $agent->email) {
            if (Agent::where('email', $form['email'])->exists()) {
                return session()->flash('failed', 'Email already exists!');
            }
            $user->email = $form['email'];
        }

        if (!empty($form['password'])) {
            $user->password = Hash::make($form['password']);
            $user->save();
        }

        $agent->update($form);
        $this->edit = false;
        return session()->flash('success', 'Agent update success.');
    }

    public function cancelUpdate() {
        $this->edit = false;
        $this->reset();
    }


    public function delete($agent_id) {
        if (auth()->user()->type != UserEnum::TYPE['ADMIN']) {
            return session()->flash('failed', 'Only admin is allowed this operation!');
        }

        $agent = Agent::findOrFail($agent_id);
        event(new AgentDelete($agent));
        $this->dispatchBrowserEvent('livewireCustomEvent', ['action' => 'scrollToTop']);
    }

    public function setEdit($agent_id) {
        if (auth()->user()->type != UserEnum::TYPE['ADMIN']) {
            $this->dispatchBrowserEvent('livewireCustomEvent', ['action' => 'scrollToTop']);
            return session()->flash('failed', 'Only admin is allowed this operation!');
        }

        $this->agent_id = $agent_id;
        $agent = Agent::findOrFail($agent_id);
        $this->fill($agent->toArray());
        $this->edit = true;
        $this->dispatchBrowserEvent('livewireCustomEvent', ['action' => 'scrollToTop']);
        return session()->flash('info', 'Start editing! Agent info has filled in the form below.');
    }


    public function render() {
        return view('livewire.admin.agent.agents', [
            'agents' => Agent::paginate(50)
        ]);
    }
}
