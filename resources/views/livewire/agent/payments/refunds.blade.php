<div class="row m-2">
    <div class="card px-0 overflow-hidden">
        <div class="card-header row align-items-center">
            <div class="col-7">
                <div class="text-bold">
                    Refunds
                </div>
            </div>

            <div class="col">
                <div class="row">
                    <div class="col px-0">
                        <label for="" class="mb-0 text-small">Filter By</label>
                        <select wire:model="filter_by" class="form-control form-control-sm">
                            <option value="DATE">RequestDate</option>
                            <option value="FOR">RequestFor</option>
                        </select>
                    </div>
                    <div class="col">
                        <label for="" class="mb-0 text-small">Filter Value</label>
                        @if ($filter_by == 'DATE')
                            <input wire:key="date" wire:model="filter_input" class="form-control form-control-sm" type="date">
                        @endif

                        @if ($filter_by == 'FOR')
                        <select wire:key="for-key" wire:model="filter_input" class="form-control form-control-sm">
                            <option value="">Choose</option>
                            <option value="AirBooking">AirBooking</option>
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
                    <th scope="col">#</th>
                    <th scope="col">RequestDate</th>
                    <th scope="col">RequestBy</th>
                    <th scope="col">RequestFor</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Fee</th>
                    <th scope="col">RefundMethod</th>
                    <th scope="col" style="width:7%">AgentRemark</th>
                    <th scope="col" style="width:7%">AdminRemark</th>
                    <th scope="col">Status</th>
                    <th scope="col">RefundedOn</th>
                    <th scope="col" style="width:5%">Action</th>
                </tr>
            </thead>
            <tbody>
                
                @foreach($refunds as $k => $refund)
                    <tr wire:key="transactions-{{ $refunds->firstItem() + $k }}" class="text-small line-height-1">
                        <td class="px-1">{{ $refunds->firstItem() + $k }}</td>
                        <td class="text-small">
                            {{ date('Y-m-d', strtotime($refund->created_at)) }} <br>
                            <span class="badge badge-secondary text-extra-small">{{ $refund->created_at->diffForHumans() }}</span>
                        </td>
                        <td>
                            {{ $refund->requestedBy->agent->company ??  $refund->requestedBy->name }}
                        </td>
                        <td>
                            {{ $refund->refundableTypeModelName() }}
                        </td>
                        <td class="font-weight-bold">
                            {{ number_format($refund->amount, 2) }}
                        </td>
                        <td class="font-weight-bold">
                            {{ number_format($refund->fee, 2) }}
                        </td>
                        <td>
                            {{ $refund->refund_method }}
                        </td>
                        <td>
                            <textarea readonly class="form-control form-control-sm text-small">{{ $refund->remark }}</textarea>
                        </td>
                        <td>
                            <textarea readonly class="form-control form-control-sm text-small">{{ $refund->admin_remark }}</textarea>
                        </td>
                        <td>
                            {{ $refund->status }}
                        </td>
                        <td>
                            {{ date('Y-m-d', strtotime($refund->refunded_at)) }} <br>
                            <span class="badge badge-secondary text-extra-small">{{ \Carbon\Carbon::create($refund->refunded_at)->diffForHumans() }}</span>
                        </td>
                        <td>
                            <ul class="breadcrumb justify-content-start justify-content-md-end mb-0">
                                <li>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle py-1" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-cog text-white"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                @if ($refund->refundableTypeModelName() == 'AirBooking')
                                                    <a href="{{ route('b2b.flight.show', $refund->refundable->reference) }}" class="dropdown-item">View Booking</a>
                                                @endif
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </td>
                    </tr>
                @endforeach

            </tbody>
            </table>
        </div>
    </div>

    <div class="text-center w-100 mt-3">
        {{ $refunds->links() }}
    </div>

    <div wire:loading>
        <x-loading />
    </div>
</div>
