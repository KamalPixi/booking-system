<div class="container">
    <div class="row">
        <div class="col-2"></div>
        <div class="col-md-8">
            <div class="bg-white shadow-md rounded p-3 mt-5">

                <div class="row">
                    <div class="col text-end">
                        <a class="btn btn-sm btn-secondary" href="{{ route('b2b.gateway.addBalanceManual') }}">
                            <i class="fa-solid fa-money-check-dollar"></i>
                            Add Manually
                        </a>
                    </div>
                </div>

                <div class="row">
                    <div class="col"></div>
                    <div class="col-7">
                        <div class="card text-center py-3" style="line-height:1rem;">
                            Accounts: <span style="font-weight:600;">+880 1847-557770</span>
                        </div>

                        <h2 class="text-6 mb-4 mt-4 text-center">Online Payment</h2>

                        <div>
                            @include('includes.errors')
                            @include('includes.alert_failed_inner')
                            @include('includes.alert_success_inner')
                        </div>

                        <form action="{{ route('b2b.gateway.addBalance') }}" id="add-balance" method="post">
                            @csrf
                            <div class="form-group mb-1">
                                <label for="operator" class="mb-0 text-small">Amount</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"> <span class="input-group-text">BDT</span> </div>
                                    <input wire:model="amount" class="form-control" name="amount" id="amount" placeholder="Enter Amount" autocomplete="off" required type="text">
                                </div>
                            </div>
                            <div class="form-group mb-1">
                                <label for="operator" class="mb-0 text-small">
                                    Service Charge
                                    @if(isset($transactionMethod->fee_type)) 
                                        <small class="badge bg-secondary">({{ $transactionMethod->fee_type }})</small>
                                    @endif
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend"> <span class="input-group-text">BDT</span> </div>
                                    <input wire:model="service_charge" class="form-control" readonly placeholder="Getway Service Charge" autocomplete="off" required type="text">
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="operator" class="mb-0 text-small">Final Deposit Amount</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"> <span class="input-group-text">BDT</span> </div>
                                    <input wire:model="final_deposit_amount" class="form-control" readonly placeholder="Final Deposit Amount" autocomplete="off" required type="text">
                                </div>
                            </div>
                            <button type="button" wire:click="submit" class="btn btn-primary btn-block mb-4">Pay</button>
                        </form>
                    </div>
                    <div class="col"></div>
                </div>
            </div>
        </div>
        <div class="col-2"></div>
    </div>
</div>
