@extends('agent.master')

@section('content')
@include('agent.includes.nav', ['holiday' => 'active'])

<div class="bg-white shadow-md p-4">
    <div class="row">
        @livewire('agent.holiday.holiday-search')

        {{-- <div class="col-lg-7">
            <img class="img-fluid rounded" src="assets/images/booking-banner-1.jpg" alt="" />
        </div> --}}
    </div>
</div>
@endsection
