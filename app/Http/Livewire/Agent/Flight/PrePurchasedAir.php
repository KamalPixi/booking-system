<?php

namespace App\Http\Livewire\Agent\Flight;

use Livewire\Component;
use App\Models\PrePurchasedAir as PrePurchasedAirModel;
use Livewire\WithPagination;

class PrePurchasedAir extends Component {

    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $airline = '';
    public $from = '';
    public $to = '';
    public $depart_date;

    public function render() {
        $query = PrePurchasedAirModel::query();
        $this->filter($query);

        return view('livewire.agent.flight.pre-purchased-air', [
            'flights' => $query->paginate(10)
        ]);
    }

    public function clearFilter() {
        $this->reset();
    }

    public function filter(&$query) {
        $query->where('count', '>', 0);

        if (!empty($this->airline)) {
            $query->where('airline', 'LIKE', '%' . $this->airline  . '%');
        }
        if (!empty($this->from)) {
            $query->where('from', 'LIKE', '%' . $this->from  . '%');
        }
        if (!empty($this->to)) {
            $query->where('to',  'LIKE', '%' . $this->to  . '%');
        }
        if (!empty($this->depart_date)) {
            $query->where('depart_date', date('Y-m-d', strtotime($this->depart_date)));
        }
    }
}
