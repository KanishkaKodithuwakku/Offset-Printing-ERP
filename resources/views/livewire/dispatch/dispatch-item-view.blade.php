<div class="grid max-w-screen-xl grid-cols-1 gap-4 p-4 mx-auto sm:grid-cols-1">
    <div class="p-6 bg-white rounded-lg shadow-md">

        <div class="p-5 mb-6 border border-gray-200 rounded-2xl dark:border-gray-800 lg:p-6">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90 lg:mb-6">
                        Dispatch Information #<span
                            class="inline-flex items-center justify-center gap-1 rounded-full bg-brand-50 px-2.5 py-0.5 text-sm font-medium text-brand-500 dark:bg-brand-500/15 dark:text-brand-400">
                            {{-- {{ $dispatchNote->dispatch_number }} --}}
                        </span>
                    </h4>

                    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2 lg:gap-7 2xl:gap-x-32">
                        <div>
                            <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">
                                Customer Name
                            </p>
                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                {{ $customer_name }}
                            </p>
                        </div>

                        <div>
                            <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">
                                Address
                            </p>
                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                {{ $jobOrder->customer->address }}
                            </p>
                        </div>

                        <div>
                            <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">
                                Email address
                            </p>
                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                {{ $jobOrder->customer->email }}
                            </p>
                        </div>

                        <div>
                            <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">
                                Phone
                            </p>
                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                {{ $jobOrder->customer->phone }}
                            </p>
                        </div>

                    </div>
                </div>

                {{-- <button @click="isProfileInfoModal = true"
                    class="flex w-full items-center justify-center gap-2 rounded-full border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200 lg:inline-flex lg:w-auto">
                    <svg class="fill-current" width="18" height="18" viewBox="0 0 18 18" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M15.0911 2.78206C14.2125 1.90338 12.7878 1.90338 11.9092 2.78206L4.57524 10.116C4.26682 10.4244 4.0547 10.8158 3.96468 11.2426L3.31231 14.3352C3.25997 14.5833 3.33653 14.841 3.51583 15.0203C3.69512 15.1996 3.95286 15.2761 4.20096 15.2238L7.29355 14.5714C7.72031 14.4814 8.11172 14.2693 8.42013 13.9609L15.7541 6.62695C16.6327 5.74827 16.6327 4.32365 15.7541 3.44497L15.0911 2.78206ZM12.9698 3.84272C13.2627 3.54982 13.7376 3.54982 14.0305 3.84272L14.6934 4.50563C14.9863 4.79852 14.9863 5.2734 14.6934 5.56629L14.044 6.21573L12.3204 4.49215L12.9698 3.84272ZM11.2597 5.55281L5.6359 11.1766C5.53309 11.2794 5.46238 11.4099 5.43238 11.5522L5.01758 13.5185L6.98394 13.1037C7.1262 13.0737 7.25666 13.003 7.35947 12.9002L12.9833 7.27639L11.2597 5.55281Z"
                            fill=""></path>
                    </svg>
                    Edit
                </button> --}}
            </div>
        </div>

        <!-- Item Search Box-- -->
        <div class="relative mb-6" style="width: 700px;">
            @if ($status === 'pending')
                <label class="block font-medium text-gray-700">Search Item</label>
                <input type="text" wire:model.live.throttle.150ms="searchTerm" placeholder="Search items..."
                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />

                <!-- Search Results.-->
                @if (!empty($searchResults))
                    <div class="max-w-full overflow-x-auto border custom-scrollbar">
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
                                            Selling
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
                                    <tr wire:click="addOrderItem({{ $item['id'] }})"
                                        class="border-t border-gray-100 cursor-pointer dark:border-gray-800 hover:bg-gray-200">
                                        <td class="px-2 py-3.5">
                                            <p class="font-medium text-gray-500 text-theme-sm dark:text-white/90">
                                                {{ $item['item_name'] }}
                                            </p>
                                        </td>
                                        <td class="px-6 py-3.5">
                                            <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                {{ $item['item_code'] }}</p>
                                        </td>
                                        <td class="px-6 py-3.5">
                                            <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                {{ $item['selling_price'] }}</p>
                                        </td>
                                        <td class="px-6 py-3.5">
                                            <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                {{ $item['stock_balance'] }}
                                            </p>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            @elseif ($status === 'printing')
                <p>Order Status : <span
                        class="inline-flex items-center justify-center gap-1 rounded-full bg-warning-50 px-2.5 py-0.5 text-sm font-medium text-warning-600 dark:bg-warning-500/15 dark:text-orange-400">
                        awiting to dispath
                    </span></p>

            @endif
        </div>

        @if (session()->has('success'))
            <div x-data="{ open: true }" x-show="open" x-transition
                class="relative p-4 mb-5 border rounded-xl border-success-500 bg-success-50 dark:border-success-500/30 dark:bg-success-500/15">
                <div class="flex flex-row justify-end">
                    <!-- Close button positioned in the top-right corner -->
                    <button @click="open = false" class="absolute text-gray-400 top-2 right-2 hover:text-red-700">
                        <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L17.94 6M18 18L6.06 6" />
                        </svg>
                    </button>

                </div>

                <div class="flex items-start gap-3">
                    <div class="-mt-0.5 text-success-500">
                        <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M3.70186 12.0001C3.70186 7.41711 7.41711 3.70186 12.0001 3.70186C16.5831 3.70186 20.2984 7.41711 20.2984 12.0001C20.2984 16.5831 16.5831 20.2984 12.0001 20.2984C7.41711 20.2984 3.70186 16.5831 3.70186 12.0001ZM12.0001 1.90186C6.423 1.90186 1.90186 6.423 1.90186 12.0001C1.90186 17.5772 6.423 22.0984 12.0001 22.0984C17.5772 22.0984 22.0984 17.5772 22.0984 12.0001C22.0984 6.423 17.5772 1.90186 12.0001 1.90186ZM15.6197 10.7395C15.9712 10.388 15.9712 9.81819 15.6197 9.46672C15.2683 9.11525 14.6984 9.11525 14.347 9.46672L11.1894 12.6243L9.6533 11.0883C9.30183 10.7368 8.73198 10.7368 8.38051 11.0883C8.02904 11.4397 8.02904 12.0096 8.38051 12.3611L10.553 14.5335C10.7217 14.7023 10.9507 14.7971 11.1894 14.7971C11.428 14.7971 11.657 14.7023 11.8257 14.5335L15.6197 10.7395Z"
                                fill=""></path>
                        </svg>
                    </div>

                    <div>
                        <h4 class="mb-1 text-sm font-semibold text-gray-800 dark:text-white/90">
                            Success Message
                        </h4>

                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        @if (session()->has('error'))
            <div
                class="p-4 mb-2 border rounded-xl border-error-500 bg-error-50 dark:border-error-500/30 dark:bg-error-500/15">
                <div class="flex items-start gap-3">
                    <div class="-mt-0.5 text-error-500">
                        <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M20.3499 12.0004C20.3499 16.612 16.6115 20.3504 11.9999 20.3504C7.38832 20.3504 3.6499 16.612 3.6499 12.0004C3.6499 7.38881 7.38833 3.65039 11.9999 3.65039C16.6115 3.65039 20.3499 7.38881 20.3499 12.0004ZM11.9999 22.1504C17.6056 22.1504 22.1499 17.6061 22.1499 12.0004C22.1499 6.3947 17.6056 1.85039 11.9999 1.85039C6.39421 1.85039 1.8499 6.3947 1.8499 12.0004C1.8499 17.6061 6.39421 22.1504 11.9999 22.1504ZM13.0008 16.4753C13.0008 15.923 12.5531 15.4753 12.0008 15.4753L11.9998 15.4753C11.4475 15.4753 10.9998 15.923 10.9998 16.4753C10.9998 17.0276 11.4475 17.4753 11.9998 17.4753L12.0008 17.4753C12.5531 17.4753 13.0008 17.0276 13.0008 16.4753ZM11.9998 6.62898C12.414 6.62898 12.7498 6.96476 12.7498 7.37898L12.7498 13.0555C12.7498 13.4697 12.414 13.8055 11.9998 13.8055C11.5856 13.8055 11.2498 13.4697 11.2498 13.0555L11.2498 7.37898C11.2498 6.96476 11.5856 6.62898 11.9998 6.62898Z"
                                fill="#F04438" />
                        </svg>
                    </div>

                    <div>
                        <h4 class="mb-1 text-sm font-semibold text-gray-800 dark:text-white/90">
                            Error Message
                        </h4>

                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ session('error') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif





        <div class="max-w-full overflow-x-auto border custom-scrollbar">
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
                                Quantity
                            </p>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                Action
                            </p>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dispatchItems as $index => $orderItem)
                        @if ($dispatchStatus === 'dispatching')
                            <tr class="border-t border-gray-100 cursor-pointer dark:border-gray-800 hover:bg-gray-200">
                                <td class="px-3 py-1">
                                    <p class="font-medium text-gray-500 text-theme-sm dark:text-white/90">
                                        {{ $orderItem['item_name'] }}
                                    </p>
                                </td>

                                <td class="px-3 py-1">
                                    <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                        <input type="number"
                                            wire:model.live="dispatchItems.{{ $index }}.quantity"
                                            min="0"
                                            max="{{ $orderItem['dispatchBalance'] == 0 ? $orderItem['quantity'] : $orderItem['dispatchBalance'] }}"
                                            class="w-16 h-6 p-1 text-center border">
                                    </p>
                                </td>

                                <td class="px-3 py-1">
                                    <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                        <svg class="w-5 h-5 text-error-600 dark:text-white" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                                                d="m6 6 12 12m3-6a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                    </p>
                                </td>
                            </tr>
                        @else
                            <tr class="border-t border-gray-100 dark:border-gray-800">
                                <td class="px-3 py-1">
                                    <p class="font-medium text-gray-500 text-theme-sm dark:text-white/90">
                                        {{ $orderItem['name'] ?? $orderItem['item_name'] ?? 'N/A' }}
                                    </p>
                                </td>

                                <td class="px-3 py-1">
                                    <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                        {{ $orderItem['quantity'] }}
                                    </p>
                                </td>

                                <td class="px-3 py-1">
                                    <svg class="w-5 h-5 text-gray-100 cursor-pointer dark:text-white hover:text-gray-400"
                                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="18"
                                        height="18" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>

            </table>
        </div>

        <div class="pb-6 my-6 text-right border-b border-gray-100 dark:border-gray-800">
            {{-- <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                Sub Total amount: {{ number_format($total_amount, 2) }}
            </p>
            <p class="mb-3 text-sm text-gray-500 dark:text-gray-400">
                Vat (10%): {{ number_format($total_amount * 0.1, 2) }}
            </p> --}}

            {{-- <p class="text-lg font-semibold text-gray-800 dark:text-white/90">
                Total : {{ number_format($total_amount) }}
            </p> --}}
        </div>


        <div class="flex items-center justify-end gap-3">
            {{-- @if ($dispatchStatus === 'dispatching') --}}
                <button wire:click="updateDispatchItems({{ $jobOrder->id }})"
                    class="flex items-center justify-center gap-2 px-4 py-3 text-sm font-medium text-white rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600">
                    <svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                        viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h3a3 3 0 0 0 0-6h-.025a5.56 5.56 0 0 0 .025-.5A5.5 5.5 0 0 0 7.207 9.021C7.137 9.017 7.071 9 7 9a4 4 0 1 0 0 8h2.167M12 19v-9m0 0-2 2m2-2 2 2" />
                    </svg>
                    Save
                </button>
            {{-- @endif --}}

            @if ($dispatchStatus === 'complete')
                <button wire:click="dispatchPrintPreview()"
                    class="flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600">

                    <svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                        viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linejoin="round" stroke-width="2"
                            d="M16.444 18H19a1 1 0 0 0 1-1v-5a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1h2.556M17 11V5a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v6h10ZM7 15h10v4a1 1 0 0 1-1 1H8a1 1 0 0 1-1-1v-4Z" />
                    </svg>

                    View Dispach note
                </button>
            @endif
        </div>
    </div>
</div>
