<?php

namespace App\Http\Livewire\Admin\Booking;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\AirTicket;
use App\Enums\FlightEnum;

class BookingFlightIssueRequests extends Component {

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

        $query = AirTicket::query();
        $this->filter($query);

        return view('livewire.admin.booking.booking-flight-issue-requests', [
            'tickets' => $query->paginate(20)
        ]);
    }


    protected function filter(&$query) {
        if (!empty($this->filter_input) && $this->filter_by == 'BOOKING_DATE') {
            $query->whereDate('created_at', date('Y-m-d', strtotime($this->filter_input)));
        }
        if (!empty($this->filter_input) && $this->filter_by == 'AIR_PNR') {
            $query->where('confirmation_id', $this->filter_input);
        }
        $query->where([
            'status' => FlightEnum::STATUS['PENDING'],
        ]);
        $query->orderBy('id', 'DESC');
    }
}
