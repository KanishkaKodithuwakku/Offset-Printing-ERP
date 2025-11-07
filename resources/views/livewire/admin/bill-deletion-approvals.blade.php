<div class="overflow-hidden rounded-xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">

    <div class="p-6">
        @if (session('success'))
            <div
                class="rounded-xl border border-success-500 bg-success-50 p-4 dark:border-success-500/30 dark:bg-success-500/15">
                <div class="flex items-start gap-3">
                    <div class="-mt-0.5 text-success-500">
                        <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M3.70186 12.0001C3.70186 7.41711 7.41711 3.70186 12.0001 3.70186C16.5831 3.70186 20.2984 7.41711 20.2984 12.0001C20.2984 16.5831 16.5831 20.2984 12.0001 20.2984C7.41711 20.2984 3.70186 16.5831 3.70186 12.0001ZM12.0001 1.90186C6.423 1.90186 1.90186 6.423 1.90186 12.0001C1.90186 17.5772 6.423 22.0984 12.0001 22.0984C17.5772 22.0984 22.0984 17.5772 22.0984 12.0001C22.0984 6.423 17.5772 1.90186 12.0001 1.90186ZM15.6197 10.7395C15.9712 10.388 15.9712 9.81819 15.6197 9.46672C15.2683 9.11525 14.6984 9.11525 14.347 9.46672L11.1894 12.6243L9.6533 11.0883C9.30183 10.7368 8.73198 10.7368 8.38051 11.0883C8.02904 11.4397 8.02904 12.0096 8.38051 12.3611L10.553 14.5335C10.7217 14.7023 10.9507 14.7971 11.1894 14.7971C11.428 14.7971 11.657 14.7023 11.8257 14.5335L15.6197 10.7395Z"
                                fill="" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="mb-1 text-sm font-semibold text-gray-800 dark:text-white/90">
                            Success
                        </h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            </div>
        @elseif (session('error'))
            <div class="rounded-xl border border-error-500 bg-error-50 p-4 dark:border-red-500/30 dark:bg-red-500/10">
                <div class="flex items-start gap-3">
                    <div class="-mt-0.5 text-error-500">
                        <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M12 3C7.031 3 3 7.031 3 12s4.031 9 9 9 9-4.031 9-9-4.031-9-9-9Zm0 16c-3.866 0-7-3.134-7-7s3.134-7 7-7 7 3.134 7 7-3.134 7-7 7Zm-.75-11a.75.75 0 0 1 1.5 0v4.5a.75.75 0 0 1-1.5 0V8Zm0 6.75a.75.75 0 1 1 1.5 0 .75.75 0 0 1-1.5 0Z"
                                fill="" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="mb-1 text-sm font-semibold text-gray-800 dark:text-white/90">
                            Error
                        </h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ session('error') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                Bill Deletion Approvals
            </h3>
            @if (!empty($selectedApprovals))
                <div class="flex gap-2">
                    <button wire:click="approve"
                        class="px-4 py-2 bg-success-500 hover:bg-success-600 text-white text-xs rounded transition-colors">
                        Approve Selected
                    </button>
                    <button wire:click="reject"
                        class="px-4 py-2 bg-error-500 hover:bg-error-600 text-white text-xs rounded transition-colors">
                        Reject Selected
                    </button>
                </div>
            @endif
        </div>

        <table class="min-w-full table-auto mt-4 border border-gray-200 text-sm dark:border-gray-700">
            <thead class="bg-gray-100 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                <tr>
                    <th class="px-3 py-2 text-sm font-semibold text-gray-500 dark:text-gray-300 text-left">Select</th>
                    <th class="px-3 py-2 text-sm font-semibold text-gray-500 dark:text-gray-300 text-left">Date</th>
                    <th class="px-3 py-2 text-sm font-semibold text-gray-500 dark:text-gray-300 text-left">Vendor</th>
                    <th class="px-3 py-2 text-sm font-semibold text-gray-500 dark:text-gray-300 text-left">Invoice No</th>
                    <th class="px-3 py-2 text-sm font-semibold text-gray-500 dark:text-gray-300 text-right">Amount</th>
                    <th class="px-3 py-2 text-sm font-semibold text-gray-500 dark:text-gray-300 text-left">Reason</th>
                    <th class="px-3 py-2 text-sm font-semibold text-gray-500 dark:text-gray-300 text-left">Requested By</th>
                    <th class="px-3 py-2 text-sm font-semibold text-gray-500 dark:text-gray-300 text-left">Status</th>
                    <th class="px-3 py-2 text-sm font-semibold text-gray-500 dark:text-gray-300 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($approvals as $approval)
                    <tr
                        class="{{ $approval->status === 'approved' ? 'bg-green-50 dark:bg-green-900/20' : ($approval->status === 'rejected' ? 'bg-red-50 dark:bg-red-900/20' : 'hover:bg-gray-50 dark:hover:bg-gray-800/50') }}">
                        <td class="px-3 py-2 text-xs border-b dark:bg-gray-800 border-gray-200 dark:border-gray-700">
                            @if ($approval->status === 'pending')
                                <input type="checkbox" wire:click="toggleSelectedApproval({{ $approval->id }})"
                                    @if(in_array($approval->id, $selectedApprovals)) checked @endif>
                            @endif
                        </td>
                        <td class="px-3 py-2 text-xs border-b dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-200">
                            @if($approval->vendorBill)
                                {{ date('d/m/Y', strtotime($approval->vendorBill->date)) }}
                            @elseif($approval->bill_date)
                                {{ date('d/m/Y', strtotime($approval->bill_date)) }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="px-3 py-2 text-xs border-b dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-200">
                            @if($approval->vendorBill && $approval->vendorBill->vendor)
                                {{ $approval->vendorBill->vendor->name }}
                            @elseif($approval->vendor_name)
                                {{ $approval->vendor_name }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="px-3 py-2 text-xs border-b dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-200">
                            @if($approval->vendorBill)
                                {{ $approval->vendorBill->ref_no }}
                            @elseif($approval->ref_no)
                                {{ $approval->ref_no }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="px-3 py-2 text-xs border-b dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-200 text-right">
                            @if($approval->vendorBill)
                                {{ number_format($approval->vendorBill->total_amount, 2) }}
                            @elseif($approval->total_amount)
                                {{ number_format($approval->total_amount, 2) }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="px-3 py-2 text-xs border-b dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-200">
                            {{ $approval->reason ? \Illuminate\Support\Str::limit($approval->reason, 50) : 'N/A' }}
                        </td>
                        <td class="px-3 py-2 text-xs border-b dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-200">
                            {{ $approval->requestedBy ? $approval->requestedBy->name : 'N/A' }}
                        </td>
                        <td class="px-3 py-2 text-xs border-b dark:bg-gray-800 border-gray-200 dark:border-gray-700 font-semibold text-gray-700 dark:text-gray-200">
                            {{ ucfirst($approval->status) }}
                        </td>
                        <td class="px-3 py-2 text-xs border-b dark:bg-gray-800 border-gray-200 dark:border-gray-700">
                            @if ($approval->status === 'pending')
                                <div class="flex gap-2">
                                    <button wire:click="approve({{ $approval->id }})"
                                        class="px-2 py-1 bg-success-500 hover:bg-success-600 text-white text-xs rounded transition-colors">
                                        Approve
                                    </button>
                                    <button wire:click="reject({{ $approval->id }})"
                                        class="px-2 py-1 bg-error-500 hover:bg-error-600 text-white text-xs rounded transition-colors">
                                        Reject
                                    </button>
                                </div>
                            @else
                                <span class="text-xs text-gray-500 dark:text-gray-400">No action</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="flex justify-between items-center border-t px-6 py-4 dark:border-gray-800">
        <div class="text-sm text-gray-600 dark:text-gray-400">
            Showing {{ $approvals->firstItem() }} to {{ $approvals->lastItem() }} of {{ $approvals->total() }}
            entries
        </div>

        <div class="pagination flex justify-between">
            {{ $approvals->links('vendor.pagination.custom-tailwind') }}
        </div>
    </div>
</div>
