{{-- <div>
<div class="grid grid-cols-2 gap-6">
    <div>
        <h2 class="text-lg font-bold">Gross Expenses (Dr)</h2>
        <ul>
            @foreach ($pandl['gross_expenses'] as $item)
                <li>{{ $item['name'] }} — {{ $item['amount_dc'] }} {{ number_format($item['amount'], 2) }}</li>
                @if ($item['children'])
            @foreach ($item['children'] as $children)
                <li>{{ $children['name'] }} — {{ $children['amount_dc'] }} {{ number_format($children['amount'], 2) }}</li>
            @endforeach
                @endif
            @endforeach
            <li class="font-bold mt-2">Total Gross Expenses: Dr {{ number_format($pandl['gross_expense_total'], 2) }}</li>
        </ul>
    </div>

    <div>
        <h2 class="text-lg font-bold">Gross Incomes (Cr)</h2>
        <ul>
            @foreach ($pandl['gross_incomes'] as $item)
                <li>{{ $item['name'] }} — {{ $item['amount_dc'] }} {{ number_format($item['amount'], 2) }}</li>
                @if ($item['children'])
            @foreach ($item['children'] as $children)
                <li>{{ $children['name'] }} — {{ $children['amount_dc'] }} {{ number_format($children['amount'], 2) }}</li>
            @endforeach
                @endif
            @endforeach
            <li class="font-bold mt-2">Total Gross Incomes: Cr {{ number_format($pandl['gross_income_total'], 2) }}</li>
        </ul>
    </div>
</div>
<div class="grid grid-cols-2 gap-6">
<div>
    @if ($pandl['gross_pl'] >= 0) Gross Profit C/D  — {{ $pandl['gross_pl'] }}
    @endif
</div>
<div>@if ($pandl['gross_pl'] < 0) Gross Loss C/D {{ $pandl['gross_pl'] }}
    @endif</div>
</div>
<div class="grid grid-cols-2 gap-6">
    <div>
<h2>Total Dr {{ number_format($pandl['gross_expense_final'], 2) }}</h2>
    </div>
<div>
<h2>Total Cr {{ number_format($pandl['gross_income_final'], 2) }}</h2>
</div>
</div>


<div class="grid grid-cols-2 gap-6">
    <div>
        <h2 class="text-lg font-bold">Net Expenses (Dr)</h2>
        <ul>
            @foreach ($pandl['net_expenses'] as $item)
            <li>{{ $item['name'] }} — {{ $item['amount_dc'] }} {{ number_format($item['amount'], 2) }}</li>
            @if ($item['children'])
            @foreach ($item['children'] as $children)
                <li>{{ $children['name'] }} — {{ $children['amount_dc'] }} {{ number_format($children['amount'], 2) }}</li>
            @endforeach
                @endif
            @endforeach
            <li class="font-bold mt-2">Total: Dr {{ number_format($pandl['net_expense_total'], 2) }}</li>
        </ul>
    </div>

    <div>
        <h2 class="text-lg font-bold">Net Incomes (Cr)</h2>
        <ul>
            @foreach ($pandl['net_incomes'] as $item)
            <li>{{ $item['name'] }}</li>
            @if ($item['children'])
            @foreach ($item['children'] as $children)
                <li>{{ $children['name'] }} — {{ $children['amount_dc'] }} {{ number_format($children['amount'], 2) }}</li>
            @endforeach
                @endif
            @endforeach
            <li class="font-bold mt-2">Total: Cr {{ number_format($pandl['net_income_total'], 2) }}</li>
        </ul>
    </div>
</div>

<div class="grid grid-cols-2 gap-6">
<div>
@if ($pandl['gross_pl'] < 0) Gross Loss B/D {{ number_format($pandl['gross_pl'], 2) }}
    @endif
</div>
<div>
     @if ($pandl['gross_pl'] >= 0) Gross Profit B/D  — {{ number_format($pandl['gross_pl'], 2) }}
    @endif
    </div>
</div>

<div class="mt-4">
    <h2 class="text-xl font-bold text-green-700">
        Net Profit: {{ number_format($pandl['net_pl'], 2) }}
    </h2>
</div>
</div> --}}




