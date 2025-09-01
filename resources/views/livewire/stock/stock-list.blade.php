<div class="p-4 mx-auto max-w-screen-xl">
    <div class="space-y-5 sm:space-y-6">
        <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="mb-4 flex flex-col gap-2 px-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Stock Summary List</h3>

                <div class="flex gap-2">
                    <!-- Pagination Toggle -->
                    <div class="flex items-center mb-2">
                        <label for="pagination-toggle" class="mr-2 text-sm text-gray-700">Enable Pagination:</label>
                        <input type="checkbox" id="pagination-toggle" wire:model.change="paginationEnabled" class="form-checkbox">
                    </div>
                    <!-- Print Button -->
                    <button
                        onclick="printPreview()"
                        class="mr-2 px-4 h-8 mt-0.5 rounded-lg bg-brand-500 text-white text-sm font-semibold hover:bg-brand-500 transition"
                        type="button">

                        Print Preview
                    </button>

                    <!-- Search Input -->
                    <div class="relative">
                        <span class="pointer-events-none absolute top-1/2 left-4 -translate-y-1/2">
                            <svg class="fill-gray-500 dark:fill-gray-400" width="15" height="15"
                                viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M3.04199 9.37381C3.04199 5.87712 5.87735 3.04218 9.37533 3.04218C12.8733 3.04218 15.7087 5.87712 15.7087 9.37381C15.7087 12.8705 12.8733 15.7055 9.37533 15.7055C5.87735 15.7055 3.04199 12.8705 3.04199 9.37381ZM9.37533 1.54218C5.04926 1.54218 1.54199 5.04835 1.54199 9.37381C1.54199 13.6993 5.04926 17.2055 9.37533 17.2055C11.2676 17.2055 13.0032 16.5346 14.3572 15.4178L17.1773 18.2381C17.4702 18.531 17.945 18.5311 18.2379 18.2382C18.5308 17.9453 18.5309 17.4704 18.238 17.1775L15.4182 14.3575C16.5367 13.0035 17.2087 11.2671 17.2087 9.37381C17.2087 5.04835 13.7014 1.54218 9.37533 1.54218Z"
                                    fill="" />
                            </svg>
                        </span>
                        <input type="text" wire:model.live.debounce.100ms="searchTerm" placeholder="Search stocks..."
                            class="h-[32px] w-full rounded-lg border border-gray-300 bg-transparent py-1.5 pr-4 pl-[42px] text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden xl:w-[300px] dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                    </div>
                </div>
            </div>

            <div class="max-w-full overflow-x-auto px-5 sm:px-6" id="printable-area">
                <table class="min-w-full">
                    <thead class="border-y border-gray-100 py-2 dark:border-gray-800">
                        <tr>

                           <th class="py-2 font-normal whitespace-nowrap" wire:click="sortByitem_name')">
                                <div class="flex items-center">
                                    <p class="text-theme-xs text-gray-500 dark:text-gray-400">Item Code</p>
                                </div>
                            </th>
                            <th class="py-2 font-normal whitespace-nowrap" wire:click="sortBy('brand_name')">
                                <div class="flex items-center">
                                    <p class="text-theme-xs text-gray-500 dark:text-gray-400">Branch</p>
                                </div>
                            </th>
                            <th class="py-2 font-normal whitespace-nowrap" wire:click="sortByitem_name')">
                                <div class="flex items-center">
                                    <p class="text-theme-xs text-gray-500 dark:text-gray-400">Item</p>
                                </div>
                            </th>


                            <th class="py-2 font-normal whitespace-nowrap" wire:click="sortBy('total_qty')">
                                <div class="flex items-center">
                                    <p class="text-theme-xs text-gray-500 dark:text-gray-400">Quantity</p>
                                </div>
                            </th>
                            {{-- <th class="py-2 font-normal whitespace-nowrap" wire:click="sortBy('cost')">
                                <div class="flex items-center">
                                    <p class="text-theme-xs text-gray-500 dark:text-gray-400">Cost</p>
                                </div>
                            </th>
                            <th class="py-2 font-normal whitespace-nowrap" wire:click="sortBy('selling')">
                                <div class="flex items-center">
                                    <p class="text-theme-xs text-gray-500 dark:text-gray-400">Selling Price</p>
                                </div>
                            </th> --}}
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach ($stocks as $stock)
                            <tr stock_id="{{$stock->id}}">
                                <td class="py-1 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-theme-xs text-gray-700 dark:text-gray-400">
                                            {{ $stock->item_code }}
                                        </p>
                                    </div>
                                </td>

                                <td class="py-1 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-theme-xs text-gray-700 dark:text-gray-400">
                                            {{ $stock->branch_name ?? 'N/A' }}
                                        </p>
                                    </div>
                                </td>
                                <td class="py-1 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-theme-xs text-gray-700 dark:text-gray-400">
                                            {{ $stock->item_name }}
                                        </p>
                                    </div>
                                </td>



                                <td class="py-1 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-theme-xs text-gray-700 dark:text-gray-400">
                                            {{ $stock->total_qty }}
                                        </p>
                                    </div>
                                </td>
                                {{-- <td class="py-1 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-theme-xs  text-gray-700 dark:text-gray-400">
                                            {{ number_format($stock->purchase_price, 2) }} LKR
                                        </p>
                                    </div>
                                </td>
                                <td class="py-1 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-theme-xs text-gray-700 dark:text-gray-400">
                                            {{ number_format($stock->sales_price, 2) }} LKR
                                        </p>
                                    </div>
                                </td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

                    <div class="flex justify-between items-center border-t px-6 py-4 dark:border-gray-800">
                    @if ($paginationEnabled)
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        Showing {{ $stocks->firstItem() }} to {{ $stocks->lastItem() }} of
                        {{ $stocks->total() }} entries
                    </div>

                    <div class="pagination flex justify-between">
                       {{ $stocks->links('vendor.pagination.custom-tailwind') }}
                    </div>
                    @endif
                </div>
        </div>
    </div>
</div>

<script>
function printPreview() {
    // Get the printable area content
    const printableElement = document.getElementById('printable-area');
    // Clone the table to manipulate for print
    const clone = printableElement.cloneNode(true);

    // Find the table
    const table = clone.querySelector('table');
    if (table) {
        // Remove Branch column from thead
        const ths = table.querySelectorAll('thead th');
        if (ths.length > 1) {
            ths[1].parentNode.removeChild(ths[1]);
        }
        // Remove Branch column from tbody rows
        const rows = table.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const tds = row.querySelectorAll('td');
            if (tds.length > 1) {
                tds[1].parentNode.removeChild(tds[1]);
            }
        });
    }

    // Get user name from a hidden element
    const userName = document.getElementById('print-user-name')?.textContent || '';

    // Get branch name from the first row (if available)
    let branchName = '';
    const branchCell = printableElement.querySelector('tbody tr td:nth-child(2) p');
    if (branchCell) {
        branchName = branchCell.textContent.trim();
    }

    // Open a new window for print preview
    const win = window.open('', '_blank', 'width=1000,height=700');

    // Customize your header as needed
    const companyName = "Master Graphics Services (Pvt) Ltd";
    const reportTitle = "Stock Summary";
    const now = new Date();
    const printDate = now.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
    const printTime = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' });

    win.document.write(`
        <html>
        <head>
            <style>
                @page {
                    size: A4 ;
                    margin: 0mm;
                }
                body {
                    font-family: Arial, Helvetica, sans-serif;
                    margin: 0;
                    padding: 20px;
                }
                .report-header {
                    margin-top: 20px;
                    margin-bottom: 20px;
                    text-align: center;
                    position: relative;
                }
                .branch-info {
                    left: 0;
                    top: 0;
                    font-size: 13px;
                    color: #666;
                    text-align: left;
                }
                .company-name {
                    font-size: 18px;
                    font-weight: bold;
                }
                .report-title {
                    font-size: 16px;
                    margin: 5px 0;
                    font-weight:bold;
                }
                .date-info {
                    font-size: 13px;
                    color: #666;
                    text-align: left !important;
                }
                .user-info {
                    font-size: 13px;
                    color: #666;
                    text-align: left !important;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 15px;
                    font-size: 12px;
                }
                th, td {
                    border: 1px solid #ddd;
                    padding: 8px;
                    text-align: left;
                }
                th {
                    background-color: #f2f2f2;
                }
                /* Align Quantity column (now 3rd column) to right */
                @media print {
                    th:nth-child(3), td:nth-child(3) {
                        text-align: right !important;
                    }
                }
                th:nth-child(3), td:nth-child(3) {
                    text-align: right !important;
                }
                .no-print {
                    display: none;
                }
                tfoot tr {
                    page-break-inside: avoid;
                }
            </style>
        </head>
        <body>
            <div class="report-header">
                <div class="company-name">${companyName}</div>
                <div class="report-title">${reportTitle}</div>
                <div class="branch-info">${branchName ? 'Branch: ' + branchName : ''}</div>
                <div class="date-info">Printed on: ${printDate} - ${printTime}</div>
                <div class="user-info">Printed by: ${userName}</div>

            </div>
            ${clone.innerHTML}
        </body>
        </html>
    `);

    win.document.close();
    win.focus();
    win.print();
    // win.close(); // Uncomment if you want to close after printing
}
</script>

{{-- Hidden element to pass user name to JS --}}
<span id="print-user-name" style="display:none">{{ auth()->user()->name ?? '' }}</span>
