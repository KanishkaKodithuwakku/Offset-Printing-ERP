<div>
    <div class="my-4">
        @if (session('message'))
            <div class="rounded-xl border border-success-500 bg-success-50 p-4 dark:border-success-500/30 dark:bg-success-500/15">
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
            <div class="rounded-xl border border-red-500 bg-red-50 p-4 dark:border-red-500/30 dark:bg-red-500/10">
                <div class="flex items-start gap-3">
                    <div class="-mt-0.5 text-red-500">
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

        <div
            class="w-full max-w-4xl rounded-2xl border p-6 border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] ">

        <!-- Search and Filter Section -->
        {{-- <input type="text" wire:model="search" placeholder="Search Adjustments"
            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 "
            style="width: 15%">
        <select wire:model="statusFilter"
            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
            style="width: 15%">
            <option value="">Select Status</option>
            <option value="pending">Pending</option>
            <option value="approved">Approved</option>
        </select> --}}

        

            <!-- Adjustments Table -->
            <table class="table mt-6  border-gray-300" style="width: 50%">
                <thead>
                    <tr class="bg-gray-100">
                        <th class=" border-gray-200 border text-gray-500 px-4 py-2 text-sm font-semibold text-left">ID
                        </th>
                        <th class=" border-gray-200 border text-gray-500 px-4 py-2 text-sm font-semibold text-left">
                            Reason</th>
                        <th class=" border-gray-200 border text-gray-500 px-4 py-2 text-sm font-semibold text-left">
                            Status</th>
                        <th class=" border-gray-200 border text-gray-500 px-4 py-2 text-sm font-semibold text-left">
                            Created At</th>
                        <th class=" border-gray-200 border text-gray-500 px-4 py-2 text-sm font-semibold text-left">
                            Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($adjustments as $adjustment)
                        <tr wire:click="selectAdjustment({{ $adjustment->id }})"
                            class="cursor-pointer hover:bg-gray-100" tabindex="0" role="button" aria-pressed="false">
                            <td class=" border-gray-200 border text-sm px-4 py-2">{{ $adjustment->id }}</td>
                            <td class=" border-gray-200 border text-sm px-4 py-2">{{ $adjustment->reason }}</td>
                            <td class=" border-gray-200 border text-sm px-4 py-2">{{ $adjustment->status }}</td>
                            <td class=" border-gray-200 border text-sm px-4 py-2">
                                {{ $adjustment->created_at->format('d-m-Y H:i') }}</td>
                            <td class=" border-gray-200 border text-sm px-4 py-2 text-left">
                                <button type="button" class="btn btn-primary "
                                    wire:click.stop="selectAdjustment({{ $adjustment->id }})">
                                    <svg class="fill-current text-gray-500 hover:text-gray-700" width="18"
                                        height="18" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M1.5 12C1.5 12 5.25 4.5 12 4.5C18.75 4.5 22.5 12 22.5 12C22.5 12 18.75 19.5 12 19.5C5.25 19.5 1.5 12 1.5 12ZM12 15C13.6569 15 15 13.6569 15 12C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15Z"
                                            fill="" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="border border-gray-300 px-4 py-2 text-center" colspan="5">No adjustments
                                found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>


            <!-- Pagination Section -->
            <div class="mt-4">
                @if ($adjustments->hasPages())
                    {{ $adjustments->links() }}
                @endif
            </div>

        <!-- Show details of selected adjustment -->
        @if ($selectedAdjustmentId)
            <div class="mt-4">
                @livewire('stock.stock-adjustment-item-list', ['adjustmentId' => $selectedAdjustmentId], key($selectedAdjustmentId))

            </div>
        @endif
    </div>
</div>
