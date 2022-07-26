<?php

namespace App\Http\Livewire\Admin\Booking;

use Livewire\Component;
use Livewire\WithPagination;

class BookingUmrahIssueRequests extends Component {

    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $filter_input = '';
    public $filter_by = 'BOOKING_DATE';

    public function resetFilter() {
        $this->filter_input = '';
        $this->filter_by = 'BOOKING_DATE';
    }

    public function render() {
        return view('livewire.admin.booking.booking-umrah-issue-requests');
    }
}
