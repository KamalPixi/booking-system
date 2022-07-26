@extends('agent.master')

@section('content')

<div class="d-flex flex-column flex-md-row px-0">


    <aside class="col-md-3">
        <div class="bg-white shadow-md rounded p-3 h-100">
            <h3 class="text-5">Filter</h3>
                <hr class="mx-n3">
            <div class="accordion accordion-alternate style-2 mt-n3" id="toggleAlternate">
            <div class="card">
                <div class="card-header" id="stops">
                <h5 class="mb-0"> <a href="#">Providers</a> </h5>
                </div>
                <div id="togglestops" class="collapse show" aria-labelledby="stops">
                <div class="card-body">
                    <div class="custom-control custom-checkbox">
                    <input type="checkbox" id="nonstop" name="stop" class="custom-control-input">
                    <label class="custom-control-label" for="nonstop">Sabre</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                    <input type="checkbox" id="1stop" name="stop" class="custom-control-input">
                    <label class="custom-control-label" for="1stop">Amadeus</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                    <input type="checkbox" id="2stop" name="stop" class="custom-control-input">
                    <label class="custom-control-label" for="2stop">USBA</label>
                    </div>
                </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="stops">
                <h5 class="mb-0"> <a href="#" data-toggle="collapse" data-target="#togglestops" aria-expanded="true" aria-controls="togglestops">No. of Stops</a> </h5>
                </div>
                <div id="togglestops" class="collapse show" aria-labelledby="stops">
                <div class="card-body">
                    <div class="custom-control custom-checkbox">
                    <input type="checkbox" id="nonstop" name="stop" class="custom-control-input">
                    <label class="custom-control-label" for="nonstop">Non Stop</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                    <input type="checkbox" id="1stop" name="stop" class="custom-control-input">
                    <label class="custom-control-label" for="1stop">1 Stop</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                    <input type="checkbox" id="2stop" name="stop" class="custom-control-input">
                    <label class="custom-control-label" for="2stop">2+ Stop</label>
                    </div>
                </div>
                </div>
            </div>
            </div>
        </div>
    </aside>


    <div class="col-md-9">

        {{-- single flight --}}
        <div class="row rounded bg-white pt-3 pb-1 mb-2">
            <div class="row mx-0 mb-3">
                <div class="col d-flex align-items-center">
                    <img class="mr-2 rounded" src="https://tbbd-flight.s3.ap-southeast-1.amazonaws.com/airlines-logo/BS.png" alt="airline" width="40">
                    <div class="d-flex align-items-center">
                        <a href="" class="text-3 font-weight-bold text-dark mr-2">US Bangla Air</a>
                        <span class="badge badge-secondary badge-flight-search text-x-small">SABRE</span> <br>
                    </div>
                </div>
            </div>

            <div class="row mx-0 px-0 line-height-1">

                <div class="col-md-7 px-0 mx-0">
                    <div class="row px-0 mx-0">
                        <div class="col-md-4">
                            <div class="text-center d-inline-block">
                                <p class="text-dark text-4 line-height-2">01:10</p>
                                <span class="badge bg-offwhite text-gray px-4 text-2">DAC</span>
                                <div class="text-small bg-light mt-1 text-gray rounded">Thu, 30Jun,22</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="h-100 pl-0 pl-md-3 d-flex flex-column justify-content-center">
                                <div class="d-inline-block">
                                    <div>3h 45m</div>
                                    <span class="pl-1">Direct</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center d-inline-block" >
                                <p class="text-dark text-4 line-height-2">06:55</p>
                                <span class="badge bg-offwhite text-gray px-4 text-2">KUL</span>
                                <div class="text-small bg-light mt-1 text-gray rounded">Fri, 01Jul,22</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flight-divider"></div>

                    <div class="row px-0 mx-0">
                        <div class="col-md-4">
                            <div class="text-center d-inline-block">
                                <p class="text-dark text-4">01:10</p>
                                <span class="badge bg-offwhite text-gray px-4 text-2">DAC</span>
                                <div class="text-small bg-light mt-1 text-gray rounded">Thu, 30Jun,22</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="h-100 pl-0 pl-md-3 d-flex flex-column justify-content-center">
                                <div class="d-inline-block">
                                    <div>3h 45m</div>
                                    <span class="pl-1">Direct</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center d-inline-block" >
                                <p class="text-dark text-4">06:55</p>
                                <span class="badge bg-offwhite text-gray px-4 text-2">KUL</span>
                                <div class="text-small bg-light mt-1 text-gray rounded">Fri, 01Jul,22</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="d-flex h-100 justify-content-center line-height-1-2 flex-column text-small">
                        <strong class="">Economy</strong>
                        <strong class="text-success">Refundable</strong>
                        <strong class="text-uppercase"><i class="fa-solid fa-suitcase"></i> 30 kg</strong>
                        <strong class="">4 Seats</strong>
                    </div>
                </div>
                <div class="col-md-3">
                    <div>
                        <h6 class="mb-1 pl-1">BDT 22,717.00</h6>
                        <div class="bg-light px-2 py-1 text-small text-left rounded mb-2">
                            <span class="mb-1">Agent Price:</span><br>
                            <strong>BDT 21,717.00</strong>
                        </div>
                        <button class="btn btn-sm btn-primary btn-block">
                            <span>BOOK FLIGHT</span>
                        </button>
                    </div>

                </div>
            </div>

            <div class="row mt-3">
                <div class="col">
                    <button type="button" class="btn btn-sm text-small font-weight-bold btn-link text-decoration-none">Flight Details</button>
                    <button type="button" class="btn btn-sm text-small font-weight-bold btn-link text-decoration-none">Fare Details</button>
                </div>
            </div>

        </div>

        
        {{-- single flight --}}
        <div class="row rounded bg-white pt-3 pb-1">
            <div class="row mx-0 mb-3">
                <div class="col d-flex align-items-center">
                    <img class="mr-2 rounded" src="https://tbbd-flight.s3.ap-southeast-1.amazonaws.com/airlines-logo/BS.png" alt="airline" width="40">
                    <div class="d-flex align-items-center">
                        <a href="" class="text-3 font-weight-bold text-dark mr-2">US Bangla Air</a>
                        <span class="badge badge-secondary badge-flight-search text-x-small">SABRE</span> <br>
                    </div>
                </div>
            </div>

            <div class="row mx-0 px-0 line-height-1">
                <div class="col-md-7 px-0 mx-0">
                    <div class="row px-0 mx-0">
                        <div class="col-md-4">
                            <div class="text-center d-inline-block">
                                <p class="text-dark text-4 line-height-2">01:10</p>
                                <span class="badge bg-offwhite text-gray px-4 text-2">DAC</span>
                                <div class="text-small bg-light mt-1 text-gray rounded">Thu, 30Jun,22</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="h-100 pl-0 pl-md-3 d-flex flex-column justify-content-center">
                                <div class="d-inline-block">
                                    <div>3h 45m</div>
                                    <span class="pl-1">Direct</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center d-inline-block" >
                                <p class="text-dark text-4 line-height-2">06:55</p>
                                <span class="badge bg-offwhite text-gray px-4 text-2">KUL</span>
                                <div class="text-small bg-light mt-1 text-gray rounded">Fri, 01Jul,22</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="d-flex h-100 line-height-1-2 flex-column text-small">
                        <strong class="">Economy</strong>
                        <strong class="text-success">Refundable</strong>
                        <strong class="text-uppercase"><i class="fa-solid fa-suitcase"></i> 30 kg</strong>
                        <strong class="">4 Seats</strong>
                    </div>
                </div>
                <div class="col-md-3">
                    <div>
                        <h6 class="mb-1 pl-1">BDT 22,717.00</h6>
                        <div class="bg-light px-2 py-1 text-small text-left rounded mb-2">
                            <span class="mb-1">Agent Price:</span><br>
                            <strong>BDT 21,717.00</strong>
                        </div>
                        <button class="btn btn-sm btn-primary btn-block">
                            <span>BOOK FLIGHT</span>
                        </button>
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col">
                    <button type="button" class="btn btn-sm text-small font-weight-bold btn-link text-decoration-none">Flight Details</button>
                    <button type="button" class="btn btn-sm text-small font-weight-bold btn-link text-decoration-none">Fare Details</button>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection

