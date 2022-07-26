<div>
@include('includes.alert_failed_inner')
@include('includes.alert_success_inner')

<!-- title and menus -->
<div class="row">
    <div class="col-md-6">
        <h1 class="h5 mb-4 text-gray-800">All Payments</h1>
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
                    <option value="PAYMENT_DATE">PaymentDate</option>
                    <option value="METHOD">Method</option>
                    <option value="STATUS">Status</option>
                </select>
            </div>
            <div class="col px-0 text-center">
                @if ($filter_by == 'PAYMENT_DATE')
                    <input wire:key="{{ uniqid() }}" wire:model="filter_input" class="form-control form-control-sm" type="date">
                @endif
                @if ($filter_by == 'METHOD')
                <select wire:key="{{ uniqid() }}" wire:model="filter_input" class="form-control form-control-sm">
                    <option value="">Choose</option>
                    <option value="{{\App\Enums\TransactionEnum::METHOD['ACCOUNT_BALANCE']}}">{{\App\Enums\TransactionEnum::METHOD['ACCOUNT_BALANCE']}}</option>
                    @foreach($transactionMethods as $transactionMethod)
                    <option value="{{ $transactionMethod->key }}">{{ $transactionMethod->title }}</option>
                    @endforeach
                </select>
                @endif
                @if ($filter_by == 'STATUS')
                <select wire:key="{{ uniqid() }}" wire:model="filter_input" class="form-control form-control-sm">
                    <option value="">Choose</option>
                    <option value="{{\App\Enums\TransactionEnum::STATUS['PENDING']}}">
                        {{\App\Enums\TransactionEnum::STATUS['PENDING']}}
                    </option>
                    <option value="{{\App\Enums\TransactionEnum::STATUS['COMPLETED']}}">
                        {{\App\Enums\TransactionEnum::STATUS['COMPLETED']}}
                    </option>
                    <option value="{{\App\Enums\TransactionEnum::STATUS['CANCELED']}}">
                        {{\App\Enums\TransactionEnum::STATUS['CANCELED']}}
                    </option>
                    <option value="{{\App\Enums\TransactionEnum::STATUS['FAILED']}}">
                        {{\App\Enums\TransactionEnum::STATUS['FAILED']}}
                    </option>
                    <option value="{{\App\Enums\TransactionEnum::STATUS['PROCESSING']}}">
                        {{\App\Enums\TransactionEnum::STATUS['PROCESSING']}}
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
                    <th scope="col">Date</th>
                    <th scope="col">Purpose</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Method</th>
                    <th scope="col">Status</th>
                    <th scope="col">Remark</th>
                    <th scope="col" class="text-center">PaidBy</th>
                    <th scope="col" style="width:5%">Action</th>
                </tr>
            </thead>
            <tbody class="small">
                @foreach($payments as $k => $payment)
                    <tr wire:key="{{ uniqid() }}">
                        <th scope="row" class="pl-1 text-center">{{ $payments->firstItem() + $k }}</th>
                        <td>{{ date('Y-m-d', strtotime($payment->created_at)) }}</td>
                        <td>{{ str_replace('_', ' ', $payment->purpose) }}</td>
                        <td class="font-weight-bold">{{ number_format($payment->amount, 2) }}</td>
                        <td title="{{ $payment->method }}">{{ mb_strimwidth($payment->method, 0, 15, "..."); }}</td>
                        <td> 
                        <span class="
                            badge 
                            @if(in_array($payment->status, [
                                \App\Enums\TransactionEnum::STATUS['PROCESSING'],
                                \App\Enums\TransactionEnum::STATUS['COMPLETED'],
                                \App\Enums\TransactionEnum::STATUS['PAID'],
                            ])) 
                                {{'badge-success'}} 
                            @elseif(in_array($payment->status, [
                                \App\Enums\TransactionEnum::STATUS['FAILED'],
                                \App\Enums\TransactionEnum::STATUS['CANCELED'],
                            ])) 
                                {{'badge-danger'}} 
                            @elseif(in_array($payment->status, [
                                \App\Enums\TransactionEnum::STATUS['PARTIAL_PAID'],
                            ])) 
                                {{'badge-warning'}} 
                            @else 
                                {{'badge-secondary'}} 
                            @endif"
                        >
                            {{ $payment->status }}
                        </span> 
                        </td>
                        <td>
                            <textarea readonly class="form-control form-control-sm text-small" cols="3" rows="1">{{ $payment->remark }}</textarea>
                        </td>
                        <td class="text-center" title="{{ $payment->createdBy->agent->company ?? '' }}">
                            {{ mb_strimwidth($payment->createdBy->agent->company ?? '', 0, 16, "..."); }}
                        </td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-info btn-sm dropdown-toggle btn-action" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-cog"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    {{-- <a target="_blank" href="" class="dropdown-item">View</a> --}}
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
    {{ $payments->links() }}
</div>

<div wire:loading>
    <x-loading />
</div>

</div>
