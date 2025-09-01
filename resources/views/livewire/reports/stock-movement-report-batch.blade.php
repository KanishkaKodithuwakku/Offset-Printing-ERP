<div id="printable-area">
    <div class="p-4">
        <h2 class="text-lg font-semibold mb-4">Stock Movement Report</h2>

        <table class="min-w-full text-sm text-left border border-gray-300">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border">Table</th>
                    <th class="px-4 py-2 border">Item</th>
                    <th class="px-4 py-2 border">Quantity</th>
                    <td class="px-4 py-2 border">Previous Balance</td>
                    <td class="px-4 py-2 border">Current Balance</td>
                    <th class="px-4 py-2 border">Last Movement</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($movements as $m)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 border">{{ $m->table_name }}</td>
                        <td class="px-4 py-2 border">{{ $m->item_name }}</td>
                        <td class="px-4 py-2 border">{{ $m->total_quantity }}</td>
                        <td class="px-4 py-2 border">{{ $m->previous_balance }}</td>
                        <td class="px-4 py-2 border">{{ $m->current_balance }}</td>
                        <td class="px-4 py-2 border">
                            {{ \Carbon\Carbon::parse($m->last_movement_date)->format('Y-m-d H:i') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center p-4">No stock movement found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Print Button -->
    <div style="text-align:right;margin-top:20px">
        <button onclick="printSection('printable-area')" 
                style="background:#2563eb;color:#fff;padding:8px 20px;border:none;border-radius:4px;
                       cursor:pointer;font-size:15px;font-weight:bold">
            Print
        </button>
    </div>
</div>

<script>
function printSection(id) {
  const content = document.getElementById(id);
  if (content) {
    const win = window.open('', '_blank', 'width=800,height=600');
    win.document.write(`
      <html>
        <head>
          <title>Print Preview</title>
          <style>
            @page { size: A4 portrait; margin:10mm; }
            body { font-family: Arial, Helvetica, sans-serif; margin:0; padding:0; }
            table { width:100%; border-collapse:collapse; font-size:12px; }
            th, td { border:1px solid #e5e7eb; padding:4px; }
            thead { display:table-header-group; }
            tfoot { display:table-footer-group; }
            tr { page-break-inside:avoid; page-break-after:auto; }
          </style>
        </head>
        <body>
          ${content.innerHTML}
        </body>
      </html>
    `);
    win.document.close();
    win.focus();
    win.print();
    win.close();
  } else {
    console.error("Element with ID '" + id + "' not found.");
  }
}
</script>
