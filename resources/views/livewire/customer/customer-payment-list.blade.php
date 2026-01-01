<div class="p-2 mx-auto max-w-screen-2xl md:p-4" x-data="{ open: false }">


    {{-- Flash Success --}}
    @if (session()->has('message'))
    <div class="mb-4 rounded-lg bg-green-50 border border-green-400 px-4 py-3 text-sucess-700">
        {{ session('message') }}
    </div>
    @endif

    {{-- Flash Error --}}
    @if (session()->has('error'))
    <div class="mb-4 rounded-lg bg-red-50 border border-red-400 px-4 py-3 text-error-700">
        {{ session('error') }}
    </div>
    @endif

    {{-- Validation Errors --}}
    @if ($errors->any())
    <div class="mb-4 rounded-lg bg-yellow-50 border border-yellow-400 px-4 py-3 text-yellow-700">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $err)
            <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif


    <!-- Modal (Filter Options) -->
    <div x-show="open" x-transition @closeModal.window="open = false"
        class="fixed inset-0 bg-gray-50 bg-opacity-80 z-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full border border-gray-200 dark:border-gray-800">
            <h3 class="text-xl font-semibold text-gray-800">Filter Options</h3>

            <div class="mt-4">
                <form wire:submit.prevent="applyFilters">
                    <!-- Custom filters here -->
                    {{-- <div class="mb-4">
                        <label class="block text-gray-600">Method</label>
                        <select wire:model="methodFilter" class="w-full p-2 border rounded-md">
                            <option value="">All Methods</option>
                            <option value="CA">Cash</option>
                            <option value="CH">Cheque</option>
                        </select>
                    </div> --}}



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




    <div class="space-y-5 sm:space-y-6">
        <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="mb-4 flex flex-col gap-6 px-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <div class="flex items-center gap-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                        Payments
                    </h3>

                    <!-- Grouped filter controls -->
                    <div class="flex items-center gap-2">
                        <select wire:model.change="statusFilter"
                            class="dark:bg-dark-900 shadow-theme-xs focus:ring focus:border-blue-500 h-7 rounded-lg border px-4 py-1 pr-5 text-xs">
                            <option value="">All</option>
                            <option value="CA">Cash</option>
                            <option value="CH">Cheque</option>
                        </select>

                        <button @click="open = true"
                            class="flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-1 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">
                            <svg class="w-4 h-4 text-gray-500 dark:text-white" ...></svg>
                            Filter
                        </button>
                    </div>
                </div>

                <div class="relative w-full max-w-xs">
                    <input type="text" wire:model.live.debounce.300ms="searchTerm" placeholder="Search..."
                        class="w-full h-8 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring focus:border-blue-400 dark:bg-gray-800 dark:text-white dark:border-gray-600 placeholder:text-gray-400 dark:placeholder:text-gray-500" />
                </div>



            </div>

            <div class="custom-scrollbar max-w-full overflow-x-auto px-5 sm:px-6">
                <table class="min-w-full">
                    <thead class="border-y border-gray-100 dark:border-gray-800">
                        <tr>
                            <th class="py-2 text-left text-sm font-medium text-gray-500 dark:text-gray-400">ID</th>
                            <th class="py-2 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Code</th>
                            <th class="py-2 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Customer ID
                            </th>
                            <th class="py-2 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Amount</th>
                            <th class="py-2 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Cedit</th>
                            <th class="py-2 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Method</th>
                            <th class="py-2 text-left text-sm font-medium text-gray-500 dark:text-gray-400">CH Number
                            </th>
                            <th class="py-2 text-left text-sm font-medium text-gray-500 dark:text-gray-400">CH Date
                            </th>

                            <th class="py-2 text-left text-sm font-medium text-gray-500 dark:text-gray-400">CH Bank
                            </th>
                            <th class="py-2 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Payment Date
                            </th>
                            <th class="py-2 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Action</th>
                        </tr>
                    </thead>


                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse ($payments as $payment)
                        <tr
                            class="hover:bg-gray-100 dark:hover:bg-gray-800 @if ($payment->status === 'cancelled') bg-red-100 text-red-600 dark:bg-red-900 @elseif ($payment->credit_amount > 0) border-l-4 border-error-500 @endif">
                            <td class="py-2 text-sm text-gray-700 dark:text-gray-300">{{ $payment->id }}</td>
                            <td class="py-2 text-sm text-gray-700 dark:text-gray-300">
                                {{ $payment->payment_code ?? 'N/A' }}</td>
                            <td class="py-2 text-sm text-gray-700 dark:text-gray-300">{{ $payment->customer->name }}
                            </td>
                            <td class="py-2 text-sm text-gray-700 dark:text-gray-300">
                                {{ number_format($payment->payment_details_sum_amount, 2) }}
                            </td>
                            <td class="py-2 text-sm text-gray-700 dark:text-gray-300">{{
                                number_format($payment->credit_amount, 2) }}</td>
                            <td class="py-2 text-sm text-gray-700 dark:text-gray-300">{{ $payment->display_method }}</td>
                            <td class="py-2 text-sm text-gray-700 dark:text-gray-300">
                                {{ $payment->check_number ?? 'N/A' }}</td>

                            <td class="py-2 text-sm text-gray-700 dark:text-gray-300">
                                {{ $payment->cheque_date ?? 'N/A' }}</td>

                            <td class="py-2 text-sm text-gray-700 dark:text-gray-300">
                                {{ ($payment->bank?->name ?? '') . ' - ' . ($payment->bankBranch?->name ?? '') }}
                            </td>
                            <td class="py-2 text-sm text-gray-700 dark:text-gray-300">{{ $payment->date }}</td>
                            <td class="py-2 text-sm text-gray-500 flex items-center space-x-2">
                                @if ($payment->status === 'cancelled')
                                    <a wire:navigate href="{{ route('payment.print', $payment->id) }}" class="text-xs font-semibold text-red-600 hover:underline" title="View Cancelled Receipt">Cancelled</a>
                                @else
                                    <a wire:navigate href="{{ route('payment.print', $payment->id) }}"
                                        class="text-blue-600 hover:underline"><svg class="fill-current" width="18"
                                            height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M1.5 12C1.5 12 5.25 4.5 12 4.5C18.75 4.5 22.5 12 22.5 12C22.5 12 18.75 19.5 12 19.5C5.25 19.5 1.5 12 1.5 12ZM12 15C13.6569 15 15 13.6569 15 12C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15Z"
                                                fill="" />
                                        </svg></a>



                                    <div x-data="{ showConfirm: false, cancelReason: '' }" class="relative mt-1">
                                        <!-- trigger -->
                                        <a @click="showConfirm = true" class="text-brand-500 hover:underline">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-white" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                                                viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M7.757 12h8.486M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>

                                        </a>

                                        <!-- modal -->
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
                                                        <p class="text-sm leading-6 text-gray-500 dark:text-gray-400">
                                                            Are you sure you want to cancel this payment? Click
                                                            "Cancel Payment" to confirm.
                                                        </p>

                                                        <label
                                                            class="text-left mt-3 mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                                            Cancel Reason <span class="text-error-500">*</span>
                                                        </label>

                                                        <input type="text" wire:model.defer="cancelReason"
                                                            placeholder="Why are you cancelling?"
                                                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />

                                                        <div class="flex items-center justify-center w-full gap-3 mt-8">
                                                            <!-- Close button -->
                                                            <button type="button" @click="showConfirm = false"
                                                                class="flex justify-center rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">
                                                                Close
                                                            </button>

                                                            <!-- Cancel Payment button -->
                                                            <button type="button" class=" flex justify-center px-4 py-3 text-sm font-medium text-white rounded-lg bg-brand-500 shadow-theme-xs hover:bg-error-600" wire:click="cancelPayment({{ $payment->id }})">
                                                                Cancel Payment
                                                            </button>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                No payments found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex justify-between items-center border-t px-6 py-4 dark:border-gray-800">
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    Showing {{ $payments->firstItem() }} to {{ $payments->lastItem() }} of {{ $payments->total() }}
                    entries
                </div>

                <div class="pagination flex justify-between">
                    {{ $payments->links('vendor.pagination.custom-tailwind') }}
                </div>
            </div>
        </div>
    </div>
</div>
