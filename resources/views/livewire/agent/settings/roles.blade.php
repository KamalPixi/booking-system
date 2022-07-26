<div>
<hr>
@include('includes.errors')
@include('includes.alert_failed_inner')
@include('includes.alert_success_inner')

<!-- unit create form -->
<div class="card mb-2 mt-4">
    <div class="card-body">
        @if($is_editing)
            <h6>Update Role</h6>
        @else
            <h6>Add Role</h6>
        @endif
        <form>
            <div class="row">
                <div class="col">
                    <label for="">Name*</label>
                    <input wire:model="name" type="text" class="form-control form-control-sm" required>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    @if($is_editing)
                    <button wire:click="update" type="button" class="btn btn-primary btn-sm min-w-10">
                        <div wire:loading.remove wire:target="update">Update Role</div>
                        <div wire:loading wire:target="update" class="loader loader-for-btn">Loading...</div>
                    </button>
                    @else
                        <button wire:click="create" type="button" class="btn btn-primary btn-sm min-w-10">
                            <div wire:loading.remove wire:target="create">Add Role</div>
                            <div wire:loading wire:target="create" class="loader loader-for-btn">Loading...</div>
                        </button>
                    @endif
                </div>
            </div>

        </form>
    </div>
</div>

<!-- show all units -->
<div class="card">
    <div class="card-body">
        <h6>Roles</h6>
        <table class="table table-sm table-bordered table-striped">
        <thead class="">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Permissions</th>
                <th scope="col" style="width:5%">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($roles as $role)
                @if(!App\Helpers\RefHelper::belongsToAgent($role->name)) @continue @endif

                <tr>
                    <th scope="row">1</th>
                    <td>{{ \App\Helpers\RefHelper::agentRoleSuffix($role->name) }}</td>
                    <td class="line-height-1">
<Textarea class="form-control form-control-sm" readonly>
@foreach($role->permissions as $permission)
{{ \App\Helpers\RefHelper::agentRoleSuffix($permission->name) }}
@endforeach
</Textarea>
                    </td>
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-secondary btn-sm px-2 py-1 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-cog"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><button wire:click="revokePermissions({{ $role->id }})" class="dropdown-item" type="button">RevokePermissions</button></li>
                                <li><button wire:click="edit({{ $role->id }})" class="dropdown-item" type="button">Edit</button></li>
                                <li><button wire:click="delete({{ $role->id }})" class="dropdown-item" type="button">Delete</button></li>
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
