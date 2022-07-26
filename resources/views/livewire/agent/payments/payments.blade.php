<div class="row m-2">
    <div wire:loading>
        <x-loading />
    </div>

    <div class="card px-0 overflow-hidden">
        <div class="card-header row align-items-center">
            <div class="col-7">
                <div class="text-bold">
                    Payments
                </div>
            </div>

            <div class="col">
                <div class="row">
                    <div class="col px-0">
                        <label for="" class="mb-0 text-small">Filter By</label>
                        <select wire:model="filter_by" class="form-control form-control-sm">
                            <option value="PAYMENT_DATE">PaymentDate</option>
                            <option value="METHOD">Method</option>
                            <option value="STATUS">Status</option>
                        </select>
                    </div>
                    <div class="col">
                        <label for="" class="mb-0 text-small">Filter Value</label>
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
                    <th scope="col" class="text-center" style="width:5%">#</th>
                    <th scope="col">Date</th>
                    <th scope="col">Purpose</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Method</th>
                    <th scope="col">Status</th>
                    <th scope="col">Remark</th>
                    <th scope="col" class="text-center">PaidBy</th>
                </tr>
            </thead>
            <tbody>
                
                @foreach($payments as $k => $payment)
                <tr wire:key="{{ uniqid() }}" class="text-small">
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
                    <td class="text-center">{{ $payment->createdBy->name }}</td>
                </tr>
                @endforeach

            </tbody>
            </table>
        </div>
    </div>

    <div class="text-center w-100 mt-3">
        {{ $payments->links() }}
    </div>
</div>
