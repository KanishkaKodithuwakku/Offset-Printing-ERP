<!DOCTYPE html>
<html>
<head>
    <title>Cheque Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background: white;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h2 {
            color: #333;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            color: #666;
            margin: 5px 0 0;
            font-size: 14px;
        }
        .cheque-details {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .details-table th {
            background-color: #f8f9fa;
            padding: 12px;
            text-align: left;
            border-bottom: 2px solid #dee2e6;
            font-weight: bold;
            color: #333;
        }
        .details-table td {
            padding: 12px;
            border-bottom: 1px solid #dee2e6;
            color: #333;
        }
        .details-table tr:last-child td {
            border-bottom: none;
        }
        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-deposited {
            background-color: #cce5ff;
            color: #004085;
        }
        .status-return {
            background-color: #f8d7da;
            color: #721c24;
        }
        .status-realize {
            background-color: #d4edda;
            color: #155724;
        }
        .status-cancel {
            background-color: #f8f9fa;
            color: #383d41;
        }
        .no-print {
            text-align: center;
            margin-top: 20px;
            padding: 20px;
        }
        .print-button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        .print-button:hover {
            background-color: #0056b3;
        }
        .invoice-badge {
            display: inline-block;
            margin: 2px;
            padding: 2px 6px;
            background-color: #e9ecef;
            border-radius: 10px;
            font-size: 12px;
        }
        @media print {
            .no-print {
                display: none;
            }
            body {
                margin: 0;
                padding: 15px;
            }
            .cheque-details {
                margin-bottom: 20px;
                page-break-inside: avoid;
            }
            .details-table {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Cheque Details Report</h2>
        <p>Generated on: {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>

    <table class="details-table">
        <thead>
            <tr>
                <th>Customer Name</th>
                <th>Cheque Number</th>
                <th>Bank</th>
                <th>Branch</th>
                <th>Amount</th>
                <th>Date</th>
                <th>Status</th>
                <th>Invoices</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cheques as $cheque)
                <tr>
                    <td>{{ $cheque->customer_name }}</td>
                    <td>{{ $cheque->cheque_number }}</td>
                    <td>{{ $cheque->bank_name }}</td>
                    <td>{{ $cheque->branch_name }}</td>
                    <td>{{ number_format($cheque->amount, 2) }}</td>
                    <td>{{ $cheque->cheque_date->format('Y-m-d') }}</td>
                    <td>
                        <span class="status-badge status-{{ $cheque->status }}">
                            {{ ucfirst($cheque->status) }}
                        </span>
                    </td>
                    <td>
                        @foreach($cheque->paidInvoices as $paidInvoice)
                            <span class="invoice-badge">
                                {{ $paidInvoice->invoice->invoice_number }} ({{ number_format($paidInvoice->amount_paid, 2) }})
                            </span>
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="no-print" style="text-align: right; margin-top: 20px; padding: 20px;">
        <button onclick="window.print()" class="print-button">Print Report</button>
        <button onclick="window.close()" class="print-button" style="background-color: #6c757d; margin-left: 10px;">Close</button>
    </div>

    <script>
        // Auto-print when page loads (optional)
        window.onload = function() {
            @if(request()->has('autoprint'))
                window.print();
            @endif
        };
    </script>
</body>
</html>