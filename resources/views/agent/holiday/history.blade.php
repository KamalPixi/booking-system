@extends('agent.master')
@section('content')
<div class="row m-2">
    <div class="card px-0">
        <div class="card-header text-bold">
            Package History
        </div>

        <div class="row py-3 text-right">
            <div>
                <button class="btn btn-sm btn-light border shadow-none">All Bookings</button>
                <button class="btn btn-sm btn-light border shadow-none">Issued</button>
                <button class="btn btn-sm btn-light border shadow-none">Booked</button>
                <button class="btn btn-sm btn-light border shadow-none">Reset</button>
            </div>
        </div>

        <div>
            <table class="table table-sm">
            <thead>
                <tr class="text-small">
                    <th scope="col">#</th>
                    <th scope="col">Booked By</th>
                    <th scope="col">Booking Date</th>
                    <th scope="col">Issue Date</th>
                    <th scope="col">Fly Date</th>
                    <th scope="col">Time Limit</th>
                    <th scope="col">Airlines</th>
                    <th scope="col">Booking Code</th>
                    <th scope="col">PNR</th>
                    <th scope="col" class="text-center">Payment Status</th>
                    <th scope="col">Booking Status</th>
                    <th scope="col">CardsPay</th>
                    <th scope="col">Client Pay</th>
                    <th scope="col">Agent Pay</th>
                    <th scope="col">AIT</th>
                    <th scope="col">Details</th>
                </tr>
            </thead>
            <tbody>
                <tr class="text-small">
                    <th scope="row" class="pl-1"></th>
                    <td>
                        <input type="text" class="form-control form-control-sm w-75">
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                        <input type="text" class="form-control form-control-sm w-75">
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm w-75">
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm w-75">
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm w-75">
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm w-75">
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm w-75">
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm w-75">
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm w-75">
                    </td>
                    <td></td>
                    <td></td>
                </tr>
                <tr class="text-small">
                    <th scope="row" class="pl-1">1</th>
                    <td>ALASWAD02</td>
                    <td>19 Apr, 2022 11:55</td>
                    <td>21 Apr, 2022 10:15</td>
                    <td>21 Apr, 2022 10:15</td>
                    <td>19 Apr, 2022 12:41</td>
                    <td>WY</td>
                    <td> <b>STFLB1650347747125</b> </td>
                    <td>GIGNPW</td>
                    <td class="text-center">Unpaid</td>
                    <td>Cancelled</td>
                    <td>No</td>
                    <td>-44381</td>
                    <td>-41838</td>
                    <td>126</td>
                    <td>
                        <a href="#" class="btn btn-primary btn-sm px-1 py-0">
                            <small>
                                VIEW
                            </small>
                        </a>
                    </td>
                </tr>
                <tr class="text-small">
                    <th scope="row" class="pl-1">1</th>
                    <td>ALASWAD02</td>
                    <td>19 Apr, 2022 11:55</td>
                    <td>21 Apr, 2022 10:15</td>
                    <td>21 Apr, 2022 10:15</td>
                    <td>19 Apr, 2022 12:41</td>
                    <td>WY</td>
                    <td> <b>STFLB1650347747125</b> </td>
                    <td>GIGNPW</td>
                    <td class="text-center">Unpaid</td>
                    <td>Cancelled</td>
                    <td>No</td>
                    <td>-44381</td>
                    <td>-41838</td>
                    <td>126</td>
                    <td>
                        <a href="#" class="btn btn-primary btn-sm px-1 py-0">
                            <small>
                                VIEW
                            </small>
                        </a>
                    </td>
                </tr>

            </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
