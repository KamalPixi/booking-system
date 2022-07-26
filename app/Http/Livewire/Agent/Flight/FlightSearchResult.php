<?php

namespace App\Http\Livewire\Agent\Flight;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Services\FlightSearchService;
use App\Helpers\UtilityHelper;
use Carbon\Carbon;


class FlightSearchResult extends Component {

    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    
    public $session_key = '';

    # loading state
    public $readyToLoad = false;
    public $fetchError = false;

    protected $queryString = [
        'type',
        'from',
        'to',
        'depart_date',
        'return_date',
        'class',
        'people',
        'multi_cities',
        'children_dob'
    ];

    # flight parameters
    public $type = '';
    public $from = '';
    public $to = '';
    public $depart_date = '';
    public $return_date = '';
    public $class = '';
    public $people = [];
    public $multi_cities = [];
    public $children_dob = [];

    # flight ticket holder
    public $flights = [];

    # belongs to search modify functionality
    protected $listeners = [
        'searchFlight' => 'searchFlight'
    ];


    # belongs to search filter
    public $api_source = [];
    public $stops = [];
    public $airline = [];
    public $airlines = [];


    # belongs to search modify
    public $show_modify_form = false;

    public function updated($propertyName) {
        $this->filter();
    }

    public function addMultiCity() {
        $this->multi_cities[] = [
            'from' => '',
            'to' => '',
            'depart_date' => ''
        ];
    }

    # automatically get called when view is rendered!
    public function searchFlight(FlightSearchService $service) {
        $this->resetPage();

        if (!auth()->user()->can('agent_search')) {
            abort(401);
            return;
        };
        $this->mapDates();

        # flag, if api request has any errors
        $this->fetchError = false;

        # OneWay
        if ($this->type == 'ONE_WAY') {
            if (!$form = $this->validateOneWay()) { return; }
            try {
                $this->flights = $service->fetchOneWayFlights($form);
                $this->setFlightsInSession();
            } catch (\ErrorException $ex) {
                $this->fetchError = true;
                session()->flash('failed', $ex->getMessage());
            }
        }

        # RoundTrip
        if ($this->type == 'ROUND_TRIP') {
            if (!$form = $this->validateRoundTrip()) { return; }

            try {
                $this->flights = $service->fetchRoundTripFlights($form);
                $this->setFlightsInSession();
            } catch (\ErrorException $ex) {
                $this->fetchError = true;
                session()->flash('failed', $ex->getMessage());
            }
        }

        # Multi-city
        if ($this->type == 'MULTI_CITY') {
            if (!$form = $this->validateMultiCity()) { return; }

            try {
                $this->flights = $service->fetchMultiCityFlights($form);
                $this->setFlightsInSession();
            } catch (\ErrorException $ex) {
                $this->fetchError = true;
                session()->flash('failed', $ex->getMessage());
            }
        }
        
        $this->setAirlinesFromResult();
        $this->filterLowToHighPrice();
        $this->readyToLoad = true;
    }

    public function book($session_key, $key) {
        return redirect()->route('b2b.flight.search.details', ['session_key' => $session_key, 'session_index' => $key, 'children_dob' => $this->children_dob]);
    }

    # store search result flights in session
    public function setFlightsInSession() {
        $this->session_key = UtilityHelper::randomString();
        session()->put($this->session_key, $this->flights);
    }
    # empty search result from session
    public function removeFlightsFromSession() {
        session(['flights', []]);
    }


    
    /**
     * Belongs to search modify
     */
    public function modifySearch() {
        $this->readyToLoad = false;
        $this->emit('searchFlight');
    }

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
    public function removeMultiCity() {
        array_pop($this->multi_cities);
    }
    /** ends search modify */



    protected function validateRoundTrip() {
        return $this->validate([
            'from' => 'required|min:3|max:3',
            'to' => 'required|min:3|max:3',
            'depart_date' => 'required',
            'return_date' => 'required',
            'people.adults' => 'required',
            'people.children' => 'required',
            'children_dob.*' => 'nullable',
            'people.infants' => 'required',
            'class' => 'required|max:1',
            'type' => 'required|max:10',
        ]);

        if ($this->people['children'] > 0) {
            $this->validate([
                'children_dob.*' => 'required',
            ]);
        }
    }
    protected function validateMultiCity() {
        return $this->validate([
            'people.adults' => 'required',
            'people.children' => 'required',
            'children_dob.*' => 'nullable',
            'people.infants' => 'required',
            'class' => 'required|max:1',
            'type' => 'required|max:10',
            'multi_cities.*.from' => 'required',
            'multi_cities.*.to' => 'required',
            'multi_cities.*.depart_date' => 'required',
        ]);

        if ($this->people['children'] > 0) {
            $this->validate([
                'children_dob.*' => 'required',
            ]);
        }
    }
    protected function validateOneWay() {
        return $this->validate([
            'from' => 'required|min:3|max:3',
            'to' => 'required|min:3|max:3',
            'depart_date' => 'required',
            'people.adults' => 'required',
            'people.children' => 'required',
            'children_dob.*' => 'nullable',
            'people.infants' => 'required',
            'class' => 'required|max:1',
            'type' => 'required|max:10',
        ]);

        if ($this->people['children'] > 0) {
            $this->validate([
                'children_dob.*' => 'required',
            ]);
        }
    }


