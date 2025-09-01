<div class="max-w-5xl mx-auto p-6 bg-white rounded-2xl border border-gray-200 shadow-sm">

    <h4 class="text-lg font-semibold mb-6">
        Ledger Reconciliation Report

    </h4>

    <form wire:submit.prevent="submit" class="space-y-4">
        <!-- Ledger Select -->

        <div style="max-width: 25%;">
            <livewire:components.select-ledger-dropdown />
        </div>



        <!-- Dates and Submit in one row -->
        <div class="flex items-end gap-3">
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                <div class="relative ">
                    <input type="date" id="start_date" wire:model="start_date" onclick="this.showPicker()"
                        class="rounded-md border pr-5 border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 bg-white" />
                    <span
                        class="pointer-events-none absolute top-1/2 right-3 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                        <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M6.66659 1.5415C7.0808 1.5415 7.41658 1.87729 7.41658 2.2915V2.99984H12.5833V2.2915C12.5833 1.87729 12.919 1.5415 13.3333 1.5415C13.7475 1.5415 14.0833 1.87729 14.0833 2.2915V2.99984L15.4166 2.99984C16.5212 2.99984 17.4166 3.89527 17.4166 4.99984V7.49984V15.8332C17.4166 16.9377 16.5212 17.8332 15.4166 17.8332H4.58325C3.47868 17.8332 2.58325 16.9377 2.58325 15.8332V7.49984V4.99984C2.58325 3.89527 3.47868 2.99984 4.58325 2.99984L5.91659 2.99984V2.2915C5.91659 1.87729 6.25237 1.5415 6.66659 1.5415ZM6.66659 4.49984H4.58325C4.30711 4.49984 4.08325 4.7237 4.08325 4.99984V6.74984H15.9166V4.99984C15.9166 4.7237 15.6927 4.49984 15.4166 4.49984H13.3333H6.66659ZM15.9166 8.24984H4.08325V15.8332C4.08325 16.1093 4.30711 16.3332 4.58325 16.3332H15.4166C15.6927 16.3332 15.9166 16.1093 15.9166 15.8332V8.24984Z"
                                fill="" />
                        </svg>
                    </span>
                </div>
            </div>
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                <div class="relative ">
                <input type="date" id="end_date" wire:model="end_date" onclick="this.showPicker()"
                    class="rounded-md border pr-5 border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 bg-white" />
                    <span
                    class="pointer-events-none absolute top-1/2 right-3 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                    <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M6.66659 1.5415C7.0808 1.5415 7.41658 1.87729 7.41658 2.2915V2.99984H12.5833V2.2915C12.5833 1.87729 12.919 1.5415 13.3333 1.5415C13.7475 1.5415 14.0833 1.87729 14.0833 2.2915V2.99984L15.4166 2.99984C16.5212 2.99984 17.4166 3.89527 17.4166 4.99984V7.49984V15.8332C17.4166 16.9377 16.5212 17.8332 15.4166 17.8332H4.58325C3.47868 17.8332 2.58325 16.9377 2.58325 15.8332V7.49984V4.99984C2.58325 3.89527 3.47868 2.99984 4.58325 2.99984L5.91659 2.99984V2.2915C5.91659 1.87729 6.25237 1.5415 6.66659 1.5415ZM6.66659 4.49984H4.58325C4.30711 4.49984 4.08325 4.7237 4.08325 4.99984V6.74984H15.9166V4.99984C15.9166 4.7237 15.6927 4.49984 15.4166 4.49984H13.3333H6.66659ZM15.9166 8.24984H4.08325V15.8332C4.08325 16.1093 4.30711 16.3332 4.58325 16.3332H15.4166C15.6927 16.3332 15.9166 16.1093 15.9166 15.8332V8.24984Z"
                            fill="" />
                    </svg>
                </span>
            </div>
                </div>
            <div class="pt-5">
                <button type="submit"
                    class="bg-brand-500 hover:bg-brand-600 text-white font-medium px-5 py-2 rounded-md text-sm shadow transition">
                    Submit
                </button>
            </div>
        </div>
    </form>



    @if ($submitted)

        <div class="mt-6 mb-4 text-gray-800 font-semibold">
            @if ($ledger)
                {{ $ledger->name }} from {{ $startDate }} to {{ $endDate }}
            @endif
        </div>


        <table class="mb-6 border border-yellow-200 bg-yellow-100 text-sm"
            style ="width:40%; background-color: #FFFFCC;">
            <tbody class="bg-yellow-50">
                <tr onmouseover="this.style.background='#FFFF99';" onmouseout="this.style.background='#FFFFCC';">
                    <td class="px-3 py-1.5"class="border border-yellow-200 px-2 py-1 " style="width:70%">Bank or cash
                        account</td>
                    <td class="border border-yellow-200 px-2 py-1 " style="width:30%">
                        {{ $ledger->type == 1 ? 'Yes' : 'No' }}</td>
                </tr>

                <tr onmouseover="this.style.background='#FFFF99';" onmouseout="this.style.background='#FFFFCC';">
                    <td class="border border-yellow-200 px-2 py-1 " style="width:70%">Opening balance as on 01-Apr-2025
                    </td>
                    <td class="border border-yellow-200 px-2 py-1 " style="width:30%">
                        {{ \App\Helpers\FormatHelper::formatCurrency($opening['dc'], $opening['amount']) }}</td>
                </tr>
                <tr onmouseover="this.style.background='#FFFF99';" onmouseout="this.style.background='#FFFFCC';">
                    <td class="border border-yellow-200 px-2 py-1 " style="width:70%">Closing balance as on 31-Mar-2026
                    </td>
                    <td class="border border-yellow-200 px-2 py-1 " style="width:30%">
                        {{ \App\Helpers\FormatHelper::formatCurrency($closing['dc'], $closing['amount']) }}</td>
                </tr>
                <tr onmouseover="this.style.background='#FFFF99';" onmouseout="this.style.background='#FFFFCC';">
                    <td class="border border-yellow-200 px-2 py-1 " style="width:70%">Debit Reconciliation pending from
                        01-Apr-2025 to 31-Mar-2026</td>
                    <td class="border border-yellow-200 px-2 py-1 " style="width:30%">
                        {{ \App\Helpers\FormatHelper::formatCurrency('D', $dr_pending) }}</td>
                </tr>
                <tr onmouseover="this.style.background='#FFFF99';" onmouseout="this.style.background='#FFFFCC';">
                    <td class="border border-yellow-200 px-2 py-1 " style="width:70%">Credit Reconciliation pending from
                        01-Apr-2025 to 31-Mar-2026</td>
                    <td class="border border-yellow-200 px-2 py-1 " style="width:30%">
                        {{ \App\Helpers\FormatHelper::formatCurrency('C', $cr_pending) }}</td>
                </tr>
            </tbody>
        </table>


        <form wire:submit.prevent="reconcile" class="space-y-4">

            <div class="overflow-x-auto">
                <table class="w-full table-auto border border-gray-300 text-sm text-left text-gray-700">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-3 py-2 border-b border-gray-300">Date</th>
                            <th class="px-3 py-2 border-b border-gray-300">Number</th>
                            <th class="px-3 py-2 border-b border-gray-300">Ledger</th>
                            <th class="px-3 py-2 border-b border-gray-300">Type</th>
                            <th class="px-3 py-2 border-b border-gray-300">Tag</th>
                            <th class="px-3 py-2 border-b border-gray-300">Debit</th>
                            <th class="px-3 py-2 border-b border-gray-300">Credit</th>
                            <th class="px-3 py-2 border-b border-gray-300">Reconciliation Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($entries as $entry)
                             @php
                               $isSelectedLedger = isset($ledger) && $entry->ledger->id == $ledger->id;
                               $ledgerClass = $isSelectedLedger ? 'text-success-700 font-semibold' : '';
                             @endphp

                            <tr class="border-t border-gray-200 hover:bg-gray-50 transition">
                                <td class="px-3 py-2">{{ \Carbon\Carbon::parse($entry->date)->format('d-M-Y') }}</td>
                                <td class="px-3 py-2">{{ $entry->number }}</td>
                                <td class="px-3 py-2">
                                    <div class="flex flex-wrap items-center space-x-1">
                                        <span class="{{ $ledgerClass }}" >{{ $entry->dc == 'D' ? 'Dr' : 'Cr' }}</span>
                                        <span class="{{ $ledgerClass }}" >[{{ $entry->ledger->code ?? '-' }}]</span>
                                        <a href="{{ route('ledgers.edit', $entry->ledger->id) }}" class="text-success-700 font-semibold truncate">
                                            {{ $entry->ledger->name ?? 'N/A' }}
                                        </a>
                                        <span class="text-gray-500 text-[10px] mt-[1px]">
                                            ({{ $entry->dc }}  {{ number_format($entry->amount, 2) }})

                                        </span>
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $entry->narration }}
                                    </div>
                                </td>
                                <td class="px-3 py-2">{{ $entry->entrytype_id }}</td>
                                <td class="px-3 py-2">{{ $entry->tag_id ?? '-' }}</td>
                                <td class="px-3 py-2">
                                    @if ($entry->dc == 'D')
                                        {{ \App\Helpers\FormatHelper::formatCurrency('D', $entry->amount) }}
                                    @endif
                                </td>
                                <td class="px-3 py-2">
                                    @if ($entry->dc == 'C')
                                        {{ \App\Helpers\FormatHelper::formatCurrency('C', $entry->amount) }}
                                    @endif
                                </td>
                                <td class="px-3 py-2">
                                    <input type="date" wire:model.defer="reconcile.{{ $entry->id }}" onclick="this.showPicker()"
                                        class="w-full rounded border border-gray-300 px-2 py-1 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500" />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <button type="submit"
                class="bg-brand-500 hover:bg-brand-600 text-white px-4 py-2 rounded-md text-sm shadow-md transition duration-300 ease-in-out transform hover:scale-105">
                Reconcile
            </button>
        </form>
    @endif

</div>
