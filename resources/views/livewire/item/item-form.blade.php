


<div class="mx-auto max-w-screen-2xl p-4">
    <form wire:submit='saveItem' action="">
        <div
            class="grid grid-cols-1 gap-6 sm:grid-cols-2 w-full rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <!-- Full-width grid container with 2-column layout for sm and larger screens -->

            <!-- Left Column -->
            <div class="space-y-6">
                <div class="">
                    <div class="px-5 py-4 sm:px-6 sm:py-5">
                        <h3 class="pl-2 text-base font-medium text-gray-800 dark:text-white/90">
                            Create Item
                        </h3>
                    </div>

                    <div class="p-5 space-y-6 border-t border-gray-100 dark:border-gray-800 sm:p-6">
                        <div class="flex flex-wrap gap-y-5">
                            <!-- Item Name (Left Column) -->
                            <div class="w-full px-2.5 xl:w-1/2">
                                <label class="mb-1.5 block text-xs font-medium text-gray-700 dark:text-gray-400">Item
                                    Name<span class="text-error-500">*</span></label>
                                <input type="text" {{ $isView ? 'disabled' : '' }} wire:model="item_name"
                                    id="item_name" placeholder="Item Name"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                                @error('item_name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex flex-row px-2.6 flex-1">
                                <!-- Brand Selection (Left Column) -->
                                {{-- <div class="w-1/2 px-2.5 flex-grow flex-1">
                                    <label
                                        class="mb-1.5 block text-xs font-medium text-gray-700 dark:text-gray-400">Brand<span class="text-error-500">*</span></label>
                                    <select wire:model="brands_id" id="brands_id"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                                        <option class="text-gray-400" value="">Select Brand</option>
                                        @if (!empty($brands) && $brands->count() > 0)
                                            @foreach ($brands as $brand)
                                                <option class="text-gray-700" value="{{ $brand->id }}">
                                                    {{ $brand->brand_name }}</option>
                                            @endforeach
                                        @else
                                            <option class="text-red-400" value="">No brands available</option>
                                        @endif
                                    </select>

                                    @error('brands_id')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div> --}}

                                <!-- Item Type (Right Column) -->
                                <div class="w-1/2 px-2.5  flex-grow flex-1">
                                    <label
                                        class="mb-1.5 block text-xs font-medium text-gray-700 dark:text-gray-400">Item
                                        Type<span class="text-error-500">*</span></label>
                                    <select wire:model.change="item_type" id="item_type"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                        style="color:#000">
                                        <option value="RW">Raw Material</option>
                                        <option value="FG">Finish Good</option>
                                        <option value="SR">Service</option>
                                        <option value="PR">Product</option>
                                    </select>
                                    @error('item_type')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                            </div>
                            @if ($item_type == 'RW')
                                <!-- Show only Purchase Price -->
                                <div class="w-full px-2.5 xl:w-1/2">
                                    <label
                                        class="mb-1.5 block text-xs font-medium text-gray-700 dark:text-gray-400">Purchase
                                        Price<span class="text-error-500">*</span></label>
                                    <input type="number" wire:model="purchase_price" id="purchase_price" step="0.01"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                                </div>

                                <!-- Purchase Account (Right Column) -->
                                <div class="w-full px-2.5 xl:w-1/2">
                                    <label
                                        class="mb-1.5 block text-xs font-medium text-gray-700 dark:text-gray-400">Purchase
                                        Account</label>
                                    <select wire:model="purchase_account" id="purchase_account"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                                        <option value="">Select Purchase Account</option>
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}">{{ $account->account_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('purchase_account')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Inventory Account (Right Column) -->
                                <div class="w-full px-2.5 xl:w-1/2">
                                    <label
                                        class="mb-1.5 block text-xs font-medium text-gray-700 dark:text-gray-400">Inventory
                                        Account<span class="text-error-500">*</span></label>
                                    <select wire:model="inventory_account" id="inventory_account"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                                        <option value="">Select Inventory Account</option>
                                        <option value="">Inventory Account</option>
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}">{{ $account->account_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('inventory_account')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            @elseif($item_type == 'FG')
                                <!-- Show MRP and Sales Price -->
                                <div class="w-full px-2.5 xl:w-1/2">
                                    <label
                                        class="mb-1.5 block text-xs font-medium text-gray-700 dark:text-gray-400">MRP</label>
                                    <input type="number" wire:model="mrp" id="mrp" step="0.01"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                                </div>
                                <div class="w-full px-2.5 xl:w-1/2">
                                    <label
                                        class="mb-1.5 block text-xs font-medium text-gray-700 dark:text-gray-400">Sales
                                        Price</label>
                                    <input type="number" wire:model="sales_price" id="sales_price" step="0.01"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                                </div>
                                <!-- Sales Account (Right Column) -->
                                <div class="w-full px-2.5 xl:w-1/2">
                                    <label
                                        class="mb-1.5 block text-xs font-medium text-gray-700 dark:text-gray-400">Sales
                                        Account</label>
                                    <select wire:model="sales_account" id="sales_account"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                                        <option value="">Select Sales Account</option>
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}">{{ $account->account_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('sales_account')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <!-- Inventory Account (Right Column) -->
                                <div class="w-full px-2.5 xl:w-1/2">
                                    <label
                                        class="mb-1.5 block text-xs font-medium text-gray-700 dark:text-gray-400">Inventory
                                        Account</label>
                                    <select wire:model="inventory_account" id="inventory_account"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                                        <option value="">Select Inventory Account</option>
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}">{{ $account->account_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('inventory_account')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            @elseif($item_type == 'SR')
                                <!-- Show MRP and Sales Price only -->
                                <div class="w-full px-2.5 xl:w-1/2">
                                    <label
                                        class="mb-1.5 block text-xs font-medium text-gray-700 dark:text-gray-400">MRP</label>
                                    <input type="number" wire:model="mrp" id="mrp" step="0.01"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                                </div>
                                <div class="w-full px-2.5 xl:w-1/2">
                                    <label
                                        class="mb-1.5 block text-xs font-medium text-gray-700 dark:text-gray-400">Sales
                                        Price</label>
                                    <input type="number" wire:model="sales_price" id="sales_price" step="0.01"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                                </div>
                                <!-- Sales Account (Right Column) -->
                                <div class="w-full px-2.5 xl:w-1/2">
                                    <label
                                        class="mb-1.5 block text-xs font-medium text-gray-700 dark:text-gray-400">Sales
                                        Account</label>
                                    <select wire:model="sales_account" id="sales_account"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                                        <option value="">Select Sales Account</option>
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}">{{ $account->account_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('sales_account')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            @elseif($item_type == 'PR')
                                <!-- Show MRP and Purchase Price only -->
                                <div class="w-full px-2.5 xl:w-1/2">
                                    <label
                                        class="mb-1.5 block text-xs font-medium text-gray-700 dark:text-gray-400">MRP</label>
                                    <input type="number" wire:model="mrp" id="mrp" step="0.01"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                                </div>
                                <div class="w-full px-2.5 xl:w-1/2">
                                    <label
                                        class="mb-1.5 block text-xs font-medium text-gray-700 dark:text-gray-400">Purchase
                                        Price</label>
                                    <input type="number" wire:model="purchase_price" id="purchase_price"
                                        step="0.01"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                                </div>


                                <!-- Sales Account (Right Column) -->
                                <div class="w-full px-2.5 xl:w-1/2">
                                    <label
                                        class="mb-1.5 block text-xs font-medium text-gray-700 dark:text-gray-400">Sales
                                        Account</label>
                                    <select wire:model="sales_account" id="sales_account"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                                        <option value="">Select Sales Account</option>
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}">{{ $account->account_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('sales_account')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>


                                <!-- Purchase Account (Right Column) -->
                                <div class="w-full px-2.5 xl:w-1/2">
                                    <label
                                        class="mb-1.5 block text-xs font-medium text-gray-700 dark:text-gray-400">Purchase
                                        Account</label>
                                    <select wire:model="purchase_account" id="purchase_account"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                                        <option value="">Select Purchase Account</option>
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}">{{ $account->account_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('purchase_account')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Inventory Account (Right Column) -->
                                <div class="w-full px-2.5 xl:w-1/2">
                                    <label
                                        class="mb-1.5 block text-xs font-medium text-gray-700 dark:text-gray-400">Inventory
                                        Account</label>
                                    <select wire:model="inventory_account" id="inventory_account"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                                        <option value="">Select Inventory Account</option>
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}">{{ $account->account_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('inventory_account')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>


                            @endif
                            <div x-data="{ checkboxToggle: false }" class="w-full px-2.5 xl:w-1/2 mt-7">
                                <label for="checkboxLabelOne"
                                    class="flex cursor-pointer items-center text-xs font-medium text-gray-700 select-none dark:text-gray-400">
                                    <div class="relative">
                                        <input wire:model='returnable' type="checkbox" id="checkboxLabelOne"
                                            class="sr-only" @change="checkboxToggle = !checkboxToggle" />
                                        <div :class="checkboxToggle ? 'border-brand-500 bg-brand-500' :
                                            'bg-transparent border-gray-300 dark:border-gray-700'"
                                            class="f hover:border-brand-500 dark:hover:border-brand-500 mr-3 flex h-5 w-5 items-center justify-center rounded-md border-[1.25px]">
                                            <span :class="checkboxToggle ? '' : 'opacity-0'">
                                                <svg width="14" height="14" viewBox="0 0 14 14"
                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M11.6666 3.5L5.24992 9.91667L2.33325 7" stroke="white"
                                                        stroke-width="1.94437" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                            </span>
                                        </div>
                                    </div>
                                    Returnable
                                </label>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <div class="">
                    <div class="px-5 py-4 sm:px-6 sm:py-5">
                        <h3 class="pl-2 text-base font-medium text-gray-800 dark:text-white/90">
                            Additional Item Info
                        </h3>
                    </div>

                    <div class="p-5 space-y-6 border-t border-gray-100 dark:border-gray-800 sm:p-6">
                        <div class="flex flex-wrap gap-y-5">

                            <!-- Sales Tax (Right Column) -->
                            <div class="w-full px-2.5 xl:w-1/2">
                                <label class="mb-1.5 block text-xs font-medium text-gray-700 dark:text-gray-400">Sales
                                    Tax</label>
                                <input type="text" {{ $isView ? 'disabled' : '' }} wire:model="sales_tax"
                                    id="sales_tax" placeholder="Sales Tax"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                                @error('sales_tax')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Purchase Tax (Right Column) -->
                            <div class="w-full px-2.5 xl:w-1/2">
                                <label
                                    class="mb-1.5 block text-xs font-medium text-gray-700 dark:text-gray-400">Purchase
                                    Tax</label>
                                <input type="text" {{ $isView ? 'disabled' : '' }} wire:model="purchase_tax"
                                    id="sales_tax" placeholder="Purchase Tax"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                                @error('purchase_tax')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Preferred Vendor (Right Column) -->
                            <div class="w-full px-2.5 xl:w-1/2">
                                <label
                                    class="mb-1.5 block text-xs font-medium text-gray-700 dark:text-gray-400">Preferred
                                    Vendor</label>
                                <select wire:model="preferred_vendor" id="preferred_vendor"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                                    <option value="">Select Vendor</option>
                                    @foreach ($vendors as $vendor)
                                        <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                    @endforeach
                                </select>
                                @error('preferred_vendor')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Short Description (Left Column) -->
                            <div class="w-full px-2.5 xl:w-1/2">
                                <label class="mb-1.5 block text-xs font-medium text-gray-700 dark:text-gray-400">Short
                                    Description</label>
                                <input type="text" {{ $isView ? 'disabled' : '' }}
                                    wire:model="item_short_description" id="item_short_description"
                                    placeholder="Short Description"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                                @error('item_short_description')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Short Description (Left Column) -->
                            <div class="w-full px-2.5 xl:w-1/2">
                                <label
                                    class="mb-1.5 block text-xs font-medium text-gray-700 dark:text-gray-400">Description</label>
                                <input type="text" {{ $isView ? 'disabled' : '' }} wire:model="item_description"
                                    id="item_description" placeholder="Description"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                                @error('item_description')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Unit of Measure (Left Column) -->
                            <div class="w-full px-2.5 xl:w-1/2">
                                <label class="mb-1.5 block text-xs font-medium text-gray-700 dark:text-gray-400">UOM
                                    (Unit of Measure)</label>

                                <select wire:model="uom" id="uom"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                                    <option value="">Select UOM </option>
                                    @foreach ($uoms as $uom)
                                        <option value="{{ $uom->id }}">{{ $uom->abbreviation }}</option>
                                    @endforeach
                                </select>

                                @error('uom')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                    </div>

                </div>


                <!-- Save Button inside white card -->
                <div class="w-full px-5 pt-2 pb-4  border-gray-100 dark:border-gray-800 flex justify-end">
                    <button
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-md text-xs shadow-md transition duration-300 ease-in-out transform hover:scale-105"
                        style="background-color:#465FFF;">
                        {{ $item ? 'Update' : 'Save'}}

                    </button>

                </div>


            </div>

        </div>


        <!-- Full-width Button Area -->

    </form>
</div>
