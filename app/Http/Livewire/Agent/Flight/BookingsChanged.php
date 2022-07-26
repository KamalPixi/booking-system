<?php

namespace App\Http\Livewire\Agent\Flight;

use Livewire\Component;
use App\Models\AirTicket;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Enums\FlightEnum;

class BookingsChanged extends Component {

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

        $query = AirTicket::query();
        $this->filter($query);

        return view('livewire.agent.flight.bookings-changed', [
            'tickets' => $query->paginate(10)
        ]);
    }

    public function filter(&$query) {
        if (!auth()->user()->can('agent_view booking')) {
            return view('agent.includes.unauthorized');
        };

        $agent = auth()->user()->agent;

        if (!empty($this->filter_input) && $this->filter_by == 'BOOKING_DATE') {
            $query->whereDate('created_at', date('Y-m-d', strtotime($this->filter_input)));
        }
        if (!empty($this->filter_input) && $this->filter_by == 'AIR_PNR') {
            $query->where('confirmation_id', $this->filter_input);
        }

        $query->where([
            'agent_id' => $agent->id,
            'status' => FlightEnum::STATUS['CHANGED'],
        ]);
        $query->orderBy('id', 'DESC');
    }
}
