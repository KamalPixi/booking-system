@extends('agent.master')

@section('content')
@include('agent.includes.nav', ['prePurchasedAir' => 'active'])
<div class="row bg-white shadow-md rounded p-4" style="min-height:30rem">
    <div class="mx-auto mb-4 mb-lg-0">
        @livewire('agent.flight.pre-purchased-air')
    </div>
</div>
@endsection



<!-- will be used inside liveware components -->
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

