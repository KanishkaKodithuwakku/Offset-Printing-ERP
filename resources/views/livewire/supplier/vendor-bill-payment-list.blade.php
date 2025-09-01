<div class="p-6 bg-white border border-gray-300 rounded-md">
    <div class="mt-5 mb-4 text-lg font-semibold text-center">
        <h2 style="font-size: 18px; font-weight: bold; margin: 0;">{{ config('app.company_name') }}</h2>
        <h2 style="font-size: 18px; font-weight: bold; margin: 0; text-align: left;">Vendor Bill Payment List</h2>
    </div>

    {{-- Filters --}}
    <div class="flex flex-wrap items-end gap-4">
        <div class="relative">
            <label class="block text-xs font-medium text-gray-700">Select Supplier</label>
            <select wire:model="supplierId" wire:change="loadSupplierBills"
                class="block w-full h-8 py-2 pl-3 pr-10 text-xs border border-gray-300 rounded-md focus:border-blue-500 focus:outline-none focus:ring-blue-500">
                <option value="">All Suppliers</option>
                @foreach ($suppliers as $supplier)
                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-xs font-medium text-gray-700">From</label>
            <input type="date" wire:model.live="startDate" name="startDate" onclick="this.showPicker()"
                class="dark:bg-dark-900 datepickerTwo shadow-theme-xs w-full focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 pl-4 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
        </div>

        <div>
            <label class="block text-xs font-medium text-gray-700">To</label>
            <input type="date" wire:model.live="endDate" name="endDate" onclick="this.showPicker()"
                class="dark:bg-dark-900 datepickerTwo shadow-theme-xs w-full focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 pl-4 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
        </div>

        <div>
            <label class="block text-xs font-medium text-gray-700">Method</label>
            <select wire:model.change="statusFilter"
                class="dark:bg-dark-900 shadow-theme-xs focus:ring focus:border-blue-500 h-8 rounded-lg border py-1 text-xs">
                <option value="ALL">All</option>
                <option value="CA">Cash</option>
                <option value="CH">Cheque</option>
            </select>
        </div>

        <div class="flex items-center mb-2">
            <label for="pagination-toggle" class="mr-2 text-sm text-gray-700">Enable Pagination:</label>
            <input type="checkbox" id="pagination-toggle" wire:model.change="paginationEnabled" class="form-checkbox">
        </div>

        <div class="flex justify-end">
            <button onclick="printPreview()" class="px-4 py-1 text-white rounded-lg bg-brand-500">
                Print Preview
            </button>
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto" id="printable-area">
        <table class="min-w-full mt-5 border divide-y">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-sm font-semibold text-left">Payment Date</th>
                    <th class="px-4 py-2 text-sm font-semibold text-left">Receipt No</th>
                    <th class="px-4 py-2 text-sm font-semibold text-left">Vendor Name</th>
                    <th class="px-4 py-2 text-sm font-semibold text-left">Method</th>
                    <th class="px-4 py-2 text-sm font-semibold text-left">Cheque Number</th>
                    <th class="px-4 py-2 text-sm font-semibold text-left">Cheque Date</th>
                    <th class="px-4 py-2 text-sm font-semibold text-right">Amount</th>
                    <th class="px-4 py-2 text-sm font-semibold text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y">
                @forelse ($payments as $payment)
                <tr>
                    <td class="px-4 py-2 text-sm whitespace-nowrap">{{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d') : 'N/A' }}</td>
                    <td class="px-4 py-2 text-sm whitespace-nowrap">{{ $payment->receipt_number ?? 'N/A' }}</td>
                    <td class="px-4 py-2 text-sm whitespace-nowrap">{{ $payment->vendor->name ?? 'N/A' }}</td>
                    <td class="px-4 py-2 text-sm whitespace-nowrap">{{ $payment->payment_method === 'CA' ? 'Cash' : ($payment->payment_method === 'CH' ? 'Cheque' : $payment->payment_method) }}</td>
                    <td class="px-4 py-2 text-sm whitespace-nowrap">{{ $payment->check_number ?? 'N/A' }}</td>
                    <td class="px-4 py-2 text-sm whitespace-nowrap">
                        @if ($payment->payment_method === 'CH')
                        {{ $payment->check_date ? \Carbon\Carbon::parse($payment->check_date)->format('Y-m-d') : '' }}
                        @endif
                    </td>
                    <td class="px-4 py-2 text-sm text-right whitespace-nowrap">
                        {{ number_format($payment->total_amount, 2) }}
                    </td>
                    <td class="px-4 py-2 text-sm whitespace-nowrap text-center">
                        <div class="flex items-center justify-center space-x-2" x-data="{ showConfirm: false }">
                            <button wire:click="printPayment({{ $payment->id }})" class="text-gray-400 hover:text-brand-500" title="Print">
                                <svg class="fill-current" width="18"
                                            height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M1.5 12C1.5 12 5.25 4.5 12 4.5C18.75 4.5 22.5 12 22.5 12C22.5 12 18.75 19.5 12 19.5C5.25 19.5 1.5 12 1.5 12ZM12 15C13.6569 15 15 13.6569 15 12C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15Z"
                                                fill="" />
                                        </svg>
                            </button>
                            <button @click="showConfirm = true" class="text-gray-400 hover:text-error-500" title="Delete">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>

                            <!-- Custom Styled Confirmation Modal -->
                            <div x-show="showConfirm"
                                class="fixed inset-0 z-50 flex items-center justify-center p-5 overflow-y-auto">
                                <div
                                    class="modal-close-btn fixed inset-0 h-full w-full bg-gray-400/50 backdrop-blur-[30px]">
                                </div>

                                <div class="flex flex-col px-4 py-4 overflow-y-auto no-scrollbar">
                                    <div @click.outside="showConfirm = false"
                                        class="relative w-full max-w-[507px] rounded-3xl bg-white p-6 dark:bg-gray-900 lg:p-10">
                                        <div class="text-center">
                                            <h4
                                                class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">
                                                Confirm Deletion
                                            </h4>
                                            <p class="text-sm leading-6 text-gray-500 dark:text-gray-400">
                                                Are you sure you want to delete this vendor bill payment? <br>
                                                Click Delete to confirm.
                                            </p>
                                            <div class="flex items-center justify-center w-full gap-3 mt-8">
                                                <button @click="showConfirm = false" type="button"
                                                    class="flex justify-center rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">
                                                    Cancel
                                                </button>
                                                <button type="button"
                                                    @click="$wire.deletePayment({{ $payment->id }}); showConfirm = false"
                                                    class="flex justify-center px-4 py-3 text-sm font-medium text-white rounded-lg bg-brand-500 shadow-theme-xs hover:bg-error-600">
                                                    Delete
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-6 text-sm text-center text-gray-500">
                        No vendor bill payments found.
                    </td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="bg-gray-100" style="border: 1px solid #000;">
                    <td class="px-3 py-2 text-sm font-semibold text-left text-gray-800">Total</td>
                    <td class="px-3 py-2 text-sm font-semibold text-left text-gray-500"></td>
                    <td class="px-3 py-2 text-sm font-semibold text-left text-gray-500"></td>
                    <td class="px-3 py-2 text-sm font-semibold text-left text-gray-500"></td>
                    <td class="px-3 py-2 text-sm font-semibold text-left text-gray-500"></td>
                    <td class="px-3 py-2 text-sm font-semibold text-left text-gray-500"></td>
                    <td class="px-3 py-2 text-sm font-semibold text-right text-gray-800">{{ number_format($payments->sum('total_amount'), 2) }}</td>
                    <td class="px-3 py-2 text-sm font-semibold text-left text-gray-500"></td>
                </tr>
            </tfoot>
        </table>
    </div>

    {{-- Pagination --}}
    @if ($paginationEnabled)
    <div class="flex items-center justify-between px-6 py-4 border-t">
        <div class="text-sm text-gray-600">
            Showing {{ $payments->firstItem() }} to {{ $payments->lastItem() }} of
            {{ $payments->total() }} entries
        </div>
        <div class="flex justify-between pagination">
            {{ $payments->links('vendor.pagination.custom-tailwind') }}
        </div>
    </div>
    @endif
</div>

<script>
    function printPreview() {
        // Clone the printable area to capture all dynamic content
        const printableElement = document.getElementById('printable-area');
        const content = printableElement.innerHTML;

        // Create a temporary div to manipulate the content
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = content;

        // Remove the Actions column from each row
        const rows = tempDiv.querySelectorAll('tr');
        rows.forEach(row => {
            const cells = row.querySelectorAll('td, th');
            if (cells.length > 0) {
                // Remove the last cell (Actions column)
                cells[cells.length - 1].remove();
            }
        });

        const filteredContent = tempDiv.innerHTML;

        const win = window.open('', '_blank', 'width=800,height=600');

        // Get the dates and supplier data
        function formatDate(dateStr) {
            if (!dateStr) return '';
            const [year, month, day] = dateStr.split('-');
            if (!year || !month || !day) return dateStr;
            return `${day}-${month}-${year}`;
        }

        // Try to get the current filter values from the DOM
        const startDateInput = document.querySelector('input[name="startDate"]');
        const endDateInput = document.querySelector('input[name="endDate"]');
        const startDate = startDateInput ? formatDate(startDateInput.value) : '';
        const endDate = endDateInput ? formatDate(endDateInput.value) : '';

        const statusFilter = document.querySelector('select[wire\\:model\\.change="statusFilter"]')?.value || 'ALL';
        const supplierSelect = document.querySelector('select[wire\\:model="supplierId"]');
        const supplierName = supplierSelect?.options[supplierSelect.selectedIndex]?.text || 'All Suppliers';

        const printDateTime = new Date().toLocaleString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
        });

        const reportTitle = statusFilter === 'CA' ? 'Cash Payment Listing' :
                           statusFilter === 'CH' ? 'Cheque Payment Listing' :
                           'Vendor Bill Payment Report';

        win.document.write(`
            <html>
            <head>
                <title>Vendor Bill Payment List - Print Preview</title>
                <style>
                    @page {
                        size: A4 portrait;
                        margin: 5mm;
                    }
                    body {
                        font-family: Arial, Helvetica, sans-serif;
                        margin: 0;
                        padding: 5px 25px 5px 50px;
                    }
                    .report-header {
                        margin-bottom: 20px;
                        text-align: center;
                    }
                    .company-name {
                        font-size: 18px;
                        font-weight: bold;
                    }
                    .report-title {
                        font-size: 18px;
                        font-weight: bold;
                        margin: 5px 0;
                        margin-bottom: 30px;
                    }
                    .supplier-info {
                        font-size: 14px;
                        margin: 5px 0;
                        color: #000;
                        text-align: left !important;
                    }
                    .supplier-info span{font-weight:bold;}
                    .date-info {
                        font-size: 13px;
                        color: #000;
                        text-align: left !important;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-top: 15px;
                        font-size: 12px;
                    }
                    th, td {
                        border: 1px solid #000;
                        padding: 2px;
                        text-align: left;
                    }
                    th {
                        background-color: #f2f2f2;
                        border-top: 2px solid #000;
                        border-bottom: 2px solid #000;
                    }
                    /* Center align all table headers */
                    table tr th {
                        text-align: center !important;
                    }
                    /* Right align amount column */
                    table tr th:nth-child(7),
                    table tr td:nth-child(7),
                    tfoot td:last-child {
                        text-align: right !important;
                    }
                    /* Right align Receipt Number column (2nd column) */
                    table tr th:nth-child(2),
                    table tr td:nth-child(2) {
                        text-align: right !important;
                    }
                    /* Right align Cheque Number column (5th column) */
                    table tr th:nth-child(5),
                    table tr td:nth-child(5) {
                        text-align: right !important;
                    }
                    /* Left align only the 'Total' label cell in tfoot */
                    tfoot td[colspan="6"] {
                        text-align: left !important;
                    }
                    /* Increase width for Payment Date column */
                    table tr th:nth-child(1),
                    table tr td:nth-child(1) {
                        width: 80px !important;
                        min-width: 80px !important;
                    }
                    tfoot {
                        display: table-row-group;
                    }
                    .no-print {
                        display: none;
                    }
                    tfoot tr {
                        page-break-inside: avoid;
                    }
                    tfoot td {
                        border-top: 2px solid #000;
                        border-bottom: 2px solid #000;
                        background-color: #f0f0f0;
                        font-weight: bold;
                    }
                    .no-print {
                        display: none;
                    }
                </style>
            </head>
            <body>
                <div class="report-header">
                    <div class="company-name">{{ config('app.company_name') }}</div>
                    <div class="report-title">${reportTitle}</div>
                    <div class="supplier-info">Supplier: <span>${supplierName}</span></div>
                    ${startDate && endDate ? `<div class="date-info">Selected From: ${startDate} To: ${endDate}</div>` : ''}
                    <div class="date-info">Printed on: ${printDateTime}</div>
                </div>
                ${filteredContent}
            </body>
            </html>
        `);

        // Delay printing to ensure content is loaded
        win.document.close();
        win.focus();
        win.print();
    }
</script>
