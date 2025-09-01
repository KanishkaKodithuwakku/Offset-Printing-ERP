<div class="p-6 flex justify-start">
    <form wire:submit.prevent="submit" class="w-full max-w-3xl rounded-2xl border border-gray-200 bg-white shadow-sm" style="width: 50%;">

        <!-- Header -->
        <div class="px-6 py-5 border-b border-gray-300">
            <h2 class="text-lg font-semibold text-gray-800">Add Account Ledger</h2>
        </div>

        <!-- Form Body -->
        <div class="px-6 py-6 space-y-6">

            <!-- Ledger Name -->
            <div>
                <label for="name" class="block mb-1 text-xs font-medium text-gray-700">Ledger name <span class="text-error-500">*</span></label>
                <input type="text" wire:model="name" id="name" placeholder="Ledger name"
                    class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-1 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 shadow-sm" />
                @error('name')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Ledger Code -->
            <div>
                <label for="code" class="block mb-1 text-xs font-medium text-gray-700">Ledger code (optional)</label>
                <input type="text" wire:model="code" id="code" placeholder="Ledger code"
                    class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-1 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 shadow-sm" />
            </div>

            <!-- Parent Group -->
            <div>
                {{-- < livewire:components.select-ledger-dropdown  /> --}}

                   <div>
                <label for="group_id" class="block mb-1 text-xs font-medium text-gray-700">Parent group</label>
                <select wire:model="group_id" id="group_id"
                    class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-1 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 shadow-sm">
                    <option value="">Select a group</option>
                    @foreach ($groups as $group)
                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                    @endforeach
                </select>
                @error('group_id')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
                @error('group_id')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Opening Balance and Dr/Cr -->
            <div class="flex space-x-4 gap-5">
                <div class="w-1/5">
                    <label for="op_balance_dc" class="block mb-1 text-xs font-medium text-gray-700">Dr / Cr</label>
                    <select wire:model="op_balance_dc" id="op_balance_dc"
                        class="w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 shadow-sm">
                        <option value="D">Dr</option>
                        <option value="C">Cr</option>
                    </select>
                </div>
                <div class="flex-1">
                    <label for="op_balance" class="block mb-1 text-xs font-medium text-gray-700">Opening Balance</label>
                    <input type="number" wire:model="op_balance" id="op_balance" step="0.01" placeholder="0.00"
                        class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-1 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 shadow-sm" />
                </div>
            </div>

            <!-- Bank or Cash Account -->
            <div>
                <label class="inline-flex items-center text-sm text-gray-700">
                    <input type="checkbox" wire:model="type" value="1" class="mr-2 rounded border-gray-300 text-brand-600 focus:ring-brand-500" />
                    Bank or Cash Account
                </label>
                <p class="text-xs text-gray-500 mt-1">Note: Select if this ledger is a bank or cash account.</p>
            </div>

            <!-- Reconciliation -->
            <div>
                <label class="inline-flex items-center text-sm text-gray-700">
                    <input type="checkbox" wire:model="reconciliation" class="mr-2 rounded border-gray-300 text-brand-600 focus:ring-brand-500" />
                    Reconciliation
                </label>
                <p class="text-xs text-gray-500 mt-1">Note: Enables reconciliation feature from Reports.</p>
            </div>

            <!-- Notes -->
            <div>
                <label for="notes" class="block mb-1 text-xs font-medium text-gray-700">Notes</label>
                <textarea wire:model="notes" id="notes" rows="3" placeholder="Additional notes"
                    class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 shadow-sm resize-none"></textarea>
            </div>

            <!-- Buttons -->
            <div class="flex space-x-4 gap-5">
                <button type="submit"
                    class="bg-brand-500 hover:bg-brand-600 text-white font-medium px-6 py-2 rounded-md text-sm shadow-md transition duration-300 ease-in-out transform hover:scale-105">
                    Submit
                </button>
                <a wire:navigate href="{{ route('accounts.chart') }}"
                    class="inline-flex items-center justify-center px-6 py-2 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-100 transition">
                    Cancel
                </a>
            </div>

        </div>
    </form>
</div>
