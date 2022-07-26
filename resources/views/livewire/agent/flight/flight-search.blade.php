<div>
<form id="bookingFlight">
    <div class="mb-3">
        <div class="custom-control custom-radio custom-control-inline">
            <input wire:model="type" value="ONE_WAY" id="oneway" class="custom-control-input" checked="" required type="radio">
            <label class="custom-control-label" for="oneway">One Way</label>
        </div>
        <div class="custom-control custom-radio custom-control-inline">
            <input wire:model="type" value="ROUND_TRIP" id="roundtrip" class="custom-control-input" required type="radio">
            <label class="custom-control-label" for="roundtrip">Round Trip</label>
        </div>
        <div class="custom-control custom-radio custom-control-inline">
            <input wire:model="type" value="MULTI_CITY" id="multi-city" class="custom-control-input" required type="radio">
            <label class="custom-control-label" for="multi-city">Multi City</label>
        </div>
    </div>

    <!-- for one-way & round trip -->
    @if($type != 'MULTI_CITY')
    <div wire:key="one-way" class="form-row">
        <div class="col form-group">
            <input onfocus="searchAirport('flightFrom')" wire:model.lazy="from" type="text" class="form-control @error('from')) is-invalid @enderror" id="flightFrom" required placeholder="From">
            <span class="icon-inside"><i class="color-primary fa-solid fa-plane-departure"></i></span>
        </div>
        <div class="col form-group">
            <input onfocus="searchAirport('flightTo')" wire:model.lazy="to" type="text" class="form-control @error('to')) is-invalid @enderror" id="flightTo" required placeholder="To">
            <span class="icon-inside"><i class="color-primary fa-solid fa-plane-arrival"></i></span>
        </div>
    </div>
    @endif

    @if($type == 'MULTI_CITY')
        @foreach($multi_cities as $k => $multi_city)
        <div class="form-row" wire:key="{{ $k }}">
            <div class="col form-group">
                <input id="flightFrom{{$k}}" onfocus="searchAirport('flightFrom{{$k}}')" wire:model="multi_cities.{{$k}}.from" type="text" class="form-control" required placeholder="From">
                <span class="icon-inside"><i class="color-primary fa-solid fa-plane-departure"></i></span>
            </div>
            <div class="col form-group">
                <input id="flightTo{{$k}}" onfocus="searchAirport('flightTo{{$k}}')" wire:model="multi_cities.{{$k}}.to" type="text" class="form-control" required placeholder="To">
                <span class="icon-inside"><i class="color-primary fa-solid fa-plane-arrival"></i></span>
            </div>
            <div class="col form-group">

                {{-- multi depart date --}}
                <input 
                    onmouseover="setDatePickers()"
                    onfocusout="emitDate(this, 'MULTI_CITY_DATE', {{$k}})"
                    wire:model="multi_cities.{{$k}}.depart_date" 
                    id="flightDepart{{$k}}" 
                    type="text" 
                    class="form-control datepicker" 
                    required
                    placeholder="Depart Date"
                >
                <span class="icon-inside"><i class="color-primary far fa-calendar-alt"></i></span>
            </div>
        </div>
        @endforeach
    @endif

    <div class="form-row">
        @if($type != 'MULTI_CITY')
        <div wire:key="my_depart" class="col form-group">

            {{-- depart date --}}
            <input 
                onmouseover="setDepartDate()"
                onfocusout="emitDate(this, 'DEPART', '')"
                wire:model="depart_date" 
                id="flightDepart" 
                type="text" 
                class="form-control datepicker @error('depart_date')) is-invalid @enderror" 
                autocomplete="off" 
                required 
                placeholder="Depart Date"
            >
            <span class="icon-inside"><i class="color-primary far fa-calendar-alt"></i></span>

        </div>
        @endif
        @if($type == 'ROUND_TRIP')
        <div wire:key="my_return" class="col form-group">

            {{-- return date --}}
            <input 
                onmouseover="setReturnDate()"
                onfocusout="emitDate(this, 'RETURN', '')"
                wire:model="return_date" 
                id="flightReturn" 
                type="text" 
                class="form-control datepicker @error('return_date')) is-invalid @enderror" 
                required 
                placeholder="Return Date"
            >
            <span class="icon-inside"><i class="color-primary far fa-calendar-alt"></i></span> 
        </div>
        @endif
    </div>

    <div class="travellers-class form-group">
        <input type="text" id="flightTravellersClass" class="travellers-class-input form-control @error('class')) is-invalid @enderror" name="flight-travellers-class" placeholder="Class" readonly required onkeypress="return false;">
        <span class="icon-inside"><i class="fas fa-caret-down"></i></span>
        <div wire:ignore.self class="travellers-dropdown">
        <div class="mb-3">
            <div class="custom-control custom-radio">
                <input wire:model.lazy="class" value="Y" id="flightClassEconomic" name="flight-class" class="flight-class custom-control-input" value="0" checked="" required type="radio">
                <label class="custom-control-label" for="flightClassEconomic">Economy</label>
            </div>
            <div class="custom-control custom-radio">
                <input wire:model.lazy="class" value="C" id="flightClassBusiness" name="flight-class" class="flight-class custom-control-input" value="2" required type="radio">
                <label class="custom-control-label" for="flightClassBusiness">Business</label>
            </div>
            <div class="custom-control custom-radio">
                <input wire:model.lazy="class" value="S" id="flightClassPremiumEconomic" name="flight-class" class="flight-class custom-control-input" value="1" required type="radio">
                <label class="custom-control-label" for="flightClassPremiumEconomic">Premium Economy</label>
            </div>
            <div class="custom-control custom-radio">
                <input wire:model.lazy="class" value="F" id="flightClassFirstClass" name="flight-class" class="flight-class custom-control-input" value="3" required type="radio">
                <label class="custom-control-label" for="flightClassFirstClass">First Class</label>
            </div>
        </div>
            <button class="btn btn-primary w-100 submit-done" type="button">Done</button>
        </div>
    </div>

    <!-- Travelers -->
    <div class="travellers-class form-group">
        <input value="{{ $people['adults'] + $people['children'] + $people['infants']  }} Persons" type="text" id="flightTravellersClass-02" class="travellers-class-input form-control" placeholder="Travellers" readonly>
        <span class="icon-inside"><i class="fas fa-caret-down"></i></span>
        <div wire:ignore.self class="travellers-dropdown-02">
        <div class="row align-items-center">
            <div class="col-sm-7">
                <p class="mb-sm-0">Adults <small class="text-muted">(12+ yrs)</small></p>
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
                <p class="mb-sm-0">Children <small class="text-muted">(2-12 yrs)</small></p>
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
                <p class="mb-sm-0">Infants <small class="text-muted">(Below 2 yrs)</small></p>
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

    @if($type == 'MULTI_CITY')
    <div class="form-row align-items-center pl-2 mb-4">
        <i class="fa-solid fa-circle-plus title-icon color-primary"></i>
        <button type="button" class="btn btn-sm btn-primary rounded-pill ml-2 px-5" wire:click="addMultiCity">Add City</button>

        @if(count($multi_cities) > 2)
        <button type="button" class="btn btn-sm btn-primary rounded-pill ml-2 px-5" wire:click="removeMultiCity">Remove</button>
        @endif
    </div>
    @endif
    <button wire:click="search" class="btn btn-primary w-100" type="button">Search Flights</button>
</form>

</div>
