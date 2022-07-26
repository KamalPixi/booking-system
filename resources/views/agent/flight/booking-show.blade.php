@extends('agent.master')
@section('content')
    @livewire('agent.flight.flight-booking-show', ['flight_booking_id' => $flight_booking_id])
@endsection
