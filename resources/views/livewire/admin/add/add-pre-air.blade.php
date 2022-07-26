<div>
<!-- title and menus -->
<div class="row">
    <div class="col-md-6">
        <h1 class="h5 mb-4 text-gray-800">Special Tickets</h1>
    </div>
    <div class="col-md-6">
        <div class="d-flex justify-content-center justify-content-md-end mb-2 mb-md-0">
            <button wire:click="toggleForm" class="btn btn-sm btn-primary">Add Special Ticket</button>
        </div>
    </div>
</div>

@include('includes.alert_failed_inner')
@include('includes.alert_success_inner')
@include('includes.alert_info_inner')
@include('includes.errors')

@if ($showForm)
<div class="card @if($edit) border border-info @endif">
    <div class="card-body">
        <form wire:submit.prevent="@if($edit){{'update'}}@else{{'add'}}@endif">
            <div class="row mb-3">
                <div class="col">
                    <label for="">Airline Code*</label>
                    <input wire:model="airline" type="text" class="form-control form-control-sm">                    
                </div>
                <div class="col position-relative">
                    <label for="">From*</label>
                    <input wire:model="from" type="text" class="form-control form-control-sm">
                </div>
                <div class="col">
                    <label for="">To*</label>
                    <input wire:model="to" type="text" class="form-control form-control-sm">
                </div>
                <div class="col">
                    <label for="">Fare*</label>
                    <input wire:model="fare" type="text" class="form-control form-control-sm">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col">
                    <label for="">Depart Date*</label>
                    <input wire:model="depart_date" type="date" class="form-control form-control-sm">                    
                </div>
                <div class="col">
                    <label for="">Arrival Date</label>
                    <input wire:model="arrival_date" type="date" class="form-control form-control-sm">
                </div>
                <div class="col position-relative">
                    <label for="">Baggage</label>
                    <input wire:model="baggage" wire:click="showBaggage" onfocusout="Livewire.emit('hideBaggage') type="text" placeholder="Ex: 30KG" class="form-control form-control-sm">
                    
                    @if(count($baggages) > 0)
                    <div wire:key="transit-hour" class="border shadow-sm position-absolute bg-white w-100 p-2 z-10">
                        <ul class="line-height-1 list-unstyled">
                            @foreach($baggages as $b)
                            <li wire:key="transit-hour-{{$loop->index}}" wire:click="setBaggage('{{$b}}')" type="button" class="border-bottom mb-1 pb-1">
                                <h6 class="text-2 text-hover">{{ $b }}</h6>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
            </div>

            <div class="row mb-3">
                <div class="col">
                    <label for="">Transit Location</label>
                    <input wire:model="transit_location" type="text" placeholder="Ex: SIN,KUL" class="form-control form-control-sm">
                </div>



                <div class="col-1 position-relative px-0">
                    <label for="">Hour</label>
                    <input wire:model="transit_hour" wire:click="showHours" onfocusout="Livewire.emit('hideHours')" type="text" placeholder="Ex: 03h" class="form-control form-control-sm">
                   
                   @if(count($transit_hours) > 0)
                    <div wire:key="transit-hour" class="border shadow-sm position-absolute bg-white w-100 p-2 z-10">
                        <ul class="line-height-1 list-unstyled">
                            @foreach($transit_hours as $h)
                            <li wire:key="transit-hour-{{$loop->index}}" wire:click="setHour('{{$h}}')" type="button" class="border-bottom">
                                <h6 class="text-2 text-hover mb-0 pb-0">{{ $h }}</h6>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
                <div class="col-1 position-relative px-0">
                    <label for="">Minute</label>
                    <input wire:model="transit_minute" wire:click="showMinutes" onfocusout="Livewire.emit('hideMinutes')" type="text" placeholder="Ex: 03m" class="form-control form-control-sm">
                   
                   @if(count($transit_minutes) > 0)
                    <div wire:key="transit-minute" class="border shadow-sm position-absolute bg-white w-100 p-2 z-10">
                        <ul class="line-height-1 list-unstyled">
                            @foreach($transit_minutes as $h)
                            <li wire:key="transit-minute-{{$loop->index}}" wire:click="setMinute('{{$h}}')" type="button" class="border-bottom">
                                <h6 class="text-2 text-hover mb-0 pb-0">{{ $h }}</h6>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>

                <div class="col position-relative">
                    <label for="">Transit Hour</label>
                    <input wire:model="transit_time" type="text" placeholder="Ex: 03h 50m" class="form-control form-control-sm disabled">
                </div>
                <div class="col">
                    <label for="">Tickets Count*</label>
                    <input wire:model="count" type="number" placeholder="Ex: 10" class="form-control form-control-sm">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-8">
                    <label for="">Reference remark</label>
                    <textarea wire:model="reference_remark" rows="1" class="form-control form-control-sm"></textarea>
                </div>
                <div class="col">
                    <label for="">Manage by</label>
                    <select wire:model="manage_by" class="form-control form-control-sm">
                        <option value="">Choose</option>
                        @foreach (\App\Models\User::where('type', \App\Enums\UserEnum::TYPE['ADMIN_USER'])->get() as $u)
                            <option value="{{$u->id}}">{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- submit button -->
            @if($edit)
            <div class="row">
                <div class="col d-flex">
                    <div wire:loading wire:target="updateAgent">
                        <button type="button" class="btn btn-primary btn-sm d-flex px-5 justify-content-center">
                            <div class="loader"></div>
                        </button>
                    </div>
                    <div wire:loading.remove wire:target="updateAgent">
                        <button type="submit" class="btn btn-primary btn-sm px-4">Update</button>
                    </div>
                    <div wire:target="toggleForm" class="ml-2">
                        <button type="button" wire:click="toggleForm" class="btn btn-secondary btn-sm px-4">Close</button>
                    </div>
                </div>
            </div>
            @else
            <div class="row">
                <div class="col">
                    <div>
                        <button type="submit" class="btn btn-primary btn-sm px-4">Add</button>
                        <button wire:click="toggleForm" type="button" class="btn btn-secondary btn-sm px-4">Close</button>
                    </div>
                </div>
            </div>
            @endif

        </form>
    </div>
</div>
@endif


<hr class="my-4">

<!---agent list---->
<div class="row">
    <div class="col-md-6">
        <h1 class="h5 text-gray-800">All Agents</h1>
    </div>
    <div class="col-md-6">
        <div class="d-flex justify-content-center justify-content-md-end mb-2 mb-md-0">
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table id="products" class="table table-sm table-bordered table-striped">
            <thead class="bg-primary text-white">
                <tr>
                
                    <th scope="col">#</th>
                    <th scope="col">airline</th>
                    <th scope="col">route</th>
                    <th scope="col">fare</th>
                    <th scope="col">depart_date</th>
                    <th scope="col">arrival_date</th>
                    <th scope="col">baggage</th>
                    <th scope="col">transitLocation</th>
                    <th scope="col">transitTime</th>
                    <th scope="col">referenceRemark</th>
                    <th scope="col">Ticekt Left</th>
                    <th scope="col">createdBy</th>
                    <th scope="col" style="width:5%">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($flights as $flight)
                    <tr>
                        <td>{{ $flights->firstItem() + $loop->index }}</td>
                        <td>{{ $flight->airline }}</td>
                        <td class="font-weight-bold">{{ $flight->from }} -> {{ $flight->to }}</td>
                        <td class="font-weight-bold">{{ $flight->fare }}</td>
                        <td>{{ $flight->depart_date }}</td>
                        <td>{{ $flight->arrival_date }}</td>
                        <td>{{ $flight->baggage }}</td>
                        <td>{{ $flight->transit_location }}</td>
                        <td>{{ $flight->transit_time }}</td>
                        <td>
                            <textarea readonly class="form-control form-control-sm" rows="1">{{ $flight->reference_remark }}</textarea>
                        </td>
                        <td class="text-center">{{ $flight->count }}</td>
                        <td>{{ $flight->user->name }}</td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-info btn-sm dropdown-toggle btn-action" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-cog"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <button 
                                        wire:click="setEdit({{$flight->id}})"
                                        type="button" 
                                        class="dropdown-item" 
                                        data-toggle="modal" 
                                        data-target="#exampleModalCenter"
                                    >
                                        Edit
                                    </button>
                                    <button wire:click="delete({{$flight->id}})" class="dropdown-item" type="button">Delete</button>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="pagination-container">
            {{ $flights->links() }}
        </div>
    </div>
</div>
<div 
    wire:loading 
    wire:target="update,edit,add,delete,toggleForm"
    >
    <x-loading />
</div>
</div>
