<div class="p-4">
    <!-- Button to Open Modal -->
    <div class="text-right mb-4">
        <button wire:click="$set('isModalOpen', true)" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Filter by Item
        </button>
    </div>

    <!-- Modal (Dummy UI) -->


    <div x-show="open" x-transition @closeModal.window="open = false" class="fixed inset-0 bg-gray-50 bg-opacity-80 z-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full border border-gray-200 pt-4 dark:border-gray-800 dark:bg-gray-900">
            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100">Filter by Item</h3>

            <div class="mt-4">
                <form wire:submit.prevent="searchItem">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Item Name</label>
                        <input type="text" wire:model.lazy="searchTerm" placeholder="Enter item name"
                            class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-500 dark:bg-gray-800 dark:text-white dark:border-gray-600" />
                    </div>

                    <div class="flex justify-end gap-4 mt-6">
                        <button type="button" @click="open = false"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-6 py-2 rounded-md dark:bg-gray-700 dark:text-white">
                            Cancel
                        </button>

                        <button type="submit" @click="open = false"
                            class="bg-brand-500 hover:bg-brand-600 text-white font-semibold px-6 py-2 rounded-md">
                            Search
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Dummy Table -->
    <div class="bg-white dark:bg-gray-800 rounded shadow p-4">
        <table class="min-w-full table-auto border border-gray-300 dark:border-gray-700">
            <thead>
                <tr class="bg-gray-100 dark:bg-gray-700">
                    <th class="px-4 py-2 border">Branch</th>
                    <th class="px-4 py-2 border">Item Name</th>
                    <th class="px-4 py-2 border">Quantity</th>
                </tr>
            </thead>
            <tbody>
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-4 py-2 border">Colombo</td>
                    <td class="px-4 py-2 border">Sample Item</td>
                    <td class="px-4 py-2 border">50</td>
                </tr>
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-4 py-2 border">Kandy</td>
                    <td class="px-4 py-2 border">Sample Item</td>
                    <td class="px-4 py-2 border">30</td>
                </tr>
            </tbody>
        </table>

        <!-- Buttons Bottom Right -->
        <div class="flex justify-end mt-4 space-x-2">
            <button class="bg-gray-400 text-white px-3 py-1 rounded hover:bg-gray-500">Back</button>
            <button class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Reset Filter</button>
        </div>
    </div>
</div>
