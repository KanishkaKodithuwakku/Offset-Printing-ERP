<div>
    <div class="bg-white rounded-lg shadow-md p-6" style="padding: -2%">
        @if (session('message'))
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


        <!-- Header -->
        <div class="flex justify-between  ">
            <h1 class="text-medium font-bold text-gray-700">Customer Payment</h1>
            <div class="text-right" style="padding-top: ">
                <p class="text-xs text-gray-500">Customer Credit Balance</p>
                <p class="text-xl font-bold text-error-500">{{ number_format($customerAvlCredits, 2) }}</p>
                <p class="text-xs text-gray-500">Customer Due</p>
                <p class="text-xl font-bold">{{ number_format($totalAmountDue, 2) }}</p>
            </div>
        </div>

        <!-- Customer Selection and Payment Form -->
        <div class=" flex md:grid-cols-2 gap-6 ">
            <div>
                <div class="mb-2">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Received From</label>
                    {{-- {{ $selectedCustomer->id ?? '' }} --}}
                    <div class="relative">
                        <select wire:model="customerId" wire:change="loadCustomerInvoices"
                            class="block w-full rounded-md border border-gray-300  h-8 py-2 pl-3 pr-10 text-xs focus:border-blue-500 focus:outline-none focus:ring-blue-500 ">
                            <option value="">Select Customer</option>
                            @foreach ($customers as $customer)
                            <option value="{{ $customer['id'] }}">{{ $customer['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mb-2">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Payment Amount</label>
                    <div class="mt-1">
                        @if (! $paymentLocked)
                        {{-- editable on first entry --}}
                        <input type="number" step="0.01" wire:model="paymentAmount"
                            wire:change="handlePaymentAmountChange"
                            class="block w-full rounded-md border border-gray-300 h-8 py-2 px-3 text-xs focus:border-blue-500 focus:ring-blue-500"
                            placeholder="0.00" />
                        @else
                        {{-- once locked, just show it --}}
                        <div class="mt-1 text-sm font-semibold">
                            {{ number_format($initialPayment, 2) }}
                        </div>
                        @endif
                    </div>
                </div>
                <div class="mb-2">
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        Remaining to allocate
                    </label>
                    <div class="text-sm font-semibold">
                        Rs. {{ number_format($remainingBalance, 2) }}
                    </div>
                </div>
                {{-- {{ $ledger_id }} --}}
                {{--
                <livewire:components.select-ledger-dropdown /> --}}
                <label class="block text-xs font-medium text-gray-700 mb-1">Select the Bank</label>
                <livewire:components.select-bank-ledger-dropdown :model="$ledger_id" :show-label="false" />
            </div>

            <div class="gap-6" style="width:12%;">

                <!-- Payment Method -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        Payment Method<span class="text-error-500">*</span>
                    </label>
                    <select wire:model.change="payment_method" id="payment_method"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800  w-full rounded-lg border border-gray-300 bg-transparent px-2  h-8 py-2 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                        style="color:#000">
                        <option value="">Select Payment Method</option>
                        <option value="CA">Cash</option>
                        <option value="CH">Cheque</option>
                    </select>
                    @error('payment_method')
                    <p class="text-error-500 text-xs mt-1">{{ $message }}</p>
                    @enderror

                    <!-- Check Number field - only shown when payment_method is CH -->
                    @if ($payment_method == 'CH')
                    <div>
                        <label class="mb-1 mt-2 block text-xs font-medium text-gray-700 dark:text-gray-400">
                            Cheque Number {{ $check_number }}<span class="text-error-500">*</span>
                        </label>
                        <input type="text" wire:model.change="check_number" id="check_number"
                            class="block w-full rounded-md border border-gray-300  h-8 py-2 pl-3 pr-10 text-xs focus:border-blue-500 focus:outline-none focus:ring-blue-500">
                        @error('check_number')
                        <p class="text-error-500 text-xs mt-1"></p>
                        @enderror
                    </div>
                    <div class="flex-1">
                        {{-- {{ $paymentDate }} --}}
                        <label class="mb-1 mt-2 block text-xs font-medium text-gray-700 dark:text-gray-400">Cheque
                            Date</label>
                        <div class="relative ">
                            <input type="date" wire:model="paymentDate" onclick="this.showPicker()"
                                class="block w-full rounded-md border pr-5 border-gray-300  h-8 py-2 px-3 focus:border-blue-500 focus:outline-none focus:ring-blue-500 text-xs">
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
                    @endif

                </div>
            </div>

            @if ($payment_method == 'CH')
            <div class="flex flex-col gap-2.5" style="width: 12%;">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Bank</label>
                    {{-- <input type="text" wire:model="bank_name"
                        class="block w-full rounded-md border border-gray-300 py-2 px-3 text-xs text-gray-800 focus:border-blue-500 focus:outline-none focus:ring-blue-500" />
                    --}}
                    {{-- {{ json_encode($bank_id) }} --}}
                    <livewire:components.bank-select-with-add :selectedBank="$bank_id" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Branch</label>
                    {{-- <input type="text" wire:model="branch_name"
                        class="block w-full rounded-md border border-gray-300 py-2 px-3 text-xs text-gray-800 focus:border-blue-500 focus:outline-none focus:ring-blue-500" />
                    --}}
                    {{-- {{ json_encode($branch_id) }} --}}
                    <livewire:components.branch-select-with-add :selectedBranch="$branch_id" />
                </div>
            </div>
            @endif
        </div>

        <!-- Credits Modal (Hidden by default) -->
        <div class="flex justify-end">
            @if ($showCreditModal)
            <div class="fixed inset-0 flex items-center justify-center p-5 overflow-y-auto modal z-99999">
                <div class="modal-close-btn fixed inset-0 h-full w-full bg-gray-400/50 backdrop-blur-[32px]"></div>
                <div class="relative w-[70%] rounded-1xl bg-white p-1 dark:bg-gray-900">
                    <!-- close btn -->
                    {{-- {{ json_encode($credits) }}
                    {{ json_encode($selectedInvoices) }} --}}

                    {{-- <button
                        class="group absolute right-3 top-3 z-999 flex h-9.5 w-9.5 items-center justify-center rounded-full bg-gray-200 text-gray-500 transition-colors hover:bg-gray-300 hover:text-gray-500 dark:bg-gray-800 dark:hover:bg-gray-700 sm:right-6 sm:top-6 sm:h-11 sm:w-11">
                        <svg class="transition-colors fill-current group-hover:text-gray-600 dark:group-hover:text-gray-200"
                            width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M6.04289 16.5413C5.65237 16.9318 5.65237 17.565 6.04289 17.9555C6.43342 18.346 7.06658 18.346 7.45711 17.9555L11.9987 13.4139L16.5408 17.956C16.9313 18.3466 17.5645 18.3466 17.955 17.956C18.3455 17.5655 18.3455 16.9323 17.955 16.5418L13.4129 11.9997L17.955 7.4576C18.3455 7.06707 18.3455 6.43391 17.955 6.04338C17.5645 5.65286 16.9313 5.65286 16.5408 6.04338L11.9987 10.5855L7.45711 6.0439C7.06658 5.65338 6.43342 5.65338 6.04289 6.0439C5.65237 6.43442 5.65237 7.06759 6.04289 7.45811L10.5845 11.9997L6.04289 16.5413Z"
                                fill="" />
                        </svg>
                    </button> --}}

                    <div
                        class="bg-white rounded-lg shadow-lg max-w-4xl w-full border border-gray-200 dark:border-gray-800">
                        <!-- Modal Header with Tabs -->
                        <div class="border-b border-gray-200">
                            <div class="flex">
                                <button
                                    class="px-6 py-3 text-gray-800 font-medium border-b-2 border-blue-500 bg-gray-100">
                                    Credits
                                </button>
                            </div>
                        </div>

                        <!-- Modal Content -->
                        <div class="p-3">
                            <!-- Invoice Header Information -->
                            <div class="mb-2 border-b pb-2">
                                <h3 class="text-medium font-semibold text-gray-800 mb-1">Invoice</h3>

                                <div class="grid grid-cols-2 gap-4 ">
                                    <!-- Left Column -->
                                    <div>
                                        <div class="mb-1">
                                            <span class="text-xs text-gray-700">Customer:</span>
                                            <span class="ml-2">{{ $this->selectedCustomer->name }}</span>
                                        </div>
                                        <div class="mb-1">
                                            <span class="text-xs text-gray-700">Invoice Number:</span>
                                            <span class="ml-2"></span>
                                        </div>
                                        <div class="mb-1">
                                            <span class="text-xs text-gray-700">Date:</span>
                                            <span class="ml-2"></span>
                                        </div>
                                        <div class="mb-1">
                                            <span class="text-xs text-gray-700">Original Amount:</span>
                                            <span class="ml-2"></span>
                                        </div>
                                    </div>

                                    <!-- Right Column. -->
                                    <div>
                                        <div class="mb-1">
                                            <span class="text-xs text-gray-700">Amount Due:</span>
                                            <span class="ml-2">{{ number_format($totalAmountDue, 2) }}</span>
                                        </div>
                                        <div class="mb-1">
                                            <span class="text-xs text-gray-700">Credit Used:</span>
                                            <span class="ml-2"></span>
                                        </div>
                                        <div class="mb-1">
                                            <span class="text-xs text-gray-700">Balance Due:</span>
                                            <span class="ml-2 font-semibold"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h3 class="text-medium font-semibold text-gray-800 mb-4">Available Credits</h3>

                            <!-- Credits Table -->
                            <div class="overflow-x-auto border border-gray-200 rounded-md">
                                @if (count($credits) > 0)
                                <table class="w-full text-xs">
                                    <thead>
                                        <tr class="bg-gray-100">

                                            <th class="text-xs px-4 py-2 text-gray-500 text-left">DATE</th>
                                            <th class="text-xs px-4 py-2 text-gray-500 text-left">CREDIT NO.
                                            </th>
                                            <th class="text-xs px-4 py-2 text-gray-500 text-left">CREDIT AMT.
                                            </th>
                                            <th class="text-xs px-4 py-2 text-gray-500 text-left">AMT. TO USE
                                            </th>
                                            <th class="text-xs px-4 py-2 text-gray-500 text-left">CREDIT
                                                BALANCE</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Credit rows will be populated dynamically -->
                                        @foreach ($credits as $index => $credit)
                                        <tr>
                                            <td>{{ $credit['date'] }}</td>
                                            <td>{{ $credit['number'] }}</td>
                                            <td>{{ number_format($credit['amount'], 2) }}</td>

                                            <td>
                                                <input type="number" min="0" step="0.01"
                                                    wire:model.lazy="credits.{{ $index }}.amountToUse"
                                                    wire:change="validateCreditAmount({{ $index }})"
                                                    class="border rounded px-2 text-right w-full" />
                                            </td>

                                            <td>{{ number_format($credit['balance'], 2) }}</td>
                                        </tr>
                                        @endforeach

                                        <!-- Empty state when no credits -->
                                        @else
                                        <tr class="bg-white">
                                            <td colspan="6" class="px-4 py-4 text-center text-gray-500">
                                                No credits available
                                            </td>
                                        </tr>
                                        @endif
                                        <!-- Totals row -->
                                        <tr class="bg-white border-t">
                                            <td class="px-2 py-3"></td>
                                            <td class="px-4 py-3 font-medium text-right" colspan="2">Totals</td>
                                            <td class="px-4 py-3 text-right font-medium"></td>
                                            <td class="px-4 py-3 text-right font-medium"></td>
                                            <td class="px-4 py-3 text-right font-medium"></td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>

                            <!-- Footer with buttons -->
                            <div class="flex justify-end gap-4 mt-6">
                                <button type="button" wire:click="$set('showCreditModal', false)"
                                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-6 py-2 rounded-md">
                                    Cancel
                                </button>
                                <button type="submit" wire:click="allocateCreditatoSelectedInvoice"
                                    class="bg-brand-500 hover:bg-brand-600 text-white font-semibold px-6 py-2 rounded-md">
                                    Save
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>


    {{--
    <pre>{{ json_encode($payments) }}</pre>
    <pre>{{ json_encode($selectedInvoices, JSON_PRETTY_PRINT) }}</pre>

    <pre>{{ json_encode($invoices, JSON_PRETTY_PRINT) }}</pre>
    <pre>{{ json_encode($totalCredit, JSON_PRETTY_PRINT) }}</pre>
    <pre>Credits {{ json_encode($customerAvlCredits, JSON_PRETTY_PRINT) }}</pre>
    <pre>payments {{ json_encode($payments, JSON_PRETTY_PRINT) }}</pre>
    <pre>payment amount {{ $paymentAmount}}</pre>
    <pre>total payment {{ $totalPayment}}</pre>
    <pre>total credit {{ $totalCredit}}</pre>
    <pre>overpayment {{ $overpayment}}</pre> --}}

    <!-- Invoices Table -->
    <div class="overflow-x-auto mb-6">
        <table class="min-w-full divide-y divide-gray-300">
            <thead>
                <tr class="bg-gray-50">
                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-xs font-semibold text-gray-900 sm:pl-6">
                        {{-- <input type="checkbox" wire:model="selectAll"
                            class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"> --}}
                    </th>
                    <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-gray-500">DATE</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-gray-500">NUMBER</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-gray-500">ORIG. AMT.
                    </th>
                    <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-gray-500">AMT. DUE</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-gray-500">CREDIT</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-gray-500">PAYMENT</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @forelse($invoices as $index => $invoice)
                <tr class="{{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}">
                    <td class="whitespace-nowrap py-3 pl-4 pr-3 text-xs sm:pl-6">
                        {{-- <input type="checkbox" wire:change="toggleInvoiceSelection({{ $invoice['id'] }})"
                            class="h-4 w-4 text-blue-600" /> --}}
                    </td>
                    <td class="whitespace-nowrap px-3 py-3 text-xs text-gray-900">
                        {{ \Carbon\Carbon::parse(time: $invoice['created_at'])->format('d/m/Y') }}
                    </td>
                    <td class="whitespace-nowrap px-3 py-3 text-xs text-gray-900">
                        {{ $invoice['invoice_number'] }}
                    </td>
                    <td class="whitespace-nowrap px-3 py-3 text-xs text-gray-900 ">
                        {{ number_format($invoice['original_amount'], 2) }}
                    </td>
                    <td class="whitespace-nowrap px-3 py-3 text-xs text-gray-900 ">
                        {{ number_format($invoice['amount_due'], 2) }}
                    </td>
                    <td class="whitespace-nowrap px-3 py-3 text-xs text-brand-500  cursor-pointer underline"
                        wire:click="openCreditModal({{ $invoice['id'] }})">

                        {{ number_format($invoice['credit'] ?? 0, 2) }}

                    </td>
                    <td class="whitespace-nowrap px-3 py-3 text-xs font-medium">

                        @if ($invoice['amount_due'] === 0 && $invoice['credit'] > 0)
                        {{ $invoice['credit'] }}
                        @else
                        <input type="number" step="0.01" wire:model.lazy="payments.{{ $invoice['id'] }}"
                            wire:change="updateSelectedInvoiceAmount({{ $invoice['id'] }})"
                            class="text-right border rounded px-2 py-1 text-xs" />
                        @endif

                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-3 py-8 text-center text-xs text-gray-500">
                        No unpaid invoices found for this customer.
                    </td>
                </tr>
                @endforelse

                <!-- Totals Row -->
                <tr class="font-bold bg-gray-100">
                    <td class="whitespace-nowrap py-3 pl-4 pr-3 text-xs sm:pl-6" colspan="3" style="padding-left:6%">
                        Totals</td>
                    <td class="whitespace-nowrap px-3 py-3 text-xs text-gray-900 ">
                        {{ number_format($totalOriginalAmount, 2) }}</td>
                    <td class="whitespace-nowrap px-3 py-3 text-xs text-gray-900 ">
                        {{ number_format($totalAmountDue, 2) }}</td>
                    <td class="whitespace-nowrap px-3 py-3 text-xs text-gray-900 ">
                        {{ number_format($totalCredit, 2) }}</td>
                    <td class="whitespace-nowrap px-3 py-3 text-xs text-gray-900 ">
                        {{ number_format($totalPayment, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Summary -->
    <div class="mt-6 px-2.5">
        <div class="w-full justify-between flex gap-2">
            <div class="flex-1">
                <!-- over payment or underpayment -->
                <div>
                    @if ($difference > 0)
                    <span class="text-medium font-semibold text-gray-600 dark:text-green-300">Over Payment:</span>
                    <span class="text-sm font-semibold text-black-600 dark:text-green-400">Rs.
                        {{ number_format($difference, 2) }}</span>
                    @elseif($difference < 0) <span class="text-xs font-bold text-red-600 dark:text-red-400">
                        Underpayment:</span>
                        <span class="text-xs font-bold text-red-600 dark:text-red-400">${{
                            number_format(abs($difference), 2) }}</span>
                        @endif
                </div>
            </div>
            <div class="flex-1">
                <div class="border flex justify-between rounded-md p-4 bg-gray-50">
                    <h3 class="font-medium text-sm text-gray-700 mb-2">Available Credits</h3>
                    <p class="text-sm font-bold text-right">Rs. {{ number_format($customerAvlCredits, 2) }}</p>
                </div>
                <div class="border flex justify-between rounded-md p-4 bg-gray-50 mt-2">
                    <h3 class="font-medium text-sm text-gray-700 mb-2">Remaining to allocate</h3>
                    <p class="text-sm font-bold text-right">Rs. {{ number_format($remainingBalance, 2) }}</p>
                </div>
                <div class="mt-4">
                    <label for="memo" class="block text-xs font-medium text-gray-700">Memo</label>
                    <textarea id="memo" wire:model="memo" rows="1"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-xs"></textarea>
                </div>
            </div>
            <div class="flex-1">
                <div class="border rounded-md p-4 bg-gray-50">
                    <h3 class="font-medium text-sm text-gray-700 mb-4">Amount For Invoices</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-medium text-gray-600">Amount Due</span>
                            <span class="text-xs font-bold">{{ number_format($totalAmountDue, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-medium text-gray-600">Applied</span>
                            <span class="text-xs font-bold">{{ number_format($totalPayment, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-medium text-gray-600">Credit Applied</span>
                            <span class="text-xs font-bold">{{ number_format($totalCredit, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex justify-end mt-6 space-x-4 gap-4">
        <button wire:click="clear"
            class="inline-flex justify-center rounded-md border border-gray-300 bg-white py-2 px-4 text-xs font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            Clear
        </button>
        <button wire:click="savePayment"
            class="inline-flex justify-center rounded-md border border-transparent bg-brand-500 py-2 px-4 text-xs font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            Save & New
        </button>
    </div>
</div>
</div>