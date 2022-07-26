<?php

namespace App\Http\Livewire\Admin\Deposits;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Deposit;
use App\Models\TransactionMethod;
use App\Enums\TransactionEnum;
use App\Events\Deposited;


class ManualDeposits extends Component {
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $filter_input = '';
    public $filter_by = 'DEPOSIT_DATE';

    # belongs to deposit handle
    public $depositModel = '';
    public $deposit_id = '';
    public $showDepositHandleForm = false;
    public $amount = 0;
    public $fee = 0;
    public $status = '';
    public $admin_remark = '';

    public $transactionMethods = [];

    public function mount() {
        $this->transactionMethods = TransactionMethod::all();
    }

    public function updated($propertyName) {
        if ($propertyName == 'amount') {
            $this->depositModel = Deposit::findOrFail($this->deposit_id);
            $this->fee = $this->depositModel->amount - $this->amount;
        }
    }

    public function resetFilter() {
        $this->filter_input = '';
        $this->filter_by = 'DEPOSIT_DATE';
    }
    

    public function showDepositHandleForm($deposit_id) {
        if (!auth()->user()->can('admin_edit deposit')) {
            return session()->flash('failed', 'Access denied!');
        };

        $this->deposit_id = $deposit_id;
        $this->showDepositHandleForm = true;
        $this->depositModel = Deposit::findOrFail($deposit_id);
        $this->amount = $this->depositModel->amount;

    }
    public function hideDepositHandleForm() {
        if (!auth()->user()->can('admin_edit deposit')) {
            return session()->flash('failed', 'Access denied!');
        };

        $this->deposit_id = '';
        $this->showDepositHandleForm = false;
    }
    public function handleDepositForm() {
        if (!auth()->user()->can('admin_edit deposit')) {
            return session()->flash('failed', 'Access denied!');
        };

        $allowedStatus = [
            TransactionEnum::STATUS['COMPLETED'],
            TransactionEnum::STATUS['CANCELED'],
        ];

        $form = $this->validate([
            'status' => 'required|in:' . implode(',', $allowedStatus),
            'admin_remark' => 'nullable|max:1000',
            'amount' => 'bail|required|numeric|min:0|digits_between:0,12',
            'fee' => 'bail|required|numeric|min:0|digits_between:0,12'
        ]);

        $deposit = Deposit::findOrFail($this->deposit_id);
        # more than amount is not allowed to be deposited
        if ($this->amount > $deposit->amount) {
            return session()->flash('failed', 'More than deposit amount is not allowed!');
        };

        $deposit->update($form);
                
        # will create a transaction, add amount to agent account balance
        if ($form['status'] == TransactionEnum::STATUS['COMPLETED']) {
            event(new Deposited($deposit));
        }
        
        $this->hideDepositHandleForm();
        return session()->flash('success', 'Deposit status updated');
    }
    


    public function render() {
        if (!auth()->user()->can('admin_view deposit')) {
            return view('admin.includes.unauthorized');
        };

        $query = Deposit::query();
        $query->orderBy('id', 'DESC');
        $query->where('status', TransactionEnum::STATUS['PENDING']);
        $query->wherein('method', [
            TransactionEnum::METHOD['NAGAD'],
            TransactionEnum::METHOD['BANK_DEPOSIT'],
            TransactionEnum::METHOD['CASH'],
            TransactionEnum::METHOD['BKASH'],
            TransactionEnum::METHOD['ONLINE_BANK_TRANSFER']
        ]);


        // $query->orWhere(function ($query) {
        //     $query->where('method', TransactionEnum::METHOD['BANK_DEPOSIT']);
        // });
        // $query->orWhere(function ($query) {
        //     $query->where('method', TransactionEnum::METHOD['CASH']);
        // });
        // $query->orWhere(function ($query) {
        //     $query->where('method', TransactionEnum::METHOD['BKASH']);
        // });
        // $query->orWhere(function ($query) {
        //     $query->where('method', TransactionEnum::METHOD['NAGAD']);
        // });
        // $query->orWhere(function ($query) {
        //     $query->where('method', TransactionEnum::METHOD['ONLINE_BANK_TRANSFER']);
        // });

        if (!empty($this->filter_input) && $this->filter_by == 'DEPOSIT_DATE') {
            $query->whereDate('created_at', date('Y-m-d', strtotime($this->filter_input)));
        }
        if (!empty($this->filter_input) && $this->filter_by == 'DEPOSIT_NO') {
            $query->where('transaction_no', $this->filter_input);
        }

        return view('livewire.admin.deposits.manual-deposits', [
            'deposits' => $query->paginate(20)
        ]);
    }

}
