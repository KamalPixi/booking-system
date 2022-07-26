<div>
<!-- title and menus -->
<div class="row">
    <div class="col-md-6">
        <h1 class="h5 mb-4 text-gray-800">Agent Registration requests</h1>
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

<div class="card">
    <div class="card-body">
        <table id="products" class="table table-sm table-bordered table-striped">
            <thead class="bg-primary text-white">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Company</th>
                    <th scope="col">Proprietor</th>
                    <th scope="col">Email</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Full Address</th>
                    <th scope="col">Request Date</th>
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
                        <td class="line-height-1">
                            <span class="text-small">{{ $agent->address }} </span> <br>
                            <span class="text-small">{{ $agent->state }} </span> <br>
                            <span class="text-small">{{ $agent->city }} </span> <br>
                            <span class="text-small">{{ $agent->postcode }} </span>
                        </td>
                        <td>
                            {{ date('Y-m-d', strtotime($agent->created_at)) }}
                            <span class="badge badge-secondary">{{ $agent->created_at->diffForHumans() }}</span>
                        </td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-info btn-sm dropdown-toggle btn-action" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-cog"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <button wire:click="create({{ $agent->id }})" class="dropdown-item" type="button">Create Agent Account</button>
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
    wire:target="delete,create"
    >
    <x-loading />
</div>
</div>
