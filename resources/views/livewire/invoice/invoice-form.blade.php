<div class=" flex flex-row justify-start">
    <!-- left side -->
    <div class="bg-white p-6 rounded-lg shadow-md flex-1">
        <h2 class="text-2xl font-semibold mb-4">{{ $invoiceId ? 'Update' : 'Create' }} Invoice order id {{$order_id}}</h2>

        <!-- Customer Selection -->
        <div class="relative mb-6">
            <label class="block text-gray-700 font-medium">Customer</label>
            <input type="text" wire:model.live.throttle.150ms="searchCustomer" placeholder="Search customer..."
                class="w-full border p-2 rounded focus:ring focus:ring-blue-300" />
            <!-- Search Results -->
            @if (!empty($searchResultsCustomer))
                <ul class="absolute z-10 w-full bg-white border rounded shadow mt-1">
                    @foreach ($searchResultsCustomer as $customer)
                        <li class="p-2 cursor-pointer hover:bg-gray-100 flex justify-between items-center"
                            wire:click="assignCustomer({{ $customer['id'] }})">
                            <div class="flex flex-row justify-start gap-1">
                                <span class="">
                                    {{ $customer['name'] }}
                                </span>
                                <span>
                                    ({{ $customer['phone'] }})
                                </span>
                                <span>
                                    {{ $customer['city'] }}
                                </span>
                                <button class="bg-green-500 text-white px-2 py-1 rounded">
                                    Add
                                </button>
                            </div>

                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div class="relative mb-6">
            <label class="block text-gray-700 font-medium">Order</label>
            <input type="text" wire:model.live.throttle.150ms="searchOrder" placeholder="Search order..."
                class="w-full border p-2 rounded focus:ring focus:ring-blue-300" />
            <!-- Search Results -->
            @if (!empty($searchResultsOrder))
                <ul class="absolute z-10 w-full bg-white border rounded shadow mt-1">
                    @foreach ($searchResultsOrder as $order)
                        <li class="p-2 cursor-pointer hover:bg-gray-100 flex justify-between items-center"
                            wire:click="assignOrder({{ $order['id'] }})">
                            <div class="flex flex-row justify-start gap-1">
                                <span class="">
                                    {{ $order['order_customer_name'] }}
                                </span>
                                <span class="">
                                    {{ $order['order_number'] }}
                                </span>
                                <span>
                                    ({{ $order['total_amount'] }})
                                </span>

                                <button class="bg-green-500 text-white px-2 py-1 rounded">
                                    Add
                                </button>
                            </div>
                        </li>
                    @endforeach
                </ul>
                @elseif (strlen($this->searchOrder) > 1 && empty($searchResultsOrder) && !$orderSelected)
                <!-- Display message when no orders are found -->
                <div class="absolute z-10 w-full bg-white border rounded shadow mt-1 p-2 text-gray-500">
                    Order not found!
                </div>
            @endif
        </div>


        <div class="mb-4">

        </div>
        <!-- Payment Method -->
        <div class="mt-4">
            <label class="block text-gray-700 font-medium">Payment Method</label>
            <select wire:model="payment_method" class="w-full border p-2 rounded" disabled>
                <option value="cash">Cash</option>
                <option value="bank">Bank</option>
                <option value="credit">Credit</option>
                <option value="cheque">Cheque</option>
            </select>
        </div>

        <div class="mt-4">
            @if (!empty($invoiceId))
                <!-- Check if $orderId is either not set or empty -->
                <label class="block text-gray-700 font-medium">Status</label>
                <select wire:model="status" class="w-full border p-2 rounded">
                    <option value="invoiced">Invoiced</option>
                    <option value="printed">Printed</option>
                    <option value="delivered">Delivered</option>
                </select>
            @endif
        </div>


        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                Cheque Number
            </label>
            <input disabled type="text" wire:model="cheque_no" min="1"
                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
            @error('cheque_no')
                <p style="color: red" class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                Bank
            </label>
            <input disabled type="text" wire:model="bank_name" min="1"
                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
            @error('bank_name')
                <p style="color: red" class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>


        <div class="mt-4">
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                Realization Date
            </label>
            <input disabled type="date" wire:model="cheque_realization_date"
                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
            @error('cheque_realization_date')
                <p sstyle="color: red" class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Save Button -->
        @if ($status === 'plan')
            <div class="mt-6">
                <button wire:click="createInvice"
                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600" style="background-color:#465fff">
                    Generate Invoice
                </button>
            </div>
        @endif
        @if (session()->has('success'))
            <div class="mt-4 text-green-500">
                {{ session('success') }}
            </div>
        @endif
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


        @if ($order_customer)
            <div class="border-t border-gray-100 p-4 dark:border-gray-800 sm:p-6">

                <div
                    class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] sm:w-fit">
                    <ul class="flex flex-col">
                        <li class="border-b border-gray-200 px-3 py-2.5 last:border-b-0 dark:border-gray-800">
                            <div x-data="{ checkboxToggle: false }">
                                <label for="listCheckboxOne"
                                    class="flex cursor-pointer select-none items-center text-sm text-gray-500 dark:text-gray-400">

                                    <svg class="w-6 h-6 text-gray-200 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-width="2" d="M7 17v1a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-1a3 3 0 0 0-3-3h-4a3 3 0 0 0-3 3Zm8-9a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                      </svg>


                                    {{ $order_customer }}
                                </label>
                            </div>
                        </li>


                        <li class="border-b border-gray-200 px-3 py-2.5 last:border-b-0 dark:border-gray-800">
                            <div x-data="{ checkboxToggle: false }">
                                <label for="listCheckboxOne"
                                    class="flex cursor-pointer select-none items-center text-sm text-gray-500 dark:text-gray-400">
                                    <svg class="w-6 h-6 text-gray-200 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-6 5h6m-6 4h6M10 3v4h4V3h-4Z"/>
                                      </svg>


                                    {{ $order_number }} on {{$created_at}}
                                </label>
                            </div>
                        </li>

                        <li class="border-b border-gray-200 px-3 py-2.5 last:border-b-0 dark:border-gray-800">
                            <div x-data="{ checkboxToggle: false }">
                                <label for="listCheckboxOne"
                                    class="flex cursor-pointer select-none items-center text-sm text-gray-500 dark:text-gray-400">
                                    <svg class="w-6 h-6 text-gray-200 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m11.0001 18-.8536-.8536c-.0937-.0937-.1464-.2209-.1464-.3535v-4.4172c0-.2422-.08794-.4762-.24744-.6585L4.45127 5.6585C3.88551 5.01192 4.34469 4 5.20385 4H18.7547c.8658 0 1.3225 1.02544.7433 1.66896L16.5001 9m-2.5 9.3754c.3347.3615.7824.6134 1.2788.7195.4771.1584 1.0002.1405 1.464-.05.4638-.1906.8338-.5396 1.0356-.977.2462-.8286-.6363-1.7337-1.7735-1.9948-1.1372-.2611-2.016-1.1604-1.7735-1.9948.2016-.4375.5716-.7868 1.0354-.9774.4639-.1905.9871-.2082 1.4643-.0496.491.1045.9348.3517 1.2689.7067m-1.9397 5.41V20m0-8v.9771"/>
                                      </svg>


                                    {{ $total_amount }}
                                </label>
                            </div>
                        </li>


                        <li class="border-b border-gray-200 px-3 py-2.5 last:border-b-0 dark:border-gray-800">
                            <div x-data="{ checkboxToggle: false }">
                                <label for="listCheckboxOne"
                                    class="flex cursor-pointer select-none items-center text-sm text-gray-500 dark:text-gray-400">
                                    <svg class="w-6 h-6 text-gray-200 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M8 7V6a1 1 0 0 1 1-1h11a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1h-1M3 18v-7a1 1 0 0 1 1-1h11a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1Zm8-3.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
                                      </svg>


                                    {{ $advance_payment }}
                                </label>
                            </div>
                        </li>

                        <li class="border-b border-gray-200 px-3 py-2.5 last:border-b-0 dark:border-gray-800">
                            <div x-data="{ checkboxToggle: false }">
                                <label for="listCheckboxOne"
                                    class="flex cursor-pointer select-none items-center text-sm text-gray-500 dark:text-gray-400">
                                    <svg class="w-6 h-6 text-gray-200 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                        <path fill-rule="evenodd" d="M4 5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H4Zm0 6h16v6H4v-6Z" clip-rule="evenodd"/>
                                        <path fill-rule="evenodd" d="M5 14a1 1 0 0 1 1-1h2a1 1 0 1 1 0 2H6a1 1 0 0 1-1-1Zm5 0a1 1 0 0 1 1-1h5a1 1 0 1 1 0 2h-5a1 1 0 0 1-1-1Z" clip-rule="evenodd"/>
                                      </svg>


                                    {{ $payment_method }} - {{ $bank_name }}

                                    @if ($payment_method === 'cheque')
                                        <p>{{ $cheque_no }}</p>
                                        <p>{{ $cheque_realization_date }}</p>
                                    @endif
                                </label>
                            </div>
                        </li>

                    </ul>
                </div>
            </div>
        @endif


    </div>

</div>
