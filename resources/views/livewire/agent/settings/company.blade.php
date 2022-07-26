<div>
    <div class="row m-2">
        <div class="col text-center">
            @if(isset(auth()->user()->agent->logo()->file))
                <img src="{{ auth()->user()->agent->logo()->file }}" class="rounded" alt="" width="100">
            @else
                <img src="https://via.placeholder.com/60" class="rounded" alt="">
            @endif
            <h6 class="font-weight-bold">{{ auth()->user()->agent->company }}</h6>
            <small>{{ auth()->user()->agent->state }}, {{ auth()->user()->agent->postcode }}</small>
        </div>
    </div>



    @if($edit)
        <div wire:key="company_edit_form" class="px-4">

            @include('includes.errors')
            @include('includes.alert_failed_inner')
            @include('includes.alert_success_inner')

            <form autocomplete="off">
                <div class="row mb-1">
                    <div class="col-6 px-1">
                        <label for="" class="mb-0 text-small">Company Name*</label>
                        <input wire:model="company" required class="form-control form-control-sm" type="text">
                    </div>
                    <div class="col-6 px-1">
                        <label for="" class="mb-0 text-small">Proprietor Name*</label>
                        <input wire:model="full_name" required class="form-control form-control-sm" type="text" autocomplete="off">
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-6 px-1">
                        <label for="" class="mb-0 text-small">Company Phone No.*</label>
                        <input wire:model="phone" required class="form-control form-control-sm" type="text" autocomplete="off">
                    </div>
                    <div class="col-6 px-1">
                        <label for="" class="mb-0 text-small">Company Email*</label>
                        <input wire:model="email" required class="form-control form-control-sm" type="email">
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col px-1">
                        <label for="" class="mb-0 text-small">Address*</label>
                        <input wire:model="address" required class="form-control form-control-sm" type="text">
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col px-1">
                        <label for="" class="mb-0 text-small">Division*</label>
                        <select wire:model="division" required class="form-control form-control-sm">
                            <option value="">Choose</option>
                            @foreach(\App\Models\BangladeshDivision::all() as $d)
                                <option value="{{$d->name}}">{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col px-1">
                        <label class="mb-0 text-small">District*</label>
                        <select wire:model="city" required class="form-control form-control-sm">
                            <option value="">Choose</option>
                            @foreach(\App\Models\BangladeshDistrict::all() as $d)
                                <option value="{{$d->name}}">{{ $d->name }}</option>
                            @endforeach
                        </select>                    
                    </div>
                    <div class="col px-1">
                        <label class="mb-0 text-small">Postal Code*</label>
                        <input wire:model="postcode" required type="text" class="form-control form-control-sm" />
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col px-1">
                        <label for="" class="mb-0 text-small">Logo</label>
                        <input wire:model="logo" required class="form-control form-control-sm" type="file">
                    </div>
                </div>

            </form>

        </div>
    @else
        <div class="px-4 pt-4 mb-4">
            <table class="table table-sm table-striped">
                <tr>
                    <th>Company</th>
                    <td>: {{ auth()->user()->agent->company }}</td>
                </tr>
                <tr>
                    <th>Proprietor Name</th>
                    <td>: {{ auth()->user()->agent->full_name }}</td>
                </tr>
                <tr>
                    <th>Company Email</th>
                    <td>: {{ auth()->user()->agent->email }}</td>
                </tr>
                <tr>
                    <th>Company Phone</th>
                    <td>: {{ auth()->user()->agent->phone }}</td>
                </tr>
                <tr>
                    <th>Total Users</th>
                    <td>: {{ auth()->user()->agent->users->count() }} </td>
                </tr>
                <tr>
                    <th>Created At</th>
                    <td>: 
                        {{ auth()->user()->agent->created_at }} 
                        <span class="badge badge-secondary">
                            {{ auth()->user()->agent->created_at->diffForHumans() }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>: 
                        @if(auth()->user()->agent->status)
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-success">Active</span>
                        @endif
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
            <div wire:loading.remove>Edit Company</div>
            <div wire:loading class="loader loader-for-btn">Loading...</div>
        </button>
    </div>
    @endif
</div>
