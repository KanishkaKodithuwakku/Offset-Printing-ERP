<div class="p-4 bg-white border rounded-xl dark:border-success-500/30 dark:bg-success-500/15">
    <div id="printable-area" style="width:100%;margin:0;padding:0;font-family:'Open Sans',sans-serif;">
        <div>
            <h2 style="font-size: 18px; font-weight: bold;text-align:center;margin:0;padding-top:10px;">{{ config('app.company_name') }}</h2>
        </div>
        <div style="font-size:16px;font-weight:600 ;text-align:center">
            Date wise total Sales Listing
        </div>
        <div style="font-size:13px;text-align:center;margin-bottom:15px;">
            From: <span style="font-weight:600">{{ $startDate }}</span>
            To: <span style="font-weight:600">{{ $endDate }}</span>
        </div>
        <div style="font-size:13px;text-align:left;margin-bottom:15px;">
            Printed on: {{ now('Asia/Colombo')->format('Y-m-d - H:i:s') }}
        </div>
        <div >
              <table style="width:100%;border-collapse:collapse;font-size:12px;margin:0 auto;">
                <thead>
                    <tr>
                        <th style="border:1px solid #000;padding:4px;text-align:left;width: 60px !imporatnt">Invoice Date</th>
                        <th style="border:1px solid #000;padding:4px;text-align:left">Invoice Number</th>
                        <th style="border:1px solid #000;padding:4px;text-align:left">Job Number</th>
                        <th style="border:1px solid #000;padding:4px;text-align:left">Customer</th>
                        <th style="border:1px solid #000;padding:4px;text-align:left">Job Description</th>
                        <th style="border:1px solid #000;padding:4px;text-align:right;width:150px;font-size:12px !important">Invoice Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sales as $invoice)
                        <tr>
                            <td style="border:1px solid #000;padding:2px;text-align:left" class="std">
                                {{ $invoice->created_at->format('Y-m-d') }}
                            </td>
                            <td style="border:1px solid #000;padding:2px;text-align:left" class="std">
                                {{ $invoice->invoice_number }}
                            </td>
                            <td style="border:1px solid #000;padding:2px;text-align:left" class="std">
                                {{ $invoice->order->job_number ?? '-' }}
                            </td>
                            <td style="border:1px solid #000;padding:2px;text-align:left" class="std">
                                {{ $invoice->customer->name ?? '-' }}
                            </td>
                            <td style="border:1px solid #000;padding:2px;text-align:left" class="std">
                                {{ $invoice->order->description ?? '-' }}
                            </td>
                            <td style="border:1px solid #000;padding:2px;text-align:right;font-size:13px">
                                {{ number_format($invoice->total_amount, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tr>
                    <tr style="background-color:#f3f4f6">
                        <td colspan="5" style="border:1px solid #000;padding:6px;text-align:left;font-weight:bold">
                            Total Amount:
                        </td>
                        <td style="border:1px solid #000;padding:6px;text-align:right;font-weight:bold">
                            {{ number_format($sales->sum('total_amount'), 2) }}
                        </td>
                    </tr>
                </tr>
            </table>

        </div>

    </div>

    <!--print button -->
    <div style="margin-top:20px; display:flex;  gap:1rem; justify-content:flex-end;">
        <div>
            <button id="back-button" onclick="window.history.back();" style="display: flex; align-items: center; font-family: outfit; gap: 0.4rem; padding: 0.4rem 1rem; font-size: 0.875rem; font-weight: 500; color: white; border-radius: 0.375rem; background-color: #6b7280; box-shadow: 0 2px 2px rgba(0, 0, 0, 0.1);">
                <svg style="width: 1.5rem; height: 1.5rem; color: white;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back
            </button>
        </div>
        <div>
            <button onclick="printSection('printable-area')"
            style="display: flex; align-items: center; font-family: outfit; gap: 0.4rem; padding: 0.4rem 2rem; font-size: 0.875rem; font-weight: 500; color: white; border-radius: 0.375rem; background-color: #465FFF; box-shadow: 0 2px 2px #465FFF;">
            Print
        </button>
        </div>

    </div>

    <script>
        function printSection(id) {
            const content = document.getElementById(id).innerHTML;
            const win = window.open('', '_blank', 'width=800,height=600');
            win.document.write(`
    <html>
      <head>

        <style>
          @page { size: A4 portrait; margin:5mm; }
          body { font-family: sans-serif; margin:0; padding:0 25px 0 50px; }
          table { width:100%; border-collapse:collapse; font-size:10px; }
          .std{font-size:10px !important;}
          .ltd{font-size:12px !important;}
          th, td { border:1px solid #000; padding:4px; }
          /* Center align all table headers */
          table tr th {
              text-align: center !important;
          }
          /* Increase width for Invoice Date column */
          table tr th:nth-child(1),
          table tr td:nth-child(1) {
              width: 70px !important;
              min-width: 70px !important;
          }
          thead { display:table-header-group; }
          tfoot { display:table-footer-group; }
          tr { page-break-inside:avoid; page-break-after:auto; }
        </style>
      </head>
      <body>
        ${content}
      </body>
    </html>
  `);
            win.document.close();
            win.focus();
            win.print();
            //   win.close();
        }
    </script>

</div>
