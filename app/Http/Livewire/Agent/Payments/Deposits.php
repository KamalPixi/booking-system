<?php

namespace App\Http\Livewire\Agent\Payments;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Deposit;
use App\Models\TransactionMethod;

class Deposits extends Component {

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
        if (!auth()->user()->can('agent_view deposit')) {
            return view('agent.includes.unauthorized');
        };

        $agent = auth()->user()->agent;
        
        $query = Deposit::query();
        $query->where([
            'depositable_type' => 'App\Models\Agent',
            'depositable_id' => $agent->id,
        ]);
        $query->orderBy('id', 'DESC');

        if (!empty($this->filter_input) && $this->filter_by == 'DEPOSIT_DATE') {
            $query->whereDate('created_at', date('Y-m-d', strtotime($this->filter_input)));
        }
        if (!empty($this->filter_input) && $this->filter_by == 'DEPOSIT_NO') {
            $query->where('transaction_no', $this->filter_input);
        }
        if (!empty($this->filter_input) && $this->filter_by == 'METHOD') {
            $query->where('method', $this->filter_input);
        }
        if (!empty($this->filter_input) && $this->filter_by == 'STATUS') {
            $query->where('status', $this->filter_input);
        }

        return view('livewire.agent.payments.deposits', [
            'deposits' => $query->paginate(10)
        ]);
    }
}
