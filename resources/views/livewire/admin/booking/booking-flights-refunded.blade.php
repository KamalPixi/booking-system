<div>
@include('includes.alert_failed_inner')
@include('includes.alert_success_inner')

<!-- title and menus -->
<div class="row">
    <div class="col-md-6">
        <h1 class="h5 mb-4 text-gray-800">All Refunded Flights</h1>
    </div>
    <div class="col-md-6">
        <div class="d-flex justify-content-center justify-content-md-end mb-2 mb-md-0">
            {{-- <a href="" class="btn btn-primary btn-sm">Add Payment</a> --}}
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
                    <option value="BOOKING_DATE">Issue Request Date</option>
                    <option value="AIR_PNR">Airline PNR</option>
                </select>
            </div>
            <div class="col px-0 text-center">
                @if ($filter_by == 'BOOKING_DATE')
                    <input wire:key="{{ uniqid() }}" wire:model="filter_input" class="form-control form-control-sm" type="date">
                @endif
                @if ($filter_by == 'AIR_PNR')
                    <input wire:key="{{ uniqid() }}" wire:model="filter_input" placeholder="Enter {{ strtolower(str_replace('_', ' ', $filter_by)) }}" class="form-control form-control-sm" type="text">
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
                    <th scope="col" class="text-center">RequestDate</th>
                    <th scope="col" class="text-center">IssuedDate</th>
                    <th scope="col" class="text-center">BookingRef</th>
                    <th scope="col" class="text-center">BookingPNR</th>
                    <th scope="col" class="text-center">AirPNR</th>
                    <th scope="col" class="text-center">Airline</th>
                    <th scope="col" class="text-center">Amount</th>
                    <th scope="col" class="text-center">Due</th>
                    <th scope="col" class="text-center">Paid</th>
                    <th scope="col" class="text-center">Agent</th>
                    <th scope="col" style="width:5%">Action</th>
                </tr>
            </thead>
            <tbody class="small">
                @foreach($tickets as $k => $ticket)
                <tr class="text-small" wire:key="{{ uniqid() }}">
                    <th scope="row" class="pl-1">{{ $k + 1 }}</th>
                    <td class="text-center">
                        {{ date('dMY', strtotime($ticket->created_at)) }}
                        <span class="badge badge-secondary">
                            {{ $ticket->created_at->diffForHumans() }}
                        </span>
                    </td>
                    <td class="text-center">
                        {{ date('dMY', strtotime($ticket->issued_at)) }}
                        <span class="badge badge-secondary">
                            {{ \Carbon\Carbon::create($ticket->issued_at)->diffForHumans() }}
                        </span>
                    </td>
                    <td class="text-center">{{ $ticket->airBooking->reference }}</td>
                    <td class="text-center"><span class="badge badge-secondary">{{ $ticket->airBooking->confirmation_id }}</span></td>
                    <td class="text-center"><span class="badge badge-secondary">{{ $ticket->confirmation_id }}</span></td>
                    <td class="text-center">{{ $ticket->airBooking->airline() }}</td>
                    <td class="text-center font-weight-bold text-right">{{$ticket->currency}} {{ number_format($ticket->amount, 2) }}</td>
                    <td class="text-center font-weight-bold text-danger text-right">{{$ticket->currency}} {{ number_format($ticket->amount - $ticket->airBooking->payments->sum('amount'), 2) }}</td>
                    <td class="text-center font-weight-bold text-success text-right">{{$ticket->currency}} {{ number_format($ticket->airBooking->payments->sum('amount'), 2) }}</td>
                    <td class="text-center">{{ mb_strimwidth($ticket->createdBy->agent->company, 0, 18, "..."); }}</td>
                    <td>
                        <div class="btn-group">
                            <button class="btn btn-info btn-sm dropdown-toggle btn-action" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-cog"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a target="_blank" href="{{ route('admin.booking.flights.view',  $ticket->airBooking->reference) }}" class="dropdown-item">View</a>
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
    {{ $tickets->links() }}
</div>

</div>
