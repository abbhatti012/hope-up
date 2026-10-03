<ul class="nav items">
    <li class="nav item"><a href="{{ route('user-dashboard') }}">Dashboard</a></li>
    <li class="nav item"><a href="{{ route('account-information') }}">Account Information</a></li>
    <li class="nav item"><a href="{{ route('billing-information') }}">Billing Information</a></li>
    <li class="nav item"><a href="{{ route('user-orders') }}">My Orders</a></li>
    <li class="nav item"><a href="{{ route('track-order') }}">Track Order</a></li>
    <li class="nav item">
        <span class="delimiter block border-b border-container w-full my-2"></span>
    </li>
    <li class="nav item">
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Sign Out</a>
    </li>

</ul>