<?php

namespace App\Http\Livewire\Agent\Transaction;

use Livewire\Component;
use App\Models\TransactionMethod;
use App\Enums\TransactionEnum;

class AddBalance extends Component {

    public $amount = '';
    public $service_charge = '';
    public $final_deposit_amount = '';

    public $transactionMethod;
    protected $rules = [
        'amount' => 'bail|required|numeric|min:100|digits_between:3,12'
    ];

    public function mount() {
        $this->transactionMethod = TransactionMethod::where([
            'key' => TransactionEnum::METHOD['ONLINE_PAYMENT'],
            'status' => 1
        ])->first();
    }

    public function updated($propertyName) {
        $this->reset([
            'service_charge',
            'final_deposit_amount'
        ]);

        if (!is_numeric($this->amount)) {
            return session()->flash('failed', 'Please enter valid amount!');
        }
        
        $this->service_charge = number_format($this->transactionMethod->fee, 2);
        # percentage
        if ($this->transactionMethod->fee_type == TransactionEnum::METHOD_FEE_TYPE['PERCENTAGE']) {
            $this->final_deposit_amount = number_format($this->amount - ($this->amount / 100 * $this->transactionMethod->fee), 2, '.', '');
            return;
        }

        #fixed
        $this->final_deposit_amount = $this->amount - $this->transactionMethod->fee;
    }

    public function submit() {
        if (!auth()->user()->can('agent_create deposit')) {
            return session()->flash('failed', 'Unauthorized!');
        };

        $this->validate();
        $this->dispatchBrowserEvent('livewireCustomEvent', [
            'action' => 'submitForm',
            'formId' => 'add-balance'
        ]);
    }

    public function render() {
        return view('livewire.agent.transaction.add-balance');
    }
}
