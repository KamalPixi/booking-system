<?php

namespace App\Http\Livewire\Admin\Payments;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\BookingPartialRequest;
use \App\Enums\TransactionEnum;

class PartialRequests extends Component {

    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $filter_input = TransactionEnum::STATUS['PENDING'];
    public $filter_by = 'STATUS';

    public function resetFilter() {
        $this->filter_input = '';
        $this->filter_by = 'STATUS';
    }

    public function render() {
        if (!auth()->user()->can('admin_view partial payment request')) {
            return view('admin.includes.unauthorized');
        };

        $query = BookingPartialRequest::query();
        $query->orderBy('id', 'DESC');

        if (!empty($this->filter_input) && $this->filter_by == 'REQUEST_DATE') {
            $query->whereDate('created_at', date('Y-m-d', strtotime($this->filter_input)));
        }
        if (!empty($this->filter_input) && $this->filter_by == 'STATUS') {
            $query->where('status', $this->filter_input);
        }

        return view('livewire.admin.payments.partial-requests', [
            'requests' => $query->paginate(20)
        ]);
    }
}
