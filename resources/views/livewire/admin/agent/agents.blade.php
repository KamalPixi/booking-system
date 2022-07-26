<div>
<!-- title and menus -->
<div class="row">
    <div class="col-md-6">
        <h1 class="h5 mb-4 text-gray-800">Add Agent</h1>
    </div>
    <div class="col-md-6">
        <div class="d-flex justify-content-center justify-content-md-end mb-2 mb-md-0">
        </div>
    </div>
</div>

@include('includes.alert_failed_inner')
@include('includes.alert_success_inner')
@include('includes.alert_info_inner')
@include('includes.errors')

<div class="card @if($edit) border border-info @endif">
    <div class="card-body">
        <form wire:submit.prevent="@if($edit){{'updateAgent'}}@else{{'addAgent'}}@endif">
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="">Company</label>
                    <input wire:model="company" type="text" class="form-control form-control-sm">                    
                </div>
                <div class="col-md-4">
                    <label for="">Proprietor Full Name</label>
                    <input wire:model.lazy="full_name" type="text" class="form-control form-control-sm">
                </div>
                <div class="col-md-4">
                    <label for="">Phone No.</label>
                    <input wire:model.lazy="phone" type="text" class="form-control form-control-sm">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label for="">Address</label>
                    <textarea wire:model.lazy="address" rows="1" class="form-control form-control-sm"></textarea>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col">
                    <label for="">State</label>
                    <input wire:model="state" type="text" class="form-control form-control-sm">                    
                </div>
                <div class="col">
                    <label for="">City</label>
                    <input wire:model.lazy="city" type="text" class="form-control form-control-sm">
                </div>
                <div class="col">
                    <label for="">Postal Code</label>
                    <input wire:model.lazy="postcode" type="text" class="form-control form-control-sm">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col">
                    <label for="">Email*</label>
                    <input wire:model.lazy="email" type="text" class="form-control form-control-sm">
                </div>
                <div class="col">
                    <label for="">Password*</label>
                    <input wire:model.lazy="password" type="text" class="form-control form-control-sm">
                </div>
                <div class="col">
                    <label for="">Repeat Password*</label>
                    <input wire:model="password_confirmation" type="text" class="form-control form-control-sm">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label for="">Status</label>
                    <select wire:model="status" class="form-control form-control-sm">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>


            <!-- submit button -->

            @if($edit)
            <div class="row">
                <div class="col d-flex">
                    <div wire:loading wire:target="updateAgent">
                        <button type="button" class="btn btn-primary btn-sm d-flex px-5 justify-content-center">
                            <div class="loader"></div>
                        </button>
                    </div>
                    <div wire:loading.remove wire:target="updateAgent">
                        <button type="submit" class="btn btn-primary btn-sm px-4">Update Agent</button>
                    </div>
                    <div wire:target="cancelUpdate" class="ml-2">
                        <button type="button" wire:click="cancelUpdate" class="btn btn-secondary btn-sm px-4">Cancel</button>
                    </div>
                </div>
            </div>
            @else
            <div class="row">
                <div class="col">
                    <div>
                        <button type="submit" class="btn btn-primary btn-sm px-4">Add Agent</button>
                    </div>
                </div>
            </div>
            @endif

        </form>
    </div>
</div>

<hr class="my-4">

<!---agent list---->
<div class="row">
    <div class="col-md-6">
        <h1 class="h5 text-gray-800">All Agents</h1>
    </div>
    <div class="col-md-6">
        <div class="d-flex justify-content-center justify-content-md-end mb-2 mb-md-0">
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table id="products" class="table table-sm table-bordered table-striped text-small">
            <thead class="bg-primary text-white">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Company</th>
                    <th scope="col">Proprietor</th>
                    <th scope="col">Email</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Address</th>
                    <th scope="col">Status</th>
                    <th scope="col">Balance</th>
                    <th scope="col">Created</th>
                    <th scope="col" style="width:5%">Action</th>
                </tr>
            </thead>
            <tbody>

                @foreach($agents as $key => $agent)
                    <tr>
                        <th scope="row">{{ $agents->firstItem() + $key }}</th>
                        <td>{{ $agent->company }}</td>
                        <td>{{ $agent->full_name }}</td>
                        <td>{{ $agent->email }}</td>
                        <td>{{ $agent->phone }}</td>
                        <td>
<textarea rows="1" class="form-control form-control-sm">
{{ $agent->address }}
{{ $agent->state }}
{{ $agent->city }}
{{ $agent->postcode }}
</textarea>
                        </td>
                        <td>
                            @if($agent->status)
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-danger">Inactive</span>
                            @endif
                        </td>
                        <td>{{ number_format($agent->account->balance, 2) }}</td>
                        <td>{{ date('Y-m-d', strtotime($agent->created_at)) }}</td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-info btn-sm dropdown-toggle btn-action" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-cog"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <button 
                                        wire:click="setEdit({{ $agent->id }})"
                                        type="button" 
                                        class="dropdown-item" 
                                        data-toggle="modal" 
                                        data-target="#exampleModalCenter"
                                    >
                                        Edit
                                    </button>
                                    <button wire:click="delete({{ $agent->id }})" class="dropdown-item" type="button">Delete</button>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>

        <div class="pagination-container">
            {{ $agents->links() }}
        </div>
    </div>
</div>
<div 
    wire:loading 
    wire:target="updateAgent,addAgent,cancelUpdate,updateAgent"
    >
    <x-loading />
</div>
</div>
