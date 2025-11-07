<div class="p-4 max-w-7xl mx-auto bg-gray-50 min-h-screen">

    <div class="bg-white rounded-lg shadow-md p-6" style="padding: -2%">
        <h3 class="text-sm font-bold mb-4 text-gray-800">SELECT BILLS TO BE PAID</h3>

        {{-- Flash Message --}}
        {{-- Success Message --}}
        {{-- <x-flash-messages /> --}}


@if (session()->has('error'))
            <div
                class="p-4 mb-2 border rounded-xl border-error-500 bg-error-50 dark:border-error-500/30 dark:bg-error-500/15">
                <div class="flex items-start gap-3">
                    <div class="-mt-0.5 text-error-500">
                        <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M20.3499 12.0004C20.3499 16.612 16.6115 20.3504 11.9999 20.3504C7.38832 20.3504 3.6499 16.612 3.6499 12.0004C3.6499 7.38881 7.38833 3.65039 11.9999 3.65039C16.6115 3.65039 20.3499 7.38881 20.3499 12.0004ZM11.9999 22.1504C17.6056 22.1504 22.1499 17.6061 22.1499 12.0004C22.1499 6.3947 17.6056 1.85039 11.9999 1.85039C6.39421 1.85039 1.8499 6.3947 1.8499 12.0004C1.8499 17.6061 6.39421 22.1504 11.9999 22.1504ZM13.0008 16.4753C13.0008 15.923 12.5531 15.4753 12.0008 15.4753L11.9998 15.4753C11.4475 15.4753 10.9998 15.923 10.9998 16.4753C10.9998 17.0276 11.4475 17.4753 11.9998 17.4753L12.0008 17.4753C12.5531 17.4753 13.0008 17.0276 13.0008 16.4753ZM11.9998 6.62898C12.414 6.62898 12.7498 6.96476 12.7498 7.37898L12.7498 13.0555C12.7498 13.4697 12.414 13.8055 11.9998 13.8055C11.5856 13.8055 11.2498 13.4697 11.2498 13.0555L11.2498 7.37898C11.2498 6.96476 11.5856 6.62898 11.9998 6.62898Z"
                                fill="#F04438" />
                        </svg>
                    </div>

                    <div>
                        <h4 class="mb-1 text-sm font-semibold text-gray-800 dark:text-white/90">
                            Error Message
                        </h4>

                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ session('error') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif
