@extends('agent.master')

@section('content')
@include('agent.includes.nav', ['umrah' => 'active'])
<div class="row bg-white shadow-md rounded p-4">
<div class="col-lg-7 mx-auto mb-4 mb-lg-0">
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
    </div>
    <div class="form-row">
        <div class="col-lg-6 form-group">
        <input type="text" class="form-control" id="flightFrom" required placeholder="From">
        <span class="icon-inside"><i class="fas fa-map-marker-alt"></i></span> </div>
        <div class="col-lg-6 form-group">
        <input type="text" class="form-control" id="flightTo" required placeholder="To">
        <span class="icon-inside"><i class="fas fa-map-marker-alt"></i></span> </div>
    </div>
    <div class="form-row">
        <div class="col-lg-6 form-group">
        <input id="flightDepart" type="text" class="form-control" required placeholder="Depart Date">
        <span class="icon-inside"><i class="far fa-calendar-alt"></i></span> </div>
        <div class="col-lg-6 form-group">
        <input id="flightReturn" type="text" class="form-control" required placeholder="Return Date">
        <span class="icon-inside"><i class="far fa-calendar-alt"></i></span> </div>
    </div>
    <div class="travellers-class form-group">
        <input type="text" id="flightTravellersClass" class="travellers-class-input form-control" name="flight-travellers-class" placeholder="Travellers, Class" readonly required onkeypress="return false;">
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
    <button class="btn btn-primary w-100" type="submit">Search Umrah Flights</button>
    </form>
</div>
{{-- <div class="col-lg-5">
    <div class="d-flex h-100 align-items-center">
        <img class="img-fluid rounded mt-0 mt-md-5" src="assets/images/booking-banner-1.jpg" alt="" />
    </div>
</div> --}}
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
    });

    $(function() {
	    $('.calendar.right').show();
    });
});
</script>
@endpush