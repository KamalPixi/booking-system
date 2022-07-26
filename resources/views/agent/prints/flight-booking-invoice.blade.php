<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Invoice</title>

    <style>
        * {
            margin:0;
            padding:0;
            font-size: 13px;
            color:#222;
        }
        body {
            font-family: 'Arial', sans-serif;
            margin-left: 40px;
            margin-right: 40px;
            padding-top: 50px;
            color: #000;
        }
        .footer-address {
            position: absolute;
            bottom:0;
            left:0;
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
            font-size: 1.8rem;
        }
        .display-2 {
            font-size: 1rem;
        }
        .line-height-1 {
            line-height:1;
        }
        .mb-2 {
            margin-bottom: 0.2rem;
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
        .footer-line {
            position: absolute !important;
            left: 0;
            bottom: 0;
        }
        .border {
            border: 1px solid #f7f7f7;
        }
        .d-flex {
            display: flex;
        } 
        .justify-content-end {
            justify-content:end;
        }
        .invoice-serials tr td {
            padding-left: 0.3rem;
            padding-top: 0.3rem;
            padding-right: 0;
            padding-bottom: 0.3rem;
        }
        .dark-tr td {
            background-color: #e6e6e6;
        }
        .dark-tr th {
            background-color: #e6e6e6;
        }
        .text-small {
            font-size: .88rem;
        }
        .text-extra-small {
            font-size: .8rem;
        }
        .text-dark  {
            color: #353535;
        }
        .w-100 {
            width:100%;
        }
        .text-white {
            color:white;
        }
        .text-uppercase {
         text-transform:uppercase;
        }
        .table-border tr td {
            border: 1px solid #f7f7f7;
        }
        .dark-border {
            border: 1px solid #cacaca;
        }
        .p {
            padding: 0.5rem;
        }
        .text-start {
            text-align: left;
        }
        .mb-1-5 {
            margin-bottom:1.5rem;
        }
        .mb-1 {
            margin-bottom:1rem;
        }
    </style>
</head>

<body>
    <!-- company info -->
    <table style="width:100%; margin-bottom:25px;">
        <tr>
            <td style="width:62%">
                <img class="border" src="assets/images/logo/dreamtripbd-logo.png" alt="logo" width="150px">
                <p class="font-weight-bold" style="color:#090958;padding-top:0.5rem;">{{ \App\Enums\CompanyEnum::company() }}</p>
                <p class="text-extra-small text-dark">{{ \App\Enums\CompanyEnum::address() }}</p>
                <p class="text-extra-small text-dark">{{ \App\Enums\CompanyEnum::country() }}</p>
                <p class="text-extra-small text-dark">
                <span class="font-weight-bold text-extra-small text-dark">Phone</span>
                    {{ \App\Enums\CompanyEnum::phone() }}
                </p>
                <p class="text-extra-small text-dark">
                    <span class="font-weight-bold text-extra-small text-dark">Email</span>
                    {{ \App\Enums\CompanyEnum::email() }}
                </p>
            </td>
            <td>
                <div class="d-flex justify-content-end">
                    <table class="invoice-serials" style="width:100%;">
                        <tr class="dark-tr">
                            <td colspan="3" class="text-center font-weight-bold" style="font-size:1.1rem;">INVOICE</td>
                        </tr>
                        <tr>
                            <td class="text-small" style="width:36%;">Invoice No</td>
                            <td style="width:5%;">:</td>
                            <td class="text-small">{{ $invoice_no }}</td>
                        </tr>
                        <tr  class="dark-tr">
                            <td class="text-small" style="width:36%;">Date:</td>
                            <td style="width:5%;">:</td>
                            <td class="text-small">{{ date('d-m-Y') }}</td>
                        </tr>
                        <tr>
                            <td class="text-small" style="width:36%;">Customer Name:</td>
                            <td style="width:5%;">:</td>
                            <td class="text-small">{{ $booking->agent()->company }}</td>
                        </tr>
                        <tr  class="dark-tr">
                            <td class="text-small" style="width:36%;">Reference:</td>
                            <td style="width:5%;">:</td>
                            <td class="text-small">{{ $booking->reference }}</td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>
    <div style="margin-bottom:10px; border-bottom:1px solid #c6c6c6;"></div>

    <table class="w-100 mb-1-5">
        <tr>
            <td style="width:50%;vertical-align:top;">
                <table class="w-100 invoice-serials">
                    <tr class="dark-tr">
                        <td>
                            <h6>To</h6>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                    </tr>
                    <tr>
                        <td>
                            <h6>{{ $booking->agent()->company }}</h6>
                            <p>
                                <span class="font-weight-bold text-extra-small">Contact:</span> 
                                <span class="text-uppercase" style="padding:0.1rem 0;">{{ $booking->agent()->full_name }}</span>
                            </p>
                            <p class="text-extra-small">
                                {{ $booking->agent()->address }},
                                {{ $booking->agent()->city }},
                                {{ $booking->agent()->state }},
                                {{ $booking->agent()->postcode }}
                            </p>
                            <div class="text-extra-small">
                                Email: <span class="font-weight-bold text-extra-small">{{ $booking->agent()->email }}</span> <br>
                                Phone:<span class="font-weight-bold text-extra-small">{{ $booking->agent()->phone }}</span>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>

            <td></td>

            <td style="width:40%;vertical-align:top;">
                <table class="w-100 invoice-serials">
                    <tr class="dark-tr p">
                        <td>
                            <h6>Invoice for</h6>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="p">
                            <h6 class="text-uppercase">{{ $booking->airBookingPassengers->first()->fullNameWithTitle() }}</h6>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table class="w-100 dark-border mb-1-5">
        <tr class="dark-tr">
            <th class="p text-start">Service</th>
            <th class="p text-start">Description</th>
            <th class="p text-center">Base Fare</th>
            <th class="p text-center">Tax</th>
            <th class="p text-center">Qty</th>
            <th class="p text-center">Total</th>
        </tr>

        <tr class="text-center">
            <td class="p dark-border text-start">Air</td>
            <td class="p dark-border text-start">
                @foreach($booking->getJourneysSabre() as $k => $journey)
                    <span class="font-weight-bold text-small">{{ $journey['firstAirportCode'] }}</span>  -> <span class="font-weight-bold text-small">{{ $journey['lastAirportCode'] }}</span> <br>
                @endforeach
            </td>
            <td class="p dark-border">{{ $flight['pricingInfo']['totalBaseFareAmount'] ?? 0 }}</td>
            <td class="p dark-border">{{ $flight['pricingInfo']['totalTaxAmount'] ?? 0 }}</td>
            <td class="p dark-border">{{ count($booking->getJourneysSabre()) }}</td>
            <td class="p dark-border">{{ $booking->amount }}</td>
        </tr>
    </table>


    <table class="w-100">
        <tr>
            <td style="width:70%">
                <div>
                    <p class="mb-1 text-small">In words: <span class="font-weight-bold">{{ $flight['pricingInfo']['totalPriceInWord'] ?? '' }}</span></p>
                    <p>Note:</p>
                    <p class="text-extra-small">All payment should be made in favor of "{{\App\Enums\CompanyEnum::company()}}"</p> 
                    <p class="text-extra-small">3% Bank Charge will be add on total bill amount, if the bill Paid by Debit/Credit Card</p> 
                </div>
            </td>
            <td>
                <table class="w-100 dark-border">
                    <tr class="">
                        <td class="p" style="width:40%;">Total Fare</td>
                        <td class="p">:</td>
                        <td class="p text-right">{{ $flight['pricingInfo']['totalPrice'] ?? 0 }}</td>
                    </tr>
                    <tr class="">
                        <td class="p" style="width:40%;">Total Tax</td>
                        <td class="p">:</td>
                        <td class="p text-right">{{ $flight['pricingInfo']['totalTaxAmount'] ?? 0 }}</td>
                    </tr>
                    <tr class="dark-tr">
                        <td class="p" style="width:40%;"></td>
                        <td class="p">:</td>
                        <td class="p text-right">{{ $flight['pricingInfo']['totalPrice'] ?? 0 }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <p style="margin-bottom:5rem;"></p>
</body>
</html>
