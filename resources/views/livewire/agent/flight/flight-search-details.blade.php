<div class="h-100" wire:poll.30000ms="revalidate">

<div wire:loading wire:target="book">
    <x-loading />
</div>

<section class="page-header bg-light mb-0">
    <div class="container">
    <div class="row align-items-center">
        <div class="col-md-8">
        <h6 class="">Flight information & Booking</h6>
        </div>
        <div class="col-md-4">
        <!-- <ul class="breadcrumb justify-content-start justify-content-md-end mb-0">
            <li><a href="index.html">Home</a></li>
            <li class="active">About Us</li>
        </ul> -->
        </div>
    </div>
    </div>
</section>

<div class="px-3 mt-2">
    @include('includes.errors')
    @include('includes.alert-failed-center')
    @include('includes.alert-success-center')
</div>


@if(!empty($booking_id))
<div wire:key="booking-success" class="row m-3 mb-0">
    <div class="alert alert-success mb-0 line-height-1">
        <p class="m-0 text-small font-weight-bold">Booking successful</p>
        <a class="text-small" href="{{ route('b2b.flight.show', $this->booking_id) }}">View Booking</a>
    </div>
</div>
@endif

<div class="row rounded bg-white pb-3 m-3">
    <div class="row mx-0 bg-light pt-3 pb-3 mb-3">
        <div class="col d-flex align-items-center">
            <img class="mr-2 rounded" src="https://tbbd-flight.s3.ap-southeast-1.amazonaws.com/airlines-logo/{{$flight['legs'][0]['schedules'][0]['carrier']['marketing'] ?? ''}}.png" alt="airline" width="40">
            <div class="d-flex align-items-center">
                <a  href="#" class="text-3 font-weight-bold text-dark mr-2">
                    {{ $flight['legs'][0]['schedules'][0]['carrier']['marketing_name'] }}
                </a>
                <span class="badge badge-secondary badge-flight-search text-x-small">{{$flight['apiSource']}}</span> <br>
            </div>
        </div>
    </div>

    <div class="row mx-0 px-0 line-height-1 mb-4">
        <div class="col-md-7 px-0 mx-0">

            @foreach($flight['legs'] as $legKey => $leg)

            @if($legKey > 0)
                <div class="flight-divider"></div>
            @endif

            <div wire:key="leg-key-{{$legKey}}" class="row px-0 mx-0">
                <div class="col-md-4">
                    <div class="text-center d-inline-block">
                        <p class="text-dark text-4">{{ $leg['schedules'][0]['departure']['time'] }}</p>
                        <span class="badge bg-offwhite text-gray px-4 text-2">{{ $leg['schedules'][0]['departure']['city'] }}</span>
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
                            {{ $leg['schedules'][count($leg['schedules']) - 1]['arrival']['city'] }}
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

                <strong  class="text-secondary">
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
                <strong class="">
                    @php 
                        $seatsAvailable = 0;
                    @endphp

                    @foreach($flight['passengerInfoList'] as $passengerInfo)
                        @php $seatsAvailable += $passengerInfo['seatsAvailable']; @endphp
                    @endforeach
                    {{ $seatsAvailable }} Seats
                </strong>
            </div>
        </div>
        <div class="col-md-3">
            <div>
                <h6 class="mb-1 pl-1">
                    <h6 class="mb-1 pl-1 text-0-9 font-weight-bold">
                        {{ $flight['pricingInfo']['currency'] }} 
                        {{ number_format($flight['pricingInfo']['totalPrice'], 2) }}
                    </h6>
                </h6>
                <div class="bg-light px-2 py-2 text-1 text-left rounded mb-2">
                    <span class="mb-1">Agent Price:</span><br>
                    <strong class="text-success text-2">
                        {{ $flight['pricingInfo']['currency'] }} 
                        {{ number_format($flight['pricingInfo']['totalAgentPriceWithMargin'], 2) }}
                    </strong>
                </div>
            </div>

        </div>
    </div>

    <div class="row @if(count($flight['legs']) > 1) mt-3 @endif">
        <div class="col px-0">
            <button type="button" onclick="toggle('ticket-details-1')" class="btn btn-sm text-small font-weight-bold btn-link text-decoration-none">Flight Details</button>
            <button type="button"  onclick="toggle('priceBreakDown-1')" class="btn btn-sm text-small font-weight-bold btn-link text-decoration-none">Fare Details</button>
        </div>
    </div>
    
    {{-- price breakdown --}}
    <div class="row px-0">
        <div class="col-md-6"></div>
        <div class="col-md-6 px-0">
            <div id="priceBreakDown-1" class="d-none hidden mt-2 bg-light rounded p-2">
                
                {{-- totals --}}
                @php 
                    $totalBase = 0;
                    $totalTax = 0;
                    $finalPrice = 0;
                    $totalPassengers = 0;
                @endphp

                {{-- single person fare --}}
                @foreach ($flight['passengerInfoList'] as $pKey => $passanger)
                    @php 
                        $totalBase += $passanger['totalFare']['baseFareAmount'];
                        $totalTax += $passanger['totalFare']['totalTaxAmount'];
                        $finalPrice += $passanger['totalFare']['totalPrice'];
                        $totalPassengers += $passanger['passengerNumber'];
                    @endphp

                    <div class="d-flex justify-content-between">
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
                        <small>BDT {{ number_format($passanger['totalFare']['baseFareAmount'], 2) }}</small>
                    </div>
                    <div class="d-flex justify-content-between">
                        <small>Tax & Fee x1 :</small>
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
                    <small>BDT {{ number_format($totalBase, 2) }}</small>
                </div>
                <div class="d-flex justify-content-between">
                    <small>Total Tax x{{ $totalPassengers }} </small>
                    <small>BDT {{ number_format($totalTax, 2) }}</small>
                </div>
                <hr>

                <div class="d-flex justify-content-between pb-2">
                    <small class="font-weight-bold">Final Price x{{ $totalPassengers }}</small>
                    <small class="font-weight-bold">BDT {{ number_format($finalPrice, 2) }}</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row px-0 mx-0">
        <div id="ticket-details-1" class="d-none px-0">

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


