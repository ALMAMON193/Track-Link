<header class="bg-white shadow-md border-b border-gray-200">
    <div class="flex items-center justify-between px-4 lg:px-6 py-4">
        <!-- Left -->
        <div class="flex items-center space-x-4">
            <button id="sidebarToggle"
                class="lg:hidden p-2 text-gray-600 hover:text-gray-900 rounded-lg hover:bg-gray-100">
                <i class="fas fa-bars text-xl"></i>
            </button>
            <div>
                <h2 class="text-xl lg:text-2xl font-semibold">Dashboard</h2>
                <p class="text-sm text-text-muted hidden sm:block">Welcome back, {{ Auth::user()->name ?? 'Admin' }}</p>
            </div>
        </div>

        <!-- Right -->
        <div class="flex items-center space-x-2 lg:space-x-4">
            <!-- Search -->
            {{-- <div class="relative hidden md:block">
                <input type="text" placeholder="Search everything..."
                    class="search-input w-48 lg:w-64 pl-8 lg:pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-primary focus:border-transparent transition-all duration-200">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div> --}}

            <!-- Notifications -->
            {{-- <div class="relative group">
                <button class="p-2 text-gray-600 hover:text-gray-900 rounded-lg hover:bg-gray-100 relative">
                    <i class="fas fa-bell text-xl"></i>
                    <span
                        class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">3</span>
                </button>
                <!-- Notification dropdown -->
                <div
                    class="absolute right-0 mt-2 w-72 lg:w-80 bg-white rounded-lg shadow-lg border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                    <div class="p-4 border-b border-gray-100">
                        <h3 class="text-sm font-semibold">Notifications</h3>
                    </div>
                    <div class="max-h-64 overflow-y-auto">
                        <div class="p-3 hover:bg-gray-50 border-b border-gray-100 flex items-start space-x-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-blue-600 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-900">New user registered</p>
                                <p class="text-xs text-text-muted">2 minutes ago</p>
                            </div>
                        </div>
                        <div class="p-3 hover:bg-gray-50 border-b border-gray-100 flex items-start space-x-3">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-shopping-cart text-green-600 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-900">New order received</p>
                                <p class="text-xs text-text-muted">5 minutes ago</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-3 border-t border-gray-100">
                        <a wire:navigate href="#" class="text-sm text-primary hover:text-primary-dark">View
                            all</a>
                    </div>
                </div>
            </div> --}}

            <!-- Profile Dropdown -->
            <div class="relative group">
                <!-- Trigger Button -->
                <button
                    class="flex items-center space-x-2 lg:space-x-3 px-2 lg:px-3 py-2 rounded-xl bg-white shadow-sm hover:shadow-md hover:bg-gray-50 transition duration-200">
                    <!-- User Image -->
                    <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=32&h=32&fit=crop&crop=face"
                        alt="User"
                        class="w-8 h-8 lg:w-9 lg:h-9 rounded-full object-cover border-2 border-gray-200 group-hover:border-primary transition">

                    <!-- Name & Email (small screen e hide) -->
                    <div class="hidden sm:block text-left">
                        <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name ?? 'Super Admin' }}</p>
                        <p class="text-xs text-gray-500">{{ Auth::user()->user_type ?? 'admin' }}</p>
                    </div>

                    <!-- Dropdown Icon -->
                    <i
                        class="fas fa-chevron-down text-gray-400 text-xs transition-transform duration-200 group-hover:rotate-180 hidden sm:block"></i>
                </button>

                <!-- Dropdown Menu -->
                <div
                    class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-100 transform opacity-0 scale-95 group-hover:scale-100 group-hover:opacity-100 invisible group-hover:visible transition-all duration-200 z-50">
                    <div class="py-2">
                        <!-- User Info -->
                        <div class="px-4 py-3 border-b border-gray-100">
                            <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name ?? 'admin' }}</p>
                            <p class="text-xs text-gray-500">{{ Auth::user()->email ?? 'admin@example.com' }}</p>
                        </div>

                        <!-- Menu Items -->

                        <a wire:navigate href="{{ route('settings') }}"
                            class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary transition">
                            <i class="fas fa-cog w-5 mr-2 text-gray-400"></i> Settings
                        </a>
                        <livewire:auth.logout-component />

                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
