<?php

namespace App\Http\Livewire\Admin\Agent;

use Livewire\Component;
use App\Models\AgentRegistrationRequest;
use App\Enums\UserEnum;
use App\Events\RegisterAgent;

class AgentRequests extends Component {

    public function create($agent_id) {
        if (auth()->user()->type != UserEnum::TYPE['ADMIN']) {
            return session()->flash('failed', 'Only admin is allowed this operation!');
        }

        $agent = AgentRegistrationRequest::findOrFail($agent_id);
        $form = $agent->toArray();
        $form['password'] = UserEnum::getDefaultPassword();
        event(new RegisterAgent($form));

        $agent->delete();
        return session()->flash('success', 'Agent create success');
    }

    public function delete($agent_id) {
        if (auth()->user()->type != UserEnum::TYPE['ADMIN']) {
            return session()->flash('failed', 'Only admin is allowed this operation!');
        }

        $agent = AgentRegistrationRequest::findOrFail($agent_id);
        $agent->delete();
        $this->dispatchBrowserEvent('livewireCustomEvent', ['action' => 'scrollToTop']);
        return session()->flash('success', 'Delete success');
    }

    public function render() {
        return view('livewire.admin.agent.agent-requests', [
            'agents' => AgentRegistrationRequest::orderBy('id', 'DESC')->paginate(20)
        ]);
    }
}
