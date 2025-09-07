<div class="rounded-md border bg-white shadow-sm">
    <div class="border-b bg-gray-50 px-4 py-2">
        <strong class="text-gray-700"><i class="fas fa-bars"></i> Menu</strong>
    </div>
    <nav class="py-1">
        <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm rounded {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-700 hover:bg-gray-100' }}" aria-current="{{ request()->routeIs('dashboard') ? 'page' : false }}">
            <i class="fas fa-gauge me-2"></i> Dashboard
        </a>
        <a href="{{ route('rooms.index') }}" class="block px-4 py-2 text-sm rounded {{ request()->routeIs('rooms.*') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-700 hover:bg-gray-100' }}" aria-current="{{ request()->routeIs('rooms.*') ? 'page' : false }}">
            <i class="fas fa-bed me-2"></i> Rooms
        </a>
        <a href="{{ route('students.index') }}" class="block px-4 py-2 text-sm rounded {{ request()->routeIs('students.*') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-700 hover:bg-gray-100' }}" aria-current="{{ request()->routeIs('students.*') ? 'page' : false }}">
            <i class="fas fa-users me-2"></i> Students
        </a>
        <a href="{{ route('bookings.index') }}" class="block px-4 py-2 text-sm rounded {{ request()->routeIs('bookings.*') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-700 hover:bg-gray-100' }}" aria-current="{{ request()->routeIs('bookings.*') ? 'page' : false }}">
            <i class="fas fa-calendar-check me-2"></i> Bookings
        </a>
        <a href="{{ route('payments.index') }}" class="block px-4 py-2 text-sm rounded {{ request()->routeIs('payments.*') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-700 hover:bg-gray-100' }}" aria-current="{{ request()->routeIs('payments.*') ? 'page' : false }}">
            <i class="fas fa-money-bill me-2"></i> Payments
        </a>
        <a href="{{ route('paymentStatistics.index') }}" class="block px-4 py-2 text-sm rounded {{ request()->routeIs('paymentStatistics*') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-700 hover:bg-gray-100' }}" aria-current="{{ request()->routeIs('paymentStatistics*') ? 'page' : false }}">
            <i class="fas fa-chart-bar me-2"></i> Payment Statistics
        </a>
    </nav>
</div>

<div class="mt-3 rounded-md border bg-white shadow-sm">
    <div class="border-b bg-gray-50 px-4 py-2">
        <strong class="text-gray-700"><i class="fas fa-user"></i> Account</strong>
    </div>
    <div class="py-1">
        @auth
            <span class="block px-4 py-2 text-sm text-gray-500">
                <i class="fas fa-user-circle me-2"></i> Welcome, {{ Auth::user()->name }}
            </span>
            <form method="POST" action="{{ route('logout') }}" class="px-4 pb-2">
                @csrf
                <button type="submit" class="w-full rounded bg-red-50 px-3 py-2 text-left text-sm text-red-600 hover:bg-red-100">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                <i class="fas fa-sign-in-alt me-2"></i> Login
            </a>
            <a href="{{ route('register') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                <i class="fas fa-user-plus me-2"></i> Register
            </a>
        @endauth
    </div>
</div>

