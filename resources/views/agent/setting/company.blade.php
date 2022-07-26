@extends('agent.master')
@section('content')

<div id="content">
<section class="container">
    <div class="row mt-4">
        <div class="col-md-12 col-lg-10">
            <div wire:ignore.self id="verticalTab">
                <div class="row no-gutters">
                    <div class="col-md-3 my-0 my-md-4">
                        <ul class="resp-tabs-list">
                            <li><span><i class="fa-solid fa-address-card"></i></span> Profile</li>
                            <li><span><i class="fas fa-building"></i></span> Company</li>
                            <li><span><i class="fas fa-users"></i></span> Users</li>
                            <li><span><i class="fas fa-tasks"></i></span> Settings </li>
                    </div>
                    <div class="col-md-9">
                        <div class="resp-tabs-container bg-white shadow-md rounded h-100 p-3">

                            <!-- Profile -->
                            <div>
                                @livewire('agent.settings.profile')
                            </div>

                            <div>
                                @livewire('agent.settings.company')
                            </div>


                            <!-- Users -->
                            <div>
                                @if(auth()->user()->can('agent_user view') || auth()->user()->type == \App\Enums\UserEnum::TYPE['AGENT'])
                                    @livewire('agent.settings.users')
                                    @livewire('agent.settings.roles')
                                    @livewire('agent.settings.permissions')
                                @else
                                    @include('agent.includes.unauthorized-text')
                                @endif
                            </div>


                            <!-- Settings -->
                            <div>
                                @livewire('agent.settings.setting')
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>



    </div>
</section>
</div>


@push('script')
<script src="/assets/inner/vendor/easy-responsive-tabs/easy-responsive-tabs.js"></script> 
<script>
    $(document).ready(function () {
        $('#verticalTab').easyResponsiveTabs({
            type: 'vertical', //Types: default, vertical, accordion
        });
    });
</script>
@endpush
@endsection
