<div>
@include('includes.alert_failed_inner')
@include('includes.alert_success_inner')

<!-- title and menus -->
<div class="row">
    <div class="col-md-6">
        <h1 class="h5 mb-4 text-gray-800">Flight Bookings</h1>
    </div>
    <div class="col-md-6">
        <div class="d-flex justify-content-center justify-content-md-end mb-2 mb-md-0">
            <!-- <a href="" class="btn btn-primary btn-sm">Add Payment</a> -->
        </div>
    </div>
</div>

<!-- search -->
<div class="card">
    <div class="card-body">
        <div class="row justify-content-center">
            <div class="col"></div>
            <div class="col text-center">
                <select wire:model="filter_by" class="form-control form-control-sm">
                    <option value="BOOKING_DATE">Booking Date</option>
                    <option value="REF">Booking Refefrence</option>
                    <option value="PNR">Booking PNR</option>
                    <option value="AIR_PNR">Airline PNR</option>
                    <option value="STATUS">Booking Status</option>
                    <option value="PAYMENT_STATUS">Payment Status</option>
                </select>
            </div>
            <div class="col px-0 text-center">
                @if (in_array($filter_by, ['REF','PNR', 'AIR_PNR']))
                    <input wire:key="{{ uniqid() }}" wire:model="filter_input" placeholder="Enter {{ strtolower(str_replace('_', ' ', $filter_by)) }}" class="form-control form-control-sm" type="text">
                @endif
                @if ($filter_by == 'BOOKING_DATE')
                    <input wire:key="{{ uniqid() }}" wire:model="filter_input" class="form-control form-control-sm" type="date">
                @endif
                @if ($filter_by == 'STATUS')
                <select wire:key="{{ uniqid() }}" wire:model="filter_input" class="form-control form-control-sm">
                    <option value="">Choose</option>
                    <option value="CONFIRMED">CONFIRMED</option>
                    <option value="CANCELED">CANCELED</option>
                    <option value="PENDING">PENDING</option>
                </select>
                @endif
                @if ($filter_by == 'PAYMENT_STATUS')
                <select wire:key="{{ uniqid() }}" wire:model="filter_input" class="form-control form-control-sm">
                    <option value="">Choose</option>
                    <option value="PAID">PAID</option>
                    <option value="PARTIAL_PAID">PARTIAL_PAID</option>
                    <option value="UNPAID">UNPAID</option>
                </select>
                @endif
            </div>
            <div class="col">
                <button wire:click="resetFilter" class="btn btn-secondary btn-sm d-block p-1 px-2">Reset</button>
            </div>
            <div class="col"></div>
        </div>
    </div>
</div>


<div class="card">
    <div class="card-body">
        <table id="products" class="table table-sm table-bordered table-striped">
            <thead class="bg-primary text-white">
                <tr>
                    <th scope="col">#</th>
                   <th scope="col" class="text-center">Agent</th>
                    <th scope="col" class="text-center">BookingDate</th>
                    <th scope="col" class="text-center">BookingRef</th>
                    <th scope="col" class="text-center">BookingPNR</th>
                    <th scope="col" class="text-center">Airline</th>
                    <th scope="col" class="text-center">AirPNR</th>
                    <th scope="col" class="text-center">BookingStatus</th>
                    <th scope="col" class="text-center">Amount</th>
                    <th scope="col" class="text-center">PaymentStatus</th>
                    <th scope="col" style="width:5%">Action</th>
                </tr>
            </thead>
            <tbody class="small">
                @foreach($bookings as $k => $booking)

                    <tr wire:key="{{ uniqid() }}">
                    <th scope="row">{{ $bookings->firstItem() + $k }}</th>
                    <td class="text-center" title="{{ $booking->createdBy->agent->company ?? '' }}">{{ mb_strimwidth($booking->createdBy->agent->company ?? '', 0, 15, "..."); }}</td>
                    <td class="text-center">
                        {{ date('dMY', strtotime($booking->created_at)) }} 
                        <span class="badge badge-secondary">{{ $booking->created_at->diffForHumans() }}</span>
                    </td>
                    <td class="text-center">{{ $booking->reference }}</td>
                    <td class="text-center"><span class="badge badge-info">{{ $booking->confirmation_id }}</span></td>
                    <td class="text-center">{{ $booking->airline() }}</td>
                    <td class="text-center"><span class="badge badge-secondary">{{ $booking->airline_confirmation_id }}</span></td>
                    <td class="text-center">{{ $booking->status }}</td>
                    <td class="text-center font-weight-bold">{{ number_format($booking->amount) }}</td>
                    <td class="text-center">
                        <span class="badge 
                            @if($booking->payment_status == \App\Enums\TransactionEnum::STATUS['PAID'])
                                badge-success
                            @endif
                            @if($booking->payment_status == \App\Enums\TransactionEnum::STATUS['PARTIAL_PAID'])
                                badge-warning
                            @endif
                            @if($booking->payment_status == \App\Enums\TransactionEnum::STATUS['UNPAID'])
                                badge-danger
                            @endif
                            ">
                            {{ $booking->payment_status }}
                        </span>
                    </td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-info btn-sm dropdown-toggle btn-action" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-cog"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a target="_blank" href="{{ route('admin.booking.flights.view', $booking->reference) }}" class="dropdown-item">View</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="pagination-container mt-3">
    {{ $bookings->links() }}
</div>

</div>
