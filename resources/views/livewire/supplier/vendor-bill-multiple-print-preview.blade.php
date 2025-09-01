<div id="printable-area" style="width: 100%; margin: 0; padding: 0; font-family: 'Open Sans', sans-serif;">
    <style>
        @media print {
            @page {
                size: A4;
                margin: 10mm;
            }

            body * {
                visibility: hidden;
            }

            #printable-area,
            #printable-area * {
                visibility: visible;
            }

            #printable-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            .no-print {
                display: none !important;
            }
        }
    </style>

    <div style="margin: auto; font-family: 'Open Sans', sans-serif; padding: 20px;" id="top-border">
        <div style="margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between;">
                <h2 style="font-size: 18px; font-weight: bold; text-align: left; margin-bottom: 15px;">
                    {{ config('app.company_name') }}
                </h2>
                <div>
                    <h2 style="font-size: 16px; font-weight: bold; ">
                        Payment Receipt
                    </h2>
                    <div style="display: flex;">
                        <div style="font-weight: bold;">Receipt No: <span style="font-weight: normal;">{{ $receiptNumber }}</span></div>
                    </div>
                </div>
            </div>

            <div style="font-size: 14px;">
                <div style="display: flex; padding: 5px 0;">
                    <div style="font-weight: bold; min-width: 150px;">Payment Date</div>
                    <div>: {{ \Carbon\Carbon::parse($paymentDate)->format('d/m/Y') }}</div>
                </div>

                <div style="display: flex; padding: 5px 0;">
                    <div style="font-weight: bold; min-width: 150px;">Payment Account</div>
                    <div>: {{ $ledger->name ?? 'N/A' }}</div>
                </div>

                <div style="display: flex; padding: 5px 0;">
                    <div style="font-weight: bold; min-width: 150px;">Payment Method</div>
                    <div>: {{ $paymentMethod === 'CA' ? 'Cash' : 'Cheque' }}</div>
                </div>

                @if($checkNumber)
                <div style="display: flex; padding: 5px 0;">
                    <div style="font-weight: bold; min-width: 150px;">Cheque Number</div>
                    <div>: {{ $checkNumber }}</div>
                </div>
                @endif

                @if($payment_voucher_no)
                <div style="display: flex; padding: 5px 0;">
                    <div style="font-weight: bold; min-width: 150px;">Payment Voucher No.</div>
                    <div>: {{ $payment_voucher_no }}</div>
                </div>
                @endif
            </div>
        </div>

        <p style="font-size: 14px;">Payment has been successfully recorded for the following bills:</p>

        <table style="width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 14px;">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th style="text-align: left; border: 1px solid #ccc; padding: 8px;">DATE DUE</th>
                    <th style="text-align: left; border: 1px solid #ccc; padding: 8px;">VENDOR</th>
                    <th style="text-align: left; border: 1px solid #ccc; padding: 8px;">INVOICE NO</th>
                    <th style="text-align: right; border: 1px solid #ccc; padding: 8px;">AMOUNT PAID</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $payment)
                <tr>
                    <td style="border: 1px solid #ccc; padding: 8px;">
                        {{ \Carbon\Carbon::parse($payment->vendorBill->bill_due_date)->format('d/m/Y') }}
                    </td>
                    <td style="border: 1px solid #ccc; padding: 8px;">{{ $payment->vendorBill->vendor->name }}</td>
                    <td style="border: 1px solid #ccc; padding: 8px;">{{ $payment->vendorBill->ref_no }}</td>
                    <td style="text-align: right; border: 1px solid #ccc; padding: 8px;">
                        {{ number_format($payment->amount, 2) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background-color: #f0f0f0; font-weight: bold;">
                    <td colspan="3" style="text-align: right; border: 1px solid #ccc; padding: 8px;">Total Amount</td>
                    <td style="text-align: right; font-weight: bold; border: 1px solid #ccc; padding: 8px;">
                        {{ number_format($totalAmount, 2) }}
                    </td>
                </tr>
            </tfoot>
        </table>

        <div style="margin-top: 40px; text-align: left; font-size: 12px;">
            <p>You can print cheques now or print them later from Print Forms on the File menu.</p>
        </div>
    </div>

    <div class="no-print" style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 1rem;">
        <button onclick="history.back()"
            style="display: flex;  align-items: center; gap: 0.5rem; font-family: 'Open Sans', sans-serif; padding: 0.3rem 1rem; font-size: 0.875rem; font-weight: 500; color: #ffffff; border: 1px solid #D1D5DB; border-radius: 0.375rem; background-color: rgb(156, 156, 156); box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);">
            <svg style="width: 1.5rem; height: 1.5rem; color: #ffffff;" aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linejoin="round" stroke-width="2"
                    d="M19 12H5M12 19l-7-7 7-7" />
            </svg>Back
        </button>
        <button onclick="window.print()"
            style="display: flex; align-items: center; gap: 0.5rem; font-family: 'Open Sans', sans-serif; padding: 0.3rem 1rem; font-size: 0.875rem; font-weight: 500; color: white; border-radius: 0.375rem; background-color: #465FFF; box-shadow: 0 2px 2px rgba(0, 0, 0, 0.1); margin-right: 2.5rem;">
            <svg style="width: 1.5rem; height: 1.5rem; color: white;" aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linejoin="round" stroke-width="2"
                    d="M16.444 18H19a1 1 0 0 0 1-1v-5a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1h2.556M17 11V5a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v6h10ZM7 15h10v4a1 1 0 0 1-1 1H8a1 1 0 0 1-1-1v-4Z" />
            </svg>Print
        </button>
    </div>
</div>

<script>
    // The script is kept for potential future use, but window.print() is called directly from the button.
</script>
