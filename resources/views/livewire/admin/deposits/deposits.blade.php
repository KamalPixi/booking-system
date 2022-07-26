<div>
@include('includes.alert_failed_inner')
@include('includes.alert_success_inner')

<!-- title and menus -->
<div class="row">
    <div class="col-md-6">
        <h1 class="h5 mb-4 text-gray-800">All Deposits</h1>
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
                    <option value="DEPOSIT_DATE">DepositDate</option>
                    <option value="DEPOSIT_NO">DepositNo</option>
                    <option value="METHOD">Method</option>
                    <option value="STATUS">Status</option>
                </select>
            </div>
            <div class="col px-0 text-center">
                @if ($filter_by == 'DEPOSIT_DATE')
                    <input wire:key="{{ uniqid() }}" wire:model="filter_input" class="form-control form-control-sm" type="date">
                @endif
                @if ($filter_by == 'DEPOSIT_NO')
                    <input wire:key="{{ uniqid() }}" placeholder="Enter deposit no." wire:model="filter_input" class="form-control form-control-sm" type="text">
                @endif
                @if ($filter_by == 'METHOD')
                <select wire:key="{{ uniqid() }}" wire:model="filter_input" class="form-control form-control-sm">
                    <option value="">Choose</option>
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
                    <th scope="col">DepositNo</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Fee</th>
                    <th scope="col">Method</th>
                    <th scope="col">ToAccount</th>
                    <th scope="col">Status</th>
                    <th scope="col">Remark</th>
                    <th scope="col">DepositedBy</th>
                    <th scope="col">Attachment</th>
                    <th scope="col" style="width:5%">Action</th>
                </tr>
            </thead>
            <tbody class="small">
                @foreach($deposits as $k => $deposit)
                    <tr wire:key="{{ uniqid() }}">
                        <th scope="row" class="pl-1 text-center">{{ $deposits->firstItem() + $k }}</th>
                        <td>{{ date('Y-m-d', strtotime($deposit->created_at)) }}</td>
                        <td>{{ $deposit->transaction_no }}</td>
                        <td class="font-weight-bold text-right">{{ number_format($deposit->amount, 2) }}</td>
                        <td class="text-right">{{ number_format($deposit->fee, 2) }}</td>
                        <td title="{{ $deposit->method }}">{{ mb_strimwidth($deposit->method, 0, 15, "..."); }}</td>
                        <td class="line-height-1">
                            @if(in_array($deposit->method, [
                                \App\Enums\TransactionEnum::METHOD['BANK_DEPOSIT']
                            ]))
                                @if(isset($deposit->depositedAdminBankAccount->bank))
                                    {{ $deposit->depositedAdminBankAccount->bank }} <br>
                                    {{ $deposit->depositedAdminBankAccount->account_no }}
                                @endif
                            @else
                                @if(isset($deposit->depositedToMethod->key))
                                    {{ $deposit->depositedToMethod->title }} <br>
                                    {{ $deposit->depositedToMethod->key }}
                                    {{ $deposit->depositedToMethod->remark }}
                                @else
                                <div class="pl-4">
                                    N/A
                                </div>
                                @endif
                            @endif
                        </td>
                        <td> 
                        <span class="
                            badge 
                            @if(in_array($deposit->status, [
                                \App\Enums\TransactionEnum::STATUS['PROCESSING'],
                                \App\Enums\TransactionEnum::STATUS['COMPLETED'],
                                \App\Enums\TransactionEnum::STATUS['PAID'],
                            ])) 
                                {{'badge-success'}} 
                            @elseif(in_array($deposit->status, [
                                \App\Enums\TransactionEnum::STATUS['FAILED'],
                                \App\Enums\TransactionEnum::STATUS['CANCELED'],
                            ])) 
                                {{'badge-danger'}} 
                            @else 
                                {{'badge-secondary'}} 
                            @endif"
                        >
                            {{ $deposit->status }}
                        </span> 
                        </td>
                        <td>
                            <textarea readonly class="form-control form-control-sm text-small" cols="3" rows="1">{{ $deposit->remark }}</textarea>
                        </td>
                        <td class="text-center" title="{{ $deposit->depositedBy->agent->company }}">
                            {{ mb_strimwidth($deposit->depositedBy->agent->company, 0, 16, "..."); }}
                        </td>
                        <td class="text-center" style="width:5%">
                            @if(isset($deposit->files[0]->file))
                            <a href="{{ asset('storage/files/' . basename($deposit->files[0]->file)) }}" target="_blank" class="btn-link">
                                View
                            </a>
                            @else
                                N/A
                            @endif
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
    {{ $deposits->links() }}
</div>

<div wire:loading>
    <x-loading />
</div>

</div>
