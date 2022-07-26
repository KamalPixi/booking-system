<?php

namespace App\Http\Livewire\Agent\Profile;

use Livewire\Component;

class AccountBalance extends Component {
    protected $listeners = ['ACCOUNT_BALANCE_UPDATED' =>  '$refresh'];

    public function render() {
        return view('livewire.agent.profile.account-balance');
    }
}
