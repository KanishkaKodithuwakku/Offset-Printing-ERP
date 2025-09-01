<div class="mx-auto max-w-screen-xl p-4 grid grid-cols-1 sm:grid-cols-1 gap-3">
    <div class="bg-white p-6 rounded-lg shadow-md">



        <div class="p-5 mb-6 border border-gray-200 rounded-2xl dark:border-gray-800 lg:p-6">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90 lg:mb-1">
                        Invoice # <span
                            class="inline-flex items-center justify-center gap-1 rounded-full bg-brand-50 px-2.5 py-0.5 text-sm font-medium text-brand-500 dark:bg-brand-500/15 dark:text-brand-400">
                            {{ $invoice->invoice_number }}
                        </span>

                        <span
                            class="inline-flex items-center justify-center gap-1 rounded-full bg-blue-light-50 px-2.5 py-0.5 text-sm font-medium text-blue-light-500 dark:bg-blue-light-500/15 dark:text-blue-light-500">
                        </span>
                        <p>PO: {{ $customer_po_number }}</p>
                    </h4>
                    <p class="mb-1 text-xs leading-normal text-gray-500 dark:text-gray-400">
                        Status:
                        <span
                            class="inline-flex items-center justify-center gap-1 rounded-full bg-warning-50 px-2.5 py-0.5 text-sm font-medium text-warning-600 dark:bg-warning-500/15 dark:text-orange-400">
                            {{ $status }}
                        </span>
                        @if ($invoice->status === 'cancelled')
                        <span class="block mt-1 text-xs ">
                            <div class="flex items-center gap-2">
                                <div class="text-gray-500  text-xs">
                                    Cancelled Date:
                                </div>
                                <div class="text-error-500  text-xs">
                                    {{ \Carbon\Carbon::parse($invoice->updated_at)->format('d-m-Y') }}<br>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="text-gray-500  text-xs">
                                    Reason:
                                </div>
                                <div class="text-error-500  text-xs">
                                    {{ $invoice->cancel_reason }}
                                </div>
                            </div>


                        </span>
                        @endif
                    </p>
                    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2 lg:gap-15">
                        <div class="grid grid-cols-1 gap-1 lg:grid-cols-2 lg:gap-2 2xl:gap-x-30">
                            <div>
                                <p class="mb-1 text-xs leading-normal text-gray-500 dark:text-gray-400">
                                    Customer Name
                                </p>
                                <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                    {{ $invoice->customer->name }}
                                </p>
                            </div>

                            <div>
                                <p class="mb-1 text-xs leading-normal text-gray-500 dark:text-gray-400">
                                    Address
                                </p>
                                <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                </p>
                            </div>

                            <div>
                                <p class="mb-1 text-xs leading-normal text-gray-500 dark:text-gray-400">
                                    Email address
                                </p>
                                <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                </p>
                            </div>

                            <div>
                                <p class="mb-1 text-xs leading-normal text-gray-500 dark:text-gray-400">
                                    Phone
                                </p>
                                <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add PO Number Input to the right side -->
                @if ($invoice->status === 'invoicing')
                <div class="flex items-start flex-col lg:w-1/3 lg:ml-6">
                    <label for="po_number" class="text-xs leading-normal text-gray-500 dark:text-gray-400 mb-2">PO
                        Number</label>
                    <input type="text" id="po_number" wire:model="customer_po_number"
                        class="w-full h-8 px-4 py-2.5 text-sm border rounded-lg text-gray-800 dark:bg-dark-900 dark:text-white/90 dark:border-gray-700 focus:ring-3 focus:ring-brand-500 focus:outline-none"
                        placeholder="Enter PO Number">
                    <button wire:click="updatePoNumber" class="mt-2 px-4 h-8 rounded-lg text-sm font-medium flex items-center justify-center gap-2 shadow-theme-xs
