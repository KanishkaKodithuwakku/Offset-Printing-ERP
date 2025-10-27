<div class="p-4 flex justify-left">
    <form wire:submit.prevent="saveCustomer" class="w-full sm:w-4/6 md:w-3/6 lg:w-2/6">
        <div class="w-full max-w-4xl rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">

            <!-- Header -->
            <div class="px-5 py-4 sm:px-6 sm:py-5 border-b border-gray-200 dark:border-gray-800">
                <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                    {{ $isView ? 'View' : ($customer ? 'Edit' : 'Create') }} Customer
                </h3>
            </div>

            <!-- Tab Navigation -->
            <div class="border-b border-gray-200 dark:border-gray-800">
                <nav class="-mb-px flex gap-5 space-x-8 px-6" aria-label="Tabs">
                    <button type="button" onclick="switchTab('general')" id="general-tab"
                        class="tab-button active py-4 px-1 border-b-2 font-medium text-sm border-brand-500 text-brand-500 dark:text-brand-400">
                        General
                    </button>
                    <button type="button" onclick="switchTab('credit')" id="credit-tab"
                        class="tab-button py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300">
                        Credit Limit & Period
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                <!-- General Tab Content -->
                <div id="general-content" class="tab-content">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Left Column -->
                        <div class="space-y-6">
                            <!-- Name -->
                            <div>
                                <label class="mb-2 block text-xs font-medium text-gray-700 dark:text-gray-400">
                                    Name<span class="text-error-500">*</span>
                                </label>
                                <input type="text" wire:model="name" placeholder="Customer Name"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                                @error('name')
                                    <p class="text-xs text-error-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="mb-2 block text-xs font-medium text-gray-700 dark:text-gray-400">
                                    Email
                                </label>
                                <input type="email" wire:model="email" placeholder="Email Address"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                                @error('email')
                                    <p class="text-xs text-error-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <!-- Phone -->
                                <div>
                                    <label class="mb-2 block text-xs font-medium text-gray-700 dark:text-gray-400">
                                        Phone
                                    </label>
                                    <input type="text" wire:model="phone" placeholder="Phone Number"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                                    @error('phone')
                                        <p class="text-xs text-error-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Mobile Number -->
                                <div>
                                    <label class="mb-2 block text-xs font-medium text-gray-700 dark:text-gray-400">
                                        Mobile Number
                                    </label>
                                    <input type="text" wire:model="mobile_number" placeholder="Mobile Number"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                                    @error('mobile_number')
                                        <p class="text-xs text-error-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-6">
                            <!-- Address -->
                            <div>
                                <label class="mb-2 block text-xs font-medium text-gray-700 dark:text-gray-400">
                                    Address
                                </label>
                                <input type="text" wire:model="address" placeholder="Street Address"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                                @error('address')
                                    <p class="text-xs text-error-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <!-- City -->
                                <div>
                                    <label class="mb-2 block text-xs font-medium text-gray-700 dark:text-gray-400">
                                        City
                                    </label>
                                    <input type="text" wire:model="city" placeholder="City"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                                    @error('city')
                                        <p class="text-xs text-error-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Country -->
                                <div>
                                    <label class="mb-2 block text-xs font-medium text-gray-700 dark:text-gray-400">
                                        Country
                                    </label>
                                    <input type="text" wire:model="country" placeholder="Country"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                                    @error('country')
                                        <p class="text-xs text-error-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Status -->
                            <div>
                                <label class="mb-2 block text-xs font-medium text-gray-700 dark:text-gray-400">
                                    Status
                                </label>
                                <select wire:model="status"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                                @error('status')
                                    <p class="text-xs text-error-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Credit Limit & Period Tab Content -->
                <div id="credit-content" class="tab-content hidden">
                    <div class="space-y-8" wire:ignore.self>
                        <!-- Customer Type Selection -->
                        <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-6" wire:ignore>
                            <h4 class="text-sm font-medium text-gray-800 dark:text-white/90 mb-4">Customer Type</h4>
                            <div class="flex gap-6">
                                <label class="flex items-center">
                                    <input type="radio" wire:model.live="customer_type" value="credit"
                                        class="form-radio h-4 w-4 text-brand-600 border-gray-300 focus:ring-brand-500" />
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Credit Customer</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" wire:model.live="customer_type" value="cash"
                                        class="form-radio h-4 w-4 text-brand-600 border-gray-300 focus:ring-brand-500" />
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Cash Customer</span>
                                </label>
                            </div>
                        </div>

                        <div wire:key="credit-limit-section" class="space-y-6">
                        @if($customer_type === 'credit')
                        <!-- Credit Limit 1 -->
                        <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-6">
                            <h4 class="text-sm font-medium text-gray-800 dark:text-white/90 mb-4">Credit Limit 1</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label class="mb-2 block text-xs font-medium text-gray-700 dark:text-gray-400">
                                        Credit Days
                                    </label>
                                    <input type="number" wire:model="credit_limit_1_days" placeholder="Credit Days"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                                    @error('credit_limit_1_days')
                                        <p class="text-xs text-error-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="mb-2 block text-xs font-medium text-gray-700 dark:text-gray-400">
                                        Credit Limit Amount
                                    </label>
                                    <input type="number" step="0.01" wire:model="credit_limit_1_amount" placeholder="Credit Amount"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                                    @error('credit_limit_1_amount')
                                        <p class="text-xs text-error-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Credit Limit 2 -->
                        <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-6">
                            <h4 class="text-sm font-medium text-gray-800 dark:text-white/90 mb-4">Credit Limit 2</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label class="mb-2 block text-xs font-medium text-gray-700 dark:text-gray-400">
                                        Credit Days
                                    </label>
                                    <input type="number" wire:model="credit_limit_2_days" placeholder="Credit Days"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                                    @error('credit_limit_2_days')
                                        <p class="text-xs text-error-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="mb-2 block text-xs font-medium text-gray-700 dark:text-gray-400">
                                        Credit Limit Amount
                                    </label>
                                    <input type="number" step="0.01" wire:model="credit_limit_2_amount" placeholder="Credit Amount"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                                    @error('credit_limit_2_amount')
                                        <p class="text-xs text-error-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800 flex justify-end">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-md text-sm shadow-md transition duration-300 ease-in-out transform hover:scale-105"
                    style="background-color:#465FFF; padding: 8px 15px;">
                    Save Customer
                </button>
            </div>
        </div>
    </form>
</div>

<script>
function switchTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });

    // Remove active class from all tabs
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active', 'border-brand-500', 'text-brand-500', 'dark:text-brand-400');
        button.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300', 'dark:text-gray-400', 'dark:hover:text-gray-300');
    });

    // Show selected tab content
    document.getElementById(tabName + '-content').classList.remove('hidden');

    // Add active class to selected tab
    const activeTab = document.getElementById(tabName + '-tab');
    activeTab.classList.add('active', 'border-brand-500', 'text-brand-500', 'dark:text-brand-400');
    activeTab.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300', 'dark:text-gray-400', 'dark:hover:text-gray-300');
}
</script>
