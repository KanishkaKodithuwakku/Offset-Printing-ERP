<div class="space-y-4">
    <div class="my-4">
        @if (session('success'))
        <div
            class="rounded-xl border border-success-500 bg-success-50 p-4 dark:border-success-500/30 dark:bg-success-500/15">
            <div class="flex items-start gap-3">
                <div class="-mt-0.5 text-success-500">
                    <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M3.70186 12.0001C3.70186 7.41711 7.41711 3.70186 12.0001 3.70186C16.5831 3.70186 20.2984 7.41711 20.2984 12.0001C20.2984 16.5831 16.5831 20.2984 12.0001 20.2984C7.41711 20.2984 3.70186 16.5831 3.70186 12.0001ZM12.0001 1.90186C6.423 1.90186 1.90186 6.423 1.90186 12.0001C1.90186 17.5772 6.423 22.0984 12.0001 22.0984C17.5772 22.0984 22.0984 17.5772 22.0984 12.0001C22.0984 6.423 17.5772 1.90186 12.0001 1.90186ZM15.6197 10.7395C15.9712 10.388 15.9712 9.81819 15.6197 9.46672C15.2683 9.11525 14.6984 9.11525 14.347 9.46672L11.1894 12.6243L9.6533 11.0883C9.30183 10.7368 8.73198 10.7368 8.38051 11.0883C8.02904 11.4397 8.02904 12.0096 8.38051 12.3611L10.553 14.5335C10.7217 14.7023 10.9507 14.7971 11.1894 14.7971C11.428 14.7971 11.657 14.7023 11.8257 14.5335L15.6197 10.7395Z"
                            fill="" />
                    </svg>
                </div>
                <div>
                    <h4 class="mb-1 text-sm font-semibold text-gray-800 dark:text-white/90">
                        Success
                    </h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        </div>
        @elseif (session('error'))
        <div class="rounded-xl border border-error-500 bg-error-50 p-4 dark:border-red-500/30 dark:bg-red-500/10">
            <div class="flex items-start gap-3">
                <div class="-mt-0.5 text-red-500">
                    <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M12 3C7.031 3 3 7.031 3 12s4.031 9 9 9 9-4.031 9-9-4.031-9-9-9Zm0 16c-3.866 0-7-3.134-7-7s3.134-7 7-7 7 3.134 7 7-3.134 7-7 7Zm-.75-11a.75.75 0 0 1 1.5 0v4.5a.75.75 0 0 1-1.5 0V8Zm0 6.75a.75.75 0 1 1 1.5 0 .75.75 0 0 1-1.5 0Z"
                            fill="" />
                    </svg>
                </div>
                <div>
                    <h4 class="mb-1 text-sm font-semibold text-gray-800 dark:text-white/90">
                        Error
                    </h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ session('error') }}
                    </p>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div
        class="w-full max-w-4xl p-6 rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] ">

        <!-- Filters -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">


            <!-- Item Search -->
            <div class=" mb-1">
                <label class="block text-xs text-gray-700 font-medium">Search Item <span
                        class="text-error-500">*</span></label>
                <input type="text" wire:model.live.throttle.150ms="searchTerm" placeholder="Search items..."
                    class="dark:bg-dark-900 mt-1 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8  rounded-lg border border-gray-300 bg-transparent px-4 py-1.5 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 "
                    style="width: 27%" />

                <!-- Search Results -->
                @if (!empty($searchResults))
                <div class="max-w-full overflow-x-auto custom-scrollbar border">
                    <table class="w-full">
                        <thead>
                            <tr class="border-t border-gray-100 dark:border-gray-800">
                                <th class="px-3 py-3 text-left">
                                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                        Item
                                    </p>
                                </th>
                                <th class="px-6 py-3 text-left">
                                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                        Code
                                    </p>
                                </th>
                                <th class="px-6 py-3 text-left">
                                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                        Stock
                                    </p>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($searchResults as $item)
                            <tr wire:click="setItemId({{ $item['id'] }})" @if ($item['stock_balance'] !=0)
                                class="border-t border-gray-100 dark:border-gray-800 cursor-pointer hover:bg-error-200"
                                @else
                                class="border-t border-gray-100 dark:border-gray-800 opacity-50 cursor-not-allowed hover:bg-error-50 bg-error-50"
                                @endif>
                                <td class="px-2 py-3">
                                    <p class="font-medium text-gray-500 text-theme-xs dark:text-white/90">
                                        {{ $item['item_name'] }}
                                    </p>
                                </td>
                                <td class="px-6 py-3">
                                    <p class="text-gray-500 text-theme-xs dark:text-gray-400">
                                        {{ $item['item_code'] }}</p>
                                </td>
                                <td class="px-6 py-3">
                                    <p class="text-gray-500 text-theme-xs dark:text-gray-400">
                                        {{ $item['stock_balance'] }}
                                    </p>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>

            <!-- Movement type -->
            <div>
                <label class="block text-xs text-gray-700 font-medium">Movement Type</label>
                <select wire:model.change="movementType"
                    class="mt-1 dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8  rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                    style="width: 27%">
                    <option value=""> All Types </option>
                    <option value="adjustment">Adjustment</option>
                    <option value="grn">GRN</option>
                    <option value="dispatch">Dispatch</option>
                    <option value="stock_transfer">Stock Transfer</option>
                </select>
            </div>

            <div class="flex items-center mb-4">
                <label for="pagination-toggle" class="text-sm text-gray-700 mr-2">Enable Pagination:</label>
                <input type="checkbox" id="pagination-toggle" wire:model.change="paginationEnabled"
                    class="form-checkbox">
            </div>

            <!-- Date range -->
            <div class="flex  gap-4">
                <div class="relative">
                    <label class="block text-xs text-gray-700 font-medium">From</label>
                    <input type="date" wire:model.change="startDate" onclick="this.showPicker()"
                        class="dark:bg-dark-900  datepickerTwo shadow-theme-xs w-full focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8  appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 pl-4 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                    <span
                        class="pointer-events-none mt-2 absolute top-1/2 right-3 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                        <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M6.66659 1.5415C7.0808 1.5415 7.41658 1.87729 7.41658 2.2915V2.99984H12.5833V2.2915C12.5833 1.87729 12.919 1.5415 13.3333 1.5415C13.7475 1.5415 14.0833 1.87729 14.0833 2.2915V2.99984L15.4166 2.99984C16.5212 2.99984 17.4166 3.89527 17.4166 4.99984V7.49984V15.8332C17.4166 16.9377 16.5212 17.8332 15.4166 17.8332H4.58325C3.47868 17.8332 2.58325 16.9377 2.58325 15.8332V7.49984V4.99984C2.58325 3.89527 3.47868 2.99984 4.58325 2.99984L5.91659 2.99984V2.2915C5.91659 1.87729 6.25237 1.5415 6.66659 1.5415ZM6.66659 4.49984H4.58325C4.30711 4.49984 4.08325 4.7237 4.08325 4.99984V6.74984H15.9166V4.99984C15.9166 4.7237 15.6927 4.49984 15.4166 4.49984H13.3333H6.66659ZM15.9166 8.24984H4.08325V15.8332C4.08325 16.1093 4.30711 16.3332 4.58325 16.3332H15.4166C15.6927 16.3332 15.9166 16.1093 15.9166 15.8332V8.24984Z"
                                fill="" />
                        </svg>
                    </span>
                </div>
                <div class="relative">
                    <label class="block text-xs text-gray-700 font-medium">To</label>
                    <input type="date" wire:model.change="endDate" onclick="this.showPicker()"
                        class="dark:bg-dark-900  datepickerTwo shadow-theme-xs w-full focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8  appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 pl-4 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                    <span
                        class="pointer-events-none mt-2 absolute top-1/2 right-3 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                        <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M6.66659 1.5415C7.0808 1.5415 7.41658 1.87729 7.41658 2.2915V2.99984H12.5833V2.2915C12.5833 1.87729 12.919 1.5415 13.3333 1.5415C13.7475 1.5415 14.0833 1.87729 14.0833 2.2915V2.99984L15.4166 2.99984C16.5212 2.99984 17.4166 3.89527 17.4166 4.99984V7.49984V15.8332C17.4166 16.9377 16.5212 17.8332 15.4166 17.8332H4.58325C3.47868 17.8332 2.58325 16.9377 2.58325 15.8332V7.49984V4.99984C2.58325 3.89527 3.47868 2.99984 4.58325 2.99984L5.91659 2.99984V2.2915C5.91659 1.87729 6.25237 1.5415 6.66659 1.5415ZM6.66659 4.49984H4.58325C4.30711 4.49984 4.08325 4.7237 4.08325 4.99984V6.74984H15.9166V4.99984C15.9166 4.7237 15.6927 4.49984 15.4166 4.49984H13.3333H6.66659ZM15.9166 8.24984H4.08325V15.8332C4.08325 16.1093 4.30711 16.3332 4.58325 16.3332H15.4166C15.6927 16.3332 15.9166 16.1093 15.9166 15.8332V8.24984Z"
                                fill="" />
                        </svg>
                    </span>
                </div>

                <div class="relative flex justify-end w-full">
                    <button onclick="printPreview()" class="bg-brand-500 text-white py-1 px-4 rounded-lg">
                        Print Preview
                    </button>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto mt-6" id="printable-area">
            <table class="min-w-full border-t border-gray-300 rounded-lg divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-300">
                            Movement
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-300">
                            Job Order Number
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-300">
                            Item
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-300">
                            Qty
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-300">
                            Previous Balance
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-300">
                            Current Balance
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-300">
                            Last Movement
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($movements as $m)
                    <tr class="hover:bg-gray-200 transition-colors duration-150 @if(strtolower($m->table_name) == 'grn') bg-success-50 @endif @if(strtolower($m->table_name) == 'dispatch') bg-error-50 @endif"">
                        <td class=" px-6 py-2 whitespace-nowrap text-xs text-gray-700 border-b border-gray-300">
                        <p>{{ strtoupper($m->table_name) }} </p>
                        {{-- <p class="text-xs text-gray-200"> {{ $m->p_id }} </p> --}}

                        </td>
                        <td class="px-6 py-2 whitespace-nowrap text-xs text-gray-700 border-b border-gray-300">

                            {{ $m->job_order_number ?? '—' }}

                        </td>

                        <td class="px-6 py-2 whitespace-nowrap text-xs text-gray-700 border-b border-gray-300">
                            {{ $m->item_name }}
                            <!-- Use item_name directly from query -->
                        </td>
                        <td class="px-6 py-2 whitespace-nowrap text-xs text-gray-700 border-b border-gray-300">
                            {{ $m->total_quantity }}
                        </td>
                        <td
                            class="px-6 py-2 whitespace-nowrap text-xs text-gray-700 border-b border-gray-300 text-center">
                            {{ $m->previous_balance }}
                        </td>
                        <td
                            class="px-6 py-2 whitespace-nowrap text-xs text-gray-700 border-b border-gray-300 text-center">
                            {{ $m->current_balance }}
                        </td>
                        <td
                            class="px-6 py-2 whitespace-nowrap text-xs text-gray-700 border-b border-gray-300 text-center">
                            {{ \Carbon\Carbon::parse($m->last_movement_date)->format('Y-m-d H:i:s') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-2 text-center text-gray-500 border-b border-gray-300">
                            No movements found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>


        <!-- Pagination -->
        <div class="flex justify-between items-center border-t px-6 py-4 dark:border-gray-800">
            @if ($paginationEnabled)
            <div class="text-sm text-gray-600 dark:text-gray-400">
                Showing {{ $movements->firstItem() }} to {{ $movements->lastItem() }} of
                {{ $movements->total() }} entries
            </div>
            @endif

            <div class="pagination flex justify-between">
                @if ($paginationEnabled)
                <div class="pagination flex justify-between">
                    {{ $movements->links('vendor.pagination.custom-tailwind') }}
                </div>
                @endif
            </div>
        </div>


    </div>

    <script>
        function printPreview() {
    const content = document.getElementById('printable-area').innerHTML;
    const win = window.open('', '_blank', 'width=800,height=600');
    win.document.write(`
        <html>
        <head>
            <title>Print Preview</title>
            <style>
                @page { size: A4 portrait; margin: 10mm; }
                body { font-family: Arial, Helvetica, sans-serif; margin: 0; padding: 0; }
                table { width: 100%; border-collapse: collapse; font-size: 12px; }
                th, td { border: 1px solid #e5e7eb; padding: 4px; }
                thead { display: table-header-group; }
                tfoot { display: table-footer-group; }
                tr { page-break-inside: avoid; page-break-after: auto; }
                .no-print { display: none; }
            </style>
        </head>
        <body>
            <div id="printable-area">
                ${content}
            </div>
        </body>
        </html>
    `);
    win.document.close();
    win.focus();
    win.print();
    win.close();
}
    </script>
</div>