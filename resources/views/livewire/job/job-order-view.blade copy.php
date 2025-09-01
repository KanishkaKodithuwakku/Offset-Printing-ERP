<div class="mt-6 bg-gray-100 p-4 rounded-lg">
    {{-- <h2 class="text-2xl font-semibold mb-4">GRN</h2> --}}

    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md">
            {{ session('success') }}
        </div>
    @elseif (session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md">
            {{ session('error') }}
        </div>
    @endif

    <div class="flex flex-wrap items-center gap-6 mb-4">
        <!-- Job Number -->
        <div class="flex-1">
            <label class="block text-gray-700 font-medium">Job Order #</label>
            <p class="text-gray-900">{{ $jobOrder->job_number }}</p>
        </div>

        <!-- Supplier -->
        <div class="flex-1">
            <label class="block text-gray-700 font-medium">Customer</label>
            <p class="text-gray-900">{{ $jobOrder->customer->name }}</p>
        </div>

        <!-- Status -->
        <div class="flex-1">
            <label class="block text-gray-700 font-medium">Status</label>
            <p class="text-gray-900">{{ ucfirst($jobOrder->status) }}</p>
        </div>
    </div>




    <h3 class="text-lg font-semibold mb-3">Items</h3>

    <!-- Search Item -->
    <div class="mt-4 mb-10">
        <label>Search Item</label>
        <input type="text" wire:model.live.throttle.150ms="searchTerm" placeholder="Search items..."
            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">

        @if (!empty($searchResults))
            <table class="w-full border">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border text-left px-4 py-2 text-sm">Name</th>
                        <th class="border text-left px-4 py-2 text-sm">Code</th>
                        <th class="border text-right px-4 py-2 text-sm">Price</th>
                        {{-- <th class="border text-center px-4 py-2 text-sm">Stock Qty</th> --}}
                        <th class="border text-center px-4 py-2 text-sm">Qty</th>
                        <th class="border text-center px-4 py-2 text-sm">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($searchResults as $item)
                        <tr>
                            <td class="border px-4 py-2 text-sm">{{ $item['item_name'] }}</td>
                            <td class="border px-4 py-2 text-sm">{{ $item['item_code'] }}</td>
                            <td class="border text-right px-4 py-2 text-sm">{{ $item['selling_price'] }} LKR</td>

                            <td class="border text-center px-4 py-2 text-sm">
                                <input type="number" wire:model="updatedQuantity.{{ $item['id'] }}" min="1"
                                    class="w-16 border p-1 text-center" value="1">
                            </td>
                            <td class="border text-center px-4 py-2 text-sm">
                                @php
                                    // Check if the item already exists in the grnItems list
                                    $existingItem = collect($grnItems)->firstWhere('item_id', $item['id']);
                                @endphp

                                @if ($item['same_item'])
                                    <!-- Show Update button if item exists -->
                                    <button style="background-color:#465FFF;color: #fff; border-radius: 4px;"
                                        class="bg-green-500 text-white px-2 py-1 rounded"
                                        wire:click="updateItemQty({{ $item['id'] }})">
                                        Update
                                    </button>
                                @else
                                    <!-- Show Add button if item does not exist -->
                                    <button style="background-color:#465FFF;color: #fff; border-radius: 4px;"
                                        class="bg-green-500 text-white px-2 py-1 rounded"
                                        wire:click="addItem({{ $item['id'] }})">
                                        Add
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach


                </tbody>
            </table>
        @endif
    </div>

    <!-- Job Items Table -->
    <div class="custom-scrollbar max-w-full overflow-x-auto">

        <table class="min-w-full">
            <thead class="border-y border-gray-100 py-2 dark:border-gray-800">
                <tr class="bg-gray-200">
                    <th class="py-2 font-normal whitespace-nowrap px-2">
                        <div class="flex items-center">
                            <p class="text-theme-xs text-gray-500 dark:text-gray-400">Item</p>
                        </div>
                    </th>

                    <th class="py-2 font-normal whitespace-nowrap">
                        <div class="flex items-center">
                            <p class="text-theme-xs text-gray-500 dark:text-gray-400">SKU Code</p>
                        </div>
                    </th>

                    <th class="py-2 font-normal whitespace-nowrap">
                        <div class="flex items-center">
                            <p class="text-theme-xs text-gray-500 dark:text-gray-400">Sales Price</p>
                        </div>
                    </th>
                    <th class="py-2 font-normal whitespace-nowrap">
                        <div class="flex items-center">
                            <p class="text-theme-xs text-gray-500 dark:text-gray-400">Purchase Price</p>
                        </div>
                    </th>
                    <th class="py-2 font-normal whitespace-nowrap">
                        <div class="flex items-center">
                            <p class="text-theme-xs text-gray-500 dark:text-gray-400">Quantity</p>
                        </div>
                    </th>

                    <th class="py-2 font-normal whitespace-nowrap">
                        <div class="flex items-center">
                            <p class="text-theme-xs text-gray-500 dark:text-gray-400">Total</p>
                        </div>
                    </th>

                    <th class="py-2 font-normal whitespace-nowrap">
                        <div class="flex items-center">
                            <p class="text-theme-xs text-gray-500 dark:text-gray-400">Remove</p>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                {{-- @foreach ($grnItems as $index => $grnItem) --}}
                    <tr class="cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800">
                        <td class="py-1 whitespace-nowrap">
                            <div class="flex items-center">
                                <p class="text-theme-xs text-gray-700 dark:text-gray-400 px-2">
                                    {{-- {{ $grnItem['item_name'] }} --}}
                                </p>
                            </div>
                        </td>
                        <td class="py-1 whitespace-nowrap">
                            <div class="flex items-center">
                                <p class="text-theme-xs text-gray-700 dark:text-gray-400">
                                    {{-- {{ $grnItem['sku_code'] }} --}}
                                </p>
                            </div>
                        </td>
                        <td class="py-1 whitespace-nowrap">
                            <div class="flex items-center">
                                <p class="text-theme-xs text-gray-700 dark:text-gray-400">
                                    {{-- {{ number_format($grnItem['selling_price'], 2) }} --}}
                                </p>
                            </div>
                        </td>
                        <td class="py-1 whitespace-nowrap">
                            <div class="flex items-center">
                                <p class="text-theme-xs text-gray-700 dark:text-gray-400">
                                    {{-- {{ number_format($grnItem['purchase_price'], 2) }} --}}
                                </p>
                            </div>
                        </td>
                        <td class="py-1 whitespace-nowrap">
                            <div class="flex items-center">
                                <p class="text-theme-xs text-gray-700 dark:text-gray-400">
                                    {{-- <input type="number" wire:model="grnItems.{{ $index }}.quantity"
                                        wire:change="updateTotal({{ $index }})" min="1"
                                        class="w-16 border p-1 text-center"> --}}
                                </p>
                            </div>
                        </td>
                        <td class="py-1 whitespace-nowrap">
                            <div class="flex items-center">
                                <p class="text-theme-xs text-gray-700 dark:text-gray-400">
                                    {{-- {{ number_format($grnItem['total'], 2) }} --}}
                                </p>
                            </div>
                        </td>
                        <td class="py-1 whitespace-nowrap text-center">
                            <div class="flex items-center">
                                <p class="text-theme-xs text-gray-700 dark:text-gray-400">
                                    {{-- <button wire:click="removeItem({{ $index }})"
                                        class="text-red-500">X</button> --}}
                                </p>
                            </div>
                        </td>
                    </tr>
                {{-- @endforeach --}}
            </tbody>
        </table>
    </div>


    <div class="flex flex-wrap items-center gap-6 mb-4 justify-start" style="background-color: #FFF">
        <!-- Save GRN Items Button -->
        <div class="">
            <button style="background-color:{{ $status !== 'pending' ? '#D3D3D3' : '#465FFF' }};" wire:click="saveGrnItems"
                class="flex items-center space-x-2 bg-[#465FFF] hover:bg-[#3b4ddb] text-white font-medium px-6 py-2 rounded-md text-sm shadow-md transition duration-300 ease-in-out transform hover:scale-105"
                {{ $status === 'completed' ? 'disabled' : '' }} wire:key="saveButton-{{ $status }}">
                Save Items
            </button>
        </div>
        <div class="">
            <button style="background-color: {{ $status !== 'pending' ? '#D3D3D3' : '#465FFF' }};"
                wire:click="processGrnItems" wire:key="processButton-{{ $status }}"
                class="flex items-center space-x-2 bg-[#465FFF] hover:bg-[#3b4ddb] text-white font-medium px-6 py-2 rounded-md text-sm shadow-md transition duration-300 ease-in-out transform hover:scale-105 {{ $status !== 'pending' ? 'cursor-not-allowed opacity-50' : '' }}"
                {{ $status !== 'pending' ? 'disabled' : '' }}>
                Process GRN
            </button>

        </div>

    </div>
</div>

{{-- <script>
    Livewire.on('statusUpdated', () => {
      alert();
    });
</script> --}}
