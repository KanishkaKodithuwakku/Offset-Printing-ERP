<div>
    <h2 class="text-xl font-semibold mb-4">Accounts</h2>

    <!-- Transaction Table -->
    <table class="min-w-full bg-white border border-gray-200">
        <thead>
            <tr>
                <th class="border px-4 py-2 text-left">ID</th>
                <th class="border px-4 py-2 text-left">Sale ID</th>
                <th class="border px-4 py-2 text-left">Account ID</th>
                <th class="border px-4 py-2 text-left">Debit</th>
                <th class="border px-4 py-2 text-left">Credit</th>
                <th class="border px-4 py-2 text-left">Transaction Type</th>
                <th class="border px-4 py-2 text-left">Created At</th>
                <th class="border px-4 py-2 text-left">Updated At</th>
                <th class="border px-4 py-2 text-left">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
                <tr>
                    <td class="border px-4 py-2">{{ $transaction->id }}</td>
                    <td class="border px-4 py-2">{{ $transaction->sale_id }}</td>
                    <td class="border px-4 py-2">{{ $transaction->account ? $transaction->account['account_name'] : '' }}</td>
                    <td class="border px-4 py-2">{{ number_format($transaction->debit, 2) }}</td>
                    <td class="border px-4 py-2">{{ number_format($transaction->credit, 2) }}</td>
                    <td class="border px-4 py-2">{{ $transaction->transaction_type }}</td>
                    <td class="border px-4 py-2">{{ $transaction->created_at->format('Y-m-d H:i:s') }}</td>
                    <td class="border px-4 py-2">{{ $transaction->updated_at->format('Y-m-d H:i:s') }}</td>
                    <td class="border px-4 py-2">
                        <a href="#" class="text-blue-500">Edit</a> |
                        <a href="#" class="text-red-500">Delete</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
