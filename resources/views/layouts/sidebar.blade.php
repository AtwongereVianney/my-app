<div class="card shadow-sm">
    <div class="card-header bg-light">
        <strong><i class="fas fa-bars"></i> Menu</strong>
    </div>
    <div class="list-group list-group-flush">
        <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('dashboard') ? 'active' : '' }}" aria-current="{{ request()->routeIs('dashboard') ? 'page' : false }}">
            <i class="fas fa-gauge"></i> Dashboard
        </a>
        <a href="{{ route('rooms.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('rooms.*') ? 'active' : '' }}" aria-current="{{ request()->routeIs('rooms.*') ? 'page' : false }}">
            <i class="fas fa-bed"></i> Rooms
        </a>
        <a href="{{ route('students.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('students.*') ? 'active' : '' }}" aria-current="{{ request()->routeIs('students.*') ? 'page' : false }}">
            <i class="fas fa-users"></i> Students
        </a>
        <a href="{{ route('bookings.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('bookings.*') ? 'active' : '' }}" aria-current="{{ request()->routeIs('bookings.*') ? 'page' : false }}">
            <i class="fas fa-calendar-check"></i> Bookings.
        </a>
        <a href="{{ route('payments.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('payments.*') ? 'active' : '' }}" aria-current="{{ request()->routeIs('payments.*') ? 'page' : false }}">
            <i class="fas fa-money-bill"></i> Payments
        </a>
        <a href="{{ route('payments.statistics') }}" class="list-group-item list-group-item-action {{ request()->routeIs('payments.statistics') ? 'active' : '' }}" aria-current="{{ request()->routeIs('payments.statistics') ? 'page' : false }}">
            <i class="fas fa-chart-bar"></i> Payment Statistics
        </a>
    </div>
</div>

<!-- Authentication Section -->
<div class="card shadow-sm mt-3">
    <div class="card-header bg-light">
        <strong><i class="fas fa-user"></i> Account</strong>
    </div>
    <div class="list-group list-group-flush">
        @auth
            <span class="list-group-item text-muted">
                <i class="fas fa-user-circle"></i> Welcome, {{ Auth::user()->name }}
            </span>
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="list-group-item list-group-item-action text-danger">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" class="list-group-item list-group-item-action">
                <i class="fas fa-sign-in-alt"></i> Login
            </a>
            <a href="{{ route('register') }}" class="list-group-item list-group-item-action">
                <i class="fas fa-user-plus"></i> Register
            </a>
        @endauth
    </div>
</div>

