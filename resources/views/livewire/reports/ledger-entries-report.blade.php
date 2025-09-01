<div
    class="overflow-hidden rounded-xl border px-4 py-4 border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
    <div class="w-full">
        <h2 class="text-lg font-semibold mb-2">Ledger Entries </h2>

        {{-- Ledger account selector --}}
        <div class="mb-2">
            <div style="max-width: 25%;">
                <livewire:components.select-ledger-dropdown />
            </div>
        </div>


        {{-- Filter Form --}}
        <form wire:submit.prevent="submit" class="flex flex-wrap gap-4  items-end">
            <div>
                <label for="start_date" class="block text-sm  text-gray-800 font-medium mb-1">Start Date</label>
                <div class="relative ">
                <input type="date" wire:model="start_date" id="start_date" onclick="this.showPicker()"
                    class=" form-input gap-5 border pr-5 border-gray-300 rounded-lg px-2 py-1 text-sm" style="width: 100%" />
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
                <label for="end_date" class="block text-sm  text-gray-800 font-medium mb-1">End Date</label>
                <div class="relative ">
                    <input type="date" wire:model="end_date" id="end_date" onclick="this.showPicker()"
                        class="form-input border pr-5 border-gray-300 rounded-lg px-2 py-1 text-sm" />
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
                <button type="submit"
                    class="bg-brand-500 rounded-lg text-white px-5 py-1 border border-brand-500 text-sm">Submit</button>
            </div>
        </form>

        </form>

        @if ($submitted)
            <div class="mt-4">
                <h5 class="text-normal font-semibold mb-2">Ledger: {{ $ledger->name }}</h5>
                {{-- <p><span class="font-normal">Opening Balance:</span>
                    {{ \App\Helpers\FormatHelper::formatCurrency($opening_balance['dc'], $opening_balance['amount']) }}
                </p>
                <p><span class="font-normal">Closing Balance:</span>
                    {{ \App\Helpers\FormatHelper::formatCurrency($closing_balance['dc'], $closing_balance['amount']) }}
                </p> --}}

                {{-- Yellow summary boxes --}}
                <div class=" mt-5">
                    <table class=" border border-yellow-200 bg-yellow-100 text-sm"
                        style ="width:40%; background-color: #FFFFCC;">
                        <tr onmouseover="this.style.background='#FFFF99';"
                            onmouseout="this.style.background='#FFFFCC';">
                            <td class="border border-yellow-200 px-2 py-1 " style="width:50%">Bank or cash account</td>
                            <td class="border border-yellow-200 px-2 py-1 " style="width:50%">
                                {{ $ledger->type ? 'Yes' : '' }}</td>
                        </tr>
                        <tr onmouseover="this.style.background='#FFFF99';"
                            onmouseout="this.style.background='#FFFFCC';">
                            <td class="border border-yellow-200 px-2 py-1">Notes</td>
                            <td class="border border-yellow-200 px-2 py-1">{{ $ledger->notes ?? '' }}</td>
                        </tr>
                    </table>
                    <table class=" border border-yellow-200 bg-yellow-100 text-sm mt-5"
                        style ="width:40%; background-color: #FFFFCC;">
                        <tr onmouseover="this.style.background='#FFFF99';"
                            onmouseout="this.style.background='#FFFFCC';">
                            <td class="border border-yellow-200 px-2 py-1 " style="width:50%">Opening balance as on
                                {{ $start_date }}</td>
                            <td class="border border-yellow-200 px-2 py-1 " style="width:50%">

                                {{ \App\Helpers\FormatHelper::formatCurrency($opening_balance['dc'], $opening_balance['amount']) }}
                            </td>
                        </tr>
                        <tr onmouseover="this.style.background='#FFFF99';"
                            onmouseout="this.style.background='#FFFFCC';">
                            <td class="border border-yellow-200 px-2 py-1">Closing balance as on {{ $end_date }}
                            </td>
                            <td class="border border-yellow-200 px-2 py-1">
                                {{ \App\Helpers\FormatHelper::formatCurrency($closing_balance['dc'], $closing_balance['amount']) }}
                            </td>
                        </tr>
                    </table>
                </div>


                <table class="min-w-full border-t border-b border-gray-300 text-sm mt-10">
                    <thead>
                        <tr class="bg-gray-100 border-b border-gray-300">
                            <th class="px-2 text-left py-1 font-normal">Date</th>
                            <th class="px-2 text-left py-1 font-normal">Ledger</th>
                            {{-- <th class="px-2 text-left py-1 font-normal">Party Name</th> --}}
                            {{-- <th class="px-2 text-left py-1 font-normal">Narration</th> --}}
                            <th class="px-2 text-left py-1 font-normal">Party Type</th>
                            <th class="px-2 text-left py-1 font-normal">Dr</th>
                            <th class="px-2 text-left py-1 font-normal">Cr</th>
                        </tr>
                    </thead>
                    <tbody >
                        @foreach ($entries as $entry)
                            <tr onmouseover="this.style.background='#FFFF99';" onmouseout="this.style.background='';" class="border-b border-gray-200">
                                <td class="px-2">{{ $entry->date }}</td>
                                <td class="px-2">
                                    @foreach ($entry->entryitems as $item)
                                        @php
                                            $isSelectedLedger = isset($ledger) && $item->ledger->id == $ledger->id;
                                            $ledgerClass = $isSelectedLedger ? 'text-success-700 font-semibold' : '';
                                        @endphp
                                        <div class="flex flex-wrap items-center space-x-1">
                                            <span class="{{ $ledgerClass }}">{{ $item->dc == 'D' ? 'Dr' : 'Cr' }}</span>
                                            <span class="{{ $ledgerClass }}">[L{{ $item->ledger->code ?? '-' }}]</span>
                                            <a href="{{ route('ledgers.edit', $item->ledger->id) }}" class="{{ $ledgerClass }} truncate">
                                                {{ $item->ledger->name ?? 'N/A' }}
                                            </a>
                                            <span class="text-gray-500 text-[10px] mt-[1px]">
                                                ({{ $item->dc }} {{ number_format($item->amount, 2) }})
                                            </span>

                                        </div>

                                    @endforeach
                                    <div>
                                        <span class="text-xs text-gray-500">
                                            {{ $entry->narration }}
                                        </span>
                                    </div>
                                </td>
                                {{-- <td class="px-2">{{ $entry->customer?->name ?? $entry->vendor?->name ?? 'N/A' }}</td> --}}
                                {{-- <td class="px-2">{{ $entry->narration }}</td> --}}
                                <td class="px-2">{{ strtoupper($entry->entryType->label) }}</td>
                                <td class="px-2">
                                    Dr. {{ $entry->dc === 'D' ? number_format($entry->amount, 2) : '00' }}
                                </td>
                                <td class="px-2">
                                    Cr. {{ $entry->dc === 'C' ? number_format($entry->amount, 2) : '00' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
