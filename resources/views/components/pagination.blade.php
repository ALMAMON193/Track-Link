<div class="px-4 pt-5 pb-5 lg:px-6 py-2 bg-white flex flex-col sm:flex-row items-center justify-between gap-2 border-t border-gray-100">
    <div class="text-xs font-medium text-gray-600 text-center sm:text-left">
        Showing {{ $paginator->firstItem() ?? 0 }} to {{ $paginator->lastItem() ?? 0 }} of {{ $paginator->total() }} entries
    </div>

    <div class="flex flex-wrap items-center justify-center gap-1">
        {{-- First Page --}}
        @if ($paginator->onFirstPage())
            <button disabled class="px-3 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-400 cursor-not-allowed transition-colors duration-200">First</button>
        @else
            <button wire:click.prevent="gotoPage(1)" class="px-3 py-1 text-xs font-medium rounded-full bg-white border border-gray-300 hover:bg-gray-50 transition-colors duration-200">First</button>
        @endif

        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <button disabled class="px-3 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-400 cursor-not-allowed transition-colors duration-200">Previous</button>
        @else
            <button wire:click.prevent="previousPage" class="px-3 py-1 text-xs font-medium rounded-full bg-white border border-gray-300 hover:bg-gray-50 transition-colors duration-200">Previous</button>
        @endif

        {{-- Page Numbers --}}
        @foreach ($pageRange as $page)
            @if ($page == $paginator->currentPage())
                <button class="px-3 py-1 text-xs font-medium rounded-full bg-purple-600 text-white transition-colors duration-200">{{ $page }}</button>
            @else
                <button wire:click.prevent="gotoPage({{ $page }})" class="px-3 py-1 text-xs font-medium rounded-full bg-white border border-gray-300 hover:bg-gray-50 transition-colors duration-200">{{ $page }}</button>
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <button wire:click.prevent="nextPage" class="px-3 py-1 text-xs font-medium rounded-full bg-white border border-gray-300 hover:bg-gray-50 transition-colors duration-200">Next</button>
        @else
            <button disabled class="px-3 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-400 cursor-not-allowed transition-colors duration-200">Next</button>
        @endif

        {{-- Last Page --}}
        @if ($paginator->currentPage() == $paginator->lastPage())
            <button disabled class="px-3 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-400 cursor-not-allowed transition-colors duration-200">Last</button>
        @else
            <button wire:click.prevent="gotoPage({{ $paginator->lastPage() }})" class="px-3 py-1 text-xs font-medium rounded-full bg-white border border-gray-300 hover:bg-gray-50 transition-colors duration-200">Last</button>
        @endif
    </div>
</div>
