<div class="flex flex-row justify-start">
    <!-- left side -->
    <div class="bg-white p-6 rounded-lg shadow-md flex-1">
        <h2 class="text-2xl font-semibold mb-4">{{ $orderId ? 'Update' : 'Create' }} Customer Order</h2>

        <!-- Customer Selection -->
        <div class="relative mb-6">
            <label class="block text-gray-700 font-medium">Search Customer</label>
            <input type="text" wire:model.live.throttle.150ms="searchCustomer" wire:click="onSearchClick"
                placeholder="Search customer..." class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
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
        </div>

        <div class="mb-4">

        </div>
        <!-- Payment Method -->
        <div class="mt-4">
            <label class="block text-gray-700 font-medium">Payment Method</label>
            <select wire:model="payment_method" class="w-full border p-2 rounded">
                <option value="cash">Cash</option>
                <option value="bank">Bank</option>
                <option value="credit">Credit</option>
                <option value="cheque">Cheque</option>
            </select>
        </div>

        <div class="mt-4">
            @if (!empty($orderId))
                <!-- Check if $orderId is either not set or empty -->
                <label class="block text-gray-700 font-medium">Status</label>
                <select wire:model="status" class="w-full border p-2 rounded">
                    <option value="plan">Plan</option>
                    <option value="released">Release</option>
                </select>
            @endif
        </div>


        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                Cheque Number
            </label>
            <input type="text" wire:model="cheque_no" min="1"
                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
            @error('cheque_no')
                <p style="color: red" class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                Bank
            </label>
            <input type="text" wire:model="bank_name" min="1"
                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
            @error('bank_name')
                <p style="color: red" class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>


        <div class="mt-4">
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                Realization Date
            </label>
            <input type="date" wire:model="cheque_realization_date"
                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
            @error('cheque_realization_date')
                <p sstyle="color: red" class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>


        <div class="flex items-center justify-end gap-3 border-t border-gray-100 dark:border-gray-800 mt-10 mb-10 pt-5">

            @if ($status === 'plan')
            <button  wire:click="saveOrUpdateOrder"
              class="flex items-center justify-center gap-2 px-4 py-3 text-sm font-medium text-white rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600"
            >

            <svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h3a3 3 0 0 0 0-6h-.025a5.56 5.56 0 0 0 .025-.5A5.5 5.5 0 0 0 7.207 9.021C7.137 9.017 7.071 9 7 9a4 4 0 1 0 0 8h2.167M12 19v-9m0 0-2 2m2-2 2 2"/>
              </svg>
              Save Order
            </button>
            @endif

            @if (session()->has('success'))
            <div class="mt-4 text-green-500">
                {{ session('success') }}
            </div>
        @endif
          </div>

        <!-- Save Button -->
        {{-- @if ($status === 'plan')
            <div class="mt-6">
                <button wire:click="saveOrUpdateOrder"
                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600" style="background-color:#465fff">
                    {{ $orderId ? 'Update Order' : 'Save Order' }}
                </button>
            </div>
        @endif
        @if (session()->has('success'))
            <div class="mt-4 text-green-500">
                {{ session('success') }}
            </div>
        @endif --}}
    </div>

    <div class="flex-1">

        @if ($customer_id)
            <div class="border-t border-gray-100 p-4 dark:border-gray-800 sm:p-6">

                <div
                    class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] sm:w-fit">
                    <ul class="flex flex-col">
                        <li class="border-b border-gray-200 px-3 py-2.5 last:border-b-0 dark:border-gray-800">
                            <div x-data="{ checkboxToggle: false }">
                                <label for="listCheckboxOne"
                                    class="flex cursor-pointer select-none items-center text-sm text-gray-500 dark:text-gray-400">

                                    <svg class="w-6 h-6 text-gray-200 dark:text-white" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                        viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M7 6H5m2 3H5m2 3H5m2 3H5m2 3H5m11-1a2 2 0 0 0-2-2h-2a2 2 0 0 0-2 2M7 3h11a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1Zm8 7a2 2 0 1 1-4 0 2 2 0 0 1 4 0Z" />
                                    </svg>

                                    {{ $customer_name }}
                                </label>
                            </div>
                        </li>


                        <li class="border-b border-gray-200 px-3 py-2.5 last:border-b-0 dark:border-gray-800">
                            <div x-data="{ checkboxToggle: false }">
                                <label for="listCheckboxOne"
                                    class="flex cursor-pointer select-none items-center text-sm text-gray-500 dark:text-gray-400">
                                    <svg class="w-6 h-6 text-gray-200 dark:text-white" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                        viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="m4 12 8-8 8 8M6 10.5V19a1 1 0 0 0 1 1h3v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h3a1 1 0 0 0 1-1v-8.5" />
                                    </svg>

                                    {{ $customer_address }}
                                </label>
                            </div>
                        </li>

                        <li class="border-b border-gray-200 px-3 py-2.5 last:border-b-0 dark:border-gray-800">
                            <div x-data="{ checkboxToggle: false }">
                                <label for="listCheckboxOne"
                                    class="flex cursor-pointer select-none items-center text-sm text-gray-500 dark:text-gray-400">
                                    <svg class="w-6 h-6 text-gray-200 dark:text-white" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                                            d="m3.5 5.5 7.893 6.036a1 1 0 0 0 1.214 0L20.5 5.5M4 19h16a1 1 0 0 0 1-1V6a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Z" />
                                    </svg>

                                    {{ $customer_email }}
                                </label>
                            </div>
                        </li>


                        <li class="border-b border-gray-200 px-3 py-2.5 last:border-b-0 dark:border-gray-800">
                            <div x-data="{ checkboxToggle: false }">
                                <label for="listCheckboxOne"
                                    class="flex cursor-pointer select-none items-center text-sm text-gray-500 dark:text-gray-400">
                                    <svg class="w-6 h-6 text-gray-200 dark:text-white" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M18.427 14.768 17.2 13.542a1.733 1.733 0 0 0-2.45 0l-.613.613a1.732 1.732 0 0 1-2.45 0l-1.838-1.84a1.735 1.735 0 0 1 0-2.452l.612-.613a1.735 1.735 0 0 0 0-2.452L9.237 5.572a1.6 1.6 0 0 0-2.45 0c-3.223 3.2-1.702 6.896 1.519 10.117 3.22 3.221 6.914 4.745 10.12 1.535a1.601 1.601 0 0 0 0-2.456Z" />
                                    </svg>

                                    {{ $customer_phone }}
                                </label>
                            </div>
                        </li>

                        <li class="border-b border-gray-200 px-3 py-2.5 last:border-b-0 dark:border-gray-800">
                            <div x-data="{ checkboxToggle: false }">
                                <label for="listCheckboxOne"
                                    class="flex cursor-pointer select-none items-center text-sm text-gray-500 dark:text-gray-400">
                                    <svg class="w-6 h-6 text-gray-200 dark:text-white" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M5 14v7M5 4.971v9.541c5.6-5.538 8.4 2.64 14-.086v-9.54C13.4 7.61 10.6-.568 5 4.97Z" />
                                    </svg>

                                    {{ $customer_city }}
                                </label>
                            </div>
                        </li>

                    </ul>
                </div>
            </div>
        @endif


    </div>

</div>