    /**
     * Belongs to Filter
     */
    public function filter() {
        $this->resetPage();
        $its = session()->get($this->session_key)['itineraries'] ?? [];
        $flightCollection = collect($its);

        # by api provider
        if (is_array($this->api_source) && count($this->api_source) > 0) {
            $flightCollection = $flightCollection->filter(function($flight) {
                if (in_array($flight['apiSource'], $this->api_source)) {
                    return true;
                }
                return false;
            });
        }

        # by airlines
        if (is_array($this->airline) && count($this->airline) > 0) {
            $flightCollection = $flightCollection->filter(function($flight) {
                $code = $flight['legs'][0]['schedules'][0]['carrier']['marketing'] ?? '';
                if (in_array($code, $this->airline)) {
                    return true;
                }else {
                    return false;
                }
            });
        }
        
        # by stops
        if (is_array($this->stops) && count($this->stops) > 0) {
            $flightCollection = $flightCollection->filter(function($flight) {
                if (in_array('STOP_2', $this->stops)) {
                    if ($flight['legs'][0]['stops'] >= 2) {
                        return true;
                    }
                }
                if (in_array('STOP_1', $this->stops)) {
                    if ($flight['legs'][0]['stops'] == 1) {
                        return true;
                    }
                }
                if (in_array('NON_STOP', $this->stops)) {
                    if ($flight['legs'][0]['stops'] < 1) {
                        return true;
                    }
                }
                
                return false;
            });
        }

        $this->flights['itineraries'] = $flightCollection;
        $this->filterLowToHighPrice();
    }

    public function setAirlinesFromResult() {
        $this->airlines = [];
        $its = session()->get($this->session_key)['itineraries'] ?? [];
        $flightCollection = collect($its);
        $flightCollection->each(function($flight) {
            foreach ($flight['legs'] as $leg) {
                $this->airlines[] = $leg['schedules'][0]['carrier']['marketing'];
            }
        });
        $this->airlines = array_unique($this->airlines);
    }

    public function filterLowToHighPrice() {
        $its = $this->flights['itineraries'] ?? [];
        $flightCollection = collect($its);
        $this->flights['itineraries'] = $flightCollection->sort(function ($a, $b) {
            if ($a['pricingInfo']['totalPrice'] == $b['pricingInfo']['totalPrice']) {
                return 0;

            }
            return ($a['pricingInfo']['totalPrice'] < $b['pricingInfo']['totalPrice']) ? -1 : 1;
        });
    }


    public function clearApiFilter() {
        $this->flights['itineraries'] = session()->get($this->session_key)['itineraries'];
        $this->api_source = [];
        $this->stops = [];
        $this->airline = [];
        $this->resetPage();
    }


    public function mapDates() {
        if (!empty($this->depart_date)) {
            $this->depart_date = Carbon::create($this->depart_date)->format('Y-m-d');
        }
        if (!empty($this->return_date)) {
            $this->depart_date = Carbon::create($this->depart_date)->format('Y-m-d');
        }

        foreach ($this->multi_cities as &$multi_city) {
            if (!empty($this->return_date)) {
                $multi_city['depart_date'] = Carbon::create($multi_city['depart_date'])->format('Y-m-d');
            }
        }
    }

    public function dieDump() {
        dd($this->flights);
    }

    public function render() {
        # for paginate
        $perPage = 10;
        $paginator = '';

        # paginate, if flights are available
        if(isset($this->flights['itineraries'])) {
            $collection = collect($this->flights['itineraries']);
            $items = $collection->forPage($this->page, $perPage);
            $paginator = new LengthAwarePaginator($items, $collection->count(), $perPage, $this->page);
        }

        return view('livewire.agent.flight.flight-search-result', ['theFlights' => $paginator]);
    }
}
