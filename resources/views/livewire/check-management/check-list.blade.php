<div class="">
    @if (session()->has('message'))
        <div class="px-4 py-2 mb-4 text-green-700 bg-green-100 border border-green-400 rounded">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="px-4 py-2 mb-4 text-red-700 bg-red-100 border border-red-400 rounded">
            {{ session('error') }}
        </div>
    @endif
    
    
    <div class="max-w-full overflow-x-hidden">
        <div class="h-screen bg-white rounded-lg shadow-md dark:bg-gray-900">
            <div class="flex flex-row justify-start">
                <div class="flex-1 p-6 bg-white rounded-lg shadow-md dark:bg-gray-900">
                    <div class="bg-white border border-gray-200 rounded-2xl dark:border-gray-700 dark:bg-gray-900">
                        <!-- Header with Add Button -->
                        <div class="flex items-center justify-between px-5 py-4 sm:px-6 sm:py-5">
                            <h3 class="text-base font-medium text-gray-800 dark:text-white">
                                Check Management
                            </h3>
                            <a href="{{ route('check-management.create') }}"
                                class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500/50">
                                Add New Cheque
                            </a>
                        </div>

                        <!-- Customer Search -->
                        <div class="px-5 py-4 form-group">
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">
                                        Customer Name
                                        @if ($selectedCustomerId)
                                            <span class="ml-2 text-xs text-gray-500 dark:text-gray-400">(ID:
                                                {{ $selectedCustomerId }})</span>
                                        @endif
                                    </label>
                                    <div class="flex gap-2 mt-1">
                                        <input type="text" wire:model.live.debounce.300ms="searchCustomer"
                                            placeholder="Search customer"
                                            class="flex-1 w-full h-8 px-3 py-2 text-xs text-gray-800 bg-white border border-gray-300 rounded-lg shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-600 dark:bg-gray-800 dark:text-white placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:outline-none" />
                                        @if ($selectedCustomerId)
                                            <button wire:click="clearCustomerFilter"
                                                class="text-red-500 hover:text-red-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Customer Search Results -->
                        @if (!empty($searchResultsCustomer))
                            <div
                                class="max-w-full px-5 pb-4 overflow-x-auto border border-gray-200 dark:border-gray-700 custom-scrollbar">
                                <table class="w-full">
                                    <thead>
                                        <tr class="border-t border-gray-100 dark:border-gray-800">
                                            <th class="px-3 py-3 text-left">
                                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                    id
                                                </p>
                                            </th>
                                            <th class="px-3 py-3 text-left">
                                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                    Name
                                                </p>
                                            </th>
                                            <th class="px-6 py-3 text-left">
                                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                    Contact
                                                </p>
                                            </th>
                                            <th class="px-6 py-3 text-left">
                                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                    Email
                                                </p>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($searchResultsCustomer as $customer)
                                            <tr wire:click="assignCustomer({{ $customer['id'] }})"
                                                class="border-t border-gray-100 cursor-pointer dark:border-gray-800 hover:bg-gray-200">
                                                <td class="px-2 py-3.5">
                                                    <p
                                                        class="font-medium text-gray-500 text-theme-sm dark:text-white/90">
                                                        {{ $customer['id'] }}
                                                    </p>
                                                </td>
                                                <td class="px-2 py-3.5">
                                                    <p
                                                        class="font-medium text-gray-500 text-theme-sm dark:text-white/90">
                                                        {{ $customer['name'] }}
                                                    </p>
                                                </td>
                                                <td class="px-6 py-3.5">
                                                    <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                        {{ $customer['phone'] }}
                                                    </p>
                                                </td>
                                                <td class="px-6 py-3.5">
                                                    <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                        {{ $customer['email'] }}
                                                    </p>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        <!-- Filters -->
                        <div class="p-4">
                            <div class="flex gap-4 p-2 mb-8">
                                <!-- Search -->
                                <div class="flex-1 gap-2">
                                    <label class="block mb-1 text-xs font-medium text-gray-700 dark:text-gray-300">
                                        Search
                                    </label>
                                    <input wire:model.live.debounce.300ms="search" type="text"
                                        placeholder="Search by cheque number, customer, etc..."
                                        class="w-full h-10 px-3 py-2 text-xs text-gray-800 bg-white border border-gray-300 rounded-lg shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-600 dark:bg-gray-800 dark:text-white placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:outline-none">
                                </div>

                                <!-- Status Filter -->
                                <div class='flex-1 gap-2'>
                                    <label class="block mb-1 text-xs font-medium text-gray-700 dark:text-gray-300">
                                        Status
                                    </label>
                                    <select wire:model.live="status"
                                        class="w-full h-10 px-3 py-2 text-xs text-gray-800 bg-white border border-gray-300 rounded-lg shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-600 dark:bg-gray-800 dark:text-white placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:outline-none">
                                        <option value="">All Status</option>
                                        <option value="pending">Pending</option>
                                        <option value="deposited">Deposited</option>
                                        <option value="return">Return</option>
                                        <option value="realize">Realize</option>
                                        <option value="cancel">Cancel</option>
                                    </select>
                                </div>

                                <!-- Date Filter -->
                                <div class='flex-1 gap-2'>
                                    <label class="block mb-1 text-xs font-medium text-gray-700 dark:text-gray-300">
                                        Cheque Date
                                    </label>
                                    <input type="date" wire:model.live="dateFilter" onclick="this.showPicker()"
                                        class="w-full h-10 px-3 py-2 text-xs text-gray-800 bg-white border border-gray-300 rounded-lg shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-600 dark:bg-gray-800 dark:text-white placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:outline-none">
                                </div>
                            </div>
                            
                            <!-- Process Actions -->
                            <div class="flex items-center justify-between gap-4 mb-4">
                                <div>
                                    @if (count($selectedCheques) > 0)
                                        <span class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ count($selectedCheques) }} cheque(s) selected
                                        </span>
                                    @endif
                                </div>
                                <div class="flex gap-4">
                                    <div class="relative inline-block" x-data="{ open: false }">
                                        <button type="button" @click="open = !open"
                                            class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500/50">
                                            Process
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                                        <div x-show="open" @click.away="open = false"
                                            x-transition:enter="transition ease-out duration-100"
                                            x-transition:enter-start="transform opacity-0 scale-95"
                                            x-transition:enter-end="transform opacity-100 scale-100"
                                            x-transition:leave="transition ease-in duration-75"
                                            x-transition:leave-start="transform opacity-100 scale-100"
                                            x-transition:leave-end="transform opacity-0 scale-95"
                                            class="absolute right-0 z-50 w-48 mt-2 text-sm bg-white border border-gray-200 rounded-lg shadow-lg dark:bg-gray-800 dark:border-gray-700">
                                            <div class="py-1">
                                                <button
                                                    wire:click="bulkUpdateStatus('deposited'); $nextTick(() => { open = false })"
                                                    class="block w-full px-4 py-2 text-left text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                    Deposited
                                                </button>
                                                <button
                                                    wire:click="bulkUpdateStatus('return'); $nextTick(() => { open = false })"
                                                    class="block w-full px-4 py-2 text-left text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                    Return
                                                </button>
                                                <button
                                                    wire:click="bulkUpdateStatus('realize'); $nextTick(() => { open = false })"
                                                    class="block w-full px-4 py-2 text-left text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                    Realize
                                                </button>
                                                <button
                                                    wire:click="bulkUpdateStatus('cancel'); $nextTick(() => { open = false })"
                                                    class="block w-full px-4 py-2 text-left text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                    Cancel
                                                </button>
                                                <button
                                                    wire:click="printSelectedCheques(); $nextTick(() => { open = false })"
                                                    class="block w-full px-4 py-2 text-left text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                    Print Selected
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <button wire:click="printFilteredCheques()"
                                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500/50">
                                        Print All Filtered
                                    </button>
                                </div>
                            </div>

                            <!-- Cheques Table -->
                            <div
                                class="overflow-x-auto border border-gray-200 rounded-lg dark:border-gray-700 custom-scrollbar">
                                <table class="min-w-full text-sm divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-800">
                                        <tr>
                                            <th
                                                class="px-3 py-2 text-xs font-medium tracking-wider text-left text-gray-500 dark:text-gray-400">
                                                <input type="checkbox" wire:model="selectAll"
                                                    @change="$wire.toggleSelectAll($event.target.checked)"
                                                    class="w-4 h-4 border-gray-300 rounded dark:border-gray-600 text-brand-500 focus:ring-brand-500">
                                            </th>
                                            <th
                                                class="px-3 py-2 text-xs font-medium tracking-wider text-left text-gray-500 dark:text-gray-400">
                                                Customer</th>
                                            <th
                                                class="px-3 py-2 text-xs font-medium tracking-wider text-left text-gray-500 dark:text-gray-400">
                                                Cheque No.</th>
                                            <th
                                                class="px-3 py-2 text-xs font-medium tracking-wider text-left text-gray-500 dark:text-gray-400">
                                                Bank</th>
                                            <th
                                                class="px-3 py-2 text-xs font-medium tracking-wider text-left text-gray-500 dark:text-gray-400">
                                                Branch</th>
                                            <th
                                                class="px-3 py-2 text-xs font-medium tracking-wider text-left text-gray-500 dark:text-gray-400">
                                                Amount</th>
                                            <th
                                                class="px-3 py-2 text-xs font-medium tracking-wider text-left text-gray-500 dark:text-gray-400">
                                                Invoices</th>
                                            <th
                                                class="px-3 py-2 text-xs font-medium tracking-wider text-left text-gray-500 dark:text-gray-400">
                                                Date</th>
                                            <th
                                                class="px-3 py-2 text-xs font-medium tracking-wider text-left text-gray-500 dark:text-gray-400">
                                                Status</th>
                                            <th
                                                class="px-3 py-2 text-xs font-medium tracking-wider text-left text-gray-500 dark:text-gray-400">
                                                Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody
                                        class="bg-white divide-y divide-gray-200 dark:bg-gray-900 dark:divide-gray-700">
                                        @forelse ($cheques as $cheque)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                                <td class="px-3 py-2 whitespace-nowrap">
                                                    <input type="checkbox" wire:model="selectedCheques"
                                                        value="{{ $cheque->id }}"
                                                        class="w-4 h-4 border-gray-300 rounded dark:border-gray-600 text-brand-500 focus:ring-brand-500">
                                                </td>
                                                <td
                                                    class="px-3 py-2 text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                                    {{ $cheque->customer_name }}</td>
                                                <td
                                                    class="px-3 py-2 text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                                    {{ $cheque->cheque_number }}</td>
                                                <td
                                                    class="px-3 py-2 text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                                    {{ $cheque->bank_name }}</td>
                                                <td
                                                    class="px-3 py-2 text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                                    {{ $cheque->branch_name }}</td>
                                                <td
                                                    class="px-3 py-2 text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                                    {{ number_format($cheque->amount, 2) }}</td>
                                                <td
                                                    class="px-3 py-2 text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                                    @foreach($cheque->paidInvoices as $paidInvoice)
                                                        <span class="inline-block px-2 py-1 mb-1 mr-1 text-xs bg-blue-100 rounded-full dark:bg-blue-900/30 dark:text-blue-300">
                                                            {{ $paidInvoice->invoice->invoice_number }}
                                                        </span>
                                                    @endforeach
                                                </td>
                                                <td
                                                    class="px-3 py-2 text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                                    {{ $cheque->cheque_date->format('Y-m-d') }}</td>
                                                <td class="px-3 py-2 whitespace-nowrap">
                                                    @if ($cheque->status === 'pending')
                                                        <span
                                                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-500">Pending</span>
                                                    @elseif($cheque->status === 'deposited')
                                                        <span
                                                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-500">Deposited</span>
                                                    @elseif($cheque->status === 'return')
                                                        <span
                                                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-500">Return</span>
                                                    @elseif($cheque->status === 'realize')
                                                        <span
                                                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-500">Realize</span>
                                                    @else
                                                        <span
                                                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-500">Cancel</span>
                                                    @endif
                                                </td>
                                                <td
                                                    class="px-3 py-2 text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                                    <div class="flex space-x-2">
                                                        <button wire:click="viewChequeDetails({{ $cheque->id }})"
                                                            class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300"
                                                            title="View Details">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                                                fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                            </svg>
                                                        </button>
                                                        <button wire:click="printCheque({{ $cheque->id }})"
                                                            class="text-purple-600 dark:text-purple-400 hover:text-purple-800 dark:hover:text-purple-300"
                                                            title="Print">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                                                fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10"
                                                    class="px-3 py-2 text-xs text-center text-gray-500 dark:text-gray-400">
                                                    No cheques found matching your criteria
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="mt-4">
                                {{ $cheques->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cheque Details Modal -->
    <div x-data="{ open: @entangle('showChequeDetails') }" x-show="open" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>

            <div
                class="relative inline-block w-full max-w-md px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:p-6 dark:bg-gray-800">
                <!-- Close Button -->
                <button type="button" wire:click="closeModal"
                    class="absolute top-0 right-0 p-2 m-2 text-gray-400 rounded-md hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-brand-500 dark:hover:text-gray-300">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                @if ($selectedCheque)
                    <div class="space-y-4">
                        <!-- Header -->
                        <div>
                            <h3 class="text-lg font-bold leading-6 text-gray-900 dark:text-white">
                                Cheque #{{ $selectedCheque->cheque_number }}
                            </h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                {{ $selectedCheque->cheque_date->format('M d, Y') }} •
                                <span class="capitalize">{{ $selectedCheque->status }}</span>
                            </p>
                        </div>

                        <!-- Main Content -->
                        <div class="space-y-4">
                            <!-- Cheque Info -->
                            <div class="p-3 rounded-lg bg-gray-50 dark:bg-gray-700">
                                <h4 class="mb-2 text-sm font-medium text-gray-900 dark:text-white">Cheque Information</h4>
                                <dl class="grid grid-cols-2 gap-2 text-sm">
                                    <div>
                                        <dt class="font-medium text-gray-500 dark:text-gray-400">Bank</dt>
                                        <dd class="text-gray-900 dark:text-white">{{ $selectedCheque->bank_name }}</dd>
                                    </div>
                                    <div>
                                        <dt class="font-medium text-gray-500 dark:text-gray-400">Branch</dt>
                                        <dd class="text-gray-900 dark:text-white">{{ $selectedCheque->branch_name }}</dd>
                                    </div>
                                    <div>
                                        <dt class="font-medium text-gray-500 dark:text-gray-400">Amount</dt>
                                        <dd class="font-semibold text-gray-900 dark:text-white">
                                            {{ number_format($selectedCheque->amount, 2) }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="font-medium text-gray-500 dark:text-gray-400">Customer</dt>
                                        <dd class="text-gray-900 dark:text-white">{{ $selectedCheque->customer_name }}</dd>
                                    </div>
                                </dl>
                            </div>

                            <!-- Invoices -->
                            <div class="p-3 rounded-lg bg-gray-50 dark:bg-gray-700">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white">Invoices</h4>
                                    <button wire:click="showChequeInvoices({{ $selectedCheque->id }})" 
                                            class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                        View All
                                    </button>
                                </div>
                                @if($selectedCheque->paidInvoices->count() > 0)
                                    <div class="space-y-2">
                                        @foreach($selectedCheque->paidInvoices->take(3) as $paidInvoice)
                                            <div class="flex items-center justify-between px-3 py-2 text-sm bg-white rounded-md dark:bg-gray-600">
                                                <span class="font-medium text-gray-700 dark:text-gray-200">
                                                    #{{ $paidInvoice->invoice->invoice_number }}
                                                </span>
                                                <span class="text-gray-600 dark:text-gray-300">
                                                    {{ number_format($paidInvoice->amount_paid, 2) }}
                                                </span>
                                            </div>
                                        @endforeach
                                        @if($selectedCheque->paidInvoices->count() > 3)
                                            <div class="text-xs text-center text-gray-500 dark:text-gray-400">
                                                +{{ $selectedCheque->paidInvoices->count() - 3 }} more invoices
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <p class="text-sm text-gray-500 dark:text-gray-400">No invoices associated</p>
                                @endif
                            </div>

                            <!-- Details -->
                            @if ($selectedCheque->details)
                                <div class="p-3 rounded-lg bg-gray-50 dark:bg-gray-700">
                                    <h4 class="mb-2 text-sm font-medium text-gray-900 dark:text-white">Notes</h4>
                                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ $selectedCheque->details }}</p>
                                </div>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        <div
                            class="flex flex-col gap-4 pt-4 sm:flex-row sm:space-y-0 sm:space-x-2 sm:justify-end dark:border-gray-700">
                            <button wire:click="printCheque({{ $selectedCheque->id }})"
                                class="flex items-center justify-center w-full px-3 py-2 text-sm font-medium text-white border border-transparent rounded-md shadow-sm bg-brand-500 hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 sm:w-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                                Print
                            </button>

                            @if ($selectedCheque->status === 'pending')
                                <button wire:click="updateChequeStatus({{ $selectedCheque->id }}, 'deposited')"
                                    class="w-full px-3 py-2 text-sm font-medium text-white bg-green-500 border border-transparent rounded-md shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:w-auto">
                                    Mark Deposited
                                </button>
                            @endif

                            <button wire:click="closeModal"
                                class="w-full px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600 sm:w-auto">
                                Close
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Cheque Invoices Modal -->
    <div x-data="{ open: @entangle('showInvoicesModal') }" x-show="open" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"></div>

            <div
                class="relative px-4 pt-5 pb-4 overflow-hidden text-left transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:w-full sm:max-w-2xl sm:p-6 dark:bg-gray-900">
                <div class="absolute top-0 right-0 pt-4 pr-4">
                    <button type="button" wire:click="closeModal"
                        class="text-gray-400 bg-white rounded-md hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 dark:bg-gray-900 dark:hover:text-gray-300">
                        <span class="sr-only">Close</span>
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="sm:flex sm:items-start">
                    <div class="w-full mt-3 text-center sm:mt-0 sm:text-left">
                        <h3 class="mb-4 text-lg font-semibold leading-6 text-gray-900 dark:text-white/90">
                            Invoices for Cheque #{{ $selectedCheque->cheque_number ?? '' }}
                        </h3>

                        <div class="overflow-x-auto border border-gray-200 rounded-lg dark:border-gray-700">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-800">
                                    <tr>
                                        <th class="px-4 py-2 text-xs font-medium text-left text-gray-500 dark:text-gray-400">
                                            Invoice #
                                        </th>
                                        <th class="px-4 py-2 text-xs font-medium text-left text-gray-500 dark:text-gray-400">
                                            Amount Paid
                                        </th>
                                        <th class="px-4 py-2 text-xs font-medium text-left text-gray-500 dark:text-gray-400">
                                            Invoice Date
                                        </th>
                                        <th class="px-4 py-2 text-xs font-medium text-left text-gray-500 dark:text-gray-400">
                                            Status
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-900 dark:divide-gray-700">
                                    @forelse ($chequeInvoices as $paidInvoice)
                                        <tr>
                                            <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400">
                                                {{ $paidInvoice->invoice->invoice_number }}
                                            </td>
                                            <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400">
                                                {{ number_format($paidInvoice->amount_paid, 2) }}
                                            </td>
                                            <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400">
                                                {{ $paidInvoice->invoice->created_at->format('Y-m-d') }}
                                            </td>
                                            <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400">
                                                @if($paidInvoice->invoice->status === 'paid')
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-500">
                                                        Paid
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 dark:bg-gray-900/30 text-gray-800 dark:text-gray-500">
                                                        {{ ucfirst($paidInvoice->invoice->status) }}
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-4 py-2 text-sm text-center text-gray-500 dark:text-gray-400">
                                                No invoices found for this cheque
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>