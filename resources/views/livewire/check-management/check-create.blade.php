<div class="flex flex-col h-screen bg-white rounded-lg shadow-md">
    @if (session()->has('error'))
        <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    @if (session()->has('success'))
        <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="p-10 px-6 py-3">
            <div class="p-4 border rounded-xl border-error-500 bg-error-50">
                <div class="flex items-start gap-3">
                    <div class="-mt-0.5 text-error-500">
                        <!-- your svg -->
                    </div>
                    <div>
                        <h4 class="mb-1 text-sm font-semibold text-gray-800">
                            Cheque Creation Failed!
                        </h4>
                        <p class="text-sm text-gray-500">
                            Please check the required fields!
                        </p>
                        <ul class="mt-1 text-xs text-error-500">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- MAIN FLEX ROW -->
    <div class="flex ">
        <!-- LEFT SIDE FORM -->
        <div class="flex-1 w-2/3">
            <form wire:submit.prevent="save" class="space-y-6">
                <div class="bg-white rounded-2xl">
                    <div class="flex items-center justify-between px-5 py-4">
                        <h3 class="text-base font-medium text-gray-800">
                            Add New Postdated Cheque
                        </h3>
                    </div>

                    <div class="p-6">
                        <div class="w-full space-y-6">
                            <!-- CUSTOMER SEARCH -->
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-700">
                                    Customer Name<span class="text-error-500">*</span>
                                </label>
                                <input type="text" wire:model.live.throttle.150ms="searchCustomer" placeholder="Search customer"
                                    class="w-full h-10 px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                                @error('customer_name')
                                    <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
                                @enderror
                            </div>

                            @if (!empty($searchResultsCustomer))
                                <div class="max-w-full mt-2 overflow-x-auto border">
                                    <table class="w-full">
                                        <thead>
                                            <tr class="border-t border-gray-100">
                                                <th class="px-3 py-3 text-xs font-medium text-left text-gray-500">ID</th>
                                                <th class="px-3 py-3 text-xs font-medium text-left text-gray-500">Name</th>
                                                <th class="px-6 py-3 text-xs font-medium text-left text-gray-500">Contact</th>
                                                <th class="px-6 py-3 text-xs font-medium text-left text-gray-500">Email</th>
                                                <th class="px-6 py-3 text-xs font-medium text-left text-gray-500">City</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($searchResultsCustomer as $customer)
                                                <tr wire:click="assignCustomer({{ $customer['id'] }})"
                                                    class="border-t cursor-pointer hover:bg-gray-200">
                                                    <td class="px-2 py-3.5 text-sm text-gray-500">{{ $customer['id'] }}</td>
                                                    <td class="px-2 py-3.5 text-sm text-gray-500">{{ $customer['name'] }}</td>
                                                    <td class="px-6 py-3.5 text-sm text-gray-500">{{ $customer['phone'] }}</td>
                                                    <td class="px-6 py-3.5 text-sm text-gray-500">{{ $customer['email'] }}</td>
                                                    <td class="px-6 py-3.5 text-sm text-gray-500">{{ $customer['city'] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif

                            @if ($customer_name)
                                <div class="p-3 mt-4 text-sm rounded-lg bg-gray-50">
                                    Selected Customer: <span class="font-semibold">{{ $customer_name }}</span>
                                </div>
                            @endif

                            <!-- CHEQUE NUMBER -->
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-700">
                                    Cheque Number<span class="text-error-500">*</span>
                                </label>
                                <input type="text" wire:model="cheque_number"
                                    class="w-full h-10 px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @error('cheque_number')
                                    <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- BANK & BRANCH -->
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Bank</label>
                                    <livewire:components.bank-select-with-add :selectedBank="$bank_id" />
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Branch</label>
                                    <livewire:components.branch-select-with-add :selectedBranch="$branch_id" />
                                </div>
                            </div>

                            <!-- AMOUNT -->
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-700">
                                    Amount<span class="text-error-500">*</span>
                                </label>
                                <input type="number" step="0.01" wire:model="amount"
                                    class="w-full h-10 px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @error('amount')
                                    <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- CHEQUE DATE -->
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-700">
                                    Cheque Date<span class="text-error-500">*</span>
                                </label>
                                <input type="date" wire:model="cheque_date" onclick="this.showPicker()"
                                    class="w-full h-10 px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @error('cheque_date')
                                    <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- DETAILS -->
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-700">Details</label>
                                <textarea wire:model="details" rows="4"
                                    class="w-full px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                                @error('details')
                                    <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- INVOICES -->
                          

                            <!-- BUTTONS -->
                            <div class="flex justify-end gap-3 mt-8 space-x-4">
                                <button type="button" wire:click="$dispatch('closeModal')"
                                    class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                                    Cancel
                                </button>
                                <button type="submit"
                                    class="px-6 py-2.5 text-sm font-medium text-dark bg-primary-600 border border-blue-700 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">
                                    Save Cheque
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class='flex-1 w-1/3 p-4'>
             @if ($customer_name)
                                <div class="pt-6 mt-8 border-t">
                                    <div class="flex items-center justify-between mb-4">
                                        <h4 class="text-sm font-medium text-gray-700">
                                            Customer Invoices  -(<span style="color: red">*</span>select on invoice name)
                                            @if (count($selectedInvoiceIds) > 0)
                                                <span class="text-blue-600">({{ count($selectedInvoiceIds) }} selected)</span>
                                            @endif
                                        </h4>
                                        <div class="w-1/3">
                                            <input type="text" wire:model.live.300ms="invoiceSearch" placeholder="Search invoices..."
                                                class="w-full h-10 px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                    </div>

                                    @if (count($customerInvoices) > 0)
                                        <div class="overflow-hidden border rounded-lg">
                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th class="px-4 py-3 text-xs font-medium text-left text-gray-500"></th>
                                                        <th class="px-4 py-3 text-xs font-medium text-left text-gray-500">Invoice #</th>
                                                        <th class="px-4 py-3 text-xs font-medium text-left text-gray-500">Amount Due</th>
                                                        <th class="px-4 py-3 text-xs font-medium text-left text-gray-500">Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($customerInvoices as $invoice)
                                                        <tr wire:click="selectInvoice({{ $invoice['id'] }}, '{{ $invoice['invoice_number'] }}')"
                                                            class="cursor-pointer hover:bg-gray-50 {{ in_array($invoice['id'], $selectedInvoiceIds) ? 'bg-blue-50' : '' }}">
                                                            <td class="px-4 py-3 text-sm">
                                                                <input type="checkbox" wire:model="selectedInvoiceIds" 
                                                                    value="{{ $invoice['id'] }}"
                                                                    class="text-blue-600 rounded focus:ring-blue-500">
                                                            </td>
                                                            <td class="px-4 py-3 text-sm">{{ $invoice['invoice_number'] }}</td>
                                                            <td class="px-4 py-3 text-sm">{{ number_format($invoice['amount'], 2) }}</td>
                                                            <td class="px-4 py-3 text-sm">{{ $invoice['date'] }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        @if (count($selectedInvoiceIds) > 0)
                                            <div class="p-3 mt-3 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg">
                                                Total Amount for Selected Invoices:
                                                <span class="font-bold">{{ number_format($amount, 2) }}</span>
                                            </div>
                                        @endif
                                    @else
                                        <div class="p-4 text-sm text-center text-gray-500">
                                            No invoices found for this customer
                                        </div>
                                    @endif
                                </div>
                            @endif
        </div>
    </div>
    
</div>
