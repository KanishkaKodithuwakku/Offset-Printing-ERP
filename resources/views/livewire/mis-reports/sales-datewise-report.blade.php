<div class="p-4 bg-white border rounded-xl dark:border-success-500/30 dark:bg-success-500/15" style="font-family:'Outfit',sans-serif;">
    <div class="mb-2 text-center">
        <h2 style="font-size: 18px; font-weight: bold;text-align:center;">{{ config('app.company_name') }}</h2>
        <h3 class="text-lg font-semibold">Date wise total Sales Listing</h3>
        <div class="mt-1 mb-4 text-sm">From: <span class="font-semibold">{{ $startDate }}</span> To: <span class="font-semibold">{{ $endDate }}</span></div>
    </div>
    <div class="p-6" x-data="{ showPrint: false }">
        <h1 class="text-lg font-semibold text-gray-800 dark:text-white/90">Sales Report - Date Wise Listing</h1>
        <div class="p-4 bg-white rounded shadow">
             <!-- Date range -->
            <div class="flex gap-4 pb-4">
                <div class="relative">
                    <label class="block text-xs font-medium text-gray-700">From</label>
                    <input type="date" wire:model.live="startDate" onclick="this.showPicker()"
                        class="dark:bg-dark-900  datepickerTwo shadow-theme-xs w-full focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8  appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 pl-4 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                    <span
                        class="absolute mt-2 text-gray-500 -translate-y-1/2 pointer-events-none top-1/2 right-3 dark:text-gray-400">
                        <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M6.66659 1.5415C7.0808 1.5415 7.41658 1.87729 7.41658 2.2915V2.99984H12.5833V2.2915C12.5833 1.87729 12.919 1.5415 13.3333 1.5415C13.7475 1.5415 14.0833 1.87729 14.0833 2.2915V2.99984L15.4166 2.99984C16.5212 2.99984 17.4166 3.89527 17.4166 4.99984V7.49984V15.8332C17.4166 16.9377 16.5212 17.8332 15.4166 17.8332H4.58325C3.47868 17.8332 2.58325 16.9377 2.58325 15.8332V7.49984V4.99984C2.58325 3.89527 3.47868 2.99984 4.58325 2.99984L5.91659 2.99984V2.2915C5.91659 1.87729 6.25237 1.5415 6.66659 1.5415ZM6.66659 4.49984H4.58325C4.30711 4.49984 4.08325 4.7237 4.08325 4.99984V6.74984H15.9166V4.99984C15.9166 4.7237 15.6927 4.49984 15.4166 4.49984H13.3333H6.66659ZM15.9166 8.24984H4.08325V15.8332C4.08325 16.1093 4.30711 16.3332 4.58325 16.3332H15.4166C15.6927 16.3332 15.9166 16.1093 15.9166 15.8332V8.24984Z"
                                fill="" />
                        </svg>
                    </span>
                </div>
                <div class="relative">
                    <label class="block text-xs font-medium text-gray-700">To</label>
                    <input type="date" wire:model.live="endDate" onclick="this.showPicker()"
                        class="dark:bg-dark-900  datepickerTwo shadow-theme-xs w-full focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8  appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 pl-4 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                    <span
                        class="absolute mt-2 text-gray-500 -translate-y-1/2 pointer-events-none top-1/2 right-3 dark:text-gray-400">
                        <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M6.66659 1.5415C7.0808 1.5415 7.41658 1.87729 7.41658 2.2915V2.99984H12.5833V2.2915C12.5833 1.87729 12.919 1.5415 13.3333 1.5415C13.7475 1.5415 14.0833 1.87729 14.0833 2.2915V2.99984L15.4166 2.99984C16.5212 2.99984 17.4166 3.89527 17.4166 4.99984V7.49984V15.8332C17.4166 16.9377 16.5212 17.8332 15.4166 17.8332H4.58325C3.47868 17.8332 2.58325 16.9377 2.58325 15.8332V7.49984V4.99984C2.58325 3.89527 3.47868 2.99984 4.58325 2.99984L5.91659 2.99984V2.2915C5.91659 1.87729 6.25237 1.5415 6.66659 1.5415ZM6.66659 4.49984H4.58325C4.30711 4.49984 4.08325 4.7237 4.08325 4.99984V6.74984H15.9166V4.99984C15.9166 4.7237 15.6927 4.49984 15.4166 4.49984H13.3333H6.66659ZM15.9166 8.24984H4.08325V15.8332C4.08325 16.1093 4.30711 16.3332 4.58325 16.3332H15.4166C15.6927 16.3332 15.9166 16.1093 15.9166 15.8332V8.24984Z"
                                fill="" />
                        </svg>
                    </span>
                </div>

                {{-- <div class="relative flex justify-end w-full">
                    <button onclick="printPreview()" class="px-4 py-1 text-white rounded-lg bg-brand-500">
                        Print Preview
                    </button>
                </div> --}}
            </div>

            <!-- Table Section -->
            <div id="print-section">
                <div class="overflow-hidden rounded-xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm border-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-3 py-2 text-sm font-semibold text-left text-gray-500">Invoice Date</th>
                                    <th class="px-3 py-2 text-sm font-semibold text-left text-gray-500">Invoice Number</th>
                                    <th class="px-3 py-2 text-sm font-semibold text-left text-gray-500">Job Number</th>
                                    <th class="px-3 py-2 text-sm font-semibold text-left text-gray-500">Customer</th>
                                    <th class="px-3 py-2 text-sm font-semibold text-left text-gray-500">Job Description</th>
                                    <th class="px-3 py-2 text-sm font-semibold text-left text-gray-500">Invoice Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($sales as $invoice)
                                    <tr class="border-b border-gray-200">
                                        <td class="px-3 py-2 text-xs text-left">{{ $invoice->created_at->format('Y-m-d') }}</td>
                                        <td class="px-3 py-2 text-xs text-left">{{ $invoice->invoice_number }}</td>
                                        <td class="px-3 py-2 text-xs text-left">{{ $invoice->order->job_number ?? '-' }}</td>
                                        <td class="px-3 py-2 text-xs text-left">{{ $invoice->customer->name ?? '-' }}</td>
                                        <td class="px-3 py-2 text-xs text-left">{{ $invoice->order->description ?? '-' }}</td>
                                        <td class="px-3 py-2 text-xs text-left">{{ number_format($invoice->total_amount, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr class="border-b border-gray-200">
                                        <td colspan="6" class="px-3 py-2 text-xs text-center text-gray-500">No sales found for selected dates.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr class="bg-gray-100">
                                    <td colspan="5" class="px-3 py-2 text-sm font-semibold text-left text-gray-500">Total Amount:</td>
                                    <td class="px-3 py-2 text-sm font-semibold text-left text-gray-500">{{ number_format($sales->sum('total_amount'), 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="flex justify-end mt-4">
                {{ $sales->links('vendor.pagination.custom-tailwind') }}
            </div>
            <div class="flex justify-end mt-4">
                <a :href="`/mis-reports/sales-datewise-report/print?startDate=${$wire.startDate}&endDate=${$wire.endDate}`"
                    class="px-4 py-2 font-semibold text-white bg-brand-500 rounded-md hover:bg-brand-500"  >
                    Print Preview
                </a>
            </div>
        </div>
    </div>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap');

        body {
            font-family: 'Outfit', sans-serif;
        }

        @media print {
            @page {
                size: auto;
                margin: 10mm;
            }

            body {
                margin: 0;
                padding: 0;
                font-size: 10pt !important;
                font-family: 'Outfit', sans-serif !important;
            }

            table {
                width: 100% !important;
                font-size: 10pt !important;
                page-break-inside: auto;
                font-family: 'Outfit', sans-serif !important;
            }

            tr {
                page-break-inside: avoid;
            }

            thead {
                display: table-header-group;
            }

            tfoot {
                display: table-footer-group;
            }

            button, a {
                display: none !important;
            }
        }

        @media (max-width: 600px) {
            table {
                font-size: 10px !important;
            }

            th, td {
                padding: 4px !important;
            }

            h2 {
                font-size: 14px !important;
            }

            h3 {
                font-size: 12px !important;
            }
        }
    </style>
</div>