<div class="p-4 lg:p-6 border-b border-gray-200">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h3 class="text-lg lg:text-xl font-bold text-gray-800">{{ $title }}</h3>
            <p class="text-sm text-gray-600 mt-1">
                Total <span class="font-semibold">{{ $total }}</span> {{ Str::lower($title) }} found
            </p>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
            <!-- Search -->
            <div class="relative flex-1 sm:w-48 lg:w-64">
                <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 lg:w-5 lg:h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z" />
                    </svg>
                </span>
                <input type="text"
                       wire:model.live.debounce.300ms="search"
                       placeholder="{{ $searchPlaceholder }}"
                       class="w-full pl-9 lg:pl-10 pr-4 py-2 lg:py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-1 focus:outline-none focus:ring-blue-500 focus:border-transparent" />
            </div>

            <!-- Status Filter -->
            @if($showStatus && !empty($statusOptions))
                <div class="relative flex-1 sm:w-32 lg:w-48">
                    <select wire:model.live="statusFilter"
                            class="w-full pl-3 lg:pl-4 pr-8 lg:pr-10 py-2 lg:py-2.5 text-sm border border-gray-300 rounded-lg text-gray-700 focus:ring-1 focus:outline-none focus:ring-blue-500 focus:border-transparent appearance-none bg-white">
                        <option value="">All Status</option>
                        @foreach($statusOptions as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <!-- Per Page -->
            <div class="relative w-full sm:w-20 lg:w-40">
                <select wire:model.live="perPage"
                        class="w-full pl-3 lg:pl-4 pr-8 lg:pr-10 py-2 lg:py-2.5 text-sm border border-gray-300 rounded-lg text-gray-700 focus:ring-1 focus:outline-none focus:ring-blue-500 focus:border-transparent appearance-none bg-white">
                    @foreach($perPageOptions as $option)
                        <option value="{{ $option }}">{{ $option }}</option>
                    @endforeach
                </select>
            </div>

            @if($addNewRoute)
                <x-link-button
                    :href="$addNewRoute"
                    icon="plus"
                    color="blue"
                    navigate="true"
                >
                    Add New
                </x-link-button>
            @endif
        </div>
    </div>
</div>
