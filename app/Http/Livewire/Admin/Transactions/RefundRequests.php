<?php

namespace App\Http\Livewire\Admin\Transactions;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Refund;
use App\Models\TransactionMethod;
use App\Enums\TransactionEnum;


class RefundRequests extends Component {

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
        if (!auth()->user()->can('admin_view refund request')) {
            return view('admin.includes.unauthorized');
        };


        $query = Refund::query();
        $query->orderBy('id', 'DESC');
        $query->where('status', TransactionEnum::STATUS['PENDING']);


        if (!empty($this->filter_input) && $this->filter_by == 'DATE') {
            $query->whereDate('created_at', date('Y-m-d', strtotime($this->filter_input)));
        }
        return view('livewire.admin.transactions.refund-requests', [
            'refunds' => $query->paginate(20)
        ]);
    }
}
