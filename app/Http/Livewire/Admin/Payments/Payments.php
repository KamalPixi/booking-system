<?php

namespace App\Http\Livewire\Admin\Payments;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Payment;
use App\Models\TransactionMethod;

class Payments extends Component {

    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $filter_input = '';
    public $filter_by = 'PAYMENT_DATE';
    public $transactionMethods = [];

    public function mount() {
        $this->transactionMethods = TransactionMethod::all();
    }


    public function resetFilter() {
        $this->filter_input = '';
        $this->filter_by = 'PAYMENT_DATE';
    }

    public function render() {
        if (!auth()->user()->can('admin_view payment')) {
            return view('admin.includes.unauthorized');
        };

        $query = Payment::query();
        $query->orderBy('id', 'DESC');

        if (!empty($this->filter_input) && $this->filter_by == 'PAYMENT_DATE') {
            $query->whereDate('created_at', date('Y-m-d', strtotime($this->filter_input)));
        }
        if (!empty($this->filter_input) && $this->filter_by == 'METHOD') {
            $query->where('method', $this->filter_input);
        }
        if (!empty($this->filter_input) && $this->filter_by == 'STATUS') {
            $query->where('status', $this->filter_input);
        }

        return view('livewire.admin.payments.payments', [
            'payments' => $query->paginate(20)
        ]);
    }
}
