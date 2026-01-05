<div class="p-4 bg-white border rounded-xl dark:border-success-500/30 dark:bg-success-500/15" style="font-family:'Open Sans',sans-serif;">
    <div class="mb-2 text-center">
        <h2 style="font-size: 18px; font-weight: bold;text-align:center;">{{ config('app.company_name') }}</h2>
        <h3 class="text-lg font-semibold">Plate Consumption Detail Report</h3>
        <div class="mt-1 mb-4 text-sm">From: <span class="font-semibold">{{ $startDate }}</span> To: <span class="font-semibold">{{ $endDate }}</span></div>
    </div>
    <div class="p-6" x-data="{ showPrint: false }">

        <div class="p-4 bg-white rounded shadow">
            <div class="flex gap-4 pb-4 no-print">
                <div class="flex-1">
                    <label class="block text-xs font-medium text-gray-700">Plate Name</label>
                    <select wire:model.live="selectedItemId"
                        class="dark:bg-dark-900 shadow-theme-xs w-full focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 pr-11 pl-4 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                        <option value="">All Plates</option>
                        @foreach($plates as $plate)
                            <option value="{{ $plate->id }}">{{ $plate->item_name }} ({{ $plate->item_code }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="relative">
                    <label class="block text-xs font-medium text-gray-700">From</label>
                    <input type="date" wire:model.live="startDate" onclick="this.showPicker()"
                        class="dark:bg-dark-900 shadow-theme-xs w-full focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 pl-4 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                    <span class="absolute mt-2 text-gray-500 -translate-y-1/2 pointer-events-none top-1/2 right-3 dark:text-gray-400">
                        <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M6.66659 1.5415C7.0808 1.5415 7.41658 1.87729 7.41658 2.2915V2.99984H12.5833V2.2915C12.5833 1.87729 12.919 1.5415 13.3333 1.5415C13.7475 1.5415 14.0833 1.87729 14.0833 2.2915V2.99984L15.4166 2.99984C16.5212 2.99984 17.4166 3.89527 17.4166 4.99984V7.49984V15.8332C17.4166 16.9377 16.5212 17.8332 15.4166 17.8332H4.58325C3.47868 17.8332 2.58325 16.9377 2.58325 15.8332V7.49984V4.99984C2.58325 3.89527 3.47868 2.99984 4.58325 2.99984L5.91659 2.99984V2.2915C5.91659 1.87729 6.25237 1.5415 6.66659 1.5415ZM6.66659 4.49984H4.58325C4.30711 4.49984 4.08325 4.7237 4.08325 4.99984V6.74984H15.9166V4.99984C15.9166 4.7237 15.6927 4.49984 15.4166 4.49984H13.3333H6.66659ZM15.9166 8.24984H4.08325V15.8332C4.08325 16.1093 4.30711 16.3332 4.58325 16.3332H15.4166C15.6927 16.3332 15.9166 16.1093 15.9166 15.8332V8.24984Z"
                                fill="" />
                        </svg>
                    </span>
                </div>
                <div class="relative">
                    <label class="block text-xs font-medium text-gray-700">To</label>
                    <input type="date" wire:model.live="endDate" onclick="this.showPicker()"
                        class="dark:bg-dark-900 shadow-theme-xs w-full focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 pl-4 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                    <span class="absolute mt-2 text-gray-500 -translate-y-1/2 pointer-events-none top-1/2 right-3 dark:text-gray-400">
                        <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M6.66659 1.5415C7.0808 1.5415 7.41658 1.87729 7.41658 2.2915V2.99984H12.5833V2.2915C12.5833 1.87729 12.919 1.5415 13.3333 1.5415C13.7475 1.5415 14.0833 1.87729 14.0833 2.2915V2.99984L15.4166 2.99984C16.5212 2.99984 17.4166 3.89527 17.4166 4.99984V7.49984V15.8332C17.4166 16.9377 16.5212 17.8332 15.4166 17.8332H4.58325C3.47868 17.8332 2.58325 16.9377 2.58325 15.8332V7.49984V4.99984C2.58325 3.89527 3.47868 2.99984 4.58325 2.99984L5.91659 2.99984V2.2915C5.91659 1.87729 6.25237 1.5415 6.66659 1.5415ZM6.66659 4.49984H4.58325C4.30711 4.49984 4.08325 4.7237 4.08325 4.99984V6.74984H15.9166V4.99984C15.9166 4.7237 15.6927 4.49984 15.4166 4.49984H13.3333H6.66659ZM15.9166 8.24984H4.08325V15.8332C4.08325 16.1093 4.30711 16.3332 4.58325 16.3332H15.4166C15.6927 16.3332 15.9166 16.1093 15.9166 15.8332V8.24984Z"
                                fill="" />
                        </svg>
                    </span>
                </div>
                <div class="flex items-center mt-6">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" wire:model.live="showAll"
                            class="w-4 h-4 text-brand-600 border-gray-300 rounded focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-700">
                        <span class="text-xs font-medium text-gray-700 dark:text-gray-300">Show All (Print)</span>
                    </label>
                </div>
                 <div class="flex items-end">
                     <button onclick="printReport()"
                         class="flex items-center justify-center gap-2 px-4 py-2 text-xs font-medium text-white bg-brand-500 rounded-lg hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 dark:bg-brand-500 dark:hover:bg-brand-600">
                         <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                             <polyline points="6 9 6 2 18 2 18 9"></polyline>
                             <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                             <rect x="6" y="14" width="12" height="8"></rect>
                         </svg>
                         Print Report
                     </button>
                 </div>
            </div>

            <div id="print-section">
                <div class="overflow-x-auto">
                    <table class="min-w-full mt-4 text-sm border-gray-200 table-auto">
                        <thead class="h-10 bg-gray-100">
                            <tr>
                                <th class="px-2 text-xs text-left text-gray-500 dark:text-gray-400">Plate Name</th>
                                <th class="px-2 text-xs text-left text-gray-500 dark:text-gray-400">Job Number</th>
                                <th class="px-2 text-xs text-left text-gray-500 dark:text-gray-400">Job Description</th>
                                <th class="px-2 text-xs text-left text-gray-500 dark:text-gray-400">Dispatch Number</th>
                                <th class="px-2 text-xs text-left text-gray-500 dark:text-gray-400">Customer Name</th>
                                <th class="px-2 text-xs text-left text-gray-500 dark:text-gray-400">Quantity Used</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $totalUsed = 0; @endphp
                            @forelse ($usedPlates as $plate)
                                @php
                                    $quantity = $plate->quantity;
                                    $totalUsed += $quantity;
                                @endphp
                                <tr class="border-b border-gray-200">
                                    <td class="px-3 py-2 text-xs text-left">{{ $plate->item_name }}</td>
                                    <td class="px-3 py-2 text-xs text-left">{{ $plate->job_number ?? '-' }}</td>
                                    <td class="px-3 py-2 text-xs text-left">{{ $plate->job_description ?? '-' }}</td>
                                    <td class="px-3 py-2 text-xs text-left">{{ $plate->dispatch_number ?? '-' }}</td>
                                    <td class="px-3 py-2 text-xs text-left">{{ $plate->customer_name ?? '-' }}</td>
                                    <td class="px-3 py-2 text-xs text-left">{{ number_format($quantity) }}</td>
                                </tr>
                            @empty
                                <tr class="border-b border-gray-200">
                                    <td colspan="6" class="px-3 py-2 text-xs text-center text-gray-500">No used plates found for selected filters.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="bg-gray-100">
                                <td colspan="5" class="px-3 py-2 text-xs font-semibold text-right">Total Used:</td>
                                <td class="px-3 py-2 text-xs font-semibold text-left">{{ number_format($totalUsed) }}</td>

                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            @if (!$showAll)
                <div class="flex justify-end mt-4">
                    {{ $usedPlates->links('vendor.pagination.custom-tailwind') }}
                </div>
            @endif
        </div>
    </div>
    
    {{-- MOVED STYLE AND SCRIPT INSIDE THE ROOT DIV --}}
    <style>
        @media print {
            body, table, th, td, h2, h3, div {
                font-family: 'Outfit', sans-serif !important;
                font-weight: normal !important;
            }

            @page {
                size: auto;
                margin: 10mm;
            }

            button, a, .no-print {
                display: none !important;
            }

            body {
                margin: 0;
                padding: 0;
                font-size: 10pt !important;
            }

            table {
                width: 100% !important;
                font-size: 10pt !important;
                page-break-inside: auto;
                font-family: 'Outfit', sans-serif !important;
                border-left: 2px solid #000 !important;
                border-right: 2px solid #000 !important;
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

            /* Bold borders for header and footer in print */
            thead th,
            tfoot td {
                border: 2px solid #000 !important;
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
     <script>
         function printReport() {
             // Get the print section
             const printContent = document.getElementById('print-section').cloneNode(true);
             const originalContent = document.body.innerHTML;

             // Extract the total value from tfoot before removing it
             const table = printContent.querySelector('table');
             const tfoot = table ? table.querySelector('tfoot') : null;
             let totalValue = '';
             let totalRowCells = [];
             if (tfoot) {
                 const totalRow = tfoot.querySelector('tr');
                 if (totalRow) {
                     const cells = totalRow.querySelectorAll('td');
                     cells.forEach(cell => {
                         totalRowCells.push({
                             text: cell.textContent.trim(),
                             colspan: cell.getAttribute('colspan') || '1',
                             className: cell.className,
                             align: cell.style.textAlign || (cell.classList.contains('text-right') ? 'right' : 'left')
                         });
                     });
                     const totalCell = totalRow.querySelector('td:last-child');
                     totalValue = totalCell ? totalCell.textContent.trim() : '';
                 }
                 // Remove tfoot from the table so it doesn't repeat on every page
                 tfoot.remove();
             }

             // Create a new window for printing
             const printWindow = window.open('', '_blank');

             // Get the report details - read current date values from DOM inputs
             const reportTitle = 'Plate Consumption Detail Report';
             // Find all date inputs and get their current values
             const dateInputs = Array.from(document.querySelectorAll('input[type="date"]'));
             const startDate = (dateInputs.length >= 1 && dateInputs[0].value) ? dateInputs[0].value : '{{ $startDate }}';
             const endDate = (dateInputs.length >= 2 && dateInputs[1].value) ? dateInputs[1].value : '{{ $endDate }}';
             const companyName = '{{ config("app.company_name") }}';

             // Build total row HTML if exists
             let totalRowHtml = '';
             if (totalRowCells.length > 0) {
                 const cellsHtml = totalRowCells.map(function(cell, index) {
                     let cellStyle = 'text-align: ' + cell.align + ';';
                     // Reduce width of the last cell (right side)
                     if (index === totalRowCells.length - 1) {
                         cellStyle += ' width: 15%; max-width: 150px;';
                     }
                     return '<td colspan="' + cell.colspan + '" class="' + cell.className + '" style="' + cellStyle + '">' + cell.text + '</td>';
                 }).join('');
                 // NOTICE: I escaped the closing div tag below (<\/div>) to prevent parser errors
                 totalRowHtml = '<div class="total-row-container"><table class="total-table"><tbody><tr>' + cellsHtml + '</tr></tbody></table><\/div>';
             }

             // Write the HTML content
             printWindow.document.write(`
                 <!DOCTYPE html>
                 <html>
                 <head>
                     <title>${reportTitle}</title>
                     <style>
                         @page {
                             size: auto;
                             margin: 10mm;
                         }

                         body {
                             margin: 0;
                             padding: 20px;
                             font-family: 'Arial', sans-serif;
                             font-size: 10pt;
                             color: #000;
                         }

                         .header {
                             text-align: center;
                             margin-bottom: 20px;
                         }

                         .header h2 {
                             font-size: 18px;
                             font-weight: bold;
                             margin: 5px 0;
                         }

                         .header h3 {
                             font-size: 14px;
                             font-weight: bold;
                             margin: 5px 0;
                         }

                         .header .date-range {
                             font-size: 11px;
                             margin-top: 10px;
                         }

                         table {
                             width: 100%;
                             border-collapse: collapse;
                             font-size: 9pt;
                             margin-top: 10px;
                             border-left: 2px solid #000000;
                             border-right: 2px solid #000000;
                         }

                         thead {
                             background-color: #f3f4f6;
                         }

                        th, td {
                            border: 1px solid #000000;
                            padding: 6px 8px;
                            text-align: left;
                        }

                        /* Bold borders for header and footer inside print window */
                        thead th,
                        tfoot td {
                            border: 2px solid #000000;
                        }

                         th {
                             font-weight: bold;
                             font-size: 8pt;
                         }

                         tfoot tr {
                             background-color: #f3f4f6;
                             font-weight: bold;
                         }

                         tfoot td {
                             padding: 6px 8px;
                         }

                         .text-center {
                             text-align: center;
                         }

                         .text-right {
                             text-align: right;
                         }

                         .total-row-container {
                             margin-top: 20px;
                             page-break-before: avoid;
                             page-break-inside: avoid;
                         }

                         .total-table {
                             width: 100%;
                             border-collapse: collapse;
                             border-left: 2px solid #000000;
                             border-right: 2px solid #000000;
                             margin-top: 20px;
                         }

                         .total-table td {
                             border: 2px solid #000000;
                             padding: 6px 8px;
                             background-color: #f3f4f6;
                             font-weight: bold;
                         }

                         .total-table td:last-child {
                             width: 15%;
                             max-width: 150px;
                         }

                         @media print {
                             body {
                                 padding: 10px;
                             }
                             table {
                                 page-break-inside: auto;
                             }
                             tr {
                                 page-break-inside: avoid;
                                 page-break-after: auto;
                             }
                             thead {
                                 display: table-header-group;
                             }
                             .total-row-container {
                                 page-break-before: avoid;
                                 page-break-inside: avoid;
                                 display: block;
                             }
                         }
                     </style>
                 </head>
                 <body>
                     <div class="header">
                         <h2>${companyName}</h2>
                         <h3>${reportTitle}</h3>
                         <div class="date-range">
                             <strong>From:</strong> ${startDate} <strong>To:</strong> ${endDate}
                         </div>
                     </div>
                     ${printContent.innerHTML}
                     ${totalRowHtml}
                 </body>
                 </html>
             `);

             printWindow.document.close();

             // Wait for content to load, then print
             printWindow.onload = function() {
                 setTimeout(function() {
                     printWindow.print();
                     printWindow.close();
                 }, 250);
             };
         }

         // Support keyboard shortcut Ctrl+P or Cmd+P
         document.addEventListener('keydown', function(event) {
             if ((event.ctrlKey || event.metaKey) && event.key === 'p') {
                 event.preventDefault();
                 printReport();
             }
         });
     </script>
</div>