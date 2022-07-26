<div>
<form x-data="searchForm" id="bookingFlight">

    <div class="mb-3">
        <div class="custom-control custom-radio custom-control-inline">
            <input x-model="type" value="ONE_WAY" id="oneway" class="custom-control-input" checked="" required type="radio">
            <label class="custom-control-label" for="oneway">One Way</label>
        </div>
        <div class="custom-control custom-radio custom-control-inline">
            <input x-model="type" value="ROUND_TRIP" id="roundtrip" class="custom-control-input" required type="radio">
            <label class="custom-control-label" for="roundtrip">Round Trip</label>
        </div>
        <div class="custom-control custom-radio custom-control-inline">
            <input x-model="type" value="MULTI_CITY" id="multi-city" class="custom-control-input" required type="radio">
            <label class="custom-control-label" for="multi-city">Multi City</label>
        </div>
    </div>

    <!-- for one-way & round trip -->
    <template x-if="type != 'MULTI_CITY'">
        <div wire:key="one-way" class="form-row">
            <div class="col form-group position-relative">
                <input @click.outside="clearSearch" @keyup="searchAirport('from')" x-model="airportFrom" type="text" class="form-control" id="flightFrom" required placeholder="From" autocomplete="off">
                <span class="icon-inside"><i class="color-primary fa-solid fa-plane-departure"></i></span>

                <template x-if="searchingAirport == 'from'">
                    <ul class="search-ul" x-ref="searchUl">
                        <template x-for="airport in airports" :key="airport.id">
                            <li @click="setAirport(airport.id, 'from')" x-text="airport.label"></li>
                        </template>
                    </ul>
                </template>
            </div>
            <div class="col form-group position-relative">
                <input @click.outside="clearSearch" @keyup="searchAirport('to')" x-model="airportTo" type="text" class="form-control" id="flightTo" required placeholder="To" autocomplete="off">
                <span class="icon-inside"><i class="color-primary fa-solid fa-plane-arrival"></i></span>

                <template x-if="searchingAirport == 'to'">
                    <ul class="search-ul" x-ref="searchUl">
                        <template x-for="airport in airports" :key="airport.id">
                            <li @click="setAirport(airport.id, 'to')" x-text="airport.label"></li>
                        </template>
                    </ul>
                </template>
            </div>
        </div>
    </template>


    <template x-if="type == 'MULTI_CITY'">
        <template x-for="(multi_citie, i) in multi_cities">
            <div class="form-row">
                <div class="col form-group position-relative">
                    <input @click.outside="clearSearch" @keyup="searchAirport('multiFrom', multi_citie.from, i)" type="text" x-model="multi_citie.from" placeholder="From" class="form-control">
                    <span class="icon-inside"><i class="color-primary fa-solid fa-plane-departure"></i></span>
                    <template x-if="searchingAirport == 'multiFrom' && multiFromIndex == i">
                        <ul class="search-ul" x-ref="searchUl">
                            <template x-for="airport in airports" :key="airport.id">
                                <li @click="setAirport(airport.id, 'multiFrom', i)" x-text="airport.label"></li>
                            </template>
                        </ul>
                    </template>
                </div>

                <div class="col form-group position-relative">
                    <input @click.outside="clearSearch" @keyup="searchAirport('multiTo', multi_citie.to, i)"  x-model="multi_citie.to" type="text" placeholder="To" class="form-control">
                    <span class="icon-inside"><i class="color-primary fa-solid fa-plane-arrival"></i></span>
                    <template x-if="searchingAirport == 'multiTo' && multiFromIndex == i">
                        <ul class="search-ul" x-ref="searchUl">
                            <template x-for="airport in airports" :key="airport.id">
                                <li @click="setAirport(airport.id, 'multiTo', i)" x-text="airport.label"></li>
                            </template>
                        </ul>
                    </template>
                </div>
                <div class="col form-group">
                    <x-date-picker x-model="multi_citie.depart_date" id="flightDepart" placeholder="Depart Date" class="form-control"/>
                    <span class="icon-inside"><i class="color-primary far fa-calendar-alt"></i></span>
                </div>
            </div>
        </template>
    </template>

    <div class="form-row">
        <template x-if="type != 'MULTI_CITY'">
            <div class="col form-group">
                {{-- depart date --}}
                <x-date-picker x-model="depart_date" id="flightDepart" placeholder="Depart Date" class="form-control"/>
                <span class="icon-inside"><i class="color-primary far fa-calendar-alt"></i></span>
            </div>
        </template>

        <template x-if="type == 'ROUND_TRIP'" >
            <div class="col form-group">
                {{-- return date --}}
                <x-date-picker x-model="return_date" id="flightReturn" placeholder="Return Date" class="form-control"/>
                <span class="icon-inside"><i class="color-primary far fa-calendar-alt"></i></span> 
            </div>
        </template>
    </div>


    <div class="form-row">
        <div class="col">
            <!-- Class -->
            <div class="travellers-class form-group">
                <input type="text" id="flightTravellersClass" class="travellers-class-input form-control @error('class')) is-invalid @enderror" name="flight-travellers-class" placeholder="Class" readonly required onkeypress="return false;">
                <span class="icon-inside"><i class="fas fa-caret-down"></i></span>
                <div class="travellers-dropdown">
                <div class="mb-3">
                    <div class="custom-control custom-radio">
                        <input wire:model="class" value="Y" id="flightClassEconomic" name="flight-class" class="flight-class custom-control-input" value="0" checked="" required type="radio">
                        <label class="custom-control-label" for="flightClassEconomic">Economy</label>
                    </div>
                    <div class="custom-control custom-radio">
                        <input wire:model="class" value="C" id="flightClassBusiness" name="flight-class" class="flight-class custom-control-input" value="2" required type="radio">
                        <label class="custom-control-label" for="flightClassBusiness">Business</label>
                    </div>
                    <div class="custom-control custom-radio">
                        <input wire:model="class" value="S" id="flightClassPremiumEconomic" name="flight-class" class="flight-class custom-control-input" value="1" required type="radio">
                        <label class="custom-control-label" for="flightClassPremiumEconomic">Premium Economy</label>
                    </div>
                    <div class="custom-control custom-radio">
                        <input wire:model="class" value="F" id="flightClassFirstClass" name="flight-class" class="flight-class custom-control-input" value="3" required type="radio">
                        <label class="custom-control-label" for="flightClassFirstClass">First Class</label>
                    </div>
                </div>
                    <button class="btn btn-primary w-100 submit-done" type="button">Done</button>
                </div>
            </div>
        </div>

        <div class="col">
            <!-- Travelers -->
            <div class="travellers-class form-group">
                <input value="{{ $people['adults'] + $people['children'] + $people['infants']  }} Persons" type="text" id="flightTravellersClass-02" class="travellers-class-input form-control" placeholder="Travellers" readonly>
                <span class="icon-inside"><i class="fas fa-caret-down"></i></span>
                <div wire:ignore.self class="travellers-dropdown-02">
                <div class="row align-items-center">
                    <div class="col-sm-7">
                        <p class="mb-sm-0 text-1">Adults <small class="text-muted">(12+ yrs)</small></p>
                    </div>
                    <div class="col-sm-5">

                    <div class="qty input-group">
                        <div class="input-group-prepend">
                            <button wire:click="adultDecrement" type="button" class="btn bg-light-4">-</button>
                        </div>
                        <input wire:model="people.adults" type="text" id="flightAdult-travellers" class="qty-spinner form-control" readonly>
                        <div class="input-group-append">
                            <button wire:click="adultIncrement" type="button" class="btn bg-light-4">+</button>
                        </div>
                    </div>
                    </div>
                </div>
                <hr class="my-2">
                <div class="row align-items-center">
                    <div class="col-sm-7">
                        <p class="mb-sm-0  text-1">Children <small class="text-muted">(2-12 yrs)</small></p>
                    </div>
                    <div class="col-sm-5">
                        <div class="qty input-group">
                            <div class="input-group-prepend">
                                <button wire:click="childrenDecrement" type="button" class="btn bg-light-4">-</button>
                            </div>
                            <input wire:model="people.children" type="text" class="qty-spinner form-control" readonly>
                            <div class="input-group-append">
                                <button wire:click="childrenIncrement" type="button" class="btn bg-light-4">+</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    @for ($i = 0; $i < $people['children']; $i++)
                        <div class="col">
                            <label for="" class="text-small mb-0">DateOfBirth <b>Child#{{$i+1}}</b></label>
                            <input 
                                wire:model="children_dob.{{$i}}" 
                                class="form-control form-control-sm" 
                                type="date"
                            >
                        </div>
                    @endfor
                </div>

                <hr class="my-2">
                <div class="row align-items-center">
                    <div class="col-sm-7">
                        <p class="mb-sm-0  text-1">Infants <small class="text-muted">(Below 2 yrs)</small></p>
                    </div>
                    <div class="col-sm-5">
                    <div class="qty input-group">
                        <div class="input-group-prepend">
                            <button wire:click="infantDecrement" type="button" class="btn bg-light-4">-</button>
                        </div>
                        <input wire:model="people.infants" type="text" class="qty-spinner form-control" readonly>
                        <div class="input-group-append">
                            <button wire:click="infantIncrement" type="button" class="btn bg-light-4">+</button>
                        </div>
                    </div>
                    </div>
                </div>
                    <button class="btn btn-primary w-100 submit-done mt-2" type="button">Done</button>
                </div>
            </div>
        </div>
    </div>

    <template x-if="type == 'MULTI_CITY'">
        <div class="form-row align-items-center pl-2 mb-4">
            <i class="fa-solid fa-circle-plus title-icon color-primary"></i>
            <button type="button" class="btn btn-sm btn-primary rounded-pill ml-2 px-5" x-on:click="addMultiCity">Add City</button>

            <template x-if="multi_cities.length > 2">
                <button type="button" class="btn btn-sm btn-primary rounded-pill ml-2 px-5" x-on:click="removeMultiCity">Remove</button>
            </template>
        </div>
    </template>

    <button wire:click="search" class="btn btn-primary w-100" type="button">Search Flights</button>
