<!-- Sidebar Toggle Button (Mobile/Desktop) -->
<button id="sidebarToggle" class="fixed top-4 left-4 z-50 p-2 bg-gray-800 text-white rounded-md hover:bg-gray-700 transition-colors duration-200 lg:hidden">
    <i class="fas fa-bars text-lg"></i>
</button>

<!-- Sidebar Container -->
<div id="sidebar" class="fixed inset-y-0 left-0 z-40 w-64 transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0">
    <!-- Overlay for mobile -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 lg:hidden opacity-0 pointer-events-none transition-opacity duration-300"></div>
    
    <!-- Sidebar Content -->
    <div class="relative flex flex-col w-64 h-full bg-white shadow-lg">
        <!-- Close button for mobile -->
        <button id="sidebarClose" class="absolute top-4 right-4 p-2 text-gray-500 hover:text-gray-700 lg:hidden">
            <i class="fas fa-times text-lg"></i>
        </button>
        
        <!-- Menu Card -->
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm m-4">
            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 rounded-t-lg">
                <h3 class="font-semibold text-gray-800">
                    <i class="fas fa-bars mr-2"></i> Menu
                </h3>
            </div>
            <div class="py-2">
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-500' : '' }} transition-colors duration-200">
                    <i class="fas fa-gauge mr-3"></i> Dashboard
                </a>
                <a href="{{ route('rooms.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 {{ request()->routeIs('rooms.*') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-500' : '' }} transition-colors duration-200">
                    <i class="fas fa-bed mr-3"></i> Rooms
                </a>
                <a href="{{ route('students.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 {{ request()->routeIs('students.*') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-500' : '' }} transition-colors duration-200">
                    <i class="fas fa-users mr-3"></i> Students
                </a>
                <a href="{{ route('bookings.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 {{ request()->routeIs('bookings.*') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-500' : '' }} transition-colors duration-200">
                    <i class="fas fa-calendar-check mr-3"></i> Bookings
                </a>
                <a href="{{ route('payments.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 {{ request()->routeIs('payments.*') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-500' : '' }} transition-colors duration-200">
                    <i class="fas fa-money-bill mr-3"></i> Payments
                </a>
                <a href="{{ route('payments.statistics') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 {{ request()->routeIs('payments.statistics') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-500' : '' }} transition-colors duration-200">
                    <i class="fas fa-chart-bar mr-3"></i> Payment Statistics
                </a>
            </div>
        </div>

        <!-- Authentication Section -->
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm m-4 mt-0">
            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 rounded-t-lg">
                <h3 class="font-semibold text-gray-800">
                    <i class="fas fa-user mr-2"></i> Account
                </h3>
            </div>
            <div class="py-2">
                @auth
                    <div class="px-4 py-3 text-gray-500 border-b border-gray-100">
                        <i class="fas fa-user-circle mr-3"></i> Welcome, {{ Auth::user()->name }}
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="inline-block w-full">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-4 py-3 text-red-600 hover:bg-red-50 transition-colors duration-200 text-left">
                            <i class="fas fa-sign-out-alt mr-3"></i> Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                        <i class="fas fa-sign-in-alt mr-3"></i> Login
                    </a>
                    <a href="{{ route('register') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                        <i class="fas fa-user-plus mr-3"></i> Register
                    </a>
                @endauth
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarClose = document.getElementById('sidebarClose');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    function openSidebar() {
        sidebar.classList.remove('-translate-x-full');
        sidebarOverlay.classList.remove('opacity-0', 'pointer-events-none');
        sidebarOverlay.classList.add('opacity-100');
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        sidebar.classList.add('-translate-x-full');
        sidebarOverlay.classList.remove('opacity-100');
        sidebarOverlay.classList.add('opacity-0', 'pointer-events-none');
        document.body.style.overflow = '';
    }

    // Toggle sidebar on mobile
    sidebarToggle.addEventListener('click', openSidebar);
    sidebarClose.addEventListener('click', closeSidebar);
    sidebarOverlay.addEventListener('click', closeSidebar);

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        const isClickInsideSidebar = sidebar.contains(event.target);
        const isClickOnToggle = sidebarToggle.contains(event.target);
        
        if (!isClickInsideSidebar && !isClickOnToggle && window.innerWidth < 1024) {
            closeSidebar();
        }
    });

    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 1024) {
            closeSidebar();
            document.body.style.overflow = '';
        }
    });
});
</script>