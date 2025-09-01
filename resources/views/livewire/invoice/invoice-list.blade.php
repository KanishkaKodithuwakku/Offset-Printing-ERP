<div x-data="{ open: false }">

    <!-- Modal (Filter Options) -->
    <div x-show="open" x-transition @closeModal.window="open = false"
        class="fixed inset-0 bg-gray-50 bg-opacity-80 z-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full border border-gray-200 dark:border-gray-800">
            <h3 class="text-xl font-semibold text-gray-800">Filter Options</h3>

            <div class="mt-4">
                <form>
                    <div class="mb-4">
                        <div class="flex gap-4 w-full">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-600">Start Date</label>
                                <input onclick="this.showPicker()" type="date" wire:model="startDate"
                                    class="w-full p-2 border rounded-md">
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-600">End Date</label>
                                <input onclick="this.showPicker()" type="date" wire:model="endDate"
                                    class="w-full p-2 border rounded-md">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-4 mt-4">
                        <button type="button" @click="open = false"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-6 py-2 rounded-md">
                            Close
                        </button>
                        <button type="submit" @click="open = false"
                            class="bg-brand-500 hover:bg-brand-700 text-white font-semibold px-6 py-2 rounded-md">
                            Apply Filters
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="my-4">
        @if (session('success'))
            <div
                class="rounded-xl border border-success-500 bg-success-50 p-4 dark:border-success-500/30 dark:bg-success-500/15">
                <div class="flex items-start gap-3">
                    <div class="-mt-0.5 text-success-500">
                        <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M3.70186 12.0001C3.70186 7.41711 7.41711 3.70186 12.0001 3.70186C16.5831 3.70186 20.2984 7.41711 20.2984 12.0001C20.2984 16.5831 16.5831 20.2984 12.0001 20.2984C7.41711 20.2984 3.70186 16.5831 3.70186 12.0001ZM12.0001 1.90186C6.423 1.90186 1.90186 6.423 1.90186 12.0001C1.90186 17.5772 6.423 22.0984 12.0001 22.0984C17.5772 22.0984 22.0984 17.5772 22.0984 12.0001C22.0984 6.423 17.5772 1.90186 12.0001 1.90186ZM15.6197 10.7395C15.9712 10.388 15.9712 9.81819 15.6197 9.46672C15.2683 9.11525 14.6984 9.11525 14.347 9.46672L11.1894 12.6243L9.6533 11.0883C9.30183 10.7368 8.73198 10.7368 8.38051 11.0883C8.02904 11.4397 8.02904 12.0096 8.38051 12.3611L10.553 14.5335C10.7217 14.7023 10.9507 14.7971 11.1894 14.7971C11.428 14.7971 11.657 14.7023 11.8257 14.5335L15.6197 10.7395Z"
                                fill="" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="mb-1 text-sm font-semibold text-gray-800 dark:text-white/90">
                            Success
                        </h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            </div>
        @elseif (session('error'))
            <div class="rounded-xl border border-error-500 bg-error-50 p-4 dark:border-red-500/30 dark:bg-red-500/10">
                <div class="flex items-start gap-3">
                    <div class="-mt-0.5 text-red-500">
                        <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M12 3C7.031 3 3 7.031 3 12s4.031 9 9 9 9-4.031 9-9-4.031-9-9-9Zm0 16c-3.866 0-7-3.134-7-7s3.134-7 7-7 7 3.134 7 7-3.134 7-7 7Zm-.75-11a.75.75 0 0 1 1.5 0v4.5a.75.75 0 0 1-1.5 0V8Zm0 6.75a.75.75 0 1 1 1.5 0 .75.75 0 0 1-1.5 0Z"
                                fill="" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="mb-1 text-sm font-semibold text-gray-800 dark:text-white/90">
                            Error
                        </h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ session('error') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <div class="p-2 mx-auto max-w-screen-2xl md:p-4">
        <div class="space-y-5 sm:space-y-6">
            <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="mb-4 flex flex-col gap-2 px-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                    <div class="flex items-center justify-between  sm:gap-5">
                        {{-- Title --}}
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                            Invoices
                        </h3>

                        {{-- Status Dropdown --}}
                        <div>
                            <label class="mb-1  block text-xs font-medium text-gray-700 dark:text-gray-400">Status</label>
                            <select wire:model.change="statusFilter"
                                class="h-8 rounded-md border border-gray-300 px-2 text-xs dark:bg-dark-900 dark:text-white/90">
                                <option value="">All</option>
                                <option value="invoicing">Invoicing</option>
                                <option value="invoiced">Invoiced</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>

                        {{-- Date Filters --}}
                        <div class="flex gap-5">
                            <div class="flex-1">
                                <label class="mb-1 block text-xs font-medium text-gray-700 dark:text-gray-400">Start Date</label>
                                <div class="relative ">
                                    <input type="date" wire:model.change="startDate" onclick="this.showPicker()"
                                        class="block w-full rounded-md border pr-5 border-gray-300  h-8 py-2 px-3 focus:border-blue-500 focus:outline-none focus:ring-blue-500 text-xs">
                                    <span
                                        class="pointer-events-none absolute top-1/2 right-3 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                                        <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20"
                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M6.66659 1.5415C7.0808 1.5415 7.41658 1.87729 7.41658 2.2915V2.99984H12.5833V2.2915C12.5833 1.87729 12.919 1.5415 13.3333 1.5415C13.7475 1.5415 14.0833 1.87729 14.0833 2.2915V2.99984L15.4166 2.99984C16.5212 2.99984 17.4166 3.89527 17.4166 4.99984V7.49984V15.8332C17.4166 16.9377 16.5212 17.8332 15.4166 17.8332H4.58325C3.47868 17.8332 2.58325 16.9377 2.58325 15.8332V7.49984V4.99984C2.58325 3.89527 3.47868 2.99984 4.58325 2.99984L5.91659 2.99984V2.2915C5.91659 1.87729 6.25237 1.5415 6.66659 1.5415ZM6.66659 4.49984H4.58325C4.30711 4.49984 4.08325 4.7237 4.08325 4.99984V6.74984H15.9166V4.99984C15.9166 4.7237 15.6927 4.49984 15.4166 4.49984H13.3333H6.66659ZM15.9166 8.24984H4.08325V15.8332C4.08325 16.1093 4.30711 16.3332 4.58325 16.3332H15.4166C15.6927 16.3332 15.9166 16.1093 15.9166 15.8332V8.24984Z"
                                                fill="" />
                                        </svg>
                                    </span>
                                </div>
                            </div>

                            <div class="flex-1">
                                <label class="mb-1  block text-xs font-medium text-gray-700 dark:text-gray-400">End Date</label>
                                <div class="relative ">
                                    <input type="date" wire:model.change="endDate" onclick="this.showPicker()"
                                        class="block w-full rounded-md border pr-5 border-gray-300  h-8 py-2 px-3 focus:border-blue-500 focus:outline-none focus:ring-blue-500 text-xs">
                                    <span
                                        class="pointer-events-none absolute top-1/2 right-3 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                                        <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20"
                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M6.66659 1.5415C7.0808 1.5415 7.41658 1.87729 7.41658 2.2915V2.99984H12.5833V2.2915C12.5833 1.87729 12.919 1.5415 13.3333 1.5415C13.7475 1.5415 14.0833 1.87729 14.0833 2.2915V2.99984L15.4166 2.99984C16.5212 2.99984 17.4166 3.89527 17.4166 4.99984V7.49984V15.8332C17.4166 16.9377 16.5212 17.8332 15.4166 17.8332H4.58325C3.47868 17.8332 2.58325 16.9377 2.58325 15.8332V7.49984V4.99984C2.58325 3.89527 3.47868 2.99984 4.58325 2.99984L5.91659 2.99984V2.2915C5.91659 1.87729 6.25237 1.5415 6.66659 1.5415ZM6.66659 4.49984H4.58325C4.30711 4.49984 4.08325 4.7237 4.08325 4.99984V6.74984H15.9166V4.99984C15.9166 4.7237 15.6927 4.49984 15.4166 4.49984H13.3333H6.66659ZM15.9166 8.24984H4.08325V15.8332C4.08325 16.1093 4.30711 16.3332 4.58325 16.3332H15.4166C15.6927 16.3332 15.9166 16.1093 15.9166 15.8332V8.24984Z"
                                                fill="" />
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">

                        <!-- Search Input -->
                        <div class="relative">
                            <span class="pointer-events-none absolute top-1/2 left-4 -translate-y-1/2">
                                <svg class="fill-gray-500 dark:fill-gray-400" width="15" height="15"
                                    viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M3.04199 9.37381C3.04199 5.87712 5.87735 3.04218 9.37533 3.04218C12.8733 3.04218 15.7087 5.87712 15.7087 9.37381C15.7087 12.8705 12.8733 15.7055 9.37533 15.7055C5.87735 15.7055 3.04199 12.8705 3.04199 9.37381ZM9.37533 1.54218C5.04926 1.54218 1.54199 5.04835 1.54199 9.37381C1.54199 13.6993 5.04926 17.2055 9.37533 17.2055C11.2676 17.2055 13.0032 16.5346 14.3572 15.4178L17.1773 18.2381C17.4702 18.531 17.945 18.5311 18.2379 18.2382C18.5308 17.9453 18.5309 17.4704 18.238 17.1775L15.4182 14.3575C16.5367 13.0035 17.2087 11.2671 17.2087 9.37381C17.2087 5.04835 13.7014 1.54218 9.37533 1.54218Z"
                                        fill="" />
                                </svg>
                            </span>
                            <input type="text" wire:model.live.debounce.100ms="searchTerm"
                                placeholder="Search by INV,JOB,PO or Customer Name"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-[32px] w-full rounded-lg border border-gray-300 bg-transparent py-1.5 pr-4 pl-[42px] text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden xl:w-[300px] dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                        </div>
                        <a wire:navigate href="{{ route('customer.orders.create') }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-md text-sm shadow-md transition duration-300 ease-in-out transform hover:scale-105"
                            style="background-color:#465FFF;padding 8px 15px;">
                            + Add New Invoice
                        </a>
                    </div>
                </div>

                {{-- Optional text --}}
                {{-- @if ($startDate && $endDate)
                <div class="px-6 text-sm text-error-600 dark:text-error-600">
                    Job list filtered from <strong>{{ $startDate }}</strong> to <strong>{{ $endDate }}</strong>
                </div>
                @endif --}}

                <div class="custom-scrollbar max-w-full overflow-x-auto px-5 sm:px-6">
                    <table class="min-w-full">
                        <thead class="border-y border-gray-100 py-2 dark:border-gray-800">
                            <tr>
                                <th class="py-2 font-normal whitespace-nowrap" wire:click="sortBy('id')">
                                    <div class="flex items-center">
                                        <p class="text-theme-xs text-gray-500 dark:text-gray-400">Invoice ID</p>
                                    </div>
                                </th>
                                <th class="py-2 font-normal whitespace-nowrap" wire:click="sortBy('id')">
                                    <div class="flex items-center">
                                        <p class="text-theme-xs text-gray-500 dark:text-gray-400">Invoice Number</p>
                                    </div>
                                </th>
                                <th class="py-2 font-normal whitespace-nowrap" wire:click="sortBy('id')">
                                    <div class="flex items-center">
                                        <p class="text-theme-xs text-gray-500 dark:text-gray-400">Job Number</p>
                                    </div>
                                </th>
                                <th class="py-2 font-normal whitespace-nowrap" wire:click="sortBy('id')">
                                    <div class="flex items-center">
                                        <p class="text-theme-xs text-gray-500 dark:text-gray-400">PO Number</p>
                                    </div>
                                </th>
                                <th class="py-2 font-normal whitespace-nowrap" wire:click="sortBy('id')">
                                    <div class="flex items-center">
                                        <p class="text-theme-xs text-gray-500 dark:text-gray-400">Customer</p>
                                    </div>
                                </th>
                                <th class="py-2 font-normal whitespace-nowrap" wire:click="sortBy('id')">
                                    <div class="flex items-center">
                                        <p class="text-theme-xs text-gray-500 dark:text-gray-400">Status</p>
                                    </div>
                                </th>
                                <th class="py-2 font-normal whitespace-nowrap" wire:click="sortBy('id')">
                                    <div class="flex items-center">
                                        <p class="text-theme-xs text-gray-500 dark:text-gray-400">Due</p>
                                    </div>
                                </th>
                                <th class="py-2 font-normal whitespace-nowrap" wire:click="sortBy('id')">
                                    <div class="flex items-center">
                                        <p class="text-theme-xs text-gray-500 dark:text-gray-400">Total Amount</p>
                                    </div>
                                </th>
                                <th class="py-2 font-normal whitespace-nowrap" wire:click="sortBy('id')">
                                    <div class="flex items-center">
                                        <p class="text-theme-xs text-gray-500 dark:text-gray-400">Created Date</p>
                                    </div>
                                </th>
                                <th class="py-2 font-normal whitespace-nowrap" style="width:5%">
                                    <div class="flex items-center">
                                        <p class="text-theme-xs text-gray-500 dark:text-gray-400 ">Actions</p>
                                    </div>
                                </th>

                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">

                            @foreach ($invoices as $invoice)
                                <tr
                                    class="hover:bg-gray-200 transition cursor-pointer @if ($invoice->status === 'invoiced') bg-success-50 @else bg-error-50 @endif">
                                    <td class="py-1 whitespace-nowrap" wire:navigate
                                        href="{{ route('invoice.view', $invoice['id']) }}">
                                        <div class="flex items-center">
                                            <p class="text-theme-xs text-gray-700 dark:text-gray-400">
                                                {{ $invoice->id }}
                                            </p>
                                        </div>
                                    </td>
                                    <td class="py-1 whitespace-nowrap" wire:navigate
                                        href="{{ route('invoice.view', $invoice['id']) }}">
                                        <div class="flex items-center">
                                            <p class="text-theme-xs text-gray-700 dark:text-gray-400">
                                                {{ $invoice->invoice_number }}
                                            </p>
                                        </div>
                                    </td>
                                    <td class="py-1 whitespace-nowrap" wire:navigate
                                        href="{{ route('invoice.view', $invoice['id']) }}">
                                        <div class="flex items-center">
                                            <p class="text-theme-xs text-gray-700 dark:text-gray-400">
                                                {{ $invoice->order?->job_number }}
                                            </p>
                                        </div>
                                    </td>
                                    <td class="py-1 whitespace-nowrap" wire:navigate
                                        href="{{ route('invoice.view', $invoice['id']) }}">
                                        <div class="flex items-center">
                                            <p class="text-theme-xs text-gray-700 dark:text-gray-400">
                                                {{ $invoice->order?->customer_po_number }}
                                            </p>
                                        </div>
                                    </td>
                                    <td class="py-1 whitespace-nowrap" wire:navigate
                                        href="{{ route('invoice.view', $invoice['id']) }}">
                                        <div class="flex items-center">
                                            <p class="text-theme-xs text-gray-700 dark:text-gray-400">
                                                {{ $invoice->order?->customer->name }}
                                            </p>
                                        </div>
                                    </td>
                                    <td class="py-1 whitespace-nowrap" wire:navigate
                                        href="{{ route('invoice.view', $invoice['id']) }}">
                                        <div class="flex items-center">
                                            <p class="text-theme-xs text-gray-700 dark:text-gray-400">
                                                <span
                                                    class="@if ($invoice->status === 'invoiced') inline-flex items-center justify-center gap-1 rounded-full bg-success-50 px-2.5 py-0.5 text-xs font-medium text-success-600 dark:bg-success-500/15 dark:text-success-500 @else inline-flex items-center justify-center gap-1 rounded-full bg-error-50 px-2.5 py-0.5 text-xs font-medium text-error-600 dark:bg-error-500/15 dark:text-error-500 @endif">
                                                    {{ strtoupper($invoice->status) }} </span>
                                                @if ($invoice->status === 'invoiced')
                                                    <span class="text-success-600">&#10004</span>
                                                @endif
                                            </p>
                                        </div>
                                    </td>
                                    <td class="py-1 whitespace-nowrap" wire:navigate
                                        href="{{ route('invoice.view', $invoice['id']) }}">
                                        <div class="flex items-center">
                                            <p class="text-theme-xs text-gray-700 dark:text-gray-400">
                                                {{ number_format($invoice->amount_due, 2) }} LKR
                                            </p>
                                        </div>
                                    </td>
                                    <td class="py-1 whitespace-nowrap" wire:navigate
                                        href="{{ route('invoice.view', $invoice['id']) }}">
                                        <div class="flex items-center">
                                            <p class="text-theme-xs text-gray-700 dark:text-gray-400">
                                                {{ number_format($invoice->total_amount, 2) }} LKR
                                            </p>
                                        </div>
                                    </td>
                                    <td class="py-1 whitespace-nowrap" wire:navigate
                                        href="{{ route('invoice.view', $invoice['id']) }}">
                                        <div class="flex items-center">
                                            <p class="text-theme-xs text-gray-700 dark:text-gray-400">
                                                {{ $invoice->created_at->format('d-m-Y') }}
                                            </p>
                                        </div>
                                    </td>

                                    @if($invoice->status != 'cancelled')
                                    <td class="py-1 whitespace-nowrap">
                                        <div class="flex items-center justify-start">
                                            @if($invoice->status != 'cancelled')
                                            <a wire:navigate href="{{ route('invoice.view', $invoice['id']) }}"
                                                class="text-gray-400 hover:text-gray-800 dark:text-gray-400 dark:hover:text-blue-500"
                                                title="View">
                                                <svg class="fill-current" width="18" height="18"
                                                    viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M16.444 18H19a1 1 0 0 0 1-1v-5a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1h2.556M17 11V5a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v6h10ZM7 15h10v4a1 1 0 0 1-1 1H8a1 1 0 0 1-1-1v-4Z" />
                                                </svg>
                                            </a>
                                            @endif

                                            @if ($invoice->status === 'invoicing' && $invoice->status != 'cancelled')
                                                <!-- Edit Button -->
                                                <a href="" wire:navigate
                                                    class="text-gray-500 px-4 hover:text-gray-800 dark:text-gray-400 dark:hover:text-white/90"
                                                    title="Edit">
                                                    <svg class="fill-current" width="19" height="19"
                                                        viewBox="0 0 21 21" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                            d="M17.0911 3.53206C16.2124 2.65338 14.7878 2.65338 13.9091 3.53206L5.6074 11.8337C5.29899 12.1421 5.08687 12.5335 4.99684 12.9603L4.26177 16.445C4.20943 16.6931 4.286 16.9508 4.46529 17.1301C4.64458 17.3094 4.90232 17.3859 5.15042 17.3336L8.63507 16.5985C9.06184 16.5085 9.45324 16.2964 9.76165 15.988L18.0633 7.68631C18.942 6.80763 18.942 5.38301 18.0633 4.50433L17.0911 3.53206ZM14.9697 4.59272C15.2626 4.29982 15.7375 4.29982 16.0304 4.59272L17.0027 5.56499C17.2956 5.85788 17.2956 6.33276 17.0027 6.62565L16.1043 7.52402L14.0714 5.49109L14.9697 4.59272ZM13.0107 6.55175L6.66806 12.8944C6.56526 12.9972 6.49455 13.1277 6.46454 13.2699L5.96704 15.6283L8.32547 15.1308C8.46772 15.1008 8.59819 15.0301 8.70099 14.9273L15.0436 8.58468L13.0107 6.55175Z"
                                                            fill="" />
                                                    </svg>
                                                </a>
                                            @else
                                                <a class="text-gray-300 px-4 hover:text-gray-300 dark:text-gray-400 dark:hover:text-white/90"
                                                    title="Edit">
                                                    <svg class="fill-current" width="19" height="19"
                                                        viewBox="0 0 21 21" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                            d="M17.0911 3.53206C16.2124 2.65338 14.7878 2.65338 13.9091 3.53206L5.6074 11.8337C5.29899 12.1421 5.08687 12.5335 4.99684 12.9603L4.26177 16.445C4.20943 16.6931 4.286 16.9508 4.46529 17.1301C4.64458 17.3094 4.90232 17.3859 5.15042 17.3336L8.63507 16.5985C9.06184 16.5085 9.45324 16.2964 9.76165 15.988L18.0633 7.68631C18.942 6.80763 18.942 5.38301 18.0633 4.50433L17.0911 3.53206ZM14.9697 4.59272C15.2626 4.29982 15.7375 4.29982 16.0304 4.59272L17.0027 5.56499C17.2956 5.85788 17.2956 6.33276 17.0027 6.62565L16.1043 7.52402L14.0714 5.49109L14.9697 4.59272ZM13.0107 6.55175L6.66806 12.8944C6.56526 12.9972 6.49455 13.1277 6.46454 13.2699L5.96704 15.6283L8.32547 15.1308C8.46772 15.1008 8.59819 15.0301 8.70099 14.9273L15.0436 8.58468L13.0107 6.55175Z"
                                                            fill="" />
                                                    </svg>
                                                </a>
                                            @endif

                                            {{-- Delete Button --}}
                                            <div x-data="{ showConfirm: false }" class="relative mt-1">
                                                {{-- @if ($invoice->status !== 'invoiced' && $invoice->status !== 'invoicing' && $invoice->status != 'cancelled') --}}
                                                    <button @click="showConfirm = true"
                                                        class="text-gray-500 hover:text-error-500 dark:text-gray-400 dark:hover:text-error-500"
                                                        title="Delete">
                                                        <svg class="fill-current" width="18" height="18"
                                                            viewBox="0 0 21 21" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                                d="M7.04142 4.29199C7.04142 3.04935 8.04878 2.04199 9.29142 2.04199H11.7081C12.9507 2.04199 13.9581 3.04935 13.9581 4.29199V4.54199H16.1252H17.166C17.5802 4.54199 17.916 4.87778 17.916 5.29199C17.916 5.70621 17.5802 6.04199 17.166 6.04199H16.8752V8.74687V13.7469V16.7087C16.8752 17.9513 15.8678 18.9587 14.6252 18.9587H6.37516C5.13252 18.9587 4.12516 17.9513 4.12516 16.7087V13.7469V8.74687V6.04199H3.8335C3.41928 6.04199 3.0835 5.70621 3.0835 5.29199C3.0835 4.87778 3.41928 4.54199 3.8335 4.54199H4.87516H7.04142V4.29199ZM15.3752 13.7469V8.74687V6.04199H13.9581H13.2081H7.79142H7.04142H5.62516V8.74687V13.7469V16.7087C5.62516 17.1229 5.96095 17.4587 6.37516 17.4587H14.6252C15.0394 17.4587 15.3752 17.1229 15.3752 16.7087V13.7469ZM8.54142 4.54199H12.4581V4.29199C12.4581 3.87778 12.1223 3.54199 11.7081 3.54199H9.29142C8.87721 3.54199 8.54142 3.87778 8.54142 4.29199V4.54199ZM8.8335 8.50033C9.24771 8.50033 9.5835 8.83611 9.5835 9.25033V14.2503C9.5835 14.6645 9.24771 15.0003 8.8335 15.0003C8.41928 15.0003 8.0835 14.6645 8.0835 14.2503V9.25033C8.0835 8.83611 8.41928 8.50033 8.8335 8.50033ZM12.9168 9.25033C12.9168 8.83611 12.581 8.50033 12.1668 8.50033C11.7526 8.50033 11.4168 8.83611 11.4168 9.25033V14.2503C11.4168 14.6645 11.7526 15.0003 12.1668 15.0003C12.581 15.0003 12.9168 14.6645 12.9168 14.2503Z"
                                                                fill="" />
                                                        </svg>
                                                    </button>

                                                    <!-- Custom Styled Confirmation Modal -->
                                                <div x-show="showConfirm"
                                                    class="fixed inset-0 flex items-center justify-center p-5 overflow-y-auto z-50">
                                                    <div
                                                        class="modal-close-btn fixed inset-0 h-full w-full bg-gray-400/50 backdrop-blur-[30px]">
                                                    </div>

                                                    <div class="flex flex-col px-4 py-4 overflow-y-auto no-scrollbar">
                                                        <div @click.outside="showConfirm = false"
                                                            class="relative w-full max-w-[515px] rounded-3xl bg-white p-6 dark:bg-gray-900 lg:p-10">
                                                            <div class="text-center">
                                                                <h4
                                                                    class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">
                                                                    Confirm Cancel
                                                                </h4>
                                                                <p
                                                                    class="text-sm leading-6 text-gray-500 dark:text-gray-400">
                                                                    Are you sure you want to Cancel this Invoice ?
                                                                    Click
                                                                    Cancel Invoice to confirm.
                                                                </p>

                                                                <label
                                                        class="text-left mt-3 mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                                        Cancel Reason <span class="text-error-500">*</span>
                                                    </label>

                                                    <input type="text" wire:model.defer="cancelReason"
                                                        placeholder="Why are you cancelling?"
                                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />

                                                                <div
                                                                    class="flex items-center justify-center w-full gap-3 mt-8">
                                                                    <button @click="showConfirm = false"
                                                                        type="button"
                                                                        class="flex justify-center rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">
                                                                        Close
                                                                    </button>
                                                                    <button type="button"
                                                                        @click="$wire.cancelInvoice({{$invoice->id }}); showConfirm = false"
                                                                        class="flex justify-center px-4 py-3 text-sm font-medium text-white rounded-lg bg-brand-500 shadow-theme-xs hover:bg-error-600">
                                                                        Cancle Invoice
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- @else
                                                    <button
                                                        class="text-gray-300 hover:text-gray-300 dark:text-gray-400 dark:hover:text-error-500"
                                                        title="Delete">
                                                        <svg class="fill-current" width="18" height="18"
                                                            viewBox="0 0 21 21" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                                d="M7.04142 4.29199C7.04142 3.04935 8.04878 2.04199 9.29142 2.04199H11.7081C12.9507 2.04199 13.9581 3.04935 13.9581 4.29199V4.54199H16.1252H17.166C17.5802 4.54199 17.916 4.87778 17.916 5.29199C17.916 5.70621 17.5802 6.04199 17.166 6.04199H16.8752V8.74687V13.7469V16.7087C16.8752 17.9513 15.8678 18.9587 14.6252 18.9587H6.37516C5.13252 18.9587 4.12516 17.9513 4.12516 16.7087V13.7469V8.74687V6.04199H3.8335C3.41928 6.04199 3.0835 5.70621 3.0835 5.29199C3.0835 4.87778 3.41928 4.54199 3.8335 4.54199H4.87516H7.04142V4.29199ZM15.3752 13.7469V8.74687V6.04199H13.9581H13.2081H7.79142H7.04142H5.62516V8.74687V13.7469V16.7087C5.62516 17.1229 5.96095 17.4587 6.37516 17.4587H14.6252C15.0394 17.4587 15.3752 17.1229 15.3752 16.7087V13.7469ZM8.54142 4.54199H12.4581V4.29199C12.4581 3.87778 12.1223 3.54199 11.7081 3.54199H9.29142C8.87721 3.54199 8.54142 3.87778 8.54142 4.29199V4.54199ZM8.8335 8.50033C9.24771 8.50033 9.5835 8.83611 9.5835 9.25033V14.2503C9.5835 14.6645 9.24771 15.0003 8.8335 15.0003C8.41928 15.0003 8.0835 14.6645 8.0835 14.2503V9.25033C8.0835 8.83611 8.41928 8.50033 8.8335 8.50033ZM12.9168 9.25033C12.9168 8.83611 12.581 8.50033 12.1668 8.50033C11.7526 8.50033 11.4168 8.83611 11.4168 9.25033V14.2503C11.4168 14.6645 11.7526 15.0003 12.1668 15.0003C12.581 15.0003 12.9168 14.6645 12.9168 14.2503Z"
                                                                fill="" />
                                                        </svg>
                                                    </button>
                                                @endif --}}
                                            </div>
                                        </div>
                                    </td>
                                    @endif
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>

                <div class="flex justify-between items-center border-t px-6 py-4 dark:border-gray-800">
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        Showing {{ $invoices->firstItem() }} to {{ $invoices->lastItem() }} of
                        {{ $invoices->total() }} entries
                    </div>

                    <div class="pagination flex justify-between">
                        {{ $invoices->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
