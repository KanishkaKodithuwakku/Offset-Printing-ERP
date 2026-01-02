<div class="p-6 bg-white border border-gray-300 rounded-md">
    <div class="mt-5 mb-4 text-lg font-semibold text-center">
        <h2 style="font-size: 18px; font-weight: bold; margin: 0;">{{ config('app.company_name') }}</h2>
        Receipt List
    </div>

    {{-- Filters --}}
    <div class="flex flex-wrap items-end gap-4">
        <div class="relative">
            <label class="block text-xs font-medium text-gray-700">Customer</label>
            <input
                type="text"
                name="customerName"
                wire:model.live.debounce.300ms="customerName"
                placeholder="Search customer by name..."
                class="block w-full h-8 py-2 pl-3 pr-3 text-xs border border-gray-300 rounded-md focus:border-blue-500 focus:outline-none focus:ring-blue-500"
            >
            @if ($showSuggestions && !empty($customerName) && $customerSuggestions->count())
                <div class="absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-md shadow-lg max-h-48 overflow-auto">
                    @foreach ($customerSuggestions as $customer)
                        <button
                            type="button"
                            wire:click="selectCustomer('{{ addslashes($customer->name) }}')"
                            class="w-full px-3 py-2 text-left text-xs hover:bg-gray-100">
                            {{ $customer->name }}
                        </button>
                    @endforeach
                </div>
            @endif
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
                    <th class="px-4 py-2 text-sm font-semibold text-left">Receipt Date</th>
                    <th class="px-4 py-2 text-sm font-semibold text-left">Receipt Number</th>
                    <th class="px-4 py-2 text-sm font-semibold text-left">Customer Name</th>
                    <th class="px-4 py-2 text-sm font-semibold text-left">Method</th>
                    <th class="px-4 py-2 text-sm font-semibold text-left">Cheque Number</th>
                    <th class="px-4 py-2 text-sm font-semibold text-left">Cheque Date</th>
                    <th class="px-4 py-2 text-sm font-semibold text-left">Bank</th>
                    <th class="px-4 py-2 text-sm font-semibold text-right">Amount</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y">
                @forelse ($payments as $payment)
                <tr>
                    <td class="px-4 py-2 text-sm whitespace-nowrap">{{ $payment->date ??
                        $payment->created_at->format('Y-m-d') }}</td>
                    <td class="px-4 py-2 text-sm whitespace-nowrap">{{ $payment->payment_code ?? 'N/A' }}</td>
                    <td class="px-4 py-2 text-sm whitespace-nowrap">{{ $payment->customer->name ?? 'N/A' }}</td>
                    <td class="px-4 py-2 text-sm whitespace-nowrap">{{ $payment->display_method }}</td>
                    <td class="px-4 py-2 text-sm whitespace-nowrap">{{ $payment->check_number ?? 'N/A' }}</td>
                    <td class="px-4 py-2 text-sm whitespace-nowrap">
                        @if ($payment->method === 'CH')
                        {{ $payment->cheque_date ?? '' }}
                        @endif
                    </td>
                    <td class="px-4 py-2 text-sm whitespace-nowrap">
                        {{ ($payment->bank?->name ?? '') . ' ' . ($payment->bankBranch?->name ?? '') }}
                    </td>
                    <td class="px-4 py-2 text-sm text-right whitespace-nowrap">
                        {{ number_format($payment->payment_details_sum_amount, 2) }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-6 text-sm text-center text-gray-500">
                        No payments found.
                    </td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="bg-gray-100" style="border: 2px solid #000;">
                    <td colspan="7" class="px-3 py-2 text-sm font-semibold text-left text-gray-500"
                        style="font-weight: bold">Total Receipt Amount:</td>
                    <td class="px-4 py-2 text-sm font-semibold text-right text-gray-500" style="font-weight: bold">
                        @php
                            $total = is_a($payments, 'Illuminate\Pagination\LengthAwarePaginator') 
                                ? $payments->sum('payment_details_sum_amount') 
                                : $payments->sum('payment_details_sum_amount');
                        @endphp
                        {{ number_format($total, 2) }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

    {{-- Customer Credits (Overpayments) Table --}}
    @if (isset($customerCredits) && $customerCredits->count() > 0)
    <div class="mt-6 customer-credits-table">
        <h3 class="mb-3 text-lg font-semibold text-gray-800">Customer Credit Notes (Addition)</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full border divide-y">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-sm font-semibold text-left" style="width: 70%;">Customer Name</th>
                        <th class="px-4 py-2 text-sm font-semibold text-right" style="width: 30%;">Credit Amount</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y">
                    @foreach ($customerCredits as $credit)
                    <tr>
                        <td class="px-4 py-2 text-sm" style="width: 70%;">{{ $credit['customer']->name ?? 'N/A' }}</td>
                        <td class="px-4 py-2 text-sm text-right whitespace-nowrap" style="width: 30%;">
                            {{ number_format($credit['total_credit'], 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-gray-100" style="border: 2px solid #000;">
                        <td class="px-3 py-2 text-sm font-semibold text-left text-gray-500" style="font-weight: bold">
                            Total Over Payments Amount:</td>
                        <td class="px-4 py-2 text-sm font-semibold text-right text-gray-500" style="font-weight: bold">
                            {{ number_format($customerCredits->sum('total_credit'), 2) }}
                        </td>
                    </tr>
                    <tr class="bg-gray-200" style="border: 2px solid #000;">
                        <td class="px-3 py-2 text-sm font-semibold text-left text-gray-700" style="font-weight: bold">
                            Sub Total:</td>
                        <td class="px-4 py-2 text-sm font-semibold text-right text-gray-700" style="font-weight: bold">
                            @php
                                $totalAmount = is_a($payments, 'Illuminate\Pagination\LengthAwarePaginator') 
                                    ? $payments->sum('payment_details_sum_amount') 
                                    : $payments->sum('payment_details_sum_amount');
                                $totalCredit = $customerCredits->sum('total_credit');
                                $grandTotal = $totalAmount + $totalCredit;
                            @endphp
                            {{ number_format($grandTotal, 2) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    @endif

    {{-- Payments with Credit Table --}}
    @if (isset($paymentsWithCredit) && $paymentsWithCredit->count() > 0)
    <div class="mt-6 payments-with-credit-table">
        <h3 class="mb-3 text-lg font-semibold text-gray-800">Credit Notes Allocated Receipts (Diduction)</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full border divide-y">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-sm font-semibold text-left">Receipt Date</th>
                        <th class="px-4 py-2 text-sm font-semibold text-left">Receipt Number</th>
                        <th class="px-4 py-2 text-sm font-semibold text-left">Customer Name</th>
                        <th class="px-4 py-2 text-sm font-semibold text-right">Credit Amount</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y">
                    @foreach ($paymentsWithCredit as $item)
                    <tr>
                        <td class="px-4 py-2 text-sm whitespace-nowrap">
                            {{ $item['payment']->date ?? $item['payment']->created_at->format('Y-m-d') }}
                        </td>
                        <td class="px-4 py-2 text-sm whitespace-nowrap">
                            {{ $item['payment']->payment_code ?? 'N/A' }}
                        </td>
                        <td class="px-4 py-2 text-sm whitespace-nowrap">
                            {{ $item['payment']->customer->name ?? 'N/A' }}
                        </td>
                        <td class="px-4 py-2 text-sm text-right whitespace-nowrap">
                            {{ number_format($item['credit_amount'], 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-gray-100" style="border: 2px solid #000;">
                        <td colspan="3" class="px-3 py-2 text-sm font-semibold text-left text-gray-500" style="font-weight: bold">
                            Total Credit Amount:</td>
                        <td class="px-4 py-2 text-sm font-semibold text-right text-gray-500" style="font-weight: bold">
                            @php
                                $totalCreditAmount = $paymentsWithCredit->sum('credit_amount');
                            @endphp
                            {{ number_format($totalCreditAmount, 2) }}
                        </td>
                    </tr>
                    <tr class="bg-gray-100" style="border: 2px solid #000;">
                        <td colspan="3" class="px-3 py-2 text-sm font-semibold text-left text-gray-800" style="font-weight: bold">
                            Bank To Be Deposited:</td>
                        <td class="px-4 py-2 text-sm font-semibold text-right text-gray-800" style="font-weight: bold">
                            @php
                                // Calculate Total Receipt Amount
                                $totalReceiptAmount = is_a($payments, 'Illuminate\Pagination\LengthAwarePaginator') 
                                    ? $payments->sum('payment_details_sum_amount') 
                                    : $payments->sum('payment_details_sum_amount');
                                
                                // Check if Grand Total exists (if customerCredits table exists)
                                $grandTotal = null;
                                if (isset($customerCredits) && $customerCredits->count() > 0) {
                                    $totalOverPayment = $customerCredits->sum('total_credit');
                                    $grandTotal = $totalReceiptAmount + $totalOverPayment;
                                }
                                
                                // Calculate Bank Deposit Amount
                                // If grand total exists: bank deposit amount = grand total - Total Credit Amount
                                // If grand total doesn't exist: bank deposit amount = Total Receipt Amount - Total Credit Amount
                                $bankDepositAmount = $grandTotal !== null 
                                    ? $grandTotal - $totalCreditAmount 
                                    : $totalReceiptAmount - $totalCreditAmount;
                            @endphp
                            {{ number_format($bankDepositAmount, 2) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    @endif

    <!-- Pagination -->
    <div class="flex items-center justify-between px-6 py-4 border-t dark:border-gray-800">
        @if ($paginationEnabled && is_a($payments, 'Illuminate\Pagination\LengthAwarePaginator'))
        <div class="text-sm text-gray-600 dark:text-gray-400">
            Showing {{ $payments->firstItem() }} to {{ $payments->lastItem() }} of
            {{ $payments->total() }} entries
        </div>
        @endif

        <div class="flex justify-between pagination">
            @if ($paginationEnabled && is_a($payments, 'Illuminate\Pagination\LengthAwarePaginator'))
            <div class="flex justify-between pagination">
                {{ $payments->links('vendor.pagination.custom-tailwind') }}
            </div>
            @endif
        </div>
    </div>
</div>

<script>
    // Get company name from PHP
    const companyName = @json(config('app.company_name', ''));
    
    function printPreview() {
        // Create a print-friendly version of the content
        const printableElement = document.getElementById('printable-area');
        let content = printableElement.innerHTML;
        
        // Check if customer credits table exists and add it to content
        const creditsTableContainer = document.querySelector('.customer-credits-table');
        if (creditsTableContainer) {
            // Get the table HTML from the container
            const creditsTableHTML = creditsTableContainer.innerHTML;
            content += '<div style="margin-top: 20px;">' + creditsTableHTML + '</div>';
        }

        // Check if payments with credit table exists and add it to content
        const paymentsWithCreditTableContainer = document.querySelector('.payments-with-credit-table');
        if (paymentsWithCreditTableContainer) {
            // Get the table HTML from the container
            const paymentsWithCreditTableHTML = paymentsWithCreditTableContainer.innerHTML;
            content += '<div style="margin-top: 20px;">' + paymentsWithCreditTableHTML + '</div>';
        }

        // Get the dates and customer data
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
        const customerInput = document.querySelector('input[name="customerName"]');
        const customerName = customerInput?.value?.trim() || 'All Customers';

        const printDate = new Date().toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });

        const printDateTime = new Date().toLocaleString('en-US', {
  year:   'numeric',
  month:  'short',
  day:    'numeric',
  hour:   '2-digit',
  minute: '2-digit',
  hour12: true
});

        const reportTitle = statusFilter === 'CA' ? 'Cash Receipt Listing' :
                           statusFilter === 'CH' ? 'Cheque Receipt Listing' :
                           'Cheque/Cash Receipt Listing';

        // Create a hidden iframe for printing
        const iframe = document.createElement('iframe');
        iframe.style.position = 'absolute';
        iframe.style.width = '0';
        iframe.style.height = '0';
        iframe.style.border = 'none';
        iframe.style.left = '-9999px';
        
        let printExecuted = false;
        
        // Set up onload handler before appending to DOM
        iframe.onload = function() {
            if (printExecuted) return;
            printExecuted = true;
            
            setTimeout(function() {
                try {
                    const win = iframe.contentWindow;
                    if (win) {
                        win.focus();
                        win.print();
                    }
                    // Remove iframe after printing
                    setTimeout(function() {
                        if (document.body.contains(iframe)) {
                            document.body.removeChild(iframe);
                        }
                    }, 100);
                } catch (e) {
                    console.error('Print error:', e);
                }
            }, 250);
        };
        
        document.body.appendChild(iframe);
        
        // Get iframe document
        const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
        iframeDoc.open();
        iframeDoc.write(`
            <html>
            <head>
                <title>Payment List - Print Preview</title>
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
                        font-size: 16px;
                        margin: 5px 0;
                    }
                    .customer-info {
                        font-size: 14px;
                        margin: 5px 0;
                        color: #000;
                        text-align: left !important;
                    }
                    .customer-info span{font-weight:bold;}
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
                    }
                    /* Center align all table headers */
                    table tr th {
                        text-align: center !important;
                    }
                    /* Right align amount column */
                    table tr th:nth-child(8),
                    table tr td:nth-child(8),
                    tfoot td:last-child {
                        text-align: right !important;
                    }
                    /* Left align only the 'Total Amount:' label cell in tfoot */
                    tfoot td[colspan="7"] {
                        text-align: left !important;
                    }
                    /* Increase width for Receipt Date column */
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
                    h3 {
                        font-size: 14px;
                        font-weight: bold;
                        margin: 15px 0 10px 0;
                    }
                </style>
            </head>
            <body>
                <div class="report-header">
                    <div class="company-name">${companyName}</div>
                    <div class="report-title">${reportTitle}</div>
                    <div class="customer-info">Customer: <span>${customerName}</span></div>
                    ${startDate && endDate ? `<div class="date-info">Selected From: ${startDate} To: ${endDate}</div>` : ''}
                    <div class="date-info">Printed on: ${printDateTime}</div>
                </div>
                ${content}
            </body>
            </html>
        `);
        iframeDoc.close();
        
        // Fallback: if onload doesn't fire, print after a delay
        setTimeout(function() {
            if (!printExecuted) {
                printExecuted = true;
                try {
                    const win = iframe.contentWindow;
                    if (win && win.document.readyState === 'complete') {
                        win.focus();
                        win.print();
                        // Remove iframe after printing
                        setTimeout(function() {
                            if (document.body.contains(iframe)) {
                                document.body.removeChild(iframe);
                            }
                        }, 100);
                    }
                } catch (e) {
                    console.error('Print error:', e);
                }
            }
        }, 500);
    }
</script>