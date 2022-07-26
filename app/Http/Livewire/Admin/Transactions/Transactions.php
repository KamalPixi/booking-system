<?php

namespace App\Http\Livewire\Admin\Transactions;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Transaction;
use App\Models\TransactionMethod;
use App\Enums\UserEnum;

class Transactions extends Component {

    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $filter_input = '';
    public $filter_by = 'DATE';
    public $transactionMethods = [];

    public function mount() {
        $this->transactionMethods = TransactionMethod::all();
    }


    public function resetFilter() {
        $this->filter_input = '';
        $this->filter_by = 'DATE';
    }

    public function render() {
        if (auth()->user()->type != UserEnum::TYPE['ADMIN']) { 
            if (!auth()->user()->can('admin_view transactions')) {
                return view('admin.includes.unauthorized');
            };
        }

        $query = Transaction::query();
        $query->orderBy('id', 'DESC');

        if (!empty($this->filter_input) && $this->filter_by == 'DATE') {
            $query->whereDate('created_at', date('Y-m-d', strtotime($this->filter_input)));
        }
        if (!empty($this->filter_input) && $this->filter_by == 'PURPOSE') {
            $query->where('purpose', $this->filter_input);
        }
        if (!empty($this->filter_input) && $this->filter_by == 'METHOD') {
            $query->where('method', $this->filter_input);
        }
        if (!empty($this->filter_input) && $this->filter_by == 'SIGN') {
            $query->where('sign', $this->filter_input);
        }

        return view('livewire.admin.transactions.transactions', [
            'transactions' => $query->paginate(20)
        ]);
    }
}
