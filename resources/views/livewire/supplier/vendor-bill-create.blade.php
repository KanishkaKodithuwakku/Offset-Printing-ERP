<div>
    <div class="w-full rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6">
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
        <x-validation-errors />
        <div class="py-4 sm:py-5">
    <h3 class="text-normal font-bold text-gray-800 dark:text-white/90">Bill</h3>
</div>
<form wire:submit.prevent="save" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Left Column -->
    <div class="space-y-4">
       {{-- <p> {{ $vendor_id }}</p>
       <p> {{ $date }}</p>
       <p> {{ $ref_no }}</p>
       <p> {{ $bill_due_date }}</p>
       <p> {{ $terms }}</p>
       <p> {{ $memo }}</p>
       <p> {{ $billMemo }}</p> --}}
        <div>
            <label for="vendor"
                class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Vendor</label>
            <select wire:model.change="vendor_id" id="vendor"
                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                <option value="">Select vendor</option>
                @foreach ($vendors as $vendor)
                    <option value="{{ $vendor->id }}">{{ $vendor->company_name }}</option>
                @endforeach
            </select>
            @error('vendor_id')
                <span class="error text-error-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="date"
                class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Date</label>
            <div class="relative">
                <input type="date" wire:model.change="date" id="date" readonly
                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 cursor-not-allowed"
                    onclick="return false;" />
                <span class="pointer-events-none absolute top-1/2 right-3 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                    <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M6.66659 1.5415C7.0808 1.5415 7.41658 1.87729 7.41658 2.2915V2.99984H12.5833V2.2915C12.5833 1.87729 12.919 1.5415 13.3333 1.5415C13.7475 1.5415 14.0833 1.87729 14.0833 2.2915V2.99984L15.4166 2.99984C16.5212 2.99984 17.4166 3.89527 17.4166 4.99984V7.49984V15.8332C17.4166 16.9377 16.5212 17.8332 15.4166 17.8332H4.58325C3.47868 17.8332 2.58325 16.9377 2.58325 15.8332V7.49984V4.99984C2.58325 3.89527 3.47868 2.99984 4.58325 2.99984L5.91659 2.99984V2.2915C5.91659 1.87729 6.25237 1.5415 6.66659 1.5415ZM6.66659 4.49984H4.58325C4.30711 4.49984 4.08325 4.7237 4.08325 4.99984V6.74984H15.9166V4.99984C15.9166 4.7237 15.6927 4.49984 15.4166 4.49984H13.3333H6.66659ZM15.9166 8.24984H4.08325V15.8332C4.08325 16.1093 4.30711 16.3332 4.58325 16.3332H15.4166C15.6927 16.3332 15.9166 16.1093 15.9166 15.8332V8.24984Z"
                            fill="" />
                    </svg>
                </span>
            </div>
            @error('date')
                <span class="error text-error-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="ref_no"
                class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Invoice No</label>
            <input type="text" wire:model.change="ref_no" id="ref_no"
                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
        </div>

        {{-- <div>
            <label for="amount_due"
                class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Amount Due</label>
            <input type="number" step="0.01" wire:model="amount_due" id="amount_due"
                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
            @error('amount_due')
                <span class="error text-error-500 text-sm">{{ $message }}</span>
            @enderror
        </div> --}}

    </div>

    <!-- Right Column -->
    <div class="space-y-4">

        <div>
            <label for="bill_due_date"
                class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Bill Due Date</label>
            <div class="relative">
                <input type="date" wire:model.change="bill_due_date" id="bill_due_date" onclick="this.showPicker()"
                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                <span class="pointer-events-none absolute top-1/2 right-3 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                    <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M6.66659 1.5415C7.0808 1.5415 7.41658 1.87729 7.41658 2.2915V2.99984H12.5833V2.2915C12.5833 1.87729 12.919 1.5415 13.3333 1.5415C13.7475 1.5415 14.0833 1.87729 14.0833 2.2915V2.99984L15.4166 2.99984C16.5212 2.99984 17.4166 3.89527 17.4166 4.99984V7.49984V15.8332C17.4166 16.9377 16.5212 17.8332 15.4166 17.8332H4.58325C3.47868 17.8332 2.58325 16.9377 2.58325 15.8332V7.49984V4.99984C2.58325 3.89527 3.47868 2.99984 4.58325 2.99984L5.91659 2.99984V2.2915C5.91659 1.87729 6.25237 1.5415 6.66659 1.5415ZM6.66659 4.49984H4.58325C4.30711 4.49984 4.08325 4.7237 4.08325 4.99984V6.74984H15.9166V4.99984C15.9166 4.7237 15.6927 4.49984 15.4166 4.49984H13.3333H6.66659ZM15.9166 8.24984H4.08325V15.8332C4.08325 16.1093 4.30711 16.3332 4.58325 16.3332H15.4166C15.6927 16.3332 15.9166 16.1093 15.9166 15.8332V8.24984Z"
                            fill="" />
                    </svg>
                </span>
            </div>
            @error('bill_due_date')
                <span class="error text-error-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="terms"
                class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Terms</label>
            <input type="text" wire:model.change="terms" id="terms" placeholder="e.g., Net 30, Due on receipt"
                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
        </div>

        <!-- Action Buttons -->

        {{-- memo --}}
        <div >
            <label for="memo"
                class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Memo</label>
            <textarea wire:model.change="memo" id="memo"
                class="h-20 dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"></textarea>
        </div>
    </div>
