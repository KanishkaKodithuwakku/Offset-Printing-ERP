<div class="p-6 bg-white border border-gray-300 rounded-md">
    {{-- Filters --}}
    <div class="flex flex-wrap items-end gap-4 mb-4">
        <div class="relative">
            <h1 class="text-lg mb-3 font-semibold text-gray-800 dark:text-white/90">
                Age Analysis Report
            </h1>
            <label class="block text-xs font-medium text-gray-700">Select Customer</label>
            <select wire:model.live="selectedCustomerId"
                class="block w-full h-8 py-2 pl-3 pr-10 text-xs border border-gray-300 rounded-md focus:border-blue-500 focus:outline-none focus:ring-blue-500">
                <option value="">All Customers</option>
                @foreach ($customers as $customer)
                    <option value="{{ $customer['id'] }}">{{ $customer['name'] }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-700">From</label>
            <input type="date" name="startDate" wire:model.live="startDate" onclick="this.showPicker()"
                class="w-full h-8 px-3 text-xs border border-gray-300 rounded-md focus:border-blue-500 focus:outline-none focus:ring-blue-500" />
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-700">To</label>
            <input type="date" name="endDate" wire:model.live="endDate" onclick="this.showPicker()"
                class="w-full h-8 px-3 text-xs border border-gray-300 rounded-md focus:border-blue-500 focus:outline-none focus:ring-blue-500" />
        </div>
        <div class="flex items-center mb-2">
            <label for="pagination-toggle" class="mr-2 text-sm text-gray-700">Enable Pagination:</label>
            <input type="checkbox" id="pagination-toggle" wire:model.change="paginationEnabled" class="form-checkbox">
        </div>
        <div class="flex justify-end">
            <button onclick="printPreview()" class="px-4 py-1 text-white bg-brand-500 rounded-lg hover:bg-brand-500">
                Print Preview
            </button>
        </div>
    </div>

    <div class="overflow-x-auto" id="printable-area">
        <table class="min-w-full border border-gray-200 divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>

                    <th class="px-2 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Customer
                    </th>
                    <th class="px-2 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase" style="text-align: center">Total
                    </th>
                    <th class="px-2 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase" style="text-align: center">1-30
                        Days</th>
                    <th class="px-2 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase" style="text-align: center">31-60
                        Days</th>
                    <th class="px-2 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase" style="text-align: center">61-90
                        Days</th>
                    <th class="px-2 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase" style="text-align: center">Over 90
                        Days</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($invoices as $invoice)
                    <tr class="hover:bg-gray-50">

                        <td class="px-4 py-2 text-sm font-medium text-gray-900 whitespace-nowrap">
                            {{ $invoice['customer_name'] }}</td>
                        
                        <td class="tot px-4 py-2 text-sm text-right text-gray-500 whitespace-nowrap" style="text-align:right !important;">
                            {{ number_format($invoice['total_amount'], 2) }}</td>
                        <td class="px-4 py-2 text-sm text-right text-gray-500 whitespace-nowrap" style="text-align:right !important;">
                            @if ($invoice['age_category'] == '1-30 Days')
                                {{ number_format($invoice['total_amount'], 2) }}
                            @else
                                0.00
                            @endif
                        </td>
                        <td class="px-4 py-2 text-sm text-right text-gray-500 whitespace-nowrap">
                            @if ($invoice['age_category'] == '31-60 Days')
                                {{ number_format($invoice['total_amount'], 2) }}
                            @else
                                0.00
                            @endif
                        </td>
                        <td class="px-4 py-2 text-sm text-right text-gray-500 whitespace-nowrap">
                            @if ($invoice['age_category'] == '61-90 Days')
                                {{ number_format($invoice['total_amount'], 2) }}
                            @else
                                0.00
                            @endif
                        </td>
                        <td class="px-4 py-2 text-sm text-right text-gray-500 whitespace-nowrap">
                            @if ($invoice['age_category'] == 'Over 90 Days')
                                {{ number_format($invoice['total_amount'], 2) }}
                            @else
                                0.00
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <!-- Totals Row -->
            <tfoot class="bg-gray-100">
                <tr class="border-b-2 border-black bg-gray-200" style="background-color: #ccc">
                    <td class="px-4 py-3 text-sm font-bold text-gray-900 bg-gray-200" style="background-color: #666">Total</td>
                    <td class="px-4 py-3 text-sm font-bold text-right text-gray-900 bg-gray-200"  style="text-align:right !important;">
                        {{ number_format($invoices->sum('total_amount'), 2) }}</td>
                    <td class="px-4 py-3 text-sm font-bold text-right text-gray-900 bg-gray-200"  style="text-align:right !important;">
                        {{ number_format($total_1_30_days, 2) }}</td>
                    <td class="px-4 py-3 text-sm font-bold text-right text-gray-900 bg-gray-200"  style="text-align:right !important;">
                        {{ number_format($total_31_60_days, 2) }}</td>
                    <td class="px-4 py-3 text-sm font-bold text-right text-gray-900 bg-gray-200">
                        {{ number_format($total_61_90_days, 2) }}</td>
                    <td class="px-4 py-3 text-sm font-bold text-right text-gray-900 bg-gray-200">
                        {{ number_format($total_over_90_days, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Pagination -->
    <div class="flex items-center justify-between px-6 py-4 border-t border-gray-200">
        @if ($paginationEnabled)
            <div class="text-sm text-gray-600">
                Showing {{ $invoices->firstItem() }} to {{ $invoices->lastItem() }} of
                {{ $invoices->total() }} entries
            </div>
        @endif

        <div class="flex justify-between pagination">
            @if ($paginationEnabled)
                {{ $invoices->links('vendor.pagination.custom-tailwind') }}
            @endif
        </div>
    </div>
</div>

<script>
    function printPreview() {
        const content = document.getElementById('printable-area').innerHTML;
        const reportTitle = "Age Analysis Report";

        function formatDate(dateStr) {
            if (!dateStr) return '';
            const [year, month, day] = dateStr.split('-');
            if (!year || !month || !day) return dateStr;
            return `${day}-${month}-${year}`;
        }
        const startDateInput = document.querySelector('input[name="startDate"]');
        const endDateInput = document.querySelector('input[name="endDate"]');
        console.log('Start:', startDateInput?.value, 'End:', endDateInput?.value); // Debug
        const startDate = startDateInput && startDateInput.value ? formatDate(startDateInput.value) : '{{ \Carbon\Carbon::parse($startDate)->format('d-m-Y') }}';
        const endDate = endDateInput && endDateInput.value ? formatDate(endDateInput.value) : '{{ \Carbon\Carbon::parse($endDate)->format('d-m-Y') }}';
        const dateRange = `${startDate} to ${endDate}`;

        const now = new Date();
        const printDate = `${now.getDate().toString().padStart(2, '0')}-${(now.getMonth()+1).toString().padStart(2, '0')}-${now.getFullYear()}`;
        const printTime = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' });

        const win = window.open('', '_blank', 'width=1000,height=700');
        win.document.write(`
            <html>
            <head>
                <title>${reportTitle} - Print Preview</title>
                <style>
                    @page {
                        size: A4 portrait;
                        margin: 10mm;
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
                        font-size: 16px;
                        margin: 5px 0;
                    }
                    .tot{text-align:right  !important;}
                    .date-info {
                        font-size: 13px;
                        color: #000;
                        text-align:left  !important;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-top: 15px;
                        font-size: 12px;
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
                        text-align: center !important;
                    }
                    /* Only the first column (Customer) stays left, others left */
                    th:first-child {
                        text-align: left;
                    }
                    /* Align Total, 1-30, 31-60, 61-90, Over 90 Days headings to right */
                    th:nth-child(3), th:nth-child(4), th:nth-child(5), th:nth-child(6), th:nth-child(7) {
                        text-align: right;
                    }
                    td {
                        text-align: right;
                    }
                    td:first-child, td:nth-child(2) {
                        text-align: left;
                    }
                    .darkback{background-color: #ccc !important;}
                    tfoot td {
                        font-weight: bold;
                    }
                    /* This is the key change - prevents footer repetition */
                tfoot {
                    display: table-row-group;
                }
                .no-print {
                    display: none;
                }
                /* Ensure totals stay together with last rows */

                tfoot tr {
                    page-break-inside: avoid;
                }
                </style>
            </head>
            <body>
                <div class="report-header">
                <div class="company-name">Master Graphics Services (Pvt) Ltd</div>

                    <div class="report-title">${reportTitle}</div>
                    <div class="date-info">Period: ${dateRange}</div>
                    <div class="date-info">Printed on: ${printDate}</div>
                    <div class="date-info">Print time: ${printTime}</div>
                </div>
                ${content}
            </body>
            </html>
        `);
        win.document.close();
        win.focus();
        win.print();
        // win.close();
    }
</script>
