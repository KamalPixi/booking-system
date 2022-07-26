<div>
    @if(!empty($booking_id))
    <div wire:key="booking-success" class="row m-3 mb-0">
        <div class="alert alert-success mb-0 line-height-1">
            <p class="m-0 text-small font-weight-bold">Booking successful</p>
            <a class="text-small" href="{{ route('b2b.flight.show', $this->booking_id) }}">View Booking</a>
        </div>
    </div>
    @endif

    <div class="row rounded border bg-white m-3 py-4">
        <div class="row mx-0 px-0 line-height-1">
            <div class="col-md-8 px-0 mx-0 d-flex align-items-center">
                <div class="row px-0 mx-0 w-100">
                    
                    <div class="col-4 d-flex align-items-center">
                        <img class="mr-2 rounded border"
                            src="https://tbbd-flight.s3.ap-southeast-1.amazonaws.com/airlines-logo/{{$flight->airline}}.png" alt="airline"
                            width="40">
                        <div class="d-flex align-items-center">
                            <a href="#" class="text-3 font-weight-bold text-dark mr-2">
                                {{$flight->airlineName()->name}}
                            </a>
                        </div>
                    </div>

                    <div class="col">
                        <div class="text-center d-inline-block">
                            {{-- <p class="text-dark text-4">00:50</p> --}}
                            <span class="badge bg-offwhite text-gray px-4 text-2">
                                {{$flight->from}} <br>
                                <small class="font-weight-normal">
                                    {{\App\Helpers\UtilityHelper::cityCodeToName($flight->from)}}
                                </small>
                            </span>
                            <div class="text-small bg-light mt-1 text-gray rounded">
                                {{ date("D, d M, y", strtotime($flight->depart_date)) }}
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="h-100 d-flex flex-column justify-content-center">
                            @if (empty($flight->transit_location))
                                <small class="text-center">Direct</small>
                            @else
                                <div class="d-inline-block text-center">
                                    <small class="font-weight-bold">Transit</small> <br>
                                    {{ $flight->transit_time }} <br>
                                    <small class="">{{ $flight->transit_location }}</small>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col">
                        <div class="text-center d-inline-block">
                            {{-- <p class="text-dark text-4">06:40</p> --}}
                            <span class="badge bg-offwhite text-gray px-4 text-2">
                                {{$flight->to}} <br>
                                <small class="font-weight-normal">
                                    {{\App\Helpers\UtilityHelper::cityCodeToName($flight->to)}}
                                </small>
                            </span>
                            <div class="text-small bg-light mt-1 text-gray rounded">
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
            <div class="col">
                <div class="text-center h-100 d-flex align-items-center">
                    <h6 class="mb-1 pl-1 font-weight-bold">
                        BDT
                        {{ number_format($flight->fare, 2) }}
                    </h6>
                </div>

            </div>
        </div>
    </div>

    <hr class="mx-3 my-4">

    {{-- passegers info --}}
    <div class="px-4 position-relative">
        <h6 class="text-secondary mb-1">How many ticket needs?* <small>(Qty)</small></h6>
        <select wire:model="ticket_count" class="form-control">
            @for ($i = 0; $i < $flight->count; $i++)
                <option value="{{$i + 1}}">{{ $i + 1 }}</option>
            @endfor
        </select>
        
    </div>

    <hr class="mx-3 my-4">

    {{-- passegers info --}}
    <div class="px-4">
        <h6 class="text-secondary">Enter Passeger Details</h6>
    </div>

    <div class="px-3 mt-2">
        @include('includes.errors')
        @include('includes.alert-failed-center')
        @include('includes.alert-success-center')
    </div>
    

    @foreach ($passengers as $p)
    <div wire:key="passengers-{{uniqid()}}" class="px-3 mb-3">
        <div class="accordion" id="accordionFlush">
            <div wire:key="passengers-box-{{uniqid()}}" class="accordion-item">
                <div class="bg-light p-2 px-4 d-flex">
                    <span>Adult #{{$loop->index + 1}}</span> 
                    @if ($loop->index != 0)
                    <span wire:click="removePassenger({{$loop->index}})" class="ml-auto" type="button">
                        <i class="fas fa-trash-alt text-danger"></i>
                    </span>
                    @endif
                </div>
                <div id="flush-collapse" class="accordion-collapse show">
                    <div class="accordion-body">
                        <div wire:key="form-{{$loop->index}}" class="form-row">
                            <div class="col-sm-2 form-group">
                            <label for="" class="text-small mb-0">Title</label>
                            <div>
                                <select wire:model="passengers.{{$loop->index}}.title" class="custom-select">
                                    <option>Mr</option>
                                    <option>Ms</option>
                                    <option>Mrs</option>
                                </select>
                            </div>

                            </div>
                            <div class="col-sm-4 form-group">
                                <label for="" class="text-small mb-0">First name</label>
                                <input wire:model.lazy="passengers.{{$loop->index}}.first_name" class="form-control" placeholder="Enter first name" type="text">
                            </div>
                            <div class="col-sm-4 form-group">
                                <label for="" class="text-small mb-0">Surename</label>
                                <input wire:model.lazy="passengers.{{$loop->index}}.surname" class="form-control" placeholder="Enter surname" type="text">
                            </div>
                            <div class="col-sm-2 form-group">
                                <label for="" class="text-small mb-0">Date of birth</label>
                                <x-date-picker2 wire:key="dob-{{$loop->index}}" wire:model="passengers.{{$loop->index}}.dob" yx-model="passengers[{{$loop->index}}]['dob']" placeholder="Date of birth" class="form-control"/>
                                <span class="icon-inside" style="top:calc(70% - 11px)"><i class="far fa-calendar-alt" aria-hidden="true"></i></span>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-sm-2 form-group">
                                <label for="" class="text-small mb-0">Gender</label>
                                <div>
                                    <select wire:model="passengers.{{$loop->index}}.gender" class="custom-select">
                                        <option value="">Select</option>
                                        <option value="M">Male</option>
                                        <option value="F">Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4 form-group">
                                <label for="" class="text-small mb-0">Passport No</label>
                                <input wire:model.lazy="passengers.{{$loop->index}}.passport_no" class="form-control" placeholder="Enter passport number" type="text">
                            </div>
                            <div class="col form-group">
                                <label for="" class="text-small mb-0">Passport expiry date</label>
                                <x-date-picker2 wire:key="expiry-date-{{$loop->index}}" wire:model="passengers.{{$loop->index}}.passport_expiry_date" yx-model="passengers[{{$loop->index}}]['passport_expiry_date']" class="form-control" placeholder="Passport expiry date"/>
                                <span class="icon-inside" style="top:calc(70% - 11px)"><i class="far fa-calendar-alt" aria-hidden="true"></i></span>
                            </div>
                            <div class="col form-group">
                                <label for="" class="text-small mb-0">Phone No</label>
                                <input wire:model.lazy="passengers.{{$loop->index}}.phone_no" placeholder="Ex: 01xxxxxxx" class="form-control" type="phone">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col form-group">
                                <label for="" class="text-small mb-0">Passport* <small>(JPG,PNG,PDF - 1MB max)</small></label>
                                <input wire:key="passport-{{$loop->index}}" wire:model="passengers.{{$loop->index}}.passport" class="form-control" type="file">
                            </div>
                            <div class="col form-group">
                                <label for="" class="text-small mb-0">Visa Copy* <small>(JPG,PNG,PDF - 1MB max)</small></label>
                                <input wire:key="visa-{{$loop->index}}" wire:model="passengers.{{$loop->index}}.visa" class="form-control" type="file">
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach


    <div class="card mx-3 p-2 mb-3 border-info">
        <small>If need any help please contact below number:</small>
        <h6>Contact No: {{ $flight->user->mobile_no ?? '' }}</h6>
    </div>

    <div class="px-3">
        <button wire:target="passport,visa" wire:loading.class="disabled" class="btn btn-primary px-5" wire:click="issue" type="submit">Issue Ticket</button>
        <button wire:target="passport,visa" wire:loading.class="disabled" class="btn btn-secondary px-5" wire:click="requestPartial" type="button">Partial Payment Request</button>
        @include('includes.alert_success_text')
    </div>


    <div wire:loading wire:target="issue,requestPartial">
        <x-loading />
    </div>

    <script>
        let form = () => {
            return {
                'passengers': @entangle('passengers').defer,
            }
        }
    </script>
</div>
