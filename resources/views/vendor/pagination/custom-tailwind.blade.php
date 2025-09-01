@if ($paginator->hasPages())
    <nav class="flex justify-center mt-4" role="navigation" aria-label="Pagination Navigation">
        <ul class="inline-flex items-center border rounded-xl overflow-hidden text-sm shadow-sm bg-white">
            {{-- First --}}
            @if ($paginator->currentPage() > 1)
                <li>
                    <button wire:click="gotoPage(1)" class="px-3 py-2 hover:bg-gray-100 border-r text-gray-700">
                        &laquo;
                    </button>
                </li>
            @endif

            {{-- Previous --}}
            <li>
                @if ($paginator->onFirstPage())
                    <span class="px-3 py-2 text-gray-400 border-r">&lsaquo;</span>
                @else
                    <button wire:click="previousPage" class="px-3 py-2 hover:bg-gray-100 border-r text-gray-700">
                        &lsaquo;
                    </button>
                @endif
            </li>

            {{-- Page Numbers Logic --}}
            @php
                $total = $paginator->lastPage();
                $current = $paginator->currentPage();
                $visiblePages = 5;

                $half = floor($visiblePages / 2);
                $start = max(1, $current - $half);
                $end = min($total, $start + $visiblePages - 1);

                if ($end - $start + 1 < $visiblePages) {
                    $start = max(1, $end - $visiblePages + 1);
                }
            @endphp

            @for ($i = $start; $i <= $end; $i++)
                <li>
                    @if ($i === $current)
                        <span
                            class="px-4 py-2 font-bold text-blue-700 border-t border-b border-blue-500 bg-gray-100">
                            {{ $i }}
                        </span>
                    @else
                        <button wire:click="gotoPage({{ $i }})"
                            class="px-4 py-2 hover:bg-gray-100 text-gray-700 border-x">
                            {{ $i }}
                        </button>
                    @endif
                </li>
            @endfor

            {{-- Next --}}
            <li>
                @if ($paginator->hasMorePages())
                    <button wire:click="nextPage" class="px-3 py-2 hover:bg-gray-100 border-l text-gray-700">
                        &rsaquo;
                    </button>
                @else
                    <span class="px-3 py-2 text-gray-400 border-l">&rsaquo;</span>
                @endif
            </li>

            {{-- Last --}}
            @if ($paginator->currentPage() < $paginator->lastPage())
                <li>
                    <button wire:click="gotoPage({{ $paginator->lastPage() }})"
                        class="px-3 py-2 hover:bg-gray-100 border-l text-gray-700">
                        &raquo;
                    </button>
                </li>
            @endif
        </ul>
    </nav>
@endif
