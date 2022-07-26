<div>
@include('includes.alert_failed_inner')
@include('includes.alert_success_inner')

<!-- title and menus -->
<div class="row">
    <div class="col-md-6">
        <h1 class="h5 mb-4 text-gray-800">All Transactions</h1>
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
                    <option value="DATE">Date</option>
                    <option value="PURPOSE">Purpose</option>
                    <option value="METHOD">Method</option>
                    <option value="SIGN">Sign</option>
                </select>
            </div>
            <div class="col px-0 text-center">
                @if ($filter_by == 'DATE')
                    <input wire:key="{{ uniqid() }}" wire:model="filter_input" class="form-control form-control-sm" type="date">
                @endif

                @if ($filter_by == 'PURPOSE')
                <select wire:key="purpose-key" wire:model="filter_input" class="form-control form-control-sm">
                    <option value="">Choose</option>
                    @foreach(\App\Enums\TransactionEnum::PURPOSE as $pKey => $p)
                        <option value="{{ $pKey }}">{{ $p }}</option>
                    @endforeach
                </select>
                @endif

                @if ($filter_by == 'METHOD')
                <select wire:key="method-key" wire:model="filter_input" class="form-control form-control-sm">
                    <option value="">Choose</option>
                    <option value="{{\App\Enums\TransactionEnum::METHOD['ACCOUNT_BALANCE']}}">{{\App\Enums\TransactionEnum::METHOD['ACCOUNT_BALANCE']}}</option>
                    @foreach($transactionMethods as $transactionMethod)
                        <option value="{{ $transactionMethod->key }}">{{ $transactionMethod->title }}</option>
                    @endforeach
                </select>
                @endif

                @if ($filter_by == 'SIGN')
                <select wire:key="sign-key" wire:model="filter_input" class="form-control form-control-sm">
                    <option value="">Choose</option>
                    <option value="+">Plus</option>
                    <option value="-">Minus</option>
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
                    <th scope="col">Type</th>
                    <th scope="col">ReferenceNo.</th>
                    <th scope="col">Purpose</th>
                    <th scope="col">Method</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Date</th>
                    <th scope="col">initiatedBy</th>
                    <th scope="col">Remark</th>
                    {{-- <th scope="col" style="width:5%">Action</th> --}}
                </tr>
            </thead>
            <tbody class="small">
                @foreach($transactions as $k => $transaction)
                    <tr wire:key="transactions-{{ $transactions->firstItem() + $k }}">
                        <th scope="row" class="pl-1 text-center">{{ $transactions->firstItem() + $k }}</th>
                        <td>{{ explode('\\', $transaction->transactionable_type)[2] ?? '' }}</td>
                        <td class="text-center">{{ $transaction->transactionable->transaction_no ?? 'N/A' }}</td>
                        <td>{{ str_replace('_', ' ', $transaction->purpose) }}</td>
                        <td>{{ $transaction->method }}</td>

                        <td class="font-weight-bold text-right">
                            <span>{{$transaction->sign}}</span>
                            {{ number_format($transaction->amount, 2) }}
                        </td>

                        <td class="font-weight-bold text-center">
                            {{ date('Y-m-d', strtotime($transaction->created_at)) }}
                            <span class="badge badge-secondary">{{ $transaction->created_at->diffForHumans() }}</span>
                        </td>
                        <td>
                            {{-- for payment --}}
                            @if (isset($transaction->transactionable->createdBy))
                                {{-- if agent then show agent company name --}}
                                @if (isset($transaction->transactionable->createdBy->agent->company))
                                    {{ $transaction->transactionable->createdBy->agent->company }}
                                @else
                                    {{-- if customer then show customer name --}}
                                    @if (isset($transaction->transactionable->createdBy->name))
                                        {{ $transaction->transactionable->createdBy->name }}
                                    @endif
                                @endif
                            @endif
                            
                            {{-- for deposit --}}
                            @if (isset($transaction->transactionable->depositedBy))
                                {{-- if agent then show agent company name --}}
                                @if (isset($transaction->transactionable->depositedBy->agent->company))
                                    {{ $transaction->transactionable->depositedBy->agent->company }}
                                @else
                                    {{-- if user/customer then show user/customer name --}}
                                    @if (isset($transaction->transactionable->depositedBy->name))
                                        {{ $transaction->transactionable->depositedBy->name }}
                                    @endif
                                @endif
                            @endif

                            {{-- for refund --}}
                            @if (isset($transaction->transactionable->refundedBy))
                                {{ $transaction->transactionable->refundedBy->name }}
                            @endif
                        </td>

                        <td>
                            <textarea readonly class="form-control form-control-sm text-small" cols="3" rows="1">{{ $transaction->remark }}</textarea>
                        </td>

                        {{-- <td>
                            <div class="btn-group">
                                <button class="btn btn-info btn-sm dropdown-toggle btn-action" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-cog"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a target="_blank" href="" class="dropdown-item">View</a>
                                </div>
                            </div>
                        </td> --}}
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="pagination-container mt-3">
    {{ $transactions->links() }}
</div>

<div wire:loading>
    <x-loading />
</div>

</div>
