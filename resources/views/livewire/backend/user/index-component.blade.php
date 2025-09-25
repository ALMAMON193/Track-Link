<main class="flex-1 overflow-auto p-4 lg:p-6" role="main">
    <nav aria-label="breadcrumb" class="mb-4 lg:mb-6">
        <ol class="flex items-center space-x-2 text-sm text-gray-600">
            <li><a href="#" class="hover:text-blue-600 transition-colors">Home</a></li>
            <li><i class="fas fa-chevron-right text-xs"></i></li>
            <li class="text-gray-900 font-medium">Users</li>
        </ol>
    </nav>

    <div class="bg-white rounded-xl shadow-lg border border-gray-100">
        <!-- Table Header -->
        <x-table-header title="All Users" :total="$users->total()" search-placeholder="Search Users" add-new-route=""
            wire:model.debounce.500ms="search" />

        <!-- Table -->
        <div class="relative overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 lg:px-6 py-3 text-left">
                            <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </th>
                        <th
                            class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Name</th>
                        <th
                            class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Email</th>
                        <th
                            class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">
                            User Type</th>
                        <th
                            class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">
                            Verified</th>
                        <th
                            class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">
                            Status</th>
                        <th
                            class="px-4 lg:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 lg:px-6 py-3 whitespace-nowrap">
                                <input type="checkbox"
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </td>
                            <td class="px-4 lg:px-6 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $item->name }}</td>
                            <td class="px-4 lg:px-6 py-3 whitespace-nowrap text-sm text-gray-500">{{ $item->email }}
                            </td>
                            <td class="px-4 lg:px-6 py-3 whitespace-nowrap text-sm text-gray-500 hidden lg:table-cell">
                                {{ ucfirst($item->user_type) }}</td>

                            <!-- Verified Badge -->
                            <td class="px-4 lg:px-6 py-3 whitespace-nowrap text-sm hidden lg:table-cell">
                                @if ($item->is_verified)
                                    <span
                                        class="inline-flex items-center px-3 py-1 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full">
                                        <span class="w-2 h-2 mr-2 bg-green-600 rounded-full"></span>
                                        Verified
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-3 py-1 text-xs font-semibold leading-5 text-red-800 bg-red-100 rounded-full">
                                        <span class="w-2 h-2 mr-2 bg-red-600 rounded-full"></span>
                                        Unverified
                                    </span>
                                @endif
                            </td>

                            <!-- Status Badge -->
                            <td class="px-4 lg:px-6 py-3 whitespace-nowrap text-sm hidden lg:table-cell">
                                @switch($item->status)
                                    @case('pending')
                                        <span
                                            class="inline-flex items-center px-3 py-1 text-xs font-semibold leading-5 text-yellow-800 bg-yellow-100 rounded-full">
                                            <span class="w-2 h-2 mr-2 bg-yellow-600 rounded-full"></span>
                                            Pending
                                        </span>
                                    @break

                                    @case('verified')
                                        <span
                                            class="inline-flex items-center px-3 py-1 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full">
                                            <span class="w-2 h-2 mr-2 bg-green-600 rounded-full"></span>
                                            Verified
                                        </span>
                                    @break

                                    @case('unverified')
                                        <span
                                            class="inline-flex items-center px-3 py-1 text-xs font-semibold leading-5 text-red-800 bg-red-100 rounded-full">
                                            <span class="w-2 h-2 mr-2 bg-red-600 rounded-full"></span>
                                            Unverified
                                        </span>
                                    @break
                                @endswitch
                            </td>

                            <!-- Actions -->
                            <td class="px-4 lg:px-6 py-3 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('users.details', $item->id) }}"
                                        class="text-indigo-600 hover:text-indigo-900 transition-colors p-1"
                                        title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button wire:click="delete({{ $item->id }})" wire:confirm="Are you sure?"
                                        class="text-red-600 hover:text-red-900 transition-colors p-1" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 lg:px-6 py-8 text-center text-gray-500">No users found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Loading Overlay -->
                <div wire:loading.flex class="absolute inset-0 bg-white bg-opacity-70 z-10 items-center justify-center">
                    <div class="flex flex-col items-center">
                        <svg class="animate-spin h-6 w-6 text-blue-600 mb-2" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                        </svg>
                        <p class="text-gray-500">Loading users...</p>
                    </div>
                </div>
            </div>

            <!-- Footer Pagination -->
            <x-pagination :paginator="$users" :pageRange="$pageRange" />
        </div>
    </main>
