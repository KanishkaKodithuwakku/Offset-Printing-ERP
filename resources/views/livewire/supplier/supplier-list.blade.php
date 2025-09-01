<div class="p-2 mx-auto max-w-screen-2xl md:p-4">
    <div class="space-y-5 sm:space-y-6">

        <!-- Flash Messages -->
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
        <!-- Supplier List Card -->
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex flex-col gap-3 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <h3 class="-ml-2 text-lg font-semibold text-gray-800 dark:text-white/90">Supplier List</h3>

                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-end">
                    <!-- Search -->
                    <div class="relative">
                        <span class="pointer-events-none absolute top-1/2 left-4 -translate-y-1/2">
                            <!-- Job List Style Search Icon -->
                            <svg class="fill-gray-500  dark:fill-gray-400" width="15" height="15"
                                    viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M3.04199 9.37381C3.04199 5.87712 5.87735 3.04218 9.37533 3.04218C12.8733 3.04218 15.7087 5.87712 15.7087 9.37381C15.7087 12.8705 12.8733 15.7055 9.37533 15.7055C5.87735 15.7055 3.04199 12.8705 3.04199 9.37381ZM9.37533 1.54218C5.04926 1.54218 1.54199 5.04835 1.54199 9.37381C1.54199 13.6993 5.04926 17.2055 9.37533 17.2055C11.2676 17.2055 13.0032 16.5346 14.3572 15.4178L17.1773 18.2381C17.4702 18.531 17.945 18.5311 18.2379 18.2382C18.5308 17.9453 18.5309 17.4704 18.238 17.1775L15.4182 14.3575C16.5367 13.0035 17.2087 11.2671 17.2087 9.37381C17.2087 5.04835 13.7014 1.54218 9.37533 1.54218Z"
                                        fill="" />
                                </svg>
                        </span>
                        <input type="text" wire:model.live.debounce.300ms="searchTerm"
                            placeholder="Search suppliers..."
                            class="pl-9 pr-3 py-2 text-sm rounded-lg border border-gray-300 bg-white shadow-sm focus:ring focus:ring-brand-100 focus:border-brand-400 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:border-gray-700 w-full xl:w-[300px]" />
                    </div>

                    <!-- Add Supplier Button -->
                    <a wire:navigate href="{{ route('suppliers.create') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white transition hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Add Supplier
                    </a>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class=" min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                    <thead class="border-t dark:bg-gray-900">
                        <tr>
                            <th class="px-4 py-3 text-left font-normal text-gray-500 dark:text-white/80">ID</p></th>
                            <th class="px-4 py-3 text-left font-normal text-gray-500 dark:text-white/80">Name</th>
                            <th class="px-4 py-3 text-left font-normal text-gray-500 dark:text-white/80">Company Name</th>
                            <th class="px-4 py-3 text-left font-normal text-gray-500 dark:text-white/80" style="width:15%">Phone</th>
                            <th class="px-4 py-3 text-left font-normal text-gray-500 dark:text-white/80 " style="width:15%">Email</th>
                            <th class="px-4 py-3 text-left font-normal text-gray-500 dark:text-white/80">Address</th>
                            <th class="px-4 py-3 text-left font-normal text-gray-500 dark:text-white/80">Status</th>
                            <th class="px-4 py-3 text-left font-normal text-gray-500 dark:text-white/80">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse ($suppliers as $supplier)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 ">
                                <td class="px-4 py-2 text-xs text-gray-700 dark:text-white/90">{{ $supplier->id }}
                                </td>
                                <td class="px-4 py-1 text-theme-xs text-gray-600 dark:text-white/90">{{ $supplier->name }}
                                </td>
                                <td class="px-4 py-1 text-theme-xs text-gray-600 dark:text-white/90">{{ $supplier->company_name }}
                                </td>
                                <td class="px-4 py-1 text-theme-xs text-gray-6 dark:text-white/90">{{ $supplier->phone }}
                                </td>
                                <td class="px-4 py-1 text-theme-xs text-gray-600 dark:text-white/90">{{ $supplier->email }}
                                </td>
                                <td class="px-4 py-1 text-theme-xs text-gray-600 dark:text-white/90">{{ $supplier->address }}
                                </td>
                                <td class="px-4 py-1 text-theme-xs">
                                    @if ($supplier->status == 'active')
                                        <span class="text-success-600 bg-success-50 font-medium">Active</span>
                                    @else
                                        <span class="text-error-600 bg-error-50">Inactive</span>
                                    @endif
                                </td>

                                <td class="px-4 py-1 text-xs space-x-2">
                                    <!-- Edit Button -->

                                    <div x-data="{ showConfirm: false }" class="relative ">

                                        <a wire:navigate href="{{ route('suppliers.edit', $supplier->id) }}"
                                            class="inline-flex items-center pr-2 text-gray-500 hover:text-brand-500 dark:text-blue-400 dark:hover:text-blue-300">
                                            <svg class="fill-current" width="19" height="19" viewBox="0 0 21 21"
                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M17.0911 3.53206C16.2124 2.65338 14.7878 2.65338 13.9091 3.53206L5.6074 11.8337C5.29899 12.1421 5.08687 12.5335 4.99684 12.9603L4.26177 16.445C4.20943 16.6931 4.286 16.9508 4.46529 17.1301C4.64458 17.3094 4.90232 17.3859 5.15042 17.3336L8.63507 16.5985C9.06184 16.5085 9.45324 16.2964 9.76165 15.988L18.0633 7.68631C18.942 6.80763 18.942 5.38301 18.0633 4.50433L17.0911 3.53206ZM14.9697 4.59272C15.2626 4.29982 15.7375 4.29982 16.0304 4.59272L17.0027 5.56499C17.2956 5.85788 17.2956 6.33276 17.0027 6.62565L16.1043 7.52402L14.0714 5.49109L14.9697 4.59272ZM13.0107 6.55175L6.66806 12.8944C6.56526 12.9972 6.49455 13.1277 6.46454 13.2699L5.96704 15.6283L8.32547 15.1308C8.46772 15.1008 8.59819 15.0301 8.70099 14.9273L15.0436 8.58468L13.0107 6.55175Z"
                                                    fill="" />
                                            </svg>
                                        </a>

                                        <!-- Delete Icon Button -->
                                        @if (auth()->user()->mode === 'admin')
                                        <button @click="showConfirm = true"
                                            class="text-gray-500 hover:text-error-500 dark:text-gray-400 dark:hover:text-error-500"
                                            title="Delete">
                                            <svg class="fill-current" width="18" height="18" viewBox="0 0 21 21" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M7.04142 4.29199C7.04142 3.04935 8.04878 2.04199 9.29142 2.04199H11.7081C12.9507 2.04199 13.9581 3.04935 13.9581 4.29199V4.54199H16.1252H17.166C17.5802 4.54199 17.916 4.87778 17.916 5.29199C17.916 5.70621 17.5802 6.04199 17.166 6.04199H16.8752V8.74687V13.7469V16.7087C16.8752 17.9513 15.8678 18.9587 14.6252 18.9587H6.37516C5.13252 18.9587 4.12516 17.9513 4.12516 16.7087V13.7469V8.74687V6.04199H3.8335C3.41928 6.04199 3.0835 5.70621 3.0835 5.29199C3.0835 4.87778 3.41928 4.54199 3.8335 4.54199H4.87516H7.04142V4.29199ZM15.3752 13.7469V8.74687V6.04199H13.9581H13.2081H7.79142H7.04142H5.62516V8.74687V13.7469V16.7087C5.62516 17.1229 5.96095 17.4587 6.37516 17.4587H14.6252C15.0394 17.4587 15.3752 17.1229 15.3752 16.7087V13.7469ZM8.54142 4.54199H12.4581V4.29199C12.4581 3.87778 12.1223 3.54199 11.7081 3.54199H9.29142C8.87721 3.54199 8.54142 3.87778 8.54142 4.29199V4.54199ZM8.8335 8.50033C9.24771 8.50033 9.5835 8.83611 9.5835 9.25033V14.2503C9.5835 14.6645 9.24771 15.0003 8.8335 15.0003C8.41928 15.0003 8.0835 14.6645 8.0835 14.2503V9.25033C8.0835 8.83611 8.41928 8.50033 8.8335 8.50033ZM12.9168 9.25033C12.9168 8.83611 12.581 8.50033 12.1668 8.50033C11.7526 8.50033 11.4168 8.83611 11.4168 9.25033V14.2503C11.4168 14.6645 11.7526 15.0003 12.1668 15.0003C12.581 15.0003 12.9168 14.6645 12.9168 14.2503Z"
                                                    fill="" />
                                            </svg>
                                        </button>
                                        @else
                                        <button
                                            class="text-gray-300 hover:text-gray-300 dark:text-gray-400 dark:hover:text-error-500"
                                            title="Delete">
                                            <svg class="fill-current" width="18" height="18"
                                                viewBox="0 0 21 21" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M7.04142 4.29199C7.04142 3.04935 8.04878 2.04199 9.29142 2.04199H11.7081C12.9507 2.04199 13.9581 3.04935 13.9581 4.29199V4.54199H16.1252H17.166C17.5802 4.54199 17.916 4.87778 17.916 5.29199C17.916 5.70621 17.5802 6.04199 17.166 6.04199H16.8752V8.74687V13.7469V16.7087C16.8752 17.9513 15.8678 18.9587 14.6252 18.9587H6.37516C5.13252 18.9587 4.12516 17.9513 4.12516 16.7087V13.7469V8.74687V6.04199H3.8335C3.41928 6.04199 3.0835 5.70621 3.0835 5.29199C3.0835 4.87778 3.41928 4.54199 3.8335 4.54199H4.87516H7.04142V4.29199ZM15.3752 13.7469V8.74687V6.04199H13.9581H13.2081H7.79142H7.04142H5.62516V8.74687V13.7469V16.7087C5.62516 17.1229 5.96095 17.4587 6.37516 17.4587H14.6252C15.0394 17.4587 15.3752 17.1229 15.3752 16.7087V13.7469ZM8.54142 4.54199H12.4581V4.29199C12.4581 3.87778 12.1223 3.54199 11.7081 3.54199H9.29142C8.87721 3.54199 8.54142 3.87778 8.54142 4.29199V4.54199ZM8.8335 8.50033C9.24771 8.50033 9.5835 8.83611 9.5835 9.25033V14.2503C9.5835 14.6645 9.24771 15.0003 8.8335 15.0003C8.41928 15.0003 8.0835 14.6645 8.0835 14.2503V9.25033C8.0835 8.83611 8.41928 8.50033 8.8335 8.50033ZM12.9168 9.25033C12.9168 8.83611 12.581 8.50033 12.1668 8.50033C11.7526 8.50033 11.4168 8.83611 11.4168 9.25033V14.2503C11.4168 14.6645 11.7526 15.0003 12.1668 15.0003C12.581 15.0003 12.9168 14.6645 12.9168 14.2503Z"
                                                fill="" />
                                            </svg>
                                         </button>
                                        @endif

                                        <!-- Confirmation Modal -->
                                        <div x-show="showConfirm" class="fixed inset-0 flex items-center justify-center p-5 overflow-y-auto z-50">
                                            <div class="modal-close-btn fixed inset-0 h-full w-full bg-gray-400/50 backdrop-blur-[30px]"></div>
                                            <div class="flex flex-col px-4 py-4 overflow-y-auto no-scrollbar">
                                                <div @click.outside="showConfirm = false"
                                                    class="relative w-full max-w-[507px] rounded-3xl bg-white p-6 dark:bg-gray-900 lg:p-10">
                                                    <div class="text-center">
                                                        <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">
                                                            Confirm Deletion
                                                        </h4>
                                                        <p class="text-sm leading-6 text-gray-500 dark:text-gray-400">
                                                            Are you sure you want to delete this supplier? Click Delete to confirm.
                                                        </p>

                                                        <div class="flex items-center justify-center w-full gap-3 mt-8">
                                                            <button @click="showConfirm = false" type="button"
                                                                class="flex justify-center rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">
                                                                Cancel
                                                            </button>
                                                            <button type="button"
                                                                @click="$wire.deleteSupplier({{ $supplier->id }}); showConfirm = false"
                                                                class="flex justify-center px-4 py-3 text-sm font-medium text-white rounded-lg bg-brand-500 shadow-theme-xs hover:bg-error-600">
                                                                Delete
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-4 text-center text-gray-500 dark:text-white/70">
                                    No suppliers found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex justify-between items-center border-t px-6 py-4 dark:border-gray-800">
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    Showing {{ $suppliers->firstItem() }} to {{ $suppliers->lastItem() }} of
                    {{ $suppliers->total() }} entries
                </div>

                <div class="pagination flex justify-between">
                    {{ $suppliers->links() }}
                    {{ $suppliers->links('vendor.pagination.custom-tailwind') }}
                </div>
            </div>
        </div>
    </div>
</div>
