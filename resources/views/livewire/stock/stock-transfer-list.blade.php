<div >
    <div
        class="w-full max-w-4xl mx-auto p-6 rounded-2xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800">
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
        <div class="flex">
            <div class="pr-5">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    Stock Transfer List
            </div>

            <div>
                {{-- Status Dropdown --}}
                <div>
                    <label class="mb-1  block text-xs font-medium text-gray-700 dark:text-gray-400">Status</label>
                    <select wire:model.change="statusFilter"
                        class="h-8 rounded-md border border-gray-300 dark:border-gray-600 pr-6 text-xs dark:bg-gray-900 dark:text-white">
                        <option value="">All</option>
                        <option value="approved">Complete</option>
                        <option value="pending">Pending</option>
                    </select>
                </div>
            </div>
        </div>


        <table class="min-w-full mt-5 text-sm border border-gray-300 dark:border-gray-600 border-collapse">
            <thead class="bg-gray-100 dark:bg-gray-900">
                <tr>
                    <th class="h-10 px-4 py-2 border-b border-t border-gray-300 dark:border-gray-600 font-semibold text-gray-700 dark:text-gray-300 text-left">ID</th>
                    <th class="h-10 px-4 py-2 border-b border-t border-gray-300 dark:border-gray-600 font-semibold text-gray-700 dark:text-gray-300 text-left">Job</th>
                    <th class="h-10 px-4 py-2 border-b border-t border-gray-300 dark:border-gray-600 font-semibold text-gray-700 dark:text-gray-300 text-left">From</th>
                    <th class="h-10 px-4 py-2 border-b border-t border-gray-300 dark:border-gray-600 font-semibold text-gray-700 dark:text-gray-300 text-left">To</th>
                    <th class="h-10 px-4 py-2 border-b border-t border-gray-300 dark:border-gray-600 font-semibold text-gray-700 dark:text-gray-300 text-left">Status</th>
                    <th class="h-10 px-4 py-2 border-b border-t border-gray-300 dark:border-gray-600 font-semibold text-gray-700 dark:text-gray-300 text-left">Created</th>
                    <th class="h-10 px-4 py-2 border-b border-t border-gray-300 dark:border-gray-600 font-semibold text-gray-700 dark:text-gray-300 text-left">Action</th>
                </tr>
            </thead>
            <tbody class="dark:bg-gray-700">
                @foreach ($transfers as $transfer)
                    <tr wire:click="showItems({{ $transfer->id }})" class="h-10 cursor-pointer hover:bg-blue-50 dark:hover:bg-gray-800 transition-colors">
                        <td class="px-4 py-1 border-b border-t border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-300 ">{{ $transfer->transfer_code }}</td>
                        <td class="px-4 py-1 border-b border-t border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-300 ">{{ $transfer->job->job_number?? 'N/A' }}</td>
                        <td class="px-4 py-1 border-b border-t border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-300">{{ $transfer->fromBranch->branch_name ?? '-' }}</td>
                        <td class="px-4 py-1 border-b border-t border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-300">{{ $transfer->toBranch->branch_name ?? '-' }}</td>
                        <td class="px-4 py-1 border-b border-t border-gray-300 dark:border-gray-600">
                            @if ($transfer->status === 'complete')
                                <span class="bg-success-50 font-semibold text-xs text-success-600 dark:bg-success-500/15 dark:text-success-500 rounded-full px-2 py-0.5">Complete</span>
                            @elseif ($transfer->status === 'failed')
                                <span class="bg-error-50 font-semibold text-xs text-error-600 dark:bg-error-500/15 dark:text-error-400 rounded-full px-2 py-0.5">Rejected</span>
                            @elseif ($transfer->status === 'pending')
                                <span class="bg-warning-50 font-semibold text-xs text-warning-600 dark:bg-warning-500/15 dark:text-warning-500 rounded-full px-2 py-0.5">Pending</span>
                            @endif
                        </td>
                        <td class="px-4 py-1 border-b border-t border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-300">{{ $transfer->created_at->format('Y-m-d') }}</td>
                        <td class="px-4 py-1 border-b border-t border-gray-300 dark:border-gray-600 text-gray-500 dark:text-gray-300" >
                            <a wire:navigate href="{{ route('stock-transfer.print', ['transferCode' => $transfer->transfer_code]) }}"  style="display: flex; width:50%; align-items: center; gap: 0.5rem; font-family: outfit; padding: 0.1rem 0.5rem; font-size: 0.875rem; font-weight: 500; color: white; border-radius: 0.375rem; background-color: #465FFF; box-shadow: 0 2px 2px rgba(0, 0, 0, 0.1);  text-decoration: none;">
                                <svg style="width: 1.5rem; height: 1.5rem; color: white;" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linejoin="round" stroke-width="2" d="M16.444 18H19a1 1 0 0 0 1-1v-5a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1h2.556M17 11V5a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v6h10ZM7 15h10v4a1 1 0 0 1-1 1H8a1 1 0 0 1-1-1v-4Z" />
                                </svg>
                                Print
                            </a>
                        </td>
                    </tr>

                    @if ($selectedTransferId === $transfer->id)
                        <tr>
                            <td colspan="7" class="bg-blue-50 dark:bg-gray-800 px-6 py-4 border border-gray-300 dark:border-gray-600">
                                <div class="max-w-full overflow-x-auto">
                                    <h4 class="font-semibold text-gray-600 dark:text-gray-400 mb-2">Transfer Items</h4>
                                    <table class="min-w-full text-xs border border-gray-300 dark:border-gray-600 border-collapse mb-2">
                                        <thead class="bg-blue-100 dark:bg-gray-900">
                                            <tr>
                                                <th class="h-10 px-3 py-2 border border-gray-300 dark:border-gray-600 text-left font-semibold text-gray-600 dark:text-gray-300">Item</th>
                                                <th class="h-10 px-3 py-2 border border-gray-300 dark:border-gray-600 text-left font-semibold text-gray-600 dark:text-gray-300">Quantity</th>
                                                <th class="h-10 px-3 py-2 border border-gray-300 dark:border-gray-600 text-left font-semibold text-gray-600 dark:text-gray-300">Transferred</th>
                                                <th class="h-10 px-3 py-2 border border-gray-300 dark:border-gray-600 text-left font-semibold text-gray-600 dark:text-gray-300">Balance</th>
                                                <th class="h-10 px-3 py-2 border border-gray-300 dark:border-gray-600 text-left font-semibold text-gray-600 dark:text-gray-300">Pending Add</th>
                                                <th class="h-10 px-3 py-2 border border-gray-300 dark:border-gray-600 text-left font-semibold text-gray-600 dark:text-gray-300">Total</th>
                                                <th class="h-10 px-3 py-2 border border-gray-300 dark:border-gray-600 text-left font-semibold text-gray-600 dark:text-gray-300">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="dark:bg-gray-700">
                                            @foreach ($items as $item)
                                                <tr class="h-10 hover:bg-blue-200/30 dark:hover:bg-gray-800 transition-colors">
                                                    <td class="px-3 py-1 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-300">{{ $item->item->item_name }}</td>
                                                    <td class="px-3 py-1 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300">{{ $item->quantity }}</td>
                                                    <td class="px-3 py-1 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300">{{ $item->transferred_quantity ?? 0 }}</td>
                                                    <td class="px-3 py-1 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300">{{ $item->quantity - ($item->transferred_quantity ?? 0) }}</td>
                                                    <td class="px-3 py-1 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300">{{ $item->partially_added_quantity ?? 0 }}</td>
                                                    <td class="px-3 py-1 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300">{{ number_format($item->total, 2) }}</td>
                                                    <td class="px-3 py-1 border border-gray-300 dark:border-gray-600 text-center">
                                                        <button wire:click.prevent="openPartialTransferModal({{ $item->id }}, {{ $transfer->id }})"
                                                            class="bg-brand-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-xs"
                                                            @if(($item->quantity - ($item->transferred_quantity ?? 0)) == 0) disabled style="background-color: #aab2b7; color: black; cursor: not-allowed;" @endif>
                                                            Partial Transfer
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            @if ($items->isEmpty())
                                                <tr>
                                                    <td colspan="7" class="text-center text-gray-400 dark:text-gray-500 py-4 border border-gray-300 dark:border-gray-600">No items found</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                    @if ($transfer->status !== 'complete')
                                        @php
                                            $allTransferred = true;
                                            foreach ($items as $item) {
                                                if (($item->quantity - ($item->transferred_quantity ?? 0)) > 0) {
                                                    $allTransferred = false;
                                                    break;
                                                }
                                            }
                                            $buttonClasses = 'font-medium px-6 py-2 rounded-md text-xs shadow-md transition duration-300 ease-in-out transform hover:scale-105 ';
                                            $buttonClasses .= $allTransferred ? 'bg-brand-500 hover:bg-brand-500 text-white' : 'bg-gray-200 text-gray-400 dark:text-gray-600 cursor-not-allowed';
                                            $buttonStyle = $allTransferred ? 'background-color:#465FFF;' : 'background-color: #495668; cursor: not-allowed;';
                                        @endphp
                                        <button
                                            wire:click.prevent="markAsComplete({{ $transfer->id }},{{ true }})"
                                            class="{{ $buttonClasses }}"
                                            style="{{ $buttonStyle }}"
                                            @if(!$allTransferred) disabled @endif
                                        >
                                            Confirm Transfer
                                        </button>
                                    @else
                                        <button
                                            class="mt-2 px-6 py-2 text-white bg-gray-400 dark:bg-gray-600 dark:text-gray-700 font-semibold rounded-md cursor-not-allowed"
                                            disabled>
                                            Transferred
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>

        {{-- Partial Transfer Modal --}}
        @if($showPartialTransferModal)
            <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-40 border border-gray-800" style="border: 2px solid #465FFF !important;">
                <div class="bg-white dark:bg-gray-800 p-6 rounded shadow-lg w-96 border-gray-800 dark:border-gray-600"
                    style="box-shadow: 0 10px 25px rgba(0,0,0,0.25), 0 1.5px 6px rgba(70,95,255,0.10);">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">Partial Transfer for {{ $modalItemName }}</h3>
                    <form wire:submit.prevent="submitPartialTransfer({{ $isAddToStock ? 'true' : 'false' }})">
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Quantity to Transfer</label>
                            <input type="number" min="1" max="{{ $modalItemMaxQuantity }}"
                                wire:model.defer="partialQuantity"
                                class="w-full border border-gray-300 dark:border-gray-600 rounded px-3 py-2 dark:bg-gray-700 dark:text-white"
                                required
                            >
                            @error('partialQuantity') <span class="text-error-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4 flex items-center">
                            <input type="checkbox" id="isAddToStock" wire:model.live="isAddToStock" class="mr-2">
                            <label for="isAddToStock" class="text-sm text-gray-700 dark:text-gray-300">Add to destination branch stock {{$isAddToStock}}</label>
                        </div>
                        <div class="flex justify-end gap-2">
                            <button type="button" wire:click="closePartialTransferModal" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded">Cancel</button>
                            <button type="submit" class="px-4 py-2 bg-brand-500 text-white rounded">Transfer</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        <div class="mt-6 flex justify-center">
            {{ $transfers->links('vendor.pagination.custom-tailwind') }}
        </div>

        <div class="flex justify-between items-center border-t px-6 py-4 dark:border-gray-600">
        <div class="text-sm text-gray-600 dark:text-gray-300">
            Showing {{ $transfers->firstItem() }} to {{ $transfers->lastItem() }} of {{ $transfers->total() }}
            entries
        </div>

        <div class="pagination flex justify-between">
         {{ $transfers->links('vendor.pagination.custom-tailwind') }}
        </div>
    </div>
    </div>
</div>
