@extends('agent.master')

@section('content')
@include('agent.includes.nav', ['group' => 'active'])
<div class="row bg-white shadow-md rounded p-4">
    <div class="col px-0">
        <ul class="nav nav-tabs rounded-top" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">
                <i class="fas fa-bed" aria-hidden="true"></i>
                Hotels
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">
                <i class="fas fa-plane" aria-hidden="true"></i>
                Flights
            </button>
        </li>
        </ul>
        <div class="tab-content p-3 mt-4" id="myTabContent">
            <!-- hotels -->
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                
                <!-- Date & type -->
                <div class="card mb-5">
                    <!-- header -->
                    <div class="card-header">
                        Date & Type
                    </div>

                    <!-- body -->
                    <div>
                        <div class="form-row p-4">
                            <div class="col-lg-4 form-group">
                                <small class="label">Check in Date</small>
                                <input id="hotelsCheckIn" type="text" class="form-control form-control-sm" required placeholder="Check In">
                            </div>
                            <div class="col-lg-4 form-group">
                                <small class="label">Check out Date</small>
                                <input id="hotelsCheckOut" type="text" class="form-control form-control-sm" required placeholder="Check Out">
                            </div>
                            <div class="col-lg-4 form-group">
                                <small class="label">Request Type</small>
                                <select class="form-control form-control-sm">
                                    <option value="">FIT</option>
                                    <option value="">GROUP BOOKING</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                

                <!-- guest -->
                <div class="card mb-5">
                    <!-- header -->
                    <div class="card-header mb-4">
                        Guest
                    </div>

                    <!-- body -->
                    <div>
                        <div class="form-row px-4">
                            <div class="col-lg-3 form-group">
                                <small class="label">Number of Adults</small>
                                <input type="text" class="form-control form-control-sm" required>
                            </div>
                            <div class="col-lg-3 form-group">
                                <small class="label">Number of Children</small>
                                <input type="text" class="form-control form-control-sm" required>
                            </div>
                            <div class="col-lg-6 form-group">
                                <small class="label">Age of Children (Separated by coma)</small>
                                <input id="hotelsCheckOut" type="text" class="form-control form-control-sm" required>
                            </div>
                        </div>
                        <div class="form-row px-4">
                            <div class="col-lg-6 form-group">
                                <small class="label">Citizenship</small>
                                <input type="text" class="form-control form-control-sm" required>
                            </div>
                            <div class="col-lg-6 form-group">
                                <small class="label">Purpose of Visit</small>
                                <input type="text" class="form-control form-control-sm" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- city & hotel info -->
                <div class="card mb-5">
                    <!-- header -->
                    <div class="card-header mb-4">
                        City & Hotel Info
                    </div>

                    <!-- body -->
                    <div>
                        <div class="form-row px-4">
                            <div class="col-lg-6 form-group">
                                <small class="label">City</small> <small>(* required)</small>
                                <input type="text" class="form-control form-control-sm" required placeholder="Search for city...">
                            </div>
                            <div class="col-lg-6 form-group">
                                <small class="label">Location</small>
                                <input type="text" class="form-control form-control-sm" required placeholder="Specify location">
                            </div>
                        </div>
                        <div class="form-row px-4">
                            <div class="col-lg-6 form-group">
                                <small class="label">Star Rating</small>
                                <select class="form-control form-control-sm" required>
                                    <option value="">Any Star</option>
                                    <option value="">1 Star</option>
                                    <option value="">2 Star</option>
                                    <option value="">3 Star</option>
                                    <option value="">4 Star</option>
                                    <option value="">5 Star</option>
                                </select>
                            </div>
                            <div class="col-lg-6 form-group">
                                <small class="label">Hotel Name</small>
                                <input type="text" class="form-control form-control-sm" required placeholder="Mention desired hotel name">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer pl-4">
                        <button class="btn btn-sm btn-success p-1 text-small text-bold">ADD ONE MORE</button>
                        <button class="btn btn-sm btn-danger p-1 text-small text-bold">REMOVE LAST</button>
                    </div>
                </div>

                <!-- room info -->
                <div class="card mb-5">
                    <!-- header -->
                    <div class="card-header mb-4">
                        Room Information
                    </div>

                    <!-- body -->
                    <div>
                        <div class="form-row px-4">
                            <div class="col-lg-3 form-group">
                                <small class="label">Room Type</small>
                                <select class="form-control form-control-sm" required>
                                    <option value="">...</option>
                                    <option value="">Any</option>
                                    <option value="">Deluxe</option>
                                    <option value="">King</option>
                                    <option value="">Double</option>
                                    <option value="">Honeymoon Suite</option>
                                    <option value="">Suite</option>
                                    <option value="">Standard</option>
                                </select>
                            </div>
                            <div class="col-lg-3 form-group">
                                <small class="label">Number of Rooms</small>
                                <select class="form-control form-control-sm" required>
                                    <option value="">...</option>
                                    @for($i = 1; $i < 101; $i++)
                                        <option value="">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-lg-3 form-group">
                                <small class="label">Budget Per Night/Room</small>
                                <input type="text" class="form-control form-control-sm" required>
                            </div>
                            <div class="col-lg-3 form-group">
                                <small class="label">Bed Type</small>
                                <select class="form-control form-control-sm" required>
                                    <option value="">...</option>
                                    <option value="">Single</option>
                                    <option value="">Twin</option>
                                    <option value="">Double</option>
                                    <option value="">Triple</option>
                                    <option value="">Quad</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer pl-4">
                        <button class="btn btn-sm btn-success p-1 text-small text-bold">ADD</button>
                        <button class="btn btn-sm btn-danger p-1 text-small text-bold">REMOVE</button>
                    </div>
                </div>

                <!-- meal type -->
                <div class="card mb-5">
                    <!-- header -->
                    <div class="card-header mb-4">
                        Meal Type
                    </div>

                    <!-- body -->
                    <div>
                        <div class="form-row px-4">
                            <div class="col form-group">
                                <small class="label">Meal Types</small>
                                <select class="form-control form-control-sm js-example-basic-multiple" name="states[]" multiple="multiple" required>
                                    <option value="BREAKFAST">Breakfast</option>
                                    <option value="MORNING_SNACKS">Morning Snacks</option>
                                    <option value="LUNCH">Lunch</option>
                                    <option value="EVENING_SNACKS">Evening Snacks</option>
                                    <option value="DINNER">Dinner</option>
                                    <option value="BBQ_DINNER">BBQ Diner</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Special note -->
                <div class="card mb-5">
                    <!-- header -->
                    <div class="card-header mb-4">
                        Special Note (if any)
                    </div>

                    <!-- body -->
                    <div>
                        <div class="form-row px-4">
                            <div class="col form-group">
                                <small class="label">Special Note</small>
                                <textarea class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <button class="btn btn-primary btn-sm">CREATE A REQUEST</button>
                </div>

            </div>

            
            <!-- flight tab -->
            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">

            <div class="row rounded">
                <div class="col">
                    <small class="label text-bold mb-2 d-block">Flight Type</small>
                    <form id="bookingFlight" method="post">
                        <div class="mb-3">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input id="oneway" name="flight-trip" class="custom-control-input" checked="" required type="radio">
                                <label class="custom-control-label" for="oneway">One Way</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input id="roundtrip" name="flight-trip" class="custom-control-input" required type="radio">
                                <label class="custom-control-label" for="roundtrip">Round Trip</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input id="multi-city" name="flight-trip" class="custom-control-input" required type="radio">
                                <label class="custom-control-label" for="multi-city">Multi City</label>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-lg-6 form-group">
                                <small class="label">Flying From</small>
                                <input type="text" class="form-control form-control-sm" id="flightFrom" required placeholder="From">
                            </div>
                            <div class="col-lg-6 form-group">
                                <small class="label">Flying To</small>
                                <input type="text" class="form-control form-control-sm" id="flightTo" required placeholder="To">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-lg-6 form-group">
                                <small class="label">Depart Date</small>
                                <input id="flightDepart" type="text" class="form-control form-control-sm" required placeholder="Depart Date">
                            </div>
                            <div class="col-lg-6 form-group">
                                <small class="label">Return Date</small>
                                <input id="flightReturn" type="text" class="form-control form-control-sm" required placeholder="Return Date">
                            </div>
                        </div>
                        <div class="travellers-class form-group">
                            <small class="label">Guests</small>
                            <input type="text" id="flightTravellersClass" class="travellers-class-input form-control form-control-sm" name="flight-travellers-class" placeholder="Travellers, Class" readonly required onkeypress="return false;">
                            <span class="icon-inside"><i class="fas fa-caret-down"></i></span>
                            <div class="travellers-dropdown">
                            <div class="row align-items-center">
                                <div class="col-sm-7">
                                    <p class="mb-sm-0">Adults <small class="text-muted">(12+ yrs)</small></p>
                                </div>
                                <div class="col-sm-5">

                                <div class="qty input-group">
                                    <div class="input-group-prepend">
                                        <button type="button" class="btn bg-light-4" data-value="decrease" data-target="#flightAdult-travellers" data-toggle="spinner">-</button>
                                    </div>
                                    <input type="text" data-ride="spinner" id="flightAdult-travellers" class="qty-spinner form-control" value="1" readonly>
                                    <div class="input-group-append">
                                        <button type="button" class="btn bg-light-4" data-value="increase" data-target="#flightAdult-travellers" data-toggle="spinner">+</button>
                                    </div>
                                </div>
                                </div>
                            </div>
                            <hr class="my-2">
                            <div class="row align-items-center">
                                <div class="col-sm-7">
                                <p class="mb-sm-0">Children <small class="text-muted">(2-12 yrs)</small></p>
                                </div>
                                <div class="col-sm-5">
                                <div class="qty input-group">
                                    <div class="input-group-prepend">
                                        <button type="button" class="btn bg-light-4" data-value="decrease" data-target="#flightChildren-travellers" data-toggle="spinner">-</button>
                                    </div>
                                        <input type="text" data-ride="spinner" id="flightChildren-travellers" class="qty-spinner form-control" value="0" readonly>
                                    <div class="input-group-append">
                                        <button type="button" class="btn bg-light-4" data-value="increase" data-target="#flightChildren-travellers" data-toggle="spinner">+</button>
                                    </div>
                                </div>
                                </div>
                            </div>
                            <hr class="my-2">
                            <div class="row align-items-center">
                                <div class="col-sm-7">
                                    <p class="mb-sm-0">Infants <small class="text-muted">(Below 2 yrs)</small></p>
                                </div>
                                <div class="col-sm-5">
                                <div class="qty input-group">
                                    <div class="input-group-prepend">
                                        <button type="button" class="btn bg-light-4" data-value="decrease" data-target="#flightInfants-travellers" data-toggle="spinner">-</button>
                                    </div>
                                        <input type="text" data-ride="spinner" id="flightInfants-travellers" class="qty-spinner form-control" value="0" readonly>
                                    <div class="input-group-append">
                                        <button type="button" class="btn bg-light-4" data-value="increase" data-target="#flightInfants-travellers" data-toggle="spinner">+</button>
                                    </div>
                                </div>
                                </div>
                            </div>
                            <hr class="mt-2">
                            <div class="mb-3">
                                <div class="custom-control custom-radio">
                                    <input id="flightClassEconomic" name="flight-class" class="flight-class custom-control-input" value="0" checked="" required type="radio">
                                    <label class="custom-control-label" for="flightClassEconomic">Economic</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input id="flightClassPremiumEconomic" name="flight-class" class="flight-class custom-control-input" value="1" required type="radio">
                                    <label class="custom-control-label" for="flightClassPremiumEconomic">Premium Economic</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input id="flightClassBusiness" name="flight-class" class="flight-class custom-control-input" value="2" required type="radio">
                                    <label class="custom-control-label" for="flightClassBusiness">Business</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input id="flightClassFirstClass" name="flight-class" class="flight-class custom-control-input" value="3" required type="radio">
                                    <label class="custom-control-label" for="flightClassFirstClass">First Class</label>
                                </div>
                            </div>
                                <button class="btn btn-primary w-100 submit-done" type="button">Done</button>
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="col-lg-6">
                                <small class="label">Group Name</small> <small>(optional)</small>
                                <input type="text" class="form-control form-control-sm">
                            </div>
                            <div class="col-lg-6">
                                <small class="label">Group Type</small> <small>(optional)</small>
                                <input type="text" class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col form-group">
                                <small class="label">Special Note</small>
                                <textarea class="form-control"></textarea>
                            </div>
                        </div>


                        <button class="btn btn-primary btn-sm mt-5" type="submit">CREATE A REQUEST</button>
                    </form>
                </div>
            </div>
            </div>

        </div>
    </div>
