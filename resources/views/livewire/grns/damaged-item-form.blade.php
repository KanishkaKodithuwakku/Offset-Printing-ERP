<div class="p-4 justify-left ">

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



    <form wire:submit.prevent="save" class="w-3/5 mt-5">
        <div
            class="w-full max-w-4xl rounded-2xl p-6 border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] ">
            @csrf


            <div class=" py-4 border-b border-gray-500 dark:border-gray-800">
                <h3 class=" text-base font-medium text-gray-800 dark:text-white/90">Add Damaged Item</h3>
            </div>


            {{-- Search job number starts --}}
            <div class="relative pt-4 ">
                <label class="block text-xs text-gray-700 font-medium">Search Job<span
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
            </div>
            {{-- Search Job number ends --}}

            {{-- Search Customer starts --}}
            <div class="relative ">
                <label class="block text-xs text-gray-700 font-medium">Search Customer<span
                        class="text-error-500">*</span></label>
                <input readonly type="text" wire:model.live.throttle.150ms="searchCustomer"
                    placeholder="Search customer"
                    class="dark:bg-dark-900 mt-1 mb-4  shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                <!-- Search Results -->
                @if (!empty($searchResultsCustomer))
                    <div class="max-w-full overflow-x-auto custom-scrollbar border">
                        <table class="w-full">
                            <thead>
                                <tr class="border-t border-gray-100 dark:border-gray-800">
                                    <th class="px-3 py-3 text-left">
                                        <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                            Name
                                        </p>
                                    </th>
                                    <th class="px-6 py-3 text-left">
                                        <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                            Contact
                                        </p>
                                    </th>
                                    <th class="px-6 py-3 text-left">
                                        <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                            Email
                                        </p>
                                    </th>
                                    <th class="px-6 py-3 text-left">
                                        <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                            City
                                        </p>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($searchResultsCustomer as $customer)
                                    <tr wire:click="assignCustomer({{ $customer['id'] }})"
                                        class="border-t border-gray-100 dark:border-gray-800 cursor-pointer hover:bg-gray-200">
                                        <td class="px-2 py-3.5">
                                            <p class="font-medium text-gray-500 text-theme-sm dark:text-white/90">
                                                {{ $customer['name'] }}
                                            </p>
                                        </td>
                                        <td class="px-6 py-3.5">
                                            <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                {{ $customer['phone'] }}</p>
                                        </td>
                                        <td class="px-6 py-3.5">
                                            <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                {{ $customer['email'] }}</p>
                                        </td>
                                        <td class="px-6 py-3.5">
                                            <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                {{ $customer['city'] }}
                                            </p>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                @endif
                @error('customer_id')
                    <span class="text-sm text-error-500">{{ $message }}</span>
                @enderror
            </div>
            {{-- Search Customer ends --}}

            {{-- Search Item start --}}
            <div class=" mb-1">
                <label class="block text-xs text-gray-700 font-medium">Search Item<span
                        class="text-error-500">*</span></label>
                <input type="text" wire:model.live.throttle.150ms="searchItem" placeholder="Search items"
                    class="dark:bg-dark-900 mt-1 mb-3  shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-1.5 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />

                <!-- Search Results -->
                @if (!empty($searchItemsResults))
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
                                @foreach ($searchItemsResults as $item)
                                    <tr
                                        @if ($item['stock_balance'] != 0) wire:click="addDamagedItem({{ $item['id'] }})"
                            class="border-t border-gray-100 dark:border-gray-800 cursor-pointer hover:bg-error-200"
                            @else
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
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
                @error('item_id')
                    <span class="text-sm text-error-500">{{ $message }}</span>
                @enderror
            </div>
            {{-- Search Item Ends --}}



            {{-- {{ json_encode($searchItem) }} --}}

            <div>
                <label class="block text-xs text-gray-700 font-medium">Quantity</label>
                <input type="number" wire:model="quantity" placeholder="Enter Quantity"
                    class="dark:bg-dark-900 mt-1 mb-4  shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                @error('quantity')
                    <span class="text-error-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-xs text-gray-700 font-medium">Reason (Remark)</label>
                <textarea wire:model="reason" placeholder="Enter reason for damage"
                    class="dark:bg-dark-900 mt-1 mb-4 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-16 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"></textarea>
                @error('reason')
                    <span class="text-error-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" wire:loading.attr="disabled" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-md text-xs shadow-md transition duration-300 ease-in-out transform hover:scale-105"
                            style="background-color:#465FFF;">
                Request Approval
            </button>
        </div>
    </form>
</div>
