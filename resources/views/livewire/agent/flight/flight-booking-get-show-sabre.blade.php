<div wire:init="getBookingByPNR">

    <section class="page-header page-header-text-light bg-light mb-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h6 class="">Booking Confirmation</h6>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb justify-content-start justify-content-md-end mb-0">
                        <li>
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary btn-sm dropdown-toggle py-1 px-5" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-hand-pointer text-white"></i> Actions
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end py-4 px-3">
                                    <li><button wire:click="issueTicket" class="dropdown-item text-uppercase text-white text-center btn btn-sm btn-primary" type="button">Issue Ticket</button></li>
                                    <li class="mt-4"><button wire:click="togglePrint" class="dropdown-item text-uppercase" type="button">Print Ticket <small>(PDF)</small> </button></li>
                                    <li><hr></li>
                                    <li><button data-bs-toggle="modal" data-bs-target="#paymentModal" class="dropdown-item text-uppercase" type="button">Make Partial Payment</button></li>
                                    <li><button wire:click="partialPaymentRequest" class="dropdown-item text-uppercase" type="button">Request Partial Payment</button></li>
                                    <li><hr></li>
                                    <li><button wire:click="refundRequest" class="dropdown-item text-uppercase" type="button">Request Refund</button></li>
                                    <li><hr></li>
                                    <li><button wire:click="cancelBooking" class="dropdown-item text-uppercase text-center btn btn-sm bg-danger text-white" type="button">Cancel Booking</button></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <div class="mx-3">
        @include('includes.errors')
        @include('includes.alert_failed_inner')
        @include('includes.alert_success_inner')
    </div>

    @if($show_print)
    <div wire:key="print-send-box" class="container mb-3">
        <div wire:key="print-ticket-settings" class="row m-1 border border-info rounded">
            <div class="col">
                <div class="p-2">
                    <div class="d-flex mb-2 pt-1">
                        <div class="form-check mr-2">
                            <input wire:model="include_price" class="form-check-input" type="checkbox" value="" id="fare">
                            <label class="form-check-label" for="fare">
                                Include Fare
                            </label>
                        </div>
                        {{-- <div class="form-check mb-2">
                            <input wire:model="sending_email" class="form-check-input" type="checkbox" value="" id="email">
                            <label class="form-check-label" for="email">
                                Send to email
                            </label>
                            <br>
                            @if($send_via_email)
                                <div>
                                    <input type="email" class="form-control form-control-sm" placeholder="Receiver email.">
                                </div>
                            @endif
                        </div> --}}
                    </div>
                    <div>
                        @if($send_via_email)
                            <button wire:click="sendTicket" class="btn btn-sm btn-primary py-1">Send to email</button>
                        @else
                            <button wire:click="printTicket" class="btn btn-sm btn-primary py-1">Download</button>
                        @endif
                        <button wire:click="togglePrint" class="btn btn-sm btn-secondary py-1">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row mx-0">
        <div class="col-md-8" wire:key="booking-container">
            
            @if($isGetBookingSuccess || !empty($airBooking->getJourneysSabre()))
                
                {{-- journey --}}
                @foreach($airBooking->getJourneysSabre() as $k => $journey)
                    <div wire:key="booking-header" class="row mx-0 bg-light pt-3 pb-3  @if($k > 0) mt-3 @endif ">
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
                            @if($airBooking->status == App\Enums\FlightEnum::STATUS['CONFIRMED'])
                                <div class="col col-md-auto text-center ml-auto order-sm-0"><span class="badge badge-success py-1 px-2 font-weight-normal text-1"> Confirmed <i class="fas fa-check-circle"></i></span></div>
                            @endif

                            @if ($airBooking->status == App\Enums\FlightEnum::STATUS['CANCELED'])
                                <div class="col col-md-auto text-center ml-auto order-sm-0"><span class="badge badge-danger py-1 px-2 font-weight-normal text-1"> Canceled <i class="fas fa-check-circle"></i></span></div> 
                            @endif
                        </div>
                    </div>
                    

                    {{-- flights --}}
                    @foreach ($airBooking->getJourneyFlightsSabre($k) as $sKey => $flight)
                        <div wire:key="booking-flight-{{ $sKey }}" class="row bg-white pb-3 mx-0">
                            @if($sKey > 0)
                                <div class="flight-divider mx-0"></div>
                            @endif

                            <div wire:key="segment-{{$k}}-{{$sKey}}" class="row px-0 mx-0">
                                <div class="col-md-4 d-flex align-items-center">
                                    <img class="mr-2 rounded" src="https://tbbd-flight.s3.ap-southeast-1.amazonaws.com/airlines-logo/{{$flight['airlineCode']}}.png" alt="airline" width="40">
                                    <div class="d-flex flex-column">
                                        <a href="#" class="text-3 text-dark mr-2 line-height-1">{{ \App\Models\Airline::where('code', $flight['airlineCode'])->first()->name ?? $flight['airlineCode']}}</a>
                                        <small class="text-muted d-block line-height-1">{{ $flight['aircraftTypeName'] }}</small> </span>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="text-center d-inline-block">
                                        <p class="text-dark text-4">{{ \Carbon\Carbon::create($flight['departureTime'])->format('H:i') }}</p>
                                        <span class="badge bg-offwhite text-gray px-4 text-2">
                                            {{ $flight['fromAirportCode'] }} <br>
                                            <small class="font-weight-normal">
                                                {{\App\Helpers\UtilityHelper::cityCodeToName($flight['fromAirportCode'])}}
                                            </small>
                                        </span>
                                        <div class="text-small bg-light mt-1 text-gray rounded">{{ date('d F Y', strtotime($flight['departureDate'])) }}</div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="h-100 pl-0 pl-md-3 d-flex flex-column justify-content-center">
                                        <div class="d-inline-block">
                                            <div>
                                                {{ \App\Helpers\UtilityHelper::minuteToHourMinute($flight['durationInMinutes'], '%02dh %02dm') }}
                                            </div>
                                            <span class="pl-1">Duration</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="text-center d-inline-block" >
                                        <p class="text-dark text-4">{{ \Carbon\Carbon::create($flight['arrivalTime'])->format('H:i') }}</p>
                                        <span class="badge bg-offwhite text-gray px-4 text-2">
                                            {{ $flight['toAirportCode'] }} <br>
                                            <small class="font-weight-normal">
                                                {{\App\Helpers\UtilityHelper::cityCodeToName($flight['toAirportCode'])}}
                                            </small>
                                        </span>
                                        <div class="text-small bg-light mt-1 text-gray rounded">{{ date('d F Y', strtotime($flight['arrivalDate'])) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                @endforeach
                
            @endif

            {{-- Passengers --}}
            <div wire:ignore class="mt-3">
                <div class="accordion" id="accordionFlush_1">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-heading_1">
                            <button class="accordion-button show text-uppercase font-weight-bold text-2 text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse_1" aria-expanded="false" aria-controls="flush-collapse_1">
                                Passeger Informations
                            </button>
                        </h2>
                        <div id="flush-collapse_1" class="accordion-collapse show collapse" aria-labelledby="flush-heading_1" data-bs-parent="#accordionFlush_1">
                            <div class="accordion-body">
                                @foreach($airBooking->airBookingPassengers as $k => $passanger)
                                    <table class="w-100 text-small line-height-1 table-td-pb-1">
                                        <tr>
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
                                        <tr>
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


                                        <tr>
                                            <td>
                                                <p class="text-small">Phone No:</p>
                                                <b class="text-uppercase">{{ $passanger->phone_no }}</b>
                                            </td>
                                            <td>
                                                <p class="text-small">Passport:</p>
                                                <b>
                                                    <a target="_blank" class="text-uppercase" href="
                                                        @if(isset($passanger->passport()->file))
                                                            {{ $passanger->passport()->file }}
                                                        @endif
                                                    ">View</a>
                                                </b>
                                            </td>
                                            <td>
                                                <p class="text-small">Visa:</p>
                                                <b>
                                                    <a target="_blank" class="text-uppercase" href="
                                                        @if(isset($passanger->visa()->file))
                                                            {{ $passanger->visa()->file }}
                                                        @endif
                                                    ">View</a>
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
                </div>
            </div>

            {{-- baggage info --}}
            <div wire:ignore class="mt-3">
                <div class="accordion" id="accordionFlush_2">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-heading_2">
                            <button class="accordion-button show text-uppercase font-weight-bold text-2 text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse_2" aria-expanded="false" aria-controls="flush-collapse_2">
                                Baggage Information
                            </button>
                        </h2>
                        <div id="flush-collapse_2" class="accordion-collapse show" aria-labelledby="flush-heading_2" data-bs-parent="#accordionFlush_2">
                            <div class="accordion-body">
                                <div class="line-height-1">
                                    @foreach($airBooking->responseJsonDecode()['CreatePassengerNameRecordRS']['AirPrice'][0]['PriceQuote']['MiscInformation']['HeaderInformation'][0]['Text'] as $text)
                                        <small>{{ $text }}</small> <br>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            {{-- payments --}}
            <div wire:ignore class="mt-3">
                <div class="accordion" id="accordionFlush_3">
                    <div class="accordion-item ">
                        <h2 class="accordion-header" id="flush-heading_2">
                            <button class="accordion-button show text-uppercase font-weight-bold text-2 text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse_3" aria-expanded="false" aria-controls="flush-collapse_3">
                                Payments
                            </button>
                        </h2>
                        <div id="flush-collapse_3" class="accordion-collapse show" aria-labelledby="flush-heading_2" data-bs-parent="#accordionFlush_3">
                            <div class="accordion-body">
                                <table class="table table-sm table-bordered table-striped">
                                    <thead class="">
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
                                        <tr wire:key="{{ uniqid() }}" class="text-small line-height-1">
                                            <th scope="row" class="pl-1 text-center">{{ $k + 1 }}</th>
                                            <td>
                                                {{ date('Y-m-d', strtotime($payment->created_at)) }}
                                                <span class="badge badge-secondary text-small">
                                                    {{$payment->created_at->diffForHumans()}}
                                                </span>
                                            </td>
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
                </div>
            </div>

        </div>


        <aside class="col-lg-4 mt-4 mt-lg-0">
            <div class="bg-white shadow-md rounded p-3 mb-3">
                <table class="w-100">
                    <tr>
                        <td>Ticket Status</td>
                        <td>
                            <span class="text-1 font-weight-500 text-dark">:</span>
                            @if(isset($airBooking->airTicket->status))
                                @if($airBooking->airTicket->status == App\Enums\FlightEnum::STATUS['CONFIRMED'])
                                    <span class="text-1 font-weight-500 badge badge-success">TICKET ISSUED</span>
                                @else
                                    <span class="text-1 font-weight-500 badge badge-danger">TICKET ISSUE {{ $airBooking->airTicket->status }}</span>
                                @endif
                            @else
                                <span class="text-1 font-weight-500 badge badge-danger">ISSUE REQUEST NOT SENT</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>

            <div class="bg-white shadow-md rounded p-3 mb-3">
                
                <table class="w-100">
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
                            <span class="text-1 font-weight-500 badge badge-secondary">{{ $airBooking->ticketing_last_datetime }}</span>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="
                    bg-white shadow-md rounded p-3 mb-3

                    @if($airBooking->payment_status == \App\Enums\TransactionEnum::STATUS['PAID'])
                        border border-success
                    @endif
                    @if($airBooking->payment_status == \App\Enums\TransactionEnum::STATUS['UNPAID'])
                        border border-danger
                    @endif
                    @if($airBooking->payment_status == \App\Enums\TransactionEnum::STATUS['PARTIAL_PAID'])
                        border border-warning
                    @endif
                    @if($airBooking->payment_status == \App\Enums\TransactionEnum::STATUS['REFUNDED'])
                        border border-danger
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
                    @if($airBooking->payment_status == \App\Enums\TransactionEnum::STATUS['REFUNDED'])
                        <span class="badge badge-danger">REFUNDED</span>
                    @endif
                    @if($airBooking->payment_status == \App\Enums\TransactionEnum::STATUS['PARTIAL_PAID'])
                        <span class="badge badge-warning">PARTIAL PAID</span>
                    @endif
                </h3>

                <hr class="mx-n3">

                
                {{-- fare break down --}}
                @if ($isGetBookingSuccess || !empty($airBooking->getJourneysSabre()))
                    <table class="w-100 mb-2 text-uppercase">
                        <tr>
                            <td>Total Base Fare</td>
                            <td>
                                <span class="text-1 font-weight-500 text-dark">
                                    : {{ $airBooking->currency }}
                                    {{ $airBooking->totalBaseFareSabre() }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>Total Tax</td>
                            <td>
                                <span class="text-1 text-dark">: 
                                    {{ $airBooking->currency }} 
                                    {{ $airBooking->totalTaxSabre() }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="">Total Fare</td>
                            <td>
                                <span class="text-1 text-dark">: 
                                    {{ $airBooking->currency }} 
                                    {{ $airBooking->totalFareSabre() }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-700">Payable Amount</td>
                            <td>
                                <span class="text-1 font-weight-700 text-dark">: 
                                    {{ $airBooking->currency }} 
                                    {{ number_format($airBooking->amount, 2) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <hr>
                            </td>
                        </tr>
                        <tr class="">
                            <td class="">Due Amount</td>
                            <td>
                                <span class="text-1 text-danger">: 
                                    {{ $airBooking->currency }} 
                                    {{ number_format($airBooking->amount - $airBooking->payments->sum('amount'), 2) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-700">Paid Amount</td>
                            <td>
                                <span class="text-1 text-success font-weight-700">: 
                                    {{ $airBooking->currency }} 
                                    {{ number_format($airBooking->payments->sum('amount'), 2) }}
                                </span>
                            </td>
                        </tr>
                    </table>
                @endif

            </div>


            @if($this->airBooking->bookingPartialRequest)
            <div class="bg-white shadow-md rounded p-3">
                <h3 class="text-3 font-weight-700 mb-3 d-flex justify-content-between">
                    Partial Payment

                    @if($this->airBooking->bookingPartialRequest && $this->airBooking->bookingPartialRequest->status == \App\Enums\TransactionEnum::STATUS['PENDING'])
                        <span class="badge badge-warning">PENDING</span>
                    @endif
                    @if($this->airBooking->bookingPartialRequest && $this->airBooking->bookingPartialRequest->status == \App\Enums\TransactionEnum::STATUS['APPROVED'])
                        <span class="badge badge-success">APPROVED</span>
                    @endif
                    @if($this->airBooking->bookingPartialRequest && $this->airBooking->bookingPartialRequest->status == \App\Enums\TransactionEnum::STATUS['REJECTED'])
                        <span class="badge badge-danger">REJECTED</span>
                    @endif
                </h3>
                <hr class="mx-n3">
                <table class="w-100 mb-2">
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
            </div>
            @endif

        </aside>
    </div>


    {{-- payament modal --}}
    <div wire:ignore.self class="modal fade" id="paymentModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">

            <div class="modal-content">
            <div class="modal-header py-2">
                <p class="modal-title text-2 font-weight-bold" id="paymentModalLabel">Make Partial Payment</p>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                @include('includes.errors')
                @include('includes.alert_failed_inner')
                @include('includes.alert_success_inner')

                <form>
                    <div class="form-group mb-2">
                        <label for="operator" class="mb-0 text-small">Amount</label>
                        <div class="input-group">
                            <div class="input-group-prepend"> <span class="input-group-text">BDT</span> </div>
                            <input wire:model="payment_amount" class="form-control" name="amount" id="amount" placeholder="Enter Amount" autocomplete="off" required type="text">
                        </div>
                    </div>


                    <button type="button" wire:click="makePartialPayment" class="btn btn-primary btn-block btn-sm">
                        <div wire:loading.remove wire:target="makePartialPayment">Pay from Balance</div>
                        <div wire:loading wire:target="makePartialPayment" class="loader loader-for-btn">Loading...</div>
                    </button>
                </form>
            </div>
            <div class="modal-footer py-1">
                <button type="button" class="btn btn-secondary btn-sm py-1" data-bs-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>

    {{-- loading indicator --}}
    <div wire:loading wire:target="getBookingByPNR,issueTicket,partialPaymentRequest,makePartialPayment,refundRequest,cancelBooking,togglePrint,printTicket">
        <x-loading />
    </div>
</div>
