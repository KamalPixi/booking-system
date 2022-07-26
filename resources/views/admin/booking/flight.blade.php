@extends('admin.master')
@section('content')
    @livewire('admin.booking.booking-flight', ['booking_id' => $air_booking_id])
@endsection
