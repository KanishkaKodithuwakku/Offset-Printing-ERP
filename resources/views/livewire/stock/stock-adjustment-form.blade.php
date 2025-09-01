<div class="p-6 max-w-lg mx-auto">
    <!-- Adjustment Form -->

    <div
        class="w-full p-6 max-w-4xl rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] ">

        <h3 class="text-lg mb-4 font-semibold text-gray-700">Stock Adjustment</h3>

        <!-- Display a success message -->
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

        <!-- Item Search -->
        <div class=" mt-4 mb-4">
            <label class="block text-sm font-medium text-gray-600">Search Item<span
                    class="text-error-500">*</span></label>
            <input type="text" wire:model.live.throttle.150ms="searchTerm" placeholder="Search items..."
                class="dark:bg-dark-900 mt-2  shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8  rounded-lg border border-gray-300 bg-transparent px-4 py-1.5 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                style="width: 25%" />

            <!-- Search Results -->
            @if (!empty($searchResults))
                <div class="max-w-full overflow-x-auto custom-scrollbar border">
                    <table class="w-full">
                        <thead>
                            <tr class="border-t border-gray-100 dark:border-gray-800">
                                <th class="px-3 py-3 text-left">
                                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                        Item
                                    </p>
                                </th>
                                <th class="px-6 py-3 text-left">
                                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                        Code
                                    </p>
                                </th>
                                <th class="px-6 py-3 text-left">
                                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                        Stock
                                    </p>
                                </th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($searchResults as $item)
                                <tr
                                    @if ($item['stock_balance'] > 0) wire:click="addAdjustmentItem({{ $item['id'] }})"
                                    class="border-t border-gray-100 dark:border-gray-800 cursor-pointer hover:bg-error-200"
                                    @else wire:click="addAdjustmentItem({{ $item['id'] }})"
                                    class="border-t border-gray-100 dark:border-gray-800 opacity-50 cursor-not-allowed hover:bg-error-50 bg-error-50" @endif>
                                    <td class="px-2 py-3">
                                        <p class="font-medium text-gray-500 text-theme-xs dark:text-white/90">
                                            {{ $item['item_name'] }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-3">
                                        <p class="text-gray-500 text-theme-xs dark:text-gray-400">
                                            {{ $item['item_code'] }}</p>
                                    </td>
                                    <td class="px-6 py-3">
                                        <p class="text-gray-500 text-theme-xs dark:text-gray-400">
                                            {{ $item['stock_balance'] }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-3">
                                        <!-- Add Button -->
                                        <button wire:click.prevent="addAdjustmentItem({{ $item['id'] }})"
                                            class="bg-green-600 hover:bg-green-700 text-white px-2 py-1 rounded text-xs">
                                            Add
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Adjustment Items Grid -->
        <div class="mt-6">
            <h3 class="block text-sm font-medium text-gray-600">Adjustment Items</h3>
            <table class="w-full mt-2 table-auto border">
                <thead class="bg-gray-100  dark:bg-gray-800 border">
                    <tr>
                        <th class="px-4 py-2 text-sm text-left">Name</th>
                        <th class="px-4 py-2 text-sm text-left">Code</th>
                        <th class="px-4 py-2 text-sm text-left">Remark</th>
                        <th class="px-4 py-2 text-sm text-left">Qty</th>
                        <th class="px-4 py-2 text-sm text-left">Action</th> <!-- New column for Remove -->
                    </tr>
                </thead>
                <tbody>
                    @foreach ($adjustmentItems as $index => $item)
                        <tr>
                            <td class="px-4 text-xs py-2">{{ $item['item_name'] }}</td>
                            <td class="px-4 text-xs py-2">{{ $item['item_code'] }}</td>
                            <td class="px-4 text-xs py-2">
                                <input type="text" wire:model="adjustmentItems.{{ $index }}.remark"
                                    class="w-full p-1 border text-xs rounded-md">
                            </td>
                            <td class="px-4 py-2">
                                <input type="number" wire:model="adjustmentItems.{{ $index }}.quantity"
                                    class="w-24 p-1 text-xs border rounded-md" min="1">
                            </td>
                            <td>
                                <!-- Remove Button -->
                                <button wire:click="removeAdjustmentItem({{ $item['item_id'] }})"
                                    class="btn btn-danger bg-gray-600 hover:bg-gray-700 text-gray-500 px-4  py-1 rounded text-xs">
                                    <svg class="w-4 h-4 text-gray-800 dark:text-white hover:text-gray-400"
                                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="18"
                                        height="18" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            <label for="reason" class="block text-sm font-medium text-gray-600">Reason</label>
            <textarea id="reason" wire:model="reason" class="w-full p-2 mt-1 border text-xs rounded-md"></textarea>
        </div>

        <!-- Save Button -->
        <div class="mt-4">
            <button wire:click="saveAdjustment"
                class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-md text-xs shadow-md transition duration-300 ease-in-out transform hover:scale-105"
                style="background-color:#465FFF;">Save
                Adjustment</button>
        </div>

    </div>
</div>
