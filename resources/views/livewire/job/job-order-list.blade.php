<div x-data="{ open: false }">
    <!-- Modal (Filter Options) -->
    <div x-show="open" x-transition @closeModal.window="open = false"
        class="fixed inset-0 z-50 flex items-center justify-center bg-gray-50 bg-opacity-80">
        <div class="w-full max-w-md p-6 bg-white border border-gray-200 rounded-lg shadow-lg dark:border-gray-800">
            <h3 class="text-xl font-semibold text-gray-800">Filter Options</h3>

            <div class="mt-4">
                <form>
                    <div class="mb-4">
                        <div class="flex w-full gap-4">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-600">Start</label>
                                <input onclick="this.showPicker()" type="date" wire:model.change="startDate"
                                    class="w-full p-2 border rounded-md">
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-600">End Date</label>
                                <input onclick="this.showPicker()" type="date" wire:model.change="endDate"
                                    class="w-full p-2 border rounded-md">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-4 mt-4">
                        <button type="button" @click="open = false"
                            class="px-6 py-2 font-semibold text-gray-800 bg-gray-300 rounded-md hover:bg-gray-400">
                            Close
                        </button>
                        <button type="submit" @click="open = false"
                            class="px-6 py-2 font-semibold text-white rounded-md bg-brand-500 hover:bg-brand-700">
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
            class="p-4 border rounded-xl border-success-500 bg-success-50 dark:border-success-500/30 dark:bg-success-500/15">
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
        <div class="p-4 border rounded-xl border-error-500 bg-error-50 dark:border-red-500/30 dark:bg-red-500/10">
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
                <div class="flex flex-col gap-6 px-5 mb-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                    <!-- Title and Filter Dropdown -->
                    {{-- Left side: Title, Status Filter, Date Range --}}
                    <div class="flex flex-wrap items-center gap-4">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                            Job Orders
                        </h3>

                        {{-- Status Dropdown --}}

                        <div>
                            <label
                                class="block mb-1 text-xs font-medium text-gray-700 dark:text-gray-400">Status</label>
                            <select wire:model.change="statusFilter"
                                class="h-8 px-2 text-xs border border-gray-300 rounded-md dark:bg-dark-900 dark:text-white/90">
                                <option value="">All</option>
                                <option value="pending">Pending</option>
                                <option value="printing">Exposing - CTP</option>
                                <option value="dispatching">CTP / Dispatch</option>
                                <option value="designing">Designing - DTP</option>
                                <option value="paused">Paused</option>
                                <option value="ready-to-invoice">Billing</option>
                                <option value="invoicing">Invoicing</option>
                                <option value="invoiced">Invoiced</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>


                        {{-- Date Filters --}}
                        <div class="flex gap-3">
                            <div class="flex-1">
                                <label class="block mb-1 text-xs font-medium text-gray-700 dark:text-gray-400">Start
                                    Date</label>
                                <div class="relative ">
                                    <input type="date" wire:model.change="startDate" onclick="this.showPicker()"
                                        class="block w-full h-8 px-3 py-2 pr-5 text-xs border border-gray-300 rounded-md focus:border-blue-500 focus:outline-none focus:ring-blue-500">
                                    <span
                                        class="absolute text-gray-500 -translate-y-1/2 pointer-events-none top-1/2 right-3 dark:text-gray-400">
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
                                <label class="block mb-1 text-xs font-medium text-gray-700 dark:text-gray-400">To
                                    Date</label>
                                <div class="relative ">
                                    <input type="date" wire:model.change="endDate" onclick="this.showPicker()"
                                        class="block w-full h-8 px-3 py-2 pr-5 text-xs border border-gray-300 rounded-md focus:border-blue-500 focus:outline-none focus:ring-blue-500">
                                    <span
                                        class="absolute text-gray-500 -translate-y-1/2 pointer-events-none top-1/2 right-3 dark:text-gray-400">
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
                                <label
                                    class="block mb-1 text-xs font-medium text-gray-700 dark:text-gray-400">User</label>
                                <div class="relative ">
                                    <select wire:model.change='user'
                                        class="h-8 px-2 text-xs border border-gray-300 rounded-md dark:bg-dark-900 dark:text-white/90">
                                        <option>Select User</option>
                                        @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{$user->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Search Input and Action Buttons -->
                    <div class="flex flex-col gap-2 mt-4 sm:flex-row sm:items-center sm:justify-end">
                        <!-- Search Input -->
                        <div class="relative">
                            <span class="absolute -translate-y-1/2 pointer-events-none top-1/2 left-4">
                                <svg class="fill-gray-500 dark:fill-gray-400" width="15" height="15" viewBox="0 0 20 20"
                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M3.04199 9.37381C3.04199 5.87712 5.87735 3.04218 9.37533 3.04218C12.8733 3.04218 15.7087 5.87712 15.7087 9.37381C15.7087 12.8705 12.8733 15.7055 9.37533 15.7055C5.87735 15.7055 3.04199 12.8705 3.04199 9.37381ZM9.37533 1.54218C5.04926 1.54218 1.54199 5.04835 1.54199 9.37381C1.54199 13.6993 5.04926 17.2055 9.37533 17.2055C11.2676 17.2055 13.0032 16.5346 14.3572 15.4178L17.1773 18.2381C17.4702 18.531 17.945 18.5311 18.2379 18.2382C18.5308 17.9453 18.5309 17.4704 18.238 17.1775L15.4182 14.3575C16.5367 13.0035 17.2087 11.2671 17.2087 9.37381C17.2087 5.04835 13.7014 1.54218 9.37533 1.54218Z"
                                        fill="" />
                                </svg>
                            </span>
                            <input type="text" wire:model.live.debounce.100ms="searchTerm"
                                placeholder="Search Job Orders..."
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-[32px] w-full rounded-lg border border-gray-300 bg-transparent py-1.5 pr-4 pl-[42px] text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden xl:w-[300px] dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                        </div>

                        <!-- Add Job Order Button -->
                        <a wire:navigate href="{{ route('job-order') }}"
                            class="px-6 py-2 text-sm font-medium text-white transition duration-300 ease-in-out transform bg-blue-600 rounded-md shadow-md hover:bg-blue-700 hover:scale-105"
                            style="background-color:#465FFF;padding: 8px 15px;">
                            + Add Job Order
                        </a>
                    </div>
                </div>

                {{-- Optional text --}}
                @if ($startDate && $endDate)
                <div class="px-6 text-sm text-error-600 dark:text-error-600">
                    Job list filtered from <strong>{{ $startDate }}</strong> to
                    <strong>{{ $endDate }}</strong>
                </div>
                @endif


                <div class="max-w-full px-5 overflow-x-auto custom-scrollbar sm:px-6">
                    <table class="resizable-table min-w-full">
                        <thead class="py-2 border-gray-100 border-y dark:border-gray-800 ">
                            <tr>
                                <th class="py-2 font-normal whitespace-nowrap" style="width: 3%;"
                                    >
                                    <div class="flex items-center">
                                        <p class="text-gray-500 text-theme-xs dark:text-gray-400"> ID</p>
                                    </div>
                                </th>
                                <th class="py-2 font-normal whitespace-nowrap" style="width: 12%;"
                                    >
                                    <div class="flex items-center">
                                        <p class="text-gray-500 text-theme-xs dark:text-gray-400">Job Number</p>
                                    </div>
                                </th>
                                <th class="py-2 font-normal whitespace-nowrap" style="width: 16%;"
                                    >
                                    <div class="flex items-center">
                                        <p class="text-gray-500 text-theme-xs dark:text-gray-400">Customer</p>
                                    </div>
                                </th>
                                <th class="py-2 font-normal whitespace-nowrap" style="width: 18%;"
                                     style="width:10%;">
                                    <div class="flex items-center">
                                        <p class="text-gray-500 text-theme-xs dark:text-gray-400">Description</p>
                                    </div>
                                </th>
                                <th class="py-2 font-normal whitespace-nowrap" style="width: 12%;"
                                     style="width:10%;">
                                    <div class="flex items-center">
                                        <p class="text-gray-500 text-theme-xs dark:text-gray-400">Note</p>
                                    </div>
                                </th>
                                <th class="py-2 font-normal whitespace-nowrap" >
                                    <div class="flex items-center">
                                        <p class="text-gray-500 text-theme-xs dark:text-gray-400">Created</p>
                                    </div>
                                </th>

                                <th class="py-2 font-normal whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-500 text-theme-xs dark:text-gray-400">Assigned To</p>
                                    </div>
                                </th>
                                <th class="py-2 font-normal whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-500 text-theme-xs dark:text-gray-400">Status</p>
                                    </div>
                                </th>
                                <th class="py-2 font-normal text-right whitespace-nowrap" style="width: 5%;">
                                    <div class="flex items-center justify-start ">
                                        <p class="text-gray-500 text-theme-xs dark:text-gray-400">Actions</p>
                                    </div>
                                </th>

                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @foreach ($jobOrders as $jobOrder)
                            @php
                            $isExternalBranch = $jobOrder->branch_id !== auth()->user()->branch_id;
                            $isInvoiceBranch = $jobOrder->invoice_branch !== auth()->user()->branch_id;

                            // $isAccessible =
                            // ($jobOrder->branch_id === auth()->user()->branch_id &&
                            // $jobOrder->status === 'ready-to-invoice') ||
                            // auth()->user()->mode === 'admin';
                            $isAccessible = true;
                            @endphp


                            <tr
                                class="cursor-pointer {{ $jobOrder->status === 'cancelled' ? 'bg-error-50' : ($loop->even ? 'bg-gray-200' : 'bg-white') }} dark:{{ $loop->even ? 'bg-gray-800/50' : 'bg-gray-900' }}"
                                style="background-color: {{ $jobOrder->status === 'cancelled' ? '#fef2f2' : ($loop->even ? '#F2F4F7' : '#ffffff') }};"
                                onmouseover="this.style.backgroundColor='#fef3c7'"
                                onmouseout="this.style.backgroundColor='{{ $jobOrder->status === 'cancelled' ? '#fef2f2' : ($loop->even ? '#F2F4F7' : '#ffffff') }}'">
                                <td wire:navigate href="{{ route('job-orders.view', $jobOrder->id) }}"
                                    class="py-1 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-xs dark:text-gray-400">
                                            {{ $jobOrder->id }}
                                        </p>
                                    </div>
                                </td>
                                <td @if ($isAccessible) wire:navigate
                                    href="{{ route('job-orders.view', $jobOrder->id) }}"
                                    class="cursor-pointer" @else class="opacity-50 cursor-not-allowed"
                                    @endif class="py-1 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-xs dark:text-gray-400">
                                            {{ $jobOrder->job_number }}</p>
                                    </div>
                                </td>

                                <td @if ($isAccessible) wire:navigate
                                    href="{{ route('job-orders.view', $jobOrder->id) }}"
                                    class="cursor-pointer" @else class="opacity-50 cursor-not-allowed"
                                    @endif class="py-1 whitespace-nowrap">
                                                                        <div class="flex items-center relative"
                                         x-data="{ showPopup: false, isTopRow: {{ $loop->index < 3 ? 'true' : 'false' }} }"
                                         @mouseenter="showPopup = true"
                                         @mouseleave="showPopup = false">
                                        <p class="text-gray-700 text-theme-xs dark:text-gray-400">
                                            {{ $jobOrder->customer?->name ?? 'N/A' }}</p>

                                                                                                                        <!-- Popup for items and quantities -->
                                        <div x-show="showPopup"
                                             x-transition:enter="transition ease-out duration-200"
                                             x-transition:enter-start="opacity-0 scale-95"
                                             x-transition:enter-end="opacity-100 scale-100"
                                             x-transition:leave="transition ease-in duration-150"
                                             x-transition:leave-start="opacity-100 scale-100"
                                             x-transition:leave-end="opacity-0 scale-95"
                                             class="absolute z-50 w-64 p-3 text-sm bg-white border border-gray-200 rounded-lg shadow-lg dark:bg-gray-800 dark:border-gray-700"
                                             :style="isTopRow ? 'top: 100%; left: 0; min-width: 250px; margin-top: 5px;' : 'bottom: 100%; left: 0; min-width: 250px; margin-bottom: 5px;'">
                                            <div class="mb-2 font-semibold text-gray-800 dark:text-white">
                                                Items & Quantities
                                            </div>
                                            @if($jobOrder->orderItems && $jobOrder->orderItems->count() > 0)
                                                <div class="space-y-1">
                                                    @foreach($jobOrder->orderItems as $item)
                                                        <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-700 last:border-b-0">
                                                            <span class="text-gray-700 dark:text-gray-300">{{ $item->item->item_name ?? 'N/A' }}</span>
                                                            <span class="font-medium text-gray-900 dark:text-white">{{ $item->quantity ?? 0 }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="text-gray-500 dark:text-gray-400">
                                                    No items found
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td @if ($isAccessible) wire:navigate
                                    href="{{ route('job-orders.view', $jobOrder->id) }}"
                                    class="cursor-pointer" @else class="opacity-50 cursor-not-allowed"
                                    @endif class="py-1 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-xs dark:text-gray-400">
                                            {{ $jobOrder->description }}
                                        </p>
                                    </div>
                                </td>
                                <td @if ($isAccessible) wire:navigate
                                    href="{{ route('job-orders.view', $jobOrder->id) }}"
                                    class="cursor-pointer" @else class="opacity-50 cursor-not-allowed"
                                    @endif class="py-1 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-xs dark:text-gray-400">
                                            {{ $jobOrder->special_instruction }}
                                        </p>
                                    </div>
                                </td>
                                <td @if ($isAccessible) wire:navigate
                                    href="{{ route('job-orders.view', $jobOrder->id) }}"
                                    class="cursor-pointer" @else class="opacity-50 cursor-not-allowed"
                                    @endif class="py-1 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-xs dark:text-gray-400">
                                            {{ $jobOrder->created_at->toDateString() }}</p>
                                    </div>
                                </td>

                                <td @if ($isAccessible) wire:navigate
                                    href="{{ route('job-orders.view', $jobOrder->id) }}"
                                    class="cursor-pointer" @else class="opacity-50 cursor-not-allowed"
                                    @endif class="py-1 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-xs dark:text-gray-400">
                                        {{ $jobOrder->assignTo ? $jobOrder->assignTo->name : 'Unassigned' }}
                                        </p>
                                    </div>
                                </td>

                                <td @if ($isAccessible) wire:navigate
                                    href="{{ route('job-orders.view', $jobOrder->id) }}"
                                    class="cursor-pointer" @else class="opacity-50 cursor-not-allowed"
                                    @endif class="py-1 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-gray-700 text-theme-xs dark:text-gray-400">


                                            @if ($jobOrder->status === 'cancelled')
                                            <span
                                                class="inline-flex items-center justify-center gap-1 rounded-full bg-warning-100 px-2.5 py-0.5 text-xs font-medium text-error-600 dark:bg-warning-500/15 dark:text-orange-400">
                                                Cancelled
                                            </span>
                                            @elseif($jobOrder->status === 'printing')
                                            <span
                                                class="inline-flex items-center justify-center gap-1 rounded-full bg-warning-50 px-2.5 py-0.5 text-xs font-medium text-warning-600 dark:bg-warning-500/15 dark:text-orange-400">
                                                Exposing - CTP
                                            </span>
                                            @elseif ($jobOrder->status === 'pending')
                                            <span
                                                class="inline-flex items-center justify-center gap-1 rounded-full bg-error-50 px-2.5 py-0.5 text-xs font-medium text-error-600 dark:bg-error-500/15 dark:text-error-500">
                                                {{ ucfirst($jobOrder->status) }}
                                            </span>
                                            @elseif ($jobOrder->status === 'dispatching' || $jobOrder->status ===
                                            'dispatched')
                                            <span
                                                class="inline-flex items-center justify-center gap-1 rounded-full bg-warning-50 px-2.5 py-0.5 text-xs font-medium text-error-600 dark:bg-error-500/15 dark:text-error-500">
                                                CTP / Dispatch
                                            </span>
                                            @elseif ($jobOrder->status === 'designing')
                                            <span
                                                class="inline-flex items-center justify-center gap-1 rounded-full bg-warning-50 px-2.5 py-0.5 text-xs font-medium text-error-600 dark:bg-error-500/15 dark:text-error-500">
                                                Designing - DTP
                                            </span>
                                            @elseif ($jobOrder->status === 'ready-to-invoice')
                                            <span
                                                class="inline-flex items-center justify-center gap-1 rounded-full bg-blue-light-50 px-2.5 py-0.5 text-xs font-medium text-blue-light-500 dark:bg-blue-light-500/15 dark:text-blue-light-500">
                                                Billing
                                            </span>
                                            @elseif ($jobOrder->status === 'invoicing')
                                            <span
                                                class="inline-flex items-center justify-center gap-1 rounded-full bg-success-50 px-2.5 py-0.5 text-xs font-medium text-success-600 dark:bg-success-500/15 dark:text-success-500">
                                                Invoicing
                                            </span>
                                            @elseif ($jobOrder->status === 'invoiced')
                                            <span
                                                class="inline-flex items-center justify-center gap-1 rounded-full bg-success-50 px-2.5 py-0.5 text-xs font-medium text-success-600 dark:bg-success-500/15 dark:text-success-500">
                                                Invoiced
                                            </span>
                                            @elseif ($jobOrder->status === 'paused')
                                            <span
                                                class="inline-flex items-center justify-center gap-1 rounded-full bg-warning-50 px-2.5 py-0.5 text-xs font-medium text-error-600 dark:bg-success-500/15 dark:text-success-500">
                                                Paused
                                                <div class="relative flex items-center justify-center text-gray-500 transition-colors bg-white border rounded-full hover:text-dark-900 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white"
                                                    @click.prevent="dropdownOpen = ! dropdownOpen; notifying = false">
                                                    <span :class="!notifying ? 'hidden' : 'flex'"
                                                        class="absolute top-0.8 left-2 z-1 h-3 w-3 rounded-full bg-orange-400">
                                                        <span
                                                            class="absolute inline-flex w-full h-full bg-orange-400 rounded-full opacity-75 -z-1 animate-ping"></span>
                                                    </span>

                                                </div>
                                            </span>
                                            @endif


                                        </p>
                                    </div>
                                </td>

                                <td class="py-1 text-left whitespace-nowrap align-center">
                                    @if($jobOrder->status != 'cancelled')

                                    <div class="flex items-center justify-center space-x-3"
                                        x-data="{ showConfirm: false, showConfirmEdit: false }">

                                        <!-- View Button -->
                                        <a wire:navigate href="{{ route('job-order.view', $jobOrder->id) }}"
                                            class="text-gray-400 hover:text-gray-800 dark:text-gray-400 dark:hover:text-blue-500"
                                            title="View">
                                            <svg class="fill-current" width="18" height="18" viewBox="0 0 24 24"
                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M1.5 12C1.5 12 5.25 4.5 12 4.5C18.75 4.5 22.5 12 22.5 12C22.5 12 18.75 19.5 12 19.5C5.25 19.5 1.5 12 1.5 12ZM12 15C13.6569 15 15 13.6569 15 12C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15Z"
                                                    fill="" />
                                            </svg>
                                        </a>

                                        @if ($jobOrder->status === 'pending' && ($role === 'admin' || $role ===
                                        'manager'))
                                        <!-- Edit Button -->
                                        <a href="{{ route('job-orders.edit', $jobOrder->id) }}" wire:navigate
                                            class="px-4 text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-white/90"
                                            title="Edit">
                                            <svg class="fill-current" width="19" height="19" viewBox="0 0 21 21"
                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M17.0911 3.53206C16.2124 2.65338 14.7878 2.65338 13.9091 3.53206L5.6074 11.8337C5.29899 12.1421 5.08687 12.5335 4.99684 12.9603L4.26177 16.445C4.20943 16.6931 4.286 16.9508 4.46529 17.1301C4.64458 17.3094 4.90232 17.3859 5.15042 17.3336L8.63507 16.5985C9.06184 16.5085 9.45324 16.2964 9.76165 15.988L18.0633 7.68631C18.942 6.80763 18.942 5.38301 18.0633 4.50433L17.0911 3.53206ZM14.9697 4.59272C15.2626 4.29982 15.7375 4.29982 16.0304 4.59272L17.0027 5.56499C17.2956 5.85788 17.2956 6.33276 17.0027 6.62565L16.1043 7.52402L14.0714 5.49109L14.9697 4.59272ZM13.0107 6.55175L6.66806 12.8944C6.56526 12.9972 6.49455 13.1277 6.46454 13.2699L5.96704 15.6283L8.32547 15.1308C8.46772 15.1008 8.59819 15.0301 8.70099 14.9273L15.0436 8.58468L13.0107 6.55175Z"
                                                    fill="" />
                                            </svg>
                                        </a>
                                        @elseif (($jobOrder->status === 'printing' || $jobOrder->status ===
                                        'dispatching' || $jobOrder->status === 'dispatched') && $role === 'dispatch')
                                        <a @click="showConfirmEdit = true"
                                            class="px-4 text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-white/90"
                                            title="Edit" >

                                            <svg class="fill-current" width="19" height="19" viewBox="0 0 21 21"
                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M17.0911 3.53206C16.2124 2.65338 14.7878 2.65338 13.9091 3.53206L5.6074 11.8337C5.29899 12.1421 5.08687 12.5335 4.99684 12.9603L4.26177 16.445C4.20943 16.6931 4.286 16.9508 4.46529 17.1301C4.64458 17.3094 4.90232 17.3859 5.15042 17.3336L8.63507 16.5985C9.06184 16.5085 9.45324 16.2964 9.76165 15.988L18.0633 7.68631C18.942 6.80763 18.942 5.38301 18.0633 4.50433L17.0911 3.53206ZM14.9697 4.59272C15.2626 4.29982 15.7375 4.29982 16.0304 4.59272L17.0027 5.56499C17.2956 5.85788 17.2956 6.33276 17.0027 6.62565L16.1043 7.52402L14.0714 5.49109L14.9697 4.59272ZM13.0107 6.55175L6.66806 12.8944C6.56526 12.9972 6.49455 13.1277 6.46454 13.2699L5.96704 15.6283L8.32547 15.1308C8.46772 15.1008 8.59819 15.0301 8.70099 14.9273L15.0436 8.58468L13.0107 6.55175Z"
                                                    fill="" />
                                            </svg>
                                        </a>
                                        @elseif ($jobOrder->status === 'paused' && ($role === 'admin' || $role ===
                                        'manager'))
                                        <a href="{{ route('job-orders.edit', $jobOrder->id) }}" wire:navigate
                                            class="px-4 text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-white/90"
                                            title="Edit">
                                            <svg class="fill-current" width="19" height="19" viewBox="0 0 21 21"
                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M17.0911 3.53206C16.2124 2.65338 14.7878 2.65338 13.9091 3.53206L5.6074 11.8337C5.29899 12.1421 5.08687 12.5335 4.99684 12.9603L4.26177 16.445C4.20943 16.6931 4.286 16.9508 4.46529 17.1301C4.64458 17.3094 4.90232 17.3859 5.15042 17.3336L8.63507 16.5985C9.06184 16.5085 9.45324 16.2964 9.76165 15.988L18.0633 7.68631C18.942 6.80763 18.942 5.38301 18.0633 4.50433L17.0911 3.53206ZM14.9697 4.59272C15.2626 4.29982 15.7375 4.29982 16.0304 4.59272L17.0027 5.56499C17.2956 5.85788 17.2956 6.33276 17.0027 6.62565L16.1043 7.52402L14.0714 5.49109L14.9697 4.59272ZM13.0107 6.55175L6.66806 12.8944C6.56526 12.9972 6.49455 13.1277 6.46454 13.2699L5.96704 15.6283L8.32547 15.1308C8.46772 15.1008 8.59819 15.0301 8.70099 14.9273L15.0436 8.58468L13.0107 6.55175Z"
                                                    fill="" />
                                            </svg>



                                        </a>
                                        @else
                                        <a class="px-4 text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-white/90"
                                            title="Edit">
                                            <svg class="fill-current" width="19" height="19" viewBox="0 0 21 21"
                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M17.0911 3.53206C16.2124 2.65338 14.7878 2.65338 13.9091 3.53206L5.6074 11.8337C5.29899 12.1421 5.08687 12.5335 4.99684 12.9603L4.26177 16.445C4.20943 16.6931 4.286 16.9508 4.46529 17.1301C4.64458 17.3094 4.90232 17.3859 5.15042 17.3336L8.63507 16.5985C9.06184 16.5085 9.45324 16.2964 9.76165 15.988L18.0633 7.68631C18.942 6.80763 18.942 5.38301 18.0633 4.50433L17.0911 3.53206ZM14.9697 4.59272C15.2626 4.29982 15.7375 4.29982 16.0304 4.59272L17.0027 5.56499C17.2956 5.85788 17.2956 6.33276 17.0027 6.62565L16.1043 7.52402L14.0714 5.49109L14.9697 4.59272ZM13.0107 6.55175L6.66806 12.8944C6.56526 12.9972 6.49455 13.1277 6.46454 13.2699L5.96704 15.6283L8.32547 15.1308C8.46772 15.1008 8.59819 15.0301 8.70099 14.9273L15.0436 8.58468L13.0107 6.55175Z"
                                                    fill="" />
                                            </svg>
                                        </a>
                                        @endif

                                        <!-- Delete Button -->

                                        <!-- Delete Icon Button -->
                                        @if ($role === 'admin' || $role === 'billing'|| $role === 'accounts')
                                        <button @click="showConfirm = true"
                                            class="text-gray-500 hover:text-error-500 dark:text-gray-400 dark:hover:text-error-500"
                                            title="Delete">
                                            <svg class="fill-current" width="18" height="18" viewBox="0 0 21 21"
                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M7.04142 4.29199C7.04142 3.04935 8.04878 2.04199 9.29142 2.04199H11.7081C12.9507 2.04199 13.9581 3.04935 13.9581 4.29199V4.54199H16.1252H17.166C17.5802 4.54199 17.916 4.87778 17.916 5.29199C17.916 5.70621 17.5802 6.04199 17.166 6.04199H16.8752V8.74687V13.7469V16.7087C16.8752 17.9513 15.8678 18.9587 14.6252 18.9587H6.37516C5.13252 18.9587 4.12516 17.9513 4.12516 16.7087V13.7469V8.74687V6.04199H3.8335C3.41928 6.04199 3.0835 5.70621 3.0835 5.29199C3.0835 4.87778 3.41928 4.54199 3.8335 4.54199H4.87516H7.04142V4.29199ZM15.3752 13.7469V8.74687V6.04199H13.9581H13.2081H7.79142H7.04142H5.62516V8.74687V13.7469V16.7087C5.62516 17.1229 5.96095 17.4587 6.37516 17.4587H14.6252C15.0394 17.4587 15.3752 17.1229 15.3752 16.7087V13.7469ZM8.54142 4.54199H12.4581V4.29199C12.4581 3.87778 12.1223 3.54199 11.7081 3.54199H9.29142C8.87721 3.54199 8.54142 3.87778 8.54142 4.29199V4.54199ZM8.8335 8.50033C9.24771 8.50033 9.5835 8.83611 9.5835 9.25033V14.2503C9.5835 14.6645 9.24771 15.0003 8.8335 15.0003C8.41928 15.0003 8.0835 14.6645 8.0835 14.2503V9.25033C8.0835 8.83611 8.41928 8.50033 8.8335 8.50033ZM12.9168 9.25033C12.9168 8.83611 12.581 8.50033 12.1668 8.50033C11.7526 8.50033 11.4168 8.83611 11.4168 9.25033V14.2503C11.4168 14.6645 11.7526 15.0003 12.1668 15.0003C12.581 15.0003 12.9168 14.6645 12.9168 14.2503Z"
                                                    fill="" />
                                            </svg>
                                        </button>
                                        @else
                                        <button
                                            class="text-gray-300 hover:text-error-300 dark:text-gray-300 dark:hover:text-error-300"
                                            title="Delete">
                                            <svg class="fill-current" width="18" height="18" viewBox="0 0 21 21"
                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M7.04142 4.29199C7.04142 3.04935 8.04878 2.04199 9.29142 2.04199H11.7081C12.9507 2.04199 13.9581 3.04935 13.9581 4.29199V4.54199H16.1252H17.166C17.5802 4.54199 17.916 4.87778 17.916 5.29199C17.916 5.70621 17.5802 6.04199 17.166 6.04199H16.8752V8.74687V13.7469V16.7087C16.8752 17.9513 15.8678 18.9587 14.6252 18.9587H6.37516C5.13252 18.9587 4.12516 17.9513 4.12516 16.7087V13.7469V8.74687V6.04199H3.8335C3.41928 6.04199 3.0835 5.70621 3.0835 5.29199C3.0835 4.87778 3.41928 4.54199 3.8335 4.54199H4.87516H7.04142V4.29199ZM15.3752 13.7469V8.74687V6.04199H13.9581H13.2081H7.79142H7.04142H5.62516V8.74687V13.7469V16.7087C5.62516 17.1229 5.96095 17.4587 6.37516 17.4587H14.6252C15.0394 17.4587 15.3752 17.1229 15.3752 16.7087V13.7469ZM8.54142 4.54199H12.4581V4.29199C12.4581 3.87778 12.1223 3.54199 11.7081 3.54199H9.29142C8.87721 3.54199 8.54142 3.87778 8.54142 4.29199V4.54199ZM8.8335 8.50033C9.24771 8.50033 9.5835 8.83611 9.5835 9.25033V14.2503C9.5835 14.6645 9.24771 15.0003 8.8335 15.0003C8.41928 15.0003 8.0835 14.6645 8.0835 14.2503V9.25033C8.0835 8.83611 8.41928 8.50033 8.8335 8.50033ZM12.9168 9.25033C12.9168 8.83611 12.581 8.50033 12.1668 8.50033C11.7526 8.50033 11.4168 8.83611 11.4168 9.25033V14.2503C11.4168 14.6645 11.7526 15.0003 12.1668 15.0003C12.581 15.0003 12.9168 14.6645 12.9168 14.2503Z"
                                                    fill="" />
                                            </svg>
                                        </button>
                                        @endif
                                        <!-- Custom Styled Confirmation Modal -->
                                        <div x-show="showConfirm"
                                            class="fixed inset-0 z-50 flex items-center justify-center p-5 overflow-y-auto">
                                            <div
                                                class="modal-close-btn fixed inset-0 h-full w-full bg-gray-400/50 backdrop-blur-[30px]">
                                            </div>

                                            <div class="flex flex-col px-4 py-4 overflow-y-auto no-scrollbar">
                                                <div @click.outside="showConfirm = false"
                                                    class="relative w-full max-w-[507px] rounded-3xl bg-white p-6 dark:bg-gray-900 lg:p-10">
                                                    <div class="text-center">
                                                        <h4
                                                            class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">
                                                            Confirm Deletion
                                                        </h4>
                                                        <p class="text-sm leading-6 text-gray-500 dark:text-gray-400">
                                                            @if (!in_array($jobOrder->status, ['pending',
                                                            'designing','printing']))
                                                            Permission denied!
                                                            @else
                                                            Are you sure you want to delete this job order?
                                                            Click
                                                            Delete to confirm.
                                                            @endif
                                                        </p>
                                                        <div class="flex items-center justify-center w-full gap-3 mt-8">
                                                            <button @click="showConfirm = false" type="button"
                                                                class="flex justify-center rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">
                                                                Cancel
                                                            </button>
                                                            <button type="button"
                                                                @click="$wire.deleteJobOrder({{ $jobOrder->id }}); showConfirm = false"
                                                                class="flex justify-center px-4 py-3 text-sm font-medium text-white rounded-lg bg-brand-500 shadow-theme-xs hover:bg-error-600"
                                                                @if (!in_array($jobOrder->status, ['pending',
                                                                'designing','printing'])) disabled @endif>
                                                                Delete
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div x-show="showConfirmEdit"
                                            class="fixed inset-0 z-50 flex items-center justify-center p-5 overflow-y-auto">
                                            <div
                                                class="modal-close-btn fixed inset-0 h-full w-full bg-gray-400/50 backdrop-blur-[30px]">
                                            </div>

                                            <div class="flex flex-col px-4 py-4 overflow-y-auto no-scrollbar">
                                                <div @click.outside="showConfirmEdit = false"
                                                    class="relative w-full max-w-[507px] rounded-3xl bg-white p-6 dark:bg-gray-900 lg:p-10">
                                                    <div class="text-center">
                                                        <h4
                                                            class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">
                                                            Confirm Edit
                                                        </h4>
                                                        <div class="text-center">
                                                            <p
                                                                class="max-w-[400px] text-sm leading-6 text-gray-500 dark:text-gray-400 break-words">
                                                                Are you sure you want to edit this job order? It
                                                                will hold the process until </br> approved. Click
                                                                Edit to
                                                                confirm.
                                                            </p>
                                                        </div>

                                                        <div class="flex items-center justify-center w-full gap-3 mt-8">
                                                            <button @click="showConfirmEdit = false" type="button"
                                                                class="flex justify-center rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">
                                                                Cancel
                                                            </button>
                                                            <a href="{{ route('job-orders.edit', $jobOrder->id) }}"
                                                                wire:navigate @click="showConfirmEdit = false"
                                                                class="flex justify-center px-4 py-3 text-sm font-medium text-white rounded-lg bg-brand-500 shadow-theme-xs hover:bg-error-600">
                                                                Edit
                                                            </a>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
                <div class="flex items-center justify-between px-6 py-4 border-t dark:border-gray-800">
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        Showing {{ $jobOrders->firstItem() }} to {{ $jobOrders->lastItem() }} of
                        {{ $jobOrders->total() }} entries
                    </div>

                    <div class="flex justify-between pagination">
                        {{ $jobOrders->links('vendor.pagination.custom-tailwind') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function reverseText(input) {
            const value = input.value;
            input.value = value.split('').reverse().join('');
        }
    </script>

    <style>
        .resizable-table th {
            position: relative;
        }
        .resizer {
            position: absolute;
            right: 0;
            top: 0;
            width: 5px;
            cursor: col-resize;
            user-select: none;
            height: 100%;
            z-index: 1;
            background: #ffffff; /* Black */
            opacity: 0.7;
            border-radius: 2px;
            transition: background 0.2s;
        }
        .resizer:hover {
            background: #000000; /* Black on hover */
            opacity: 1;
        }
    </style>

    <script>
    document.querySelectorAll('.resizable-table th').forEach(function(th) {
        const resizer = document.createElement('div');
        resizer.classList.add('resizer');
        th.appendChild(resizer);
        resizer.addEventListener('mousedown', initResize);

        function initResize(e) {
            e.preventDefault();
            window.addEventListener('mousemove', resizeColumn);
            window.addEventListener('mouseup', stopResize);
            let startX = e.pageX;
            let startWidth = th.offsetWidth;

            function resizeColumn(e) {
                th.style.width = (startWidth + (e.pageX - startX)) + 'px';
            }
            function stopResize() {
                window.removeEventListener('mousemove', resizeColumn);
                window.removeEventListener('mouseup', stopResize);
            }
        }
    });
    </script>
