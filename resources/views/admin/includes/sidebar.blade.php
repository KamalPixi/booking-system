<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

<!-- Sidebar - Brand -->
<a class="sidebar-brand d-flex align-items-center justify-content-center" href="/">
    <div class="sidebar-brand-icon bg-white p-1 rounded">
        <img src="{{ asset('admin_assets/img/logo.png') }}" alt="logo" width="40">
    </div>
    <div class="sidebar-brand-text mx-3">DreamTripBD</div>
</a>

<!-- Divider -->
<hr class="sidebar-divider my-0">

<!-- Nav Item - Dashboard -->
<li class="nav-item">
    <a class="nav-link" href="/">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>Dashboard</span>
    </a>
</li>



@if (auth()->user()->can('admin_view flight booking'))
<!-- Divider -->
<hr class="sidebar-divider">
    <li class="nav-item">
        <a class="nav-link @if(isset($active) && $active['menu'] != 'flight_bookings') collapsed @endif" href="#" data-toggle="collapse" data-target="#collapse1"
            aria-expanded="true" aria-controls="collapse1">
            <i class="far fa-list-alt"></i>
            <span>Flight Bookings</span>
        </a>
        <div id="collapse1" class="collapse @if(isset($active) && $active['menu'] == 'flight_bookings') show @endif" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Flight Management</h6>
                <a class="collapse-item @if(isset($active) && $active['sub_menu'] == 'booking.flights') active @endif" href="{{ route('admin.booking.flights') }}">All Bookings</a>
                <a class="collapse-item @if(isset($active) && $active['sub_menu'] == 'booking.flight-issue-requests') active @endif" href="{{ route('admin.booking.flight-issue-requests') }}">Issue Requests</a>
                <a class="collapse-item @if(isset($active) && $active['sub_menu'] == 'booking.flights-issued') active @endif" href="{{ route('admin.booking.flights-issued') }}">Issued/Changed</a>
                <a class="collapse-item @if(isset($active) && $active['sub_menu'] == 'booking.flights-refunded') active @endif" href="{{ route('admin.booking.flights-refunded') }}">Refunded</a>
                <a class="collapse-item @if(isset($active) && $active['sub_menu'] == 'booking.flights-canceled') active @endif" href="{{ route('admin.booking.flights-canceled') }}">Canceled</a>
            </div>
        </div>
    </li>
@endif


@if (auth()->user()->can('admin_view deposit'))
<li class="nav-item">
    <a class="nav-link @if(isset($active) && $active['menu'] != 'deposits') collapsed @endif" href="#" data-toggle="collapse" data-target="#collapse4"
        aria-expanded="true" aria-controls="collapse4">
        <i class="far fa-list-alt"></i>
        <span>Deposits</span>
    </a>
    <div id="collapse4" class="collapse @if(isset($active) && $active['menu'] == 'deposits') show @endif" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Deposit Managment</h6>
            <a class="collapse-item @if(isset($active) && $active['sub_menu'] == 'deposits') active @endif" href="{{ route('admin.deposits') }}">All Depoists</a>
            <a class="collapse-item @if(isset($active) && $active['sub_menu'] == 'deposits.online') active @endif" href="{{ route('admin.deposits.online') }}">Online Deposits</a>
            <a class="collapse-item @if(isset($active) && $active['sub_menu'] == 'deposits.manual') active @endif" href="{{ route('admin.deposits.manual') }}">Manual Deposits</a>
            <a class="collapse-item @if(isset($active) && $active['sub_menu'] == 'deposits.canceled') active @endif" href="{{ route('admin.deposits.canceled') }}">Canceled Deposits</a>
        </div>
    </div>
</li>
@endif


@if (auth()->user()->can('admin_view payment'))
<li class="nav-item">
    <a class="nav-link @if(isset($active) && $active['menu'] != 'payments') collapsed @endif" href="#" data-toggle="collapse" data-target="#collapse5"
        aria-expanded="true" aria-controls="collapse5">
        <i class="far fa-list-alt"></i>
        <span>Payments</span>
    </a>
    <div id="collapse5" class="collapse @if(isset($active) && $active['menu'] == 'payments') show @endif" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Payment Management</h6>
            <a class="collapse-item @if(isset($active) && $active['sub_menu'] == 'payments') active @endif" href="{{ route('admin.payments') }}">Payments</a>
            <a class="collapse-item @if(isset($active) && $active['sub_menu'] == 'payments.partial-requests') active @endif" href="{{ route('admin.payments.partial-requests') }}">Partial Requests</a>
        </div>
    </div>
