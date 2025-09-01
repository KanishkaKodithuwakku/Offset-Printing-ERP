<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .invoice-container {
            width: 100%;
            padding: 20px;
            border: 1px solid #e1e1e1;
            border-radius: 8px;
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .invoice-header h3, .invoice-header h4 {
            margin: 0;
        }

        .invoice-header p {
            margin: 5px 0;
        }

        .invoice-info {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .invoice-info div {
            width: 45%;
        }

        .invoice-info h5 {
            margin-bottom: 5px;
        }

        .invoice-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        .invoice-table th, .invoice-table td {
            padding: 8px 12px;
            border: 1px solid #e1e1e1;
        }

        .invoice-table th {
            background-color: #f4f4f4;
        }

        .invoice-table td {
            text-align: right;
        }

        .invoice-table td:first-child {
            text-align: left;
        }

        .invoice-footer {
            display: flex;
            justify-content: flex-end;
            margin-top: 30px;
        }

        .invoice-footer p {
            margin: 5px 0;
        }

        .total {
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="invoice-container">
    <div class="invoice-header">
        <div>
            <h3>Invoice  <span style="font-size:10px;">Status:{{ $invoice->status }}</span></h3>
            <h4>Invoice {{ $invoice->invoice_number }}</h4>
        </div>
        <div>
            <p><strong>From:</strong></p>
            <p>Chandisa Pvt</p>
            <p>1280, Clair Street, Meegoda, Homagama - 02543</p>
            <p><strong>Issued On:</strong> {{$invoice->created_at}}</p>
        </div>
    </div>

    <div class="invoice-info">
        <div>
            <h5><strong>To:</strong> {{$customer->name}}</h5>
            <p>{{$customer->address}}</p>
        </div>
        <div>
            <h5><strong>Due On:</strong> 16 March, 2027</h5>
        </div>
    </div>

    <table class="invoice-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Item Name</th>
                <th>Quantity</th>
                <th>Unit Cost</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoice->invoiceItems as $item)
            <tr>
                <td>#</td>
                <td>{{$item->item->item_name}}</td>
                <td>{{$item->quantity}}</td>
                <td>{{$item->unit_price}}</td>
                <td>{{$item->total_price}}</td>
            </tr>
            @endforeach

        </tbody>
    </table>

    <div class="invoice-footer">
        <div>
            <p>Sub Total amount: {{ number_format($invoice->total_amount, 2) }}</p>
            <p>Vat (10%): {{ number_format($invoice->total_amount * 0.10, 2) }}</p>
            <p class="total">Total : {{ number_format(($invoice->total_amount + ($invoice->total_amount * 0.10)), 2) }}</p>
        </div>
    </div>

</div>

</body>
</html>
