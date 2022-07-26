<div>
    {{-- USBangla --}}
    <section class="page-header page-header-text-light bg-secondary mb-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h6 class="color-white">Booking Confirmation</h6>
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

    @include('includes.errors')
    @include('includes.alert_failed_inner')
    @include('includes.alert_success_inner')



    <div class="row mx-0">
        <div class="col-md-8">
            @foreach($airBooking->getSabreFlightSegments() as $k => $segment)
                <div class="row rounded bg-white pb-3 mb-3 mx-1">
                    <div class="row mx-0 bg-light pt-3 pb-3 mb-3">
                        <div class="col d-flex align-items-center">
                            <div class="col-5 col-sm-auto text-center text-sm-left">
                                <h5 class="m-0 trip-place">{{ $segment['OriginLocation']['LocationCode'] }}</h5>
                            </div>
                            <div class="col-2 col-sm-auto text-8 text-black-50 text-center trip-arrow">➝</div>
                            <div class="col-5 col-sm-auto text-center text-sm-left">
                                <h5 class="m-0 trip-place">{{ $segment['DestinationLocation']['LocationCode'] }}</h5>
                            </div>
                            <div class="col-12 mt-1 d-block d-md-none"></div>
                            <div class="col-6 col-sm col-md-auto text-3 date">
                                {{ $segment['DepartureDate'] }},
                                {{ date('d F Y', strtotime($segment['DepartureTime'])) }}
                            </div>
                            <div class="col col-md-auto text-center ml-auto order-sm-0"><span class="badge badge-success py-1 px-2 font-weight-normal text-1"> Confirmed <i class="fas fa-check-circle"></i></span></div>
                        </div>
                    </div>

                    <div class="row px-0 mx-0">
                        <div class="col-md-4 d-flex align-items-center">
                            <img class="mr-2 rounded" src="https://tbbd-flight.s3.ap-southeast-1.amazonaws.com/airlines-logo/BS.png" alt="airline" width="40">
                            <div class="d-flex flex-column">
                                <a href="" class="text-3 text-dark  mr-2">{{ $segment['MarketingAirline']['Code'] }}</a>
                                <small class="text-muted d-block">Flight No: {{ $segment['MarketingAirline']['FlightNumber'] }}</small> </span>
                            </div>
                        </div>
                        <div class="col">
                            <div class="text-center d-inline-block">
                                <p class="text-dark text-4">{{ $segment['DepartureTime'] }}</p>
                                <span class="badge bg-offwhite text-gray px-4 text-2">{{ $segment['OriginLocation']['LocationCode'] }}</span>
                                <div class="text-small bg-light mt-1 text-gray rounded">{{ date('d F Y', strtotime($segment['DepartureDate'])) }}</div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="h-100 pl-0 pl-md-3 d-flex flex-column justify-content-center">
                                <div class="d-inline-block">
                                    <div></div>
                                    <span class="pl-1">Duration</span>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="text-center d-inline-block" >
                                <p class="text-dark text-4">{{ $segment['ArrivalTime'] }}</p>
                                <span class="badge bg-offwhite text-gray px-4 text-2">{{ $segment['DestinationLocation']['LocationCode'] }}</span>
                                <div class="text-small bg-light mt-1 text-gray rounded">{{ date('d F Y', strtotime($segment['ArrivalDate'])) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- Passengers --}}
            <div wire:ignore class="mt-3">
                <div class="accordion" id="accordionFlush_1">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-heading_1">
                            <button class="accordion-button collapsed text-uppercase font-weight-bold text-2 text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse_1" aria-expanded="false" aria-controls="flush-collapse_1">
                                Passeger Informations
                            </button>
                        </h2>
                        <div id="flush-collapse_1" class="accordion-collapse collapsed collapse" aria-labelledby="flush-heading_1" data-bs-parent="#accordionFlush_1">
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

        </div>


        <aside class="col-lg-4 mt-4 mt-lg-0">
            <div class="bg-white shadow-md rounded p-3 mb-3">
                
                <table class="w-100">
                    <tr>
                        <td>Booking date</td>
                        <td><span class="text-1 font-weight-500 text-dark">: {{ date('d-M-Y h:m a', strtotime($airBooking->created_at)) }}</span></td>
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
                        <td>: <span class="text-1 font-weight-500 badge badge-secondary">{{ date('d-M-Y h:m a', strtotime(date('Y') . '-' . $airBooking->responseJsonDecode()['CreatePassengerNameRecordRS']['AirPrice'][0]['PriceQuote']['MiscInformation']['HeaderInformation'][0]['LastTicketingDate'])) }}</span></td>
                    </tr>
                </table>
            </div>

            <div class="bg-white shadow-md rounded p-3">
                <h3 class="text-5 mb-3 d-flex justify-content-between">
                    Fare Details
                    <span class="badge badge-danger">UNPAID</span>
                </h3>
                <hr class="mx-n3">
                <ul class="list-unstyled">
                    <li class="mb-2">Base Fare <span class="float-right text-4 font-weight-500 text-dark">{{ $airBooking->currency }} {{ $airBooking->responseJsonDecode()['CreatePassengerNameRecordRS']['AirPrice'][0]['PriceQuote']['PricedItinerary']['TotalAmount'] }}</span><br>
                        {{-- <small class="text-muted">Adult : 1, Child : 0, Infant : 0</small> --}}
                    </li>
                    <li class="mb-2">Taxes <span class="float-right text-4 font-weight-500 text-dark">{{ $airBooking->responseJsonDecode()['CreatePassengerNameRecordRS']['AirPrice'][0]['PriceQuote']['PricedItinerary']['AirItineraryPricingInfo'][0]['ItinTotalFare']['Taxes']['TotalAmount'] }}</span></li>
                </ul>
                <div class="text-dark bg-light-4 text-4 font-weight-600 p-3 mb-3">Total Amount <span class="float-right text-4">{{ $airBooking->currency }} {{ number_format($airBooking->amount, 2) }}</span> </div>
                <button wire:click="issueTicket" class="btn btn-primary btn-block text-uppercase" type="button">Issue Ticket</button>
                <button wire:click="issueTicket" class="btn btn-primary btn-block text-uppercase" type="button">Partial Payment Request</button>
            </div>
        </aside>
    </div>
</div>
