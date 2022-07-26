<div>
<hr>

@include('includes.alert_failed_inner')
@include('includes.alert_success_inner')
@include('includes.errors')


<!-- unit create form -->
<div class="card mb-2 my-4">
    <div class="card-body">
        <h6>Permissions</h6>
        <form>
            <div class="row mb-2">
                <div class="col">
                    <label for="">Role*</label>
                    <select class="form-control form-control-sm" wire:model="role_id" required>
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

            <div class="row">
                <div class="col">
                    <label>Selected Permissions <button type="button" wire:click="clear" class="btn btn-link">Clear</button></label>
                    <select class="form-control form-control-sm" readonly multiple>
                        @foreach($permissions_ids_selected as $id)
                            <option>{{ \Spatie\Permission\Models\Permission::find($id)->name }}</option>
                        @endforeach
                    </select> 
                </div>
            </div>

            <div class="row pb-2 pt-4">
                <div class="col">
                    <input wire:model.delay="search" class="form-control form-control-sm" type="text" placeholder="Search permissions">
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <label>Permissions</label>
                    <select class="form-control form-control-sm permission-select" wire:model="permissions_ids" required multiple>
                        @foreach($permissions as $permission)
                            {{-- only show permisions created by admin --}}
                            @if(substr($permission->name, 0, 5) == \App\Helpers\RefHelper::agentPermissionPrefixOnly())
                                @continue 
                            @endif

                            <option value="{{ $permission->id }}">{{ $permission->name }}</option>
                        @endforeach
                    </select> 
                </div>
            </div>

            <div class="row mt-3 mb-3">
                <div class="col-12 col-md-2">
                    @if($is_editing)
                    <div>
                        <button wire:click="update" type="button" class="btn btn-primary btn-sm w-100">Update</button>
                    </div>
                    @else
                        <div>
                            <button wire:click="assign" type="button" class="btn btn-primary btn-sm w-100">Assign Permissions</button>
                        </div>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

<div wire:loading wire:target="update,assign">
    <x-loading />
</div>

</div>
