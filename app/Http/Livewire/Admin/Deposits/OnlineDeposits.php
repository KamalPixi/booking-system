<?php

namespace App\Http\Livewire\Admin\Deposits;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Deposit;
use App\Models\TransactionMethod;
use App\Enums\TransactionEnum;


class OnlineDeposits extends Component {
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $filter_input = '';
    public $filter_by = 'DEPOSIT_DATE';

    public $transactionMethods = [];

    public function mount() {
        $this->transactionMethods = TransactionMethod::all();
    }

    public function resetFilter() {
        $this->filter_input = '';
        $this->filter_by = 'DEPOSIT_DATE';
    }

    public function render() {
        if (!auth()->user()->can('admin_view deposit')) {
            return view('admin.includes.unauthorized');
        };

        $query = Deposit::query();
        $query->orderBy('id', 'DESC');
        $query->where('method', TransactionEnum::METHOD['ONLINE_PAYMENT']);

        if (!empty($this->filter_input) && $this->filter_by == 'DEPOSIT_DATE') {
            $query->whereDate('created_at', date('Y-m-d', strtotime($this->filter_input)));
        }
        if (!empty($this->filter_input) && $this->filter_by == 'DEPOSIT_NO') {
            $query->where('transaction_no', $this->filter_input);
        }
        if (!empty($this->filter_input) && $this->filter_by == 'STATUS') {
            $query->where('status', $this->filter_input);
        }

        return view('livewire.admin.deposits.online-deposits', [
            'deposits' => $query->paginate(20)
        ]);
    }

}
