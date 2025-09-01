<div class="mx-auto max-w-screen-xl p-4 grid grid-cols-1 sm:grid-cols-1 gap-4">
    <div class="bg-white p-6 rounded-lg shadow-md">

        <div class="p-5 mb-6 border border-gray-200 rounded-2xl dark:border-gray-800 lg:p-6">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90 lg:mb-6">
                        Order Information #{{ $customerOrder->order_number }}
                    </h4>

                    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2 lg:gap-7 2xl:gap-x-32">
                        <div>
                            <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">
                                Customer Name
                            </p>
                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                {{ $customerOrder->customer->name }}
                            </p>
                        </div>

                        <div>
                            <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">
                                Address
                            </p>
                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                {{ $customerOrder->customer->address }}
                            </p>
                        </div>

                        <div>
                            <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">
                                Email address
                            </p>
                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                {{ $customerOrder->customer->email }}
                            </p>
                        </div>

                        <div>
                            <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">
                                Phone
                            </p>
                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                {{ $customerOrder->customer->phone }}
                            </p>
                        </div>

                    </div>
                </div>

                <button @click="isProfileInfoModal = true"
                    class="flex w-full items-center justify-center gap-2 rounded-full border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200 lg:inline-flex lg:w-auto">
                    <svg class="fill-current" width="18" height="18" viewBox="0 0 18 18" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M15.0911 2.78206C14.2125 1.90338 12.7878 1.90338 11.9092 2.78206L4.57524 10.116C4.26682 10.4244 4.0547 10.8158 3.96468 11.2426L3.31231 14.3352C3.25997 14.5833 3.33653 14.841 3.51583 15.0203C3.69512 15.1996 3.95286 15.2761 4.20096 15.2238L7.29355 14.5714C7.72031 14.4814 8.11172 14.2693 8.42013 13.9609L15.7541 6.62695C16.6327 5.74827 16.6327 4.32365 15.7541 3.44497L15.0911 2.78206ZM12.9698 3.84272C13.2627 3.54982 13.7376 3.54982 14.0305 3.84272L14.6934 4.50563C14.9863 4.79852 14.9863 5.2734 14.6934 5.56629L14.044 6.21573L12.3204 4.49215L12.9698 3.84272ZM11.2597 5.55281L5.6359 11.1766C5.53309 11.2794 5.46238 11.4099 5.43238 11.5522L5.01758 13.5185L6.98394 13.1037C7.1262 13.0737 7.25666 13.003 7.35947 12.9002L12.9833 7.27639L11.2597 5.55281Z"
                            fill=""></path>
                    </svg>
                    Edit
                </button>
            </div>
        </div>

        <!-- Item Search Box -->
        <div class="relative mb-6" style="width: 700px;">
            @if ($status === 'plan')
                <label class="block text-gray-700 font-medium">Search Item</label>
                <input type="text" wire:model.live.throttle.150ms="searchTerm" placeholder="Search items..."
                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />

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
                                        class="border-t border-gray-100 dark:border-gray-800 cursor-pointer hover:bg-gray-200">
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
            @elseif ($status === 'released')
                <p>Order Status : Released</p>
            @endif
        </div>

        @if (session()->has('success'))
            <div x-data="{ open: true }" x-show="open" x-transition
                class="relative rounded-xl border border-success-500 bg-success-50 p-4 dark:border-success-500/30 dark:bg-success-500/15 mb-5">
                <div class="flex flex-row justify-end">
                    <!-- Close button positioned in the top-right corner -->
                    <button @click="open = false" class="absolute top-2 right-2 text-gray-400 hover:text-red-700">
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
                                Price (Rs)
                            </p>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                Quantity
                            </p>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                Total (Rs)
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
                    @foreach ($orderItems as $index => $orderItem)
                        <tr class="border-t border-gray-100 dark:border-gray-800 cursor-pointer hover:bg-gray-200">
                            <td class="px-2 py-3">
                                <p class="font-medium text-gray-500 text-theme-sm dark:text-white/90">
                                    {{ $orderItem['name'] }}
                                </p>
                            </td>
                            <td class="px-6 py-3">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                    {{ number_format($orderItem['price'], 2) }}
                            </td>
                            <td class="px-6 py-3">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                    <input type="number" wire:model.live="orderItems.{{ $index }}.quantity"
                                        wire:change="updateTotal({{ $index }})" min="0"
                                        max="{{ $orderItem['stock_balance'] }}" class="w-16 border p-1 text-center"
                                        style="{{ $orderItem['stock_balance'] == 0 ? 'color: red;' : '' }}">
                            </td>
                            <td class="px-6 py-3">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                    {{ number_format($orderItem['total'], 2) }}
                                </p>
                            </td>

                            <td class="px-6 py-3">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                    @if ($status === 'plan')
                                        <svg wire:click="removeItem({{ $orderItem['id'] }})"
                                            class="w-5 h-5 text-gray-800 dark:text-white hover:text-gray-400 cursor-pointer"
                                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="18"
                                            height="18" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2"
                                                d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-gray-400 dark:text-white hover:text-gray-400 cursor-pointer"
                                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="18"
                                            height="18" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-width="2"
                                                d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                    @endif
                                </p>
                            </td>

                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>




        <!-- Order Item List -->
        {{-- <div class="bg-gray-100 p-4 rounded-lg">
            <h3 class="text-lg font-semibold mb-3">Order Items</h3>
            <table class="w-full border">
                <thead>
                    <tr class="bg-gray-200 text-left">
                        <th class="p-2 border">Item</th>
                        <th class="p-2 border">Price (Rs)</th>
                        <th class="p-2 border">Quantity</th>
                        <th class="p-2 border">Total (Rs)</th>
                        <th class="p-2 border">Remove</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orderItems as $index => $orderItem)
                        <tr>
                            <td class="p-2 border">{{ $orderItem['name'] }}</td>
                            <td class="p-2 border">{{ number_format($orderItem['price'], 2) }}</td>
                            <td class="p-2 border">
                                <input type="number" wire:model.live="orderItems.{{ $index }}.quantity"
                                    wire:change="updateTotal({{ $index }})" min="0"
                                    max="{{ $orderItem['stock_balance'] }}" class="w-16 border p-1 text-center"
                                    style="{{ $orderItem['stock_balance'] == 0 ? 'color: red;' : '' }}">

                            </td>
                            <td class="p-2 border">{{ number_format($orderItem['total'], 2) }}</td>
                            <td class="p-2 border flex flex-row justify-center">
                                @if ($status === 'plan')
                                    <svg style="color: red; cursor: pointer;"
                                        wire:click="removeItem({{ $orderItem['id'] }})"
                                        class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                        viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z" />
                                    </svg>
                                @else
                                    <svg class="w-6 h-6 text-gray-800 dark:text-white cursor-pointer" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                        viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                                            d="m6 6 12 12m3-6a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                @endif

                            </td>
                        </tr>
                    @endforeach
                    @if (session('error'))
                        <tr>
                            <td class="p-12">
                                <div style="color: red"
                                    class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md">
                                    {{ session('error') }}
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div> --}}


        <div class="pb-6 my-6 text-right border-b border-gray-100 dark:border-gray-800">
            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                Sub Total amount: {{ number_format($total_amount, 2) }}
            </p>
            <p class="mb-3 text-sm text-gray-500 dark:text-gray-400">
                Vat (10%): {{ number_format($total_amount * 0.1, 2) }}
            </p>

            <p class="text-lg font-semibold text-gray-800 dark:text-white/90">
                Total : {{ number_format($total_amount + $total_amount * 0.1, 2) }}
            </p>
        </div>


        <div class="flex items-center justify-end gap-3">
            @if ($status === 'plan')
                <button wire:click="updateOrderItems"
                    class="flex items-center justify-center gap-2 px-4 py-3 text-sm font-medium text-white rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600">
                    <svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                    viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h3a3 3 0 0 0 0-6h-.025a5.56 5.56 0 0 0 .025-.5A5.5 5.5 0 0 0 7.207 9.021C7.137 9.017 7.071 9 7 9a4 4 0 1 0 0 8h2.167M12 19v-9m0 0-2 2m2-2 2 2" />
                </svg>
                    Apply Changes
                </button>
                <button wire:click="releseOrder({{ $orderId }})"
                    class="flex items-center justify-center gap-2 px-4 py-3 text-sm font-medium text-white rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600">
                    <svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m10.051 8.102-3.778.322-1.994 1.994a.94.94 0 0 0 .533 1.6l2.698.316m8.39 1.617-.322 3.78-1.994 1.994a.94.94 0 0 1-1.595-.533l-.4-2.652m8.166-11.174a1.366 1.366 0 0 0-1.12-1.12c-1.616-.279-4.906-.623-6.38.853-1.671 1.672-5.211 8.015-6.31 10.023a.932.932 0 0 0 .162 1.111l.828.835.833.832a.932.932 0 0 0 1.111.163c2.008-1.102 8.35-4.642 10.021-6.312 1.475-1.478 1.133-4.77.855-6.385Zm-2.961 3.722a1.88 1.88 0 1 1-3.76 0 1.88 1.88 0 0 1 3.76 0Z"/>
                      </svg>

                    Release Order
                </button>
            @elseif ($status === 'released')
                <button wire:click="generateInvoiceFromOrder({{ $orderId }})"
                    class="flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">
                    Convert to Invoice
                </button>

                <button
                    class="flex items-center justify-center gap-2 px-4 py-3 text-sm font-medium text-white rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600">
                    <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M6.99578 4.08398C6.58156 4.08398 6.24578 4.41977 6.24578 4.83398V6.36733H13.7542V5.62451C13.7542 5.42154 13.672 5.22724 13.5262 5.08598L12.7107 4.29545C12.5707 4.15983 12.3835 4.08398 12.1887 4.08398H6.99578ZM15.2542 6.36902V5.62451C15.2542 5.01561 15.0074 4.43271 14.5702 4.00891L13.7547 3.21839C13.3349 2.81151 12.7733 2.58398 12.1887 2.58398H6.99578C5.75314 2.58398 4.74578 3.59134 4.74578 4.83398V6.36902C3.54391 6.41522 2.58374 7.40415 2.58374 8.61733V11.3827C2.58374 12.5959 3.54382 13.5848 4.74561 13.631V15.1665C4.74561 16.4091 5.75297 17.4165 6.99561 17.4165H13.0041C14.2467 17.4165 15.2541 16.4091 15.2541 15.1665V13.6311C16.456 13.585 17.4163 12.596 17.4163 11.3827V8.61733C17.4163 7.40414 16.4561 6.41521 15.2542 6.36902ZM4.74561 11.6217V12.1276C4.37292 12.084 4.08374 11.7671 4.08374 11.3827V8.61733C4.08374 8.20312 4.41953 7.86733 4.83374 7.86733H15.1663C15.5805 7.86733 15.9163 8.20312 15.9163 8.61733V11.3827C15.9163 11.7673 15.6269 12.0842 15.2541 12.1277V11.6217C15.2541 11.2075 14.9183 10.8717 14.5041 10.8717H5.49561C5.08139 10.8717 4.74561 11.2075 4.74561 11.6217ZM6.24561 12.3717V15.1665C6.24561 15.5807 6.58139 15.9165 6.99561 15.9165H13.0041C13.4183 15.9165 13.7541 15.5807 13.7541 15.1665V12.3717H6.24561Z"
                            fill="" />
                    </svg>
                    Print
                </button>
            @endif
        </div>


        <!-- Save Button -->
        {{-- @if ($status === 'plan')
            <div class="mt-6">
                <button wire:click="updateOrderItems"
                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
                    style="background-color:#465fff">
                    {{ $orderId ? 'Update Order' : 'Save Order' }}
                </button>

                <button wire:click="releseOrder({{ $orderId }})"
                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
                    style="background-color:#465fff">
                    Release Order
                </button>
            </div>
        @elseif ($status === 'released')
            <div class="mt-6">
                <button wire:click="generateInvoiceFromOrder({{ $orderId }})"
                    class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600"
                    style="background-color:#28a745">
                    Generate Invoice
                </button>
            </div>
        @endif --}}
      
    </div>



</div>
