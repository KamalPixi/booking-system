<?php

namespace App\Http\Livewire\Admin\Settings;

use Livewire\Component;
use App\Models\AdminFee;
use App\Enums\UserEnum;
use App\Enums\AdminFeeEnum;


class AdminFees extends Component {

    // user model
    public $fees = [];
    public $editing_id;

    // form inputs
    public $name;
    public $fee_key;
    public $fee_key_sub;
    public $fee;
    public $type = 'PERCENTAGE';

    protected $rules = [
        'name' => 'required|max:255',
        'fee_key' => 'required|max:255',
        'fee_key_sub' => 'nullable|max:255',
        'fee' => 'required',
        'type' => 'required',
    ];

    public function mount() {   
        $this->fees = AdminFee::all();
    }

    // creates new unit
    public function create() {
        if (auth()->user()->type != UserEnum::TYPE['ADMIN']) {
            return session()->flash('failed', 'Permissions denied.');
        };

        $data = $this->validate();
        $adminFee = AdminFee::firstOrCreate($data);
        
        $this->fees = AdminFee::all();
        return session()->flash('success', 'Commission/Fee has created.');
    }


    public function edit($id) {
        if (auth()->user()->type != UserEnum::TYPE['ADMIN']) {
            return session()->flash('failed', 'Permissions denied.');
        };

        $adminModel = AdminFee::findOrFail($id);
        $this->editing_id = $id;

        $this->fill($adminModel->toArray());
    }

    public function update() {
        if (auth()->user()->type != UserEnum::TYPE['ADMIN']) {
            return session()->flash('failed', 'Permissions denied.');
        };

        $data = $this->validate();
        $adminFee = AdminFee::find($this->editing_id);
        $adminFee->update($data);

        session()->flash('success', 'Commission/Fee has updated.');
        $this->reset();
        $this->fees = AdminFee::all();
        $this->editing_id = '';
    }

    public function delete($id) {
        $adminFee = AdminFee::findOrFail($id);
        if (in_array($adminFee->id, [1,2])) {
            return session()->flash('failed', 'Can\'t delete!');
        }


        $adminFee->delete();
        session()->flash('success', 'Commission/Fee has deleted.');
        $this->fees = AdminFee::all();
    }


    public function render() {
        if (auth()->user()->type != UserEnum::TYPE['ADMIN']) {
            return session()->flash('failed', 'Permissions denied.');
        };

        return view('livewire.admin.settings.admin-fees');
    }
}
