<div class="mx-auto max-w-screen-xl p-4 grid grid-cols-1 sm:grid-cols-1 gap-4">
    <div class="bg-white p-6 rounded-lg shadow-md">

        <div class="p-5 mb-6 border border-gray-200 rounded-2xl dark:border-gray-800 lg:p-6">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90 lg:mb-6">
                        GRN Information #{{ $grn->grn_code }}
                    </h4>

                    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2 lg:gap-7 2xl:gap-x-32">
                        <div>
                            <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">
                                Supplier Name
                            </p>
                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                {{ $grn->supplier->name }}
                            </p>
                        </div>

                        <div>
                            <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">
                                Address
                            </p>
                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                {{ $grn->supplier->address }}
                            </p>
                        </div>

                        <div>
                            <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">
                                Email Address
                            </p>
                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                {{ $grn->supplier->email }}
                            </p>
                        </div>
                        <div class="flex gap-5">
                            <div>
                                <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">
                                    Phone
                                </p>
                                <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                    {{ $grn->supplier->phone }}
                                </p>
                            </div>
                            <div>
                                <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">
                                    PO Number
                                </p>
                                <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                    {{ $grn->delivery_remark }}
                                </p>
                            </div>
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

        <!-- Search Item -->
        <div class="mt-4 mb-10">
            @if ($status != 'completed')
                <label class="text-gray-800 dark:text-white/90">Search Item</label>
                <input type="text" wire:model.live.throttle.150ms="searchTerm" placeholder="Search items..."
                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
            @endif
            @if (!empty($searchResults))
                <div class="max-w-full overflow-x-auto custom-scrollbar border mt-2">
                    <table class="w-full">
                        <thead>
                            <tr class="border-t border-gray-100 dark:border-gray-800">
                                <th class="px-3 py-1 text-left">
                                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                        Name
                                    </p>
                                </th>
                                <th class="px-3 py-1 text-left">
                                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                        Code
                                    </p>
                                </th>
                                <th class="px-3 py-1 text-left">
                                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                        Price
                                    </p>
                                </th>
                                {{-- <th class="px-3 py-1 text-left">
                                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                        Qty
                                    </p>
                                </th>
                                <th class="px-3 py-1 text-left">
                                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                        Action
                                    </p>
                                </th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($searchResults as $item)
                                <tr wire:click="addItem({{ $item['id'] }})"
                                    class="border-t border-gray-100 dark:border-gray-800 cursor-pointer hover:bg-gray-200 mb-5">

                                    <td class="px-2 py-1 text-left">
                                        <p class="font-medium text-gray-500 text-theme-sm dark:text-white/90">
                                            {{ $item['item_name'] }}
                                        </p>
                                    </td>
                                    <td class="px-3 py-1  text-left">
                                        <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                            {{ $item['item_code'] }}</p>
                                    </td>
                                    <td class="px-3 py-1">
                                        <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                            {{ $item['purchase_price'] }}</p>
                                    </td>
                                    {{-- <td class="px-3 py-1">
                                        <input type="number" wire:model="updatedQuantity.{{ $item['id'] }}"
                                            min="1" class="w-16 border p-1 text-center" value="1">
                                    </td> --}}
                                    {{-- <td class="px-3 py-1">
                                        @php
                                            // Check if the item already exists in the grnItems list
                                            $existingItem = collect($grnItems)->firstWhere('item_id', $item['id']);
                                        @endphp

                                        @if ($item['same_item'])
                                            <!-- Show Update button if item exists -->
                                            <button style="background-color:#465FFF;color: #fff; border-radius: 4px;"
                                                class="bg-green-500 text-white px-2 py-1 rounded"
                                                wire:click="updateItemQty({{ $item['id'] }})">
                                                Update
                                            </button>
                                        @else
                                            <!-- Show Add button if item does not exist -->
                                            <button style="background-color:#465FFF;color: #fff; border-radius: 4px;"
                                                class="bg-green-500 text-white px-2 py-1 rounded"
                                                wire:click="addItem({{ $item['id'] }})">
                                                Add
                                            </button>
                                        @endif
                                    </td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>


        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90 lg:mb-6 mb-3 mt-2">GRN Items</h3>

        @if (session()->has('success'))
            <div x-data="{ open: true }" x-show="open" x-transition
                class="relative rounded-xl border border-success-500 bg-success-50 p-4 dark:border-success-500/30 dark:bg-success-500/15">
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



        <!-- GRN Items Table -->
        <div class="custom-scrollbar max-w-full overflow-x-auto mt-5">

            <table class="min-w-full">
                <thead class="border-y border-gray-100 py-2 dark:border-gray-800">
                    <tr class="bg-gray-200">
                        <th class="py-2 font-normal whitespace-nowrap px-2">
                            <div class="flex items-center">
                                <p class="text-theme-xs text-gray-500 dark:text-gray-400">Item</p>
                            </div>
                        </th>

                        <th class="py-2 font-normal whitespace-nowrap">
                            <div class="flex items-center">
                                <p class="text-theme-xs text-gray-500 dark:text-gray-400">SKU Code</p>
                            </div>
                        </th>

                        <th class="py-2 font-normal whitespace-nowrap">
                            <div class="flex items-center">
                                <p class="text-theme-xs text-gray-500 dark:text-gray-400">Sales Price</p>
                            </div>
                        </th>
                        <th class="py-2 font-normal whitespace-nowrap">
                            <div class="flex items-center">
                                <p class="text-theme-xs text-gray-500 dark:text-gray-400">Purchase Price</p>
                            </div>
                        </th>
                        <th class="py-2 font-normal whitespace-nowrap">
                            <div class="flex items-center">
                                <p class="text-theme-xs text-gray-500 dark:text-gray-400">Quantity</p>
                            </div>
                        </th>

                        <th class="py-2 font-normal whitespace-nowrap">
                            <div class="flex items-center">
                                <p class="text-theme-xs text-gray-500 dark:text-gray-400">Total</p>
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
                    @foreach ($grnItems as $index => $grnItem)
                        <tr class="cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800">
                            <td class="py-1 whitespace-nowrap">
                                <div class="flex items-center">
                                    <p class="text-theme-xs text-gray-700 dark:text-gray-400 px-2">
                                        {{ $grnItem['item_name'] }}
                                    </p>
                                </div>
                            </td>
                            <td class="py-1 whitespace-nowrap">
                                <div class="flex items-center">
                                    <p class="text-theme-xs text-gray-700 dark:text-gray-400">
                                        {{ $grnItem['sku_code'] }}
                                    </p>
                                </div>
                            </td>
                            <td class="py-1 whitespace-nowrap">
                                <div class="flex items-center">
                                    <p class="text-theme-xs text-gray-700 dark:text-gray-400">
                                        {{ number_format($grnItem['selling_price'], 2) }}
                                    </p>
                                </div>
                            </td>
                            <td class="py-1 whitespace-nowrap">
                                <div class="flex items-center">
                                    <p class="text-theme-xs text-gray-700 dark:text-gray-400">
                                        {{ number_format($grnItem['purchase_price'], 2) }}
                                    </p>
                                </div>
                            </td>
                            <td class="py-1 whitespace-nowrap">
                                <div class="flex items-center">
                                    <p class="text-theme-xs text-gray-700 dark:text-gray-400">
                                        <input type="number" wire:model="grnItems.{{ $index }}.quantity"
                                            wire:change="updateTotal({{ $index }})" min="1"
                                            class="w-16 border p-1 text-center">
                                    </p>
                                </div>
                            </td>
                            <td class="py-1 whitespace-nowrap">
                                <div class="flex items-center">
                                    <p class="text-theme-xs text-gray-700 dark:text-gray-400">
                                        {{ number_format($grnItem['total'], 2) }}
                                    </p>
                                </div>
                            </td>
                            <td class="py-1 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center">
                                    <p
                                        class="text-theme-xs text-gray-300 dark:text-gray-400 text-center hover:text-gray-500">
                                        @if ($status != 'completed')
                                            <button wire:click="removeItem({{ $index }})"
                                                class="text-red-500">
                                                <svg class="w-4 h-4 text-gray-800 dark:text-white hover:text-gray-400"
                                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                    width="18" height="18" fill="none"
                                                    viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2"
                                                        d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                </svg>
                                            </button>
                                        @else
                                            <button class="text-red-500">
                                                <svg class="w-4 h-4 text-gray-300 dark:text-white hover:text-gray-400"
                                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                    width="18" height="18" fill="none"
                                                    viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2"
                                                        d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
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

        <div class="pb-6 my-6 text-right border-b border-gray-100 dark:border-gray-800">
            {{-- <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                Sub Total amount: {{ number_format($total_amount, 2) }}
            </p> --}}
            {{-- <p class="mb-3 text-sm text-gray-500 dark:text-gray-400">
                Vat (10%): {{ number_format($total_amount * 0.1, 2) }}
            </p> --}}

            <p class="text-lg font-semibold text-gray-800 dark:text-white/90">
                Total : {{ number_format($total_amount, 2) }}
            </p>
        </div>

        <div class="flex items-center justify-end gap-3  dark:border-gray-800 mt-5 mb-10 pt-5">
            @if ($status != 'completed')
                {{-- <livewire:grn.grn-import /> --}}
                {{-- <livewire:grn.grn-export /> --}}

                <button id="back-button" onclick="window.history.back();" style="display: flex; align-items: center; font-family: outfit; gap: 0.5rem; padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; color: white; border-radius: 0.375rem; background-color: #6b7280; box-shadow: 0 2px 2px rgba(0, 0, 0, 0.1);">
                    <svg style="width: 1.5rem; height: 1.5rem; color: white;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back
                </button>
                <button wire:click="saveGrnItems" wire:key="saveButton-{{ $status }}"
                    class="flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600">

                    <svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                        viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h3a3 3 0 0 0 0-6h-.025a5.56 5.56 0 0 0 .025-.5A5.5 5.5 0 0 0 7.207 9.021C7.137 9.017 7.071 9 7 9a4 4 0 1 0 0 8h2.167M12 19v-9m0 0-2 2m2-2 2 2" />
                    </svg>
                    Apply Changes
                </button>

                @if (is_array($grnItems) && count($grnItems) > 0)
                    <button wire:click="processGrnItems" wire:key="processButton-{{ $status }}"
                        class="flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600">
                        <svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2"
                                d="M21 13v-2a1 1 0 0 0-1-1h-.757l-.707-1.707.535-.536a1 1 0 0 0 0-1.414l-1.414-1.414a1 1 0 0 0-1.414 0l-.536.535L14 4.757V4a1 1 0 0 0-1-1h-2a1 1 0 0 0-1 1v.757l-1.707.707-.536-.535a1 1 0 0 0-1.414 0L4.929 6.343a1 1 0 0 0 0 1.414l.536.536L4.757 10H4a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h.757l.707 1.707-.535.536a1 1 0 0 0 0 1.414l1.414 1.414a1 1 0 0 0 1.414 0l.536-.535 1.707.707V20a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-.757l1.707-.708.536.536a1 1 0 0 0 1.414 0l1.414-1.414a1 1 0 0 0 0-1.414l-.535-.536.707-1.707H20a1 1 0 0 0 1-1Z" />
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                        </svg>
                        Process GRN
                    </button>
                @endif
            @else

                <button
                    class="flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white rounded-lg bg-gray-500 shadow-theme-xs hover:bg-gray-200">

                    <svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                        viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h3a3 3 0 0 0 0-6h-.025a5.56 5.56 0 0 0 .025-.5A5.5 5.5 0 0 0 7.207 9.021C7.137 9.017 7.071 9 7 9a4 4 0 1 0 0 8h2.167M12 19v-9m0 0-2 2m2-2 2 2" />
                    </svg>
                    Apply Changes
                </button>
                <button
                    class="flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white rounded-lg bg-gray-500 shadow-theme-xs hover:bg-gray-200">
                    <svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                        viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 13v-2a1 1 0 0 0-1-1h-.757l-.707-1.707.535-.536a1 1 0 0 0 0-1.414l-1.414-1.414a1 1 0 0 0-1.414 0l-.536.535L14 4.757V4a1 1 0 0 0-1-1h-2a1 1 0 0 0-1 1v.757l-1.707.707-.536-.535a1 1 0 0 0-1.414 0L4.929 6.343a1 1 0 0 0 0 1.414l.536.536L4.757 10H4a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h.757l.707 1.707-.535.536a1 1 0 0 0 0 1.414l1.414 1.414a1 1 0 0 0 1.414 0l.536-.535 1.707.707V20a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-.757l1.707-.708.536.536a1 1 0 0 0 1.414 0l1.414-1.414a1 1 0 0 0 0-1.414l-.535-.536.707-1.707H20a1 1 0 0 0 1-1Z" />
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                    </svg>
                    Process GRN
                </button>
            @endif

        </div>

        {{-- <div class="flex flex-wrap items-center gap-6 mb-4 justify-start" style="background-color: #FFF">
            <!-- Save GRN Items Button -->
            <div class="">
                <button style="background-color:{{ $status !== 'pending' ? '#D3D3D3' : '#465FFF' }};"
                    wire:click="saveGrnItems"
                    class="flex items-center space-x-2 bg-[#465FFF] hover:bg-[#3b4ddb] text-white font-medium px-6 py-2 rounded-md text-sm shadow-md transition duration-300 ease-in-out transform hover:scale-105"
                    {{ $status === 'completed' ? 'disabled' : '' }} wire:key="saveButton-{{ $status }}">
                    Save Items
                </button>
            </div>
            <div class="">
                <button style="background-color: {{ $status !== 'pending' ? '#D3D3D3' : '#465FFF' }};"
                    wire:click="processGrnItems" wire:key="processButton-{{ $status }}"
                    class="flex items-center space-x-2 bg-[#465FFF] hover:bg-[#3b4ddb] text-white font-medium px-6 py-2 rounded-md text-sm shadow-md transition duration-300 ease-in-out transform hover:scale-105 {{ $status !== 'pending' ? 'cursor-not-allowed opacity-50' : '' }}"
                    {{ $status !== 'pending' ? 'disabled' : '' }}>
                    Process GRN
                </button>

            </div>


            <div class="">
                <!-- Import GRNs -->
                <livewire:grn.grn-import />
            </div>
            <div class="">
                <!-- Export GRNs -->
                <livewire:grn.grn-export />
            </div>
        </div> --}}
    </div>

    {{-- <script>
    Livewire.on('statusUpdated', () => {
      alert();
    });
</script> --}}
