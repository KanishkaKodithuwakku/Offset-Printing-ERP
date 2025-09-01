<div class="bg-white shadow rounded-lg">
    @php
        $groupedEntries = $entries
            ->getCollection()
            ->groupBy(fn($entry) => \Carbon\Carbon::parse($entry->date)->format('d-M-Y'));
    @endphp

    <div class="border-t border-gray-100 p-5 dark:border-gray-800 sm:p-6">
        <div
            class="overflow-hidden rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex flex-col gap-5 px-6 mb-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                            List of Entries
                        </h3>
                    </div>
                    <div>

                        <div class="relative w-fit">
                            <select wire:model="selectedType" wire:change="$refresh"
                                class="appearance-none bg-brand-500 hover:bg-brand-500 text-white font-medium px-8 h-9 rounded-md text-sm shadow-md transition duration-300 ease-in-out transform hover:scale-105 pr-10">
                                <option class="bg-gray-200 text-gray-700" value="" disabled selected>Add New Entry
                                </option>
                                <option class="bg-white text-gray-700" value="receipt">Receipt</option>
                                <option class="bg-white text-gray-700" value="payment">Payment</option>
                                <option class="bg-white text-gray-700" value="contra">Contra</option>
                                <option class="bg-white text-gray-700" value="journal">Journal</option>
                            </select>
                            <!-- Custom white arrow SVG -->
                            <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="flex gap-4 ">

                    <div class=" flex gap-4">

                        <select wire:model="tag_id"
                            class="h-9 text-xs font-semibold border-gray-300 rounded-lg px-8 border w-full">
                            <option value="">Tag</option>
                            @foreach ($tags as $tag)
                                <option value="{{ $tag->id }}">{{ $tag->title }}</option>
                            @endforeach
                        </select>

                    </div>


                    <form>
                        <div class="relative">
                            <span class="absolute -translate-y-1/2 pointer-events-none top-1/2 left-4">
                                <svg class="fill-gray-500 dark:fill-gray-400" width="20" height="20"
                                    viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M3.04199 9.37381C3.04199 5.87712 5.87735 3.04218 9.37533 3.04218C12.8733 3.04218 15.7087 5.87712 15.7087 9.37381C15.7087 12.8705 12.8733 15.7055 9.37533 15.7055C5.87735 15.7055 3.04199 12.8705 3.04199 9.37381ZM9.37533 1.54218C5.04926 1.54218 1.54199 5.04835 1.54199 9.37381C1.54199 13.6993 5.04926 17.2055 9.37533 17.2055C11.2676 17.2055 13.0032 16.5346 14.3572 15.4178L17.1773 18.2381C17.4702 18.531 17.945 18.5311 18.2379 18.2382C18.5308 17.9453 18.5309 17.4704 18.238 17.1775L15.4182 14.3575C16.5367 13.0035 17.2087 11.2671 17.2087 9.37381C17.2087 5.04835 13.7014 1.54218 9.37533 1.54218Z"
                                        fill="" />
                                </svg>
                            </span>
                            <input type="text" placeholder="Search..."
                                class=" dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-9 w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pr-4 pl-[42px] text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden xl:w-[300px] dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                        </div>
                    </form>
                    <div>
                        <button
                            class="text-theme-sm shadow-theme-xs inline-flex h-9 items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">
                            <svg class="stroke-current fill-white dark:fill-gray-800" width="20" height="20"
                                viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.29004 5.90393H17.7067" stroke="" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M17.7075 14.0961H2.29085" stroke="" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                                <path
                                    d="M12.0826 3.33331C13.5024 3.33331 14.6534 4.48431 14.6534 5.90414C14.6534 7.32398 13.5024 8.47498 12.0826 8.47498C10.6627 8.47498 9.51172 7.32398 9.51172 5.90415C9.51172 4.48432 10.6627 3.33331 12.0826 3.33331Z"
                                    fill="" stroke="" stroke-width="1.5" />
                                <path
                                    d="M7.91745 11.525C6.49762 11.525 5.34662 12.676 5.34662 14.0959C5.34661 15.5157 6.49762 16.6667 7.91745 16.6667C9.33728 16.6667 10.4883 15.5157 10.4883 14.0959C10.4883 12.676 9.33728 11.525 7.91745 11.525Z"
                                    fill="" stroke="" stroke-width="1.5" />
                            </svg>
                            Filter
                        </button>
                    </div>
                </div>
            </div>

            <!-- Header Row -->
            <div
                class="flex items-start px-6 py-3 border-b gap-4 whitespace-nowrap break-words bg-gray-50 dark:bg-gray-900 border-y border-gray-100 dark:border-gray-800 font-medium text-gray-500 text-xs ">
                <div class="flex whitespace-nowrap items-center gap-3" style="flex-basis: 12%; max-width: 12%;">
                    <input type="checkbox" class="h-5 w-5 rounded border-gray-300" />
                    <span>Number/Date</span>
                </div>
                <div class="flex items-center text-xs break-words whitespace-nowrap"
                    style="flex-basis: 26%; max-width: 26%;">Ledger</div>
                <div class="flex items-center text-xs break-words whitespace-nowrap"
                    style="flex-basis: 10%; max-width: 10%;">Customer</div>
                <div class="flex items-center text-xs break-words whitespace-nowrap"
                    style="flex-basis: 8%; max-width: 8%;">Type</div>
                <div class="flex items-center text-xs break-words whitespace-nowrap"
                    style="flex-basis: 8%; max-width: 8%;">Tag</div>
                <div class="flex items-center text-xs break-words whitespace-nowrap"
                    style="flex-basis: 10%; max-width: 10%;">Debit Amount (Rs)</div>
                <div class="flex items-center text-xs break-words whitespace-nowrap"
                    style="flex-basis: 10%; max-width: 10%;">Credit Amount (Rs)</div>
                <div class="flex items-center text-xs break-words whitespace-nowrap"
                    style="flex-basis: 8%; max-width: 8%;">Action</div>
            </div>

            <ul class="divide-y divide-gray-100 dark:divide-gray-800">
                @foreach ($groupedEntries as $date => $dayEntries)
                    @foreach ($dayEntries as $entry)
                        <li class="flex items-start px-6 py-3 border-b gap-4" style="transition:background 0.2s;"
                            onmouseover="this.style.backgroundColor='#FFFF99';this.style.color='';"
                            onmouseout="this.style.backgroundColor='';this.style.color='';">


                            <div class="flex items-center whitespace-nowrap"
                                style="flex-basis: 12%; max-width: 12%; min-width: 0;">
                                <input type="checkbox" class="h-5 w-5 rounded border-gray-300" />
                                <span class="ml-3 block text-xs text-gray-700 dark:text-gray-400 font-medium truncate">
                                    [{{ $entry->number }}] {{ \Carbon\Carbon::parse($entry->date)->format('d-M-Y') }}
                                </span>
                            </div>


                            <div class="flex flex-col break-words text-xs"
                                style="flex-basis: 26%; max-width: 26%; min-width: 0;">
                                @foreach ($entry->entryitems as $item)
                                    <div class="flex flex-wrap items-center space-x-1">
                                        <span>{{ $item->dc == 'D' ? 'Dr' : 'Cr' }}</span>
                                        <span>[L{{ $item->ledger->code ?? '-' }}]</span>
                                        <a href="{{ route('ledgers.edit', $item->ledger->id) }}"
                                            class="text-brand-500 truncate">
                                            {{ $item->ledger->name ?? 'N/A' }}
                                        </a>
                                        <span class="text-gray-500 text-[10px] mt-[1px]">
                                            ({{ $item->dc }} {{ number_format($item->amount, 2) }})
                                        </span>
                                    </div>
                                    <div class="text-gray-500 italic text-xs break-words">
                                        {{ $entry->narration }}
                                    </div>
                                @endforeach
                            </div>


                            <div class="flex items-center text-xs whitespace-nowrap"
                                style="flex-basis: 10%; max-width: 10%; min-width: 0;">

                                {{ $item->ledger->name ?? 'N/A' }}
                            </div>


                            <div class="flex items-center text-xs whitespace-nowrap"
                                style="flex-basis: 8%; max-width: 8%; min-width: 0;">
                                {{ $entry->entrytype->name }}
                            </div>


                            <div class="flex items-center text-xs whitespace-nowrap"
                                style="flex-basis: 8%; max-width: 8%; min-width: 0;">
                                {{ $entry->tag->title ?? '-' }}
                            </div>


                            <div class="flex items-center text-xs whitespace-nowrap text-right"
                                style="flex-basis: 10%; max-width: 10%; min-width: 0;">
                                @php
                                    $debit = $entry->entryitems->where('dc', 'D')->sum('amount');
                                @endphp
                                {{ $debit > 0 ? number_format($debit, 2) : '-' }}
                            </div>


                            <div class="flex items-center text-xs whitespace-nowrap text-right"
                                style="flex-basis: 10%; max-width: 10%; min-width: 0;">
                                @php
                                    $credit = $entry->entryitems->where('dc', 'C')->sum('amount');
                                @endphp
                                {{ $credit > 0 ? number_format($credit, 2) : '-' }}
                            </div>


                            <div class="flex items-center gap-2 justify-start whitespace-nowrap"
                                style="flex-basis: 8%; max-width: 8%; min-width: 0;">

                                <a href="{{ route('entries.view', [$entry->entryType->label, $entry->id]) }}"
                                    class="text-gray-500 hover:text-brand-500"> <svg class="fill-current"
                                        width="18" height="18" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M1.5 12C1.5 12 5.25 4.5 12 4.5C18.75 4.5 22.5 12 22.5 12C22.5 12 18.75 19.5 12 19.5C5.25 19.5 1.5 12 1.5 12ZM12 15C13.6569 15 15 13.6569 15 12C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15Z"
                                            fill="" />
                                    </svg></a>
                                <a href="{{ route('entries.edit', ['type' => $entry->entrytype->label, 'id' => $entry->id]) }}"
                                    class="text-gray-500 hover:text-brand-500"><svg class="fill-current"
                                        width="19" height="19" viewBox="0 0 21 21" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M17.0911 3.53206C16.2124 2.65338 14.7878 2.65338 13.9091 3.53206L5.6074 11.8337C5.29899 12.1421 5.08687 12.5335 4.99684 12.9603L4.26177 16.445C4.20943 16.6931 4.286 16.9508 4.46529 17.1301C4.64458 17.3094 4.90232 17.3859 5.15042 17.3336L8.63507 16.5985C9.06184 16.5085 9.45324 16.2964 9.76165 15.988L18.0633 7.68631C18.942 6.80763 18.942 5.38301 18.0633 4.50433L17.0911 3.53206ZM14.9697 4.59272C15.2626 4.29982 15.7375 4.29982 16.0304 4.59272L17.0027 5.56499C17.2956 5.85788 17.2956 6.33276 17.0027 6.62565L16.1043 7.52402L14.0714 5.49109L14.9697 4.59272ZM13.0107 6.55175L6.66806 12.8944C6.56526 12.9972 6.49455 13.1277 6.46454 13.2699L5.96704 15.6283L8.32547 15.1308C8.46772 15.1008 8.59819 15.0301 8.70099 14.9273L15.0436 8.58468L13.0107 6.55175Z"
                                            fill="" />
                                    </svg></a>
                                <a href="#"
                                    class="text-gray-500 hover:text-error-500"onclick="event.preventDefault(); if (confirm('Are you sure you want to delete this entry?')) { @this.call('deleteEntry') }"><svg
                                        class="fill-current" width="18" height="18" viewBox="0 0 21 21"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M7.04142 4.29199C7.04142 3.04935 8.04878 2.04199 9.29142 2.04199H11.7081C12.9507 2.04199 13.9581 3.04935 13.9581 4.29199V4.54199H16.1252H17.166C17.5802 4.54199 17.916 4.87778 17.916 5.29199C17.916 5.70621 17.5802 6.04199 17.166 6.04199H16.8752V8.74687V13.7469V16.7087C16.8752 17.9513 15.8678 18.9587 14.6252 18.9587H6.37516C5.13252 18.9587 4.12516 17.9513 4.12516 16.7087V13.7469V8.74687V6.04199H3.8335C3.41928 6.04199 3.0835 5.70621 3.0835 5.29199C3.0835 4.87778 3.41928 4.54199 3.8335 4.54199H4.87516H7.04142V4.29199ZM15.3752 13.7469V8.74687V6.04199H13.9581H13.2081H7.79142H7.04142H5.62516V8.74687V13.7469V16.7087C5.62516 17.1229 5.96095 17.4587 6.37516 17.4587H14.6252C15.0394 17.4587 15.3752 17.1229 15.3752 16.7087V13.7469ZM8.54142 4.54199H12.4581V4.29199C12.4581 3.87778 12.1223 3.54199 11.7081 3.54199H9.29142C8.87721 3.54199 8.54142 3.87778 8.54142 4.29199V4.54199ZM8.8335 8.50033C9.24771 8.50033 9.5835 8.83611 9.5835 9.25033V14.2503C9.5835 14.6645 9.24771 15.0003 8.8335 15.0003C8.41928 15.0003 8.0835 14.6645 8.0835 14.2503V9.25033C8.0835 8.83611 8.41928 8.50033 8.8335 8.50033ZM12.9168 9.25033C12.9168 8.83611 12.581 8.50033 12.1668 8.50033C11.7526 8.50033 11.4168 8.83611 11.4168 9.25033V14.2503C11.4168 14.6645 11.7526 15.0003 12.1668 15.0003C12.581 15.0003 12.9168 14.6645 12.9168 14.2503Z"
                                            fill="" />
                                    </svg></a>
                                <a href="{{ route('entries.print-preview', $entry->id) }}" target="_blank"
                                    class="text-gray-500 hover:text-brand-600"><svg width="18" height="18"
                                        viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke="currentColor" stroke-linejoin="round" stroke-width="2"
                                            d="M16.444 18H19a1 1 0 0 0 1-1v-5a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1h2.556M17 11V5a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v6h10ZM7 15h10v4a1 1 0 0 1-1 1H8a1 1 0 0 1-1-1v-4Z" />
                                    </svg></a>
                            </div>
                        </li>
                    @endforeach
                @endforeach
            </ul>
        </div>
    </div>
    <div class="flex justify-between items-center border-t px-6 py-4 dark:border-gray-800">
        <div class="text-sm text-gray-600 dark:text-gray-400">
            Showing {{$entries->firstItem() }} to {{ $entries->lastItem() }} of {{ $entries->total() }}
            entries
        </div>

        <div class="pagination flex justify-between">
            {{ $entries->links('vendor.pagination.custom-tailwind') }}
        </div>
    </div>
</div>
