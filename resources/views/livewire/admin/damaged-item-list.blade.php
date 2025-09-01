<div class="overflow-hidden rounded-xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">

    <div class="p-4">
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
        <div class="flex  gap-5">
            <div class="pr-5">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    Damage Item list
            </div>
            <div>
                {{-- Status Dropdown --}}
                <div>
                    <label class="mb-1  block text-xs font-medium text-gray-700 dark:text-gray-400">Status</label>
                    <select wire:model.change="statusFilter"
                        class="h-8 rounded-md border border-gray-300 pr-6 text-xs dark:bg-dark-900 dark:text-white/90">
                        <option value="">All</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                        <option value="pending">Pending</option>
                    </select>
                </div>
            </div>
        </div>


        </h3>

        <table class="min-w-full table-auto mt-4  border-gray-200 text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-3 py-2 text-sm font-semibold text-gray-500 text-left">Job #</th>
                    <th class="px-3 py-2 text-sm font-semibold text-gray-500 text-left">Customer</th>
                    <th class="px-3 py-2 text-sm font-semibold text-gray-500 text-left">Item</th>
                    <th class="px-3 py-2 text-sm font-semibold text-gray-500 text-left">Qty</th>
                    <th class="px-3 py-2 text-sm font-semibold text-gray-500 text-left">Reason</th>
                    <th class="px-3 py-2 text-sm font-semibold text-gray-500 text-left">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td class="px-3 py-2 text-xs border-b">{{ $item->job_number }}</td>
                        <td class="px-3 py-2 text-xs border-b">{{ $item->customer->name }}</td>
                        <td class="px-3 py-2 text-xs border-b">{{ $item->item->item_name }}</td>
                        <td class="px-3 py-2 text-xs border-b">{{ $item->quantity }}</td>
                        <td class="px-3 py-2 text-xs border-b">{{ $item->reason }}</td>
                        <td class="px-3 py-2 text-xs border-b font-semibold text-gray-700">
                            @if ($item->status === 'approved')
                                <span
                                    class="bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500 rounded-full px-2 py-0.5">Approved</span>
                            @elseif ($item->status === 'rejected')
                                <span
                                    class="bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-400 rounded-full px-2 py-0.5">Rejected</span>
                            @elseif ($item->status === 'pending')
                                <span
                                    class="bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-500 rounded-full px-2 py-0.5">Pending</span>
                            @endif

                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- Pagination -->
    <div class="flex justify-between items-center border-t px-6 py-4 dark:border-gray-800">
        <div class="text-sm text-gray-600 dark:text-gray-400">
            Showing {{ $items->firstItem() }} to {{ $items->lastItem() }} of {{ $items->total() }}
            entries
        </div>

        <div class="pagination flex justify-between">
         {{ $items->links('vendor.pagination.custom-tailwind') }}
        </div>
    </div>
</div>