</li>
@endif


@if (auth()->user()->can('admin_view transaction'))
<li class="nav-item">
    <a class="nav-link @if(isset($active) && $active['menu'] != 'transactions') collapsed @endif" href="#" data-toggle="collapse" data-target="#collapse7"
        aria-expanded="true" aria-controls="collapse7">
        <i class="far fa-list-alt"></i>
        <span>Transactions</span>
    </a>
    <div id="collapse7" class="collapse @if(isset($active) && $active['menu'] == 'transactions') show @endif" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Transaction Management</h6>
            <a class="collapse-item @if(isset($active) && $active['sub_menu'] == 'transactions') active @endif" href="{{ route('admin.transactions') }}">Transactions</a>
            <a class="collapse-item @if(isset($active) && $active['sub_menu'] == 'refunds.requests') active @endif" href="{{ route('admin.refunds.requests') }}">Refund Requets</a>
            <a class="collapse-item @if(isset($active) && $active['sub_menu'] == 'refunds') active @endif" href="{{ route('admin.refunds') }}">Refunds</a>
        </div>
    </div>
</li>
@endif


@if (auth()->user()->type == \App\Enums\UserEnum::TYPE['ADMIN'])
<li class="nav-item">
    <a class="nav-link @if(isset($active) && $active['menu'] != 'agents') collapsed @endif" href="#" data-toggle="collapse" data-target="#customers"
        aria-expanded="true" aria-controls="customers">
        <i class="fas fa-users"></i>
        <span>Agents Management</span>
    </a>
    <div id="customers" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Agent Management</h6>
            <a class="collapse-item" href="{{ route('admin.agents') }}">Agents</a>
            <a class="collapse-item" href="{{ route('admin.agents.requests') }}">Agents Requests</a>
        </div>
    </div>
</li>
@endif

@if (auth()->user()->type == \App\Enums\UserEnum::TYPE['ADMIN'])
<li class="nav-item">
    <a class="nav-link @if(isset($active) && $active['menu'] != 'add') collapsed @endif" href="#" data-toggle="collapse" data-target="#add"
        aria-expanded="true" aria-controls="add">
        <i class="fas fa-users"></i>
        <span>Add</span>
    </a>
    <div id="add" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Add Management</h6>
            <a class="collapse-item" href="{{ route('admin.add-pre-air') }}">Add Special Ticket</a>
        </div>
    </div>
</li>
@endif


<!-- Divider -->
<hr class="sidebar-divider">
<li class="nav-item">
    <a class="nav-link @if(isset($active) && $active['menu'] != 'settings') collapsed @endif" href="#" data-toggle="collapse" data-target="#setting"
        aria-expanded="true" aria-controls="report">
        <i class="fas fa-cogs"></i>
        <span>Settings</span>
    </a>
    <div id="setting" class="collapse @if(isset($active) && $active['menu'] == 'settings') show @endif" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Setting Management</h6>
            <a class="collapse-item @if(isset($active) && $active['sub_menu'] == 'profile') active @endif" href="{{ route('admin.profile') }}">Profile</a>
            
            @if (auth()->user()->type == \App\Enums\UserEnum::TYPE['ADMIN'])
                <a class="collapse-item @if(isset($active) && $active['sub_menu'] == 'admin-fees') active @endif" href="{{ route('admin.admin-fees') }}">Comissions</a>
                <a class="collapse-item @if(isset($active) && $active['sub_menu'] == 'banks') active @endif" href="{{ route('admin.banks') }}">Banks</a>
                <a class="collapse-item @if(isset($active) && $active['sub_menu'] == 'payment-methods') active @endif" href="{{ route('admin.payment-methods') }}">Payment Methods</a>
                <a class="collapse-item @if(isset($active) && $active['sub_menu'] == 'users') active @endif" href="{{ route('admin.users') }}">Users</a>
            @endif
        </div>
    </div>
</li>

<!-- Divider -->
<hr class="sidebar-divider d-none d-md-block">

<!-- Sidebar Toggler (Sidebar) -->
<div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
</div>

</ul>
