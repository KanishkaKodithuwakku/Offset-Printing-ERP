<div class="p-6 font-sans text-sm">
    <h3 class="text-lg font-bold"></h3>
    <p>{{ nl2br(config('account.address')) }}</p>
    <h5 class="mt-2 font-semibold">{{ $entrytype->name }} Entry</h5>

    <p class="mt-4">
        Number: {{ $entry->entry_number }}<br>
        Date: {{ \Carbon\Carbon::parse($entry->date)->format('Y-m-d') }}
    </p>

    <table class="w-full mt-4 border text-left text-sm">
        <thead>
            <tr class="bg-gray-200">
                <th class="border px-2 py-1">Dr/Cr</th>
                <th class="border px-2 py-1">Ledger</th>
                <th class="border px-2 py-1">Dr Amount ({{ config('account.currency_symbol') }})</th>
                <th class="border px-2 py-1">Cr Amount ({{ config('account.currency_symbol') }})</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($entryitems as $item)
                <tr>
                    <td class="border px-2 py-1">
                        {{ $item['dc'] === 'D' ? 'Dr' : 'Cr' }}
                    </td>
                    <td class="border px-2 py-1">{{ $item['ledger_name'] }}</td>
                    <td class="border px-2 py-1 text-right">{{ $item['dr_amount'] }}</td>
                    <td class="border px-2 py-1 text-right">{{ $item['cr_amount'] }}</td>
                </tr>
            @endforeach

            <tr class="font-bold">
                <td></td>
                <td>Total</td>
                <td class="text-right">{{ number_format($entry->dr_total, 2) }}</td>
                <td class="text-right">{{ number_format($entry->cr_total, 2) }}</td>
            </tr>

            @if ($entry->dr_total != $entry->cr_total)
                <tr class="text-red-600">
                    <td></td>
                    <td>Difference</td>
                    <td class="text-right">
                        {{ $entry->dr_total > $entry->cr_total ? number_format($entry->dr_total - $entry->cr_total, 2) : '' }}
                    </td>
                    <td class="text-right">
                        {{ $entry->cr_total > $entry->dr_total ? number_format($entry->cr_total - $entry->dr_total, 2) : '' }}
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

    <p class="mt-4"><strong>Narration:</strong> {{ $entry->narration }}</p>

    <button onclick="window.print()" class="mt-4 px-4 py-1 bg-gray-700 text-white print:hidden">
        Print
    </button>
</div>
