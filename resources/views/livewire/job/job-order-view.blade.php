<div class="mx-auto max-w-screen-xl p-4 grid grid-cols-1 sm:grid-cols-1 gap-3">
    <div class="bg-white p-6 rounded-lg shadow-md">

        <div class="p-5 mb-6 border border-gray-200 rounded-2xl dark:border-gray-800 lg:p-6">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90 lg:mb-1">
                        Job Order #<span
                            class="inline-flex items-center justify-center gap-1 rounded-full bg-brand-50 px-2.5 py-0.5 text-sm font-medium text-brand-500 dark:bg-brand-500/15 dark:text-brand-400">
                            {{ $jobOrder->job_number }}
                        </span>



                        @if ($status === 'invoiced' && ($role === 'design' || $role === 'admin' || $role === 'manager'))
    <button wire:click="duplicateOrder({{ $jobOrder->id }})"
        class="flex w-full items-center justify-center rounded-full border border-gray-300 bg-white px-2 py-1 text-xs font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200 lg:inline-flex lg:w-auto">
        <svg class="w-4 h-4 text-gray-800 dark:text-white" aria-hidden="true"
            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
            viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linejoin="round" stroke-width="2"
                d="M14 4v3a1 1 0 0 1-1 1h-3m4 10v1a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1h2m11-3v10a1 1 0 0 1-1 1h-7a1 1 0 0 1-1-1V7.87a1 1 0 0 1 .24-.65l2.46-2.87a1 1 0 0 1 .76-.35H18a1 1 0 0 1 1 1Z" />
        </svg>
        Re-Order
    </button>
