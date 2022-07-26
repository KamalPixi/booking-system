<div>
<hr>
@include('includes.alert_failed_inner')
@include('includes.alert_success_inner')

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
                            @if(!App\Helpers\RefHelper::belongsToAgent($role->name)) @continue @endif
                            <option value="{{ $role->id }}">{{ \App\Helpers\RefHelper::agentRoleSuffix($role->name) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <label>Selected Permissions <button type="button" wire:click="clear" class="btn btn-link btn-sm p-0">Clear</button></label>
                    <select class="form-control form-control-sm" readonly multiple>
                        @foreach($permissions_ids_selected as $id)
                            <option>{{ \App\Helpers\RefHelper::agentRoleSuffix(\Spatie\Permission\Models\Permission::find($id)->name) }}</option>
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
                            <option value="{{ $permission->id }}">

                                {{ \App\Helpers\RefHelper::agentRoleSuffix($permission->name) }}
                            </option>
                        @endforeach
                    </select> 
                </div>
            </div>

            <div class="row mt-3 mb-3">
                <div class="col-12">
                    @if($is_editing)
                        <button wire:click="update" type="button" class="btn btn-primary btn-sm min-w-10">
                            <div wire:loading.remove>Update</div>
                            <div wire:loading class="loader loader-for-btn">Loading...</div>
                        </button>
                    @else
                        <button wire:click="assign" type="button" class="btn btn-primary btn-sm min-w-10">
                            <div wire:loading.remove>Assign Permissions</div>
                            <div wire:loading class="loader loader-for-btn">Loading...</div>
                        </button>
                    @endif
                </div>
            </div>

            @include('includes.errors')
            @include('includes.alert_failed_inner')
            @include('includes.alert_success_inner')
        </form>
    </div>
</div>

</div>
