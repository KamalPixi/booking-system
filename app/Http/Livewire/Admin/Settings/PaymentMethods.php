<?php

namespace App\Http\Livewire\Admin\Settings;

use Livewire\Component;
use App\Models\TransactionMethod;
use App\Enums\UserEnum;

class PaymentMethods extends Component {

    // user model
    public $methods = [];
    public $editing_id;

    // form inputs
    public $title;
    public $key;
    public $fee;
    public $fee_type = 'PERCENTAGE';
    public $status = 1;
    public $remark = '';

    public $is_editing = false;

    protected $rules = [
        'title' => 'required|max:255',
        'key' => 'required|max:255',
        'fee' => 'required|numeric',
        'fee_type' => 'required',
        'remark' => 'required',
        'status' => 'required',
    ];

    public function mount() {   
        $this->methods = TransactionMethod::all();
    }

    // creates new unit
    public function create() {
        if (auth()->user()->type != UserEnum::TYPE['ADMIN']) { return abort(401); }

        $method = $this->validate();
        TransactionMethod::create($method);

        $this->methods = TransactionMethod::all();
        return session()->flash('success', 'Payment method has created.');
    }

    // deactivate a user, but user id with 1 can't be deactivate 
    public function deactivate($id) {
        if (auth()->user()->type != UserEnum::TYPE['ADMIN']) {
            return session()->flash('failed', 'Permissions denied.');
        };

        $method = TransactionMethod::findOrFail($id);
        $method->update(['status' => 0]);
        session()->flash('success', 'Bank has deactivated.');
    }

    public function edit($id) {
        if (!auth()->user()->can('admin_edit bank')) {
            return session()->flash('failed', 'Permissions denied.');
        };

        $this->is_editing = true;
        $bank = TransactionMethod::findOrFail($id);
        $this->editing_id = $id;

        $this->fill($bank->toArray());
    }

    public function update() {
        if (auth()->user()->type != UserEnum::TYPE['ADMIN']) {
            return session()->flash('failed', 'Permissions denied.');
        };


        $bank = $this->validate();
        $bankModel = TransactionMethod::find($this->editing_id);
        $bankModel->update($bank);

        session()->flash('success', 'Bank has updated.');
        $this->reset();
        $this->methods = TransactionMethod::all();
    }

    public function delete() {
        
    }


    public function render() {
        if (auth()->user()->type != UserEnum::TYPE['ADMIN']) {
            return view('admin.includes.unauthorized');
        };

        return view('livewire.admin.settings.payment-methods');
    }
}
