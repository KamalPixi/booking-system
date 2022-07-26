@extends('agent.master')

@section('content')
    <div class="bg-secondary pt-4 pb-5">
        <div class="container">

            <!-- nav -->
            @include('agent.includes.nav-02', ['flight' => 'active'])

            <!-- Flights Search -->
            @livewire('agent.flight.flight-search')

        </div>
    </div>
@endsection
