<?php

namespace App\Http\Livewire\Agent\Holiday;

use Livewire\Component;

class HolidaySearch extends Component {

    public $cities = [1];

    public function addCity() {
        $this->cities[] = 1;
    }

    public function removeCity() {
        array_pop($this->cities);
    }

    public function render() {
        return view('livewire.agent.holiday.holiday-search');
    }

}
