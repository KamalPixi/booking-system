<div>

<!-- title and menus -->
<div class="row">
    <div class="col-md-6">
        <h1 class="h5 mb-4 text-gray-800">Manual(Bank) Deposits</h1>
    </div>
    <div class="col-md-6">
        <div class="d-flex justify-content-center justify-content-md-end mb-2 mb-md-0">
            <!-- <a href="" class="btn btn-primary btn-sm">Add Payment</a> -->
        </div>
    </div>
</div>


@include('includes.alert_failed_inner')
@include('includes.alert_success_inner')
@include('includes.errors')

{{-- handle  --}}
@if($showDepositHandleForm)
<div wire:key="partial-request-form">
<div class="row my-4">
    <div class="col">
        <div class="card border border-info">
            <div class="card-header">Handle Deposit</div>
            <div class="card-body">

                <div class="row">
                    <table id="products" class="table table-sm table-bordered table-striped mx-3">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th scope="col">DepositDate</th>
                                <th scope="col">DepositNo</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Fee</th>
                                <th scope="col">Method</th>
                                <th scope="col">ToAccount</th>
                                <th scope="col">Status</th>
                                <th scope="col">Remark</th>
                                <th scope="col">AdminRemark</th>
                                <th scope="col">DepositedBy</th>
                                <th scope="col">Attachment</th>
                            </tr>
                        </thead>
                        <tbody class="small">
                                <tr wire:key="{{ uniqid() }}" class="line-height-1">
                                    <td>
                                        {{ date('Y-m-d', strtotime($depositModel->created_at)) }} <br>
                                        <span class="badge badge-secondary text-extra-small">{{ $depositModel->created_at->diffForHumans() }}</span>
                                    </td>
                                    <td>{{ $depositModel->transaction_no }}</td>
                                    <td class="font-weight-bold text-right">{{ number_format($depositModel->amount, 2) }}</td>
                                    <td class="text-right">{{ number_format($depositModel->fee, 2) }}</td>
                                    <td title="{{ $depositModel->method }}">{{ mb_strimwidth($depositModel->method, 0, 15, "..."); }}</td>
                                    <td class="line-height-1">

                                        @if(in_array($depositModel->method, [
                                            \App\Enums\TransactionEnum::METHOD['BANK_DEPOSIT']
                                        ]))
                                            @if(isset($depositModel->depositedAdminBankAccount->bank))
                                                {{ $depositModel->depositedAdminBankAccount->bank }} <br>
                                                {{ $depositModel->depositedAdminBankAccount->account_no }}
                                            @endif
                                        @else
                                            @if(isset($depositModel->depositedToMethod->key))
                                                {{ $depositModel->depositedToMethod->title }} <br>
                                                {{ $depositModel->depositedToMethod->key }}
                                                {{ $depositModel->depositedToMethod->remark }}
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
                                        @if(in_array($depositModel->status, [
                                            \App\Enums\TransactionEnum::STATUS['PROCESSING'],
                                            \App\Enums\TransactionEnum::STATUS['COMPLETED'],
                                            \App\Enums\TransactionEnum::STATUS['PAID'],
                                        ])) 
                                            {{'badge-success'}} 
                                        @elseif(in_array($depositModel->status, [
                                            \App\Enums\TransactionEnum::STATUS['FAILED'],
                                            \App\Enums\TransactionEnum::STATUS['CANCELED'],
                                        ])) 
                                            {{'badge-danger'}} 
                                        @else 
                                            {{'badge-secondary'}} 
                                        @endif"
                                    >
                                        {{ $depositModel->status }}
                                    </span> 
                                    </td>
                                    <td>
                                        <textarea readonly class="form-control form-control-sm text-small" cols="3" rows="1">{{ $depositModel->remark }}</textarea>
                                    </td>
                                    <td>
                                        <textarea readonly class="form-control form-control-sm text-small" cols="3" rows="1">{{ $depositModel->admin_remark }}</textarea>
                                    </td>
                                    <td class="text-center" title="{{ $depositModel->depositedBy->agent->company }}">
                                        {{ mb_strimwidth($depositModel->depositedBy->agent->company, 0, 16, "..."); }}
                                    </td>
                                    <td class="text-center" style="width:5%">
                                        @if(isset($depositModel->files[0]->file))
                                            <a href="{{ $depositModel->files[0]->file }}" target="_blank" class="btn-link">
                                                View
                                            </a>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                        </tbody>
                    </table>
                </div>

                <form wire:submit.prevent="handleDepositForm">
                    <div class="row mb-3">
                        <div class="col">
                            <label for="">Amount</label>
                            <input wire:model="amount" class="form-control form-control-sm" type="number">
                        </div>
                        <div class="col">
                            <label for="">Fee</label>
                            <input wire:model="fee" class="form-control form-control-sm" type="number">
                        </div>

                        <div class="col">
                            <label for="">Status*</label>
                            <select wire:model="status" class="form-control form-control-sm">
                                <option value="">Choose</option>
                                <option value="{{ \App\Enums\TransactionEnum::STATUS['COMPLETED'] }}">
                                    {{ \App\Enums\TransactionEnum::STATUS['COMPLETED'] }}
                                </option>
                                <option value="{{ \App\Enums\TransactionEnum::STATUS['CANCELED'] }}">
                                    {{ \App\Enums\TransactionEnum::STATUS['CANCELED'] }}
                                </option>
                            </select>
                        </div>
                        <div class="col">
                            <label for="">Admin Remark</label>
                            <textarea wire:model="admin_remark" class="form-control form-control-sm"></textarea>
                        </div>
                    </div>

                    <!-- submit button -->
                    <div class="row">
                        <div class="col d-flex">
                            <div>
                                <button type="submit" class="btn btn-primary btn-sm px-4">Submit</button>
                            </div>

                            <div class="ml-2">
                                <button wire:click="hideDepositHandleForm" type="button" class="btn btn-secondary btn-sm px-4">Cancel</button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