</form>



        <h2 class="mt-8 mb-5 font-bold text-normal">Expenses</h2>
    <!-- Right Column: Account, Amount, Memo Table -->
    <div>
        <table class="w-full  rounded mb-2">
            <thead class="bg-gray-50">

            </thead>
            <tbody>
                <!-- Input Row -->
                <tr class="bg-white">
                    <td class="border border-gray-300 p-2">
                        <livewire:components.select-ledger-dropdown />
                        {{-- {{ $selectedLedger }} --}}
                        @error('selectedLedger')
                            <span class="text-error-500 text-sm">{{ $message }}</span>
                        @enderror
                    </td>
                    <td class="border border-gray-300 p-2">
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Amount</label>
                        <input type="number" step="0.01" wire:model.change="billAmount"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                        {{-- {{ $billAmount }} --}}
                            @error('billAmount')
                            <span class="text-error-500 text-sm">{{ $message }}</span>
                        @enderror
                    </td>
                    <td class="border border-gray-300 p-2">
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Bill Memo</label>
                        <input type="text" wire:model.change="billMemo"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                       {{-- {{ $billMemo }} --}}
                            @error('billMemo')
                            <span class="text-error-500 text-xs">{{ $message }}</span>
                        @enderror
                    </td>
                    <td class="border border-gray-300 p-2">
                           <button  wire:click.prevent="addExpense()" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-md text-xs shadow-md transition duration-300 ease-in-out transform hover:scale-105"
                style="background-color:#465FFF;">
                Add
            </button>
                        </td>


                </tr>

                {{-- {{ json_encode($expenses) }} --}}

                <!-- Existing Bills -->
                @foreach ($expenses as $index => $bill)
                    <tr class="bg-gray-50 ">
                        <td class="border text-xs border-gray-300 p-2">{{ $bill['ledger'] ?? '' }}</td>
                        <td class="border text-xs border-gray-300 p-2">{{ number_format($bill['amount'], 2) }}</td>
                        <td class="border text-xs border-gray-300 p-2">{{ $bill['memo'] }}</td>
                        <td class="border text-xs border-gray-300 p-2">
                            <!-- Remove Button -->
                            <button wire:click="removeVendorBill({{ $index }})"
                                class="btn btn-danger bg-gray-600 hover:bg-gray-700 text-gray-500 px-4  py-1 rounded text-xs">
                                <svg class="w-4 h-4 text-gray-800 dark:text-white hover:text-gray-400"
                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="18"
                                    height="18" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </button>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Add button outside the table, right aligned -->
        <div class="text-right mt-5">
            <div>
                <h3 class="font-bold mb-5 gap-5 text-gray-800">Total Amount: <span class=" px-5  ">{{ number_format($totalAmount, 2) }}</span> <h3>
            </div>

            <button wire:click.prevent="save" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-md text-xs shadow-md transition duration-300 ease-in-out transform hover:scale-105"
                style="background-color:#465FFF;">
                Save
            </button>
        </div>
    </div>




    </div>



    {{-- <div class="text-right mt-4 lg:col-span-2">
        <button type="submit"
            class="bg-brand-500 rounded-lg border border-gray-300 text-white px-6 py-2 hover:bg-green-700">
            Save & New
        </button>
    </div> --}}
</div>
