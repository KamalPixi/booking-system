<div>
@include('includes.errors')
@include('includes.alert_failed_inner')
@include('includes.alert_success_inner')

<!-- unit create form -->
<div class="card mb-2">
    <div class="card-body">
        @if($is_editing)
            <h6 class="mb-2">Update User</h6>
        @else
            <h6 class="mb-2">Add New User</h6>
        @endif
        <form>
            <div class="row">
                <div class="col-md-3 px-1">
                    <label for="">Name*</label>
                    <input wire:model="name" type="text" class="form-control form-control-sm">
                </div>
                <div class="col-md-3 px-1">
                    <label for="">Mobile No</label>
                    <input wire:model="mobile_no" type="text" class="form-control form-control-sm">
                </div>
                <div class="col-md-3 px-1">
                    <label for="">Email*</label>
                    <input wire:model="email" type="text" class="form-control form-control-sm">
                </div>
                <div class="col-md-3 px-1">
                    <label for="">Password*</label>
                    <input wire:model="password" type="text" class="form-control form-control-sm">
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label for="">Assign Role*</label>
                    <select wire:model="role_id" class="form-control form-control-sm">
                        <option value="">-</option>
                        @foreach($roles as $role)
                            @if(!App\Helpers\RefHelper::belongsToAgent($role->name)) @continue @endif
                            <option value="{{ $role->id }}">{{ \App\Helpers\RefHelper::agentRoleSuffix($role->name) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12">
                    @if($is_editing)
                        <button wire:click="update" type="button" class="btn btn-primary btn-sm min-w-10">
                            <div wire:loading.remove>Update User</div>
                            <div wire:loading class="loader loader-for-btn">Loading...</div>
                        </button>
                    @else
                        <div>
                            <button wire:click="create" type="button" class="btn btn-primary btn-sm min-w-10">
                                <div wire:loading.remove wire:target="create">Add User</div>
                                <div wire:loading wire:target="create" class="loader loader-for-btn">Loading...</div>
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
        <h6 class="mb-3">All Users</h6>
        <table class="table table-sm table-bordered table-striped">
        <thead class="">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Mobile No.</th>
                <th scope="col">Role</th>
                <th scope="col" style="width:5%">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $key => $user)
                <tr>
                    <th scope="row">{{ $key + 1 }}</th>
                    <td> {{ $user->name }} </td>
                    <td> {{ $user->email }} </td>
                    <td> {{ $user->mobile_no }} </td>
                    <td>
                        @foreach ($user->getRoleNames() as $role_name)
                            <span class="badge badge-secondary text-small">
                                {{ \App\Helpers\RefHelper::agentRoleSuffix($role_name) }}
                            </span>
                        @endforeach
                    </td>
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-secondary btn-sm px-2 py-1 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-cog"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><button wire:click="edit({{ $user->id }})" class="dropdown-item" type="button">Edit</button></li>
                                <li><button wire:click="deactivate({{ $user->id }})" class="dropdown-item" type="button">Deactive</button></li>
                            </ul>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
        </table>
    </div>
</div>
</div>
