<div>
@include('includes.errors')
@include('includes.alert_failed_inner')
@include('includes.alert_success_inner')

<!-- unit create form -->
<div class="card mb-2">
    <div class="card-body">
        @if(false)
            <h6 class="mb-2">Update</h6>
        @else
            <h6 class="mb-2">Add</h6>
        @endif
        <form>
            <div class="row">
                <div class="col-md-3 px-1">
                    <label for="">Name*</label>
                    <input wire:model="name" type="text" class="form-control form-control-sm">
                </div>
                <div class="col-md-3 px-1">
                    <label for="">Key*</label>
                    <select wire:model="fee_key" class="form-control form-control-sm">
                        <option value="">Choose</option>
                        @foreach (\App\Enums\AdminFeeEnum::KEY as $k)
                            <option value="{{$k}}">{{$k}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 px-1">
                    <label for="">Airline Code*</label>
                    <input wire:model="fee_key_sub" type="text" class="form-control form-control-sm">
                </div>
                <div class="col-md-3 px-1">
                    <label for="">Fee/Comission*</label>
                    <input wire:model="fee" type="number" class="form-control form-control-sm">
                </div>
                <div class="col-md-3 px-1">
                    <label for="">Status*</label>
                    <select wire:model="type" class="form-control form-control-sm">
                        <option value="PERCENTAGE">Percentage</option>
                        <option value="FIXED">Fixed Amount</option>
                    </select>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12">
                    @if(!empty($editing_id))
                        <button wire:click="update" type="button" class="btn btn-primary btn-sm min-w-10">
                            <div>Update</div>
                        </button>
                    @else
                        <div>
                            <button wire:click="create" type="button" class="btn btn-primary btn-sm min-w-10">
                                <div>Add</div>
                            </button>
                        </div>
                    @endif
                </div>
            </div>

        </form>
    </div>
</div>

<!-- show all units -->
<div class="card">
    <div class="card-body">
        <h6 class="mb-3">All Banks</h6>
        <table class="table table-sm table-bordered table-striped">
        <thead class="">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Key</th>
                <th scope="col">Airline Code</th>
                <th scope="col">Fee</th>
                <th scope="col">Type</th>
                <th scope="col" style="width:5%">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($fees as $key => $fee)
                <tr wire:key="{{$key}}">
                    <th scope="row">{{ $key + 1 }}</th>
                    <td> {{ $fee->name }} </td>
                    <td> {{ $fee->fee_key }} </td>
                    <td> {{ $fee->fee_key_sub }} </td>
                    <td> {{ number_format($fee->fee, 2) }} </td>
                    <td> {{ $fee->type }} </td>

                    <td>
                        <div class="btn-group">
                            <button class="btn btn-info btn-sm dropdown-toggle btn-action" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-cog"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <button class="dropdown-item" wire:click="edit({{ $fee->id }})">Edit</button>
                                <button class="dropdown-item" wire:click="delete({{ $fee->id }})">Delete</button>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
        </table>
    </div>
</div>


<div wire:loading wire:target="edit,update,create,delete">
    <x-loading />
</div>
</div>
