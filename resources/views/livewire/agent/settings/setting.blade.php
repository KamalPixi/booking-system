<div>
    @if($editProfit)
        <div wire:key="user_edit_form" class="px-4">

            @include('includes.errors')
            @include('includes.alert_failed_inner')
            @include('includes.alert_success_inner')

            <form autocomplete="off">
                <div class="row mb-1">
                    <div class="col-6 px-1">
                        <label for="" class="mb-0 text-small">Air Booking Profit Margin Amount</label>
                        <input wire:model="air_booking_margin_amount" class="form-control form-control-sm" type="text">
                    </div>
                    <div class="col-6 px-1">
                        <label for="" class="mb-0 text-small">Fixed/Percentage</label>
                        <select wire:model="air_booking_margin_type" class="form-control form-control-sm">
                            <option value="">Choose</option>
                            <option value="{{ \App\Enums\TransactionEnum::METHOD_FEE_TYPE['PERCENTAGE'] }}">Percentage</option>
                            <option value="{{ \App\Enums\TransactionEnum::METHOD_FEE_TYPE['FIXED'] }}">Fixed</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-6 px-1">
                        <label for="" class="mb-0 text-small">Hotel Booking Profit Margin Amount</label>
                        <input wire:model="hotel_booking_margin_amount" class="form-control form-control-sm" type="text">
                    </div>
                    <div class="col-6 px-1">
                        <label for="" class="mb-0 text-small">Fixed/Percentage</label>
                        <select wire:model="hotel_booking_margin_type" class="form-control form-control-sm">
                            <option value="">Choose</option>
                            <option value="{{ \App\Enums\TransactionEnum::METHOD_FEE_TYPE['PERCENTAGE'] }}">Percentage</option>
                            <option value="{{ \App\Enums\TransactionEnum::METHOD_FEE_TYPE['FIXED'] }}">Fixed</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-6 px-1">
                        <label for="" class="mb-0 text-small">Holiday Booking Profit Margin Amount</label>
                        <input wire:model="holiday_booking_margin_amount" class="form-control form-control-sm" type="text">
                    </div>
                    <div class="col-6 px-1">
                        <label for="" class="mb-0 text-small">Fixed/Percentage</label>
                        <select wire:model="holiday_booking_margin_type" class="form-control form-control-sm">
                            <option value="">Choose</option>
                            <option value="{{ \App\Enums\TransactionEnum::METHOD_FEE_TYPE['PERCENTAGE'] }}">Percentage</option>
                            <option value="{{ \App\Enums\TransactionEnum::METHOD_FEE_TYPE['FIXED'] }}">Fixed</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-6 px-1">
                        <label for="" class="mb-0 text-small">Umrah Booking Profit Margin Amount</label>
                        <input wire:model="umrah_booking_margin_amount" class="form-control form-control-sm" type="text">
                    </div>
                    <div class="col-6 px-1">
                        <label for="" class="mb-0 text-small">Fixed/Percentage</label>
                        <select wire:model="umrah_booking_margin_type" class="form-control form-control-sm">
                            <option value="">Choose</option>
                            <option value="{{ \App\Enums\TransactionEnum::METHOD_FEE_TYPE['PERCENTAGE'] }}">Percentage</option>
                            <option value="{{ \App\Enums\TransactionEnum::METHOD_FEE_TYPE['FIXED'] }}">Fixed</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-6 px-1">
                        <label for="" class="mb-0 text-small">Group Booking Profit Margin Amount</label>
                        <input wire:model="group_booking_margin_amount" class="form-control form-control-sm" type="text">
                    </div>
                    <div class="col-6 px-1">
                        <label for="" class="mb-0 text-small">Fixed/Percentage</label>
                        <select wire:model="group_booking_margin_type" class="form-control form-control-sm">
                            <option value="">Choose</option>
                            <option value="{{ \App\Enums\TransactionEnum::METHOD_FEE_TYPE['PERCENTAGE'] }}">Percentage</option>
                            <option value="{{ \App\Enums\TransactionEnum::METHOD_FEE_TYPE['FIXED'] }}">Fixed</option>
                        </select>
                    </div>
                </div>
            </form>

                <div wire:key="button_update" class="mt-4">
                    <button wire:click="update" class="btn btn-sm btn-primary min-w-10">
                        <div wire:loading.remove wire:target="update">Update Profile</div>
                        <div wire:loading wire:target="update" class="loader loader-for-btn">Loading...</div>
                    </button>
                    <button wire:click="unsetEditProfit" class="btn btn-sm btn-secondary min-w-5">
                        <div wire:loading.remove wire:target="unsetEditProfit">Close</div>
                        <div wire:loading wire:target="unsetEditProfit" class="loader loader-for-btn">Loading...</div>
                    </button>
                </div>

        </div>
    @else
        <div class="px-4 pt-4">
            <h6>Profit Margins</h6>
            <hr>

            @include('includes.alert_failed_inner')
            @if(auth()->user()->can('agent_view setting'))
                <table class="table table-sm table-striped mb-4">
                    <tr>
                        <th style="width:50%">Air Booking Profit Margin</th>
                        <td>: 
                            @if($airM)
                                {{ number_format($airM->amount, 2) }} 
                                @if($airM->type == App\Enums\TransactionEnum::METHOD_FEE_TYPE['PERCENTAGE']) 
                                    %
                                @endif
                            @else
                                0.00
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th style="width:50%">Hotel Booking Profit Margin</th>
                        <td>: 
                            @if($hotelM)
                                {{ number_format($hotelM->amount, 2) }} 
                                @if($hotelM->type == App\Enums\TransactionEnum::METHOD_FEE_TYPE['PERCENTAGE']) 
                                    %
                                @endif
                            @else
                                0.00
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th style="width:50%">Holiday Booking Profit Margin</th>
                        <td>: 
                            @if($holidayM)
                                {{ number_format($holidayM->amount, 2) }} 
                                @if($holidayM->type == App\Enums\TransactionEnum::METHOD_FEE_TYPE['PERCENTAGE']) 
                                    %
                                @endif
                            @else
                                0.00
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th style="width:50%">Umrah Booking Profit Margin</th>
                        <td>: 
                            @if($umrahM)
                                {{ number_format($umrahM->amount, 2) }} 
                                @if($umrahM->type == App\Enums\TransactionEnum::METHOD_FEE_TYPE['PERCENTAGE']) 
                                    %
                                @endif
                            @else
                                0.00
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th style="width:50%">Group Tour Profit Margin</th>
                        <td>: 
                            @if($groupM)
                                {{ number_format($groupM->amount, 2) }} 
                                @if($groupM->type == App\Enums\TransactionEnum::METHOD_FEE_TYPE['PERCENTAGE']) 
                                    %
                                @endif
                            @else
                                0.00
                            @endif
                        </td>
                    </tr>
                </table>

                <div wire:key="button_edit">
                    <button wire:click="setEditProfit" class="btn btn-sm btn-primary min-w-10">
                        <div wire:loading.remove>Edit Profit Margin</div>
                        <div wire:loading class="loader loader-for-btn">Loading...</div>
                    </button>
                </div>
            @else
                @include('agent.includes.unauthorized-text')
            @endif
        </div>
    @endif

</div>