<div x-data="searchForm">

    <div class="row mb-4">
        <div class="col-lg-9 mx-auto">
            <form>
                <div class="form-row">
                    <div class="col form-group">
                        <input wire:model="airline" type="text" class="form-control" required placeholder="Airline">
                        <span class="icon-inside"><i class="fas fa-plane"></i></span>
                    </div>
                    <div class="col form-group">
                        <input wire:model="from" type="text" class="form-control" required placeholder="From">
                        <span class="icon-inside"><i class="fas fa-map-marker-alt"></i></span>
                    </div>
                    <div class="col form-group">
                        <input wire:model="to" type="text" class="form-control" required placeholder="To">
                        <span class="icon-inside"><i class="fas fa-map-marker-alt"></i></span>
                    </div>
                    <div class="col form-group">
                        <x-date-picker x-model="depart_date" id="flightDepart" placeholder="Depart Date" class="form-control"/>
                        <span class="icon-inside"><i class="far fa-calendar-alt"></i></span>
                    </div>
                    <div class="col form-group">
                        <button wire:click="clearFilter" type="button" class="btn btn-secondary btn-sm">Clear</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Flights -->
    <div>

        @foreach ($flights as $flight)
        <div wire:key="single-flight-{{$loop->index}}" class="row rounded border bg-white m-3">
            <div class="row mx-0 px-0 line-height-1 justify-content-xl-center">
                <div class="col-8 px-0 mx-0 d-flex align-items-center">
                    <div wire:key="leg-key-{{$loop->index}}" class="row px-0 mx-0 w-100">
                        
                        <div class="col-3 d-flex align-items-center">
                            <img class="mr-2 rounded border"
                                src="https://tbbd-flight.s3.ap-southeast-1.amazonaws.com/airlines-logo/{{$flight->airline}}.png" alt="airline"
                                width="40">
                            <div class="d-flex align-items-center">
                                <a href="{{ route('b2b.flight.search.details', ['pre_purchased_id' => $flight->id]) }}" class="text-2 text-dark mr-2">
                                    {{ $flight->airlineName()->name ?? $flight->airline }}
                                </a>
                            </div>
                        </div>


                        <div class="col-3">
                            <div class="text-center d-inline-block air-route">
                                <small class="w-100 badge bg-offwhite text-gray px-4">
                                    <span class="font-weight-normal">{{$flight->from}}</span><br>
                                    <span class="text-2">
                                        {{\App\Helpers\UtilityHelper::cityCodeToName($flight->from)}}
                                    </span>
                                </small>
                                <div class="w-100 text-small font-weight-bold bg-light mt-1 text-gray rounded">
                                    {{ date("D, d M, y", strtotime($flight->depart_date)) }}
                                </div>
                            </div>
                        </div>

                        <div class="col">
                            <div class="h-100 d-flex flex-column justify-content-center">
                                @if (empty($flight->transit_location))
                                    <div class="d-inline-block text-center">
                                        <img src="{{ asset('/assets/images/stop-0.png') }}" alt="" width="80"><br>
                                        <small>Direct</small>
                                    </div>
                                @else
                                    <div class="d-inline-block text-center">
                                        {{ $flight->transit_time }} <br>
                                        @if ($flight->countLocations() == 1)
                                            <img src="{{ asset('/assets/images/stop-1.png') }}" alt="" width="80"><br>
                                            <small class="">
                                                <span class="text-danger">{{$flight->countLocations()}}stop</span> 
                                                {{ $flight->transit_location }}
                                            </small>
                                        @endif
                                        @if ($flight->countLocations() == 2)
                                            <img src="{{ asset('/assets/images/stop-2.png') }}" alt="" width="80"><br>
                                            <small class="">
                                                <span class="text-danger">{{$flight->countLocations()}}stops</span> 
                                                {{ $flight->transit_location }}
                                            </small>
                                        @endif
                                        @if ($flight->countLocations() == 3)
                                            <img src="{{ asset('/assets/images/stop-3.png') }}" alt="" width="80"><br>
                                            <small class="">
                                                <span class="text-danger">{{$flight->countLocations()}}stops</span> 
                                                {{ $flight->transit_location }}
                                            </small>
                                        @endif
                                        @if ($flight->countLocations() == 4)
                                            <img src="{{ asset('/assets/images/stop-4.png') }}" alt="" width="80"><br>
                                            <small class="">
                                                <span class="text-danger">{{$flight->countLocations()}}stops</span> 
                                                {{ $flight->transit_location }}
                                            </small>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>


                        <div class="col">
                            <div class="text-center d-inline-block air-route">
                                <small class="w-100 badge bg-offwhite text-gray px-4">
                                    <span class="font-weight-normal">{{$flight->to}}</span><br>
                                    <span class="text-2">
                                        {{\App\Helpers\UtilityHelper::cityCodeToName($flight->to)}}
                                    </span>
                                </small>
                                <div class="w-100 text-small font-weight-bold bg-light mt-1 text-gray rounded">
                                    {{ date("D, d M, y", strtotime($flight->arrival_date)) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="d-flex h-100 line-height-1-2 flex-column justify-content-center text-small">
                        <strong>Economy</strong>
                        <strong class="text-danger">
                            Non Refundable
                        </strong>
                        <strong class="text-uppercase"><i class="fa-solid fa-suitcase"></i>
                            {{ $flight->baggage }}
                        </strong>
                        <strong class="">
                            <img src="{{ asset('/assets/images/seat.png') }}" width="15" alt=""> {{ $flight->count }} Ticekts
                        </strong>
                    </div>
                </div>
                <div class="col col-lg-2">
                    <div class="text-center">
                        <h6 class="mb-1 pl-1 font-weight-bold text-center">
                            BDT
                            {{ number_format($flight->fare, 2) }}
                        </h6>

                        <a href="{{ route('b2b.flight.search.details', ['pre_purchased_id' => $flight->id]) }}" class="btn btn-sm btn-primary py-1">
                            <span>BOOK FLIGHT</span>
                        </a>
                    </div>
                </div>
                
            </div>
        </div>
        @endforeach


        @if (count($flights) < 1)
        <div class="card">
            <div class="body py-4">
                <h6 class="text-danger text-center">No Flight Found</h6>
            </div>
        </div>
        @endif


    </div>

    <div class="text-center w-100">
        {{ $flights->links() }}
    </div>


    <div wire:loading>
        <x-loading />
    </div>

    <script>
        let searchForm = () => {
            return {
                'depart_date' : @entangle('depart_date')
            }
        }
    </script>
</div>