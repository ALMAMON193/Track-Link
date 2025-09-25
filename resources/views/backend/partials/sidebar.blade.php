<!-- Mobile Overlay -->
<div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-40 lg:hidden"></div>

<!-- Mobile Menu Button -->
<button id="mobileSidebarButton" class="lg:hidden p-3 text-gray-800 bg-gray-200 rounded-md fixed top-4 left-4 z-50">
    <i class="fas fa-bars text-xl"></i>
</button>

<!-- Sidebar -->
<aside id="sidebar"
    class="fixed lg:static top-0 left-0 w-64 h-full bg-gradient-to-b from-gray-900 to-gray-800 text-white shadow-2xl transform -translate-x-full lg:translate-x-0 transition-transform duration-300 z-50 flex flex-col">

    <!-- Logo Section -->
    <div class="p-6 border-b border-gray-700/50">
        <div class="flex items-center space-x-4">
            <div
                class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-tachometer-alt text-white text-xl"></i>
            </div>

        </div>
    </div>

    <!-- Sidebar Navigation -->
    <nav class="flex-1 px-4 py-6 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-600 scrollbar-track-gray-800">
        <ul class="space-y-2">

            <!-- Dashboard -->
            <li>
                <a href="{{ route('dashboard') }}"
                    class="flex items-center px-4 py-3 rounded-lg transition-all duration-300 group bg-gradient-to-r from-blue-600/20 to-indigo-600/20 hover:from-blue-600/40 hover:to-indigo-600/40 text-gray-300">
                    <i class="fas fa-tachometer-alt w-5 mr-4 text-blue-400 group-hover:text-white"></i>
                    <span class="font-medium">Dashboard</span>
                </a>
            </li>

            <!-- Users -->
            <li>
                <a href="{{ route('users') }}"
                    class="flex items-center px-4 py-3 rounded-lg transition-all duration-300 group bg-gradient-to-r from-blue-600/20 to-indigo-600/20 hover:from-blue-600/40 hover:to-indigo-600/40 text-gray-300">
                    <i class="fas fa-users w-5 mr-4 text-blue-400 group-hover:text-white"></i>
                    <span class="font-medium">Users</span>
                </a>
            </li>

            <!-- Settings -->
            <li>
                <a href="{{ route('settings') }}"
                    class="flex items-center px-4 py-3 rounded-lg transition-all duration-300 group bg-gradient-to-r from-blue-600/20 to-indigo-600/20 hover:from-blue-600/40 hover:to-indigo-600/40 text-gray-300">
                    <i class="fas fa-cogs w-5 mr-4 text-blue-400 group-hover:text-white"></i>
                    <span class="font-medium">Settings</span>
                </a>
            </li>

        </ul>
    </nav>

    <!-- User Profile Section -->
    <div class="p-4 border-t border-gray-700/50 mt-auto">
        <div
            class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gradient-to-r hover:from-blue-600/20 hover:to-indigo-600/20 transition-all duration-300 group">
            <div class="relative">
                <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=40&h=40&fit=crop&crop=face"
                    alt="Admin" class="w-10 h-10 rounded-full object-cover ring-2 ring-blue-500/30">
                <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-green-500 rounded-full border-2 border-gray-800">
                </div>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name ?? 'Super Admin' }}</p>
                <p class="text-xs text-gray-300 truncate">{{ Auth::user()->user_type ?? 'admin' }}</p>
            </div>

        </div>
    </div>
</aside>

<!-- JS for Sidebar -->
<script>
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const mobileButton = document.getElementById('mobileSidebarButton');

    // Mobile sidebar toggle
    mobileButton.addEventListener('click', () => {
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    });

    // Overlay click closes sidebar
    overlay.addEventListener('click', () => {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
    });
</script>
