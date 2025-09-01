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

            .no-print {
                display: none !important;
            }
        }
    </style>

    <div style="margin: auto; font-family: 'Open Sans', sans-serif; padding: 20px;" id="top-border">
        <div style="display: flex; justify-content: space-between; font-size: 15px; margin-bottom: 10px;">
            <div>
                <h2 style="font-size: 18px; font-weight: bold; text-align:left;">{{ config('app.company_name') }}</h2>
                <div style="margin-bottom: 2px;"><span style="font-weight: bold; font-size: 15px;">From Branch</span> :
                    {{ $transfer->fromBranch->branch_name ?? '-' }}</div>
                <div style="margin-bottom: 2px;"><span style="font-weight: bold; font-size: 15px;">To Branch</span> :
                    {{ $transfer->toBranch->branch_name ?? '-' }}</div>
                <div style="margin-bottom: 2px;"><span style="font-weight: bold; font-size: 15px;">Status</span> :
                    {{ $transfer->status ?? '-' }}</div>
            </div>
            <div style="text-align: left;">
                <h3 style="font-size: 16px; font-weight: bold; margin: 5px 0; text-align:left;">Stock Transfer</h3>
                <div style="margin-bottom: 2px;"><span style="font-weight: bold; font-size: 15px;">Transfer Code</span>
                    : {{ $transfer->transfer_code }}</div>
                <div style="margin-bottom: 2px;"><span style="font-weight: bold; font-size: 15px;">Date</span> :
                    {{ $transfer->created_at->format('Y-m-d') }}</div>
            </div>
        </div>

        <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
            <thead>
                <tr>
                    <th style="text-align: left; border: 1px solid black; padding: 2px 0 2px 6px; font-size: 14px;">Item
                    </th>
                    <th
                        style="width: 150px; border: 1px solid black; padding: 2px; text-align: center; font-size: 14px;">
                        Qty</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transfer->items as $item)
                    <tr>
                        <td style="text-align: left; border: 1px solid black; padding: 8px; font-size: 12px;">
                            {{ $item->item->item_name }}</td>
                        <td style="text-align: center; border: 1px solid black; padding: 8px; font-size: 12px;">
                            {{ $item->transferred_quantity }}/{{$item->quantity}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 30px; display: flex; justify-content: space-between; font-size: 12px;">
            <div style="text-align: center; ">

                <div style=" margin-bottom: 2px;"><span style="font-weight: bold; font-size: 15px;">Branch</span> :
                    {{ $transfer->fromBranch->branch_name ?? '-' }}</div>
                <div style="display: flex;  gap: 10px;">
                    <div style="margin-top:25%;">
                        <p>....................................</p>
                        <p><strong>Issued By</strong></p>
                    </div>
                    <div style="margin-top: 25%;">
                        <p>....................................</p>
                        <p><strong>Received By</strong></p>
                    </div>
                </div>


            </div>
            <div style=" text-align: center; gap: 10px;">
                <div style="margin-bottom: 2px;"><span style="font-weight: bold; font-size: 15px;">Branch</span> :
                    {{ $transfer->toBranch->branch_name ?? '-' }}</div>
                <div style="display: flex;  text-align: center; gap: 10px;">
                    <div style="margin-top:25%;">
                        <p>....................................</p>
                        <p><strong>Issued By</strong></p>
                    </div>
                    <div style="margin-top: 25%;">
                        <p>....................................</p>
                        <p><strong>Received By</strong></p>
                    </div>
                </div>


            </div>
        </div>
    </div>

    <div class="no-print" style="display: flex; justify-content: flex-end; gap: 1rem; padding: 1rem; margin-top: 1rem;">
        <button id="back-button" onclick="window.history.back();"
            style="display: flex; align-items: center; font-family: outfit; gap: 0.5rem; padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; color: white; border-radius: 0.375rem; background-color: #6b7280; box-shadow: 0 2px 2px rgba(0, 0, 0, 0.1);">
            <svg style="width: 1.5rem; height: 1.5rem; color: white;" fill="none" stroke="currentColor"
                stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back
        </button>
        <button id="print-button" onclick="showCompanyCopyAndPrint()"
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

<script>
    function showCompanyCopyAndPrint() {
        document.querySelector('#top-border').style.marginTop = '100px';
        window.print();
        document.querySelector('#top-border').style.marginTop = '0px';
    }
</script>
