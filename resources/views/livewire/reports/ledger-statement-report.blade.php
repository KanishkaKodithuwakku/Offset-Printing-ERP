<div
    class="overflow-hidden rounded-xl border px-4 py-4 border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
    <div>
        <h2 class="text-lg font-semibold mb-2">Ledger Statement </h2>

        {{-- Ledger account selector --}}
        <div class="mb-2">
            {{-- <label class="block font-medium mb-1">Ledger account</label> --}}
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

        @if ($submitted)
            <div class="mt-10">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <h3 class="font-semibold text-base mb-1">{{ config('app.company_name') }}</h3>
                        <p class="font-medium">
                            Ledger statement for [{{ $ledger->code }}] {{ $ledger->name }} from {{ $start_date }} to
                            {{ $end_date }}
                        </p>
                    </div>
                    <div class="text-xs text-gray-700 text-right">
                        {{-- Master Graphics Services (Pvt) Ltd. --}}
                        <br>
                        {{ $fiscal_year ?? '' }}
                    </div>
                </div>

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


                {{-- Table --}}
                <div class="overflow-x-auto mt-10">
                    <table class="min-w-full border-t border-b border-gray-300 text-sm">
                        <thead>
                            <tr class="bg-gray-100 border-b border-gray-300">
                                <th class="px-2 py-1 font-normal">Date</th>
                                <th class="px-2 py-1 font-normal">Number</th>
                                <th class="px-2 py-1 font-normal">Ledger</th>
                                <th class="px-2 py-1 font-normal">Type</th>
                                <th class="px-2 py-1 font-normal">Tag</th>
                                <th class="px-2 py-1 font-normal">Debit Amount (Rs)</th>
                                <th class="px-2 py-1 font-normal">Credit Amount (Rs)</th>
                                <th class="px-2 py-1 font-normal">Balance (Rs)</th>
                                <th class="px-2 py-1 font-normal">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="bg-gray-50 font-medium" onmouseover="this.style.background='#FFFF99';"
                                onmouseout="this.style.background='';">
                                <td colspan="7" class="px-2 py-1">Current opening balance</td>
                                <td class="px-2 py-1">
                                    {{ \App\Helpers\FormatHelper::formatCurrency($opening_balance['dc'], $opening_balance['amount']) }}

                                <td></td>
                            </tr>

                            @php $running_balance = $opening_balance; @endphp

                            @foreach ($transactions as $entry)
                                @php
                                    $amount = $entry->amount;
                                    $dc = $entry->dc;
                                    $running_balance = App\Helpers\AccountingHelper::calculateWithDC(
                                        $running_balance['amount'],
                                        $running_balance['dc'],
                                        $amount,
                                        $dc,
                                    );
                                @endphp
                                <tr class="border-t text-sm border-gray-300" onmouseover="this.style.background='#FFFF99';"
                                    onmouseout="this.style.background='';">
                                    <td class="px-2 py-1">{{ \Carbon\Carbon::parse($entry->date)->format('d-M-Y') }}
                                    </td>
                                    <td class="px-2 py-1">{{ $entry->number }}</td>
                                    <td class="px-2 py-1 leading-tight">
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
                                    <td class="px-2 py-1">{{ ucfirst($entry->type ?? 'Unknown') }}</td>
                                    <td class="px-2 py-1">—</td>
                                    <td class="px-2 py-1">
                                        {{ $entry->dc === 'D' ? 'Dr ' . number_format($entry->amount, 2) : '' }}
                                    </td>
                                    <td class="px-2 py-1">
                                        {{ $entry->dc === 'C' ? 'Cr ' . number_format($entry->amount, 2) : '' }}
                                    </td>
                                    <td class="px-2 py-1">
                                        {{ $running_balance['dc'] }}
                                        {{ number_format($running_balance['amount'], 2) }}
                                    </td>
                                    <td class="flex items-center gap-2 justify-start whitespace-nowrap">
                                        <a href="#" class="text-gray-500 hover:text-brand-600">
                                            <svg class="fill-current" width="18" height="18" viewBox="0 0 24 24"
                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M1.5 12C1.5 12 5.25 4.5 12 4.5C18.75 4.5 22.5 12 22.5 12C22.5 12 18.75 19.5 12 19.5C5.25 19.5 1.5 12 1.5 12ZM12 15C13.6569 15 15 13.6569 15 12C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15Z"
                                                    fill="" />
                                            </svg></a>
                                        <a href="#" class="text-gray-500 hover:text-brand-600">
                                            <svg class="fill-current" width="19" height="19" viewBox="0 0 21 21"
                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M17.0911 3.53206C16.2124 2.65338 14.7878 2.65338 13.9091 3.53206L5.6074 11.8337C5.29899 12.1421 5.08687 12.5335 4.99684 12.9603L4.26177 16.445C4.20943 16.6931 4.286 16.9508 4.46529 17.1301C4.64458 17.3094 4.90232 17.3859 5.15042 17.3336L8.63507 16.5985C9.06184 16.5085 9.45324 16.2964 9.76165 15.988L18.0633 7.68631C18.942 6.80763 18.942 5.38301 18.0633 4.50433L17.0911 3.53206ZM14.9697 4.59272C15.2626 4.29982 15.7375 4.29982 16.0304 4.59272L17.0027 5.56499C17.2956 5.85788 17.2956 6.33276 17.0027 6.62565L16.1043 7.52402L14.0714 5.49109L14.9697 4.59272ZM13.0107 6.55175L6.66806 12.8944C6.56526 12.9972 6.49455 13.1277 6.46454 13.2699L5.96704 15.6283L8.32547 15.1308C8.46772 15.1008 8.59819 15.0301 8.70099 14.9273L15.0436 8.58468L13.0107 6.55175Z"
                                                    fill="" />
                                            </svg>
                                        </a>
                                        <a href="#" class="text-gray-500 hover:text-error-500">
                                            <svg class="fill-current" width="18" height="18"
                                                viewBox="0 0 21 21" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M7.04142 4.29199C7.04142 3.04935 8.04878 2.04199 9.29142 2.04199H11.7081C12.9507 2.04199 13.9581 3.04935 13.9581 4.29199V4.54199H16.1252H17.166C17.5802 4.54199 17.916 4.87778 17.916 5.29199C17.916 5.70621 17.5802 6.04199 17.166 6.04199H16.8752V8.74687V13.7469V16.7087C16.8752 17.9513 15.8678 18.9587 14.6252 18.9587H6.37516C5.13252 18.9587 4.12516 17.9513 4.12516 16.7087V13.7469V8.74687V6.04199H3.8335C3.41928 6.04199 3.0835 5.70621 3.0835 5.29199C3.0835 4.87778 3.41928 4.54199 3.8335 4.54199H4.87516H7.04142V4.29199ZM15.3752 13.7469V8.74687V6.04199H13.9581H13.2081H7.79142H7.04142H5.62516V8.74687V13.7469V16.7087C5.62516 17.1229 5.96095 17.4587 6.37516 17.4587H14.6252C15.0394 17.4587 15.3752 17.1229 15.3752 16.7087V13.7469ZM8.54142 4.54199H12.4581V4.29199C12.4581 3.87778 12.1223 3.54199 11.7081 3.54199H9.29142C8.87721 3.54199 8.54142 3.87778 8.54142 4.29199V4.54199ZM8.8335 8.50033C9.24771 8.50033 9.5835 8.83611 9.5835 9.25033V14.2503C9.5835 14.6645 9.24771 15.0003 8.8335 15.0003C8.41928 15.0003 8.0835 14.6645 8.0835 14.2503V9.25033C8.0835 8.83611 8.41928 8.50033 8.8335 8.50033ZM12.9168 9.25033C12.9168 8.83611 12.581 8.50033 12.1668 8.50033C11.7526 8.50033 11.4168 8.83611 11.4168 9.25033V14.2503C11.4168 14.6645 11.7526 15.0003 12.1668 15.0003C12.581 15.0003 12.9168 14.6645 12.9168 14.2503Z"
                                                    fill="" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach

                            <tr class="bg-gray-50 font-medium" onmouseover="this.style.background='#FFFF99';"
                                onmouseout="this.style.background='';">
                                <td colspan="7" class="px-2 py-1">Current closing balance</td>
                                <td class="px-2 py-1">
                                    {{ \App\Helpers\FormatHelper::formatCurrency($closing_balance['dc'], $closing_balance['amount']) }}
                                </td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>
