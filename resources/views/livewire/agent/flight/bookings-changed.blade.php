<div class="row m-2">
    <div wire:loading>
        <x-loading />
    </div>

    <div class="card px-0">

        <div class="card-header row align-items-center">
            <div class="col-7">
                <div class="text-bold">
                    Refunded Bookings
                </div>
            </div>

            <div class="col">
                <div class="row">
                    <div class="col px-0">
                        <label for="" class="mb-0 text-small">Filter By</label>
                        <select wire:model="filter_by" class="form-control form-control-sm">
                            <option value="BOOKING_DATE">IssueDate</option>
                            <option value="AIR_PNR">AirPNR</option>
                        </select>
                    </div>


                    <div class="col">
                        <label for="" class="mb-0 text-small">Filter Value</label>
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
                    <div class="col-2 px-0">
                        <label for="" class="mb-0"></label>
                       <button wire:click="resetFilter" class="btn btn-secondary btn-sm d-block p-1 px-2">Reset</button>
                    </div>
                </div>
            </div>
        </div>


        <div>
            <table class="theme-table table table-sm">
            <thead>
                <tr class="text-small">
                    {{-- <th scope="col">#</th> --}}
                    <th scope="col" class="text-center">IssueDate</th>
                    <th scope="col" class="text-center">BookingRef</th>
                    <th scope="col" class="text-center">BookingPNR</th>
                    <th scope="col" class="text-center">AirPNR</th>
                    <th scope="col" class="text-center">Airline</th>
                    <th scope="col" class="text-center">Amount</th>
                    <th scope="col" class="text-center">Due</th>
                    <th scope="col" class="text-center">Paid</th>
                    <th scope="col" class="text-center">BookedBy</th>
                    <th scope="col">Booking</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tickets as $k => $ticket)

                <tr class="text-small" wire:key="{{ uniqid() }}">
                    {{-- <th scope="row" class="pl-1">{{ $bookings->firstItem() + $k }}</th> --}}
                    <td class="text-center">{{ date('dMY', strtotime($ticket->created_at)) }}</td>
                    <td class="text-center">{{ $ticket->airBooking->reference }}</td>
                    <td class="text-center"><span class="badge badge-secondary">{{ $ticket->airBooking->confirmation_id }}</span></td>
                    <td class="text-center"><span class="badge badge-secondary">{{ $ticket->confirmation_id }}</span></td>
                    <td class="text-center">{{ $ticket->airBooking->airline() }}</td>
                    <td class="text-center font-weight-bold text-right">{{$ticket->currency}} {{ number_format($ticket->amount, 2) }}</td>
                    <td class="text-center font-weight-bold text-danger text-right">{{$ticket->currency}} {{ number_format($ticket->amount - $ticket->airBooking->payments->sum('amount'), 2) }}</td>
                    <td class="text-center font-weight-bold text-success text-right">{{$ticket->currency}} {{ number_format($ticket->airBooking->payments->sum('amount'), 2) }}</td>
                    <td class="text-center">{{ $ticket->createdBy->name }}</td>
                    <td>
                        <a href="{{ route('b2b.flight.show', ['id' => $ticket->airBooking->reference]) }}" class="btn btn-primary btn-sm px-1 py-0">
                            <small>
                                VIEW
                            </small>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
            </table>
        </div>
    </div>
    <div class="text-center w-100 mt-3">
        {{ $tickets->links() }}
    </div>
</div>
