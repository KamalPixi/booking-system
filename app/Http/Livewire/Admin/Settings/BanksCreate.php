<?php

namespace App\Http\Livewire\Admin\Settings;

use Livewire\Component;
use App\Models\AdminBankAccount;
use App\Enums\UserEnum;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class BanksCreate extends Component {

    use WithFileUploads;

    // user model
    public $banks = [];

    // form inputs
    public $bank;
    public $account_no;
    public $account_name;
    public $branch;
    public $status = 1;
    public $logo;
    public $editing_id;

    protected $rules = [
        'bank' => 'required|max:255',
        'account_no' => 'required|max:255',
        'account_name' => 'required|max:255',
        'branch' => 'required',
        'status' => 'required',
        'logo' => 'nullable|mimes:jpg,jpeg,png',
    ];

    public function mount() {   
        $this->banks = AdminBankAccount::all();
    }

    // creates new unit
    public function create() {
        if (auth()->user()->type != UserEnum::TYPE['ADMIN']) { return abort(401); }

        $bank = $this->validate();
        $bankModel = AdminBankAccount::create($bank);

        if ($this->logo) {
            $filename = 'bank'. auth()->user()->id .'_'.time().'.'.$this->logo->extension();  
            $path = $this->logo->storePubliclyAs('files', $filename, 's3');
            $url = Storage::disk('s3')->url($path);
            $bankModel->logo()->create([
                'file' => $url
            ]);
        }
        
        $this->banks = AdminBankAccount::all();
        return session()->flash('success', 'Bank has created.');
    }

    // deactivate a user, but user id with 1 can't be deactivate 
    public function deactivate($id) {
        if (!auth()->user()->can('admin_edit bank')) {
            return session()->flash('failed', 'Permissions denied.');
        };

        $bank = AdminBankAccount::findOrFail($id);
        $bank->update(['status' => 0]);
        session()->flash('success', 'Bank has deactivated.');
    }

    public function edit($id) {
        if (!auth()->user()->can('admin_edit bank')) {
            return session()->flash('failed', 'Permissions denied.');
        };

        $bank = AdminBankAccount::findOrFail($id);
        $this->editing_id = $id;

        $this->fill($bank->toArray());
    }

    public function update() {
        if (!auth()->user()->can('admin_edit bank')) {
            return session()->flash('failed', 'Permissions denied.');
        };

        $bank = $this->validate();

        $bankModel = AdminBankAccount::find($this->editing_id);
        $bankModel->update($bank);

        if ($this->logo) {
            $filename = 'bank'. auth()->user()->id .'_'.time().'.'.$this->logo->extension();  
            $path = $this->logo->storePubliclyAs('files', $filename, 's3');
            $url = Storage::disk('s3')->url($path);
            if ($bankModel->logo) {
                $bankModel->logo->update([
                    'file' => $url
                ]);
            }else {
                $bankModel->logo()->create([
                    'file' => $url
                ]);
            }

        }

        session()->flash('success', 'Bank has updated.');
        $this->reset();
        $this->banks = AdminBankAccount::all();
    }

    public function delete() {
        return session()->flash('failed', 'Please deactivate the bank, instead of deleting!');   
    }


    public function render() {
        if (!auth()->user()->can('admin_create bank')) {
            return view('admin.includes.unauthorized');
        };

        return view('livewire.admin.settings.banks-create');
    }
}