@if (session()->has('success'))
            <div x-data="{ open: true }" x-show="open" x-transition
                class="relative p-4 mb-5 border rounded-xl border-success-500 bg-success-50 dark:border-success-500/30 dark:bg-success-500/15">
                <div class="flex flex-row justify-end">
                    <!-- Close button positioned in the top-right corner -->
                    <button @click="open = false" class="absolute text-gray-400 top-2 right-2 hover:text-red-700">
                        <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L17.94 6M18 18L6.06 6" />
                        </svg>
                    </button>

                </div>

                <div class="flex items-start gap-3">
                    <div class="-mt-0.5 text-success-500">
                        <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M3.70186 12.0001C3.70186 7.41711 7.41711 3.70186 12.0001 3.70186C16.5831 3.70186 20.2984 7.41711 20.2984 12.0001C20.2984 16.5831 16.5831 20.2984 12.0001 20.2984C7.41711 20.2984 3.70186 16.5831 3.70186 12.0001ZM12.0001 1.90186C6.423 1.90186 1.90186 6.423 1.90186 12.0001C1.90186 17.5772 6.423 22.0984 12.0001 22.0984C17.5772 22.0984 22.0984 17.5772 22.0984 12.0001C22.0984 6.423 17.5772 1.90186 12.0001 1.90186ZM15.6197 10.7395C15.9712 10.388 15.9712 9.81819 15.6197 9.46672C15.2683 9.11525 14.6984 9.11525 14.347 9.46672L11.1894 12.6243L9.6533 11.0883C9.30183 10.7368 8.73198 10.7368 8.38051 11.0883C8.02904 11.4397 8.02904 12.0096 8.38051 12.3611L10.553 14.5335C10.7217 14.7023 10.9507 14.7971 11.1894 14.7971C11.428 14.7971 11.657 14.7023 11.8257 14.5335L15.6197 10.7395Z"
                                fill=""></path>
                        </svg>
                    </div>

                    <div>
                        <h4 class="mb-1 text-sm font-semibold text-gray-800 dark:text-white/90">
                            Success Message
                        </h4>

                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif
        {{-- Filter Section --}}
        <div class="bg-white border rounded p-3 mb-4">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-6 text-sm">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="due_on_or_before" wire:model.change="applyDueFilter"
                            class="text-blue-600">
                        <label for="due_before" class="text-sm">Due on or before</label>
                        <input @disabled(! $applyDueFilter) type="date" wire:model.change="dueDateFilter"
                            onclick="this.showPicker()"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                            value="2025-06-02" />
                        {{ $dueDateFilter }}
                    </div>

                    {{-- Search Bar --}}
                    <div class="flex items-center gap-2">
                        <label for="search" class="text-sm font-medium text-gray-700">Search</label>
                        <input type="text"
                            wire:model.live.debounce.300ms="searchTerm"
                            id="search"
                            placeholder="Search by invoice number or vendor name..."
                            class="h-8 px-3 py-1 border border-gray-300 rounded-lg text-xs text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:ring-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                            style="min-width: 250px;">
                        @if($searchTerm)
                            <button wire:click="$set('searchTerm', '')"
                                class="px-2 py-1 text-xs text-gray-500 hover:text-gray-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        @endif
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="show_all" wire:model.change="showAllBills" class="text-blue-600">
                        <label for="show_all" class="text-sm">Show all bills</label>
                    </div>

                    <div class="flex items-center  gap-2">
                        <label for="filter_by_vendor" class="text-sm">Filter By</label>
                        <select wire:model.change="selectedVendor" id="vendor" @disabled($showAllBills)
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8  rounded-lg border border-gray-300 bg-transparent gap-3 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                            <option value="">Select vendor</option>
                            @foreach ($vendors as $vendor)
                            <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                            @endforeach
                        </select>
                        @error('vendor_id')
                        <span class="error text-error-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex items-center gap-2">
                        <label class="text-sm">Sort By</label>
                        <select wire:model.change="sortBy" @disabled($showAllBills)
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8  rounded-lg border border-gray-300 bg-transparent gap-3 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"">
                        <option value=" due_date">Due Date</option>
                            <option value="vendor_id">Vendor</option>
                            <option value="total_amount">Amount</option>
                        </select>
                    </div>
                </div>
                <div>
                    <button wire:click="openDeleteModal"
                        @if(empty($selectedBills)) disabled @endif
                        class="px-4 py-2 bg-error-500 hover:bg-error-600 text-white rounded text-xs transition disabled:opacity-50 disabled:cursor-not-allowed">
                        Delete Selected Bills
                    </button>
                </div>
            </div>
        </div>

        {{-- {{ json_encode($selectedBills) }} --}}

        {{-- Payment Type Selection --}}

        {{-- Bills Table --}}
        <div class="bg-white border rounded overflow-hidden">
            <table class="min-w-full text-xs">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2 text-left border-r">
                            <input type="checkbox" wire:model="selectAll" class="text-blue-600">
                        </th>
                        <th class="p-2 text-left border-r font-semibold">DATE</th>
                        <th class="p-2 text-left border-r font-semibold">VENDOR</th>
                        <th class="p-2 text-left border-r font-semibold">INVOICE NO</th>
                        <th class="p-2 text-right border-r font-semibold">AMT. DUE</th>
                        <th class="p-2 text-right font-semibold">AMT. TO PAY</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse($bills as $index => $bill)
                    <tr class="border-b {{ $bill->has_pending_deletion ?? false ? 'bg-gray-100 opacity-60' : 'hover:bg-blue-50' }} {{ in_array($bill->id, $selectedBills ?? []) && !($bill->has_pending_deletion ?? false) ? 'bg-blue-100' : '' }}" wire:key="bill-{{ $bill->id }}">
                        <td class="p-2 border-r">
                            <input type="checkbox" wire:click="toggleSelectedBill({{ $bill->id }})"
                                @if($bill->has_pending_deletion ?? false) disabled @endif
                                @if(in_array($bill->id, $selectedBills ?? [])) checked @endif
                                class="{{ ($bill->has_pending_deletion ?? false) ? 'opacity-50 cursor-not-allowed accent-gray-400' : 'accent-blue-600' }}">
                        </td>
                        <td class="p-2 border-r {{ ($bill->has_pending_deletion ?? false) ? 'text-gray-400' : '' }}">
                            {{ $bill->date ? date('d/m/Y', strtotime($bill->date)) : now() }}</td>
                        <td class="p-2 border-r {{ ($bill->has_pending_deletion ?? false) ? 'text-gray-400' : '' }}">{{ $bill->vendor->name ?? '' }}</td>
                        <td class="p-2 border-r {{ ($bill->has_pending_deletion ?? false) ? 'text-gray-400' : '' }}">{{ $bill->ref_no ?? '' }}</td>
                        <td class="p-2 text-right border-r {{ ($bill->has_pending_deletion ?? false) ? 'text-gray-400' : '' }}">{{ number_format($bill->amount_due ?? 10000.0, 2) }}</td>
                        <td class="p-2 text-right {{ ($bill->has_pending_deletion ?? false) ? 'text-gray-400' : '' }}">
                            {{ number_format($bill->total_amount, 2) }}</td>
                    </tr>
                    @empty
                    @for ($i = 0; $i < 10; $i++) <tr class="border-b h-8">
                        <td class="p-2 border-r"></td>
                        <td class="p-2 border-r"></td>
                        <td class="p-2 border-r"></td>
                        <td class="p-2 border-r"></td>
                        <td class="p-2 border-r"></td>
                        <td class="p-2"></td>
                        </tr>
                        @endfor
                        {{-- Totals Row --}}

                        @endforelse
                        <tr class="bg-gray-100 font-semibold border-t-2 border-black">
                            <td class="p-2 border-r"></td>
                            <td class="p-2 border-r"></td>
                            <td class="p-2 border-r"></td>
                            <td class="p-2 text-right border-r">Totals</td>
                            <td class="p-2 text-right border-r"></td>
                            <td class="p-2 text-right">{{ number_format($totalAmountToPay, 2) }}</td>
                        </tr>
                </tbody>
            </table>


        </div>


        {{-- Clear Selections --}}
        {{-- <div class="mt-2">
            <button wire:click="clearSelections" class="text-blue-600 hover:text-blue-800 text-sm underline">
                Clear Selections
            </button>
        </div> --}}



        {{-- Payment Section --}}
        <div class="mt-4 bg-white border rounded p-4">
            <h4 class="font-semibold mb-3 text-sm text-gray-800">PAYMENT</h4>

            <div class="flex flex-wrap gap-5">

                {{-- Payment Date --}}
                <div class="flex flex-col">
                    <label class="mb-1 text-xs font-medium text-gray-700 dark:text-gray-400">Date</label>
                    <div class="relative">
                        <input type="date" wire:model="paymentDate" onclick="this.showPicker()"
                            class="block rounded-md border border-gray-300 h-8 py-2 px-3 text-xs focus:border-blue-500 focus:outline-none focus:ring-blue-500"
                            style="width: 10%; min-width: 120px;" />
                    </div>
                </div>

                {{-- Payment Method --}}
                <div class="flex flex-col flex-grow">
                    <label class="text-xs font-medium text-gray-700 mb-1">
                        Method <span class="text-error-500">*</span>
                    </label>
                    <select wire:model.change="payment_method" id="payment_method"
                        class="w-full h-8 px-2 py-1 border border-gray-300 rounded-md text-xs text-gray-800 focus:ring focus:ring-blue-500 focus:outline-none"
                        style="color:#000">
                        <option value="">Select Payment Method</option>
                        <option value="CA">Cash</option>
                        <option value="CH">Cheque</option>
                    </select>
                    @error('payment_method')
                    <p class="text-error-500 text-xs mt-1">{{ $message }}</p>
                    @enderror

                    {{-- Check Number Field --}}
                    @if ($payment_method == 'CH')
                    <div class="mt-3">
                        <label class="block text-xs font-medium text-gray-700 mb-1">
                            Check Number <span class="text-error-500">*</span>
                        </label>
                        <input type="text" wire:model.change="check_number" id="check_number"
                            class="w-full h-8 px-3 py-1 border border-gray-300 rounded-md text-xs focus:border-blue-500 focus:outline-none focus:ring-blue-500" />
                    </div>

                    {{-- Check Date Field --}}
                    <div class="mt-3">
                        <label class="block text-xs font-medium text-gray-700 mb-1">
                            Check Date <span class="text-error-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="date" wire:model.change="check_date" id="check_date" onclick="this.showPicker()"
                                class="w-full h-8 px-3 py-1 pr-8 border border-gray-300 rounded-md text-xs focus:border-blue-500 focus:outline-none focus:ring-blue-500" />
                            <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Bank Dropdown --}}
                <div class="flex flex-col flex-grow">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Select the Bank</label>
                    {{-- {{ $ledger_id }} --}}
                    <livewire:components.select-bank-ledger-dropdown :model="$ledger_id" :show-label="false" />
                </div>

                @error('ledger_id')
            <span class="text-error-500 text-xs mt-1">{{ $message }}</span>
            @enderror

                {{-- Remark --}}
                <div class="flex flex-col flex-grow">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Payment Voucher No.</label>
                    <input type="text" wire:model="payment_voucher_no"
                        class="w-full h-8 px-3 py-1 border border-gray-300 rounded-md text-xs focus:border-blue-500 focus:outline-none focus:ring-blue-500"
                        placeholder="Enter payment voucher number" />
                    @error('payment_voucher_no')
                        <span class="text-error-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex justify-end gap-2 mt-6">

                <button class="px-4 py-2 text-white rounded text-xs transition bg-brand-500 hover:bg-brand-500"  wire:click.prevent="paySelectedBills">
                    Pay Selected Bills
                </button>


                <button
                    class="px-4 py-2 border border-gray-300 text-gray-700 rounded hover:bg-gray-50 text-xs transition">
                    Cancel
                </button>
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    @if ($showDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-opacity-50 backdrop-blur-sm" style="background-color: rgba(25, 25, 25, 0.339);" x-data="{ open: @entangle('showDeleteModal') }" x-show="open" x-transition>
            <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full mx-4" @click.away="open = false">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Delete Bills Confirmation</h3>
                    <button wire:click="closeDeleteModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-3">
                        You are about to submit <strong>{{ count($selectedBills ?? []) }}</strong> bill(s) for deletion approval.
                    </p>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Reason for Deletion <span class="text-error-500">*</span>
                    </label>
                    <textarea wire:model="deletionReason"
                        rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500"
                        placeholder="Please provide a reason for deleting these bills..."></textarea>
                    @error('deletionReason')
                        <span class="text-error-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex justify-end gap-2">
                    <button wire:click="closeDeleteModal"
                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded hover:bg-gray-50 text-sm transition">
                        Cancel
                    </button>
                    <button wire:click="submitSelectedBillsForDeletion"
                        class="px-4 py-2 bg-error-500 hover:bg-error-600 text-white rounded text-sm transition">
                        Submit for Approval
                    </button>
                </div>
            </div>
        </div>
    @endif