<hr class="mx-3 my-4">

{{-- passegers info --}}
<div class="px-4">
    <h6 class="text-secondary">Enter Passeger Details</h6>
</div>

@php
    $passengerIndex = 0;
@endphp
@foreach($flight['passengerInfoList'] as $k => $passengerInfo)

    @php 
        $adultNum = 0;
        $childNum = 0;
        $infantNum = 0;
    @endphp

    @for ($i = 0; $i < $passengerInfo['passengerNumber']; $i++)
        <div wire:ignore wire:key="{{$k}}{{$i}}" class="p-3">
            <div class="accordion" id="accordionFlush_{{$k}}_{{$i}}">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="flush-heading_{{$k}}_{{$i}}">
                        <button class="accordion-button @if($passengerIndex != 0) collapsed @endif font-weight-bold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse_{{$k}}_{{$i}}" aria-expanded="@if($passengerIndex == 0){{'true'}}@else{{'false'}}@endif" aria-controls="flush-collapse_{{$k}}_{{$i}}">
                            
                            @if($passengerInfo['passengerType'] == 'ADT')
                                @php $adultNum += 1; @endphp
                                Adult #{{ $adultNum }}
                            @endif
                            @if($passengerInfo['passengerType'][0] == 'C')
                                @php $childNum += 1; @endphp
                                Child #{{ $childNum }}
                            @endif
                            @if($passengerInfo['passengerType'] == 'INF')
                                @php $infantNum += 1; @endphp
                                Infant #{{ $infantNum }}
                            @endif

                        </button>
                    </h2>
                    <div id="flush-collapse_{{$k}}_{{$i}}" class="accordion-collapse @if($passengerIndex == 0) show @else collapse @endif" aria-labelledby="flush-heading_{{$k}}_{{$i}}" data-bs-parent="#accordionFlush_{{$k}}_{{$i}}">
                        <div class="accordion-body">
                            <div class="form-row">
                                <div class="col-sm-2 form-group">
                                <label for="" class="text-small mb-0">Title*</label>
                                <div>
                                    <select wire:model.lazy="passengers.{{$passengerIndex}}.title" class="custom-select">
                                        <option value="">Title</option>
                                        <option>Mr</option>
                                        <option>Ms</option>
                                        <option>Mrs</option>
                                    </select>
                                </div>

                                </div>
                                <div class="col-sm-4 form-group">
                                    <label for="" class="text-small mb-0">First name*</label>
                                    <input wire:model.lazy="passengers.{{$passengerIndex}}.first_name" class="form-control" placeholder="Enter first name" type="text">
                                </div>
                                <div class="col-sm-4 form-group">
                                    <label for="" class="text-small mb-0">Surename*</label>
                                    <input wire:model.lazy="passengers.{{$passengerIndex}}.surname" class="form-control" placeholder="Enter surname" type="text">
                                </div>
                                <div class="col-sm-2 form-group">
                                    <label for="" class="text-small mb-0">Date of birth*</label>
                                    <input wire:model.lazy="passengers.{{$passengerIndex}}.dob" class="form-control" placeholder="Date of birth" type="date">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-sm-2 form-group">
                                    <label for="" class="text-small mb-0">Gender*</label>
                                    <div>
                                        <select wire:model.lazy="passengers.{{$passengerIndex}}.gender" class="custom-select">
                                            <option value="">Select</option>
                                            <option value="M">Male</option>
                                            <option value="F">Female</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4 form-group">
                                    <label for="" class="text-small mb-0">Nationality*</label>
                                    <div>
                                        <select wire:model.lazy="passengers.{{$passengerIndex}}.nationality_country" class="custom-select">
                                            <option value="BD">Bangladesh</option>
                                            <option value="MY">Malaysia</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4 form-group">
                                    <label for="" class="text-small mb-0">Passport No*</label>
                                    <input wire:model.lazy="passengers.{{$passengerIndex}}.passport_no" class="form-control" placeholder="Enter passport number" type="text">
                                </div>

                                <div class="col-sm-2 form-group">
                                    <label for="" class="text-small mb-0">Passport Issue Country*</label>
                                    <div>
                                        <select wire:model="passengers.{{$passengerIndex}}.passport_issuing_country" class="custom-select">
                                            <option value="BD">Bangladesh</option>
                                            <option value="MY">Malaysia</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">

                                @if($flight['apiSource'] == App\Helpers\ConstantHelper::US_BANGLA)
                                <div wire:key="passport-issue-date" class="col-sm-2 form-group">
                                    <label for="" class="text-small mb-0">Passport Issuance date*</label>
                                    <input wire:model.lazy="passengers.{{$passengerIndex}}.passport_issuance_date" class="form-control" type="date">
                                </div>
                                @endif

                                <div class="col-sm-2 form-group">
                                    <label for="" class="text-small mb-0">Passport expiry date*</label>
                                    <input wire:model.lazy="passengers.{{$passengerIndex}}.passport_expiry_date" class="form-control" type="date">
                                </div>
                                {{-- <div class="col-sm-2 form-group">
                                    <label for="" class="text-small mb-0">Passport Type*</label>
                                    <div>
                                        <select wire:model="passengers.{{$passengerIndex}}.passport_type" class="custom-select">
                                            <option value="P">Ordinary</option>
                                        </select>
                                    </div>
                                </div> --}}
                                
                                @if($passengerIndex == 0)
                                <div class="form-row">
                                    <div class="col form-group">
                                        <label for="" class="text-small mb-0">Phone No*</label>
                                        <input wire:model="passengers.{{$passengerIndex}}.phone_no" placeholder="Ex: 01xxxxxxx" class="form-control" type="phone">
                                    </div>
                                </div>
                                @endif

                                <div class="col form-group">
                                    <label for="" class="text-small mb-0">Passport</label>
                                    <input wire:model="passengers.{{$passengerIndex}}.passport" class="form-control" type="file">
                                </div>
                                <div class="col form-group">
                                    <label for="" class="text-small mb-0">Visa Copy</label>
                                    <input wire:model="passengers.{{$passengerIndex}}.visa" class="form-control" type="file">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    @php
        $passengerIndex++;
    @endphp
    @endfor

@endforeach


{{-- baggage & cancelation information --}}
<div class="p-3">
    <div class="accordion" id="accordionFlushExample">
        <div class="accordion-item">
            <h2 class="accordion-header" id="flush-headingOne">
                <button class="accordion-button show font-weight-bold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                    Baggage Info
                </button>
            </h2>
            <div id="flush-collapseOne" class="accordion-collapse collapse show" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                    Baggage allowances: 
                    <span class="font-weight-bold">
                        {{ $flight['passengerInfoList'][0]['baggageAllowances'][0]['weight'] ?? '' }}
                        {{ $flight['passengerInfoList'][0]['baggageAllowances'][0]['unit'] ?? '' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="p-3">
    <button wire:target="passengers.0.passport,passengers.0.visa" wire:loading.class="disabled"  class="btn btn-primary px-5" @if(!empty($booking_id)) disabled @endif wire:click="book" type="submit">Book Filght</button>
    @include('includes.alert_success_text')
</div>
</div>
