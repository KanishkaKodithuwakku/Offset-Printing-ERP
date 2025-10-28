<div x-data="{ printCount: @entangle('printedCount'),currentPage: 1 }" x-init="
        const targetPath = '/invoice/print-preview';

        const isOnTargetPage = () => window.location.pathname.startsWith(targetPath);

        window.addEventListener('keydown', function (e) {
            if (!isOnTargetPage()) return;
            if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                if (printCount > 0) {
                    e.preventDefault();
                    alert('Printing is disabled for duplicate invoices.');
                }
            }
        });

        window.addEventListener('contextmenu', function (e) {
            if (!isOnTargetPage()) return;
            e.preventDefault();
            if (printCount > 0) {
                alert('Right-click printing is disabled for duplicate invoices.');
            }
        });
    " id="printable-area">


    <style>
        @media print {
            /* @page {
                size: 210mm 148.5mm;
                margin: 0;
            } */

            body {
                margin: 0;
                padding: 0;
            }

            body * {
                visibility: hidden;
            }

            #printable-area,
            #printable-area * {
                visibility: visible;
            }

            #print-button {
                display: none !important;
            }

            .print-button {
                display: none !important;
            }
        }
    </style>


    <!-- customer copy-->

    <!-- Invoice Header -->
    <div id="top-border" style="display: flex; justify-content: space-between;">
        <div>
            <h2 style="font-size: 18px; font-weight: bold; margin: 0;">{{ config('app.company_name') }}</h2>
            <p style="margin: 2px 0;padding:0px;">Customer Name: {{ $invoice->customer->name }}</p>
            <p style="margin: 2px 0;padding:0px;">Customer Address: {{ $invoice->customer->address }}</p>
            <p style="margin: 2px 0;padding:0px;">Job No: {{ $invoice->order->job_number }}</p>
            <p style="margin: 2px 0;padding:0px;">Job Description: {{ $invoice->order->description }}</p>
        </div>
        <div style="text-align: right;">
            <h2 style="font-size: 18px; font-weight: bold; margin: 0;">
                Invoice
                @if ($invoice->print_count > 1)
                (Duplicate #{{ $invoice->print_count }})
                @endif
            </h2>
            <p style="margin: 2px 0;padding:0px;font-weight: 600;">{{ $invoice->customer->is_credit_customer ? 'Credit' : 'Cash' }}</p>
            <p style="margin: 2px 0;padding:0px;">Date: {{ $invoice->created_at->format('F j, Y') }}</p>
            <p style="margin: 2px 0;padding:0px;">Invoice No: {{ $invoice->invoice_number }}</p>
            <p style="margin: 2px 0;padding:0px;">PO No: {{ $invoice->order->customer_po_number }}</p>

        </div>
    </div>

    <!-- Invoice Items Table -->
    <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
        <thead>
            <tr>
                <th
                    style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000; padding: 2px; text-align:center;width:8%">
                    Qty</th>
                <th
                    style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000; padding: 2px; text-align:center;width:40%">
                    Description</th>
                <th
                    style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000; padding: 2px 4px 2px 0; text-align:right;width:25%">
                    Unit Price</th>
                <th
                    style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000;border-right: 1px solid #000; padding: 2px 4px 2px 0; text-align: right;width:30%">
                    Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoiceItems as $item)
            <tr>
                <td style="border-left: 1px solid #000; padding: 2px;text-align:center">{{ $item->quantity }}</td>
                <td style="padding: 2px 0 2px 35px; ">
                    @if($item->item)
                        {{ $item->item->item_name }}
                    @elseif($item->expense)
                        {{ $item->expense->expense_name }}
                    @else
                        Unknown Item
                    @endif
                </td>
                <td style="padding: 2px;text-align:right;padding: 2px 4px 2px 0px;">{{ number_format($item->unit_price,
                    2) }}</td>
                <td style="border-right: 1px solid #000; padding: 2px 6px 2px 0px;text-align:right">{{
                    number_format($item->total_price, 2) }}</td>
            </tr>
            @endforeach

            <!-- Blank row -->
            @if ($invoice->order->plate_backing)
                <tr>
                <td style="border-left: 1px solid #000; padding: 2px;text-align:center">{{$invoice->order->backing_qty}}</td>
                <td style="padding: 2px 0 2px 35px; ">Backed Plates</td>
                <td style="padding: 2px;text-align:right;padding: 2px 4px 2px 0px;">{{$invoice->backed_plates_price}}</td>
                <td style="border-right: 1px solid #000; padding: 2px 6px 2px 0px;text-align:right">{{
                    number_format($invoice->backed_plates_price*$invoice->order->backing_qty, 2) }}</td>
            </tr>
            @endif


            <!-- Grand Total Row -->
            <tr>
                <td colspan="3"
                    style="border: 2px solid #000; border-right: none; padding: 2px; text-align: right; font-weight: bold;">
                    Grand Total</td>
                <td
                    style="border: 2px solid #000; border-left: none; padding: 2px 4px 2px 0; font-weight: bold;text-align:right">
                    {{ number_format($invoice->total_amount, 2) }}</td>
            </tr>
        </tbody>
    </table>




    <!-- Invoice Footer -->
    <div style="margin-top: 20px;">
        <p style="margin: 2px 0;">Cheques should be drawn in favour of "Master Graphics Services (Pvt) Ltd."</p>
        <p style="margin: 2px 0;">Total Outstanding Balance: {{ number_format($customerTotalAmount, 2) }}</p>
    </div>

    <!-- Signature Section -->
    <div style="margin-top: 48px; display: flex; justify-content: space-between;">
        <div style="text-align: center;">
            <div
                style="margin-top: 5px; border-top: 1px dotted #000; width: 200px; margin-left: auto; margin-right: auto;">
            </div>
            <p style="margin-top: 10px;">Customer Signature</p>
        </div>
        <div style="text-align: center;">
            <div
                style="margin-top: 5px; border-top: 1px dotted #000; width: 200px; margin-left: auto; margin-right: auto;">
            </div>
            <p style="margin-top: 8px;">Prepared by</p>
        </div>
    </div>

    <!-- Print Button -->

    <div style=" display: flex; justify-content: flex-end; gap: 1rem; padding: 1rem; margin-top: 1rem;"
        class="print-button">
        <button class="print-button" id="back-button" onclick="window.history.back();"
            style="display: flex; align-items: center; font-family: outfit; gap: 0.5rem; padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; color: white; border-radius: 0.375rem; background-color: #6b7280; box-shadow: 0 2px 2px rgba(0, 0, 0, 0.1);">
            <svg style="width: 1.5rem; height: 1.5rem; color: white;" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back
        </button>
        {{-- @if ($invoice->print_count === 0)
        <button id="print-button" onclick="showCompanyCopyAndPrint()" wire:click="$refresh" wire:loading.attr="disabled"
            style="display: flex; align-items: center; gap: 0.5rem; font-family: outfit; padding: 0.5rem 1rem;
                   font-size: 0.875rem; font-weight: 500; color: white; border-radius: 0.375rem; background-color: #465FFF;
                   box-shadow: 0 2px 2px rgba(0, 0, 0, 0.1); margin-right: 2.5rem;">
            <svg style="width: 1.5rem; height: 1.5rem; color: white;" aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linejoin="round" stroke-width="2"
                    d="M16.444 18H19a1 1 0 0 0 1-1v-5a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1h2.556M17 11V5a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v6h10ZM7 15h10v4a1 1 0 0 1-1 1H8a1 1 0 0 1-1-1v-4Z" />
            </svg>
            Print
        </button>
        @else
        <button id="print-button" disabled class="bg-gray-200 cursor-not-allowed" style="display: flex; align-items: center; gap: 0.5rem; font-family: outfit; padding: 0.5rem 1rem;
                   font-size: 0.875rem; font-weight: 500; color: white; border-radius: 0.375rem; background-color: #465FFF;
                   box-shadow: 0 2px 2px rgba(0, 0, 0, 0.1); margin-right: 2.5rem;">
            <svg style="width: 1.5rem; height: 1.5rem; color: white;" aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linejoin="round" stroke-width="2"
                    d="M16.444 18H19a1 1 0 0 0 1-1v-5a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1h2.556M17 11V5a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v6h10ZM7 15h10v4a1 1 0 0 1-1 1H8a1 1 0 0 1-1-1v-4Z" />
            </svg>
            Print
        </button>
        @endif --}}


        <button id="print-button" onclick="showCompanyCopyAndPrint()" wire:click="$refresh"
            style="display: flex; align-items: center; gap: 0.5rem; font-family: outfit; padding: 0.5rem 1rem;
                   font-size: 0.875rem; font-weight: 500; color: white; border-radius: 0.375rem; background-color: #465FFF;
                   box-shadow: 0 2px 2px rgba(0, 0, 0, 0.1); margin-right: 2.5rem;">
            <svg style="width: 1.5rem; height: 1.5rem; color: white;" aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linejoin="round" stroke-width="2"
                    d="M16.444 18H19a1 1 0 0 0 1-1v-5a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1h2.556M17 11V5a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v6h10ZM7 15h10v4a1 1 0 0 1-1 1H8a1 1 0 0 1-1-1v-4Z" />
            </svg>
            Print
        </button>

    </div>

