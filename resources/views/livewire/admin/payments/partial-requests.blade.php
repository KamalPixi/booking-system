<div>
@include('includes.alert_failed_inner')
@include('includes.alert_success_inner')

<!-- title and menus -->
<div class="row">
    <div class="col-md-6">
        <h1 class="h5 mb-4 text-gray-800">Partial Payment Requests</h1>
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
                    <option value="REQUEST_DATE">PaymentDate</option>
                    <option value="STATUS">Status</option>
                </select>
            </div>
            <div class="col px-0 text-center">
                @if ($filter_by == 'REQUEST_DATE')
                    <input wire:key="{{ uniqid() }}" wire:model="filter_input" class="form-control form-control-sm" type="date">
                @endif
                @if ($filter_by == 'STATUS')
                <select wire:key="{{ uniqid() }}" wire:model="filter_input" class="form-control form-control-sm">
                    <option value="">Choose</option>
                    <option value="{{\App\Enums\TransactionEnum::STATUS['PENDING']}}">
                        {{\App\Enums\TransactionEnum::STATUS['PENDING']}}
                    </option>
                    <option value="{{\App\Enums\TransactionEnum::STATUS['APPROVED']}}">
                        {{\App\Enums\TransactionEnum::STATUS['APPROVED']}}
                    </option>
                    <option value="{{\App\Enums\TransactionEnum::STATUS['REJECTED']}}">
                        {{\App\Enums\TransactionEnum::STATUS['REJECTED']}}
                    </option>
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
                    <th scope="col">RequestBy</th>
                    <th scope="col">ApprovedAmount</th>
                    <th scope="col">ApprovedBy</th>
                    <th scope="col">Status</th>
                    <th scope="col">DueTime</th>
                    <th scope="col" style="width:5%">Action</th>
                </tr>
            </thead>
            <tbody class="small">
                @foreach($requests as $k => $request)
                    <tr wire:key="{{ uniqid() }}">
                        <th scope="row" class="pl-1 text-center">{{ $requests->firstItem() + $k }}</th>
                        <td>
                            {{ date('Y-m-d', strtotime($request->created_at)) }}
                            <span class="badge badge-secondary text-extra-small">{{ $request->created_at->diffForHumans() }}</span>
                        </td>
                        <td title="{{ $request->requestedBy->agent->company }}">{{ mb_strimwidth($request->requestedBy->agent->company, 0, 25, "...") }}</td>
                        <td class="font-weight-bold text-center">{{ number_format($request->approved_amount, 2) }}</td>
                        <td>{{  $request->approvedBy->name ?? '' }}</td>
                        <td> 
                            <span class="
                                badge 
                                @if(in_array($request->status, [
                                    \App\Enums\TransactionEnum::STATUS['APPROVED']
                                ])) 
                                    {{'badge-success'}} 
                                @elseif(in_array($request->status, [
                                    \App\Enums\TransactionEnum::STATUS['REJECTED'],
                                ])) 
                                    {{'badge-danger'}} 
                                @else 
                                    {{'badge-secondary'}} 
                                @endif"
                            >
                                {{ $request->status }}
                            </span> 
                        </td>

                        <td class="text-center">
                            {{ $request->due_time }}
                        </td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-info btn-sm dropdown-toggle btn-action" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-cog"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a target="_blank" href="{{ route('admin.booking.flights.view', $request->bookingRequestable->reference ?? '')  }}" class="dropdown-item">View Booking</a>
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
    {{ $requests->links() }}
</div>

<div wire:loading>
    <x-loading />
</div>

</div>
