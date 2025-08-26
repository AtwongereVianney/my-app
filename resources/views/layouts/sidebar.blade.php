<!-- Sidebar Toggle Button -->
<button id="sidebarToggle" class="fixed top-4 left-4 z-50 p-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 shadow-lg transition-all duration-200 lg:hidden" aria-label="Open sidebar menu">
    <i id="toggleIcon" class="fas fa-bars text-lg"></i>
</button>

<!-- Sidebar Container -->
<div id="sidebar" class="fixed inset-y-0 left-0 z-40 w-72 transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0">
    <!-- Overlay for mobile -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 lg:hidden opacity-0 pointer-events-none transition-opacity duration-300"></div>
    
    <!-- Sidebar Content -->
    <div class="relative flex flex-col w-72 h-full bg-gradient-to-b from-gray-50 to-white shadow-xl border-r border-gray-200">
        <!-- Header with Close Button -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-white">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-building text-white text-sm"></i>
                </div>
                <h2 class="text-lg font-semibold text-gray-800">Hostel Manager</h2>
            </div>
            <button id="sidebarClose" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors duration-200 lg:hidden" aria-label="Close sidebar">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        
        <!-- Scrollable Content -->
        <div class="flex-1 overflow-y-auto">
            <!-- Menu Section -->
            <div class="p-4">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-4 py-3 border-b border-gray-100">
                        <h3 class="font-medium text-gray-700 text-sm uppercase tracking-wide">
                            <i class="fas fa-compass mr-2 text-blue-500"></i> Navigation
                        </h3>
                    </div>
                    <nav class="py-2">
                        <a href="{{ route('dashboard') }}" class="group flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-700 {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-500 font-medium' : '' }} transition-all duration-200 relative">
                            <div class="flex items-center justify-center w-8 h-8 mr-3 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-blue-100' : 'bg-gray-100 group-hover:bg-blue-100' }} transition-colors duration-200">
                                <i class="fas fa-gauge text-sm"></i>
                            </div>
                            <span>Dashboard</span>
                            @if(request()->routeIs('dashboard'))
                                <i class="fas fa-chevron-right ml-auto text-xs"></i>
                            @endif
                        </a>
                        <a href="{{ route('rooms.index') }}" class="group flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-700 {{ request()->routeIs('rooms.*') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-500 font-medium' : '' }} transition-all duration-200">
                            <div class="flex items-center justify-center w-8 h-8 mr-3 rounded-lg {{ request()->routeIs('rooms.*') ? 'bg-blue-100' : 'bg-gray-100 group-hover:bg-blue-100' }} transition-colors duration-200">
                                <i class="fas fa-bed text-sm"></i>
                            </div>
                            <span>Rooms</span>
                            @if(request()->routeIs('rooms.*'))
                                <i class="fas fa-chevron-right ml-auto text-xs"></i>
                            @endif
                        </a>
                        <a href="{{ route('students.index') }}" class="group flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-700 {{ request()->routeIs('students.*') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-500 font-medium' : '' }} transition-all duration-200">
                            <div class="flex items-center justify-center w-8 h-8 mr-3 rounded-lg {{ request()->routeIs('students.*') ? 'bg-blue-100' : 'bg-gray-100 group-hover:bg-blue-100' }} transition-colors duration-200">
                                <i class="fas fa-users text-sm"></i>
                            </div>
                            <span>Students</span>
                            @if(request()->routeIs('students.*'))
                                <i class="fas fa-chevron-right ml-auto text-xs"></i>
                            @endif
                        </a>
                        <a href="{{ route('bookings.index') }}" class="group flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-700 {{ request()->routeIs('bookings.*') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-500 font-medium' : '' }} transition-all duration-200">
                            <div class="flex items-center justify-center w-8 h-8 mr-3 rounded-lg {{ request()->routeIs('bookings.*') ? 'bg-blue-100' : 'bg-gray-100 group-hover:bg-blue-100' }} transition-colors duration-200">
                                <i class="fas fa-calendar-check text-sm"></i>
                            </div>
                            <span>Bookings</span>
                            @if(request()->routeIs('bookings.*'))
                                <i class="fas fa-chevron-right ml-auto text-xs"></i>
                            @endif
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Payments Section -->
            <div class="px-4 pb-4">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-4 py-3 border-b border-gray-100">
                        <h3 class="font-medium text-gray-700 text-sm uppercase tracking-wide">
                            <i class="fas fa-money-bill-wave mr-2 text-green-500"></i> Financial
                        </h3>
                    </div>
                    <nav class="py-2">
                        <a href="{{ route('payments.index') }}" class="group flex items-center px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-700 {{ request()->routeIs('payments.index') ? 'bg-green-50 text-green-700 border-r-4 border-green-500 font-medium' : '' }} transition-all duration-200">
                            <div class="flex items-center justify-center w-8 h-8 mr-3 rounded-lg {{ request()->routeIs('payments.index') ? 'bg-green-100' : 'bg-gray-100 group-hover:bg-green-100' }} transition-colors duration-200">
                                <i class="fas fa-money-bill text-sm"></i>
                            </div>
                            <span>Payments</span>
                            @if(request()->routeIs('payments.index'))
                                <i class="fas fa-chevron-right ml-auto text-xs"></i>
                            @endif
                        </a>
                        <a href="{{ route('payments.statistics') }}" class="group flex items-center px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-700 {{ request()->routeIs('payments.statistics') ? 'bg-green-50 text-green-700 border-r-4 border-green-500 font-medium' : '' }} transition-all duration-200">
                            <div class="flex items-center justify-center w-8 h-8 mr-3 rounded-lg {{ request()->routeIs('payments.statistics') ? 'bg-green-100' : 'bg-gray-100 group-hover:bg-green-100' }} transition-colors duration-200">
                                <i class="fas fa-chart-bar text-sm"></i>
                            </div>
                            <span>Statistics</span>
                            @if(request()->routeIs('payments.statistics'))
                                <i class="fas fa-chevron-right ml-auto text-xs"></i>
                            @endif
                        </a>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Authentication Section (Fixed at bottom) -->
        <div class="p-4 border-t border-gray-200 bg-white">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                @auth
                    <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-4 py-3 border-b border-gray-100">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-user text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500">Administrator</p>
                            </div>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="p-2">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-3 py-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-200 text-left group">
                            <div class="flex items-center justify-center w-8 h-8 mr-3 rounded-lg bg-gray-100 group-hover:bg-red-100 transition-colors duration-200">
                                <i class="fas fa-sign-out-alt text-sm"></i>
                            </div>
                            <span>Logout</span>
                        </button>
                    </form>
                @else
                    <div class="p-2 space-y-1">
                        <a href="{{ route('login') }}" class="group flex items-center px-3 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-700 rounded-lg transition-colors duration-200">
                            <div class="flex items-center justify-center w-8 h-8 mr-3 rounded-lg bg-gray-100 group-hover:bg-blue-100 transition-colors duration-200">
                                <i class="fas fa-sign-in-alt text-sm"></i>
                            </div>
                            <span>Login</span>
                        </a>
                        <a href="{{ route('register') }}" class="group flex items-center px-3 py-2 text-gray-700 hover:bg-green-50 hover:text-green-700 rounded-lg transition-colors duration-200">
                            <div class="flex items-center justify-center w-8 h-8 mr-3 rounded-lg bg-gray-100 group-hover:bg-green-100 transition-colors duration-200">
                                <i class="fas fa-user-plus text-sm"></i>
                            </div>
                            <span>Register</span>
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>

