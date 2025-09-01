<div>
    <h2 class="text-xl font-semibold mb-4">Edit Entry Type</h2>

    @if (session()->has('success'))
        <div class="text-green-600 mb-4">{{ session('success') }}</div>
    @endif

    <form wire:submit.prevent="update" class="space-y-4">
        <div>
            <label class="block font-semibold">Label</label>
            <input type="text" wire:model.defer="label" class="w-full border px-3 py-2 rounded" required>
        </div>

        <div>
            <label class="block font-semibold">Name</label>
            <input type="text" wire:model.defer="name" class="w-full border px-3 py-2 rounded" required>
        </div>

        <div>
            <label class="block font-semibold">Description</label>
            <textarea wire:model.defer="description" class="w-full border px-3 py-2 rounded"></textarea>
        </div>

        <div>
            <label class="block font-semibold">Prefix</label>
            <input type="text" wire:model.defer="prefix" class="w-full border px-3 py-2 rounded">
            <p class="text-sm text-gray-500">Note: Prefix to add before entry numbers.</p>
        </div>

        <div>
            <label class="block font-semibold">Suffix</label>
            <input type="text" wire:model.defer="suffix" class="w-full border px-3 py-2 rounded">
            <p class="text-sm text-gray-500">Note: Suffix to add after entry numbers.</p>
        </div>

        <div>
            <label class="block font-semibold">Zero Padding</label>
            <input type="number" wire:model.defer="zero_padding" class="w-full border px-3 py-2 rounded">
            <p class="text-sm text-gray-500">Note: Number of zeros to pad before entry numbers.</p>
        </div>

        <div>
            <label class="block font-semibold">Restrictions</label>
            <select wire:model.defer="restriction_bankcash" class="w-full border px-3 py-2 rounded">
                <option value="0">Unrestricted</option>
                <option value="1">Atleast one Bank or Cash account must be present on Debit side</option>
                <option value="2">Atleast one Bank or Cash account must be present on Credit side</option>
                <option value="3">Only Bank or Cash account can be present on both Debit and Credit side</option>
                <option value="4">Only Non Bank or Non Cash account can be present on both Debit and Credit side</option>
            </select>
            <p class="text-sm text-gray-500">Note: Restrictions to be placed on the ledgers selected in entry.</p>
        </div>

        <div class="flex space-x-2">
            <button type="submit" class="bg-brand-500 text-white px-4 py-2 rounded">Submit</button>
            <a href="{{ route('entrytypes.index') }}" class="bg-gray-300 px-4 py-2 rounded">Cancel</a>
        </div>
    </form>
</div>