</div>
{{-- <script>
    function invoicePrintGuard(entangledPrintCount) {
        return {
            printCount: entangledPrintCount,
            keyHandler(e) {
                if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                    if (this.printCount > 0) {
                        e.preventDefault();
                        alert('Printing is disabled for duplicate invoices.');
                    }
                }
            },
            contextHandler(e) {
                if (this.printCount > 0) {
                    e.preventDefault();
                    alert('Right-click printing is disabled for duplicate invoices.');
                }
            },
            init() {
                // Bind handlers
                this._boundKeyHandler = this.keyHandler.bind(this);
                this._boundContextHandler = this.contextHandler.bind(this);

                window.addEventListener('keydown', this._boundKeyHandler);
                window.addEventListener('contextmenu', this._boundContextHandler);

                // Clean up when Alpine destroys the component
                this.$el.addEventListener('alpine:clean', () => {
                    window.removeEventListener('keydown', this._boundKeyHandler);
                    window.removeEventListener('contextmenu', this._boundContextHandler);
                });
            }
        }
    }
</script> --}}
<script>
    function showCompanyCopyAndPrint() {
        @this.call('incrementPrintCount').then(() => {
            document.querySelector('#top-border').style.marginTop = '-50px';
            window.print();
            document.querySelector('#top-border').style.marginTop = '0px';
        });
    }
</script>
