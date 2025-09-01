<div class="p-4 flex justify-left">
    <form wire:submit.prevent="saveSupplier" class="w-full sm:w-4/6 md:w-3/6 lg:w-2/6">
        <div
            class="w-full max-w-4xl rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] grid grid-cols-1 gap-0 sm:grid-cols-2">
            <!-- Left Column -->
            <div class="space-y-0 border-gray-100 dark:border-gray-800">
                <div class="px-5 py-4 sm:px-6 sm:py-5">
                    <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Create Supplier</h3>
                </div>
                <div class="p-5 border-t space-y-6 sm:p-6">
                    <!-- Address Info Section -->
                    <div class="w-full">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">Address Info</h4>

                        <!-- Company Name -->
                        <div class="w-full mb-4">
                            <label class="mb-2 block text-xs font-medium text-gray-700 dark:text-gray-400">
                                Company Name<span class="text-error-500">*</span>
                            </label>
                            <input type="text" wire:model="company_name" placeholder="Company Name"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                            @error('company_name')
                                <p class="text-xs text-error-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Name with Title Selection -->
                        <div class="w-full mb-4">
                            <label class="mb-2 block text-xs font-medium text-gray-700 dark:text-gray-400">
                                Full Name<span class="text-error-500">*</span>
                            </label>
                            <div class="flex gap-2">
                                <select wire:model="name_title"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-1/4 rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                                    <option value="Mr.">Mr. </option>
                                    <option value="Ms.">Ms. </option>
                                    <option value="Mrs.">Mrs. &nbsp &nbsp </option>
                                </select>
                                <input type="text" wire:model="name" placeholder="Full Name"
                                    class="w-full dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-3/4 rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                            </div>
                            @error('name')
                                <p class="text-xs text-error-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Job Title -->
                        <div class="w-full mb-4">
                            <label class="mb-2 block text-xs font-medium text-gray-700 dark:text-gray-400">
                                Job Title
                            </label>
                            <input type="text" wire:model="job_title" placeholder="Job Title"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                            @error('job_title')
                                <p class="text-xs text-error-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="w-full mb-4">
                            <label class="mb-2 block text-xs font-medium text-gray-700 dark:text-gray-400">
                                Email<span class="text-error-500">*</span>
                            </label>
                            <input type="email" wire:model="email" placeholder="Email Address"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                            @error('email')
                                <p class="text-xs text-error-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div class="w-full mb-4">
                            <label class="mb-2 block text-xs font-medium text-gray-700 dark:text-gray-400">
                                Address
                            </label>
                            <input type="text" wire:model="address" placeholder="Street Address"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                            @error('address')
                                <p class="text-xs text-error-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Payment Info Section -->
                    <div class="w-full mt-6">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">Payment Info</h4>


                        <div class="flex gap-4">
                            <div class="w-full  mb-4">
                                <label class="mb-2 block text-xs font-medium text-gray-700 dark:text-gray-400">
                                    Opening Balance
                                </label>
                                <input type="number" step="0.01" wire:model="opening_balance" placeholder="0.00"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                                @error('opening_balance')
                                    <p class="text-xs text-error-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="w-full  mb-4">
                                <label class="mb-2 block text-xs font-medium text-gray-700 dark:text-gray-400">
                                    As Of Date
                                </label>
                                <div class="relative">
                                    <input type="date" wire:model="as_of_date" onclick="this.showPicker()"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                                    <span
                                        class="pointer-events-none absolute top-1/2 right-3 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                                        <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20"
                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M6.66659 1.5415C7.0808 1.5415 7.41658 1.87729 7.41658 2.2915V2.99984H12.5833V2.2915C12.5833 1.87729 12.919 1.5415 13.3333 1.5415C13.7475 1.5415 14.0833 1.87729 14.0833 2.2915V2.99984L15.4166 2.99984C16.5212 2.99984 17.4166 3.89527 17.4166 4.99984V7.49984V15.8332C17.4166 16.9377 16.5212 17.8332 15.4166 17.8332H4.58325C3.47868 17.8332 2.58325 16.9377 2.58325 15.8332V7.49984V4.99984C2.58325 3.89527 3.47868 2.99984 4.58325 2.99984L5.91659 2.99984V2.2915C5.91659 1.87729 6.25237 1.5415 6.66659 1.5415ZM6.66659 4.49984H4.58325C4.30711 4.49984 4.08325 4.7237 4.08325 4.99984V6.74984H15.9166V4.99984C15.9166 4.7237 15.6927 4.49984 15.4166 4.49984H13.3333H6.66659ZM15.9166 8.24984H4.08325V15.8332C4.08325 16.1093 4.30711 16.3332 4.58325 16.3332H15.4166C15.6927 16.3332 15.9166 16.1093 15.9166 15.8332V8.24984Z"
                                                fill="" />
                                        </svg>
                                    </span>
                                    @error('as_of_date')
                                        <p class="text-xs text-error-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Account No -->
                        <div class="w-full mb-4">
                            <label class="mb-2 block text-xs font-medium text-gray-700 dark:text-gray-400">
                                Account No
                            </label>
                            <input type="text" wire:model="account_no" placeholder="Account Number"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                            @error('account_no')
                                <p class="text-xs text-error-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Bank Name -->
                        <div class="w-full mb-4">
                            <label class="mb-2 block text-xs font-medium text-gray-700 dark:text-gray-400">
                                Bank Name
                            </label>
                            <input type="text" wire:model="bank_name" placeholder="Bank Name"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                            @error('bank_name')
                                <p class="text-xs text-error-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Branch Name -->
                        <div class="w-full mb-4">
                            <label class="mb-2 block text-xs font-medium text-gray-700 dark:text-gray-400">
                                Branch Name
                            </label>
                            <input type="text" wire:model="branch_name" placeholder="Branch Name"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                            @error('branch_name')
                                <p class="text-xs text-error-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-0">
                <div class="px-5 py-4 sm:px-6 sm:py-5">
                    <div class="h-6"></div> <!-- Spacer -->
                </div>
                <div class="p-5 border-t space-y-6 sm:p-6">
                    <!-- Contact Information -->
                    <div class="w-full mb-6">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">Contact Information</h4>

                        <!-- Phone -->
                        <div class="w-full mb-4">
                            <label class="mb-2 block text-xs font-medium text-gray-700 dark:text-gray-400">
                                Work Phone<span class="text-error-500">*</span>
                            </label>
                            <input type="text" wire:model="phone" placeholder="Phone Number"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                            @error('work_phone')
                                <p class="text-xs text-error-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Mobile Number -->
                        <div class="w-full mb-4">
                            <label class="mb-2 block text-xs font-medium text-gray-700 dark:text-gray-400">
                                Mobile Number
                            </label>
                            <input type="text" wire:model="mobile" placeholder="Mobile Number"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                            @error('mobile')
                                <p class="text-xs text-error-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Fax -->
                        <div class="w-full mb-4">
                            <label class="mb-2 block text-xs font-medium text-gray-700 dark:text-gray-400">
                                Fax
                            </label>
                            <input type="text" wire:model="fax" placeholder="Fax Number"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                            @error('fax')
                                <p class="text-xs text-error-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>



                    <!-- Account Settings Section -->
                    <div class="  w-full mb-6">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">Account Settings</h4>

                        <!-- Account 1 -->
                        <div class="flex gap-2">
                            <div class="w-full mb-4">
                                <label class="mb-2 block text-xs font-medium text-gray-700 dark:text-gray-400">
                                    Account 1
                                </label>
                                <select wire:model="account_1"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                                    <option value="">Select Account</option>
                                    <option value="account1">Account 1</option>
                                    <option value="account2">Account 2</option>
                                    <option value="account3">Account 3</option>
                                </select>
                                @error('account_1')
                                    <p class="text-xs text-error-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Account 2 -->
                            <div class="w-full mb-4">
                                <label class="mb-2 block text-xs font-medium text-gray-700 dark:text-gray-400">
                                    Account 2
                                </label>
                                <select wire:model="account_2"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                                    <option value="">Select Account</option>
                                    <option value="account1">Account 1</option>
                                    <option value="account2">Account 2</option>
                                    <option value="account3">Account 3</option>
                                </select>
                                @error('account_2')
                                    <p class="text-xs text-error-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Account 3 -->
                            <div class="w-full mb-4">
                                <label class="mb-2 block text-xs font-medium text-gray-700 dark:text-gray-400">
                                    Account 3
                                </label>
                                <select wire:model="account_3"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                                    <option value="">Select Account</option>
                                    <option value="account1">Account 1</option>
                                    <option value="account2">Account 2</option>
                                    <option value="account3">Account 3</option>
                                </select>
                                @error('account_3')
                                    <p class="text-xs text-error-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Additional Info Section -->
                    <div class="w-full mb-6">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">Additional Info</h4>

                        <!-- Supplier Type -->
                        <div class="w-full mb-4">
                            <label class="mb-2 block text-xs font-medium text-gray-700 dark:text-gray-400">
                                Supplier Type
                            </label>
                            <select wire:model="supplier_type"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                                <option value="">Select Type</option>
                                <option value="consultant">Consultant</option>
                                <option value="equipment">Equipment</option>
                                <option value="materials">Materials</option>
                                <option value="permits">Permits</option>
                                <option value="subcontractors">Subcontractors</option>
                                <option value="tax_agency">Tax Agency</option>
                                <option value="utility">Utility</option>
                            </select>
                            @error('supplier_type')
                                <p class="text-xs text-error-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="w-full mb-4">
                            <label class="mb-2 block text-xs font-medium text-gray-700 dark:text-gray-400">
                                Status
                            </label>
                            <select wire:model="status" id="status"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                            @error('status')
                                <p class="text-xs text-error-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end" style="padding-top :25%;">
                        <button type="submit"
                            class="bg-brand-500 hover:bg-brand-600 text-white text-xs font-medium px-4 py-2 rounded-lg transition duration-200 ease-in-out">
                            Save Supplier
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
