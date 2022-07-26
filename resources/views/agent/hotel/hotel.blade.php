@extends('agent.master')

@section('content')
@include('agent.includes.nav', ['hotel' => 'active'])

<div class="bg-white shadow-md p-4">
    <div class="row">
        <div class="col-lg-7 mx-auto mb-4 mb-lg-0">
            <h2 class="text-4 mb-3">Book Domestic and International Hotels</h2>
            <form id="bookingHotels" method="post">
            <div class="form-row">
                <div class="col-lg-12 form-group">
                <input type="text" class="form-control" id="hotelsFrom" required placeholder="Enter Locality, City">
                <span class="icon-inside"><i class="fas fa-map-marker-alt"></i></span> </div>
            </div>
            <div class="form-row">
                <div class="col-lg-6 form-group">
                    <input id="hotelsCheckIn" type="text" class="form-control" required placeholder="Check In">
                    <span class="icon-inside"><i class="far fa-calendar-alt"></i></span> </div>
                <div class="col-lg-6 form-group">
                <input id="hotelsCheckOut" type="text" class="form-control" required placeholder="Check Out">
                <span class="icon-inside"><i class="far fa-calendar-alt"></i></span> </div>
            </div>
            <div class="travellers-class form-group">
                <input type="text" id="hotelsTravellersClass"  class="travellers-class-input form-control" name="hotels-travellers-class" placeholder="Rooms / People" required onKeyPress="return false;">
                <span class="icon-inside"><i class="fas fa-caret-down"></i></span>
                <div class="travellers-dropdown">
                <div class="row align-items-center">
                    <div class="col-sm-7">
                    <p class="mb-sm-0">Rooms</p>
                    </div>
                    <div class="col-sm-5">
                    <div class="qty input-group">
                        <div class="input-group-prepend">
                        <button type="button" class="btn bg-light-4" data-value="decrease" data-target="#hotels-rooms" data-toggle="spinner">-</button>
                        </div>
                        <input type="text" data-ride="spinner" id="hotels-rooms" class="qty-spinner form-control" value="1" readonly >
                        <div class="input-group-append">
                        <button type="button" class="btn bg-light-4" data-value="increase" data-target="#hotels-rooms" data-toggle="spinner">+</button>
                        </div>
                    </div>
                    </div>
                </div>
                <hr class="mt-2 mb-4">
                <div class="row align-items-center">
                    <div class="col-sm-7">
                    <p class="mb-sm-0">Adults <small class="text-muted">(12+ yrs)</small></p>
                    </div>
                    <div class="col-sm-5">
                    <div class="qty input-group">
                        <div class="input-group-prepend">
                        <button type="button" class="btn bg-light-4" data-value="decrease" data-target="#adult-travellers" data-toggle="spinner">-</button>
                        </div>
                        <input type="text" data-ride="spinner" id="adult-travellers" class="qty-spinner form-control" value="1" readonly>
                        <div class="input-group-append">
                        <button type="button" class="btn bg-light-4" data-value="increase" data-target="#adult-travellers" data-toggle="spinner">+</button>
                        </div>
                    </div>
                    </div>
                </div>
                <hr class="my-2">
                <div class="row align-items-center">
                    <div class="col-sm-7">
                    <p class="mb-sm-0">Children <small class="text-muted">(1-12 yrs)</small></p>
                    </div>
                    <div class="col-sm-5">
                    <div class="qty input-group">
                        <div class="input-group-prepend">
                        <button type="button" class="btn bg-light-4" data-value="decrease" data-target="#children-travellers" data-toggle="spinner">-</button>
                        </div>
                        <input type="text" data-ride="spinner" id="children-travellers" class="qty-spinner form-control" value="0" readonly>
                        <div class="input-group-append">
                        <button type="button" class="btn bg-light-4" data-value="increase" data-target="#children-travellers" data-toggle="spinner">+</button>
                        </div>
                    </div>
                    </div>
                </div>
                <button class="btn btn-primary btn-block submit-done mt-3" type="button">Done</button>
                </div>
            </div>
            <button class="btn btn-primary btn-block" type="submit">Search Hotels</button>
            </form>
        </div>

        {{-- <div class="col-lg-5">
            <div class="d-flex h-100 align-items-center">
                <img class="img-fluid rounded mt-0 mt-md-2" src="assets/images/booking-banner-1.jpg" alt="" />
            </div>
        </div> --}}
    </div>
</div>
@endsection


@push('css')
<style>
    .show-calendar  {
        display:flex;
    }
    .show-calendar .left {
        padding-right: 10px;
        border-right: 1px solid rgb(209, 209, 209);
    }
</style>
@endpush

@push('script')
<script>
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
    });

    
    $(function() {
	    $('.calendar.right').show();
    });
});
</script>
@endpush
