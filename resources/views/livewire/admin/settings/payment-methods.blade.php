<div>
@include('includes.errors')
@include('includes.alert_failed_inner')
@include('includes.alert_success_inner')

<!-- unit create form -->
<div class="card mb-2">
    <div class="card-body">
        @if($is_editing)
            <h6 class="mb-2">Update Payment Method</h6>
        @else
            <h6 class="mb-2">Add New Payment Method</h6>
        @endif
        <form>
            <div class="row">
                <div class="col-md-3 px-1">
                    <label for="">Method Name*</label>
                    <input wire:model="title" type="text" class="form-control form-control-sm">
                </div>
                <div class="col-md-3 px-1">
                    <label for="">Method Key*</label>
                    <select wire:model="key" class="form-control form-control-sm">
                        <option value="">Choose</option>
                        @foreach (\App\Enums\TransactionEnum::METHOD as $k => $m)
                            <option value="{{ $k }}">{{ $m }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 px-1">
                    <label for="">Fee*</label>
                    <input wire:model="fee" type="text" class="form-control form-control-sm">
                </div>
                <div class="col-md-3 px-1">
                    <label for="">Fee Type*</label>
                    <select wire:model="fee_type" class="form-control form-control-sm">
                        <option value="{{\App\Enums\TransactionEnum::METHOD_FEE_TYPE['PERCENTAGE']}}">Percentage</option>
                        <option value="{{\App\Enums\TransactionEnum::METHOD_FEE_TYPE['FIXED']}}">Fixed</option>
                    </select>
                </div>
                <div class="col-md-3 px-1">
                    <label for="">Status</label>
                    <select wire:model="status" class="form-control form-control-sm">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                <div class="col px-1 mt-1">
                    <label for="">AccountNo/Details*</label>
                    <textarea wire:model="remark" class="form-control form-control-sm"></textarea>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12">
                    @if($is_editing == true)
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
        <h6 class="mb-3">All Payment Methods</h6>
        <table class="table table-sm table-bordered table-striped">
        <thead class="">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Methods Name</th>
                <th scope="col">Key</th>
                <th scope="col">Fee</th>
                <th scope="col">FeeType</th>
                <th scope="col">AccountNo/Details</th>
                <th scope="col">Status</th>
                <th scope="col" style="width:5%">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($methods as $key => $method)
                <tr>
                    <th scope="row">{{ $key + 1 }}</th>
                    <td> {{ $method->title }} </td>
                    <td> {{ $method->key }} </td>
                    <td> {{ number_format($method->fee, 2) }} </td>
                    <td> {{ $method->fee_type }} </td>
                    <td> <textarea class="form-control form-control-sm">{{ $method->remark }}</textarea> </td>
                    <td> {{ $method->status }} </td>
                    <td>
                        <div class="btn-group">
                            <button class="btn btn-info btn-sm dropdown-toggle btn-action" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-cog"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <button class="dropdown-item" wire:click="edit({{ $method->id }})">Edit</button>
                                <button class="dropdown-item" wire:click="delete({{ $method->id }})">Delete</button>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
        </table>
    </div>
</div>
</div>
