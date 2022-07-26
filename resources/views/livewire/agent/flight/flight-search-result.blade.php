<div x-data="searchForm" x-init="init" wire:init="searchFlight" class="position-relative">

<section class="page-header page-header-text-light bg-light mb-0">
    <div class="container">
    <div class="row align-items-center">
        <div class="col-md-8">
        <h6 class="px-3" wire:click="dieDump">Flight search result</h6>
        </div>
        <div class="col-md-4">
            <ul class="breadcrumb justify-content-start justify-content-md-end mb-0">
                <li class="active">
                    <button x-on:click="showModifyForm" class="btn btn-sm btn-primary py-2 px-5">
                        <i class="fa-solid fa-pen-to-square"></i>
                        Modify Search
                    </button>
                </li>
            </ul>
        </div>
    </div>
    </div>
</section>

<div class="mx-5 my-2">
    @include('includes.errors')
    @include('includes.alert_failed_inner')
</div>

{{-- show modify form --}}
<div wire:ignore>
    <template x-if="show_modify_form">
        <div class="row ">
            <div class="col-2"></div>
                <div class="col bg-white p-4 rounded">
                    <form id="bookingFlight">

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
                            <div class="form-row">
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
                                <input @click="$dispatch('showclass')" type="text" id="flightTravellersClass" class="travellers-class-input form-control @error('class')) is-invalid @enderror" name="flight-travellers-class" :placeholder="bookingClassName" readonly required onkeypress="return false;">
                                <span class="icon-inside"><i class="fas fa-caret-down"></i></span>
                                <div class="travellers-dropdown">
                                <div class="mb-3">
                                    <div class="custom-control custom-radio">
                                        <input x-model="bookingclass" value="Y" id="flightClassEconomic" name="flight-class" class="flight-class custom-control-input" value="0" checked="" required type="radio">
                                        <label class="custom-control-label" for="flightClassEconomic">Economy</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input x-model="bookingclass" value="C" id="flightClassBusiness" name="flight-class" class="flight-class custom-control-input" value="2" required type="radio">
                                        <label class="custom-control-label" for="flightClassBusiness">Business</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input x-model="bookingclass" value="S" id="flightClassPremiumEconomic" name="flight-class" class="flight-class custom-control-input" value="1" required type="radio">
                                        <label class="custom-control-label" for="flightClassPremiumEconomic">Premium Economy</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input x-model="bookingclass" value="F" id="flightClassFirstClass" name="flight-class" class="flight-class custom-control-input" value="3" required type="radio">
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
                                <input @click="$dispatch('travellersshow')" x-model="totalPeopls" type="text" id="flightTravellersClass-02" class="travellers-class-input form-control" placeholder="Travellers" readonly>
                                <span class="icon-inside"><i class="fas fa-caret-down"></i></span>
                                <div class="travellers-dropdown-02">
                                    <div class="row align-items-center">
                                        <div class="col-sm-7">
                                            <p class="mb-sm-0 text-1">Adults <small class="text-muted">(12+ yrs)</small></p>
                                        </div>
                                        <div class="col-sm-5">

                                        <div class="qty input-group">
                                            <div class="input-group-prepend">
                                                <button @click="$wire.adultDecrement()" type="button" class="btn bg-light-4">-</button>
                                            </div>
                                            <input x-model="people.adults" type="text" id="flightAdult-travellers" class="qty-spinner form-control" readonly>
                                            <div class="input-group-append">
                                                <button @click="$wire.adultIncrement()" type="button" class="btn bg-light-4">+</button>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                    <hr class="my-2">
                                    <div class="row align-items-center">
                                        <div class="col-sm-7">
                                            <p class="mb-sm-0 text-1">Children <small class="text-muted">(2-12 yrs)</small></p>
                                        </div>
                                        <div class="col-sm-5">
                                            <div class="qty input-group">
                                                <div class="input-group-prepend">
                                                    <button @click="$wire.childrenDecrement()" type="button" class="btn bg-light-4">-</button>
                                                </div>
                                                <input x-model="people.children" type="text" class="qty-spinner form-control" readonly>
                                                <div class="input-group-append">
                                                    <button @click="$wire.childrenIncrement()" type="button" class="btn bg-light-4">+</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                    <template x-if="people['children'] > 0">
                                        <template x-for="i in people['children']">
                                            <div class="col">
                                                <label for="" class="text-small mb-0">DateOfBirth <b x-text="`Child#${i}`"></b></label>
                                                <input 
                                                    x-model="children_dob[i]" 
                                                    class="form-control form-control-sm" 
                                                    type="date"
                                                >
                                            </div>
                                        </template>
                                    </template>
                                    </div>

                                    <hr class="my-2">
                                    <div class="row align-items-center">
                                        <div class="col-sm-7">
                                            <p class="mb-sm-0 text-1">Infants <small class="text-muted">(Below 2 yrs)</small></p>
                                        </div>
                                        <div class="col-sm-5">
                                        <div class="qty input-group">
                                            <div class="input-group-prepend">
                                                <button @click="$wire.infantDecrement()" type="button" class="btn bg-light-4">-</button>
                                            </div>
                                            <input x-model="people.infants" type="text" class="qty-spinner form-control" readonly>
                                            <div class="input-group-append">
                                                <button @click="$wire.infantIncrement()" type="button" class="btn bg-light-4">+</button>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                        <button class="btn btn-primary w-100 submit-done mt-2" type="button">Done</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <template x-if="type == 'MULTI_CITY'">
                                <div class="form-row align-items-center pl-2 mb-4">
                                    <i class="fa-solid fa-circle-plus title-icon color-primary"></i>
                                    <button type="button" class="btn btn-sm btn-primary rounded-pill ml-2 px-5" x-on:click="addMultiCity">Add City</button>

                                    <template x-if="multi_cities.length > 2">
                                        <button type="button" class="btn btn-sm btn-primary rounded-pill ml-2 px-5" x-on:click="removeMultiCity">Remove</button>
                                    </template>
                                </div>
                            </template>
                        </div>

                        <div class="form-row">
                            <div class="col-8">
                                <button @click="searchFlight" class="btn btn-primary w-100 py-2" type="button">Search Flights</button>
                            </div>
                            <div class="col">
                                <button @click="hideSearchFlight" class="btn btn-secondary w-100 py-2" type="button">Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>
            <div class="col-2"></div>
        </div>
    </template>
