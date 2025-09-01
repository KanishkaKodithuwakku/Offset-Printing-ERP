<div class="rounded-2xl border border-gray-200 p-6 bg-white dark:border-gray-800 dark:bg-white/[0.03] px-4">
    @if (session()->has('success'))
        <div class="text-success-600">{{ session('success') }}</div>
    @endif
    @if (session()->has('error'))
        <div class="text-error-600">{{ session('error') }}</div>
    @endif
    <table class="table-auto w-full  mt-2">
        <thead class="bg-white text-gray-600 tex  ">
            <tr>
                <th class="text-sm text-left font-medium" style="padding-left: 10px;">Dr/Cr</th>
                <th class="text-sm text-left font-medium">Ledger</th>
                <th class="text-sm text-left font-medium" style="padding-left: 10px;">Dr Amount (Rs)</th>
                <th class="text-sm text-left font-medium" style="padding-left: 8px;">Cr Amount (Rs)</th>
                <th class="text-sm text-left font-medium">Actions</th>
                <th class="text-sm text-left font-medium">Cur Balance (Rs)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $index => $item)
                <tr>
                    <td class="py-2">
                        <div class="w-full px-2.5">
                            <div class="relative z-20 bg-transparent">
                                <select wire:model="items.{{ $index }}.dc" wire:change="$refresh"
                                    style="width: 50px;"
                                    class="dark:bg-dark-900 z-20  appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-1 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
                                    <option value="dr" class="text-gray-500 dark:bg-gray-900 dark:text-gray-400">
                                        Dr
                                    </option>
                                    <option value="cr" class="text-gray-500 dark:bg-gray-900 dark:text-gray-400">
                                        Cr
                                    </option>
                                </select>

                            </div>
                        </div>

                    </td>
                    <td class="py-2">
                        <select wire:model="items.{{ $index }}.ledger_id" wire:change="$refresh"
                            class="dark:bg-dark-900 z-20  appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-1.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
                            <option value="">Please select</option>
                            @foreach ($ledgers as $ledger)
                                <option value="{{ $ledger->id }}">[L{{ $ledger->code }}] {{ $ledger->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="px-2">
                        <input type="number" step="0.01"
                            class=" w-full rounded-lg border border-gray-300 px-4 py-1.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:outline-none focus:ring-brand-500/10
            dark:bg-dark-900 dark:border-gray-700 dark:text-white/90 dark:placeholder:text-white/30
            {{ $item['dc'] === 'cr' || !$item['ledger_id'] ? 'bg-gray-100 cursor-not-allowed' : 'bg-white' }}"
                            wire:model.lazy="items.{{ $index }}.dr_amount"
                            @if ($item['dc'] === 'cr' || !$item['ledger_id']) disabled @endif>
                    </td>
                    <td class="px-2">
                        <input type="number" step="0.01"
                            class=" w-full rounded-lg border border-gray-300 px-4 py-1.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:outline-none focus:ring-brand-500/10
            dark:bg-dark-900 dark:border-gray-700 dark:text-white/90 dark:placeholder:text-white/30
            {{ $item['dc'] === 'dr' || !$item['ledger_id'] ? 'bg-gray-100 cursor-not-allowed' : 'bg-white' }}"
                            wire:model.lazy="items.{{ $index }}.cr_amount"
                            @if ($item['dc'] === 'dr' || !$item['ledger_id']) disabled @endif>
                    </td>
                    <td class="px-1">
                        <button wire:click.prevent="addRow" class="text-success-600"><svg
                                class="w-5 h-5  dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 7.757v8.486M7.757 12h8.486M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </button>
                        <button wire:click.prevent="removeRow({{ $index }})" class="text-error-600"><svg
                                class="w-5 h-5  dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M7.757 12h8.486M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </button>
                    </td>
                    <td class="py-2">
                        @php
                            $ledger = collect($ledgers)->firstWhere('id', $item['ledger_id']);
                        @endphp
                        @if ($ledger)
                            {{ $ledger->op_balance_dc }} {{ number_format($ledger->op_balance, 2) }}
                        @endif
                    </td>
                </tr>
            @endforeach

            <tr class="font-bold bg-yellow-100">
                <td class="px-2 " colspan="2" style="padding-left: 12px;">Total</td>
                <td class="px-2">{{ number_format($this->totalDr, 2) }}</td>
                <td class="px-2">{{ number_format($this->totalCr, 2) }}</td>
                <td class="px-2"></td>
                <td class="px-2"></td>
            </tr>
            <tr>
                <td class="px-2" colspan="2" style="padding-left: 12px;">Difference</td>
                <td class="px-2" colspan="4">
                    @php $diff = $this->totalDr - $this->totalCr; @endphp
                    {{ $diff == 0 ? '-' : number_format($diff, 2) }}
                </td>
            </tr>
        </tbody>
    </table>

    <div class="mt-6 ml-3" style="width:85%;">
        <label for="narration" class="block text-sm font-medium text-gray-700 mb-1">Narration</label>
        <textarea id="narration" name="narration" rows="2" wire:model="narration"
            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:outline-none focus:ring-brand-500/10 dark:bg-dark-900 dark:border-gray-700 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"
            placeholder="Enter narration..."></textarea>
    </div>

    <div class="flex justify-end">
        <div class="mt-4">
            <button id="back-button" onclick="window.history.back();"
                style="display: flex; align-items: center; font-family: outfit; gap: 0.5rem; padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; color: white; border-radius: 0.375rem; background-color: #6b7280; box-shadow: 0 2px 2px rgba(0, 0, 0, 0.1);">
                <svg style="width: 1.5rem; height: 1.5rem; color: white;" fill="none" stroke="currentColor"
                    stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back
            </button>
        </div>

        <div class="mt-4 px-2">
            <button wire:click="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-3 rounded-md text-xs shadow-md transition duration-300 ease-in-out transform hover:scale-105"
            style="background-color:#465FFF;">Submit</button>
        </div>
    </div>

</div>
