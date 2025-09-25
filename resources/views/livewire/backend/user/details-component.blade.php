<main class="flex-1 overflow-auto p-4 lg:p-6" role="main" wire:poll.5s>
    <nav aria-label="breadcrumb" class="mb-4 lg:mb-6">
        <ol class="flex items-center space-x-2 text-sm text-gray-600">
            <li><a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition-colors">Users</a></li>
            <li><i class="fas fa-chevron-right text-xs"></i></li>
            <li class="text-gray-900 font-medium">{{ $user->name }}</li>
        </ol>
    </nav>

    {{-- Success message --}}
    @if (session()->has('success'))
        <div class="mb-4 p-3 text-sm text-green-800 bg-green-100 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    {{-- Error message --}}
    @if (session()->has('error'))
        <div class="mb-4 p-3 text-sm text-red-800 bg-red-100 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    {{-- Info message --}}
    @if (session()->has('info'))
        <div class="mb-4 p-3 text-sm text-blue-800 bg-blue-100 rounded-lg">
            {{ session('info') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
        <!-- Tabs -->
        <div class="flex space-x-4 border-b mb-6">
            <button wire:click="setTab('info')"
                class="px-4 py-2 font-semibold @if ($tab === 'info') border-b-2 border-blue-600 text-blue-600 @else text-gray-600 @endif">
                User Information
            </button>
            <button wire:click="setTab('driver')"
                class="px-4 py-2 font-semibold @if ($tab === 'driver') border-b-2 border-blue-600 text-blue-600 @else text-gray-600 @endif">
                Driver Details
            </button>
            <button wire:click="setTab('experience')"
                class="px-4 py-2 font-semibold @if ($tab === 'experience') border-b-2 border-blue-600 text-blue-600 @else text-gray-600 @endif">
                Experience & Preferences
            </button>
        </div>

        <!-- Tab Content -->
        <div wire:key="tab-{{ $tab }}">
            {{-- User Info Tab --}}
            @if ($tab === 'info')
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <div><span class="font-semibold">Name:</span> {{ $user->name }}</div>
                    <div><span class="font-semibold">Email:</span> {{ $user->email }}</div>
                    <div><span class="font-semibold">User Type:</span> {{ ucfirst($user->user_type) }}</div>
                    <div>
                        <span class="font-semibold">Verified:</span>
                        @if ($user->is_verified)
                            <span
                                class="inline-flex items-center px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">
                                Verified
                            </span>
                        @else
                            <span
                                class="inline-flex items-center px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">
                                Unverified
                            </span>
                        @endif
                    </div>
                    <div>
                        <span class="font-semibold">Status:</span>
                        <span
                            class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full
                            @if ($user->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($user->status === 'verified') bg-green-100 text-green-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ ucfirst($user->status) }}
                        </span>
                    </div>
                </div>

                {{-- Verify button --}}
                <div class="mt-4">
                    @if ($user->status === 'verified')
                        <button disabled
                            class="px-4 py-2 bg-gray-300 text-gray-600 text-sm font-semibold rounded-lg cursor-not-allowed">
                            Already Verified
                        </button>
                    @else
                        <button wire:click="verifyUser"
                            class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                            Verify Now
                        </button>
                    @endif
                </div>

                {{-- Driver Tab --}}
            @elseif($tab === 'driver')
                @if ($user->driverDetail)
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <div>
                            <span class="font-semibold">Driver License:</span>
                            @if ($user->driverDetail->driver_license)
                                <a href="{{ \App\Helpers\Helper::generateURL($user->driverDetail->driver_license) }}"
                                    target="_blank" class="text-blue-600 hover:underline">
                                    View License
                                </a>
                            @else
                                N/A
                            @endif
                        </div>
                        <div><span class="font-semibold">License Number:</span>
                            {{ $user->driverDetail->license_number }}</div>
                        <div><span class="font-semibold">State of Issue:</span>
                            {{ $user->driverDetail->state_of_issue }}</div>
                        <div><span class="font-semibold">Expiration Date:</span>
                            {{ \Carbon\Carbon::parse($user->driverDetail->expiration_date)->format('M d, Y') }}
                        </div>
                    </div>
                @else
                    <p class="text-gray-500">No driver details available.</p>
                @endif

                {{-- Experience Tab --}}
            @elseif($tab === 'experience')
                @if ($user->experiencePreference)
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <div><span class="font-semibold">Experience:</span>
                            {{ $user->experiencePreference->experience }}</div>
                        <div><span class="font-semibold">Vehicle Type:</span>
                            {{ ucfirst(str_replace('_', ' ', $user->experiencePreference->vehicle_type)) }}</div>
                        <div><span class="font-semibold">Service Area:</span>
                            {{ ucfirst($user->experiencePreference->service_area) }}</div>
                        <div><span class="font-semibold">Additional Info:</span>
                            {{ $user->experiencePreference->additional_information ?? 'N/A' }}</div>
                    </div>
                @else
                    <p class="text-gray-500">No experience preferences available.</p>
                @endif
            @endif
        </div>
    </div>
</main>
