<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>E-ticket</title>
    <script src="assets/js/kit.fontawesome.js"></script>

    <style>
        * {
            margin:0;
            padding:0;
            font-size: 13px;
        }
        body {
            margin-left: 30px;
            margin-right: 30px;
            padding-top:50px;
        }
        table {
            border-collapse: collapse;
            font-size: 12px;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .text-left {
            text-align: left;
        }
        .font-weight-bold {
            font-weight:bold;
        }
        .display-1 {
            font-size: 1.5rem;
        }
        .display-2 {
            font-size: 1rem;
        }
        .line-height-1 {
            line-height:1;
        }
        .line-height-08 {
            line-height:0.8rem;
        }
        .mb-2 {
            margin-bottom: 0.9rem;
        }
        .table-light-td td {
            border: 1px solid #dadada;
            padding:2px;
        }
        .table-light {
            border: 1px solid #dadada;
        }
        .title-bg {
            background-color:#dadada;
        }
        .text-offwhite {
            color: #737373;
        }
        .border {
            border: 1px solid #f4f4f4 !important
        }
        .border-end {
            border-right: 1px solid #f4f4f4 !important;
        }
        .bg-grey {
            background-color: #f1f1f1;
        }
        .title {
            font-weight: 400;
            text-transform:uppercase;
        }
        .font-weight-medium {
            font-weight: 600;
        }
        .border {
            border: 1px solid #f7f7f7;
        }
        .w-100 {
            width:100%;
        }
        .p-2 {
        }
        .w-25 {
            width: 25%;
        }
        .w-35 {
            width: 35%;
        }
        .w-40 {
            width: 40%;
        }
        .w-60 {
            width: 60%;
        }
        .d-flex {
            display:flex;
        }
        .flex-column{
            flex-direction:column;
        } 
        .justify-content-center {
            justify-content:center;
        }
        .m-0 {
            margin:0;
        }
        .p-0 {
            padding: 0;
        }
        .text-uppercase {
            text-transform:uppercase;
        }
        .text-dark {
            color:#353535;
        }
        .text-small {
            font-size: 0.8rem;
        }
        .p-1 {
            padding:1rem;
        }
        .py-1 {
            padding:1rem 0rem;
        }
        .header {
            padding: 1rem;
        }
        .mb-4 {
            margin-bottom: 2rem;
        }
        .padding-td td {
            padding: .1rem 1rem;
            padding-right: 0;
        }
        .table-details td {
            padding: .1rem 1rem;
            padding-right: 0;
            padding-left: 0;
        }
        .d-flex{
            display:flex;
        } 
        .flex-column {
            flex-direction:column;
        }
        .location {
            font-size:1.5rem;
        }
        .conditions-table  tr td {
            padding: 0.3rem 0.3rem;
            padding-right:0;
        }
        .mb-1 {
            margin-bottom:.5rem;
        }
        .rounded {
            border-radius:5%;
        }
        .mx-1 {
            margin-left:0.5rem;
            margin-right:0.5rem;
        }
    </style>
</head>

<body>
    <!-- company info -->
    <table class="border mb-4 w-100">
        <tr>
            <td class="py-1" style="width:5%;">
                @if(isset($agent->logo()->file) && !empty($agent->logo()->file))
                    <img class="rounded mx-1" src="{{ $agent->logo()->file }}" alt="logo" width="50">
                @else
                    <img class="rounded mx-1" src="https://via.placeholder.com/50" alt="logo" width="50">
                @endif
            </td>
            <td class="py-1">
                <div class="line-height-08 d-flex flex-column justify-content-center">
                    <h6 class="font-weight-bold text-uppercase display-2" style="margin-bottom:0.3rem;">{{ $agent->company }}</h6>
                    <small class="text-offwhite text-small line-height-08">{{ $agent->address }}, {{ $agent->city }} <br> {{ $agent->state }}-{{ $agent->postcode }}</small><br>
                    <small class="text-offwhite text-small line-height-08">Bangladesh</small><br>
                </div>
            </td>
            <td style="width:25%;">
                <div class="py-1 text-center line-height-1">
                    <div>
                        <h2 class="font-weight-bold m-0 display-1">e-Ticket</h2>
                    </div>
                    <div>
                        <small>Reference ID: <span style="font-weight:600;">{{ $booking->reference }}</span></small>
                    </div>
                </div>
            </td>
            <td class="py-1 line-height-08 text-right" style="padding-right:0.5rem;">
                <h6 class="font-weight-bold text-uppercase text-dark">Contact Us</h6>
                <small class="text-offwhite text-small">
                    <i class="fa-solid fa-square-phone text-dark"></i>
                    {{ $agent->phone }}
                </small><br>
                <small class="text-offwhite text-small">
                    <i class="fa-solid fa-envelope text-dark"></i>
                    {{ $agent->email }}
                </small>
            </td>
        </tr>
    </table>


    <!-- passenger info -->
    <table class="w-100 mb-2">
        <tr>
            <td class="header bg-grey text-uppercase">
                <i class="fa-solid fa-person-walking-luggage"></i> Passenger name:
                @foreach ($booking->airBookingPassengers as $p)
                    <span class="font-weight-bold">{{ $p->title }} {{ $p->first_name }} {{ $p->surname }}</span>
                    @if (!$loop->last)
                        |
                    @endif
                @endforeach
            </td>
        </tr>
    </table>
    <table class="padding-td ms-3 mb-4" style="width:28rem">
        <tr>
            <td class="title">A-PNR </td>
            <td class="font-weight-bold">{{ $booking->confirmation_id }}</td>
        </tr>
        <tr>
            <td class="title"> Issue Date </td>
            <td class="font-weight-bold">{{ date('d-m-Y', strtotime($booking->created_at)) }}</td>
        </tr>
        <tr>
            <td class="title">STATUS</td>
            <td class="font-weight-bold">{{ $booking->status }}</td>
        </tr>

        <tr>
            <td class="title"> Flight Type </td>
            <td class="font-weight-bold">International | <span class="text-small">{{ $booking->trip_type ?? '' }}</span></td>
        </tr>
    </table>

    <!-- ITINERARIES info -->
    <table class="w-100 mb-2">
        <tr>
            <td class="header bg-grey text-uppercase font-weight-bold"><i class="fa-solid fa-plane"></i> ITINERARIES:</td>
        </tr>
    </table>
    <table class="w-100 mb-4">

    @foreach($booking->getJourneysSabre() as $k => $journey)
        @foreach ($booking->getJourneyFlightsSabre($k) as $sKey => $flight)
            <tr>
                <td style="width:13%;">
                    <div class="line-height-1 d-flex flex-column">
                        <img class="rounded" src="https://tbbd-flight.s3.ap-southeast-1.amazonaws.com/airlines-logo/{{$flight['airlineCode']}}.png" alt="logo" width="40"> <br>
                        <small class="">{{ $flight['airlineCode'] }}</small> 
                    </div>
                </td>
                <td class="" style="width:15%;">
                    <h4 class="m-0 font-weight-bold text-offwhite location">{{ $flight['fromAirportCode'] }}</h4>
                    <div class="line-height-1">
                        <p class="m-0 font-weight-bold" style="font-size:1.45rem;">{{ \Carbon\Carbon::create($flight['departureTime'])->format('H:i') }}</p>
                        <span>{{ date('d F Y', strtotime($flight['departureDate'])) }}</span>
                    </div>
                </td>
                <td class="" style="width:8%;">
                    <div style="display:flex;flex-direction:column;">
                        <img src="{{ asset('assets/images/point-a-to-b.png') }}" alt="" style="width:80px;position:relative;right:20px;">
                        <span class="font-weight-bold text-offwhite" style="display:inline-block;position:relative;bottom:6px;">
                            {{ \App\Helpers\UtilityHelper::minuteToHourMinute($flight['durationInMinutes'], '%02dh %02dm') }}
                        </span>
                    </div>
                </td>
                <td class="" style="width:15%;">
                    <h4 class="m-0 font-weight-bold text-offwhite location">{{ $flight['toAirportCode'] }}</h4>
                    <div class="line-height-1">
                        <p class="m-0 font-weight-bold" style="font-size:1.45rem;">
                            {{ \Carbon\Carbon::create($flight['arrivalTime'])->format('H:i') }}
                        </p>
                        <span>{{ date('d F Y', strtotime($flight['arrivalDate'])) }}</span>
                    </div>
                </td>
                <td class="" style="line-height:1.2rem;">
                    <h1 class="font-weight-bold" style="font-size:1.1rem;margin-bottom:0.4rem;">{{ $flight['airlineCode'] }} {{ $flight['aircraftTypeName'] }}</h1>
                    <table class="w-100 table-details">
                        <tr>
                            <td class="text-uppercase">Depart</td>
                            <td class="font-weight-medium">{{ $flight['fromAirportCode'] }}</td>
                        </tr>
                        <tr>
                            <td class="text-uppercase">Lands In</td>
                            <td class="font-weight-medium">{{ $flight['toAirportCode'] }}</td>
                        </tr>
                        <tr>
                            <td class="text-uppercase">Baggage</td>
                            <td class="font-weight-medium">
                                @if($sKey > 0)
                                    N/A
                                @else
                                    @if (isset( $booking->responseJsonDecode()['CreatePassengerNameRecordRS']['AirPrice'][0]['PriceQuote']['PricedItinerary']['AirItineraryPricingInfo'][0]['BaggageProvisions'][0]['WeightLimit']['Units']))
                                        {{ $booking->responseJsonDecode()['CreatePassengerNameRecordRS']['AirPrice'][0]['PriceQuote']['PricedItinerary']['AirItineraryPricingInfo'][0]['BaggageProvisions'][0]['WeightLimit']['content'] }}
                                        {{  $booking->responseJsonDecode()['CreatePassengerNameRecordRS']['AirPrice'][0]['PriceQuote']['PricedItinerary']['AirItineraryPricingInfo'][0]['BaggageProvisions'][0]['WeightLimit']['Units'] }}G
                                    @endif
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-uppercase">A-PNR</td>
                            <td class="font-weight-medium">{{ $booking->confirmation_id }}</td>
                        </tr>
                    </table>
                </td>
            </tr>

            @if (!$loop->last)
                <tr>
                    <td colspan="5" style="padding-top:1rem;"></td>
                </tr>
            @endif
        @endforeach

        @if(!$loop->last)
            <tr>
                <td colspan="5" style="padding-top:1rem;"><hr></td>
            </tr>
        @endif
    @endforeach
    
    </table>



    <!-- fare info -->
    @if($include_price)
        <table class="w-100 mb-2">
            <tr>
                <td class="header bg-grey text-uppercase font-weight-bold">Fare details:</td>
            </tr>
        </table>
        <table class="padding-td ms-3 mb-4" style="width:28rem">
            <tr>
                <td class="title">Amount</td>
                <td class="font-weight-bold">{{ number_format($booking->amount, 2) }}</td>
            </tr>
        </table>
    @endif

    <!-- notice info -->
    <table class="w-100 mb-1">
        <tr>
            <td class="header bg-grey text-uppercase font-weight-bold"><i class="fa-solid fa-circle-exclamation"></i> CONDITIONS AND IMPORTANT NOTICE:</td>
        </tr>
    </table>
    <table class="conditions-table">
        <tr>
            <td class="">
                <h6 class="font-weight-bold">E-Ticket Notice</h6>
                <p class="text-offwhite">
                    Carriage and other services provided by the carrier are subject to conditions of carriage which are hereby incorporated by reference. These
                    conditions may be obtained from the issuing carrier.
                </p>
            </td>
        </tr>
        <tr>
            <td class="">
                <h6 class="font-weight-bold">Passport/Visa/Health</h6>
                <p class="text-offwhite">
                    Please ensure that you have all the required travel documents for your entire journey - i.e. valid passport &necessary visas - and that you have
                    had the recommended inoculations for your destination(s).
                </p>
            </td>
        </tr>
        <tr>
            <td class="">
                <h6 class="font-weight-bold">Reconfirmation of flights</h6>
                <p class="text-offwhite">
                    Please reconfirm all flights at least 72 hours in advance direct with the airline concerned. Failure to do so could result in the cancellation of your
                    reservation and possible `no-show` charges.
                </p>
            </td>
        </tr>
        <tr>
            <td class="">
                <h6 class="font-weight-bold">Insurance</h6>
                <p class="text-offwhite">
                    We strongly recommend that you take out travel insurance for the whole of your journey.
                </p>
            </td>
        </tr>
    </table>

</body>
</html>
