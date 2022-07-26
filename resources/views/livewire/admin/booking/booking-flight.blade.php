<div wire:init="getBookingByPNR">
<!-- title and menus -->
<div class="row">
    <div class="col-md-6">
        <h1 class="h5 mb-4 text-gray-800">
            Flight Booking Details | 
            <span class="badge badge-secondary text-small">{{ $airBooking->source_api }}</span>
        </h1>
    </div>
    <div class="col-md-6">
        <div class="d-flex justify-content-md-end mb-2 mb-md-0">
            <div class="btn-group">
                <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Action
                    <i class="fas fa-cog"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <button wire:click="showPartialRequestModel" class="dropdown-item">Handle Partial Request</button>
                    <button wire:click="showRefundForm" class="dropdown-item">Handle Refund Request</button>
                    <hr class="mx-4">
                    <button wire:click="markAsTicketIssued" class="dropdown-item">Mark as Ticket Issued</button>
                    <button wire:click="markAsTicketCanceled" class="dropdown-item">Mark as Ticket Canceled</button>
                    <button wire:click="markAsTicketRefunded" class="dropdown-item">Mark as Ticket Refunded</button>
                    <button wire:click="markAsTicketChanged" class="dropdown-item">Mark as Ticket Changed</button>
                </div>
            </div>
        </div>
    </div>
</div>

@include('includes.alert_failed_inner')
@include('includes.alert_success_inner')
@include('includes.errors')


{{-- Partial request form --}}
@if($showPartialForm)
<div wire:key="partial-request-form" class="row mb-4">
    <div class="col">
        <div class="card border border-info">
            <div class="card-header">Handle Partial Request</div>
            <div class="card-body">
                <form wire:submit.prevent="handlePartialRequest">
                    <div class="row mb-3">
                        <div class="col">
                            <label for="">Status*</label>
                            <select wire:model="status" class="form-control form-control-sm">
                                <option value="">Choose</option>
                                <option value="{{ \App\Enums\TransactionEnum::STATUS['APPROVED'] }}">
                                    {{ \App\Enums\TransactionEnum::STATUS['APPROVED'] }}
                                </option>
                                <option value="{{ \App\Enums\TransactionEnum::STATUS['REJECTED'] }}">
                                    {{ \App\Enums\TransactionEnum::STATUS['REJECTED'] }}
                                </option>
                            </select>
                        </div>
                        <div class="col-">
                            <label for="">Approved Amount</label>
                            <input wire:model.lazy="approved_amount" type="number" class="form-control form-control-sm">
                        </div>
                        <div class="col">
                            <label for="">Due date</label>
                            <input wire:model="due_time" type="date" class="form-control form-control-sm">
                        </div>
                    </div>

                    <!-- submit button -->
                    <div class="row">
                        <div class="col d-flex">
                            <div>
                                <button type="submit" class="btn btn-primary btn-sm px-4">Submit</button>
                            </div>

                            <div class="ml-2">
                                <button wire:click="hidePartialForm" type="button" class="btn btn-secondary btn-sm px-4">Cancel</button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Partial request form --}}
