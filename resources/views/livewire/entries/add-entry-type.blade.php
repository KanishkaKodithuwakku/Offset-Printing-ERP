<div class="p-6 max-w-5xl mx-auto bg-white rounded-2xl border border-gray-200 shadow-sm">
    <h2 class="text-xl font-semibold mb-6 text-gray-800">
        {{ $mode === 'edit' ? 'Edit Entry Type' : 'Add Entry Type' }}
    </h2>

    @if (session()->has('success'))
        <div class="mb-4 p-3 rounded bg-green-100 text-green-700">
            {{ session('success') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="mb-4 p-3 rounded bg-red-100 text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <form wire:submit.prevent="submit" class="grid grid-cols-1 gap-6 sm:grid-cols-2">

        <!-- Left Column -->
        <div class="space-y-4">
            <!-- Label -->
            <div>
                <label for="label" class="block mb-1 text-xs font-medium text-gray-700">Label</label>
                <input type="text" wire:model="label" id="label" placeholder="Label"
                    class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-1 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 shadow-sm" />
            </div>

            <!-- Name -->
            <div>
                <label for="name" class="block mb-1 text-xs font-medium text-gray-700">Name</label>
                <input type="text" wire:model="name" id="name" placeholder="Name"
                    class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-1 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 shadow-sm" />
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block mb-1  text-xs font-medium text-gray-700">Description</label>
                <textarea wire:model="description" id="description" rows="4" placeholder="Description"
                    class="w-full  rounded-lg border border-gray-300 bg-transparent px-3 py-1 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 shadow-sm resize-none"></textarea>

            </div>

            <!-- Numbering -->
            <div>
                <label for="numbering" class="block  mb-1 text-xs font-medium text-gray-700">Numbering</label>
                <select wire:model="numbering" id="numbering"
                    class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-1 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-error-500 focus:border-brand-500 shadow-sm">
                    <option value="1">Auto</option>
                    <option value="0">Manual</option>
                </select>
                <p class="mt-1 text-xs text-gray-500">Note: How the entry numbering is handled.</p>
            </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-4">

             <!-- Prefix -->
             <div>
                <label for="prefix" class="block mb-1 text-xs font-medium text-gray-700">Prefix</label>
                <input type="text" wire:model="prefix" id="prefix" placeholder="Prefix"
                    class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-1 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 shadow-sm" />
                <p class="mt-1 text-xs text-gray-500">Note: Prefix to add before entry numbers.</p>
            </div>

            <!-- Suffix -->
            <div >
                <label for="suffix" class="block text-xs mb-1 font-medium text-gray-700">Suffix</label>
                <input type="text" wire:model="suffix" id="suffix" placeholder="Suffix"
                    class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-1 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 shadow-sm" />
                <p class="mt-1 text-xs text-gray-500">Note: Suffix to add after entry numbers.</p>
            </div>

            <!-- Zero Padding -->
            <div class="space-y-1">
                <label for="zero_padding" class="block mb-1 text-xs font-medium text-gray-700">Zero Padding</label>
                <input type="number" min="0" wire:model="zero_padding" id="zero_padding" placeholder="0"
                    class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-1 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 shadow-sm" />
                <p class="mt-1 text-xs text-gray-500">Note: Number of zeros to pad before entry numbers.</p>
            </div>

            <!-- Restrictions -->
            <div >
                <label for="restriction_bankcash"
                    class="block mb-1 text-xs font-medium text-gray-700">Restrictions</label>
                <select wire:model="restriction_bankcash" id="restriction_bankcash"
                    class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-1 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 shadow-sm">
                    <option value="0">Unrestricted</option>
                    <option value="1">At least one Bank or Cash account must be present on Debit side</option>
                    <option value="2">At least one Bank or Cash account must be present on Credit side</option>
                    <option value="3">Only Bank or Cash account can be present on both sides</option>
                    <option value="4">Only Non Bank or Non Cash account can be present on both sides</option>
                </select>
                <p class="mt-1 text-xs text-gray-500">Note: Restrictions to be placed on the ledgers selected in entry.
                </p>
            </div>
        </div>

        <!-- Buttons span full width -->
        <div class="sm:col-span-2 flex gap-4 justify-end mt-6">
            <button type="submit"
                class="bg-brand-500 hover:bg-brand-600 text-white font-medium px-6 py-1 rounded-md text-sm shadow-md transition duration-300 ease-in-out transform hover:scale-105">
                Submit
            </button>
            <a href="{{ route('entrytypes.index') }}"
                class="inline-flex items-center justify-center px-6 py-1 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-100 transition">
                Cancel
            </a>
        </div>

    </form>
</div>
