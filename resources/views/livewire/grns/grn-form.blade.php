<div class="p-4 flex justify-left">
    <form wire:submit.prevent="saveGrn" class="w-full sm:w-4/6 md:w-3/6 lg:w-2/6">
        <div
            class="w-full max-w-4xl rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] grid grid-cols-1 gap-0 sm:grid-cols-2">

            <!-- Left Column -->
            <div class="space-y-0  border-gray-100 dark:border-gray-800">
                <div class="px-5 py-4 sm:px-6 sm:py-5">
                    <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Create GRN</h3>
                </div>
                <div class="p-5 border-t space-y-6 sm:p-6">
                    <!-- Search Supplier -->
                    <div class="w-full">
                        <label class="mb-2 block text-xs font-medium text-gray-700 dark:text-gray-400">Search
                            Supplier<span class="text-error-500">*</span></label>
                        <input type="text" wire:model.live.throttle.150ms="searchSupplier"
                            placeholder="Search supplier..."
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                        <!-- Search Results -->
                        @if (!empty($searchResultsSupplier))
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
                                                    address
                                                </p>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($searchResultsSupplier as $supplier)
                                            <tr wire:click="assignSupplier({{ $supplier['id'] }})"
                                                class="border-t border-gray-100 dark:border-gray-800 cursor-pointer hover:bg-gray-200">
                                                <td class="px-2 py-3.5">
                                                    <p
                                                        class="font-medium text-gray-500 text-theme-sm dark:text-white/90">
                                                        {{ $supplier['name'] }}
                                                    </p>
                                                </td>
                                                <td class="px-6 py-3.5">
                                                    <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                        {{ $supplier['phone'] }}</p>
                                                </td>
                                                <td class="px-6 py-3.5">
                                                    <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                        {{ $supplier['email'] }}</p>
                                                </td>
                                                <td class="px-6 py-3.5">
                                                    <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                        {{ $supplier['address'] }}
                                                    </p>
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                    <!-- Delivery Date -->
                    <div class="w-full">
                        <label class="mb-2 block text-xs font-medium text-gray-700 dark:text-gray-400">Delivery
                            Date<span class="text-error-500">*</span></label>
                        <div class="relative">
                            <input type="date" wire:model="delivery_date" id="delivery_date"
                                onclick="this.showPicker()"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                            @error('delivery_date')
                                <p class="text-error-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
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

                    <!-- Effective Date -->
                    <div class="w-full">
                        <label class="mb-1.5 block text-xs font-medium text-gray-700 dark:text-gray-400">Effective
                            Date<span class="text-error-500">*</span></label>
                        <div class="relative">
                            <input type="date" wire:model="effective_date" id="effective_date"
                                onclick="this.showPicker()"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                            <span
                                class="pointer-events-none mt-1 absolute top-1/2 right-3 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                                <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20"
                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M6.66659 1.5415C7.0808 1.5415 7.41658 1.87729 7.41658 2.2915V2.99984H12.5833V2.2915C12.5833 1.87729 12.919 1.5415 13.3333 1.5415C13.7475 1.5415 14.0833 1.87729 14.0833 2.2915V2.99984L15.4166 2.99984C16.5212 2.99984 17.4166 3.89527 17.4166 4.99984V7.49984V15.8332C17.4166 16.9377 16.5212 17.8332 15.4166 17.8332H4.58325C3.47868 17.8332 2.58325 16.9377 2.58325 15.8332V7.49984V4.99984C2.58325 3.89527 3.47868 2.99984 4.58325 2.99984L5.91659 2.99984V2.2915C5.91659 1.87729 6.25237 1.5415 6.66659 1.5415ZM6.66659 4.49984H4.58325C4.30711 4.49984 4.08325 4.7237 4.08325 4.99984V6.74984H15.9166V4.99984C15.9166 4.7237 15.6927 4.49984 15.4166 4.49984H13.3333H6.66659ZM15.9166 8.24984H4.08325V15.8332C4.08325 16.1093 4.30711 16.3332 4.58325 16.3332H15.4166C15.6927 16.3332 15.9166 16.1093 15.9166 15.8332V8.24984Z"
                                        fill="" />
                                </svg>
                            </span>
                            @error('effective_date')
                                <p class="text-error-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Delivery Location -->
                    {{-- <div class="w-full">
                        <label class="mb-1.5 block text-xs font-medium text-gray-700 dark:text-gray-400">Delivery
                            Location<span class="text-error-500">*</span></label>
                        <select wire:model="delivery_location" id="delivery_location"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                            <option value="type_1">Select location</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                            @endforeach

                        </select>
                        @error('delivery_location')
                            <p class="text-error-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div> --}}
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-0 mt-6">
                <div class="px-5 py-4 sm:px-6 sm:py-5">
                    <h3 class="pl-2 text-base font-medium text-gray-800 dark:text-white/90"></h3>
                </div>
                <div class="p-5 border-t space-y-5 sm:p-6">
                    <!-- Delivery Remark -->
                    <div class="w-full">
                        <label class="mb-2 block text-xs font-medium text-gray-700 dark:text-gray-400">Delivery
                            Remark</label>
                        <input wire:model="delivery_remark" id="delivery_remark" placeholder="Enter Delivery Remark"
                            class=" h-8 dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"></input>
                        @error('delivery_remark')
                            <p class="text-error-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- GRN Type -->
                    <div class="w-full">
                        <label class="mb-3 block text-xs font-medium text-gray-700 dark:text-gray-400">GRN Type</label>
                        <select wire:model="grn_type" id="grn_type"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                            <option value="type_1">Select GRN Type</option>
                            <option value="type_2">Purchase</option>
                        </select>
                        @error('grn_type')
                            <p class="text-error-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Order Type -->
                    <div class="w-full">
                        <label class="mb-2 block text-xs font-medium text-gray-700 dark:text-gray-400">Order
                            Type</label>
                        <select wire:model="order_type" id="order_type"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                            <option value="regular">Select Order Type</option>
                            <option value="urgent">PO</option>
                        </select>
                        @error('order_type')
                            <p class="text-error-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- PO Number -->
                    <div class="w-full">
                        <label class="mb-3 block text-xs font-medium text-gray-700 dark:text-gray-400">PO
                            Number</label>
                        <input wire:model="remark" id="remark" placeholder="Enter PO Number"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"></input>
                        @error('remark')
                            <p class="text-error-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>


                    <!-- Save Button -->
                    <div class="pt-2 pb-1 flex justify-end">
                        <button type="submit"
                            class="bg-brand-600 hover:bg-brand-700 text-white font-medium px-6 py-2 rounded-md text-xs shadow-md transition duration-300 ease-in-out transform hover:scale-105"
                            style="background-color:#465FFF;">
                            Save
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>
