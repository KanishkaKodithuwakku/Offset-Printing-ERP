<div class="p-2 mx-auto max-w-screen-2xl md:p-4">
    <div class="space-y-5 sm:space-y-6">
        <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="mb-4 flex flex-col gap-2 px-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    Edit Supplier
                </h3>
            </div>

            <form wire:submit.prevent="saveSupplier">
                <div class="space-y-6 border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800">
                    <!-- Name -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Name
                        </label>
                        <input type="text" wire:model="name" id="name" placeholder="Supplier Name"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 
                                   dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent 
                                   px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 
                                   focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 
                                   dark:placeholder:text-white/30" />
                        @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Email
                        </label>
                        <input type="email" wire:model="email" id="email" placeholder="Email Address"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 
                                   dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent 
                                   px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 
                                   focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 
                                   dark:placeholder:text-white/30" />
                        @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Phone
                        </label>
                        <input type="text" wire:model="phone" id="phone" placeholder="Phone Number"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 
                                   dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent 
                                   px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 
                                   focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 
                                   dark:placeholder:text-white/30" />
                        @error('phone') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Address -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Address
                        </label>
                        <input type="text" wire:model="address" id="address" placeholder="Street Address"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 
                                   dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent 
                                   px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 
                                   focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 
                                   dark:placeholder:text-white/30" />
                        @error('address') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Status
                        </label>
                        <select wire:model="status" id="status"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 
                                   dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent 
                                   px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 
                                   focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 
                                   dark:placeholder:text-white/30">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        @error('status') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="bg-gray-100 dark:bg-white/[0.03] px-6 py-4 border-t flex justify-end dark:border-gray-800">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-md text-sm 
                               shadow-md transition duration-300 ease-in-out transform hover:scale-105">
                        Update Supplier
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