@endif
                    </h4>
                    <p class="mb-1 text-sm leading-normal text-gray-500 dark:text-gray-400">
                        Status:
                        <span
                            class="inline-flex items-center justify-center gap-1 rounded-full bg-warning-50 px-2.5 py-0.5 text-sm font-medium text-warning-600 dark:bg-warning-500/15 dark:text-orange-400">
                            {{ $statusText }}
                        </span>

                    </p>
                    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2 lg:gap-15">
                        <div class="grid grid-cols-1 gap-1 lg:grid-cols-2 lg:gap-2 2xl:gap-x-30">
                            <div>
                                <p class="mb-1 text-xs leading-normal text-gray-500 dark:text-gray-400">
                                    Customer Name
                                </p>
                                <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                    {{ $jobOrder->customer->name }}
                                </p>
                            </div>

                            <div>
                                <p class="mb-1 text-xs leading-normal text-gray-500 dark:text-gray-400">
                                    Address
                                </p>
                                <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                    {{ $jobOrder->customer->address }}
                                </p>
                            </div>

                            <div>
                                <p class="mb-1 text-xs leading-normal text-gray-500 dark:text-gray-400">
                                    Email address
                                </p>
                                <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                    {{ $jobOrder->customer->email }}
                                </p>
                            </div>

                            <div>
                                <p class="mb-1 text-xs leading-normal text-gray-500 dark:text-gray-400">
                                    Phone
                                </p>
                                <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                    {{ $jobOrder->customer->phone }}
                                </p>
                            </div>

                            @if (($status === 'pending' || $status === 'designing') && ($role === 'design' || $role ===
                            'admin'))
                            <div class="pr-10">
                                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Plate Baking
                                </label>
                                <select wire:model="plate_backing"
                                    wire:change="updateField('plate_backing', $event.target.value)"
                                    wire:confirm="Are you sure you want to change this value?"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 pl-4">
                                    <option value="">-- Select YES/NO--</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                            @else
                            <div class="pr-10">
                                @if ($plate_backing)
                                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Plate Baking
                                </label>

                                <span
                                    class="inline-flex items-center justify-center gap-1 rounded-full bg-warning-500 px-2.5 py-0.5 text-sm font-medium text-white">
                                    {{ $backing_qty }}
                                </span>
                                @endif


                            </div>
                            @endif

                            @if (($status === 'pending' || $status === 'designing') && ($role === 'design' || $role ===
                            'admin'))
                            <div class="pr-10">
                                @if ($plate_backing == 1)
                                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Baking Quantity
                                </label>
                                <input type="number" reguired wire:model="backing_qty" min="1"
                                    wire:change="updateField('backing_qty', $event.target.value)"
                                    wire:confirm="Are you sure you want to change this value?"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 pl-4">
                                @endif
                            </div>
                            @endif
                        </div>
                        <div class="grid grid-cols-1 gap-1 lg:grid-cols-2 lg:gap-2 2xl:gap-x-30">
                            @if (($status === 'pending' || $status === 'designing' || $status === 'printing' || $status
                            === 'dispatching') && ($role === 'design' || $role ===
                            'admin'))
                            <div>
                                <p class="mb-1 text-xs leading-normal text-gray-500 dark:text-gray-400">
                                    Description
                                </p>
                                <textarea wire:model="description" placeholder="Enter job description"
                                    wire:change="updateField('description', $event.target.value)"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-20 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">{{ $description }}</textarea>

                            </div>
                            {{-- <div>
                                <p class="mb-1 text-xs leading-normal text-gray-500 dark:text-gray-400">
                                    Special Instructions
                                </p>
                                <textarea wire:model="special_instruction" placeholder="Enter job special instruction"
                                    wire:change="updateField('special_instruction', $event.target.value)"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-20 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">{{ $special_instruction }}</textarea>

                            </div> --}}

                            @else
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Description
                                </label>
                                <div class="rounded-lg rounded-tl-sm bg-gray-100 px-3 py-2 dark:bg-white/5">
                                    <p class="text-sm text-gray-800 dark:text-white/90">
                                        {{ $description }}
                                    </p>
                                </div>

                            </div>
                            @endif

                            @if (($status === 'pending' || $status === 'designing') && ($role === 'design' || $role ===
                            'admin'))

                            <div>
                                <p class="mb-1 text-xs leading-normal text-gray-500 dark:text-gray-400">
                                    PO Number
                                </p>
                                <input type="text" reguired wire:model.chnage="customer_po_number" min="1"
                                    wire:change="updateField('customer_po_number', $event.target.value)"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 pl-4">

                            </div>

                            @else

                            @endif
                        </div>
                    </div>

                </div>

                {{-- reoder button --}}
            </div>

            <div class="flex flex-col lg:w-full">
                <label class="mb-1 block text-sm font-medium text-error-600 dark:text-error-400">Special
                    Instruction</label>
                <textarea wire:model="special_instruction" placeholder="Enter job special instruction"
                    wire:change="updateField('special_instruction', $event.target.value)"
                    class=" rounded-lg rounded-tl-sm border text-sm border-gray-300 px-3 py-2 dark:bg-white/5" style="width:20%">
        {{ $special_instruction }}
    </textarea>
            </div>




        </div>

        <!-- Item Search Box -->
        @if (($status === 'pending' || $status === 'designing') && ($role === 'design' || $role === 'admin'))
        <div class="relative mb-6" style="width: 700px;">
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
                            {{-- <th class="px-6 py-3 text-left">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                    Selling
                                </p>
                            </th> --}}
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
                            {{-- <td class="px-6 py-3.5">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                    {{ $item['selling_price'] }}</p>
                            </td> --}}
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
            @if ($status === 'printing')
            <p>Order Status : <span
                    class="inline-flex items-center justify-center gap-1 rounded-full bg-warning-50 px-2.5 py-0.5 text-sm font-medium text-warning-600 dark:bg-warning-500/15 dark:text-orange-400">
                    CTP
                </span></p>
            @endif
        </div>
        @endif




        @if (session()->has('success'))
        <div x-data="{ open: true }" x-show="open" x-transition
            class="relative rounded-xl border border-success-500 bg-success-50 p-4 dark:border-success-500/30 dark:bg-success-500/15 mb-5">
            <div class="flex flex-row justify-end">
                <!-- Close button positioned in the top-right corner -->
                <button @click="open = false" class="absolute top-2 right-2 text-gray-400 hover:text-red-700">
                    <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        fill="none" viewBox="0 0 24 24">
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
            class="rounded-xl border border-error-500 bg-error-50 p-4 dark:border-error-500/30 dark:bg-error-500/15 mb-2">
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
                        @if ($stockProcess === false)
                        One or more items stock balance is 0
                        @else
                        {{ session('error') }}
                        @endif
                    </p>
                </div>
            </div>
        </div>
        @endif

        @if (session()->has('warning'))
        <div x-data="{ open: true }" x-show="open" x-transition
            class="relative rounded-xl border border-warning-500 bg-warning-50 p-4 dark:border-warning-500/30 dark:bg-warning-500/15 mb-5">
            <div class="flex flex-row justify-end">
                <!-- Close button positioned in the top-right corner -->
                <button @click="open = false" class="absolute top-2 right-2 text-gray-400 hover:text-red-700">
                    <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L17.94 6M18 18L6.06 6" />
                    </svg>
                </button>
            </div>

            <div class="flex items-start gap-3">
                <div class="-mt-0.5 text-warning-500 dark:text-orange-400">
                    <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M3.6501 12.0001C3.6501 7.38852 7.38852 3.6501 12.0001 3.6501C16.6117 3.6501 20.3501 7.38852 20.3501 12.0001C20.3501 16.6117 16.6117 20.3501 12.0001 20.3501C7.38852 20.3501 3.6501 16.6117 3.6501 12.0001ZM12.0001 1.8501C6.39441 1.8501 1.8501 6.39441 1.8501 12.0001C1.8501 17.6058 6.39441 22.1501 12.0001 22.1501C17.6058 22.1501 22.1501 17.6058 22.1501 12.0001C22.1501 6.39441 17.6058 1.8501 12.0001 1.8501ZM10.9992 7.52517C10.9992 8.07746 11.4469 8.52517 11.9992 8.52517H12.0002C12.5525 8.52517 13.0002 8.07746 13.0002 7.52517C13.0002 6.97289 12.5525 6.52517 12.0002 6.52517H11.9992C11.4469 6.52517 10.9992 6.97289 10.9992 7.52517ZM12.0002 17.3715C11.586 17.3715 11.2502 17.0357 11.2502 16.6215V10.945C11.2502 10.5308 11.586 10.195 12.0002 10.195C12.4144 10.195 12.7502 10.5308 12.7502 10.945V16.6215C12.7502 17.0357 12.4144 17.3715 12.0002 17.3715Z"
                            fill="" />
                    </svg>
                </div>

                <div>
                    <h4 class="mb-1 text-sm font-semibold text-gray-800 dark:text-white/90">
                        Warning Message
                    </h4>

                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ session('warning') }}
                    </p>
                </div>
            </div>
        </div>
        @endif


        @if ($jobOrder->assign_to === null)
        <div
            class="rounded-xl border border-warning-500 bg-warning-50 p-4 dark:border-warning-500/30 dark:bg-warning-500/15 mb-4">
            <div class="flex items-start gap-3">
                <div class="-mt-0.5 text-warning-500 dark:text-orange-400">
                    <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M3.6501 12.0001C3.6501 7.38852 7.38852 3.6501 12.0001 3.6501C16.6117 3.6501 20.3501 7.38852 20.3501 12.0001C20.3501 16.6117 16.6117 20.3501 12.0001 20.3501C7.38852 20.3501 3.6501 16.6117 3.6501 12.0001ZM12.0001 1.8501C6.39441 1.8501 1.8501 6.39441 1.8501 12.0001C1.8501 17.6058 6.39441 22.1501 12.0001 22.1501C17.6058 22.1501 22.1501 17.6058 22.1501 12.0001C22.1501 6.39441 17.6058 1.8501 12.0001 1.8501ZM10.9992 7.52517C10.9992 8.07746 11.4469 8.52517 11.9992 8.52517H12.0002C12.5525 8.52517 13.0002 8.07746 13.0002 7.52517C13.0002 6.97289 12.5525 6.52517 12.0002 6.52517H11.9992C11.4469 6.52517 10.9992 6.97289 10.9992 7.52517ZM12.0002 17.3715C11.586 17.3715 11.2502 17.0357 11.2502 16.6215V10.945C11.2502 10.5308 11.586 10.195 12.0002 10.195C12.4144 10.195 12.7502 10.5308 12.7502 10.945V16.6215C12.7502 17.0357 12.4144 17.3715 12.0002 17.3715Z"
                            fill="" />
                    </svg>
                </div>

                <div>
                    <h4 class="mb-1 text-sm font-semibold text-gray-800 dark:text-white/90">
                        Warning Message
                    </h4>

                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        The Job is not assigned
                    </p>
                </div>
            </div>
        </div>
        @endif


        {{-- {{ json_encode($orderItems) }} --}}



        <div class="border-t border-gray-100 dark:border-gray-800">
            <div class="rounded-xl border border-gray-200 p-6 dark:border-gray-800" x-data="{ activeTab: 'overview' }">
                <div class="border-b border-gray-200 dark:border-gray-800">
                    <nav
                        class="-mb-px flex space-x-2 overflow-x-auto [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-thumb]:bg-gray-200 dark:[&::-webkit-scrollbar-thumb]:bg-gray-600 dark:[&::-webkit-scrollbar-track]:bg-transparent [&::-webkit-scrollbar]:h-1.5">
                        <button
                            class="inline-flex items-center gap-2 border-b-2 px-2.5 py-2 text-sm font-medium transition-colors duration-200 ease-in-out"
                            x-bind:class="activeTab === 'overview' ?
                                ' text-brand-500 border-brand-500  dark:text-brand-400 dark:border-brand-400' :
                                'bg-transparent text-gray-500 border-transparent  hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'"
                            x-on:click="activeTab = 'overview'">
                            <svg class="size-5" width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M4.83203 2.5835C3.58939 2.5835 2.58203 3.59085 2.58203 4.83349V7.25015C2.58203 8.49279 3.58939 9.50015 4.83203 9.50015H7.2487C8.49134 9.50015 9.4987 8.49279 9.4987 7.25015V4.8335C9.4987 3.59086 8.49134 2.5835 7.2487 2.5835H4.83203ZM4.08203 4.83349C4.08203 4.41928 4.41782 4.0835 4.83203 4.0835H7.2487C7.66291 4.0835 7.9987 4.41928 7.9987 4.8335V7.25015C7.9987 7.66436 7.66291 8.00015 7.2487 8.00015H4.83203C4.41782 8.00015 4.08203 7.66436 4.08203 7.25015V4.83349ZM4.83203 10.5002C3.58939 10.5002 2.58203 11.5075 2.58203 12.7502V15.1668C2.58203 16.4095 3.58939 17.4168 4.83203 17.4168H7.2487C8.49134 17.4168 9.4987 16.4095 9.4987 15.1668V12.7502C9.4987 11.5075 8.49134 10.5002 7.2487 10.5002H4.83203ZM4.08203 12.7502C4.08203 12.336 4.41782 12.0002 4.83203 12.0002H7.2487C7.66291 12.0002 7.9987 12.336 7.9987 12.7502V15.1668C7.9987 15.5811 7.66291 15.9168 7.2487 15.9168H4.83203C4.41782 15.9168 4.08203 15.5811 4.08203 15.1668V12.7502ZM10.4987 4.83349C10.4987 3.59085 11.5061 2.5835 12.7487 2.5835H15.1654C16.408 2.5835 17.4154 3.59086 17.4154 4.8335V7.25015C17.4154 8.49279 16.408 9.50015 15.1654 9.50015H12.7487C11.5061 9.50015 10.4987 8.49279 10.4987 7.25015V4.83349ZM12.7487 4.0835C12.3345 4.0835 11.9987 4.41928 11.9987 4.83349V7.25015C11.9987 7.66436 12.3345 8.00015 12.7487 8.00015H15.1654C15.5796 8.00015 15.9154 7.66436 15.9154 7.25015V4.8335C15.9154 4.41928 15.5796 4.0835 15.1654 4.0835H12.7487ZM12.7487 10.5002C11.5061 10.5002 10.4987 11.5075 10.4987 12.7502V15.1668C10.4987 16.4095 11.5061 17.4168 12.7487 17.4168H15.1654C16.408 17.4168 17.4154 16.4095 17.4154 15.1668V12.7502C17.4154 11.5075 16.408 10.5002 15.1654 10.5002H12.7487ZM11.9987 12.7502C11.9987 12.336 12.3345 12.0002 12.7487 12.0002H15.1654C15.5796 12.0002 15.9154 12.336 15.9154 12.7502V15.1668C15.9154 15.5811 15.5796 15.9168 15.1654 15.9168H12.7487C12.3345 15.9168 11.9987 15.5811 11.9987 15.1668V12.7502Z"
                                    fill="currentColor" />
                            </svg>
                            Order Items
                        </button>
                        @if (in_array($authUser->mode, ['dispatch', 'admin','billing']) && !$dispatchNotes->isEmpty())
                        <button
                            class="inline-flex items-center gap-2 border-b-2 px-2.5 py-2 text-sm font-medium transition-colors duration-200 ease-in-out"
                            x-bind:class="activeTab === 'notification' ?
                                    ' text-brand-500 border-brand-500  dark:border-brand-400  dark:text-brand-400' :
                                    'bg-transparent text-gray-500 border-transparent  hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'"
                            x-on:click="activeTab = 'notification'">
                            <svg class="w-5 h-5 text-gray-400 dark:text-white" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5.005 11.19V12l6.998 4.042L19 12v-.81M5 16.15v.81L11.997 21l6.998-4.042v-.81M12.003 3 5.005 7.042l6.998 4.042L19 7.042 12.003 3Z" />
                            </svg>


                            Dispatch Notes
                        </button>
                        @endif
                    </nav>
                </div>



                <div class="pt-4 dark:border-gray-800">
                    <div x-show="activeTab === 'overview'">
                        <div class="max-w-full overflow-x-auto custom-scrollbar border">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-t border-gray-100 dark:border-gray-800">
                                        <th class="px-3 py-3 text-left">
                                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                Item
                                            </p>
                                        </th>
                                        {{-- <th class="px-6 py-3 text-left">
                                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                Price (Rs)
                                            </p>
                                        </th> --}}
                                        <th class="px-6 py-3 text-left">
                                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                Quantity
                                            </p>
                                        </th>
                                        {{-- <th class="px-6 py-3 text-left">
                                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                Total (Rs)
                                            </p>
                                        </th> --}}
                                        <th class="px-6 py-3 text-right">
                                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                Action
                                            </p>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orderItems as $index => $orderItem)
                                    <tr
                                        class="border-t border-gray-100 dark:border-gray-800 cursor-pointer hover:bg-gray-200">
                                        <td class="px-2 py-1">
                                            <p class="font-medium text-gray-500 text-theme-sm dark:text-white/90">
                                                {{ $orderItem['name'] }}
                                            </p>
                                        </td>
                                        {{-- <td class="px-6 py-1">
                                            <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                {{ number_format($orderItem['price'], 2) }}
                                        </td> --}}
                                        <td class="px-6 py-1">
                                            <p class="text-gray-500 text-theme-sm dark:text-gray-400 ">
                                                @if (($status === 'pending' || $status === 'designing') && ($role ===
                                                'design' || $role === 'admin'))
                                                <input type="number" wire:model="orderItems.{{ $index }}.quantity"
                                                    min="0"
                                                    max="{{ $orderItem['stock_balance'] }}"
                                                    class="w-16 border p-1 text-center border-gray-200 dark:border-gray-800"
                                                    style="{{ $orderItem['stock_balance'] == 0 ? 'color: red;' : '' }}">
                                                @else
                                                @if ((int) $orderItem['dispatched_qty'] < $orderItem['quantity']) <span
                                                    class="text-gray-100">
                                                    {{ $orderItem['quantity'] - $orderItem['dispatched_qty'] }}
                                                    </span> / {{ $orderItem['quantity'] }}
                                                    @else
                                                    {{ $orderItem['quantity'] }}
                                                    @endif
                                                    @endif
                                        </td>
                                        {{-- <td class="px-6 py-1">
                                            <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                {{ number_format($orderItem['total'], 2) }}
                                            </p>
                                        </td> --}}

                                        <td class="px-6 py-1 text-right">
                                            <div class="flex justify-end">
                                                @if (($status === 'pending' || $status === 'designing') && ($role ===
                                                'design' || $role === 'admin'))
                                                <svg wire:click="removeItem({{ $orderItem['id'] }})"
                                                    class="w-5 h-5 text-gray-800 dark:text-white hover:text-gray-400 cursor-pointer"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2"
                                                        d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                </svg>
                                                @else
                                                <svg class="w-5 h-5 text-gray-300 dark:text-white hover:text-gray-400 cursor-pointer"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2"
                                                        d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                </svg>
                                                @endif
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
                                Vat (10%): 0
                            </p> --}}

                            <p class="text-lg font-semibold text-gray-800 dark:text-white/90">
                                {{-- Total : {{ number_format($total_amount) }} --}}
                            </p>
                        </div>

                        <div class="flex items-center justify-end gap-3">

                            <button id="back-button" onclick="window.history.back();"
                                style="display: flex; align-items: center; font-family: outfit; gap: 0.5rem; padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; color: white; border-radius: 0.375rem; background-color: #6b7280; box-shadow: 0 2px 2px rgba(0, 0, 0, 0.1);">
                                <svg style="width: 1.5rem; height: 1.5rem; color: white;" fill="none"
                                    stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                                </svg>
                                Back
                            </button>

                            @if (
                            $assigned != null &&
                            ($status === 'pending' || $status === 'designing') &&
                            ($role === 'design' || $role === 'admin' || $role === 'manager'))
                            <button wire:click="unAssignJob()"
                                class="flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white rounded-lg bg-orange-400 shadow-theme-xs hover:bg-orange-500\/\[0\.08\]">
                                <svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M8 8v8m0-8a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm0 8a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm6-2a2 2 0 1 1 4 0 2 2 0 0 1-4 0Zm0 0h-1a5 5 0 0 1-5-5v-.5" />
                                </svg>
                                Un Assign
                            </button>
                            @endif


                            @if (
                            $assigned === null &&
                            ($status === 'pending' || $status === 'designing') &&
                            ($role === 'design' || $role === 'admin' || $role === 'manager'))
                            <button wire:click="assignJob()"
                                class="flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white rounded-lg bg-error-400 shadow-theme-xs hover:bg-error-600">
                                <svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M8 8v8m0-8a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm0 8a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm6-2a2 2 0 1 1 4 0 2 2 0 0 1-4 0Zm0 0h-1a5 5 0 0 1-5-5v-.5" />
                                </svg>
                                Assign
                            </button>
                            @endif


                            @if (($status === 'pending' || $status === 'designing') && ($role === 'design' || $role ===
                            'admin'))
                            <button wire:click="updateOrderItems"
                                class="flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white rounded-lg bg-blue-light-500 shadow-theme-xs hover:bg-blue-light-600">
                                <svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15 17h3a3 3 0 0 0 0-6h-.025a5.56 5.56 0 0 0 .025-.5A5.5 5.5 0 0 0 7.207 9.021C7.137 9.017 7.071 9 7 9a4 4 0 1 0 0 8h2.167M12 19v-9m0 0-2 2m2-2 2 2" />
                                </svg>
                                Apply Changes
                            </button>
                            @endif



                            @if ($assigned != null && $status === 'designing' && ($role === 'design' || $role ===
                            'admin' || $role ===
                            'manager'))
                            <button wire:click="releseOrder({{ $jobOrderId }})"
                                class="flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600">
                                <svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linejoin="round" stroke-width="2"
                                        d="M16.444 18H19a1 1 0 0 0 1-1v-5a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1h2.556M17 11V5a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v6h10ZM7 15h10v4a1 1 0 0 1-1 1H8a1 1 0 0 1-1-1v-4Z" />
                                </svg>
                                Release Job
                            </button>
                            @endif

                            @if (
                            ($status === 'printing' || $status === 'dispatching') &&
                            $isEligibleToDispatch === true &&
                            ($role === 'dispatch' || $role === 'admin'))
                            <button wire:click="generateDispatchFromOrder({{ $jobOrderId }})"
                                class="flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">
                                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M7.99994 10 6 11.9999l1.99994 2M11 5v14m-7 0h16c.5523 0 1-.4477 1-1V6c0-.55228-.4477-1-1-1H4c-.55228 0-1 .44772-1 1v12c0 .5523.44772 1 1 1Z" />
                                </svg>

                                Dispatch
                            </button>
                            @endif


                            @if ($status === 'dispatched' && $isEligibleToDispatch === false && ($role === 'dispatch' ||
                            $role === 'admin'))
                            <button wire:click="compleateDispatch({{ $jobOrderId }})"
                                class="flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">
                                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                @if ($jobOrder->branch_id != $jobOrder->invoice_branch)
                                Create Stock Transfer
                                @else
                                Release To Invoice
                                @endif
                            </button>
                            @endif


                            <!-- if dispatch complete -->
                            @if ($status === 'ready-to-invoice' && ($role === 'billing' || $role === 'admin' || $role === 'accounts'))
                            <button wire:click="generateInvoiceFromOrder({{ $jobOrderId }})"
                                class="flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">
                                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M7.99994 10 6 11.9999l1.99994 2M11 5v14m-7 0h16c.5523 0 1-.4477 1-1V6c0-.55228-.4477-1-1-1H4c-.55228 0-1 .44772-1 1v12c0 .5523.44772 1 1 1Z" />
                                </svg>

                                Create Invoice
                            </button>
                            @endif

                            @if ($status === 'invoiced' && ($role === 'billing' || $role === 'admin' || $role === 'accounts'))
                            {{-- <button wire:click="invoicePrintPreview({{ $invoice->id }})"
                                class="flex items-center justify-center gap-2 px-4 py-3 text-sm font-medium text-white rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600">
                                <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M6.99578 4.08398C6.58156 4.08398 6.24578 4.41977 6.24578 4.83398V6.36733H13.7542V5.62451C13.7542 5.42154 13.672 5.22724 13.5262 5.08598L12.7107 4.29545C12.5707 4.15983 12.3835 4.08398 12.1887 4.08398H6.99578ZM15.2542 6.36902V5.62451C15.2542 5.01561 15.0074 4.43271 14.5702 4.00891L13.7547 3.21839C13.3349 2.81151 12.7733 2.58398 12.1887 2.58398H6.99578C5.75314 2.58398 4.74578 3.59134 4.74578 4.83398V6.36902C3.54391 6.41522 2.58374 7.40415 2.58374 8.61733V11.3827C2.58374 12.5959 3.54382 13.5848 4.74561 13.631V15.1665C4.74561 16.4091 5.75297 17.4165 6.99561 17.4165H13.0041C14.2467 17.4165 15.2541 16.4091 15.2541 15.1665V13.6311C16.456 13.585 17.4163 12.596 17.4163 11.3827V8.61733C17.4163 7.40414 16.4561 6.41521 15.2542 6.36902ZM4.74561 11.6217V12.1276C4.37292 12.084 4.08374 11.7671 4.08374 11.3827V8.61733C4.08374 8.20312 4.41953 7.86733 4.83374 7.86733H15.1663C15.5805 7.86733 15.9163 8.20312 15.9163 8.61733V11.3827C15.9163 11.7673 15.6269 12.0842 15.2541 12.1277V11.6217C15.2541 11.2075 14.9183 10.8717 14.5041 10.8717H5.49561C5.08139 10.8717 4.74561 11.2075 4.74561 11.6217ZM6.24561 12.3717V15.1665C6.24561 15.5807 6.58139 15.9165 6.99561 15.9165H13.0041C13.4183 15.9165 13.7541 15.5807 13.7541 15.1665V12.3717H6.24561Z"
                                        fill="" />
                                </svg>
                                Print
                            </button> --}}
                            <a wire:navigate href="{{ route('invoice.print-preview', ['invoiceId' => $invoice->id]) }}"
                                class="flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600">
                                <svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-width="2"
                                        d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z" />
                                    <path stroke="currentColor" stroke-width="2"
                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>

                                View Invoice
                            </a>
                            @endif

                        </div>

                    </div>


                    @if ($role === 'dispatch' || $role === 'admin' || $role ==='billing' || $role === 'accounts')
                    <div x-show="activeTab === 'notification'">

                        <div class="max-w-full overflow-x-auto custom-scrollbar border">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-t border-gray-100 dark:border-gray-800">
                                        <th class="px-3 py-3 text-left">
                                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                Number
                                            </p>
                                        </th>
                                        <th class="px-6 py-3 text-left">
                                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                Dispatched Date
                                            </p>
                                        </th>
                                        <th class="px-6 py-3 text-left">
                                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                Qty
                                            </p>
                                        </th>
                                        <th class="px-6 py-3 text-left">
                                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                Status
                                            </p>
                                        </th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dispatchNotes as $index => $dispatchNote)
                                    <tr wire:click="dispatchPrintPreview({{ $dispatchNote->id }})"
                                        class="border-t border-gray-100 dark:border-gray-800 cursor-pointer hover:bg-gray-200">


                                        <td class="px-2 py-1">
                                            <p class="font-medium text-gray-500 text-theme-sm dark:text-white/90">
                                                {{ $dispatchNote['dispatch_number'] }}
                                            </p>
                                        </td>
                                        <td class="px-6 py-1">
                                            <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                {{ $dispatchNote['dispatched_at'] }}
                                        </td>

                                        <td class="px-6 py-1">
                                            <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                {{ $dispatchNote['quantity'] }}
                                        </td>

                                        <td class="px-6 py-1">
                                            <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                {{ $dispatchNote['status'] }}
                                            </p>
                                        </td>

                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>

                        </div>
                    </div>
                    @endif

                </div>

            </div>
        </div>
    </div>