</div>



@if($readyToLoad && !$fetchError)
<div wire:key="search-result-container" class="d-flex flex-column flex-md-row mt-2 pt-1">

    <!-- Side panel-->
    <aside class="col-md-3">
        <div class="bg-white shadow-md rounded p-3 h-100">
            <h3 class="text-5  d-flex justify-content-between">
                <span>Filter</span>
                <button wire:click="clearApiFilter" class="btn btn-sm btn-link py-0">Clear filter</button>
            </h3>
            <hr class="mx-n3">
            <div class="accordion accordion-alternate style-2 mt-n3">
            <div class="card">
                <div class="card-header" id="stops">
                <h5 class="mb-0"> 
                    <a href="#">Providers</a>
                </h5>
                </div>
                <div class="show" aria-labelledby="stops">
                    <form class="card-body">
                        <div class="custom-control custom-checkbox">
                            <input wire:model="api_source" value="{{ \App\Helpers\ConstantHelper::SABRE }}" type="checkbox" id="api-sabre" class="custom-control-input">
                            <label class="custom-control-label" for="api-sabre">SABRE</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input wire:model="api_source" value="{{ \App\Helpers\ConstantHelper::US_BANGLA }}" type="checkbox" id="api-usbangla" class="custom-control-input">
                            <label class="custom-control-label" for="api-usbangla">US-BANGLA</label>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="stops">
                    <h5 class="mb-0"> <a href="#" data-toggle="collapse" data-target="#togglestops" aria-expanded="true" aria-controls="togglestops">No. of Stops</a> </h5>
                </div>
                <div id="togglestops" class="collapse show" aria-labelledby="stops">
                    <div class="card-body">
                        <div class="custom-control custom-checkbox">
                        <input wire:model="stops" value="NON_STOP" type="checkbox" id="nonstop" class="custom-control-input">
                        <label class="custom-control-label" for="nonstop">Non Stop</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                        <input wire:model="stops" value="STOP_1" type="checkbox" id="1stop" class="custom-control-input">
                        <label class="custom-control-label" for="1stop">1 Stop</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                        <input wire:model="stops" value="STOP_2" type="checkbox" id="2stop" class="custom-control-input">
                        <label class="custom-control-label" for="2stop">2+ Stop</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header" id="airlines">
                    <h5 class="mb-0"> <a href="#" data-toggle="collapse" data-target="#toggleairlines" aria-expanded="true" aria-controls="toggleairlines">Airlines</a> </h5>
                </div>
                <div id="toggleairlines" class="collapse show" aria-labelledby="airlines">
                    <div class="card-body">
                        <div class="custom-control custom-checkbox">
                            @foreach($airlines as $k => $air)
                                <div wire:key="airline-{{$k}}">
                                    <input wire:model="airline" value="{{$air}}" id="airline-{{$k}}" type="checkbox" name="airline" class="custom-control-input">
                                    <label class="custom-control-label d-flex align-items-center" for="airline-{{$k}}">
                                        <img class="rounded border border-light" src="https://tbbd-flight.s3.ap-southeast-1.amazonaws.com/airlines-logo/{{$air}}.png" alt="airline" width="20">
                                        {{ $air }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            </div>
        </div>
    </aside>
    <!-- End side panel-->

    <!-- Results-->
    <div class="col-md-9 mt-2 mt-md-0">


        {{-- new single flight --}}
        @foreach($theFlights as $key => $flight)
        <div wire:key="single-flight-{{ $key + 1 }}" class="row rounded bg-white pb-1 mb-3">
            <div class="row mx-0 bg-light pt-3 pb-3 mb-3">
                <div class="col d-flex align-items-center">
                    <img class="mr-2 rounded" src="https://tbbd-flight.s3.ap-southeast-1.amazonaws.com/airlines-logo/{{$flight['legs'][0]['schedules'][0]['carrier']['marketing']}}.png" alt="airline" width="40">
                    <div class="d-flex align-items-center">
                        <a target="_blank" href="{{ route('b2b.flight.search.details', ['session_key' => $session_key, 'session_index' => $key, 'children_dob' => $children_dob]) }}" class="text-3 font-weight-bold text-dark mr-2">
                            {{ $flight['legs'][0]['schedules'][0]['carrier']['marketing_name'] }}
                        </a>
                        <span class="badge badge-secondary badge-flight-search text-x-small">{{$flight['apiSource']}}</span> <br>
                    </div>
                </div>
            </div>

            <div class="row mx-0 px-0 line-height-1">
                <div wire:key="flight-leg-container" class="col-md-7 px-0 mx-0">

                    @foreach($flight['legs'] as $legKey => $leg)

                    @if($legKey > 0)
                        <div wire:key="flight-devider-{{$legKey}}" class="flight-divider"></div>
                    @endif

                    <div wire:key="leg-key-{{$legKey}}" class="row px-0 mx-0">
                        <div class="col-md-4">
                            <div class="text-center d-inline-block">
                                <p class="text-dark text-4">{{ $leg['schedules'][0]['departure']['time'] }}</p>
                                <span class="badge bg-offwhite text-gray px-4 text-2">
                                    {{ $leg['schedules'][0]['departure']['city'] }} <br>
                                    <small class="font-weight-normal">
                                        {{\App\Helpers\UtilityHelper::cityCodeToName($leg['schedules'][0]['departure']['city'])}}
                                    </small>
                                </span>
                                <div class="text-small bg-light mt-1 text-gray rounded">
                                    {{ date("D", strtotime($leg['schedules'][0]['departure']['date'])) }}, 
                                    {{ date("d M, y", strtotime($leg['schedules'][0]['departure']['date'])) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="h-100 pl-0 pl-md-3 d-flex flex-column justify-content-center">
                                <div class="d-inline-block">
                                    <div>
                                        @php 
                                            $elapsedTime = 0; 
                                            foreach ($leg['schedules'] as $s) {
                                                $elapsedTime += $s['elapsedTime'];
                                            }
                                        @endphp
                                        {{ \App\Helpers\UtilityHelper::minuteToHourMinute($elapsedTime, '%02dh %02dm') }}
                                    </div>
                                    @if($leg['stops'] > 0)
                                        <small>{{ $leg['stops'] }} {{'stop'}}@if($leg['stops']>1){{'s'}}@endif</small>
                                    @else
                                        <small class="pl-1">Direct</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center d-inline-block" >
                                <p class="text-dark text-4">{{ $leg['schedules'][count($leg['schedules']) - 1]['arrival']['time'] }}</p>
                                <span class="badge bg-offwhite text-gray px-4 text-2">
                                    {{ $leg['schedules'][count($leg['schedules']) - 1]['arrival']['city'] }} <br>
                                    <small class="font-weight-normal">
                                        {{\App\Helpers\UtilityHelper::cityCodeToName($leg['schedules'][count($leg['schedules']) - 1]['arrival']['city'])}}
                                    </small>
                                </span>
                                <div class="text-small bg-light mt-1 text-gray rounded">
                                    {{ date("D", strtotime($leg['schedules'][count($leg['schedules']) - 1]['arrival']['date'])) }}, 
                                    {{ date("d M, y", strtotime($leg['schedules'][count($leg['schedules']) - 1]['arrival']['date'])) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach

                </div>

                <div class="col-md-2">
                    <div class="d-flex h-100 line-height-1-2 flex-column text-small @if(count($flight['legs']) > 1) justify-content-center @endif">
                        @if($flight['apiSource'] == \App\Helpers\ConstantHelper::SABRE)
                            <strong>{{ App\Helpers\SabreFlightHelper::cabinCodeToName($flight['passengerInfoList'][0]['cabinCode']) }}</strong>
                        @endif
                        @if($flight['apiSource'] == \App\Helpers\ConstantHelper::US_BANGLA)
                            <strong>{{ App\Helpers\USBanglaFlightHelper::cabinCodeToName($flight['passengerInfoList'][0]['cabinCode']) }}</strong>
                        @endif

                        <strong class="text-secondary">
                            @if($flight['passengerInfoList'][0]['nonRefundable'])
                                Non Refundable
                            @else
                                Refundable
                            @endif
                        </strong>
                        <strong class="text-uppercase"><i class="fa-solid fa-suitcase"></i> 
                            {{$flight['passengerInfoList'][0]['baggageAllowances'][0]['weight']}}
                            {{$flight['passengerInfoList'][0]['baggageAllowances'][0]['unit']}}
                        </strong>

                        @php 
                            $seatsAvailable = 0;
                        @endphp

                        @foreach($flight['passengerInfoList'] as $passengerInfo)
                            @php $seatsAvailable += $passengerInfo['seatsAvailable']; @endphp
                        @endforeach
                        
                        @if($seatsAvailable > 0)
                            <strong class="">
                                {{ $seatsAvailable }} Seats
                            </strong>
                        @endif
                    </div>
                </div>
                <div class="col-md-3">
                    <div @if(count($flight['legs']) > 1) class="d-flex h-100 flex-column justify-content-center" @endif>
                        <h6 class="mb-1 pl-1 text-0-9 font-weight-bold">
                            {{ $flight['pricingInfo']['currency'] }} 
                            {{ number_format($flight['pricingInfo']['totalPrice'], 2) }}
                        </h6>
                        <div class="bg-light px-2 py-1 text-left rounded mb-2">
                            <span class="mb-1 text-1">Agent Price:</span><br>
                            <strong class="text-success text-2">
                                {{ $flight['pricingInfo']['currency'] }} 
                                {{ number_format($flight['pricingInfo']['totalAgentPriceWithMargin'], 2) }}
                            </strong>
                        </div>
                        <a target="_blank" href="{{ route('b2b.flight.search.details', ['session_key' => $session_key, 'session_index' => $key, 'children_dob' => $children_dob]) }}" class="btn btn-sm btn-primary btn-block">
                            <span>BOOK FLIGHT</span>
                        </a>
                    </div>

                </div>
            </div>

            <div class="row @if(count($flight['legs']) > 1) mt-3 @endif">
                <div class="col px-0">
                    <button type="button" onclick="toggle('ticket-details-{{$key}}')" class="btn btn-sm text-small font-weight-bold btn-link text-decoration-none">Flight Details</button>
                    <button type="button"  onclick="toggle('priceBreakDown-{{$key}}')" class="btn btn-sm text-small font-weight-bold btn-link text-decoration-none">Fare Details</button>
                </div>
            </div>
            
            {{-- price breakdown --}}
            <div class="row px-0">
                <div class="col-md-6"></div>
                <div class="col-md-6 px-0">
                    <div id="priceBreakDown-{{$key}}" class="d-none hidden mt-2 bg-light rounded p-2">
                        {{-- totals --}}
                        @php 
                            $totalPassengers = 0;
                        @endphp

                        {{-- single person fare --}}
                        @foreach ($flight['passengerInfoList'] as $pKey => $passanger)
                            @php 
                                $totalPassengers += $passanger['passengerNumber'];
                            @endphp

                            <div wire:key="tarvelers-{{$pKey}}" class="d-flex justify-content-between">
                                <strong class="text-small">
                                    Traveller: 
                                    @if($flight['apiSource'] == \App\Helpers\ConstantHelper::SABRE)
                                        {{ App\Helpers\SabreFlightHelper::passengerTypeCodeToName($passanger['passengerType'])}}
                                    @endif
                                    @if($flight['apiSource'] == \App\Helpers\ConstantHelper::US_BANGLA)
                                        {{ App\Helpers\USBanglaFlightHelper::passengerTypeCodeToName($passanger['passengerType'])}}
                                    @endif
                                    x{{ $passanger['passengerNumber'] }}
                                </strong>
                            </div>

                            <div class="d-flex justify-content-between">
                                <small>Base Fare x{{ $passanger['passengerNumber'] }}</small>
                                <small>BDT {{ number_format($passanger['totalFare']['totalBaseFareAmount'], 2) }}</small>
                            </div>
                            <div class="d-flex justify-content-between">
                                <small>Tax & Fee x{{ $passanger['passengerNumber'] }}</small>
                                <small>BDT {{ number_format($passanger['totalFare']['totalTaxAmount'], 2) }}</small>
                            </div>
                                
                            @if((count($flight['passengerInfoList']) - 1) != $pKey)
                                <hr>
                            @endif
                        @endforeach

                        <hr>
                        {{-- base & tax totals --}}
                        <div class="d-flex justify-content-between">
                            <small>Total Base x{{ $totalPassengers }}</small>
                            <small>BDT {{ number_format($flight['totalFare']['totalBaseFare'], 2) }}</small>
                        </div>
                        <div class="d-flex justify-content-between">
                            <small>Total Tax x{{ $totalPassengers }} </small>
                            <small>BDT {{ number_format($flight['totalFare']['totalTaxAmount'], 2) }}</small>
                        </div>
                        <hr>

                        <div class="d-flex justify-content-between pb-2">
                            <small class="font-weight-bold">Final Price x{{ $totalPassengers }}</small>
                            <small class="font-weight-bold">BDT {{ number_format($flight['totalFare']['totalPrice'], 2) }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row px-0 mx-0">
                <div id="ticket-details-{{$key}}" class="d-none px-0">

                    @foreach($flight['legs'] as $legKey => $leg)
                    <!-- single flight details -->
                    <div wire:key="flight-details-key-{{$legKey}}" class="row border m-2 rounded bg-light shadow-sm overflow-hidden">
                        <div class="row">
                            <div class="col py-3">

                                <!--deperture city & destination city-->
                                <h6>
                                    <strong>
                                        {{ $leg['schedules'][0]['departure']['city']}} 
                                        <div class="d-inline-block trip-arrow">➝</div> 
                                        {{ $leg['schedules'][count($leg['schedules']) - 1]['arrival']['city'] }}
                                    </strong>
                                </h6>
                            </div>
                        </div>
                        <hr>
                        
                        <!-- scedules -->
                        @foreach($leg['schedules'] as $sKey => $schedule)
                        <div wire:key="schedule-key-{{$sKey}}" class="row pb-3">
                            <div class="col-md-1 text-center">
                                <div>
                                    <img class="w-100" style="max-width:3.5rem;" src="https://tbbd-flight.s3.ap-southeast-1.amazonaws.com/airlines-logo/{{$schedule['carrier']['marketing']}}.png" alt="">
                                </div>
                            </div>
                            <div class="col-md-4 mb-2 mb-md-0 text-center text-md-start line-height">
                                <strong> {{ $schedule['carrier']['marketing'] }} </strong>
                                <p>
                                    <small> 
                                        <strong class="text-small">
                                            <!-- 
                                                showing 1st passangers cabinCode for all scedules is safe, 
                                                bcz during our flight search we must provide cabinType(Economy,Business) and and we get results
                                                all contains same cabin code. 
                                            -->

                                            @if($flight['apiSource'] == \App\Helpers\ConstantHelper::SABRE)
                                                {{ App\Helpers\SabreFlightHelper::cabinCodeToName($flight['passengerInfoList'][0]['cabinCode']) }}
                                            @endif
                                            @if($flight['apiSource'] == \App\Helpers\ConstantHelper::US_BANGLA)
                                                {{ App\Helpers\USBanglaFlightHelper::cabinCodeToName($flight['passengerInfoList'][0]['cabinCode']) }}
                                            @endif

                                        </strong>
                                    </small> 
                                </p>
                                {{-- <p><small><strong class="text-small">RBD: Y </strong></small> </p> --}}
                                <p>
                                    <small class="text-small">
                                        {{ date("l", strtotime($schedule['departure']['date'])) }},
                                        {{ date("H:m", strtotime($schedule['departure']['time'])) }}
                                    </small>
                                </p>
                                <p>
                                    <small class="text-small">
                                        {{ date("d M, y", strtotime($schedule['departure']['date'])) }}
                                    </small>
                                </p>
                            </div>
                            <div class="col-md-4 mb-2 mb-md-0 text-center line-height">
                                <strong> 
                                    {{ date("H:m", strtotime($schedule['departure']['time'])) }} - 
                                    {{ date("H:m", strtotime($schedule['arrival']['time'])) }}
                                </strong>
                                <p>
                                    <small class="text-small">
                                        {{ date("d M, y", strtotime($schedule['departure']['date'])) }} - 
                                        {{ date("d M, y", strtotime($schedule['arrival']['date'])) }}
                                    </small> 
                                </p>
                                <p>
                                    <small class="text-small">
                                        {{ $schedule['departure']['city'] }} - {{ $schedule['arrival']['city'] }}
                                    </small>
                                </p>
                                {{-- <p><small class="text-small">BOEING 737-800 | BS</small> </p> --}}
                            </div>
                            <div class="col-md-3 text-center text-md-end line-height">
                                <strong>Duration</strong>
                                <p><small class="text-small">{{ \App\Helpers\UtilityHelper::minuteToHourMinute($schedule['elapsedTime'], '%02dh %02dm') }}</small> </p>
                                
                                <p>
                                    <small class="text-small"> 
                                        <strong>{{ $schedule['carrier']['marketing'] }} {{ $schedule['carrier']['marketingFlightNumber'] }}</strong> 
                                    </small> 
                                </p>

                                <p><small class="text-small">Class: {{ $flight['passengerInfoList'][0]['bookingCode'] }}</small> </p>
                            </div>
                            @if((count($leg['schedules']) - 1) != $sKey)
                                <hr class="mb-4 mt-3">
                            @endif
                        </div>
                        @endforeach
                        <!-- end scedules -->

                    </div>
                    @endforeach
                    <!-- end single flight details -->

                </div>
            </div>

        </div>
        @endforeach

        <div class="text-center w-100">
            {{ $theFlights->links() }}
        </div>
        
    </div>
    <!-- End results-->

</div>
@endif

<!-- loading-->
@if (!$readyToLoad && !$errors->any())
    <div class="initLoading">
        <lottie-player src="https://assets5.lottiefiles.com/private_files/lf30_dy2xdidy.json"  background="transparent"  speed="1"  style="width: 400px; height: 400px;"  loop  autoplay></lottie-player>
        <div>
            <h5>Searching flights....</h5>
        </div>
    </div>
@endif
<!-- End loading-->
<div 
    wire:loading 
    wire:target="filterByAPI,filterByStops,clearApiFilter,previousPage,nextPage,gotoPage,clearApiFilter,api_source,stops,airline"
    >
    <x-loading />
</div>



<script>
    let searchForm = () => {
        return {
            'type': @entangle('type').defer,
            'from': @entangle('from').defer,
            'to': @entangle('to').defer,
            'depart_date': @entangle('depart_date').defer,
            'return_date': @entangle('return_date').defer,
            'show_modify_form': @entangle('show_modify_form').defer,
            'bookingclass': @entangle('class').defer,
            'bookingClassName': '',
            'people': @entangle('people').defer,
            'children_dob': @entangle('children_dob').defer,
            'airports' : [],
            'airportFrom' : '',
            'airportTo' : '',
            'searchingAirport' : '',

            'multi_cities' : @entangle('multi_cities').defer,
            'multiFromIndex': '',
            'totalPeopls': 0,

            init() {
                this.airportFrom = this.from;
                this.airportTo = this.to;

                if(this.bookingclass == 'C') {
                    this.bookingClassName = 'Business';
                }
                if(this.bookingclass == 'S') {
                    this.bookingClassName = 'Premium Economy';
                }
                if(this.bookingclass == 'F') {
                    this.bookingClassName = 'First Class';
                }
                if(this.bookingclass == 'Y') {
                    this.bookingClassName = 'Economy';
                }

                this.countPeople();
                this.watchPeople();
            },

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
            },
            showModifyForm() {
                this.show_modify_form = true;
            },
            hideSearchFlight() {
                this.show_modify_form = false;
            },
            searchFlight() {
                this.show_modify_form = false;
                this.$wire.modifySearch();
            },

            watchPeople() {
                this.$watch('people', () => {
                    this.countPeople();
                    console.log('changed');
                });
            },

            countPeople() {
                this.totalPeopls = parseInt(this.people['adults']) + parseInt(this.people['children']) + parseInt(this.people['infants']); 
                if(this.totalPeopls > 1) {
                    this.totalPeopls = this.totalPeopls + ' Persons';
                }else {
                    this.totalPeopls = this.totalPeopls + ' Person'
                }
            }
        }
    }
</script>
</div>
