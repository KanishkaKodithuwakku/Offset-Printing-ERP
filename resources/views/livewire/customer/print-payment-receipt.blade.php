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

            #print-button {
                display: none !important;
            }

            #back-button {
                display: none !important;
            }

            /* Hide buttons in print mode */
            .no-print {
                display: none !important;
            }
        }
    </style>

    <div
        style="max-width: 100%; margin: 0 auto; padding: 1.5rem; font-family: 'Open Sans', sans-serif; background-color: white;">

        <div style="display: flex ; justify-content: space-between;  margin-bottom: 3rem; ">
            <div style="text-align: left; margin-bottom: 1.5rem;">
                <h2 style="font-size: 18px;  font-weight: bold;text-align:left;">{{ config('app.company_name') }}</h2>

                {{-- <h2 style="font-size: 16px; text-align:left;">{{ config('custom.address') }}</h2>
                <h2 style="font-size: 16px; text-align:left;">{{ config('custom.phone') }}</h2>
                <h2 style="font-size: 16px; text-align:left;">{{ config('custom.email') }}</h2> --}}
            </div>
            <div>
                <h2 style="font-size: 18px;  font-weight: bold;text-align:left;">Receipt</h2>
                <h2 style="font-size: 16px;   text-align:left;"><span style="font-weight: bold">Date:</span>{{ \Carbon\Carbon::parse($payment->date)->format('d-m-Y') }}</h2>
                <p style="text-align: left; ; ;"><span style="font-weight: bold">Receipt No:</span>{{ strtoupper($payment->payment_code) }}</p>

            </div>
        </div>

        <div style="margin-bottom: 1rem;">


            <p >Received with thanks from <strong
                    style="text-transform: uppercase;">{{ strtoupper($payment->customer->name) }} .</strong></p>
            <p>Payment Method: <b>
                @php
                    $displayMethod = $payment->display_method;
                    // Convert to readable format for receipt
                    if (str_contains($displayMethod, 'CA,CR')) {
                        echo 'CASH & CREDIT';
                    } elseif (str_contains($displayMethod, 'CH,CR')) {
                        echo 'CHEQUE & CREDIT';
                    } elseif ($displayMethod === 'CA' || $displayMethod === 'Cash') {
                        echo 'CASH';
                    } elseif ($displayMethod === 'CH' || $displayMethod === 'Cheque') {
                        echo 'CHEQUE';
                    } elseif ($displayMethod === 'CR') {
                        echo 'CREDIT';
                    } else {
                        echo $displayMethod;
                    }
                @endphp
            </b> | Date: {{ \Carbon\Carbon::parse($payment->date)->format('d-m-Y') }}</p>
            @if ($payment->method === 'CH')
                <p> Cheque No. {{ $payment->check_number }} Cheque Date {{ $payment->cheque_date }}
                    <div>
                    @if($payment->bank)
                        Bank: {{ $payment->bank->name }}
                    @endif
                </div>
                <div>
                    @if($payment->bankBranch)
                        Branch: {{ $payment->bankBranch->name }}
                    @endif
                </div>

                </p>
            @endif
            @if ($payment->status === 'cancelled')
            <div style="background: #fee2e2; color: #b91c1c; padding: 0.75rem 1rem; border-radius: 0.375rem; margin-bottom: 1.5rem; font-weight: bold; text-align: center;">
                Cancelled Payment<br>
                <span style="font-weight: normal; font-size: 0.95em;">
                    Date: {{ \Carbon\Carbon::parse($payment->updated_at)->format('d-m-Y') }}<br>
                    Reason: {{ $payment->cancel_reason }}
                </span>
            </div>
        @endif
            <p style="margin-top: 1.5rem;">For The Sum Of Rupees
                <strong>{{ number_format($total, 2) }}</strong> Being Part/Balance/Full Payment
            </p>
            <p>For The Following Invoices</p>
        </div>

        <table
            style="width: 100%; font-size: 0.875rem; border: 1px solid black; border-collapse: collapse; margin-bottom: 1rem;">
            <thead style="background-color: #f7f8f8;">
                <tr>
                    <th style="border: 1px solid black; padding: 0.5rem; text-align: left;">I.No</th>
                    <th style="border: 1px solid black; padding: 0.5rem; text-align: left;">INVOICE NUMBER</th>
                    <th style="border: 1px solid black; padding: 0.5rem; text-align: left;">INVOICE DATE</th>
                    <th style="border: 1px solid black; padding: 0.5rem; text-align: right;">INVOICE AMOUNT</th>
                    <th style="border: 1px solid black; padding: 0.5rem; text-align: right;">PAYMENT AMOUNT</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($paymentDetails as $detail)
                    <tr>
                        <td style="border: 1px solid black; padding: 0.25rem 0.75rem; text-align: left;">
                            {{ $loop->iteration }}</td>
                        <td style="border: 1px solid black; padding: 0.25rem 0.75rem; text-align: left;">
                            {{ $detail->invoice->invoice_number ?? '' }}</td>
                        <td style="border: 1px solid black; padding: 0.25rem 0.75rem; text-align: left;">
                            {{ \Carbon\Carbon::parse($detail->invoice->created_at)->format('d-m-Y') }}
                        </td>

                        <td style="border: 1px solid black; padding: 0.25rem 0.75rem; text-align: right;">
                            {{ number_format($detail->invoice->total_amount, 2) }}</td>

                        <td style="border: 1px solid black; padding: 0.25rem 0.75rem; text-align: right;">
                            @php
                                // Check if this invoice has credit
                                $hasCreditForInvoice = $invoicesWithCredit->contains($detail->invoice_id);
                                $creditAmountForInvoice = $creditAmountsByInvoice->get($detail->invoice_id, 0);
                                
                                // Check if payment detail amount equals credit amount (credit-only)
                                // If payment detail amount > credit amount, then there's cash/cheque too
                                $isCreditOnly = $hasCreditForInvoice && abs($detail->amount - $creditAmountForInvoice) < 0.01;
                                
                                // Check if there's cash/cheque payment for this invoice
                                // Payment detail with is_credit=0 and amount > credit amount means cash/cheque exists
                                $hasCashForInvoice = !$detail->is_credit && $detail->amount > $creditAmountForInvoice;
                                $hasBoth = $hasCreditForInvoice && $hasCashForInvoice;
                                
                                $methodPrefix = '';
                                if ($isCreditOnly) {
                                    // Credit only for this invoice
                                    $methodPrefix = 'CR ';
                                } elseif ($hasBoth) {
                                    // Both cash/cheque and credit for this invoice
                                    if ($payment->method === 'CA') {
                                        $methodPrefix = 'CA,CR ';
                                    } elseif ($payment->method === 'CH') {
                                        $methodPrefix = 'CH,CR ';
                                    } else {
                                        $methodPrefix = 'CA,CR ';
                                    }
                                } elseif ($detail->is_credit) {
                                    // Legacy: is_credit flag set
                                    $methodPrefix = 'CR ';
                                }
                            @endphp
                            {{ $methodPrefix }}{{ number_format($detail->amount, 2) }}
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="4"
                        style="border: 1px solid black; padding: 0.5rem; text-align: left; font-weight: bold;">TOTAL
                    </td>
                    <td style="border: 1px solid black; padding: 0.5rem; text-align: right; font-weight: bold;">
                        {{ number_format($total, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div style="text-align: left; font-size: 0.75rem; margin-top: 1.5rem;">
            {{-- <p>This receipt is valid subject to realisation of cheque</p> --}}
            @if ($payment->method === 'CH')
                <p>Computer generated receipt signature not required</p>
            @endif
        </div>

        <div style="margin-top: 1.5rem; display: flex; justify-content: flex-end; gap: 1rem;">
            <button id="back-button" onclick="window.history.back();" style="display: flex; align-items: center; font-family: outfit; gap: 0.5rem; padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; color: white; border-radius: 0.375rem; background-color: #6b7280; box-shadow: 0 2px 2px rgba(0, 0, 0, 0.1);">
                <svg style="width: 1.5rem; height: 1.5rem; color: white;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back
            </button>
            <button id="print-button" onclick="printPreview()" wire:click="$refresh"
                style="display: flex; align-items: right; gap: 0.5rem; font-family: 'Open Sans', sans-serif; padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; color: white; border-radius: 0.375rem; background-color: #465FFF; box-shadow: 0 2px 2px rgba(0, 0, 0, 0.1); border: none; cursor: pointer; margin-right: 2.5rem;">
                <svg style="width: 1.5rem; height: 1.5rem; color: white;" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linejoin="round" stroke-width="2"
                        d="M16.444 18H19a1 1 0 0 0 1-1v-5a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1h2.556M17 11V5a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v6h10ZM7 15h10v4a1 1 0 0 1-1 1H8a1 1 0 0 1-1-1v-4Z" />
                </svg>
                Print
            </button>
        </div>
    </div>

<script>
    function printPreview() {
        // Get the printable area element
        const printableElement = document.getElementById('printable-area');
        if (!printableElement) {
            alert('Print area not found');
            return;
        }
        
        // Clone the element to avoid modifying the original
        const clone = printableElement.cloneNode(true);
        
        // Remove the style tag from clone
        const styleTag = clone.querySelector('style');
        if (styleTag) styleTag.remove();
        
        // Remove buttons and their parent container from the clone
        const printButton = clone.querySelector('#print-button');
        const backButton = clone.querySelector('#back-button');
        const buttonContainer = clone.querySelector('div[style*="margin-top: 1.5rem"][style*="display: flex"]');
        
        if (printButton) printButton.remove();
        if (backButton) backButton.remove();
        // Remove the button container div entirely
        if (buttonContainer) {
            buttonContainer.remove();
        }
        
        // Get the inner div that contains the actual content
        const innerDiv = clone.querySelector('div[style*="max-width"]');
        let content = '';
        if (innerDiv) {
            content = innerDiv.outerHTML;
        } else {
            // Fallback: get all content except style and buttons
            content = clone.innerHTML;
        }

        const win = window.open('', '_blank', 'width=800,height=600');
        
        if (!win) {
            alert('Please allow popups for this site');
            return;
        }

        win.document.write(`
            <html>
            <head>
                <title>Payment Receipt - Print Preview</title>
                <meta charset="UTF-8">
                <style>
                    @page {
                        size: A4 portrait;
                        margin: 10mm;
                    }
                    * {
                        margin: 0;
                        padding: 0;
                        box-sizing: border-box;
                    }
                    body {
                        font-family: 'Open Sans', sans-serif;
                        margin: 0;
                        padding: 0;
                        background-color: white;
                    }
                    #printable-area {
                        width: 100%;
                        margin: 0;
                        padding: 0;
                        font-family: 'Open Sans', sans-serif;
                    }
                    #printable-area > div {
                        max-width: 100%;
                        margin: 0 auto;
                        padding: 1.5rem;
                        font-family: 'Open Sans', sans-serif;
                        background-color: white;
                    }
                    .no-print,
                    #print-button,
                    #back-button {
                        display: none !important;
                    }
                    table {
                        width: 100%;
                        font-size: 0.875rem;
                        border: 1px solid black;
                        border-collapse: collapse;
                        margin-bottom: 1rem;
                    }
                    th, td {
                        border: 1px solid black;
                        padding: 0.5rem;
                    }
                    thead {
                        background-color: #f7f8f8;
                    }
                    h2 {
                        font-size: 18px;
                        font-weight: bold;
                    }
                    p {
                        margin: 0.5rem 0;
                    }
                </style>
            </head>
            <body>
                <div id="printable-area" style="width: 100%; margin: 0; padding: 0; font-family: 'Open Sans', sans-serif;">
                    ${content}
                </div>
            </body>
            </html>
        `);

        // Delay printing to ensure content is loaded
        win.document.close();
        win.focus();
        win.print();
    }
</script>