<div class="max-w-6xl mx-auto rounded-2xl py-5 px-5 bg-white">
    <!-- Header -->
    <div class="text-center  mb-8">
        <h1 class="text-xl font-bold text-gray-800 mb-2">{{ config('app.company_name') }}</h1>
        <h2 class="text-lg text-gray-700">Trading and Profit & Loss Statement from 01-Apr-2025 to 31-Mar-2026</h2>
    </div>

    <!-- Gross Section -->
    <div class="border border-gray-300 mb-6">
        <!-- Gross Header -->
        <div class="grid grid-cols-2 bg-gray-100 border-b border-gray-300">
            <div class="px-4 py-3 border-r border-gray-300 flex justify-between items-center">
                <h3 class="font-semibold text-gray-800">Gross Expenses (Dr)</h3>
                <span class="text-sm text-gray-600">Amount (Rs)</span>
            </div>
            <div class="px-4 py-3 flex justify-between items-center">
                <h3 class="font-semibold text-gray-800">Gross Incomes (Cr)</h3>
                <span class="text-sm text-gray-600">Amount (Rs)</span>
            </div>
        </div>

        <!-- Gross Content -->
        <div class="grid grid-cols-2">
            <!-- Gross Expenses Column -->
            <div class="px-4 py-4 border-r border-gray-300 flex flex-col justify-between min-h-96">
                <ul class="space-y-3">
                    @foreach ($pandl['gross_expenses'] as $item)
                        <li class="border-b border-gray-100 pb-2" style="transition:background 0.2s;"
                            onmouseover="this.style.backgroundColor='#FFFF99';this.style.color='';"
                            onmouseout="this.style.backgroundColor='';this.style.color='';">
                            <div class="flex justify-between items-center font-semibold text-gray-800 mb-2">
                                <span>{{ $item['name'] }}</span>
                                <span class="text-red-600">Dr {{ number_format($item['amount'], 2) }}</span>
                            </div>
                            @if (count($item['children']) > 0)
                                <ul class="ml-6 space-y-1">
                                    @foreach ($item['children'] as $child)
                                        <li class="flex justify-between items-center text-sm text-gray-600">
                                            <span>[{{ $child['code'] }}] {{ $child['name'] }}</span>
                                            <span class="text-red-600">Dr
                                                {{ number_format($child['amount'], 2) }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>

                <!-- Gross Totals -->
                <div class="mt-6 pt-4 border-t-2 border-gray-300 space-y-2">
                    <div class="flex justify-between items-center font-semibold" style="transition:background 0.2s;"
                        onmouseover="this.style.backgroundColor='#FFFF99';this.style.color='';"
                        onmouseout="this.style.backgroundColor='';this.style.color='';">
                        <span>Total Gross Expenses</span>
                        <span class="text-red-600">Dr {{ number_format($pandl['gross_expense_total'], 2) }}</span>
                    </div>
                    @if ($pandl['gross_pl'] > 0)
                        <div class="flex justify-between items-center font-semibold text-green-600"
                            style="transition:background 0.2s;"
                            onmouseover="this.style.backgroundColor='#FFFF99';this.style.color='';"
                            onmouseout="this.style.backgroundColor='';this.style.color='';">
                            <span>Gross Profit C/D</span>
                            <span>{{ number_format($pandl['gross_pl'], 2) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between items-center font-bold text-normal pt-2 border-t border-gray-200"
                        style="transition:background 0.2s;"
                        onmouseover="this.style.backgroundColor='#FFFF99';this.style.color='';"
                        onmouseout="this.style.backgroundColor='';this.style.color='';">
                        <span>Total</span>
                        <span class="text-red-600">Dr {{ number_format($pandl['gross_expense_final'], 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Gross Incomes Column -->
            <div class="px-4 py-4 flex flex-col justify-between min-h-96">
                <ul class="space-y-3">
                    @foreach ($pandl['gross_incomes'] as $item)
                        <li class="border-b border-gray-100 pb-2">
                            <div class="flex justify-between items-center font-semibold text-gray-800 mb-2" style="transition:background 0.2s;"
                            onmouseover="this.style.backgroundColor='#FFFF99';this.style.color='';"
                            onmouseout="this.style.backgroundColor='';this.style.color='';">
                                <span>{{ $item['name'] }}</span>
                                <span class="text-green-600">Cr {{ number_format($item['amount'], 2) }}</span>
                            </div>
                            @if (count($item['children']) > 0)
                                <ul class="ml-6 space-y-1">
                                    @foreach ($item['children'] as $child)
                                        <li class="flex justify-between items-center text-sm text-gray-600" style="transition:background 0.2s;"
                                        onmouseover="this.style.backgroundColor='#FFFF99';this.style.color='';"
                                        onmouseout="this.style.backgroundColor='';this.style.color='';">
                                            <span>[{{ $child['code'] }}] {{ $child['name'] }}</span>
                                            <span class="text-green-600">Cr
                                                {{ number_format($child['amount'], 2) }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>

                <!-- Gross Income Totals -->
                <div class="mt-6 pt-4 border-t-2 border-gray-300 space-y-2">
                    <div class="flex justify-between items-center font-semibold" style="transition:background 0.2s;"
                    onmouseover="this.style.backgroundColor='#FFFF99';this.style.color='';"
                    onmouseout="this.style.backgroundColor='';this.style.color='';">
                        <span>Total Gross Incomes</span>
                        <span class="text-green-600">Cr {{ number_format($pandl['gross_income_total'], 2) }}</span>
                    </div>
                    @if ($pandl['gross_pl'] < 0)
                        <div class="flex justify-between items-center font-semibold text-red-600">
                            <span>Gross Loss C/D</span>
                            <span>{{ number_format(abs($pandl['gross_pl']), 2) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between items-center font-bold text-normal pt-2 border-t border-gray-200" style="transition:background 0.2s;"
                    onmouseover="this.style.backgroundColor='#FFFF99';this.style.color='';"
                    onmouseout="this.style.backgroundColor='';this.style.color='';">
                        <span>Total</span>
                        <span class="text-green-600">Cr {{ number_format($pandl['gross_income_final'], 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Net Section -->
    <div class="border border-gray-300">
        <!-- Net Header -->
        <div class="grid grid-cols-2 bg-gray-100 border-b border-gray-300">
            <div class="px-4 py-3 border-r border-gray-300 flex justify-between items-center">
                <h3 class="font-semibold text-gray-800">Net Expenses (Dr)</h3>
                <span class="text-sm text-gray-600">Amount (Rs)</span>
            </div>
            <div class="px-4 py-3 flex justify-between items-center">
                <h3 class="font-semibold text-gray-800">Net Incomes (Cr)</h3>
                <span class="text-sm text-gray-600">Amount (Rs)</span>
            </div>
        </div>

        <!-- Net Content -->
        <div class="grid grid-cols-2">
            <!-- Net Expenses Column -->
            <div class="px-4 py-4 border-r border-gray-300 flex flex-col justify-between min-h-96">
                <ul class="space-y-3">
                    @foreach ($pandl['net_expenses'] as $item)
                        <li class="border-b border-gray-100 pb-2">
                            <div class="font-semibold text-gray-800 mb-2" style="transition:background 0.2s;"
                            onmouseover="this.style.backgroundColor='#FFFF99';this.style.color='';"
                            onmouseout="this.style.backgroundColor='';this.style.color='';">{{ $item['name'] }}</div>
                            @if (count($item['children']) > 0)
                                <ul class="ml-6 space-y-1">
                                    @foreach ($item['children'] as $child)
                                        <li class="flex justify-between items-center text-sm text-gray-600" style="transition:background 0.2s;"
                                        onmouseover="this.style.backgroundColor='#FFFF99';this.style.color='';"
                                        onmouseout="this.style.backgroundColor='';this.style.color='';">
                                            <span>[{{ $child['code'] }}] {{ $child['name'] }}</span>
                                            <span class="text-red-600">Dr
                                                {{ number_format($child['amount'], 2) }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>

                <!-- Net Expense Totals -->
                <div class="mt-6 pt-4 border-t-2 border-gray-300 space-y-2">
                    <div class="flex justify-between items-center font-semibold" style="transition:background 0.2s;"
                    onmouseover="this.style.backgroundColor='#FFFF99';this.style.color='';"
                    onmouseout="this.style.backgroundColor='';this.style.color='';">
                        <span>Total Expenses</span>
                        <span class="text-red-600">Dr {{ number_format($pandl['net_expense_total'], 2) }}</span>
                    </div>
                    @if ($pandl['gross_pl'] < 0)
                        <div class="flex justify-between items-center">
                            <span>Gross Loss B/D</span>
                            <span class="text-red-600">{{ number_format(abs($pandl['gross_pl']), 2) }}</span>
                        </div>
                    @endif
                    @if ($pandl['net_pl'] > 0)
                        <div class="flex justify-between items-center font-semibold text-green-700 text-normal" style="transition:background 0.2s;"
                        onmouseover="this.style.backgroundColor='#FFFF99';this.style.color='';"
                        onmouseout="this.style.backgroundColor='';this.style.color='';">
                            <span>Net Profit</span>
                            <span>{{ number_format($pandl['net_pl'], 2) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between items-center font-bold text-normal pt-2 border-t border-gray-200" style="transition:background 0.2s;"
                    onmouseover="this.style.backgroundColor='#FFFF99';this.style.color='';"
                    onmouseout="this.style.backgroundColor='';this.style.color='';">
                        <span>Total</span>
                        <span class="text-red-600">Dr
                            {{ number_format($pandl['net_expense_total'] + ($pandl['gross_pl'] < 0 ? abs($pandl['gross_pl']) : 0) + ($pandl['net_pl'] > 0 ? $pandl['net_pl'] : 0), 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Net Incomes Column -->
            <div class="px-4 py-4 flex flex-col justify-between min-h-96">
                <ul class="space-y-3">
                    @foreach ($pandl['net_incomes'] as $item)
                        <li class="border-b border-gray-100 pb-2">
                            <div class="font-semibold text-gray-800 mb-2" style="transition:background 0.2s;"
                            onmouseover="this.style.backgroundColor='#FFFF99';this.style.color='';"
                            onmouseout="this.style.backgroundColor='';this.style.color='';">{{ $item['name'] }}</div>
                            @if (count($item['children']) > 0)
                                <ul class="ml-6 space-y-1">
                                    @foreach ($item['children'] as $child)
                                        <li class="flex justify-between items-center text-sm text-gray-600">
                                            <span>[{{ $child['code'] }}] {{ $child['name'] }}</span>
                                            <span class="text-green-600">Cr
                                                {{ number_format($child['amount'], 2) }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>

                <!-- Net Income Totals -->
                <div class="mt-6 pt-4 border-t-2 border-gray-300 space-y-2">
                    <div class="flex justify-between items-center font-semibold" style="transition:background 0.2s;"
                    onmouseover="this.style.backgroundColor='#FFFF99';this.style.color='';"
                    onmouseout="this.style.backgroundColor='';this.style.color='';">
                        <span>Total Incomes</span>
                        <span class="text-green-600">Cr {{ number_format($pandl['net_income_total'], 2) }}</span>
                    </div>
                    @if ($pandl['gross_pl'] > 0)
                        <div class="flex justify-between font-semibold items-center" style="transition:background 0.2s;"
                        onmouseover="this.style.backgroundColor='#FFFF99';this.style.color='';"
                        onmouseout="this.style.backgroundColor='';this.style.color='';">
                            <span>Gross Profit B/D</span>
                            <span class="text-green-600">{{ number_format($pandl['gross_pl'], 2) }}</span>
                        </div>
                    @endif
                    @if ($pandl['net_pl'] < 0)
                        <div class="flex justify-between items-center font-bold text-red-600 text-lg">
                            <span>Net Loss</span>
                            <span>{{ number_format(abs($pandl['net_pl']), 2) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between items-center font-bold text-normal pt-2 border-t border-gray-200" style="transition:background 0.2s;"
                    onmouseover="this.style.backgroundColor='#FFFF99';this.style.color='';"
                    onmouseout="this.style.backgroundColor='';this.style.color='';">
                        <span>Total</span>
                        <span class="text-green-600">Cr
                            {{ number_format($pandl['net_income_total'] + ($pandl['gross_pl'] > 0 ? $pandl['gross_pl'] : 0) + ($pandl['net_pl'] < 0 ? abs($pandl['net_pl']) : 0), 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