</div>
</div>
@endsection

@push('script')
<!-- for check in checkout date -->
<script>
$(document).ready(function() {
    $('.js-example-basic-multiple').select2();
});

$(function() {
 'use strict';
  // Autocomplete
  $('#hotelsFrom').autocomplete({
	  minLength: 3,
	  delay: 100,
	  source: function (request, response) {
		$.getJSON(
		 'http://gd.geobytes.com/AutoCompleteCity?callback=?&q='+request.term,
		  function (data) {
			 response(data);
		}
	);
	},
  });
  // Hotels Check In Date
  $('#hotelsCheckIn').daterangepicker({
	singleDatePicker: true,
	autoApply: true,
	minDate: moment(),
	autoUpdateInput: false,
	}, function(chosen_date) {
  $('#hotelsCheckIn').val(chosen_date.format('MM-DD-YYYY'));
  });
  
  // Hotels Check Out Date
  $('#hotelsCheckOut').daterangepicker({
	singleDatePicker: true,
	autoApply: true,
	minDate: moment(),
	autoUpdateInput: false,
	}, function(chosen_date) {
  $('#hotelsCheckOut').val(chosen_date.format('MM-DD-YYYY'));
});});
</script>


<!-- for flight -->
<script>
$(function() {
'use strict';
    // Autocomplete
    $('#flightFrom,#flightTo').autocomplete({
    minLength: 3,
    delay: 100,
    source: function (request, response) {
    $.getJSON(
    'http://gd.geobytes.com/AutoCompleteCity?callback=?&q='+request.term,
        function (data) {
        response(data);
    }
    );
    },
    });
    // Depart Date
    $('#flightDepart').daterangepicker({
    singleDatePicker: true,
    autoApply: true,
    minDate: moment(),
    autoUpdateInput: false,
    }, function(chosen_date) {
    $('#flightDepart').val(chosen_date.format('MM-DD-YYYY'));
    });
    
    // Return Date
    $('#flightReturn').daterangepicker({
    singleDatePicker: true,
    autoApply: true,
    minDate: moment(),
    autoUpdateInput: false,
    }, function(chosen_date) {
    $('#flightReturn').val(chosen_date.format('MM-DD-YYYY'));
});});
</script>
@endpush
