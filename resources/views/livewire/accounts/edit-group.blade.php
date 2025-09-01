<div class="p-4 flex justify-start">
    <form wire:submit.prevent="updateGroup" class="w-full max-w-4xl rounded-2xl border border-gray-200 bg-white p-6" style="width: 50%;">

        <h2 class="text-lg font-semibold mb-6">Add Account Group</h2>

        <!-- Group Name -->
        <div class="mb-6">
            <label for="name" class="block mb-1 text-xs font-medium text-gray-700">Group name <span class="text-error-500">*</span></label>
            <input type="text" wire:model.defer="name" id="name" placeholder="Group Name"
                class="w-full h-8 rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-none shadow-theme-xs" />
            @error('name')
                <p class="text-error-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Group Code -->
        <div class="mb-6">
            <label for="code" class="block mb-1 text-xs font-medium text-gray-700">Group code (optional)</label>
            <input type="text" wire:model.defer="code" id="code" placeholder="Group Code"
                class="w-full h-8 rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-none shadow-theme-xs" />
        </div>

        <!-- Parent Group -->
        <div class="mb-6">
            <label for="parent_id" class="block mb-1 text-xs font-medium text-gray-700">Parent group</label>
            <select wire:model.defer="parent_id" id="parent_id"
                class="w-full h-8 rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-none shadow-theme-xs">
                <option value=""> None </option>
                @foreach ($parentGroups as $pg)
                    <option value="{{ $pg->id }}">{{ $pg->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Buttons -->
        <div class="flex items-center gap-4">
            <button type="submit"
                class="bg-brand-500 hover:bg-brand-600 text-white font-medium px-6 py-2 rounded-md text-xs shadow-md transition duration-300 ease-in-out transform hover:scale-105">
                Submit
            </button>
            <a href="{{ route('accounts.chart') }}"
        wire:navigate
        class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium px-6 py-2 rounded-md text-xs shadow-md transition duration-300 ease-in-out transform hover:scale-105 inline-flex items-center justify-center">
        Cancel
    </a>
        </div>

    </form>
</div>