<!-- Toast notification for mobile users -->
<div id="mobileToast" class="fixed top-20 left-4 right-4 bg-blue-600 text-white px-4 py-3 rounded-lg shadow-lg transform -translate-y-full opacity-0 transition-all duration-300 z-30 lg:hidden">
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <i class="fas fa-info-circle mr-2"></i>
            <span class="text-sm">Swipe or tap to navigate</span>
        </div>
        <button id="closeToast" class="ml-4 text-white hover:text-blue-200">
            <i class="fas fa-times text-sm"></i>
        </button>
    </div>
</div>

<style>
/* Custom scrollbar for sidebar */
#sidebar .overflow-y-auto::-webkit-scrollbar {
    width: 4px;
}

#sidebar .overflow-y-auto::-webkit-scrollbar-track {
    background: #f1f5f9;
}

#sidebar .overflow-y-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 2px;
}

#sidebar .overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Smooth focus styles */
a:focus, button:focus {
    outline: 2px solid #3b82f6;
    outline-offset: 2px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarClose = document.getElementById('sidebarClose');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const toggleIcon = document.getElementById('toggleIcon');
    const mobileToast = document.getElementById('mobileToast');
    const closeToast = document.getElementById('closeToast');
    
    let isOpen = false;
    let hasShownToast = localStorage.getItem('hasShownSidebarToast') === 'true';

    function openSidebar() {
        sidebar.classList.remove('-translate-x-full');
        sidebarOverlay.classList.remove('opacity-0', 'pointer-events-none');
        sidebarOverlay.classList.add('opacity-100');
        toggleIcon.classList.remove('fa-bars');
        toggleIcon.classList.add('fa-times');
        document.body.style.overflow = 'hidden';
        isOpen = true;
        
        // Show toast on first mobile use
        if (!hasShownToast && window.innerWidth < 1024) {
            setTimeout(showToast, 500);
        }
    }

    function closeSidebar() {
        sidebar.classList.add('-translate-x-full');
        sidebarOverlay.classList.remove('opacity-100');
        sidebarOverlay.classList.add('opacity-0', 'pointer-events-none');
        toggleIcon.classList.remove('fa-times');
        toggleIcon.classList.add('fa-bars');
        document.body.style.overflow = '';
        isOpen = false;
    }

    function showToast() {
        mobileToast.classList.remove('-translate-y-full', 'opacity-0');
        mobileToast.classList.add('translate-y-0', 'opacity-100');
        
        setTimeout(() => {
            hideToast();
        }, 3000);
    }

    function hideToast() {
        mobileToast.classList.remove('translate-y-0', 'opacity-100');
        mobileToast.classList.add('-translate-y-full', 'opacity-0');
        localStorage.setItem('hasShownSidebarToast', 'true');
        hasShownToast = true;
    }

    // Event listeners
    sidebarToggle.addEventListener('click', function(e) {
        e.stopPropagation();
        if (isOpen) {
            closeSidebar();
        } else {
            openSidebar();
        }
    });

    sidebarClose.addEventListener('click', closeSidebar);
    sidebarOverlay.addEventListener('click', closeSidebar);
    closeToast.addEventListener('click', hideToast);

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        const isClickInsideSidebar = sidebar.contains(event.target);
        const isClickOnToggle = sidebarToggle.contains(event.target);
        
        if (!isClickInsideSidebar && !isClickOnToggle && window.innerWidth < 1024 && isOpen) {
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

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && isOpen) {
            closeSidebar();
        }
    });

    // Touch gestures for mobile
    let touchStartX = 0;
    let touchEndX = 0;

    document.addEventListener('touchstart', function(e) {
        touchStartX = e.changedTouches[0].screenX;
    });

    document.addEventListener('touchend', function(e) {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    });

    function handleSwipe() {
        const swipeThreshold = 100;
        const diff = touchStartX - touchEndX;
        
        if (window.innerWidth < 1024) {
            // Swipe right to open (from left edge)
            if (touchStartX < 50 && diff < -swipeThreshold && !isOpen) {
                openSidebar();
            }
            // Swipe left to close
            else if (diff > swipeThreshold && isOpen) {
                closeSidebar();
            }
        }
    }

    // Add loading states for navigation links
    document.querySelectorAll('a[href^="{{ route("]').forEach(link => {
        link.addEventListener('click', function() {
            const icon = this.querySelector('i');
            const originalClass = icon.className;
            icon.className = 'fas fa-spinner fa-spin text-sm';
            
            setTimeout(() => {
                icon.className = originalClass;
            }, 2000);
        });
    });
});
</script>