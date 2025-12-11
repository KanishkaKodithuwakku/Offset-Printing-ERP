<div class="p-6 bg-white border border-gray-300 rounded-md">
    <div class="mt-5 mb-4 text-lg font-semibold text-center">
        <h2 style="font-size: 18px; font-weight: bold; margin: 0;">{{ config('app.company_name') }}</h2>
        Customer Wise Outstanding Report as on {{ \Carbon\Carbon::parse($startDate)->format('d-M-Y') }} to
        {{ \Carbon\Carbon::parse($endDate)->format('d-M-Y') }}
    </div>
    {{-- Filters --}}
    <div class="flex flex-wrap items-end gap-4">

        <div class="relative">
            <label class="block text-xs font-medium text-gray-700">Select Customer</label>
            <select wire:model.live="selectedCustomerId"
                class="block w-full h-8 py-2 pl-3 pr-10 text-xs border border-gray-300 rounded-md focus:border-blue-500 focus:outline-none focus:ring-blue-500 ">
                <option value="">All Customers</option>
                @foreach ($customers as $customer)
                <option value="{{ $customer['id'] }}">{{ $customer['name'] }}</option>
                @endforeach
            </select>
        </div>
        <span id="selectedCustomerName" style="display: none;">
            @if ($selectedCustomerId)
            {{ optional($customers->where('id', $selectedCustomerId)->first())->name ?? 'Selected Customer' }}
            @else
            All Customers
            @endif
        </span>

        <div>
            <label class="block text-xs font-medium text-gray-700">From</label>
            <input type="date" wire:model.live="startDate" name="startDate" onclick="this.showPicker()"
                class="dark:bg-dark-900  datepickerTwo shadow-theme-xs w-full focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8  appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 pl-4 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
            <span
                class="absolute mt-2 text-gray-500 -translate-y-1/2 pointer-events-none top-1/2 right-3 dark:text-gray-400">

            </span>
        </div>

        <div>
            <label class="block text-xs font-medium text-gray-700">To</label>
            <input type="date" wire:model.live="endDate" name="endDate" onclick="this.showPicker()"
                class="dark:bg-dark-900  datepickerTwo shadow-theme-xs w-full focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8  appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 pl-4 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
            <span
                class="absolute mt-2 text-gray-500 -translate-y-1/2 pointer-events-none top-1/2 right-3 dark:text-gray-400">

            </span>
        </div>



        <div class="flex items-center mb-2">
            <label for="pagination-toggle" class="mr-2 text-sm text-gray-700">Enable Pagination:</label>
            <input type="checkbox" id="pagination-toggle" wire:model.change="paginationEnabled" class="form-checkbox">
        </div>

        <div class="flex justify-end">
            @if (!$paginationEnabled)
            <button onclick="printPreview()" class="px-4 py-1 text-white rounded-lg bg-brand-500">
                Print Preview
            </button>
            @else
            <button disabled class="px-4 py-1 text-white rounded-lg bg-gray-400 cursor-not-allowed" title="Print is disabled when pagination is enabled">
                Print Preview
            </button>
            @endif
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto" id="printable-area">

        <table class="min-w-full mt-5 border divide-y">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-sm font-semibold text-center" style="width: 70px;">Invoice Date</th>
                    <th class="px-4 py-2 text-sm font-semibold text-center">Invoice Number</th>
                    {{-- <th class="px-4 py-2 text-sm font-semibold text-center">Job Number</th> --}}
                    <th class="px-4 py-2 text-sm font-semibold text-center">Job Description</th>
                    <th class="px-4 py-2 text-sm font-semibold text-center" style="width: 100px;">Invoice Amount</th>
                    <th class="px-4 py-2 text-sm font-semibold text-center" style="width: 100px;">Paid Amount</th>
                    <th class="px-4 py-2 text-sm font-semibold text-center" style="width: 100px;">Balance</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y">
                @forelse($invoices as $inv)
                <tr>
                    <td class="px-2 py-2 text-sm whitespace-nowrap">{{ $inv->created_at->format('Y-m-d') }}</td>
                    <td class="px-2 py-2 text-sm whitespace-nowrap">{{ $inv->invoice_number }}</td>
                    {{-- <td class="px-2 py-2 text-sm whitespace-nowrap">{{ $inv->order->job_number ?? '-' }}</td> --}}
                    <td class="px-2 py-2 text-sm whitespace-nowrap">
                        {{ $inv->order->description ?? ($inv->invoice_details ?? '-') }}</td>
                    <td class="px-2 py-2 text-sm text-right whitespace-nowrap" style="text-align: right">
                        {{ number_format($inv->total_amount, 2) }}</td>
                    <td class="px-2 py-2 text-sm text-right whitespace-nowrap">
                        {{ number_format($inv->total_amount - $inv->amount_due, 2) }}</td>
                    <td class="px-2 py-2 text-sm text-right whitespace-nowrap">
                        {{ number_format($inv->amount_due, 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-6 text-sm text-center text-gray-500">
                        No outstanding invoices found.
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if (!$paginationEnabled || ($paginationEnabled && $invoices->currentPage() == $invoices->lastPage()))
            <tfoot>
                <tr class="bg-gray-100" style="border: 2px solid #000">
                    <td colspan="3" class="px-3 py-2 text-sm font-semibold text-left text-gray-500" style="font-weight: bold">Total Amount:
                    </td>
                    <td class="px-2 py-2 text-sm font-semibold text-right text-gray-500" style="font-weight: bold">
                        {{ number_format($allInvoices->sum('total_amount'), 2) }}</td>
                    <td class="px-2 py-2 text-sm font-semibold text-right text-error-500" style="font-weight: bold">
                        {{ number_format($allInvoices->sum('total_amount') - $allInvoices->sum('amount_due'), decimals: 2) }}</td>
                    <td class="px-2 py-2 text-sm font-semibold text-right text-gray-500" style="font-weight: bold">
                        {{ number_format($allInvoices->sum('amount_due'), 2) }}</td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>


    <!-- Pagination -->
    <div class="flex items-center justify-between px-6 py-4 border-t dark:border-gray-800">
        @if ($paginationEnabled)
        <div class="text-sm text-gray-600 dark:text-gray-400">
            Showing {{ $invoices->firstItem() }} to {{ $invoices->lastItem() }} of
            {{ $invoices->total() }} entries
        </div>
        @endif

        <div class="flex justify-between pagination">
            @if ($paginationEnabled)
            <div class="flex justify-between pagination">
                {{ $invoices->links('vendor.pagination.custom-tailwind') }}
            </div>
            @endif
        </div>
    </div>



</div>

<script>
    function printPreview() {
        // Clone the printable area to capture all dynamic content
        const printableElement = document.getElementById('printable-area');
        const content = printableElement.innerHTML;

        const win = window.open('', '_blank', 'width=800,height=600');

        // Get the dates and customer data
        function formatDate(dateStr) {
            if (!dateStr) return '';
            const [year, month, day] = dateStr.split('-');
            if (!year || !month || !day) return dateStr;
            return `${day}-${month}-${year}`;
        }
        // Try to get the current filter values from the DOM, fallback to Blade values
        const startDateInput = document.querySelector('input[name="startDate"]');
        const endDateInput = document.querySelector('input[name="endDate"]');
        const startDate = startDateInput ? formatDate(startDateInput.value) : '{{ \Carbon\Carbon::parse($startDate)->format('d-m-Y') }}';
        const endDate = endDateInput ? formatDate(endDateInput.value) : '{{ \Carbon\Carbon::parse($endDate)->format('d-m-Y') }}';
        const cust = document.getElementById('selectedCustomerName')?.innerText || 'All Customers';
        const d = new Date();
        const year = d.getFullYear();
        const month = d.toLocaleString('default', { month: 'short' });
        const day = d.getDate();
        const time = d.toLocaleTimeString();
        const printDate = `${year} ${month} ${day} ${time}`;
        win.document.write(`
            <html>
            <head>
                <title>Customer Outstanding Report - Print Preview</title>
                <style>
                    
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
                        font-size: 16px;
                        margin: 5px 0;
                    }
                    .customer-info {
                        font-size: 14px;
                        
                        margin: 5px 0;
                        color: #000;
                        text-align:left  !important;
                    }
                    .customer-info span{font-weight:bold;}
                    .date-info {
                        font-size: 13px;
                        color: #000;
                        text-align:left  !important;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-top: 15px;
                        font-size: 11px;
                    }
                    th, td {
                        border: 1px solid #000;
                        padding: 3px;
                    }
                     td{
                        text-align: left;
                        }
                    th {
                        background-color: #f2f2f2;
                        text-align: center;
                    }
                    /* Right align all amount columns (5, 6, 7) in thead, tbody, tfoot */
                    table tr th:nth-child(5),
                    table tr th:nth-child(6),
                    table tr th:nth-child(7),
                    table tr td:nth-child(5),
                    table tr td:nth-child(6),
                    table tr td:nth-child(7),
                    tfoot td {
                        text-align: right !important;
                    }
                    /* Left align only the 'Total Amount:' label cell in tfoot */
                    tfoot td[colspan="4"] {
                        text-align: left !important;
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
                </style>
            </head>
            <body>
                <div class="report-header">
                    <div class="company-name">Master Graphics Services (Pvt) Ltd</div>
                    <div class="report-title">Customer Outstanding Balance</div>
                    <div class="customer-info">Customer: <span>${cust}</span></div>
                    <div class="date-info">Selected From: ${startDate} To: ${endDate}</div>
                    <div class="date-info">Printed on: ${printDate}</div>
                </div>
                ${content}
            </body>
            </html>
        `);

        // Delay printing to ensure content is loaded
        win.document.close();
        win.focus();
        win.print();
        // win.close();
    }
</script>