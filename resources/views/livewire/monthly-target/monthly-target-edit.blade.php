<div class="p-4 flex justify-left">
    <!-- Success/Error Messages -->
    @if (session('success'))
        <div class="mb-4 rounded-xl border border-success-500 bg-success-50 p-4 dark:border-success-500/30 dark:bg-success-500/15">
            <div class="flex items-start gap-3">
                <div class="-mt-0.5 text-success-500">
                    <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M3.70186 12.0001C3.70186 7.41711 7.41711 3.70186 12.0001 3.70186C16.5831 3.70186 20.2984 7.41711 20.2984 12.0001C20.2984 16.5831 16.5831 20.2984 12.0001 20.2984C7.41711 20.2984 3.70186 16.5831 3.70186 12.0001ZM12.0001 1.90186C6.423 1.90186 1.90186 6.423 1.90186 12.0001C17.5772 6.423 22.0984 12.0001 22.0984 17.5772 22.0984 6.423 17.5772 1.90186 12.0001 1.90186ZM15.6197 10.7395C15.9712 10.388 15.9712 9.81819 15.6197 9.46672C15.2683 9.11525 14.6984 9.11525 14.347 9.46672L11.1894 12.6243L9.6533 11.0883C9.30183 10.7368 8.73198 10.7368 8.38051 11.0883C8.02904 11.4397 8.02904 12.0096 8.38051 12.3611L10.553 14.5335C10.7217 14.7023 10.9507 14.7971 11.1894 14.7971C11.428 14.7971 11.657 14.7023 11.8257 14.5335L15.6197 10.7395Z" fill="" />
                    </svg>
                </div>
                <div>
                    <h4 class="mb-1 text-sm font-semibold text-gray-800 dark:text-white/90">Success</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 rounded-xl border border-error-500 bg-error-50 p-4 dark:border-error-500/30 dark:bg-error-500/10">
            <div class="flex items-start gap-3">
                <div class="-mt-0.5 text-error-500">
                    <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M12 3C7.031 3 3 7.031 3 12s4.031 9 9 9 9-4.031 9-9-4.031-9-9-9Zm0 16c-3.866 0-7-3.134-7-7s3.134-7 7-7 7 3.134 7 7-3.134 7-7 7Zm-.75-11a.75.75 0 0 1 1.5 0v4.5a.75.75 0 0 1-1.5 0V8Zm0 6.75a.75.75 0 1 1 1.5 0 .75.75 0 0 1-1.5 0Z" fill="" />
                    </svg>
                </div>
                <div>
                    <h4 class="mb-1 text-sm font-semibold text-gray-800 dark:text-white/90">Error</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <form wire:submit.prevent="updateMonthlyTarget" class="w-full" style="width: 60%;">
        <div
            class="w-full max-w-4xl rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] grid grid-cols-1 gap-0 sm:grid-cols-2">

            <!-- Left Column -->
            <div class="space-y-0 border-gray-100 dark:border-gray-800">
                <div class="px-5 py-4 sm:px-6 sm:py-5">
                    <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                        Edit Monthly Target
                    </h3>
                </div>
                <div class="p-5 border-t space-y-6 sm:p-6">
                    <!-- Month -->
                    <div class="w-full">
                        <label class="mb-1.5 block text-xs font-medium text-gray-700 dark:text-gray-400">
                            Month<span class="text-error-500">*</span>
                        </label>
                        <select wire:model="month" id="month"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                            @foreach($this->monthOptions as $monthOption)
                                <option value="{{ $monthOption }}" {{ $month === $monthOption ? 'selected' : '' }}>{{ $monthOption }}</option>
                            @endforeach
                        </select>
                        @error('month')
                            <p class="text-error-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Year -->
                    <div class="w-full">
                        <label class="mb-1.5 block text-xs font-medium text-gray-700 dark:text-gray-400">
                            Year<span class="text-error-500">*</span>
                        </label>
                        <select wire:model="year" id="year"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                            @foreach($this->yearOptions as $yearOption)
                                <option value="{{ $yearOption }}" {{ $year == $yearOption ? 'selected' : '' }}>{{ $yearOption }}</option>
                            @endforeach
                        </select>
                        @error('year')
                            <p class="text-error-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Target Amount -->
                    <div class="w-full">
                        <label class="mb-1.5 block text-xs font-medium text-gray-700 dark:text-gray-400">
                            Target Amount<span class="text-error-500">*</span>
                        </label>
                        <input type="number" wire:model="target_amount" id="target_amount"
                            placeholder="Enter target amount" step="0.01" min="0.01"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                        @error('target_amount')
                            <p class="text-error-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-0 mt-6">
                <div class="px-5 py-4 sm:px-6 sm:py-5">
                    <h3 class="pl-2 text-base font-medium text-gray-800 dark:text-white/90"></h3>
                </div>
                <div class="p-5 border-t space-y-5 sm:p-6">
                    <!-- Description -->
                    <div class="w-full">
                        <label class="mb-1.5 block text-xs font-medium text-gray-700 dark:text-gray-400">
                            Description
                        </label>
                        <textarea wire:model="description" id="description" placeholder="Description (optional)"
                            class="h-20 dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"></textarea>
                        @error('description')
                            <p class="text-error-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="w-full">
                        <label class="mb-1.5 block text-xs font-medium text-gray-700 dark:text-gray-400">
                            Status
                        </label>
                        <select wire:model="status" id="status"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                            <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <p class="text-error-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-2 pb-1 flex justify-end">
                        <button type="submit"
                            class="bg-brand-600 hover:bg-brand-700 text-white font-medium px-6 py-2 rounded-md text-xs shadow-md transition duration-300 ease-in-out transform hover:scale-105"
                            style="background-color:#465FFF;">
                            Update
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>
