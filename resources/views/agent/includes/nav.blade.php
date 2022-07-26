<div class="position-relative nav-holder">
    <div class="container bg-primary rounded w-75">
    <ul class="nav justify-content-md-center secondary-nav">
        <li class="nav-item"> <a class="nav-link @if(isset($flight)) active @endif" href="/flight"><span><i class="fas fa-plane"></i></span> Flights</a> </li>
        <li class="nav-item"> <a class="nav-link @if(isset($prePurchasedAir)) active @endif" href="/pre-purchased-air"><span><i class="fas fa-plane"></i></span> Special Ticket</a> </li>
        <li class="nav-item"> <a class="nav-link @if(isset($umrah)) active @endif" href="/umrah"><span><i class="fas fa-kaaba"></i></span> Umrah</a> </li>
        {{-- <li class="nav-item"> <a class="nav-link @if(isset($group)) active @endif" href="/group"><span><i class="fas fa-hiking"></i></span> Group Request</a> </li> --}}
        <li class="nav-item"> <a class="nav-link @if(isset($hotel)) active @endif" href="/hotel"><span><i class="fas fa-bed"></i></span> Hotels</a></li>
        <li class="nav-item"> <a class="nav-link @if(isset($holiday)) active @endif" href="/holiday"><span><i class="fas fa-luggage-cart"></i></span> Holiday</a> </li>
    </ul>
</div>
