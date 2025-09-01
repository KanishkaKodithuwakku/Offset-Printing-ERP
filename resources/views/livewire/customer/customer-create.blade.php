<div class="p-4 flex justify-left">
    <form wire:submit.prevent="saveCustomer" class="w-full sm:w-4/6 md:w-3/6 lg:w-2/6">
        <div
            class="w-full max-w-4xl rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] grid grid-cols-1 sm:grid-cols-2">

            <!-- Left Column -->
            <div class=" border-gray-100 dark:border-gray-800">
                <div class="px-5 py-4 sm:px-6 sm:py-5">
                    <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                        {{ $isView ? 'View' : ($customer ? 'Edit' : 'Create') }} Customer
                    </h3>
                </div>
                <div class="p-5 border-t space-y-6 sm:p-6">
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
                    <div class="grid grid-cols-2 gap-5">
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
            </div>

            <!-- Right Column -->
            <div>
                <div class="px-5 py-4 sm:px-6 sm:py-5">
                    <div class="h-6"></div> <!-- Spacer -->
                </div>
                <div class="p-5 border-t space-y-6 sm:p-6">

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
                    <div class="flex col-flex gap-5">
                        <!-- City -->
                        <div class="w-full">
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
                        <div class="w-full">
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

                <div class=" px-6 py-4  flex justify-end">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-md text-sm shadow-md transition duration-300 ease-in-out transform hover:scale-105"
                        style="background-color:#465FFF; padding: 8px 15px;">
                        Save Customer
                    </button>
                </div>
            </div>


        </div>

        <!-- Submit Button -->

    </form>
</div>
