<div class="p-4 flex justify-left">
    <form wire:submit.prevent="saveExpense" class="w-full" style="width: 60%;">
        <div
            class="w-full max-w-4xl rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] grid grid-cols-1 gap-0 sm:grid-cols-2">

            <!-- Left Column -->
            <div class="space-y-0  border-gray-100 dark:border-gray-800">
                <div class="px-5 py-4 sm:px-6 sm:py-5">
                    <h3 class=" text-base font-medium text-gray-800 dark:text-white/90">
                        {{ $expenseId ? 'Edit Other Expense' : 'Add Other Expense' }}
                    </h3>
                </div>
                <div class="p-5 border-t space-y-6 sm:p-6">
                    <!-- Expense Name -->
                    <div class="w-full">
                        <label class="mb-1.5 block text-xs font-medium text-gray-700 dark:text-gray-400">Expense Name<span class="text-error-500">*</span></label>
                        <input type="text" wire:model="expense_name" id="expense_name" placeholder="Enter expense name"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                        @error('expense_name')
                            <p class="text-error-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Price -->
                    <div class="w-full">
                        <label
                            class="mb-1.5 block text-xs font-medium text-gray-700 dark:text-gray-400">Price<span class="text-error-500">*</span></label>
                        <input type="number" wire:model="price" id="price" step="0.01" min="0" placeholder="0.00"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                        @error('price')
                            <p class="text-error-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-0 mt-6">
                <div class="px-5 py-4 sm:px-6 sm:py-5">
                    <h3 class="pl-2 text-base font-medium text-gray-800 dark:text-white/90"></h3>
                </div>
                <div class="p-5 border-t space-y-5 sm:p-6">
                    <!-- Description -->
                    <div class="w-full">
                        <label
                            class="mb-1.5 block text-xs font-medium text-gray-700 dark:text-gray-400">Description</label>
                        <textarea wire:model="description" id="description" placeholder="Enter description (optional)"
                            class=" dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-0.5 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" style="height: 55px;"></textarea>
                        @error('description')
                            <p class="text-error-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Save Button -->
                    <div class="pt-2 pb-1 flex justify-end">
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-md text-xs shadow-md transition duration-300 ease-in-out transform hover:scale-105"
                            style="background-color:#465FFF;">
                            Save
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>
