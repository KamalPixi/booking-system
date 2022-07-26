<div>

<h1 class="h3 mb-4 text-gray-800">Profile</h1>

<!-- unit create form -->
<div class="card mb-2">
    <div class="card-body">

        @include('includes.alert_failed_inner')
        @include('includes.alert_success_inner')
        @include('includes.errors')

        <form wire:submit.prevent="update">
            <div class="row">
                <div class="col">
                    <label for="">Name*</label>
                    <input wire:model="name" type="text" class="form-control form-control-sm">
                </div>
                <div class="col">
                    <label for="">Mobile No</label>
                    <input wire:model="mobile_no" type="text" class="form-control form-control-sm">
                </div>
                <div class="col">
                    <label for="">Email</label>
                    <input wire:model="email" type="text" class="form-control form-control-sm">
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12 col-md-2">
                    <div>
                        <button type="submit" class="btn btn-primary btn-sm w-100">Update</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- password change -->
<div class="card mb-2">
    <div class="card-body">
        <h6>Change Password</h6>
        <form wire:submit.prevent="changePassword">
            <div class="row">
                <div class="col">
                    <label for="">Current Password*</label>
                    <input wire:model="current_password" type="password" class="form-control form-control-sm">
                </div>
                <div class="col">
                    <label for="">New Password</label>
                    <input wire:model="new_password" type="password" class="form-control form-control-sm">
                </div>
                <div class="col">
                    <label for="">Confirm Password</label>
                    <input wire:model="confirm_password" type="password" class="form-control form-control-sm">
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12 col-md-2">
                    <div>
                        <button type="submit" class="btn btn-primary btn-sm w-100">Change Password</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div wire:loading wire:target="update,changePassword">
    <x-loading />
</div>

</div>
