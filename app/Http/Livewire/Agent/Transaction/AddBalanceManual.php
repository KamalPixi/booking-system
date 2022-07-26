<?php

namespace App\Http\Livewire\Agent\Transaction;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\TransactionMethod;
use App\Models\AdminBankAccount;
use App\Models\Deposit;
use App\Enums\TransactionEnum;
use App\Helpers\RefHelper;
use Illuminate\Support\Facades\Storage;


class AddBalanceManual extends Component {
    use WithFileUploads;

    public $accounts = [];
    public $deposited_to_account_text = '';

    public $amount = '';
    public $deposit_method_id = '';
    public $deposit_method_key = '';
    public $deposited_to_account_id = '';
    public $service_charge = '';
    public $deposit_amount = '';
    public $attachment = '';
    public $remark = '';

    public $depositMethods;
    public $transactionMethod;
    public $type = '';

    protected $listeners = ['ONFOCUSOUT' => 'handleFocusOut'];
    protected $rules = [
        'amount' => 'bail|required|numeric|digits_between:3,12|min:100',
        'deposit_method_id' => 'required|exists:transaction_methods,id',
        'deposited_to_account_id' => 'required|numeric|digits_between:1,11',
        'attachment' => 'bail|required|mimes:jpg,jpeg,png,pdf,docx|max:3072',
        'remark' => 'nullable|max:255',
    ];

    public function mount() {
        $this->depositMethods = TransactionMethod::where('status', 1)->get();
    }
    
    public function updated($propertyName) {
        if ($propertyName == 'deposit_method_key') {
            $this->deposited_to_account_text = '';
            $this->deposited_to_account_id = '';
            $this->calculate();
            $this->showDepositAccounts();
        }
        if ($propertyName == 'amount') {
            $this->deposited_to_account_text = '';
            $this->calculate();
        }
    }

    public function setDepositedId($id, $type, $method_id) {
        if ($type == 'BANK') {
            $account = AdminBankAccount::find($id);
            $this->deposited_to_account_id = $id;
            $this->deposit_method_id = $method_id;
            $this->type = 'BANK';
            $this->deposited_to_account_text = $account->bank 
            . "\n" 
            . $account->account_no 
            . "\n" 
            . $account->account_name;

            $this->calculate();
            return;
        }

        $method = TransactionMethod::find($id);
        $this->deposit_method_id = $method_id;
        $this->deposited_to_account_id = $id;
        $this->type = 'NO_BANK';
        $this->deposited_to_account_text = $method->title . '(' . $method->remark . ')';

        $this->calculate();
    }

    public function calculate() {
        if (empty($this->deposit_method_id) || !is_numeric($this->amount)) {
            return;
        }
        $this->transactionMethod = TransactionMethod::find($this->deposit_method_id);
        $this->service_charge = number_format($this->transactionMethod->fee, 2);
        # percentage
        if ($this->transactionMethod->fee_type == TransactionEnum::METHOD_FEE_TYPE['PERCENTAGE']) {
            $this->deposit_amount = number_format(floatval($this->amount) - (floatval($this->amount) / 100 * $this->transactionMethod->fee), 2, '.', '');
            return;
        }
        #fixed
        $this->deposit_amount = number_format($this->amount - $this->transactionMethod->fee, 2, '.', '');
    }

    public function showDepositAccounts() {
        $this->accounts = [];

        if (!empty($this->deposit_method_key)) {
            $transactionMethods = TransactionMethod::where('key', $this->deposit_method_key)->get();
                        
            foreach ($transactionMethods as $transactionMethod) {
                if (in_array($transactionMethod->key, [
                    TransactionEnum::METHOD['BANK_DEPOSIT'],
                    TransactionEnum::METHOD['ONLINE_BANK_TRANSFER']
                ])) {
                    $bankAccounts = AdminBankAccount::where('status', 1)->get();
                    foreach ($bankAccounts as $bankAccount) {
                        $this->accounts[] = [
                            'id' => $bankAccount->id,
                            'bank' => $bankAccount->bank,
                            'account_name' => $bankAccount->account_name,
                            'account_no' => $bankAccount->account_no,
                            'branch' => $bankAccount->branch,
                            'type' => 'BANK',
                            'method_id' => $transactionMethod->id,
                        ];
                    }
                    continue;
                }

                $this->accounts[] = [
                    'id' => $transactionMethod->id,
                    'bank' => $transactionMethod->key,
                    'account_name' => $transactionMethod->title,
                    'account_no' => $transactionMethod->remark,
                    'branch' => '',
                    'type' => 'NOT_BANK',
                    'method_id' => $transactionMethod->id,
                ];
            }

        }
    }

    public function handleFocusOut($payload) {
        if ($payload == 'DepositedToAccount') {
            $this->accounts = [];
        }
        // $this->calculate();
    }

    public function submit() {
        if (!auth()->user()->can('agent_create deposit')) {
            return session()->flash('failed', 'Unauthorized!');
        };

        $form = $this->validate();
        $agent = auth()->user()->agent;
        if (!$agent) {
            return session()->flash('failed', 'Agent not found!');
        }

        # calculate deposit fee
        $transactionMethod = TransactionMethod::find($this->deposit_method_id);

        $fee = 0;
        $finalDepositAmount = 0;
        if ($transactionMethod->fee_type == TransactionEnum::METHOD_FEE_TYPE['PERCENTAGE']) {
            $fee = $form['amount'] / 100 * $transactionMethod->fee;
            $finalDepositAmount = number_format($form['amount'] - $fee, 2, '.', '');
        }else {
            $finalDepositAmount = number_format($form['amount'] - $transactionMethod->fee, 2, '.', '');
            $fee = $transactionMethod->fee;
        }

        # store deposit
        $deposit = $agent->deposits()->create([
            'transaction_no' => RefHelper::createDepositRef(),
            'deposited_admin_bank_account_id' => $form['deposited_to_account_id'],
            'amount' => $finalDepositAmount,
            'fee' => $fee,
            'currency' => TransactionEnum::CURRENCY['BDT'],
            'method' => $transactionMethod->key,
            'status' => TransactionEnum::STATUS['PENDING'],
            'deposited_by' => auth()->user()->id,
            'remark' => $form['remark']
        ]);


        $filename = 'deposit_'. auth()->user()->id .'_'.time().'.'.$this->attachment->extension();  
        $path = $this->attachment->storePubliclyAs('files', $filename, 's3');
        
        $url = Storage::disk('s3')->url($path);
        $deposit->files()->create([
            'file' => $url
        ]);

        $this->reset([
            'amount',
            'deposit_amount',
            'deposit_method_id',
            'deposited_to_account_id',
            'attachment',
            'remark',
            'deposited_to_account_text',
            'deposit_method_key',
        ]);

        return session()->flash('success', 'Deposit request submitted');
    }

    public function render() {
        return view('livewire.agent.transaction.add-balance-manual');
    }
}
