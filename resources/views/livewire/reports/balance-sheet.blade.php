<div class="border border-gray-300 rounded-md bg-white">
    <div class="text-center font-semibold text-lg mt-5 mb-4">
         <h2 style="font-size: 18px; font-weight: bold; margin: 0;">{{ config('app.company_name') }}</h2>
        Closing Balance Sheet as on {{ \Carbon\Carbon::parse($date)->format('d-M-Y') }}
    </div>

    <!-- Header -->
    <div class="grid grid-cols-2 bg-gray-100 border-b border-t border-gray-300">
        <div class="px-4 py-3 border-r border-gray-300 flex justify-between items-center">
            <span class="font-semibold text-gray-800">Assets (Dr)</span>
            <span class="text-sm text-gray-600">Amount (Rs)</span>
        </div>
        <div class="px-4 py-3 flex justify-between items-center">
            <span class="font-semibold text-gray-800">Liabilities and Owners Equity (Cr)</span>
            <span class="text-sm text-gray-600">Amount (Rs)</span>
        </div>
    </div>

    <!-- Content -->
    <div class="grid grid-cols-2">
        <!-- Assets Column -->
        <div class="px-4 py-4 border-r border-gray-300 flex flex-col min-h-96">
            <ul class="space-y-3">
                @for ($i = 0; $i < max(count($assets), count($liabilities)); $i++)
                    @if (isset($assets[$i]))
                        <li class="border-b border-gray-100 pb-2 group">
                            <div class="text-sm flex justify-between font-semibold text-gray-800 mb-2 transition"
                                onmouseover="this.style.backgroundColor='#FFFF99';"
                                onmouseout="this.style.backgroundColor='';">
                                {{ $assets[$i]['name'] ?? '' }}
                                <div>
                                    Dr {{ number_format($assets[$i]['amount'] ?? '') }}
                                </div>
                            </div>
                            @if (isset($assets[$i]['children']))
                                <ul class="pl-6 space-y-1 ">
                                    @foreach ($assets[$i]['children'] as $child)
                                        <li class="flex justify-between items-center text-sm text-gray-600 transition"
                                            onmouseover="this.style.backgroundColor='#FFFF99';"
                                            onmouseout="this.style.backgroundColor='';">
                                            <span>[{{ $child['code'] ?? '' }}] {{ $child['name'] ?? '' }}</span>
                                            <span class="text-red-600">Dr
                                                {{ number_format($child['amount'], 2) }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endif
                @endfor
            </ul>

            <!-- Asset Totals -->
            <div class="mt-6 pt-4 border-t-2 border-gray-300 space-y-2">
                <div class="flex justify-between items-center font-semibold transition"
                    onmouseover="this.style.backgroundColor='#FFFF99';" onmouseout="this.style.backgroundColor='';">
                    <span>Total Assets</span>
                    <span class="text-red-600">Dr {{ number_format($totalAssets, 2) }}</span>
                </div>
                <div class="flex justify-between items-center font-semibold transition"
                    onmouseover="this.style.backgroundColor='#FFFF99';" onmouseout="this.style.backgroundColor='';">
                    <span>&nbsp;</span>
                    <span>&nbsp;</span>
                </div>
                <div class="flex justify-between items-center font-bold pt-2 border-t border-gray-200 transition bg-gray-50"
                    onmouseover="this.style.backgroundColor='#FFFF99';" onmouseout="this.style.backgroundColor='';">
                    <span>Total</span>
                    <span class="text-red-600">Dr {{ number_format($totalAssets, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Liabilities Column -->
        <div class="px-4 py-4 flex flex-col min-h-96 border-gray-300">
            <div class="flex-1">
                <ul class="space-y-3">
                    @for ($i = 0; $i < max(count($assets), count($liabilities)); $i++)
                        @if (isset($liabilities[$i]))
                            <li class="border-b border-gray-100 pb-2 group">
                                <div class="text-sm flex justify-between font-semibold text-gray-800 mb-2 transition"
                                    onmouseover="this.style.backgroundColor='#FFFF99';"
                                    onmouseout="this.style.backgroundColor='';">
                                    {{ $liabilities[$i]['name'] ?? '' }}

                                    <div>
                                        Dr {{ number_format($assets[$i]['amount'] ?? '') }}
                                    </div>

                                </div>
                                @if (isset($liabilities[$i]['children']))
                                    <ul class="pl-6 space-y-1">
                                        @foreach ($liabilities[$i]['children'] as $child)
                                            <li class="flex justify-between items-center text-sm text-gray-600 transition"
                                                onmouseover="this.style.backgroundColor='#FFFF99';"
                                                onmouseout="this.style.backgroundColor='';">
                                                <span>[{{ $child['code'] ?? '' }}] {{ $child['name'] ?? '' }}</span>
                                                <span class="text-green-600">Cr
                                                    {{ number_format($child['amount'], 2) }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endif
                    @endfor
                </ul>
            </div>

            <!-- Liability Totals at the bottom -->
            <div class="mt-6 pt-4 border-t-2 border-gray-300 space-y-2">
                <div class="flex justify-between items-center font-semibold transition"
                    onmouseover="this.style.backgroundColor='#FFFF99';" onmouseout="this.style.backgroundColor='';">
                    <span>Total Liability and Owners Equity</span>
                    <span class="text-green-600">Cr {{ number_format($totalLiabilities, 2) }}</span>
                </div>
                <div class="flex justify-between items-center font-semibold transition"
                    onmouseover="this.style.backgroundColor='#FFFF99';" onmouseout="this.style.backgroundColor='';">
                    <span>Profit &amp; Loss Account (Net Profit)</span>
                    <span class="text-green-600">Cr {{ number_format($profitAndLoss, 2) }}</span>
                </div>
                @if ($is_opdiff && $opdiff['opdiff_balance_dc'] === 'C')
                    <div class="flex justify-between items-center font-semibold text-red-600 transition"
                        onmouseover="this.style.backgroundColor='#FFFF99';" onmouseout="this.style.backgroundColor='';">
                        <span>Diff in O/P Balance</span>
                        <span>Cr {{ number_format($opdiff['opdiff_balance'], 2) }}</span>
                    </div>
                @endif
                <div class="flex justify-between items-center font-bold pt-2 border-t border-gray-200 transition bg-gray-50"
                    onmouseover="this.style.backgroundColor='#FFFF99';" onmouseout="this.style.backgroundColor='';">
                    <span>Total</span>
                    <span class="text-green-600">Cr {{ number_format($finalLiabilityTotal, 2) }}</span>
                </div>
            </div>
        </div>

    </div>
