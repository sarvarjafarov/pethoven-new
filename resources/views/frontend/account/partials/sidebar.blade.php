<div class="account-sidebar">
    <ul class="list-group">
        <li class="list-group-item {{ request()->routeIs('account.dashboard') ? 'active' : '' }}">
            <a href="{{ route('account.dashboard') }}" class="d-flex align-items-center text-decoration-none {{ request()->routeIs('account.dashboard') ? 'text-white' : 'text-dark' }}">
                <i class="fa fa-dashboard me-2"></i> Dashboard
            </a>
        </li>
        <li class="list-group-item {{ request()->routeIs('account.orders*') ? 'active' : '' }}">
            <a href="{{ route('account.orders') }}" class="d-flex align-items-center text-decoration-none {{ request()->routeIs('account.orders*') ? 'text-white' : 'text-dark' }}">
                <i class="fa fa-shopping-bag me-2"></i> Orders
            </a>
        </li>
        <li class="list-group-item {{ request()->routeIs('account.profile') ? 'active' : '' }}">
            <a href="{{ route('account.profile') }}" class="d-flex align-items-center text-decoration-none {{ request()->routeIs('account.profile') ? 'text-white' : 'text-dark' }}">
                <i class="fa fa-user me-2"></i> Profile
            </a>
        </li>
        <li class="list-group-item">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-link text-danger text-decoration-none d-flex align-items-center p-0">
                    <i class="fa fa-sign-out me-2"></i> Logout
                </button>
            </form>
        </li>
    </ul>
</div>
