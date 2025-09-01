<div class="flex flex-col  bg-white rounded-lg shadow-md">
    @if ($errors->any())
    <div class="p-10 py-3 px-6">
        <div class="rounded-xl border border-error-500 bg-error-50 p-4 dark:border-error-500/30 dark:bg-error-500/15">
            <div class="flex items-start gap-3">
                <div class="-mt-0.5 text-error-500">
                    <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M20.3499 12.0004C20.3499 16.612 16.6115 20.3504 11.9999 20.3504C7.38832 20.3504 3.6499 16.612 3.6499 12.0004C3.6499 7.38881 7.38833 3.65039 11.9999 3.65039C16.6115 3.65039 20.3499 7.38881 20.3499 12.0004ZM11.9999 22.1504C17.6056 22.1504 22.1499 17.6061 22.1499 12.0004C22.1499 6.3947 17.6056 1.85039 11.9999 1.85039C6.39421 1.85039 1.8499 6.3947 1.8499 12.0004C1.8499 17.6061 6.39421 22.1504 11.9999 22.1504ZM13.0008 16.4753C13.0008 15.923 12.5531 15.4753 12.0008 15.4753L11.9998 15.4753C11.4475 15.4753 10.9998 15.923 10.9998 16.4753C10.9998 17.0276 11.4475 17.4753 11.9998 17.4753L12.0008 17.4753C12.5531 17.4753 13.0008 17.0276 13.0008 16.4753ZM11.9998 6.62898C12.414 6.62898 12.7498 6.96476 12.7498 7.37898L12.7498 13.0555C12.7498 13.4697 12.414 13.8055 11.9998 13.8055C11.5856 13.8055 11.2498 13.4697 11.2498 13.0555L11.2498 7.37898C11.2498 6.96476 11.5856 6.62898 11.9998 6.62898Z"
                            fill="#F04438"></path>
                    </svg>
                </div>

                <div>
                    <h4 class="mb-1 text-sm font-semibold text-gray-800  dark:text-white/90">
                        Job Order Creation Faild!
                    </h4>

                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Consider following required fileds!
                    </p>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $msg)
            <li>{{ $msg }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="flex dark:bg-white/[0.03] flex-row justify-start">

        <div class="flex-1  bg-white rounded-lg p-6 shadow-md">
            <div class="space-y-6 ">
                <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="px-5 py-4  sm:px-6 sm:py-5 flex justify-between items-center">
                        <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                            {{ $jobOrderId ? 'Edit Job Order -' : 'Create New Job Order' }} {{ $job_number ?? '' }}
                        </h3>


                        <div class="relative" x-data="{ open: @entangle('showBranchDropdown'), showConfirm: false }">
                            <!-- Clickable badge -->

                            {{-- <span @click="open = !open"
                                class="cursor-pointer inline-flex items-center justify-center gap-1 rounded-full bg-blue-light-50 px-2.5 py-0.5 text-sm font-medium text-blue-light-500 dark:bg-blue-light-500/15 dark:text-blue-light-500">
                                <label class="block text-gray-700 font-medium">To Branch:</label> {{ $this->branch_code
                                }}
                            </span> --}}

                            <!-- Dropdown -->
                            <div x-show="open" @click.outside="open = false"
                                class="absolute z-50 mt-2 w-40 rounded-md bg-white shadow-lg dark:bg-gray-800 border border-gray-200 dark:border-gray-600">
                                <ul class="py-1 text-sm text-gray-700 dark:text-gray-200">
                                    @foreach ($branchOptions as $branch)
                                    <li>
                                        <button @click="open = false; showConfirm = true"
                                            wire:click="selectBranch({{ $branch['id'] }})"
                                            class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">
                                            {{ $branch['branch_code'] }} - {{ $branch['branch_name'] }}
                                        </button>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>


                            <div x-show="showConfirm"
                                class="fixed inset-0 flex items-center justify-center p-5 overflow-y-auto z-50">
                                <div
                                    class="modal-close-btn fixed inset-0 h-full w-full bg-gray-400/50 backdrop-blur-[30px]">
                                </div>

                                <div class="flex flex-col px-4 py-4 overflow-y-auto no-scrollbar">
                                    <div @click.outside="showConfirm = false"
                                        class="relative w-full max-w-[507px] rounded-3xl bg-white p-6 dark:bg-gray-900 lg:p-10">
                                        <div class="text-center">
                                            <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">
                                                Confirm Change Wrorking Branch
                                            </h4>
                                            <p class="text-sm leading-6 text-gray-500 dark:text-gray-400">
                                                Are you sure you want to change this branch for this job order?
                                                Click
                                                Yes to confirm.
                                            </p>

                                            <div class="flex items-center justify-center w-full gap-3 mt-8">
                                                <button @click="showConfirm = false" type="button"
                                                    class="flex justify-center rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">
                                                    Cancel
                                                </button>
                                                <button type="button"
                                                    @click="$wire.changeWorkingBranch(); showConfirm = false"
                                                    class="flex justify-center px-4 py-3 text-sm font-medium text-white rounded-lg bg-brand-500 shadow-theme-xs hover:bg-error-600">
                                                    Yes
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>



                    <form wire:submit.prevent="save" onclick="clearMessage()">
                        <div class="border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800">
                            <!-- Two Column Grid Layout -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">

                                <!-- Customer Search -->
                                <div class="relative ">
                                    <label class="block text-xs text-gray-700 font-medium">Search Customer<span
                                            class="text-error-500">*</span></label>
                                    <input type="text" wire:model.live.throttle.150ms="searchCustomer"
                                        placeholder="Search customer"
                                        class="dark:bg-dark-900 mt-2 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                                    <!-- Search Results -->
                                    @if (!empty($searchResultsCustomer))
                                    <div class="max-w-full overflow-x-auto custom-scrollbar border">
                                        <table class="w-full">
                                            <thead>
                                                <tr class="border-t border-gray-100 dark:border-gray-800">
                                                    <th class="px-3 py-3 text-left">
                                                        <p
                                                            class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                            Name
                                                        </p>
                                                    </th>
                                                    <th class="px-6 py-3 text-left">
                                                        <p
                                                            class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                            Contact
                                                        </p>
                                                    </th>
                                                    <th class="px-6 py-3 text-left">
                                                        <p
                                                            class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                            Email
                                                        </p>
                                                    </th>
                                                    <th class="px-6 py-3 text-left">
                                                        <p
                                                            class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
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
                                                        <p
                                                            class="font-medium text-gray-500 text-theme-sm dark:text-white/90">
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


                                <!-- Item Search -->
                                <div class=" mb-1">
                                    <label class="block text-xs text-gray-700 font-medium">Search Item<span
                                            class="text-error-500">*</span></label>
                                    <input type="text" wire:model.live.throttle.150ms="searchTerm"
                                        placeholder="Search items..."
                                        class="dark:bg-dark-900 mt-2 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-1.5 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />

                                    <!-- Search Results -->
                                    @if (!empty($searchResults))
                                    <div class="max-w-full overflow-x-auto custom-scrollbar border">
                                        <table class="w-full">
                                            <thead>
                                                <tr class="border-t border-gray-100 dark:border-gray-800">
                                                    <th class="px-3 py-3 text-left">
                                                        <p
                                                            class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                            Item
                                                        </p>
                                                    </th>
                                                    <th class="px-6 py-3 text-left">
                                                        <p
                                                            class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                            Code
                                                        </p>
                                                    </th>
                                                    {{-- <th class="px-6 py-3 text-left">
                                                        <p
                                                            class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                            Selling
                                                        </p>
                                                    </th> --}}
                                                    <th class="px-6 py-3 text-left">
                                                        <p
                                                            class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                            Stock
                                                        </p>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($searchResults as $item)
                                                <tr @if ($item['stock_balance'] !=0)
                                                    wire:click="addOrderItem({{ $item['id'] }})"
                                                    class="border-t border-gray-100 dark:border-gray-800 cursor-pointer hover:bg-error-200"
                                                    @else
                                                    class="border-t border-gray-100 dark:border-gray-800 opacity-50 cursor-not-allowed hover:bg-error-50 bg-error-50"
                                                    @endif>
                                                    <td class="px-2 py-3">
                                                        <p
                                                            class="font-medium text-gray-500 text-theme-xs dark:text-white/90">
                                                            {{ $item['item_name'] }}
                                                        </p>
                                                    </td>
                                                    <td class="px-6 py-3">
                                                        <p class="text-gray-500 text-theme-xs dark:text-gray-400">
                                                            {{ $item['item_code'] }}</p>
                                                    </td>
                                                    {{-- <td class="px-6 py-3">
                                                        <p class="text-gray-500 text-theme-xs dark:text-gray-400">
                                                            {{ $item['selling_price'] }}</p>
                                                    </td> --}}
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

                                <!-- Description -->
                                <div>
                                    <label class=" block text-xs font-medium text-gray-700 dark:text-gray-400">
                                        Description<span class="text-error-500">*</span>
                                    </label>
                                    <textarea wire:model="description" placeholder="Enter job description"
                                        class="dark:bg-dark-900 mt-2 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-15 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"></textarea>
                                    @error('description')
                                    <p style="color: red" class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Special Instruction -->
                                <div>
                                    <label class=" block text-xs font-medium text-gray-700 dark:text-gray-400">
                                        Special Instruction
                                    </label>
                                    <textarea wire:model="special_instruction"
                                        placeholder="Enter any special instructions"
                                        class="dark:bg-dark-900 mt-2 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-15 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"></textarea>
                                    @error('special_instruction')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Plat info -->
                                <div class="flex flex-row justify-between gap-2">
                                    <!-- Plate Baking -->
                                    <div class="flex-1">
                                        <label class=" block text-xs font-medium text-gray-700 dark:text-gray-400">
                                            Plate Baking
                                        </label>
                                        <select wire:model="plate_backing" wire:change="$refresh"
                                            class="dark:bg-dark-900 mt-2 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                                            <option value="0" selected>No</option>
                                            <option value="1">Yes</option>

                                        </select>
                                        @error('plate_backing')
                                        <p style="color: red" class="text-red-500 text-sm mt-1">{{ $message }}
                                        </p>
                                        @enderror
                                    </div>

                                    <!-- Baking Quantity -->
                                    <div class="flex-1">
                                        @if ($plate_backing == 1)
                                        <label class=" block text-xs font-medium text-gray-700 dark:text-gray-400">
                                            Baking Quantity
                                        </label>
                                        <input type="number" reguired wire:model="backing_qty" min="1"
                                            class="dark:bg-dark-900 mt-2 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                                        @endif
                                        @error('backing_qty')
                                        <p style="color: red" class="text-red-500 text-sm mt-1">{{ $message }}
                                        </p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Customer PO# -->
                                <div>
                                    <label class="block text-xs text-gray-700 font-medium">
                                        Customer PO#
                                    </label>
                                    <input type="text" wire:model="customer_po_number"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />

                                </div>
                                <!-- D Date -->
                                <div>
                                    <label class=" block text-xs font-medium text-gray-700 dark:text-gray-400">
                                        Due Date
                                    </label>
                                    <div class="relative">

                                        <input type="date" wire:model="delivery_date" placeholder="Select date"
                                            onclick="this.showPicker()"
                                            class="dark:bg-dark-900 mt-2 datepickerTwo shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 pl-4 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                                        <span
                                            class="pointer-events-none mt-1 absolute top-1/2 right-3 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                                            <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20"
                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M6.66659 1.5415C7.0808 1.5415 7.41658 1.87729 7.41658 2.2915V2.99984H12.5833V2.2915C12.5833 1.87729 12.919 1.5415 13.3333 1.5415C13.7475 1.5415 14.0833 1.87729 14.0833 2.2915V2.99984L15.4166 2.99984C16.5212 2.99984 17.4166 3.89527 17.4166 4.99984V7.49984V15.8332C17.4166 16.9377 16.5212 17.8332 15.4166 17.8332H4.58325C3.47868 17.8332 2.58325 16.9377 2.58325 15.8332V7.49984V4.99984C2.58325 3.89527 3.47868 2.99984 4.58325 2.99984L5.91659 2.99984V2.2915C5.91659 1.87729 6.25237 1.5415 6.66659 1.5415ZM6.66659 4.49984H4.58325C4.30711 4.49984 4.08325 4.7237 4.08325 4.99984V6.74984H15.9166V4.99984C15.9166 4.7237 15.6927 4.49984 15.4166 4.49984H13.3333H6.66659ZM15.9166 8.24984H4.08325V15.8332C4.08325 16.1093 4.30711 16.3332 4.58325 16.3332H15.4166C15.6927 16.3332 15.9166 16.1093 15.9166 15.8332V8.24984Z"
                                                    fill="" />
                                            </svg>
                                        </span>
                                    </div>
                                </div>

                            </div> <!-- End of Grid -->
                        </div>

                        <!-- Buttons area -->
                        <div
                            class="flex items-center justify-end gap-3 border-t border-gray-100 dark:border-gray-800 mb-5 pt-2 px-5">
                            <button type="submit" wire:loading.class="opacity-50"
                                class="flex items-center justify-center gap-2 px-4 py-3 text-sm font-medium text-white rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600 mt-5">
                                <svg class="w-5 h-5 text-white dark:text-white" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15 17h3a3 3 0 0 0 0-6h-.025a5.56 5.56 0 0 0 .025-.5A5.5 5.5 0 0 0 7.207 9.021C7.137 9.017 7.071 9 7 9a4 4 0 1 0 0 8h2.167M12 19v-9m0 0-2 2m2-2 2 2" />
                                </svg>
                                Save Order
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="flex-1 rounded-2xl border mt-6 mb-6 mr-6 border-gray-200 bg-white  shadow-md ">
            @if ($customer_id)
            <div class=" border-gray-100 p-4 dark:border-gray-800 sm:p-6">
                <div
                    class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] sm:w-fit">
                    <ul class="flex flex-col">
                        <li class="border-b border-gray-200 px-3 py-2.5 last:border-b-0 dark:border-gray-800">
                            <div x-data="{ checkboxToggle: false }">
                                <div for="listCheckboxOne"
                                    class="flex cursor-pointer select-none items-center text-sm text-gray-500 dark:text-gray-400">

                                    <svg class="w-5 h-5 text-gray-200 dark:text-white" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                        viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M7 6H5m2 3H5m2 3H5m2 3H5m2 3H5m11-1a2 2 0 0 0-2-2h-2a2 2 0 0 0-2 2M7 3h11a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1Zm8 7a2 2 0 1 1-4 0 2 2 0 0 1 4 0Z" />
                                    </svg>

                                    <p class="px-5">{{ $customer_name }}</p>
                                </div>

                            </div>
                        </li>


                        <li class="border-b border-gray-200 px-3 py-2.5 last:border-b-0 dark:border-gray-800">
                            <div x-data="{ checkboxToggle: false }">
                                <div for="listCheckboxOne"
                                    class="flex cursor-pointer select-none items-center text-sm text-gray-500 dark:text-gray-400">
                                    <svg class="w-5 h-5 text-gray-200 dark:text-white" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                        viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="m4 12 8-8 8 8M6 10.5V19a1 1 0 0 0 1 1h3v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h3a1 1 0 0 0 1-1v-8.5" />
                                    </svg>
                                    @if (auth()->user()->mode === 'admin' || auth()->user()->mode === 'billing'||
                                    auth()->user()->mode === 'accounts')
                                    <p class="px-5"> {{ $customer_address }}</p>
                                    @endif

                                </div>
                            </div>
                        </li>

                        <li class="border-b border-gray-200 px-3 py-2.5 last:border-b-0 dark:border-gray-800">
                            <div x-data="{ checkboxToggle: false }">
                                <div for="listCheckboxOne"
                                    class="flex cursor-pointer select-none items-center text-sm text-gray-500 dark:text-gray-400">
                                    <svg class="w-5 h-5 text-gray-200 dark:text-white" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                        viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                                            d="m3.5 5.5 7.893 6.036a1 1 0 0 0 1.214 0L20.5 5.5M4 19h16a1 1 0 0 0 1-1V6a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Z" />
                                    </svg>
                                    @if (auth()->user()->mode === 'admin' || auth()->user()->mode === 'billing'||
                                    auth()->user()->mode === 'accounts')
                                    <p class="px-5">{{ $customer_email }}</p>

                                    @endif
                                </div>
                            </div>
                        </li>


                        <li class="border-b border-gray-200 px-3 py-2.5 last:border-b-0 dark:border-gray-800">
                            <div x-data="{ checkboxToggle: false }">
                                <div for="listCheckboxOne"
                                    class="flex cursor-pointer select-none items-center text-sm text-gray-500 dark:text-gray-400">
                                    <svg class="w-5 h-5 text-gray-200 dark:text-white" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                        viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M18.427 14.768 17.2 13.542a1.733 1.733 0 0 0-2.45 0l-.613.613a1.732 1.732 0 0 1-2.45 0l-1.838-1.84a1.735 1.735 0 0 1 0-2.452l.612-.613a1.735 1.735 0 0 0 0-2.452L9.237 5.572a1.6 1.6 0 0 0-2.45 0c-3.223 3.2-1.702 6.896 1.519 10.117 3.22 3.221 6.914 4.745 10.12 1.535a1.601 1.601 0 0 0 0-2.456Z" />
                                    </svg>
                                    @if (auth()->user()->mode === 'admin' || auth()->user()->mode === 'billing'||
                                    auth()->user()->mode === 'accounts')
                                    <p class="px-5">{{ $customer_phone }}</p>
                                    @endif
                                </div>
                            </div>
                        </li>

                        <li class="border-b border-gray-200 px-3 py-2.5 last:border-b-0 dark:border-gray-800">
                            <div x-data="{ checkboxToggle: false }">
                                <div for="listCheckboxOne"
                                    class="flex cursor-pointer select-none items-center text-sm text-gray-500 dark:text-gray-400">
                                    <svg class="w-5 h-5 text-gray-200 dark:text-white" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                        viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M5 14v7M5 4.971v9.541c5.6-5.538 8.4 2.64 14-.086v-9.54C13.4 7.61 10.6-.568 5 4.97Z" />
                                    </svg>
                                    @if (auth()->user()->mode === 'admin' || auth()->user()->mode === 'billing'||
                                    auth()->user()->mode === 'accounts')
                                    <p class="px-5">{{ $customer_city }}</p>
                                    @endif
                                </div>
                            </div>
                        </li>

                        <li class="border-b border-gray-200 px-3 py-2.5 last:border-b-0 dark:border-gray-800">
                            <div x-data="{ checkboxToggle: false }">
                                <div for="listCheckboxOne"
                                    class="flex cursor-pointer select-none items-center text-sm text-gray-500 dark:text-gray-400">
                                    <svg class="w-5 h-5 text-gray-200 dark:text-white" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                        viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 8v4l3 3M3.22302 14C4.13247 18.008 7.71683 21 12 21c4.9706 0 9-4.0294 9-9 0-4.97056-4.0294-9-9-9-3.72916 0-6.92858 2.26806-8.29409 5.5M7 9H3V5" />
                                    </svg>


                                    {{-- <p class="px-5 text-gray-600">Total Due: {{ number_format($customerDue, 2) }}
                                        LKR</p> --}}
                                </div>
                            </div>
                        </li>

                    </ul>
                </div>
            </div>
            @else
            <div class=" border-gray-100 p-4 dark:border-gray-800 sm:p-6">
                <div
                    class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] sm:w-fit">
                    <ul class="flex flex-col">
                        <li class="border-b border-gray-200 px-3 py-2.5 last:border-b-0 dark:border-gray-800">
                            <div
                                class="flex cursor-pointer select-none items-center text-sm text-gray-500 dark:text-gray-400">
                                <svg class="w-5 h-5 text-gray-200 dark:text-white" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M7 6H5m2 3H5m2 3H5m2 3H5m2 3H5m11-1a2 2 0 0 0-2-2h-2a2 2 0 0 0-2 2M7 3h11a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1Zm8 7a2 2 0 1 1-4 0 2 2 0 0 1 4 0Z" />
                                </svg>
                                <p class="px-5">No Customer Selected</p>
                            </div>
                        </li>
                        <li class="border-b border-gray-200 px-3 py-2.5 last:border-b-0 dark:border-gray-800">
                            <div
                                class="flex cursor-pointer select-none items-center text-sm text-gray-500 dark:text-gray-400">
                                <svg class="w-5 h-5 text-gray-200 dark:text-white" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="m4 12 8-8 8 8M6 10.5V19a1 1 0 0 0 1 1h3v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h3a1 1 0 0 0 1-1v-8.5" />
                                </svg>
                                <p class="px-5">N/A</p>
                            </div>
                        </li>
                        <li class="border-b border-gray-200 px-3 py-2.5 last:border-b-0 dark:border-gray-800">
                            <div
                                class="flex cursor-pointer select-none items-center text-sm text-gray-500 dark:text-gray-400">
                                <svg class="w-5 h-5 text-gray-200 dark:text-white" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                                        d="m3.5 5.5 7.893 6.036a1 1 0 0 0 1.214 0L20.5 5.5M4 19h16a1 1 0 0 0 1-1V6a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Z" />
                                </svg>
                                <p class="px-5">N/A</p>
                            </div>
                        </li>
                        <li class="border-b border-gray-200 px-3 py-2.5 last:border-b-0 dark:border-gray-800">
                            <div
                                class="flex cursor-pointer select-none items-center text-sm text-gray-500 dark:text-gray-400">
                                <svg class="w-5 h-5 text-gray-200 dark:text-white" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M18.427 14.768 17.2 13.542a1.733 1.733 0 0 0-2.45 0l-.613.613a1.732 1.732 0 0 1-2.45 0l-1.838-1.84a1.735 1.735 0 0 1 0-2.452l.612-.613a1.735 1.735 0 0 0 0-2.452L9.237 5.572a1.6 1.6 0 0 0-2.45 0c-3.223 3.2-1.702 6.896 1.519 10.117 3.22 3.221 6.914 4.745 10.12 1.535a1.601 1.601 0 0 0 0-2.456Z" />
                                </svg>
                                <p class="px-5">N/A</p>
                            </div>
                        </li>
                        <li class="border-b border-gray-200 px-3 py-2.5 last:border-b-0 dark:border-gray-800">
                            <div
                                class="flex cursor-pointer select-none items-center text-sm text-gray-500 dark:text-gray-400">
                                <svg class="w-5 h-5 text-gray-200 dark:text-white" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M5 14v7M5 4.971v9.541c5.6-5.538 8.4 2.64 14-.086v-9.54C13.4 7.61 10.6-.568 5 4.97Z" />
                                </svg>
                                <p class="px-5">N/A</p>
                            </div>
                        </li>
                        <li class="border-b border-gray-200 px-3 py-2.5 last:border-b-0 dark:border-gray-800">
                            <div x-data="{ checkboxToggle: false }">
                                <div for="listCheckboxOne"
                                    class="flex cursor-pointer select-none items-center text-sm text-gray-500 dark:text-gray-400">
                                    <svg class="w-5 h-5 text-gray-200 dark:text-white" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                        viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 8v4l3 3M3.22302 14C4.13247 18.008 7.71683 21 12 21c4.9706 0 9-4.0294 9-9 0-4.97056-4.0294-9-9-9-3.72916 0-6.92858 2.26806-8.29409 5.5M7 9H3V5" />
                                    </svg>


                                    <p class="px-5 text-gray-600">N/A </p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            @endif


            @if (!empty($jobOrderItems))
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
                        <h4 class="mb-1 text-sm font-semibold text-gray-800 dark:text-white/90">Error Message
                        </h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ session('error') }}
                        </p>
                    </div>
                </div>
            </div>
            @endif


            @if ($jobOrderId && $dispatchedCount > 0)
            <div class="custom-scrollbar sm:p-6 max-w-full overflow-x-auto mt-3">
                <h4>Dispatched Items</h4>
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

                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach ($dispatchedItems as $index => $dispatchedItem)
                        <tr class="cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800">
                            <td class="py-1 whitespace-nowrap">
                                <div class="flex items-center">
                                    <p class="text-theme-xs text-gray-700 dark:text-gray-400 px-2">
                                        {{ $dispatchedItem->item->item_name }}
                                    </p>
                                </div>
                            </td>
                            <td class="py-1 whitespace-nowrap">
                                <div class="flex items-center">
                                    <p class="text-theme-xs text-gray-700 dark:text-gray-400 px-2">
                                        {{ $dispatchedItem->item->item_code }}
                                    </p>
                                </div>
                            </td>
                            <td class="py-1 whitespace-nowrap">
                                <div class="flex items-center">
                                    <p class="text-theme-xs text-gray-700 dark:text-gray-400">
                                        {{ $dispatchedItem->quantity }}
                                    </p>
                                </div>
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif






            <div class="custom-scrollbar sm:p-6 max-w-full overflow-x-auto mt-5">
                @if ($jobOrderId)
                <div class="text-sm text-gray-500 dark:text-gray-400"><strong>Note:</strong> Please enter only the additional quantity needed to reach your target total. For example, if you want the total to be 10 and you currently have 7, enter 3. If you want the total to be 5 and you currently have 7, enter <span class="text-error-500">-2</span>.</div>
                @endif
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
                                        <input type="number" wire:model="jobOrderItems.{{ $index }}.quantity"
                                            wire:change="updateTotal({{ $index }})"
                                            min="{{ isset($dispatchedCount) ? $dispatchedCount : 1 }}"
                                            class="w-16 border p-1 text-center">
                                    </p>
                                </div>
                            </td>
                            <td class="py-1 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center">
                                    <p
                                        class="text-theme-xs text-gray-300 dark:text-gray-400 text-center hover:text-gray-500">
                                        @if ($status != 'completed')
                                        <button wire:click="removeItem({{ $index }},{{ $orderItem['id'] }})"
                                            class="text-red-500">
                                            <svg class="w-4 h-4 text-gray-800 dark:text-white hover:text-gray-400"
                                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="18"
                                                height="18" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                        </button>
                                        @else
                                        <button class="text-red-500">
                                            <svg class="w-4 h-4 text-gray-300 dark:text-white hover:text-gray-400"
                                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="18"
                                                height="18" fill="none" viewBox="0 0 24 24">
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
            @else
            <div class="custom-scrollbar max-w-full sm:p-6 overflow-x-auto mt-5">
                <table class="min-w-full ">
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
                        <tr>
                            <td colspan="4" class="text-center text-gray-500 dark:text-gray-400 py-4">
                                No items found.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            @endif


            <div class="pb-6 my-6 text-right border-b border-gray-100 dark:border-gray-800 sm:p-6">
                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                    {{-- Sub Total amount: {{ number_format($total_amount, 2) }} --}}
                </p>
                {{-- <p class="mb-3 text-sm text-gray-500 dark:text-gray-400">
                    Vat (10%): {{ number_format($total_amount * 0.1, 2) }}
                </p> --}}

                <p class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    {{-- Total : {{ number_format($total_amount, 2) }} --}}
                </p>
            </div>





        </div>
    </div>
</div>