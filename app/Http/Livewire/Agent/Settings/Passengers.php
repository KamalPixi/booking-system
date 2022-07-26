<?php

namespace App\Http\Livewire\Agent\Settings;

use Livewire\Component;
use App\Models\Passenger;
use Livewire\WithPagination;

/**
 * Handles CRUD of unit
 */
class Passengers extends Component {
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $filter_input = '';
    public $filter_by = 'PASSPORT_NO';

    public function resetFilter() {
        $this->filter_input = '';
        $this->filter_by = 'PASSPORT_NO';
    }

    public function render() {
        $agent = auth()->user()->agent;
        
        $query = Passenger::query();
        $query->where('agent_id', $agent->id);
        $query->orderBy('id', 'DESC');

        if (!empty($this->filter_input) && $this->filter_by == 'DEPOSIT_DATE') {
            $query->whereDate('created_at', date('Y-m-d', strtotime($this->filter_input)));
        }

        return view('livewire.agent.settings.passengers', [
            'passengers' => $query->paginate()
        ]);
    }

}
