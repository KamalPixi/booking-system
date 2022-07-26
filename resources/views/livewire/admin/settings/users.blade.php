<div>
@include('includes.alert_failed_inner')
@include('includes.alert_success_inner')
@include('includes.errors')
<!-- heading -->
<h1 class="h3 mb-4 text-gray-800">Users</h1>

<!-- unit create form -->
<div class="card mb-2">
    <div class="card-body">
        @if($is_editing)
            <h6>Update User</h6>
        @else
            <h6>Add User</h6>
        @endif
        <form>
            <div class="row">
                <div class="col-md-3">
                    <label for="">Name*</label>
                    <input wire:model="name" type="text" class="form-control form-control-sm">
                </div>
                <div class="col-md-3">
                    <label for="">Mobile No</label>
                    <input wire:model="mobile_no" type="text" class="form-control form-control-sm">
                </div>
                <div class="col-md-3">
                    <label for="">Email*</label>
                    <input wire:model="email" type="text" class="form-control form-control-sm">
                </div>
                <div class="col-md-3">
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
                            {{-- only show roles created by admin --}}
                            @if(substr($role->name, 0, 5) == \App\Helpers\RefHelper::agentRolePrefixOnly())
                                @continue 
                            @endif

                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12 col-md-2">
                    @if($is_editing)
                    <div>
                        <button wire:click="update" type="button" class="btn btn-primary btn-sm w-100">Update</button>
                    </div>
                    @else
                        <div>
                            <button wire:click="create" type="button" class="btn btn-primary btn-sm w-100">Add</button>
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
        <h6>Users</h6>
        <table class="table table-sm table-bordered table-striped">
        <thead class="bg-primary text-white">
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
            @php $u_key = 0; @endphp
            @foreach($users as $key => $user)
                {{-- only show roles created by admin --}}
                @if(!in_array($user->type, [
                    App\Enums\UserEnum::TYPE['ADMIN'],
                    App\Enums\UserEnum::TYPE['ADMIN_USER']
                ]))
                    @continue 
                @endif
                <tr>
                    <th scope="row">{{ $u_key + 1 }}</th>
                    <td> {{ $user->name }} </td>
                    <td> {{ $user->email }} </td>
                    <td> {{ $user->mobile_no }} </td>
                    <td> {{ $user->getRoleNames() }}</td>
                    <td>
                        <div class="btn-group">
                            <button class="btn btn-info btn-sm dropdown-toggle btn-action" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-cog"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <button class="dropdown-item" wire:click="edit({{ $user->id }})">Edit</button>
                                <button class="dropdown-item" wire:click="deactivate({{ $user->id }})">Deactivate</button>
                            </div>
                        </div>
                    </td>
                </tr>
                
                @php $u_key++; @endphp
            @endforeach
        </tbody>
        </table>
    </div>
</div>

<div wire:loading wire:target="update,create">
    <x-loading />
</div>
</div>
