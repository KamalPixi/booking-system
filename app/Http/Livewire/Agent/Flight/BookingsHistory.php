<?php

namespace App\Http\Livewire\Agent\Flight;

use Livewire\Component;
use App\Models\AirBooking;
use Livewire\WithPagination;

class BookingsHistory extends Component {

    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $filter_input = '';
    public $filter_by = 'BOOKING_DATE';

    public function resetFilter() {
        $this->filter_input = '';
        $this->filter_by = 'BOOKING_DATE';
    }

    public function render() {
        if (!auth()->user()->can('agent_view booking')) {
            return view('agent.includes.unauthorized');
        };

        $agent = auth()->user()->agent;

        $query = AirBooking::query();
        $query->where('air_bookingable_type', 'App\Models\Agent')->where('air_bookingable_id', $agent->id);
        $query->orderBy('created_at', 'DESC');
        if (!empty($this->filter_input) && $this->filter_by == 'BOOKING_DATE') {
            $query->whereDate('created_at', date('Y-m-d', strtotime($this->filter_input)));
        }
        if (!empty($this->filter_input) && $this->filter_by == 'REF') {
            $query->where('reference', $this->filter_input);
        }
        if (!empty($this->filter_input) && $this->filter_by == 'PNR') {
            $query->where('confirmation_id', $this->filter_input);
        }
        if (!empty($this->filter_input) && $this->filter_by == 'AIR_PNR') {
            $query->where('airline_confirmation_id', $this->filter_input);
        }
        if (!empty($this->filter_input) && $this->filter_by == 'STATUS') {
            $query->where('status', $this->filter_input);
        }
        if (!empty($this->filter_input) && $this->filter_by == 'PAYMENT_STATUS') {
            $query->where('payment_status', $this->filter_input);
        }

        return view('livewire.agent.flight.bookings-history', [
            'bookings' => $query->paginate(10)
        ]);
    }
}
