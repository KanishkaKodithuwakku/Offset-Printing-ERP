<div>
    <div class="p-4 bg-white border rounded-xl dark:border-success-500/30 dark:bg-success-500/15" id="printable-area">
        <!-- Company and report info: only on first page -->
        <div style="text-align:center;">
            <h2 style="font-size: 18px; font-weight: bold;text-align:center;">{{ config('app.company_name') }}</h2>
            <h3 style="font-size:16px;font-weight:600;">Customer Wise Sales Report</h3>

            @php
                $customerFullName = 'All Customers';
                if (count($sales)) {
                    $customer = $sales->first()->customer;
                    if ($customer) {
                        if (isset($customer->first_name) || isset($customer->last_name)) {
                            $customerFullName = trim(($customer->first_name ?? '') . ' ' . ($customer->last_name ?? ''));
                        } elseif (isset($customer->name)) {
                            $customerFullName = $customer->name;
                        }
                    }
                    $customerFullName = $customerFullName ?: 'All Customers';
                }
            @endphp
            <div style="margin:0 0 16px 0;font-size:13px;">
                From: <span style="font-weight:500;">{{ $startDate }}</span>
                To: <span style="font-weight:500;">{{ $endDate }}</span>
            </div>
        </div>
        @if (!empty($customerSearch))
            <div style="margin:0 0 8px 0;font-size:13px;font-weight:500;">
                Customer: {{ $customerFullName }}
            </div>
        @endif
        <div class="overflow-hidden bg-white border border-gray-200 rounded-lg shadow-sm">
            <table class="min-w-full text-sm divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 font-medium text-left text-gray-700 border border-gray-200">Invoice Date</th>
                        <th class="px-4 py-3 font-medium text-left text-gray-700 border border-gray-200">Invoice Number</th>
                        <th class="px-4 py-3 font-medium text-left text-gray-700 border border-gray-200">Job Description</th>
                        <th class="px-4 py-3 font-medium text-right text-gray-700 border border-gray-200">Invoice Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @php $total = 0; @endphp
                    @forelse ($sales as $invoice)
                        @php $total += $invoice->total_amount; @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-left text-gray-700 border border-gray-200 whitespace-nowrap">{{ $invoice->created_at->format('Y-m-d') }}</td>
                            <td class="px-4 py-3 text-left text-gray-700 border border-gray-200 whitespace-nowrap">{{ $invoice->invoice_number }}</td>
                            <td class="px-4 py-3 text-left text-gray-700 border border-gray-200">{{ $invoice->order->description ?? '-' }}</td>
                            <td class="px-4 py-3 text-right text-gray-700 border border-gray-200 whitespace-nowrap">{{ number_format($invoice->total_amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-4 text-center text-gray-500">No sales found for selected filters.</td>
                        </tr>
                    @endforelse
                    <tr class="font-semibold bg-gray-50">
                        <td colspan="3" class="px-4 py-3 text-right text-gray-700 border border-gray-200">Total:</td>
                        <td class="px-4 py-3 text-right text-gray-700 border border-gray-200">{{ number_format($total, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div style="margin:20px 0 0 0;font-size:13px;text-align:left;">
            Printed on: {{ now('Asia/Colombo')->format('Y-m-d - H:i:s') }}
        </div>
        <div style="text-align:right;margin-top:20px">
            <button onclick="printSection('printable-area')" class="no-print"
                style="background:#2563eb;color:#fff;padding:8px 20px;border:none;border-radius:4px;
                     cursor:pointer;font-size:15px;font-weight:bold">
                Print
            </button>
        </div>
    </div>
    <script>
        function printSection(id) {
            const content = document.getElementById(id).innerHTML;
            const win = window.open('', '_blank', 'width=800,height=600');
            win.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Sales Report</title>
                    <style>
                        @page { size: A4 portrait; margin:10mm; }
                        body { font-family: Arial, sans-serif; margin:0; padding:10mm; }
                        table { width:100%; border-collapse:collapse; font-size:12px; }
                        th, td { border:1px solid #e5e7eb; padding:6px; }
                        th { background:#f3f4f6; text-align:left; }
                        .total-row { background:#f3f4f6; font-weight:bold; }
                        @media print {
                            .no-print {
                                display: none !important;
                            }
                        }
                    </style>
                </head>
                <body>
                    ${content}
                </body>
                </html>
            `);
            win.document.close();
            win.focus();
            setTimeout(() => {
                win.print();
            }, 200);
        }
    </script>
</div>
