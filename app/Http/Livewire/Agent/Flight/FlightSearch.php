<?php

namespace App\Http\Livewire\Agent\Flight;

use Livewire\Component;

class FlightSearch extends Component {

    public $type = 'ONE_WAY';

    public $from = '';
    public $to = '';
    public $depart_date = '';
    public $return_date = '';
    public $class = 'Y';
    public $people = [
        'adults' => 1,
        'children' => 0,
        'infants' => 0,
    ];
    public $children_dob = [];
    
    public $multi_cities = [
        [
            'from' => '',
            'to' => '',
            'depart_date' => ''
        ],
        [
            'from' => '',
            'to' => '',
            'depart_date' => ''
        ]
    ];

    public function adultIncrement() {
        $this->people['adults']++;
    }
    public function adultDecrement() {
        if ($this->people['adults'] < 2) return;
       $this->people['adults']--;
    }

    public function childrenIncrement() {
        $this->people['children']++;
    }
    public function childrenDecrement() {
        if ($this->people['children'] < 1) return;
       $this->people['children']--;
    }

    public function infantIncrement() {
        if ($this->people['infants'] < $this->people['adults']) {
            $this->people['infants']++;
        }
    }
    public function infantDecrement() {
        if ($this->people['infants'] < 1) return;
       $this->people['infants']--;
    }

    public function addMultiCity() {
        $this->multi_cities[] = [
            'from' => '',
            'to' => '',
            'depart_date' => ''
        ];
    }
    public function removeMultiCity() {
        array_pop($this->multi_cities);
    }

    public function search() {
        if (!auth()->user()->can('agent_search')) {
            abort(401);
            return;
        };

        // validate
        $this->validate([
            'people.adults' => 'required',
            'people.children' => 'required ',
            'people.infants' => 'required',
            'class' => 'required|max:1',
            'type' => 'required|max:10',
        ]);

        if ($this->type == 'ONE_WAY') {
            $this->validate([
                'from' => 'required|min:3|max:3',
                'to' => 'required|min:3|max:3',
                'depart_date' => 'required',
            ]);
        }

        if ($this->type == 'ROUND_TRIP') {
            $this->validate([
                'from' => 'required|min:3|max:3',
                'to' => 'required|min:3|max:3',
                'depart_date' => 'required',
                'return_date' => 'required',
            ]);
        }

        if ($this->type == 'MULTI_CITY') {
            $this->validate([
                'multi_cities.*.from' => 'required',
                'multi_cities.*.to' => 'required',
                'multi_cities.*.depart_date' => 'required',
            ]);
        }

        // prepare data redirect to result page
        return redirect()->route('b2b.flight.search', [
            'type' => $this->type,
            'from' => $this->from,
            'to' => $this->to,
            'depart_date' => $this->depart_date,
            'return_date' => $this->return_date,
            'class' => $this->class,
            'people' => $this->people,
            'multi_cities' => $this->multi_cities,
            'children_dob' => $this->children_dob,
        ]);
    }

    public function render() {
        return view('livewire.agent.flight.flight-search-alpine');
    }
}
