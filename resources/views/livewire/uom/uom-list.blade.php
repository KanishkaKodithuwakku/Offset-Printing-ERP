

<div>
    <div class="my-4">
        @if (session('success'))
            <div class="rounded-xl border border-success-500 bg-success-50 p-4 dark:border-success-500/30 dark:bg-success-500/15">
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
            <div class="rounded-xl border border-red-500 bg-red-50 p-4 dark:border-red-500/30 dark:bg-red-500/10">
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
    </div>

    <div
        class="overflow-hidden rounded-xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">

        <!-- Header Section -->
        <div class="mb-4 flex flex-col gap-2 px-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    Units of Measure (UOM)
                </h3>
            </div>
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-end mt-2">
                <a wire:navigate href="{{ route('uoms.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-md text-sm shadow-md transition duration-300 ease-in-out transform hover:scale-105"
                    style="background-color:#465FFF;">
                    + Add UOM
                </a>
            </div>
        </div>

        <!-- Flash Messages -->


        <!-- Table Wrapper -->
        <div class="max-w-full overflow-x-auto">
            <div class="min-w-[1102px]">
                <!-- Table Header -->
                <div
                    class="grid grid-cols-12 border-t border-gray-200 dark:border-gray-800 text-theme-xs font-medium text-gray-700 dark:text-gray-400">
                    <div class="col-span-1 flex items-center border-r px-4 py-3">ID</div>
                    <div class="col-span-3 flex items-center border-r px-4 py-3">Name</div>
                    <div class="col-span-2 flex items-center border-r px-4 py-3">Abbreviation</div>
                    <div class="col-span-3 flex items-center border-r px-4 py-3">Description</div>
                    <div class="col-span-2 flex items-center border-r px-4 py-3">Status</div>
                    <div class="col-span-1 flex items-center px-4 py-3">Action</div>
                </div>

                <!-- Table Body -->
                @forelse ($uoms as $uom)
                    <div
                        class="grid grid-cols-12 border-t border-gray-100 dark:border-gray-800 text-theme-xs text-gray-700 dark:text-gray-400 py-2">
                        <div class="col-span-1 px-4 flex items-center">{{ $uom->id }}</div>
                        <div class="col-span-3 px-4 flex items-center">{{ $uom->name }}</div>
                        <div class="col-span-2 px-4 flex items-center">{{ $uom->abbreviation }}</div>
                        <div class="col-span-3 px-4 flex items-center">{{ $uom->description }}</div>
                        <div class="col-span-2 px-4 flex items-center">
                            @if ($uom->status === 'active')
                                <span
                                    class="bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500 rounded-full px-2 py-0.5">Active</span>
                            @else
                                <span
                                    class="bg-warning-50 text-warning-700 dark:bg-warning-500/15 dark:text-warning-400 rounded-full px-2 py-0.5">Inactive</span>
                            @endif
                        </div>
                        <div class="col-span-1 flex justify-center items-center px-4">
                            <a href="{{ route('uoms.edit', $uom->id) }}" wire:navigate
                                class="text-gray-500 hover:text-blue-700 dark:text-gray-400 dark:hover:text-white/90 text-sm font-medium">

                                <svg class="fill-current" width="19" height="19" viewBox="0 0 21 21"
                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M17.0911 3.53206C16.2124 2.65338 14.7878 2.65338 13.9091 3.53206L5.6074 11.8337C5.29899 12.1421 5.08687 12.5335 4.99684 12.9603L4.26177 16.445C4.20943 16.6931 4.286 16.9508 4.46529 17.1301C4.64458 17.3094 4.90232 17.3859 5.15042 17.3336L8.63507 16.5985C9.06184 16.5085 9.45324 16.2964 9.76165 15.988L18.0633 7.68631C18.942 6.80763 18.942 5.38301 18.0633 4.50433L17.0911 3.53206ZM14.9697 4.59272C15.2626 4.29982 15.7375 4.29982 16.0304 4.59272L17.0027 5.56499C17.2956 5.85788 17.2956 6.33276 17.0027 6.62565L16.1043 7.52402L14.0714 5.49109L14.9697 4.59272ZM13.0107 6.55175L6.66806 12.8944C6.56526 12.9972 6.49455 13.1277 6.46454 13.2699L5.96704 15.6283L8.32547 15.1308C8.46772 15.1008 8.59819 15.0301 8.70099 14.9273L15.0436 8.58468L13.0107 6.55175Z"
                                        fill="" />
                                </svg>
                            </a>
                            <div x-data="{ showConfirm: false }" class="col-span-1 flex justify-center items-center px-4">
                                <!-- Delete Button -->

                                @if (auth()->user()->mode === 'admin')
                                <button @click="showConfirm = true"
                                    class="text-gray-500 hover:text-error-500 dark:text-gray-400 dark:hover:text-error-500"
                                    title="Delete">
                                    <svg class="fill-current" width="18" height="18" viewBox="0 0 21 21"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
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

                                <!-- Custom Styled Confirmation Modal -->
                                <div x-show="showConfirm"
                                    class="fixed inset-0 flex items-center justify-center p-5 overflow-y-auto z-50">
                                    <div
                                        class="modal-close-btn fixed inset-0 h-full w-full bg-gray-400/50 backdrop-blur-[30px]">
                                    </div>

                                    <div class="flex flex-col px-4 py-4 overflow-y-auto no-scrollbar">
                                        <div @click.outside="showConfirm = false"
                                            class="relative w-full max-w-[507px] rounded-3xl bg-white p-6 dark:bg-gray-900 lg:p-10">
                                            <div class="text-center">
                                                <h4
                                                    class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">
                                                    Confirm Deletion
                                                </h4>
                                                <p class="text-sm leading-6 text-gray-500 dark:text-gray-400">
                                                    Are you sure you want to delete this UOM? Click Delete to confirm
                                                </p>

                                                <div class="flex items-center justify-center w-full gap-3 mt-8">
                                                    <button @click="showConfirm = false" type="button"
                                                        class="flex justify-center rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">
                                                        Cancel
                                                    </button>
                                                    <button type="button"
                                                        @click="$wire.deleteUom({{ $uom->id }}); showConfirm = false"
                                                        class="flex justify-center px-4 py-3 text-sm font-medium text-white rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600">
                                                        Delete
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-6 px-4 text-gray-500 text-sm">No UOMs found.</div>
                @endforelse
            </div>
        </div>

        <!-- Pagination -->
        <!-- Pagination Controls -->
        <div class="flex justify-between items-center border-t px-6 py-4 dark:border-gray-800">
            <div class="text-sm text-gray-600 dark:text-gray-400">
                Showing {{ $uoms->firstItem() }} to {{ $uoms->lastItem() }} of {{ $uoms->total() }} entries
            </div>

            <div class="pagination flex justify-between">
                {{ $uoms->links('vendor.pagination.custom-tailwind') }}
            </div>
        </div>


    </div>
</div>

