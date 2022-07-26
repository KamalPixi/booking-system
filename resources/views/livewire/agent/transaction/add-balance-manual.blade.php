<div class="container">

    <div wire:loading wire:target="submit">
        <x-loading />
    </div>

    <div class="row">
        <div class="col-1"></div>
        <div class="col-md-10">
            <div class="bg-white shadow-md rounded p-3 mt-5">

                <div class="row">
                    <div class="col">
                        <a class="btn btn-sm btn-link btn-light" href="{{ route('b2b.gateway.addBalance') }}">
                            <- Go Back
                        </a>
                    </div>
                </div>

                <div class="row px-3">
                    <div class="col">
                        <h2 class="text-6 mb-4 mt-4 text-center">Add Balance</h2>

                        <div>
                            @include('includes.errors')
                            @include('includes.alert_failed_inner')
                            @include('includes.alert_success_inner')
                        </div>

                        <form id="recharge-bill">

                            <div class="row">
                                <div class="col px-1">
                                    <label for="operator" class="mb-0 text-small">Amount*</label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend"> <span class="input-group-text">BDT</span> </div>
                                        <input wire:model="amount" class="form-control" name="amount" id="amount" placeholder="Enter Amount" autocomplete="off" required type="text">
                                    </div>
                                </div>
                                <div class="col px-1">
                                    <div class="form-group">
                                        <label for="operator" class="mb-0 text-small">Deposit Method*</label>
                                        <select wire:model="deposit_method_key" onfocusout="livewireEmitEvent('ONFOCUSOUT', 'DepositedToAccount')" class="custom-select" id="operator" required="">
                                            <option value="">Select method</option>
                                            @foreach(\App\Enums\TransactionEnum::METHOD as $key => $depositMethod)
                                                @if(in_array($key, [
                                                    \App\Enums\TransactionEnum::METHOD['ONLINE_PAYMENT'],
                                                    \App\Enums\TransactionEnum::METHOD['ACCOUNT_BALANCE']
                                                ])) @continue @endif
                                                <option wire:key="deposit-method-{{$key}}" value="{{ $key }}">{{ $depositMethod }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col px-1">
                                    <div class="form-group position-relative">
                                        <label for="mobileNumber" class="mb-0 text-small">Deposited To Account*</label>
                                        <textarea wire:model="deposited_to_account_text" onfocusout="livewireEmitEvent('ONFOCUSOUT', 'DepositedToAccount')" wire:click="showDepositAccounts" placeholder="Click to Select Account &darr;" readonly cols="30" rows="1" class="form-control text-small line-height-1"></textarea>

                                        @if(count($accounts) > 0)
                                        <div wire:key="deposit-accounts" class="border shadow-sm position-absolute bg-white w-100 p-2 z-10">
                                            <ul class="line-height-1">
                                                
                                                @foreach($accounts as $k => $account)
                                                <li wire:key="deposit-account-{{$k}}" wire:click="setDepositedId({{$account['id']}},'{{$account['type']}}', '{{$account['method_id']}}')" type="button" class="border-bottom mb-1 pb-1">
                                                    <h6 class="text-2">{{ $account['bank'] }}</h6>
                                                    <small>A/C No: {{ $account['account_no'] }}</small> <br>
                                                    <small>Holder Name: {{ $account['account_name'] }}</small> <br>
                                                </li>
                                                @endforeach

                                            </ul>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col px-1">
                                    <div class="form-group">
                                        <label for="mobileNumber" class="mb-0 text-small">
                                            Service Charge
                                            @if(isset($transactionMethod->fee_type)) 
                                                <small wire:key="service-charge" class="badge bg-secondary">({{ $transactionMethod->fee_type }})</small>
                                            @endif
                                        </label>
                                        <input wire:model="service_charge" type="text" readonly class="form-control" placeholder="0.00">
                                    </div>
                                </div>
                                <div class="col px-1">
                                    <div class="form-group">
                                        <label for="mobileNumber" class="mb-0 text-small">Final Deposit Amount</label>
                                        <input wire:model="deposit_amount" type="text" readonly class="form-control" placeholder="0.00">
                                    </div>
                                </div>
                                <div class="col px-1">
                                    <div class="form-group mb-2">
                                        <label for="operator" class="mb-0 text-small">Proof Attachment*</label>
                                        <input wire:model="attachment" type="file" class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="mobileNumber" class="mb-0 text-small">Remark</label>
                                        <textarea wire:model="remark" class="form-control" placeholder="Enter Remark"></textarea>
                                    </div>
                                </div>
                            </div>


                            <button type="button" wire:click="submit" class="btn btn-primary btn-block mb-4">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-1"></div>
    </div>


    <div wire:loading wire:target="setDepositedId,attachment,showDepositAccounts,deposit_method_key">
        <x-loading />
    </div>
</div>