bg-brand-500 hover:bg-brand-600 text-white">Save
                        Po Number</button>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Update the PO number for this invoice.
                    </p>
                </div>
                @endif
            </div>
        </div>

    </div>




    @if (session()->has('success'))
    <div x-data="{ open: true }" x-show="open" x-transition
        class="relative rounded-xl border border-success-500 bg-success-50 p-4 dark:border-success-500/30 dark:bg-success-500/15 mb-5">
        <div class="flex flex-row justify-end">
            <!-- Close button positioned in the top-right corner -->
            <button @click="open = false" class="absolute top-2 right-2 text-gray-400 hover:text-red-700">
                <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    fill="none" viewBox="0 0 24 24">
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

    @if (session()->has('error'))
    <div class="rounded-xl border border-error-500 bg-error-50 p-4 dark:border-error-500/30 dark:bg-error-500/15 mb-2">
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
                    {{-- @if ($stockProcess === false)
                    One or more items stock balance is 0
                    @else
                    {{ session('error') }}
                    @endif --}}
                </p>
            </div>
        </div>
    </div>
    @endif


    <div class="border-t border-gray-100 dark:border-gray-800">
        <div class="rounded-xl border border-gray-200 p-6 dark:border-gray-800" x-data="{ activeTab: 'overview' }">
            <div class="border-b border-gray-200 dark:border-gray-800">
                <nav
                    class="-mb-px flex space-x-2 overflow-x-auto [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-thumb]:bg-gray-200 dark:[&::-webkit-scrollbar-thumb]:bg-gray-600 dark:[&::-webkit-scrollbar-track]:bg-transparent [&::-webkit-scrollbar]:h-1.5">
                    <button
                        class="inline-flex items-center gap-2 border-b-2 px-2.5 py-2 text-sm font-medium transition-colors duration-200 ease-in-out"
                        x-bind:class="activeTab === 'overview' ?
                            ' text-brand-500 border-brand-500  dark:text-brand-400 dark:border-brand-400' :
                            'bg-transparent text-gray-500 border-transparent  hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'"
                        x-on:click="activeTab = 'overview'">
                        <svg class="size-5" width="20" height="20" viewBox="0 0 20 20" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M4.83203 2.5835C3.58939 2.5835 2.58203 3.59085 2.58203 4.83349V7.25015C2.58203 8.49279 3.58939 9.50015 4.83203 9.50015H7.2487C8.49134 9.50015 9.4987 8.49279 9.4987 7.25015V4.8335C9.4987 3.59086 8.49134 2.5835 7.2487 2.5835H4.83203ZM4.08203 4.83349C4.08203 4.41928 4.41782 4.0835 4.83203 4.0835H7.2487C7.66291 4.0835 7.9987 4.41928 7.9987 4.8335V7.25015C7.9987 7.66436 7.66291 8.00015 7.2487 8.00015H4.83203C4.41782 8.00015 4.08203 7.66436 4.08203 7.25015V4.83349ZM4.83203 10.5002C3.58939 10.5002 2.58203 11.5075 2.58203 12.7502V15.1668C2.58203 16.4095 3.58939 17.4168 4.83203 17.4168H7.2487C8.49134 17.4168 9.4987 16.4095 9.4987 15.1668V12.7502C9.4987 11.5075 8.49134 10.5002 7.2487 10.5002H4.83203ZM4.08203 12.7502C4.08203 12.336 4.41782 12.0002 4.83203 12.0002H7.2487C7.66291 12.0002 7.9987 12.336 7.9987 12.7502V15.1668C7.9987 15.5811 7.66291 15.9168 7.2487 15.9168H4.83203C4.41782 15.9168 4.08203 15.5811 4.08203 15.1668V12.7502ZM10.4987 4.83349C10.4987 3.59085 11.5061 2.5835 12.7487 2.5835H15.1654C16.408 2.5835 17.4154 3.59086 17.4154 4.8335V7.25015C17.4154 8.49279 16.408 9.50015 15.1654 9.50015H12.7487C11.5061 9.50015 10.4987 8.49279 10.4987 7.25015V4.83349ZM12.7487 4.0835C12.3345 4.0835 11.9987 4.41928 11.9987 4.83349V7.25015C11.9987 7.66436 12.3345 8.00015 12.7487 8.00015H15.1654C15.5796 8.00015 15.9154 7.66436 15.9154 7.25015V4.8335C15.9154 4.41928 15.5796 4.0835 15.1654 4.0835H12.7487ZM12.7487 10.5002C11.5061 10.5002 10.4987 11.5075 10.4987 12.7502V15.1668C10.4987 16.4095 11.5061 17.4168 12.7487 17.4168H15.1654C16.408 17.4168 17.4154 16.4095 17.4154 15.1668V12.7502C17.4154 11.5075 16.408 10.5002 15.1654 10.5002H12.7487ZM11.9987 12.7502C11.9987 12.336 12.3345 12.0002 12.7487 12.0002H15.1654C15.5796 12.0002 15.9154 12.336 15.9154 12.7502V15.1668C15.9154 15.5811 15.5796 15.9168 15.1654 15.9168H12.7487C12.3345 15.9168 11.9987 15.5811 11.9987 15.1668V12.7502Z"
                                fill="currentColor" />
                        </svg>
                        Invoice Items
                    </button>
                </nav>
            </div>

            <div class="pt-4 dark:border-gray-800">
                <div x-show="activeTab === 'overview'">
                    {{-- <h3 class="mb-1 text-lg font-medium text-gray-800 dark:text-white/90">
                        Invoice Items
                    </h3> --}}

                    <div class="max-w-full overflow-x-auto custom-scrollbar border">
                        <table class="w-full">
                            <thead>
                                <tr class="border-t border-gray-100 dark:border-gray-800">
                                    <th class="px-3 py-3 text-left">
                                        <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                            Item
                                        </p>
                                    </th>
                                    <th class="px-6 py-3 text-left">
                                        <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                            Selling Price (Rs)
                                        </p>
                                    </th>
                                    <th class="px-6 py-3 text-left">
                                        <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                            Quantity
                                        </p>
                                    </th>
                                    <th class="px-6 py-3 text-left">
                                        <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                            Total (Rs)
                                        </p>
                                    </th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoiceItems as $index => $invoiceItem)
                                <tr
                                    class="border-t border-gray-100 dark:border-gray-800 cursor-pointer hover:bg-gray-200">
                                    <td class="px-2 py-3">
                                        <p class="font-medium text-gray-500 text-theme-sm dark:text-white/90">
                                            {{ $invoiceItem['name'] }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-3">
                                        <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                            @if ($status === 'invoicing')
                                            <input type="text" wire:model.live="invoiceItems.{{ $index }}.unit_price"
                                                wire:change="updateUnitPrice({{ $index }})" min="0" max=""
                                                class="w-24 border p-1 text-right text-xs"
                                                value="{{ number_format($invoiceItem['unit_price'], 2) }}" style="">
                                            @else
                                            {{ number_format($invoiceItem['unit_price'], 2) }}
                                            @endif
                                        </p>
                                    </td>
                                    <td class="px-6 py-3">
                                        <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                            {{ $invoiceItem['quantity'] }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-3">
                                        <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                            {{ number_format($invoiceItem['total_price'], 2) }}
                                        </p>
                                    </td>
                                </tr>
                                @endforeach

                                @if ($isPlateBacking)

                                <tr>
                                    <td class="px-2 py-3">
                                        <p class="font-medium text-gray-500 text-theme-sm dark:text-white/90">
                                            Baked Plates
                                        </p>
                                    </td>

                                    <td class="px-6 py-3">
                                        <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                            @if ($status === 'invoicing')
                                            <input type="text" wire:model.live="backedPrice"
                                                wire:input="updateBackedPrice()" min="0" max=""
                                                class="w-24 border p-1 text-right text-xs" style="">
                                            @else
                                            {{ number_format($backedPrice, 2) }}
                                            @endif
                                        </p>
                                    </td>
                                    <td class="px-6 py-3">
                                        <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                            @if ($isPlateBacking)
                                            {{ $backedQty }}
                                            @endif
                                        </p>
                                    </td>
                                    <td class="px-6 py-3">
                                        <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                            {{ number_format($backedTotal, 2) }}
                                        </p>
                                    </td>
                                </tr>

                                @endif

                            </tbody>
                        </table>
                    </div>

                    <div class="pb-6 my-6 text-right border-b border-gray-100 dark:border-gray-800">
                        <p class="text-lg font-semibold text-gray-800 dark:text-white/90">
                            Total : @if ($status === 'invoicing')
                            <input wire:model.defer="total_amount" value="{{ number_format($total_amount, 2) }}"
                                type="text"
                                class="text-right dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-18 w-24 border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                            @else
                            {{ number_format($total_amount, 2) }}
                            @endif
                        </p>
                    </div>

                    <div class="flex items-center justify-end gap-3">

                        <button id="back-button" onclick="window.history.back();"
                            style="display: flex; align-items: center; font-family: outfit; gap: 0.5rem; padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; color: white; border-radius: 0.375rem; background-color: #6b7280; box-shadow: 0 2px 2px rgba(0, 0, 0, 0.1);">
                            <svg style="width: 1.5rem; height: 1rem; color: white;" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Back
                        </button>


                        @if ($invoice->status != 'cancelled')
                        <button wire:click="viewPrintPreview" @disabled($changesMade)
                            class="px-4 py-2 rounded-lg text-sm font-medium flex items-center justify-center gap-2 shadow-theme-xs
    {{ $changesMade ? 'bg-gray-200 text-gray-500 cursor-not-allowed' : 'bg-brand-500 hover:bg-brand-600 text-white' }}">
                            @if ($status === 'invoicing')
                            Generate Invoice
                            @else
                            Print Preview
                            @endif
                        </button>


                        @if ($status === 'invoicing')
                        <button wire:click="updateInvoiceItems" @disabled(!$changesMade)
                            class="px-4 h-9 rounded-lg text-sm font-medium flex items-center justify-center gap-2 shadow-theme-xs
    {{ $changesMade ? 'bg-brand-500 hover:bg-brand-600 text-white' : 'bg-gray-200 text-gray-500 cursor-not-allowed' }}">

                            <svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 8v8m0-8a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm0 8a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm12 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm0 0V9a3 3 0 0 0-3-3h-3m1.5-2-2 2 2 2" />
                            </svg>

                            Apply Changes
                        </button>
                        @endif
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




{{-- @if ($printCount > 0)
<style>
    @media print {
        body {
            display: none !important;
        }
    }

    body {
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }
</style>

<script>
    document.addEventListener('contextmenu', event => event.preventDefault());
        document.addEventListener('keydown', function (e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                e.preventDefault();
                alert('Printing is disabled for this invoice.');
            }
        });
</script>
@endif --}}