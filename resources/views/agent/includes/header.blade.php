<header class="header bg-secondary" 
    @if(isset($searchDashboard))
        style="height:10rem"
    @endif
>
<div class="container-fluid">
    <div class="row">
    <div class="col-lg-6 col-md-6 col-6">
        <div class="header-left d-flex align-items-center">
        <div class="menu-toggle-btn mr-3">
            <button
            id="menu-toggle"
            class="main-btn btn-hover">
            <i class="lni lni-chevron-left"></i>
            </button>
        </div>
        <div class="header-search profile-info d-none d-md-flex">
            <div class="d-flex flex-column pr-5 line-height-1">
                <div class="balance-details line-height-1">
                    <h4 class="text-white" style="font-size:1.35rem">DreamTripBD</h4>
                    <span class="text-off-white text-1">
                        <i class="fa-solid fa-square-phone"></i>
                        Hotline:
                        <strong>01842111102</strong>
                    </span>
                </div>
            </div>
        </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-5">
        <div class="header-right align-items-center">

        <!-- profile start -->
        <div class="profile-box ml-15">
            <a
                class="bg-transparent border-0"
                href="{{ route('b2b.gateway.addBalance') }}"
            >
            <div class="profile-info">
                <div class="info">
                    <div class="text-end line-height-1">
                        <div class="line-height-1 text-small">
                            <span class="font-weight-bold mb-2 text-white">
                                <i class="fa-solid fa-money-bill-transfer"></i>
                                Add Balance
                            </span>
                            @livewire('agent.profile.account-balance')
                        </div>
                    </div>
                </div>
            </div>
            </a>
        </div>


        <div class="profile-box">
            <button
            class="dropdown-toggle bg-transparent border-0"
            type="button"
            id="profile"
            data-bs-toggle="dropdown"
            aria-expanded="false"
            >
            <div class="profile-info">
                <div class="info">
                    <div class="text-end line-height-1">
                    </div>
                <div class="image">
                    @if(auth()->user()->photo)
                        <img src="{{ auth()->user()->photo->file }}" alt=""/>
                    @else
                        <img src="https://via.placeholder.com/60" alt=""/>
                    @endif

                    <span class="status"></span>
                </div>
                </div>
            </div>
            <i class="lni lni-chevron-down text-white"></i>
            </button>
            <ul
            class="dropdown-menu dropdown-menu-end"
            aria-labelledby="profile"
            >
            <li>
                <a href="{{ url('/company') }}">
                <i class="lni lni-user"></i> View Profile
                </a>
            </li>
            <li>
                <a href="{{ route('b2b.logout') }}"> <i class="lni lni-exit"></i> Sign Out </a>
            </li>
            </ul>
        </div>
        <!-- profile end -->

        <!-- notification start -->
            @livewire('agent.notification.notifications')

        </div>
    </div>
    </div>
</div>
</header>
