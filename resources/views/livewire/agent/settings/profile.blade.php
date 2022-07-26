<div>
    <div class="row mb-4">
        <div class="col text-center">
            @if(auth()->user()->photo)
                <div class="image">
                    <img src="{{auth()->user()->photo->file}}" width="60" height="60" class="rounded-circle" alt="">
                </div>
            @else
                <img src="https://via.placeholder.com/60" class="rounded-circle" alt="">
            @endif
            <h6 class="font-weight-bold">{{ auth()->user()->name }}</h6>
            <small>{{  \App\Helpers\RefHelper::agentRoleSuffix(auth()->user()->getRoleNames()->first()) }}</small>
        </div>
    </div>

    @if($edit)
        <div wire:key="user_edit_form" class="px-4">

            @include('includes.errors')
            @include('includes.alert_failed_inner')
            @include('includes.alert_success_inner')

            <form autocomplete="off">
                <div class="row mb-1">
                    <div class="col-6 px-1">
                        <label for="" class="mb-0 text-small">Name</label>
                        <input wire:model="name" class="form-control form-control-sm" type="text">
                    </div>
                    <div class="col-6 px-1">
                        <label for="" class="mb-0 text-small">Email</label>
                        <input wire:model="email" class="form-control form-control-sm" type="text" autocomplete="off">
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-6 px-1">
                        <label for="" class="mb-0 text-small">Mobile No.</label>
                        <input wire:model="mobile_no" class="form-control form-control-sm" placeholder="m" type="text" autocomplete="off">
                    </div>
                    <div class="col-6 px-1">
                        <label for="" class="mb-0 text-small">Profile Photo</label>
                        <input wire:model="photo" class="form-control form-control-sm" type="file">
                    </div>
                </div>
                

                <div class="row bg-light p-1">
                    <span class="text-small">Leave password fields empty, if not want to change.</span>
                </div>
                <div class="row mb-1">
                    <div class="col-6 px-1">
                        <label for="" class="mb-0 text-small">Password</label>
                        <input wire:model="password" class="form-control form-control-sm" type="password" autocomplete="off">
                    </div>
                    <div class="col-6 px-1">
                        <label for="" class="mb-0 text-small">Repeat Password</label>
                        <input wire:model="password_confirmation" class="form-control form-control-sm" type="password">
                    </div>
                </div>

            </form>

        </div>
    @else
        <div wire:key="user_view" class="px-4 pt-4 mb-4">
            <table class="table table-sm table-striped">
                <tr>
                    <th>Full Name</th>
                    <td>: {{ auth()->user()->name }}</td>
                </tr>
                <tr>
                    <th>Phone</th>
                    <td>: {{ auth()->user()->mobile_no }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>: {{ auth()->user()->email }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>: 
                        <span class="badge badge-success">
                            @if (auth()->user()->status)
                                Active
                            @endif
                        </span>
                    </td>
                </tr>
            </table>
        </div>
    @endif

    @if($edit)
        <div wire:key="button_update" class="px-3 mt-4">
            <button wire:click="update" class="btn btn-sm btn-primary min-w-10">
                <div wire:loading.remove wire:target="update">Update Profile</div>
                <div wire:loading wire:target="update" class="loader loader-for-btn">Loading...</div>
            </button>
            <button wire:click="unsetEdit" class="btn btn-sm btn-secondary min-w-5">
                <div wire:loading.remove wire:target="unsetEdit">Cancel</div>
                <div wire:loading wire:target="unsetEdit" class="loader loader-for-btn">Loading...</div>
            </button>
        </div>
    @else
    <div wire:key="button_edit" class="px-4">
        <button wire:click="setEdit" class="btn btn-sm btn-primary min-w-10">
            <div wire:loading.remove>Edit Profile</div>
            <div wire:loading class="loader loader-for-btn">Loading...</div>
        </button>
    </div>
    @endif
</div>