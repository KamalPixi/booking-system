    <div wire:ignore.self class="my-modal" id="myModal">
        <div class="my-modal-content rounded">
            <!--header-->
            <header class="modal-header py-2">
                <p class="modal-title text-2 font-weight-bold" id="paymentModalLabel">Make Partial Payment</p>
                <button onclick="hideElement('myModal')" type="button" class="btn-close"></button>
            </header>

            <!--body-->
            <div class="p-4">
                @include('includes.errors')
                @include('includes.alert_failed_inner')
                @include('includes.alert_success_inner')
                
                <div class="row">
                    <div class="col-2"></div>
                    <div class="col-8">
                        <form>
                            <div class="form-group mb-2">
                                <label for="operator" class="mb-0 text-small">Amount</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"> <span class="input-group-text">BDT</span> </div>
                                    <input wire:model="payment_amount" class="form-control" name="amount" id="amount" placeholder="Enter Amount" autocomplete="off" required type="text">
                                </div>
                            </div>
                            <button type="button" wire:click="makePartialPayment" class="btn btn-primary btn-block btn-sm">Pay from Balance</button>
                        </form>
                    </div>
                    <div class="col-2"></div>
                </div>
            </div>

            <!--footer-->
            <footer class="modal-footer py-1">
                <button onclick="hideElement('myModal')" type="button" class="btn btn-secondary btn-sm py-1">Close</button>
            </footer>
        </div>
    </div>