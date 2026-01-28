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
    <div style="margin: auto; font-family: 'Open Sans', sans-serif; padding: 20px;" id="top-border">
        <h2 style="font-size: 18px; font-weight: bold; text-align:center;">{{ config('app.company_name') }}</h2>
        <h2 style="font-size: 18px; font-weight: bold; text-align: center; margin-bottom: 20px;">
            @if ($statusFilter === 'CA')
                 Cash Receipt Listing
            @elseif ($statusFilter === 'CH')
                 Cheque Receipt Listing
            @else
                 Cheque/Cash Receipt Listing
            @endif
        </h2>
        @if (request('startDate') || request('endDate'))
            <div style="text-align:center; margin-bottom: 10px;">
                <span>
                    @if (request('startDate'))
                        From: {{ request('startDate') }}
                    @endif
                    @if (request('endDate'))
                        To: {{ request('endDate') }}
                    @endif
                </span>
            </div>
        @endif

        <table style="width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 14px;">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th style="border: 1px solid #ccc; padding: 8px; text-align: left;">Receipt Date</th>
                    <th style="border: 1px solid #ccc; padding: 8px; text-align: left;">Receipt Number</th>
                    <th style="border: 1px solid #ccc; padding: 8px; text-align: left;">Customer Name</th>
                    <th style="border: 1px solid #ccc; padding: 8px; text-align: left;">Method</th>
                    @if ($statusFilter !== 'CA')
                        <th style="border: 1px solid #ccc; padding: 8px; text-align: left;">Cheque Date</th>
                        <th style="border: 1px solid #ccc; padding: 8px; text-align: left;">Cheque Number</th>
                        <th style="border: 1px solid #ccc; padding: 8px; text-align: left;">Bank</th>
                        <th style="border: 1px solid #ccc; padding: 8px; text-align: left;">Branch</th>
                    @endif
                    <th style="border: 1px solid #ccc; padding: 8px; text-align: right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($payments as $payment)
                    <tr>
                        <td style="border: 1px solid #ccc; padding: 8px; text-align: left;">
                            {{ $payment->date ?? $payment->created_at->format('Y-m-d') }}</td>
                        <td style="border: 1px solid #ccc; padding: 8px; text-align: left;">
                            {{ $payment->payment_code ?? 'N/A' }}</td>
                        <td style="border: 1px solid #ccc; padding: 8px; text-align: left;">
                            {{ $payment->customer->name ?? 'N/A' }}</td>
                        <td style="border: 1px solid #ccc; padding: 8px; text-align: left;">
                            {{ $payment->display_method }}</td>
                        @if ($statusFilter !== 'CA')
                            <td style="border: 1px solid #ccc; padding: 8px; text-align: left;">
                                @if ($payment->method === 'CH')
                                    {{ $payment->cheque_date ?? '' }}
                                @endif
                            </td>
                            <td style="border: 1px solid #ccc; padding: 8px; text-align: left;">
                                {{ $payment->check_number ?? 'N/A' }}</td>
                            <td style="border: 1px solid #ccc; padding: 8px; text-align: left;">
                                {{ $payment->bank->name ?? '' }}</td>
                            <td style="border: 1px solid #ccc; padding: 8px; text-align: left;">
                                {{ $payment->bankBranch->name ?? '' }}</td>
                        @endif
                        <td style="border: 1px solid #ccc; padding: 8px; text-align: right;">
                            {{ number_format($payment->payment_details_sum_amount, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="@if ($statusFilter !== 'CA') 9 @else 5 @endif"
                            style="text-align: center; border: 1px solid #ccc; padding: 12px; color: #888;">No payments
                            found.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="@if ($statusFilter !== 'CA') 8 @else 4 @endif" style="border: 1px solid #ccc; padding: 8px; text-align: left; font-weight: bold;">Total</td>
                    <td style="border: 1px solid #ccc; padding: 8px; text-align: right; font-weight: bold;">
                        {{ number_format($payments->sum('payment_details_sum_amount'), 2) }}
                    </td>
                </tr>
            </tfoot>
        </table>
        <div style="margin-top: 30px; text-align: right; display: flex; justify-content: flex-end; gap: 1rem;">

            <button id="back-button" onclick="window.history.back();" style="display: flex; align-items: center; font-family: outfit; gap: 0.5rem; padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; color: white; border-radius: 0.375rem; background-color: #6b7280; box-shadow: 0 2px 2px rgba(0, 0, 0, 0.1);">
                <svg style="width: 1.5rem; height: 1.5rem; color: white;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back
            </button>

            <button id="print-button" onclick="window.print()"
                style="display: flex; align-items: center; gap: 0.5rem; font-family: outfit; padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; color: white; border-radius: 0.375rem; background-color: #465FFF; box-shadow: 0 2px 2px rgba(0, 0, 0, 0.1); margin-right: 2.5rem;">
                <svg style="width: 1.5rem; height: 1.5rem; color: white;" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linejoin="round" stroke-width="2"
                        d="M16.444 18H19a1 1 0 0 0 1-1v-5a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1h2.556M17 11V5a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v6h10ZM7 15h10v4a1 1 0 0 1-1 1H8a1 1 0 0 1-1-1v-4Z" />
                </svg>
                Print
            </button>
        </div>
    </div>
</div>
