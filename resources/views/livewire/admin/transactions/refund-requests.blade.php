<div>
@include('includes.alert_failed_inner')
@include('includes.alert_success_inner')

<!-- title and menus -->
<div class="row">
    <div class="col-md-6">
        <h1 class="h5 mb-4 text-gray-800">Refund Requests</h1>
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
                    <option value="DATE">RequestDate</option>
                    <option value="FOR">RequestFor</option>
                </select>
            </div>
            <div class="col px-0 text-center">
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
                    <th scope="col">RequestDate</th>
                    <th scope="col">RequestFor</th>
                    <th scope="col">RequestBy</th>
                    <th scope="col">Remark</th>
                    <th scope="col" style="width:5%">Action</th>
                </tr>
            </thead>
            <tbody class="small">
                @foreach($refunds as $k => $refund)
                    <tr wire:key="refunds-{{ $refunds->firstItem() + $k }}">
                        <td>
                            {{ $refunds->firstItem() + $k }}
                        </td>
                        <td>
                            {{ date('Y-m-d', strtotime($refund->created_at)) }}
                            <span class="badge badge-secondary">{{ $refund->created_at->diffForHumans() }}</span>
                        </td>
                        <td>
                            {{ $refund->refundableTypeModelName() }}
                        </td>
                        <td>
                            {{ $refund->requestedBy->agent->company ?? '' }}
                        </td>
                        <td>
                            <textarea readonly class="form-control">{{ $refund->remark }}</textarea>
                        </td>

                        <td>
                            <div class="btn-group">
                                <button class="btn btn-info btn-sm dropdown-toggle btn-action" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-cog"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    {{-- air booking --}}
                                    @if ($refund->refundableTypeModelName() == 'AirBooking')
                                        <a target="_blank" href="{{ route('admin.booking.flights.view', $refund->refundable->reference) }}" class="dropdown-item">View Request Item</a>
                                    @endif
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
    {{ $refunds->links() }}
</div>

<div wire:loading>
    <x-loading />
</div>

</div>
