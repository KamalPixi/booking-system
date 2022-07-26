<aside class="sidebar-nav-wrapper">
    <div class="navbar-logo">
        <a href="/" class="text-white font-weight-bold">
            <!-- <img src="assets/images/logo/logo.png" alt="logo" /> -->
            B2B - DreamTripBD
        </a>
    </div>
    <nav class="sidebar-nav">
        <ul>
            <li class="nav-item text-white">
                <a href="/" class="text-white"> 
                    <span class="icon">
                        <i class="fas fa-search"></i>
                    </span>
                    Search
                 </a>
            </li>

            <li class="nav-item nav-item-has-children">
                <a
                    href="#0"
                    class="collapsed"
                    data-bs-toggle="collapse"
                    data-bs-target="#ddmenu_12"
                    aria-controls="ddmenu_12"
                    aria-expanded="false"
                    aria-label="Toggle navigation"
                >
                    <span class="icon">
                    <i class="fas fa-exchange-alt"></i>
                    </span>
                    <span class="text">Flight Bookings</span>
                </a>
                <ul id="ddmenu_12" class="collapse @if(isset($active) && $active[0] == 3) show @endif dropdown-nav">
                    <li>
                        <a class="@if(isset($active) && $active[0] == 3 && $active[1] == 1) active @endif" href="{{ url('flight/history') }}">All</a>
                    </li>
                    <li>
                        <a class="@if(isset($active) && $active[0] == 3 && $active[1] == 2) active @endif" href="{{ url('/flight/pending') }}">Pending</a>
                    </li>
                    <li>
                        <a class="@if(isset($active) && $active[0] == 3 && $active[1] == 2) active @endif" href="{{ url('/flight/in-progress') }}">InProgress</a>
                    </li>
                    <li>
                        <a class="@if(isset($active) && $active[0] == 3 && $active[1] == 2) active @endif" href="{{ url('/flight/issued') }}">Issued</a>
                    </li>
                    <li>
                        <a class="@if(isset($active) && $active[0] == 3 && $active[1] == 3) active @endif" href="{{ url('/flight/canceled') }}">Canceled</a>
                    </li>
                    <li>
                        <a class="@if(isset($active) && $active[0] == 3 && $active[1] == 4) active @endif" href="{{ url('/flight/refunded') }}">Refunded</a>
                    </li>
                    {{-- <li>
                        <a class="@if(isset($active) && $active[0] == 3 && $active[1] == 5) active @endif" href="{{ url('/flight/changed') }}">Changed</a>
                    </li> --}}
                </ul>
            </li>

            <li class="nav-item nav-item-has-children">
                <a
                    href="#0"
                    class="collapsed"
                    data-bs-toggle="collapse"
                    data-bs-target="#ddmenu_10"
                    aria-controls="ddmenu_10"
                    aria-expanded="false"
                    aria-label="Toggle navigation"
                >
                    <span class="icon">
                    <i class="fas fa-file-invoice-dollar"></i>
                    </span>
                    <span class="text">Accounts</span>
                </a>
                <ul id="ddmenu_10" class="collapse @if(isset($active) && $active[0] == 4) show @endif collapse dropdown-nav">
                    <li>
                        <a class="@if(isset($active) && $active[0] == 4 && $active[1] == 1) active @endif" href="{{ url('/deposits') }}"> Deposits </a>
                    </li>
                    <li>
                        <a class="@if(isset($active) && $active[0] == 4 && $active[1] == 2) active @endif" href="{{ url('/payments') }}"> Payments </a>
                    </li>
                    <li>
                        <a class="@if(isset($active) && $active[0] == 4 && $active[1] == 3) active @endif" href="{{ url('/refunds') }}"> Refunds </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item nav-item-has-children">
                <a
                    href="#0"
                    class="collapsed"
                    data-bs-toggle="collapse"
                    data-bs-target="#ddmenu_11"
                    aria-controls="ddmenu_11"
                    aria-expanded="false"
                    aria-label="Toggle navigation"
                >
                    <span class="icon">
                    <i class="fas fa-cogs"></i>
                    </span>
                    <span class="text">Settings</span>
                </a>
                <ul id="ddmenu_11" class=" @if(isset($active) && $active[0] == 6) show @endif collapse dropdown-nav">
                    <li>
                        <a class="@if(isset($active) && $active[0] == 6 && $active[1] == 2) active @endif" href="{{ url('/company') }}"> Manage </a>
                    </li>
                    {{-- <li>
                        <a class="@if(isset($active) && $active[0] == 6 && $active[1] == 3) active @endif" href="{{ url('/passengers') }}"> Passengers </a>
                    </li> --}}
                </ul>
            </li>


            <li class="nav-item text-white">
                <a href="{{ url('/payment-method-list') }}" class="text-white"> 
                    <span class="icon">
                        <i class="fa-solid fa-building-columns"></i>
                    </span>
                    Bank List
                 </a>
            </li>
            <li class="nav-item text-white">
                <a href="{{ url('/logout') }}" class="text-white"> 
                    <span class="icon">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    </span>
                    Sign out
                 </a>
            </li>
        </ul>
    </nav>
</aside>
<div class="overlay"></div>
