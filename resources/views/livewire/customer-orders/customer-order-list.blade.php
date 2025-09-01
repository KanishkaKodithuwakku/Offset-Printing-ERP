<div class="p-2 mx-auto max-w-screen-2xl md:p-4">
    <div class="space-y-5 sm:space-y-6">
        <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="mb-4 flex flex-col gap-2 px-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    Customer Orders
                </h3>

                <!-- Search Input -->
                <div class="relative">
                    <span class="pointer-events-none absolute top-1/2 left-4 -translate-y-1/2">
                        <svg class="fill-gray-500 dark:fill-gray-400" width="15" height="15" viewBox="0 0 20 20"
                            fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M3.04199 9.37381C3.04199 5.87712 5.87735 3.04218 9.37533 3.04218C12.8733 3.04218 15.7087 5.87712 15.7087 9.37381C15.7087 12.8705 12.8733 15.7055 9.37533 15.7055C5.87735 15.7055 3.04199 12.8705 3.04199 9.37381ZM9.37533 1.54218C5.04926 1.54218 1.54199 5.04835 1.54199 9.37381C1.54199 13.6993 5.04926 17.2055 9.37533 17.2055C11.2676 17.2055 13.0032 16.5346 14.3572 15.4178L17.1773 18.2381C17.4702 18.531 17.945 18.5311 18.2379 18.2382C18.5308 17.9453 18.5309 17.4704 18.238 17.1775L15.4182 14.3575C16.5367 13.0035 17.2087 11.2671 17.2087 9.37381C17.2087 5.04835 13.7014 1.54218 9.37533 1.54218Z"
                                fill="" />
                        </svg>
                    </span>
                    <input type="text" wire:model.live.debounce.100ms="searchTerm" placeholder="Search orders..."
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-[32px] w-full rounded-lg border border-gray-300 bg-transparent py-1.5 pr-4 pl-[42px] text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden xl:w-[300px] dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                </div>
                <a wire:navigate href="{{ route('customer.orders.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-md text-sm shadow-md transition duration-300 ease-in-out transform hover:scale-105"
                    style="background-color:#465FFF;padding 8px 15px;">
                    + Add New Order
                </a>
            </div>

            <div class="custom-scrollbar max-w-full overflow-x-auto px-5 sm:px-6">
                <table class="min-w-full">
                    <thead class="border-y border-gray-100 py-2 dark:border-gray-800">
                        <tr>
                            <th class="py-2 font-normal whitespace-nowrap" wire:click="sortBy('id')">
                                <div class="flex items-center">
                                    <p class="text-theme-xs text-gray-500 dark:text-gray-400">Order ID</p>
                                </div>
                            </th>
                            <th class="py-2 font-normal whitespace-nowrap" wire:click="sortBy('id')">
                                <div class="flex items-center">
                                    <p class="text-theme-xs text-gray-500 dark:text-gray-400">Customer</p>
                                </div>
                            </th>
                            <th class="py-2 font-normal whitespace-nowrap" wire:click="sortBy('id')">
                                <div class="flex items-center">
                                    <p class="text-theme-xs text-gray-500 dark:text-gray-400">Total Amount</p>
                                </div>
                            </th>
                            <th class="py-2 font-normal whitespace-nowrap" wire:click="sortBy('id')">
                                <div class="flex items-center">
                                    <p class="text-theme-xs text-gray-500 dark:text-gray-400">Payment Method</p>
                                </div>
                            </th>
                            <th class="py-2 font-normal whitespace-nowrap" wire:click="sortBy('id')">
                                <div class="flex items-center">
                                    <p class="text-theme-xs text-gray-500 dark:text-gray-400">Status</p>
                                </div>
                            </th>
                            <th class="py-2 font-normal whitespace-nowrap" wire:click="sortBy('id')">
                                <div class="flex items-center">
                                    <p class="text-theme-xs text-gray-500 dark:text-gray-400">Created Date</p>
                                </div>
                            </th>
                            <th class="py-2 font-normal whitespace-nowrap" wire:click="sortBy('id')">
                                <div class="flex items-center">
                                    <p class="text-theme-xs text-gray-500 dark:text-gray-400">Actions</p>
                                </div>
                            </th>

                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach ($orders as $order)
                            <tr class="hover:bg-gray-200 transition cursor-pointer" wire:navigate
                                href="{{ route('customer.orders.view', $order->id) }}">
                                <td class="py-1 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-theme-xs text-gray-700 dark:text-gray-400">
                                            {{ $order->order_number }}
                                        </p>
                                    </div>
                                </td>
                                <td class="py-1 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if ($order->status === 'plan')
                                            <a class="text-theme-xs text-gray-700 dark:text-gray-400">
                                                {{ $order->customer->name }}
                                            </a>
                                        @else
                                            <a class="text-theme-xs text-gray-700 dark:text-gray-400" wire:navigate
                                                href="{{ route('customer.orders.view', $order->id) }}">
                                                {{ $order->customer->name }}
                                            </a>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-1 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-theme-xs text-gray-700 dark:text-gray-400">
                                            {{ number_format($order->total_amount, 2) }} LKR
                                        </p>
                                    </div>
                                </td>
                                <td class="py-1 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-theme-xs text-gray-700 dark:text-gray-400">
                                            {{ ucfirst($order->payment_method) }}
                                        </p>
                                    </div>
                                </td>
                                <td class="py-1 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-theme-xs text-gray-700 dark:text-gray-400">
                                            {{ ucfirst($order->status) }}
                                        </p>
                                    </div>
                                </td>
                                <td class="py-1 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <p class="text-theme-xs text-gray-700 dark:text-gray-400">
                                            {{ $order->created_at->format('d-m-Y') }}
                                        </p>
                                    </div>
                                </td>
                                <td class="py-1 whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        <div x-data="{ openDropDown: false }" class="relative">
                                            <button @click="openDropDown = !openDropDown"
                                                class="text-gray-500 dark:text-gray-400">
                                                <svg class="fill-current" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M5.99902 10.245C6.96552 10.245 7.74902 11.0285 7.74902 11.995V12.005C7.74902 12.9715 6.96552 13.755 5.99902 13.755C5.03253 13.755 4.24902 12.9715 4.24902 12.005V11.995C4.24902 11.0285 5.03253 10.245 5.99902 10.245ZM17.999 10.245C18.9655 10.245 19.749 11.0285 19.749 11.995V12.005C19.749 12.9715 18.9655 13.755 17.999 13.755C17.0325 13.755 16.249 12.9715 16.249 12.005V11.995C16.249 11.0285 17.0325 10.245 17.999 10.245ZM13.749 11.995C13.749 11.0285 12.9655 10.245 11.999 10.245C11.0325 10.245 10.249 11.0285 10.249 11.995V12.005C10.249 12.9715 11.0325 13.755 11.999 13.755C12.9655 13.755 13.749 12.9715 13.749 12.005V11.995Z"
                                                        fill="" />
                                                </svg>
                                            </button>
                                            <div x-show="openDropDown" @click.outside="openDropDown = false"
                                                class="shadow-theme-lg dark:bg-gray-dark absolute top-full right-0 z-40 w-40 space-y-1 rounded-2xl border border-gray-200 bg-white p-2 dark:border-gray-800">
                                                <a class="text-theme-xs flex w-full rounded-lg px-3 py-2 text-left font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300"
                                                    wire:navigate href="{{ route('items.view', $order->id) }}">
                                                    View More
                                                </a>
                                                <a href="{{ route('items.edit', $order->id) }}" wire:navigate
                                                    class="text-theme-xs flex w-full rounded-lg px-3 py-2 text-left font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300">
                                                    Edit
                                                </a>
                                                <button wire:click="deleteItem({{ $order->id }})"
                                                    class="text-theme-xs flex w-full rounded-lg px-3 py-2 text-left font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300">
                                                    Delete
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>


            <div class="flex justify-between items-center border-t px-6 py-4 dark:border-gray-800">
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of
                    {{ $orders->total() }} entries
                </div>

                <div class="pagination flex justify-between">
                    {{ $orders->links('vendor.pagination.custom-tailwind') }}
                </div>
            </div>
        </div>
    </div>
</div>