</div>

@endif

<!-- search -->
<div class="card">
    <div class="card-body">
        <div class="row justify-content-center">
            <div class="col"></div>
            <div class="col text-center">
                <select wire:model="filter_by" class="form-control form-control-sm">
                    <option value="DEPOSIT_DATE">DepositDate</option>
                    <option value="DEPOSIT_NO">DepositNo</option>
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
                    <th scope="col">DepositDate</th>
                    <th scope="col">DepositNo</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Fee</th>
                    <th scope="col">Method</th>
                    <th scope="col">ToAccount</th>
                    <th scope="col">Status</th>
                    <th scope="col">Remark</th>
                    <th scope="col">AdminRemark</th>
                    <th scope="col">DepositedBy</th>
                    <th scope="col">Attachment</th>
                    <th scope="col" style="width:5%">Action</th>
                </tr>
            </thead>
            <tbody class="small">
                @foreach($deposits as $k => $deposit)
                    <tr wire:key="{{ uniqid() }}" class="line-height-1">
                        <th scope="row" class="pl-1 text-center">{{ $deposits->firstItem() + $k }}</th>
                        <td>
                            {{ date('Y-m-d', strtotime($deposit->created_at)) }} <br>
                            <span class="badge badge-secondary text-extra-small">{{ $deposit->created_at->diffForHumans() }}</span>
                        </td>
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
                        <td>
                            <textarea readonly class="form-control form-control-sm text-small" cols="3" rows="1">{{ $deposit->admin_remark }}</textarea>
                        </td>
                        <td class="text-center" title="{{ $deposit->depositedBy->agent->company }}">
                            {{ mb_strimwidth($deposit->depositedBy->agent->company, 0, 16, "..."); }}
                        </td>
                        <td class="text-center" style="width:5%">
                            @if(isset($deposit->files[0]->file))
                                <a href="{{ $deposit->files[0]->file }}" target="_blank" class="btn-link">
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
                                    <button wire:click="showDepositHandleForm({{ $deposit->id }})" class="dropdown-item">Handle Deposit</button>
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

<div 
    wire:loading 
    wire:target="showDepositHandleForm,hideDepositHandleForm"
    >
    <x-loading />
</div>

</div>
