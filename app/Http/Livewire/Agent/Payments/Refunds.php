<?php

namespace App\Http\Livewire\Agent\Payments;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Refund;
use App\Models\TransactionMethod;
use App\Enums\TransactionEnum;

class Refunds extends Component {

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
        $query = Refund::query();
        $query->orderBy('id', 'DESC');
        $query->where('agent_id', auth()->user()->agent->id);

        if (!empty($this->filter_input) && $this->filter_by == 'DATE') {
            $query->whereDate('created_at', date('Y-m-d', strtotime($this->filter_input)));
        }

        return view('livewire.agent.payments.refunds', [
            'refunds' => $query->paginate(10)
        ]);
    }
}