@if($showRefundForm)
<div wire:key="refund-request-form" class="row mb-4">
    <div class="col">
        <div class="card border border-info">
            <div class="card-header">Refund Handle Form</div>
            <div class="card-body">
                <form wire:submit.prevent="handleRefund">
                    <div class="row">
                        <div class="col">
                            <label for="">Status*</label>
                            <select wire:model="refund_status" class="form-control form-control-sm">
                                <option value="{{ \App\Enums\TransactionEnum::STATUS['PENDING'] }}">
                                    {{ \App\Enums\TransactionEnum::STATUS['PENDING'] }}
                                </option>
                                <option value="{{ \App\Enums\TransactionEnum::STATUS['COMPLETED'] }}">
                                    {{ \App\Enums\TransactionEnum::STATUS['COMPLETED'] }}
                                </option>
                                <option value="{{ \App\Enums\TransactionEnum::STATUS['CANCELED'] }}">
                                    {{ \App\Enums\TransactionEnum::STATUS['CANCELED'] }}
                                </option>
                            </select>
                        </div>
                        <div class="col-">
                            <label for="">Refund Amount</label>
                            <input wire:model.lazy="refund_amount" type="number" class="form-control form-control-sm">
                        </div>
                        <div class="col">
                            <label for="">Refund Fee</label>
                            <input wire:model.lazy="refund_fee" type="number" class="form-control form-control-sm">
                        </div>
                        <div class="col">
                            <label for="">Refund Method</label>
                            <select wire:model="refund_method" class="form-control form-control-sm">
                                <option value="">Choose</option>
                                @foreach(\App\Enums\TransactionEnum::METHOD as $k => $m)
                                    <option value="{{$k}}">{{$m}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="">Remark from Admin</label>
                            <textarea wire:model.lazy="admin_remark" class="form-control"></textarea>
                        </div>
                    </div>

                    <!-- submit button -->
                    <div class="row mt-3">
                        <div class="col d-flex">
                            <div>
                                <button type="submit" class="btn btn-primary btn-sm px-4">Submit</button>
                            </div>

                            <div class="ml-2">
                                <button wire:click="hideRefundForm" type="button" class="btn btn-secondary btn-sm px-4">Cancel</button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endif

<div class="row">
    {{-- left --}}
    <div class="col-8">

        {{-- booking US_BANGLA--}}
        @if($airBooking->source_api == \App\Helpers\ConstantHelper::US_BANGLA)
            @foreach($airBooking->responseJsonDecode()['Booking']['Segments'] as $segment)
                <div class="row rounded bg-white pb-3 mb-3 mx-1 text-dark border w-100">
                    <div class="row mx-0 bg-light pt-3 pb-3 mb-3 w-100">
                        <div class="col d-flex align-items-center">
                            <div class="col-5 col-sm-auto text-center text-sm-left">
                                <h5 class="m-0 trip-place">{{ $segment['OriginCode'] }}</h5>
                            </div>
                            <div class="col-2 col-sm-auto text-8 text-black-50 text-center trip-arrow">➝</div>
                            <div class="col-5 col-sm-auto text-center text-sm-left">
                                <h5 class="m-0 trip-place">{{ $segment['DestinationCode'] }}</h5>
                            </div>
                            <div class="col-12 mt-1 d-block d-md-none"></div>
                            <div class="col-6 col-sm col-md-auto text-3 date">
                                {{ date('D', strtotime($segment['FlightInfo']['DepartureDate'])) }},
                                {{ date('d F Y', strtotime($segment['FlightInfo']['DepartureDate'])) }}
                            </div>
                            <div class="col col-md-auto text-center ml-auto order-sm-0"><span class="badge badge-success py-1 px-2 font-weight-normal text-1">{{ $segment['BookingClass']['StatusCode'] }} <i class="fas fa-check-circle"></i></span></div>
                        </div>
                    </div>

                    <div class="row px-0 mx-0 w-100">
                        <div class="col-md-4 d-flex align-items-center">
                            <img class="mr-2 rounded" src="https://tbbd-flight.s3.ap-southeast-1.amazonaws.com/airlines-logo/{{$segment['AirlineDesignator']}}.png" alt="airline" width="40">
                            <div class="d-flex flex-column">
                                <a href="" class="text-3 text-dark  mr-2">{{ $segment['AirlineDesignator'] }}</a>
                                <small class="text-muted d-block">Flight No: {{ $segment['FlightInfo']['FlightNumber'] }}</small> </span>
                            </div>
                        </div>
                        <div class="col">
                            <div class="text-center d-inline-block">
                                <p class="text-dark text-4">{{ date('H:m', strtotime($segment['FlightInfo']['DepartureDate'])) }}</p>
                                <span class="badge bg-offwhite text-gray px-4 text-2">{{ $segment['OriginCode'] }}</span>
                                <div class="text-small bg-light mt-1 text-gray rounded">{{ date('d F Y', strtotime($segment['FlightInfo']['DepartureDate'])) }}</div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="h-100 pl-0 pl-md-3 d-flex flex-column justify-content-center text-small">
                                <div class="d-inline-block">
                                    <div>{{ App\Helpers\UtilityHelper::minuteToHourMinute($segment['FlightInfo']['DurationMinutes'], '%02dh %02dm') }}</div>
                                    <span class="pl-1">Duration</span>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="text-center d-inline-block" >
                                <p class="text-dark text-4">{{ date('H:m', strtotime($segment['FlightInfo']['ArrivalDate'])) }}</p>
                                <span class="badge bg-offwhite text-gray px-4 text-2">{{ $segment['DestinationCode'] }}</span>
                                <div class="text-small bg-light mt-1 text-gray rounded">{{ date('d F Y', strtotime($segment['FlightInfo']['ArrivalDate'])) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
        
        {{-- booking SABRE --}}
        @if($airBooking->source_api == \App\Helpers\ConstantHelper::SABRE)
            @if($isGetBookingSuccess || isset($airBooking->sabreGetBookingResJsonDecode()['journeys']))
                @foreach($airBooking->sabreGetBookingResJsonDecode()['journeys'] as $k => $journey)
                <div wire:key="{{ uniqid() }}" class="row rounded bg-white pb-3 mb-3 mx-1 border rounded text-dark">
                    <div class="row w-100 mx-0 bg-light pt-3 pb-3 mb-3">
                        <div class="col d-flex align-items-center">
                            <div class="col-5 col-sm-auto text-center text-sm-left">
                                <h5 class="m-0 trip-place">{{ $journey['firstAirportCode'] }}</h5>
                            </div>
                            <div class="col-2 col-sm-auto text-8 text-black-50 text-center trip-arrow px-0">➝</div>
                            <div class="col-5 col-sm-auto text-center text-sm-left">
                                <h5 class="m-0 trip-place">{{ $journey['lastAirportCode'] }}</h5>
                            </div>
                            <div class="col-12 mt-1 d-block d-md-none"></div>
                            <div class="col-6 col-sm col-md-auto text-3 date">
                                {{ $journey['departureTime'] }},
                                {{ date('d F Y', strtotime($journey['departureDate'])) }}
                            </div>
                            <div class="col col-md-auto text-center ml-auto order-sm-0"><span class="badge badge-success py-1 px-2 font-weight-normal text-1"> Confirmed <i class="fas fa-check-circle"></i></span></div>
                        </div>
                    </div>

                    @foreach ($airBooking->sabreSegmentsBasedOnJourney($journey) as $sKey => $segment)

                    @if($sKey > 0)
                        <div class="row w-100 flight-divider mx-0"></div>
                    @endif

                    <div wire:key="segment-{{$k}}-{{$sKey}}" class="row w-100 px-0 mx-0">
                        <div class="col-md-4 d-flex align-items-center">
                            <img class="mr-2 rounded" src="https://tbbd-flight.s3.ap-southeast-1.amazonaws.com/airlines-logo/{{$segment['airlineCode']}}.png" alt="airline" width="40">
                            <div class="d-flex flex-column">
                                <a href="#" class="text-3 text-dark  mr-2">{{ $segment['airlineCode'] }}</a>
                                <small class="text-muted d-block text-small">{{ $segment['aircraftTypeName'] }}</small> </span>
                            </div>
                        </div>
                        <div class="col">
                            <div class="text-center d-inline-block">
                                <p class="text-dark text-4">{{ \Carbon\Carbon::create($segment['departureTime'])->format('H:i') }}</p>
                                <span class="badge bg-offwhite text-gray px-4 text-2">{{ $segment['fromAirportCode'] }}</span>
                                <div class="text-small bg-light mt-1 text-gray rounded">{{ date('d F Y', strtotime($segment['departureDate'])) }}</div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="h-100 pl-0 pl-md-3 d-flex flex-column justify-content-center">
                                <div class="d-inline-block text-small">
                                    <div>
                                        {{ \App\Helpers\UtilityHelper::minuteToHourMinute($segment['durationInMinutes'], '%02dh %02dm') }}
                                    </div>
                                    <span class="pl-1">Duration</span>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="text-center d-inline-block" >
                                <p class="text-dark text-4">{{ \Carbon\Carbon::create($segment['arrivalTime'])->format('H:i') }}</p>
                                <span class="badge bg-offwhite text-gray px-4 text-2">{{ $segment['toAirportCode'] }}</span>
                                <div class="text-small bg-light mt-1 text-gray rounded">{{ date('d F Y', strtotime($segment['arrivalDate'])) }}</div>
                            </div>
                        </div>
                    </div>
                    @endforeach

                </div>
                @endforeach
            @endif
        @endif

        {{-- Pre Purchased Air Ticket --}}
        @if($airBooking->source_api == \App\Helpers\ConstantHelper::PRE)
            <div class="row rounded bg-white pb-3 mb-3 mx-1 text-dark border w-100">
                <div class="row mx-0 bg-light pt-3 pb-3 mb-3 w-100">
                    <div class="col d-flex align-items-center">
                        <div class="col-5 col-sm-auto text-center text-sm-left">
                            <h5 class="m-0 trip-place">{{ $prePurchasedFlight['from'] }}</h5>
                        </div>
                        <div class="col-2 col-sm-auto text-8 text-black-50 text-center trip-arrow">➝</div>
                        <div class="col-5 col-sm-auto text-center text-sm-left">
                            <h5 class="m-0 trip-place">{{ $prePurchasedFlight['to'] }}</h5>
                        </div>
                        <div class="col-12 mt-1 d-block d-md-none"></div>
                        <div class="col-6 col-sm col-md-auto text-3 date">
                            {{ date('d F Y', strtotime($prePurchasedFlight['depart_date'])) }}
                        </div>
                        <div class="col col-md-auto text-center ml-auto order-sm-0">
                        <span class="badge badge-success py-1 px-2 font-weight-normal text-1">
                            <i class="fas fa-check-circle"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="row px-0 mx-0 w-100">
                    <div class="col-md-4 d-flex align-items-center">
                        <img class="mr-2 rounded" src="https://tbbd-flight.s3.ap-southeast-1.amazonaws.com/airlines-logo/{{$prePurchasedFlight['airline']}}.png" alt="airline" width="40">
                        <div class="d-flex flex-column">
                            <a href="" class="text-3 text-dark  mr-2">{{ $prePurchasedFlight['airline'] }}</a>
                        </div>
                    </div>
                    <div class="col">
                        <div class="text-center d-inline-block">
                            <span class="badge bg-offwhite text-gray px-4 text-2">{{ $prePurchasedFlight['from'] }}</span>
                            <div class="text-small bg-light mt-1 text-gray rounded">{{ date('d F Y', strtotime($prePurchasedFlight['depart_date'])) }}</div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="h-100 pl-0 pl-md-3 d-flex flex-column justify-content-center text-small">
                            <div class="d-inline-block">
                                <div>{{$prePurchasedFlight['transit_time']}}</div>
                                <span class="pl-1">{{$prePurchasedFlight['transit_location']}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="text-center d-inline-block" >
                            <span class="badge bg-offwhite text-gray px-4 text-2">{{ $prePurchasedFlight['to'] }}</span>
                            <div class="text-small bg-light mt-1 text-gray rounded">{{ date('d F Y', strtotime($prePurchasedFlight['arrival_date'])) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        @endif


        {{-- Passengers --}}
        <div class="card mb-4 text-dark">
            <div class="card-header">
                Passengers
            </div>
            <div class="card-body py-0 px-3">
                <div wire:ignore class="mt-3">
                    @foreach($airBooking->airBookingPassengers as $k => $passanger)
                    <table class="table table-sm table-bordered table-striped text-small">
                        <tr class="small">
                            <td>
                                <p class="text-small">Name:</p>
                                <b class="text-uppercase">{{ $passanger->title }} {{ $passanger->fullName() }}</b>
                            </td>
                            <td>
                                <p class="text-small">Gender:</p>
                                <b class="text-uppercase">{{ $passanger->gender }}</b>
                            </td>
                            <td>
                                <p class="text-small">Date of Birth:</p>
                                <b class="text-uppercase">{{ $passanger->dob }}</b>
                            </td>
                            <td>
                                <p class="text-small">Nationality:</p>
                                <b class="text-uppercase">{{ $passanger->nationality_country }}</b>
                            </td>
                        </tr>
                        <tr class="small">
                            <td>
                                <p class="text-small">Passport No:</p>
                                <b class="text-uppercase">{{ $passanger->passport_no }}</b>
                            </td>
                            <td>
                                <p class="text-small">Passport Type:</p>
                                <b class="text-uppercase">{{ $passanger->passport_type }}</b>
                            </td>
                            <td>
                                <p class="text-small">Passport Issuance Date:</p>
                                <b class="text-uppercase">{{ $passanger->passport_issuance_date }}</b>
                            </td>
                            <td>
                                <p class="text-small">Passport Expiry Date:</p>
                                <b class="text-uppercase">{{ $passanger->passport_expiry_date }}</b>
                            </td>
                        </tr>


                        <tr class="small">
                            <td>
                                <p class="text-small">Phone No:</p>
                                <b class="text-uppercase">{{ $passanger->phone_no }}</b>
                            </td>
                            <td>
                                <p class="text-small">Passport:</p>
                                <b>
                                    <a class="text-uppercase" href="{{ asset($passanger->passport) }}">View</a>
                                </b>
                            </td>
                            <td>
                                <p class="text-small">Visa:</p>
                                <b>
                                    <a class="text-uppercase" href="{{ asset($passanger->visa) }}">View</a>
                                </b>
                            </td>
                            <td></td>
                        </tr>
                    </table>
                        @if((count($airBooking->airBookingPassengers) -1) != $k ) <hr> @endif
                    @endforeach
                </div>
            </div>
        </div>

        {{-- baggage SABRE --}}
        @if($airBooking->source_api == \App\Helpers\ConstantHelper::SABRE)
            <div class="card text-dark mb-4">
                <div class="card-header">
                    Baggage Information
                </div>
                <div class="card-body">
                    <div class="line-height-1">
                        @foreach($airBooking->responseJsonDecode()['CreatePassengerNameRecordRS']['AirPrice'][0]['PriceQuote']['MiscInformation']['HeaderInformation'][0]['Text'] as $text)
                            <small>{{ $text }}</small> <br>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif


        {{-- payments --}}
        <div class="card text-dark">
            <div class="card-header">
                Payments
            </div>
            <div class="card-body">
                <table class="table table-sm table-bordered table-striped">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Date</th>
                            <th scope="col">Purpose</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Method</th>
                            <th scope="col">Status</th>
                            <th scope="col">Remark</th>
                            <th scope="col" class="text-center">PaidBy</th>
                        </tr>
                    </thead>
                    <tbody class="small text-dark">
                        @foreach($airBooking->payments as $k => $payment)
                        <tr wire:key="{{ uniqid() }}" class="text-small">
                            <th scope="row" class="pl-1 text-center">{{ $k + 1 }}</th>
                            <td>{{ date('Y-m-d', strtotime($payment->created_at)) }}</td>
                            <td>{{ str_replace('_', ' ', $payment->purpose) }}</td>
                            <td class="font-weight-bold">{{ number_format($payment->amount, 2) }}</td>
                            <td title="{{ $payment->method }}">{{ mb_strimwidth($payment->method, 0, 15, "..."); }}</td>
                            <td class="text-center"> 
                            <span class="
                                badge 
                                @if(in_array($payment->status, [
                                    \App\Enums\TransactionEnum::STATUS['PROCESSING'],
                                    \App\Enums\TransactionEnum::STATUS['COMPLETED'],
                                    \App\Enums\TransactionEnum::STATUS['PAID'],
                                ])) 
                                    {{'badge-success'}} 
                                @elseif(in_array($payment->status, [
                                    \App\Enums\TransactionEnum::STATUS['FAILED'],
                                    \App\Enums\TransactionEnum::STATUS['CANCELED'],
                                ])) 
                                    {{'badge-danger'}} 
                                @elseif(in_array($payment->status, [
                                    \App\Enums\TransactionEnum::STATUS['PARTIAL_PAID'],
                                ])) 
                                    {{'badge-warning'}} 
                                @else 
                                    {{'badge-secondary'}} 
                                @endif"
                            >
                                {{ $payment->status }}
                            </span> 
                            </td>
                            <td>
                                <textarea readonly class="form-control form-control-sm text-small" cols="3" rows="1">{{ $payment->remark }}</textarea>
                            </td>
                            <td class="text-center">{{ $payment->createdBy->name }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- right --}}
    <aside class="col-4">
        {{-- booking status --}}
        @if($airBooking->airTicket)
        <div class="bg-white shadow-md rounded p-3 mb-3">
            <table class="w-100 text-dark">
                <tr>
                    <td>Ticket Status</td>
                    <td>
                        <span class="text-1 font-weight-500 text-dark">:</span>
                        @if(in_array($airBooking->airTicket->status, [
                            App\Enums\FlightEnum::STATUS['CONFIRMED'],
                            App\Enums\FlightEnum::STATUS['CHANGED'],
                        ]))
                            <span class="text-1 font-weight-500 badge badge-success">TICKET ISSUE {{ $airBooking->airTicket->status }}</span>
                        @else
                            <span class="text-1 font-weight-500 badge badge-danger">TICKET ISSUE {{ $airBooking->airTicket->status }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Issued On</td>
                    <td>
                        <span class="text-1 font-weight-500 text-dark">:</span>
                        <span class="text-1">
                            {{ date('Y-m-d', strtotime($airBooking->airTicket->issued_at)) }}
                        </span>
                        <span class="text-extra-small badge badge-secondary">
                            {{ \Carbon\Carbon::create($airBooking->airTicket->issued_at)->diffForHumans() }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Issued By</td>
                    <td>
                        <span class="text-1 font-weight-500 text-dark">:</span>
                        <span class="text-1">{{ $airBooking->airTicket->issuedBy->name ?? '' }}</span>
                    </td>
                </tr>
            </table>
        </div>
        @endif

        {{-- status details --}}
        <div class="bg-white shadow-md rounded p-3 mb-3">
            <table class="w-100 text-dark">
                <tr>
                    <td>Booking date</td>
                    <td><span class="text-1 font-weight-500 text-dark">: {{ \Carbon\Carbon::create($airBooking->created_at)->format('Y-m-d h:i a') }}</span></td>
                </tr>
                <tr>
                    <td>Booking PNR</td>
                    <td><span class="text-1 font-weight-500 text-dark">: {{ $airBooking->confirmation_id }}</span></td>
                </tr>
                <tr>
                    <td>Reference</td>
                    <td><span class="text-1 font-weight-500 text-dark">: {{ $airBooking->reference }}</span></td>
                </tr>
                <tr>
                    <td>Airline PNR</td>
                    <td><span class="text-1 font-weight-500 text-dark">: {{ $airBooking->airline_confirmation_id }}</span></td>
                </tr>
                <tr>
                    <td>Time limit</td>
                    <td>: 
                        <span class="text-1 font-weight-500 badge badge-secondary"></span>
                    </td>
                </tr>
            </table>
        </div>


        {{-- fare details SABRE--}}
        @if($airBooking->source_api == \App\Helpers\ConstantHelper::SABRE)
            <div class="
                    bg-white shadow-md rounded p-3 mb-3 text-dark

                    @if($airBooking->payment_status == \App\Enums\TransactionEnum::STATUS['PAID'])
                        border border-success
                    @endif
                    @if($airBooking->payment_status == \App\Enums\TransactionEnum::STATUS['UNPAID'])
                        border border-danger
                    @endif
                    @if($airBooking->payment_status == \App\Enums\TransactionEnum::STATUS['PARTIAL_PAID'])
                        border border-warning
                    @endif
                ">
                <h3 class="text-3 mb-3 d-flex font-weight-700 justify-content-between">
                    Fare Details
                    @if($airBooking->payment_status == \App\Enums\TransactionEnum::STATUS['PAID'])
                        <span class="badge badge-success">PAID</span>
                    @endif
                    @if($airBooking->payment_status == \App\Enums\TransactionEnum::STATUS['UNPAID'])
                        <span class="badge badge-danger">UNPAID</span>
                    @endif
                    @if($airBooking->payment_status == \App\Enums\TransactionEnum::STATUS['PARTIAL_PAID'])
                        <span class="badge badge-warning">PARTIAL PAID</span>
                    @endif
                </h3>
                <hr class="mx-n3">
                <table class="w-100 mb-2">
                    <tr>
                        <td>Base Fare</td>
                        <td><span class="text-1 font-weight-500 text-dark">: {{ $airBooking->currency }} {{ number_format($airBooking->responseJsonDecode()['CreatePassengerNameRecordRS']['AirPrice'][0]['PriceQuote']['PricedItinerary']['TotalAmount'], 2) }}</span></td>
                    </tr>
                    <tr>
                        <td>Taxes</td>
                        <td><span class="text-1 text-dark">: {{ $airBooking->currency }} {{ number_format($airBooking->responseJsonDecode()['CreatePassengerNameRecordRS']['AirPrice'][0]['PriceQuote']['PricedItinerary']['AirItineraryPricingInfo'][0]['ItinTotalFare']['Taxes']['TotalAmount'], 2) }}</span></td>
                    </tr>
                    <tr>
                        <td class="font-weight-700">Total Fare</td>
                        <td><span class="text-1 text-dark font-weight-700">: {{ $airBooking->currency }} {{ number_format($airBooking->amount, 2) }}</span></td>
                    </tr>
                    <tr class="">
                        <td class="">Due Amount</td>
                        <td><span class="text-1 text-danger">: {{ $airBooking->currency }} {{ number_format($airBooking->amount - $airBooking->payments->sum('amount'), 2) }}</span></td>
                    </tr>
                    <tr>
                        <td class="font-weight-700">Total Paid Amount</td>
                        <td><span class="text-1 text-success font-weight-700">: {{ $airBooking->currency }} {{ number_format($airBooking->payments->sum('amount'), 2) }}</span></td>
                    </tr>
                </table>

                {{-- <button wire:click="issueTicket" class="btn btn-primary btn-block text-uppercase p-2 @if($this->airBooking->airTicket) disabled @endif" type="button">Issue Ticket</button> --}}
            </div>
        @endif

        {{-- fare details US_BANGLA--}}
        @if($airBooking->source_api == \App\Helpers\ConstantHelper::US_BANGLA)
            <div class="
                    bg-white shadow-md rounded p-3 mb-3 text-dark

                    @if($airBooking->payment_status == \App\Enums\TransactionEnum::STATUS['PAID'])
                        border border-success
                    @endif
                    @if($airBooking->payment_status == \App\Enums\TransactionEnum::STATUS['UNPAID'])
                        border border-danger
                    @endif
                    @if($airBooking->payment_status == \App\Enums\TransactionEnum::STATUS['PARTIAL_PAID'])
                        border border-warning
                    @endif
                ">
                <h3 class="text-3 mb-3 d-flex font-weight-700 justify-content-between">
                    Fare Details
                    @if($airBooking->payment_status == \App\Enums\TransactionEnum::STATUS['PAID'])
                        <span class="badge badge-success">PAID</span>
                    @endif
                    @if($airBooking->payment_status == \App\Enums\TransactionEnum::STATUS['UNPAID'])
                        <span class="badge badge-danger">UNPAID</span>
                    @endif
                    @if($airBooking->payment_status == \App\Enums\TransactionEnum::STATUS['PARTIAL_PAID'])
                        <span class="badge badge-warning">PARTIAL PAID</span>
                    @endif
                </h3>
                <hr class="mx-n3">
                <table class="w-100 mb-2">
                    <tr>
                        <td>Base Fare</td>
                        <td><span class="text-1 font-weight-500 text-dark">: {{ $airBooking->currency }} {{ number_format($airBooking->responseJsonDecode()['Booking']['FareInfo']['SaleCurrencyAmountTotal']['BaseAmount'], 2) }}</span></td>
                    </tr>
                    <tr>
                        <td>Taxes</td>
                        <td><span class="text-1 font-weight-500 text-dark">: {{ $airBooking->currency }} {{ number_format($airBooking->responseJsonDecode()['Booking']['FareInfo']['SaleCurrencyAmountTotal']['TaxAmount'], 2) }}</span></td>
                    </tr>
                    <tr>
                        <td class="font-weight-700">Total Fare</td>
                        <td><span class="text-1 font-weight-700 text-dark">: {{ $airBooking->currency }} {{ number_format($airBooking->amount, 2) }}</span></td>
                    </tr>
                    <tr>
                        <td class="">Due Amount</td>
                        <td><span class="text-1 text-danger">: {{ $airBooking->currency }} {{ number_format($airBooking->amount - $airBooking->payments->sum('amount'), 2) }}</span></td>
                    </tr>
                    <tr>
                        <td class="font-weight-700">Total Paid Amount</td>
                        <td><span class="text-1 font-weight-700 text-success">: {{ $airBooking->currency }} {{ number_format($airBooking->payments->sum('amount'), 2) }}</span></td>
                    </tr>
                </table>
            </div>
        @endif

        {{-- fare details US_BANGLA--}}
        @if($airBooking->source_api == \App\Helpers\ConstantHelper::PRE)
            <div class="
                    bg-white shadow-md rounded p-3 mb-3 text-dark

                    @if($airBooking->payment_status == \App\Enums\TransactionEnum::STATUS['PAID'])
                        border border-success
                    @endif
                    @if($airBooking->payment_status == \App\Enums\TransactionEnum::STATUS['UNPAID'])
                        border border-danger
                    @endif
                    @if($airBooking->payment_status == \App\Enums\TransactionEnum::STATUS['PARTIAL_PAID'])
                        border border-warning
                    @endif
                ">
                <h3 class="text-3 mb-3 d-flex font-weight-700 justify-content-between">
                    Fare Details
                    @if($airBooking->payment_status == \App\Enums\TransactionEnum::STATUS['PAID'])
                        <span class="badge badge-success">PAID</span>
                    @endif
                    @if($airBooking->payment_status == \App\Enums\TransactionEnum::STATUS['UNPAID'])
                        <span class="badge badge-danger">UNPAID</span>
                    @endif
                    @if($airBooking->payment_status == \App\Enums\TransactionEnum::STATUS['PARTIAL_PAID'])
                        <span class="badge badge-warning">PARTIAL PAID</span>
                    @endif
                </h3>
                <hr class="mx-n3">
                <table class="w-100 mb-2">
                    <tr>
                        <td>Base Fare</td>
                        <td><span class="text-1 font-weight-500 text-dark">: {{ $airBooking->currency }} {{ number_format($airBooking->amount) }}</span></td>
                    </tr>
                    <tr>
                        <td>Taxes</td>
                        <td><span class="text-1 font-weight-500 text-dark">: {{ $airBooking->currency }} 00</span></td>
                    </tr>
                    <tr>
                        <td class="font-weight-700">Total Fare</td>
                        <td><span class="text-1 font-weight-700 text-dark">: {{ $airBooking->currency }} {{ number_format($airBooking->amount, 2) }}</span></td>
                    </tr>
                    <tr>
                        <td class="">Due Amount</td>
                        <td><span class="text-1 text-danger">: {{ $airBooking->currency }} {{ number_format($airBooking->amount - $airBooking->payments->sum('amount'), 2) }}</span></td>
                    </tr>
                    <tr>
                        <td class="font-weight-700">Total Paid Amount</td>
                        <td><span class="text-1 font-weight-700 text-success">: {{ $airBooking->currency }} {{ number_format($airBooking->payments->sum('amount'), 2) }}</span></td>
                    </tr>
                </table>
            </div>
        @endif


        {{-- partial request status --}}
        @if($this->airBooking->bookingPartialRequest)
        <div class="bg-white shadow-md rounded p-3 mb-3">
            <h3 class="text-3 font-weight-700 mb-3 d-flex justify-content-between text-dark">
                Partial Request Status

                @if($this->airBooking->bookingPartialRequest->status == \App\Enums\TransactionEnum::STATUS['PENDING'])
                    <span class="badge badge-warning">PENDING</span>
                @endif
                @if($this->airBooking->bookingPartialRequest->status == \App\Enums\TransactionEnum::STATUS['APPROVED'])
                    <span class="badge badge-success">APPROVED</span>
                @endif
                @if($this->airBooking->bookingPartialRequest->status == \App\Enums\TransactionEnum::STATUS['REJECTED'])
                    <span class="badge badge-danger">REJECTED</span>
                @endif
            </h3>
            <hr class="mx-n3">
            <table class="w-100 mb-2 text-dark">
                <tr>
                    <td>Approved Amount</td>
                    <td>
                        <span class="text-1 font-weight-500 text-dark">: 
                            @if($this->airBooking->bookingPartialRequest)
                                BDT {{ number_format($this->airBooking->bookingPartialRequest->approved_amount, 2) }}
                            @endif
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Due Date</td>
                    <td>
                    <span class="text-1 font-weight-500 text-dark">: 
                        @if($this->airBooking->bookingPartialRequest)
                            {{ $this->airBooking->bookingPartialRequest->due_time }}
                        @endif
                    </span>
                    </td>
                </tr>
            </table>

            @if(isset($this->airBooking->bookingPartialRequest->status) 
                && $this->airBooking->bookingPartialRequest->status 
                == \App\Enums\TransactionEnum::STATUS['APPROVED']
            )
                <div class="text-dark bg-light-4 text-1 p-3 mb-3 line-height-1">
                    Click Action->ISSUE TICKET. <br> Your partial payment request has approved.
                </div>
            @endif
            {{-- <button wire:click="partialPaymentRequest" class="btn btn-secondary btn-block text-uppercase p-2 @if($this->airBooking->bookingPartialRequest || $airBooking->payment_status == \App\Enums\TransactionEnum::STATUS['PAID']) disabled @endif" type="button">Request Partial Payment</button> --}}
        </div>
        @endif

        {{-- remark --}}
        <div class="bg-white shadow-md rounded p-3 mb-3">
            <h3 class="text-3 font-weight-700 mb-3 d-flex justify-content-between text-dark">
                Ticket Issue Related Remark
            </h3>
            <textarea readonly class="form-control h-25">{{ $this->airBooking->airTicket->remark ?? '' }}</textarea>
        </div>
    </aside>

</div>

{{-- loading --}}
<div wire:loading 
    wire:target="handlePartialRequest,
        showPartialRequestModel,
        markAsTicketCanceled,
        markAsTicketRefunded,
        hidePartialForm,
        handleRefund,
        markAsTicketChanged,
        showRefundForm,
        hideRefundForm"
    >
    <x-loading />
</div>

</div>
