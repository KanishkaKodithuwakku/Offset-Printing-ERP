<div class="p-4 rounded-xl bg-white dark:bg-dark-800 shadow-sm space-y-5">
    @if (session('success'))
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
            <div class="rounded-xl border border-error-500 bg-red-50 p-4 dark:border-error-500/30 dark:bg-error-500/10">
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

    <div class="grid grid-cols-2 gap-6">
        <!-- Left Column -->
        <div class="space-y-4">
            <!-- Transfer Date -->
            <div>
                <label class="block mb-1 text-xs font-medium text-gray-700 dark:text-gray-400">Transfer Date</label>
                <input type="text" value="{{ now()->format('Y-m-d') }}" readonly class="h-8 w-full text-xs px-2 py-1 rounded-lg border border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-white/70 cursor-not-allowed">
            </div>
            <!-- From Branch -->
            <div>
                <label class="block mb-1 text-xs font-medium text-gray-700 dark:text-gray-400">From Branch</label>
                <select wire:model="from_branch_id" class="h-8 w-full text-xs px-2 py-1 rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent dark:bg-gray-900 text-gray-800 dark:text-white/90">
                    <option value="">Select</option>
                    @foreach ($branches as $branch)
                    <option value="{{ $branch->id }}">{{ $branch->branch_code }} - {{ $branch->branch_name }}</option>
                    @endforeach
                </select>
                @error('from_branch_id')
                <span class="text-xs text-error-500 mt-1">{{ $message }}</span>
                @enderror
            </div>
             <!-- To Branch -->
            <div>
                <label class="block mb-1 text-xs font-medium text-gray-700 dark:text-gray-400">To Branch</label>
                <select wire:model="to_branch_id" class="h-8 w-full text-xs px-2 py-1 rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent dark:bg-gray-900 text-gray-800 dark:text-white/90">
                    <option value="">Select</option>
                    @foreach ($branches as $branch)
                    <option value="{{ $branch->id }}">{{ $branch->branch_code }} - {{ $branch->branch_name }}</option>
                    @endforeach
                </select>
                @error('to_branch_id')
                <span class="text-xs text-error-500 mt-1">{{ $message }}</span>
                @enderror
            </div>
            <!-- Remark -->
            <div>
                <label class="block mb-1 text-xs font-medium text-gray-700 dark:text-gray-400">Remark</label>
                <textarea wire:model="remark" rows="1" class="w-full h-8 text-xs px-2 py-1 rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent dark:bg-gray-900 text-gray-800 dark:text-white/90"></textarea>
                @error('remark')
                <span class="text-xs text-error-500 mt-1">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-4">
           
           
           {{-- Search job number starts --}}
            {{-- <div class="relative pt-4 ">
                <label class="block text-xs text-gray-700 font-medium">Search Job {{$job_order_id}}<span
                        class="text-error-500">*</span></label>
                <input type="text" wire:model.live.throttle.150ms="searchJob" placeholder="Search Job"
                    class="dark:bg-dark-900 mt-1 mb-4 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                <!-- Search Results -->
                @if (!empty($searchResultsJobs))
                    <div class="max-w-full overflow-x-auto custom-scrollbar border">
                        <table class="w-full">
                            <thead>
                                <tr class="border-t border-gray-100 dark:border-gray-800">
                                    <th class="px-3 py-3 text-left">
                                        <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                            Job Number
                                        </p>
                                    </th>
                                    <th class="px-6 py-3 text-left">
                                        <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                            Description
                                        </p>
                                    </th>
                                    <th class="px-6 py-3 text-left">
                                        <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                            Customer
                                        </p>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($searchResultsJobs as $job)
                                    <tr wire:click="assignJobOrder({{ $job['id'] }})"
                                        class="border-t border-gray-100 dark:border-gray-800 cursor-pointer hover:bg-gray-200">
                                        <td class="px-2 py-3.5">
                                            <p class="font-medium text-gray-500 text-theme-sm dark:text-white/90">
                                                {{ $job['job_number'] }}
                                            </p>
                                        </td>
                                        <td class="px-6 py-3.5">
                                            <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                {{ $job['description'] }}</p>
                                        </td>
                                        <td class="px-6 py-3.5">
                                            <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                {{ $job['customer_name'] }}</p>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                @error('job_id')
                    <span class="text-sm text-error-500">{{ $message }}</span>
                @enderror
            </div> --}}
            {{-- Search Job number ends --}}
           
           
           
           
            <!-- Search Item -->
            <div>
                <label class="block text-xs text-gray-700 font-medium">Search Item<span class="text-error-500">*</span></label>
                <input type="text" wire:model.live.throttle.150ms="searchTerm" placeholder="Search items..." class="dark:bg-dark-900 mt-2 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-1.5 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                <!-- Search Results -->
                @if (!empty($searchResults))
                <div class="max-w-full overflow-x-auto custom-scrollbar border">
                    <table class="w-full">
                        <thead>
                            <tr class="border-t border-gray-100 dark:border-gray-800">
                                <th class="px-3 py-3 text-left">
                                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Item</p>
                                </th>
                                <th class="px-6 py-3 text-left">
                                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Code</p>
                                </th>
                                <th class="px-6 py-3 text-left">
                                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Selling</p>
                                </th>
                                <th class="px-6 py-3 text-left">
                                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Stock</p>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($searchResults as $item)
                            <tr wire:click="addOrderItem({{ $item['id'] }})" class="border-t border-gray-100 dark:border-gray-800 cursor-pointer hover:bg-gray-200">
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
                                        {{ $item['selling_price'] }}</p>
                                </td>
                                <td class="px-6 py-3">
                                    <p class="text-gray-500 text-theme-xs dark:text-gray-400">
                                        {{ $item['stock_balance'] }}
                                    </p>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
            <!-- Item Table -->
            @if (!empty($jobOrderItems))
            {{-- {{ json_encode($jobOrderItems) }} --}}
            <div class="custom-scrollbar sm:p-6 max-w-full overflow-x-auto ">
                <table class="min-w-full">
                    <thead class="border-y border-gray-100 py-2 dark:border-gray-800">
                        <tr class="bg-gray-200">
                            <th class="py-2 font-normal whitespace-nowrap px-2">
                                <div class="flex items-center">
                                    <p class="text-theme-xs text-gray-500 dark:text-gray-400">Item</p>
                                </div>
                            </th>
                            <th class="py-2 font-normal whitespace-nowrap px-2">
                                <div class="flex items-center">
                                    <p class="text-theme-xs text-gray-500 dark:text-gray-400">Code</p>
                                </div>
                            </th>
                            <th class="py-2 font-normal whitespace-nowrap">
                                <div class="flex items-center">
                                    <p class="text-theme-xs text-gray-500 dark:text-gray-400">Quantity</p>
                                </div>
                            </th>
                            <th class="py-2 font-normal whitespace-nowrap">
                                <div class="flex items-center">
                                    <p class="text-theme-xs text-gray-500 dark:text-gray-400">Remove</p>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach ($jobOrderItems as $index => $orderItem)
                        <tr class="cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800">
                            <td class="py-1 whitespace-nowrap">
                                <div class="flex items-center">
                                    <p class="text-theme-xs text-gray-700 dark:text-gray-400 px-2">
                                        {{ $orderItem['name'] }}
                                    </p>
                                </div>
                            </td>
                            <td class="py-1 whitespace-nowrap">
                                <div class="flex items-center">
                                    <p class="text-theme-xs text-gray-700 dark:text-gray-400 px-2">
                                        {{ $orderItem['code'] }}
                                    </p>
                                </div>
                            </td>
                            <td class="py-1 whitespace-nowrap">
                                <div class="flex items-center">
                                    <p class="text-theme-xs text-gray-700 dark:text-gray-400">
                                        <input type="number" wire:model="jobOrderItems.{{ $index }}.quantity" wire:change="updateTotal({{ $index }})" min="{{ isset($dispatchedCount) ? $dispatchedCount : 1 }}" class="w-16 border p-1 text-center">
                                    </p>
                                </div>
                            </td>
                            <td class="py-1 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center">
                                    <p class="text-theme-xs text-gray-300 dark:text-gray-400 text-center hover:text-gray-500">
                                        @if ($status != 'complete')
                                        <button wire:click="removeItem({{ $index }},{{ $orderItem['id'] }})" class="text-red-500">
                                            <svg class="w-4 h-4 text-gray-800 dark:text-white hover:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                        </button>
                                        @else
                                        <button class="text-red-500">
                                            <svg class="w-4 h-4 text-gray-300 dark:text-white hover:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                        </button>
                                        @endif
                                    </p>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex justify-end gap-4">
        <button wire:click="save" class="bg-brand-500 hover:bg-brand-600 text-white text-xs font-medium px-4 py-2 rounded-lg shadow-sm transition">
            Transfer
        </button>
    </div>
</div>
