<?php

namespace App\Http\Livewire\Admin\Booking;

use Livewire\Component;
use App\Models\AirBooking;
use Livewire\WithPagination;

class BookingFlights extends Component {

    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $filter_input = '';
    public $filter_by = 'BOOKING_DATE';

    public function resetFilter() {
        $this->filter_input = '';
        $this->filter_by = 'BOOKING_DATE';
    }

    public function render() {
        if (!auth()->user()->can('admin_view flight booking')) {
            return view('admin.includes.unauthorized');
        };

        $query = AirBooking::query();
        $this->filter($query);

        return view('livewire.admin.booking.booking-flights', [
            'bookings' => $query->paginate(20)
        ]);
    }

    protected function filter(&$query) {
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
    }
}
