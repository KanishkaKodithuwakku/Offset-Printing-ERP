<div class="p-6 bg-white rounded shadow">
    <h1 class="text-2xl font-semibold mb-4">View {{ $entry->entrytype->name }} Entry</h1>

    <p><strong>Number :</strong> {{ $entry->number }}</p>
    <p><strong>Date :</strong> {{ \Carbon\Carbon::parse($entry->date)->format('d-M-Y') }}</p>

    <table class="w-full table-auto mt-4 border">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-2 py-1">Dr/Cr</th>
                <th class="px-2 py-1">Ledger</th>
                <th class="px-2 py-1">Dr Amount (Rs)</th>
                <th class="px-2 py-1">Cr Amount (Rs)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($entry->entryitems as $item)
                <tr>
                    <td class="px-2 py-1">{{ $item->dc }}</td>
                    <td class="px-2 py-1">
                        [L{{ $item->ledger->code ?? '-' }}] {{ $item->ledger->name ?? 'N/A' }}
                    </td>
                    <td class="px-2 py-1">{{ $item->dc == 'D' ? number_format($item->amount, 2) : '' }}</td>
                    <td class="px-2 py-1">{{ $item->dc == 'C' ? number_format($item->amount, 2) : '' }}</td>
                </tr>
            @endforeach
            <tr class="font-bold bg-gray-100">
                <td colspan="2">Total</td>
                <td>{{ number_format($entry->dr_total, 2) }}</td>
                <td>{{ number_format($entry->cr_total, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <p class="mt-4"><strong>Narration :</strong> {{ $entry->narration }}</p>
    <p><strong>Tag :</strong> {{ $entry->tag->name ?? '-' }}</p>

    <div class="mt-4 space-x-2">
        <a href="{{ route('entries.edit', ['type' => $entry->entrytype->label, 'id' => $entry->id]) }}" class="btn btn-blue">Edit</a>

        <a href="#" class="btn btn-red">Delete</a>
        <a href="#" class="btn btn-gray">Cancel</a>
    </div>
</div>
