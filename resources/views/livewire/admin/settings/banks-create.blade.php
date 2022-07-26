<div>
@include('includes.errors')
@include('includes.alert_failed_inner')
@include('includes.alert_success_inner')

<!-- unit create form -->
<div class="card mb-2">
    <div class="card-body">
        @if($editing_id)
            <h6 class="mb-2">Update Bank</h6>
        @else
            <h6 class="mb-2">Add New Bank</h6>
        @endif
        <form>
            <div class="row">
                <div class="col-md-3 px-1">
                    <label for="">Bank Name*</label>
                    <input wire:model="bank" type="text" class="form-control form-control-sm">
                </div>
                <div class="col-md-3 px-1">
                    <label for="">Account Name*</label>
                    <input wire:model="account_name" type="text" class="form-control form-control-sm">
                </div>
                <div class="col-md-3 px-1">
                    <label for="">Account No*</label>
                    <input wire:model="account_no" type="text" class="form-control form-control-sm">
                </div>
                <div class="col-md-3 px-1">
                    <label for="">Branch*</label>
                    <input wire:model="branch" type="text" class="form-control form-control-sm">
                </div>
                <div class="col-md-3 px-1">
                    <label for="">Logo</label>
                    <input wire:model="logo" type="file" class="form-control form-control-sm">
                </div>
                <div class="col-md-3 px-1">
                    <label for="">Status*</label>
                    <select wire:model="status" class="form-control form-control-sm">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12">
                    @if($editing_id)
                        <button wire:click="update" type="button" class="btn btn-primary btn-sm min-w-10">
                            <div>Update Bank</div>
                        </button>
                    @else
                        <div>
                            <button wire:click="create" type="button" class="btn btn-primary btn-sm min-w-10">
                                <div>Add Bank</div>
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
                <th scope="col">Logo</th>
                <th scope="col">Bank Name</th>
                <th scope="col">Account No</th>
                <th scope="col">Account Name</th>
                <th scope="col">Branch</th>
                <th scope="col">Status</th>
                <th scope="col" style="width:5%">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($banks as $key => $bank)
                <tr wire:key="{{$key}}">
                    <th scope="row">{{ $key + 1 }}</th>
                    <td>
                        @if(isset($bank->logo))
                            <img width="50" height="35" src="{{$bank->logo->file}}" alt="{{ $bank->bank }}">
                        @endif
                    </td>
                    <td> {{ $bank->bank }} </td>
                    <td> {{ $bank->account_no }} </td>
                    <td> {{ $bank->account_name }} </td>
                    <td> {{ $bank->branch }} </td>
                    <td> {{ $bank->status }} </td>

                    <td>
                        <div class="btn-group">
                            <button class="btn btn-info btn-sm dropdown-toggle btn-action" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-cog"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <button class="dropdown-item" wire:click="edit({{ $bank->id }})">Edit</button>
                                <button class="dropdown-item" wire:click="delete({{ $bank->id }})">Delete</button>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
        </table>
    </div>
</div>

<div wire:loading wire:target="edit,update,create">
    <x-loading />
</div>

</div>