</form>

<script>
    let searchForm = () => {
        return {
            'type': @entangle('type').defer,
            'from': @entangle('from').defer,
            'to': @entangle('to').defer,
            'depart_date': @entangle('depart_date').defer,
            'return_date': @entangle('return_date').defer,
            'airports' : [],
            'airportFrom' : '',
            'airportTo' : '',
            'searchingAirport' : '',

            'multi_cities' : @entangle('multi_cities').defer,
            'multiFromIndex': '',

            searchAirport(searchingAirport, term, index) {
                this.multiFromIndex = index;
                this.searchingAirport = searchingAirport;
                var searchTerm = '';

                if(searchingAirport  == 'from') {
                    searchTerm = this.airportFrom;
                }
                if(searchingAirport  == 'to') {
                    searchTerm = this.airportTo;
                }
                if(searchingAirport  == 'multiFrom' || searchingAirport  == 'multiTo') {
                    searchTerm = term;
                }

                fetch('/api/airports?query=' + searchTerm)
                    .then(response => response.json())
                    .then((data) => {
                        this.airports = data.data;
                    });

            },

            setAirport(code, name, i) {
                if(name == 'from') {
                    this.from = code;
                    this.airportFrom = code;
                    this.airports = [];
                }
                if(name == 'to') {
                    this.to = code;
                    this.airportTo = code;
                    this.airports = [];
                }
                this.searchingAirport = '';

                if(name == 'multiFrom') {
                    this.multi_cities[i].from = code;
                }
                if(name == 'multiTo') {
                    this.multi_cities[i].to = code;
                }
            },

            addMultiCity() {
                this.multi_cities.push({
                    'from' : '',
                    'to' : '',
                    'depart_date' : ''
                });
            },
            removeMultiCity() {
                this.multi_cities.pop();
            },
            clearSearch() {
                this.searchingAirport = '';
                this.airports= [];
            }
        }
    }
</script>
</div>
