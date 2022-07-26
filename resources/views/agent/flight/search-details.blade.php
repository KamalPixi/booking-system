@extends('agent.master')
@section('content')

    @if (request()->input('pre_purchased_id'))
        @livewire('agent.flight.pre-purchased-air-book')
    @else
        @livewire('agent.flight.flight-search-details')
    @endif

@endsection
