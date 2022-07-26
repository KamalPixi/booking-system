<div>
<hr>
@include('includes.alert_failed_inner')
@include('includes.alert_success_inner')
@include('includes.errors')


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
                <div class="col-12 col-md-2">
                    @if($is_editing)
                    <div>
                        <button wire:click="update" type="button" class="btn btn-primary btn-sm w-100">Update Role</button>
                    </div>
                    @else
                        <div>
                            <button wire:click="create" type="button" class="btn btn-primary btn-sm w-100">Add Role</button>
                        </div>
                    @endif
                </div>
            </div>

        </form>
    </div>
</div>


@include('includes.alert_failed_inner')
@include('includes.alert_success_inner')

<!-- show all units -->
<div class="card">
    <div class="card-body">
        <h6>Roles</h6>
        <table class="table table-sm table-bordered table-striped">
        <thead class="bg-primary text-white">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Permissions</th>
                <th scope="col" style="width:5%">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($roles as $role)
                {{-- only show roles created by admin --}}
                @if(substr($role->name, 0, 5) == \App\Helpers\RefHelper::agentRolePrefixOnly())
                    @continue 
                @endif

                <tr>
                    <th scope="row">1</th>
                    <td>{{ $role->name }}</td>
                    <td class="line-height-1">
<Textarea class="form-control form-control-sm" readonly>
@foreach($role->permissions as $permission)
{{ $permission->name }} 
@endforeach
</Textarea>
                    </td>
                    <td>
                        <div class="btn-group">
                            <button class="btn btn-info btn-sm dropdown-toggle btn-action" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-cog"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <button class="dropdown-item" wire:click="revokePermissions({{ $role->id }})">RevokePermissions</button>
                                <button class="dropdown-item" wire:click="edit({{ $role->id }})">Edit</button>
                                <button class="dropdown-item" wire:click="delete({{ $role->id }})">Delete</button>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
        </table>
    </div>
</div>

<div wire:loading wire:target="update,create">
    <x-loading />
</div>
</div>
