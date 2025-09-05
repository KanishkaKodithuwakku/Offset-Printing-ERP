<div class="mx-auto max-w-(--breakpoint-2xl) p-4 md:p-6">
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <div class="flex h-16 w-16 items-center justify-center rounded-3xl bg-blue-600 shadow-xl">
                    <img width="50" height="50" src="https://img.icons8.com/3d-fluency/50/control-panel.png"
                        alt="control-panel" />
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Dashboard
                    </h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Comprehensive overview of your business performance
                    </p>
                </div>
            </div>
            <!-- Time Period Selector and Refresh Button -->
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-0.5 rounded-lg bg-gray-100 p-0.5 dark:bg-gray-900">
                    <button wire:click="loadDailyData"
                        class="rounded-md px-3 py-2 text-sm font-medium transition-all duration-200 {{ $activePeriod === 'daily' ? 'bg-white text-gray-900 shadow-sm dark:bg-gray-800 dark:text-white' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}">
                        Daily
                    </button>
                    <button wire:click="loadWeeklyData"
                        class="rounded-md px-3 py-2 text-sm font-medium transition-all duration-200 {{ $activePeriod === 'weekly' ? 'bg-white text-gray-900 shadow-sm dark:bg-gray-800 dark:text-white' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}">
                        Weekly
                    </button>
                    <button wire:click="loadMonthlyData"
                        class="rounded-md px-3 py-2 text-sm font-medium transition-all duration-200 {{ $activePeriod === 'monthly' ? 'bg-white text-gray-900 shadow-sm dark:bg-gray-800 dark:text-white' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}">
                        Monthly
                    </button>
                    <button wire:click="loadYearlyData"
                        class="rounded-md px-3 py-2 text-sm font-medium transition-all duration-200 {{ $activePeriod === 'yearly' ? 'bg-white text-gray-900 shadow-sm dark:bg-gray-800 dark:text-white' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}">
                        Annually
                    </button>

                </div>

                <!-- Refresh Button -->
                <button wire:click="refreshData" wire:loading.attr="disabled"
                    class="flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white transition-all duration-200 hover:bg-brand-600 dark:bg-brand-500 dark:hover:bg-brand-500 disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg wire:loading.remove class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <div wire:loading
                        class="h-4 w-4 animate-spin rounded-full border-2 border-white border-t-transparent">
                    </div>
                    <span wire:loading.remove>Refresh</span>
                    <span wire:loading>Refreshing...</span>
                </button>


            </div>
        </div>
    </div>

    <!-- Main Metrics Grid -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:gap-6 xl:grid-cols-4 mb-6" x-data="{ animate: false }"
        x-init="setTimeout(() => animate = true, 100)">
        <!-- Revenue Card -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] transition-all duration-500 transform hover:scale-105 hover:shadow-lg"
            :class="animate ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'" style="transition-delay: 0ms;">
            <div class="flex items-center justify-between">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-600 shadow-lg">
                    <img width="48" height="48" src="https://img.icons8.com/fluency/48/money-bag--v1.png"
                        alt="money-bag--v1" />
                </div>
                <div class="flex items-center gap-1">
                    <span
                        class="flex items-center gap-1 rounded-full bg-success-50 px-2 py-0.5 text-xs font-medium text-success-600 dark:bg-success-500/15 dark:text-success-500">
                        +{{ number_format($currentData['revenue'] > 0 ? $currentData['revenue'] / 1000 : 0, 1) }}%
                    </span>
                </div>
            </div>
            <div class="mt-4">
                <h4 class="text-2xl font-bold text-gray-800 dark:text-white/90" data-target="{{ $currentData['revenue'] }}" data-prefix="Rs.">
                    Rs.0
                </h4>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Total Revenue
                </p>
            </div>
        </div>

        <!-- Payments Card -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] transition-all duration-500 transform hover:scale-105 hover:shadow-lg"
            :class="animate ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'" style="transition-delay: 100ms;">
            <div class="flex items-center justify-between">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-600 shadow-lg">
                    <img width="48" height="48" src="https://img.icons8.com/fluency/48/card-in-use-1.png"
                        alt="card-in-use-1" />
                </div>
                <div class="flex items-center gap-1">
                    <span
                        class="flex items-center gap-1 rounded-full bg-success-50 px-2 py-0.5 text-xs font-medium text-success-600 dark:bg-success-500/15 dark:text-success-500">
                        +{{ number_format($currentData['payments'] > 0 ? $currentData['payments'] / 1000 : 0, 1) }}%
                    </span>
                </div>
            </div>
            <div class="mt-4">
                <h4 class="text-2xl font-bold text-gray-800 dark:text-white/90" data-target="{{ $currentData['payments'] }}" data-prefix="Rs.">
                    Rs.0
                </h4>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Total Payments
                </p>
            </div>
        </div>

        <!-- Vendor Payments Card -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] transition-all duration-500 transform hover:scale-105 hover:shadow-lg"
            :class="animate ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'" style="transition-delay: 200ms;">
            <div class="flex items-center justify-between">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-purple-600 shadow-lg">
                    <img width="48" height="48" src="https://img.icons8.com/fluency/48/receive-cash.png"
                        alt="receive-cash" />
                </div>
                <div class="flex items-center gap-1">
                    <span
                        class="flex items-center gap-1 rounded-full bg-warning-50 px-2 py-0.5 text-xs font-medium text-warning-600 dark:bg-warning-500/15 dark:text-warning-500">
                        +{{ number_format($currentData['vendor_payments'] > 0 ? $currentData['vendor_payments'] / 1000 : 0, 1) }}%
                    </span>
                </div>
            </div>
            <div class="mt-4">
                <h4 class="text-2xl font-bold text-gray-800 dark:text-white/90" data-target="{{ $currentData['vendor_payments'] }}" data-prefix="Rs.">
                    Rs.0
                </h4>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Vendor Payments
                </p>
            </div>
        </div>

        <!-- Damaged Plates Card -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] transition-all duration-500 transform hover:scale-105 hover:shadow-lg"
            :class="animate ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'" style="transition-delay: 300ms;">
            <div class="flex items-center justify-between">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-red-600 shadow-lg">
                    <img width="64" height="64"
                        src="https://img.icons8.com/external-flaticons-flat-flat-icons/64/external-damage-public-relations-agency-flaticons-flat-flat-icons.png"
                        alt="external-damage-public-relations-agency-flaticons-flat-flat-icons" />
                </div>
                <div class="flex items-center gap-1">
                    <span
                        class="flex items-center gap-1 rounded-full bg-error-50 px-2 py-0.5 text-xs font-medium text-error-600 dark:bg-error-500/15 dark:text-error-500">
                        {{ $currentData['damaged_plates'] > 0 ? number_format($currentData['damaged_plates'] / 100, 1) : 0 }}%
                    </span>
                </div>
            </div>
            <div class="mt-4">
                <h4 class="text-2xl font-bold text-gray-800 dark:text-white/90" data-target="{{ $currentData['damaged_plates'] }}">
                    0
                </h4>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Damaged Plates
                </p>
                        </div>
        </div>
    </div>



        <!-- Charts and Data Grid -->
    <div class="grid grid-cols-12 gap-4 md:gap-6 xl:gap-8 mb-6">
        <!-- Financial Performance Chart -->
        <div class="col-span-12 lg:col-span-6 xl:col-span-6">
            <div
                class="rounded-2xl border border-gray-200 bg-white px-5 pt-5 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6 sm:pt-6 min-h-[500px]">
            <div class="flex flex-wrap items-start justify-between gap-5 mb-6">
                <div class="flex items-center gap-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-green-600 shadow-lg">
                            <img width="48" height="48"
                                src="https://img.icons8.com/fluency/48/stocks-growth--v1.png" alt="stocks-growth--v1" />
                    </div>
                    <div>
                        <h3 class="mb-1 text-lg font-semibold text-gray-800 dark:text-white/90">
                            Financial Performance
                        </h3>
                        <span class="block text-sm text-gray-500 dark:text-gray-400">
                            Revenue and payment trends over time
                        </span>
                    </div>
                </div>

            </div>
                <div id="financialChart" class="h-80 w-full mt-6 mb-8"></div>



        </div>
    </div>

        <!-- Job Status Overview -->
        <div class="col-span-12 lg:col-span-6 xl:col-span-6">
            <div
                class="rounded-2xl border border-gray-200 bg-white px-5 pt-5 pb-6 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6 sm:pt-6 min-h-[500px]">
                <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-600 shadow-lg">
                            <img width="48" height="48"
                                src="https://img.icons8.com/fluency/48/spinner-frame-5.png" alt="spinner-frame-5" />
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                        Job Status Overview
                    </h3>
                </div>

            </div>


                        <!-- Job Status Chart -->
                <div>


                    <div class="flex flex-col items-center gap-1" <!-- Donut Chart -->
                    <div class="relative">
                            <div id="jobStatusChart" class="w-60 h-60"></div>
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <div class="text-center">
                                <div class="text-xs text-gray-500 dark:text-gray-400">Total Jobs</div>
                                    <div class="text-lg font-bold text-gray-800 dark:text-white" data-target="{{ array_sum($jobStatusData) }}">
                                        0</div>
                            </div>
                        </div>
                    </div>

                                        <!-- Legend -->
                    <div class="flex-1">
                        @php
                            $totalJobs = array_sum($jobStatusData);
                                $pendingPercent =
                                    $totalJobs > 0 ? round(($jobStatusData['pending'] / $totalJobs) * 100, 1) : 0;
                                $designingDtpPercent =
                                    $totalJobs > 0 ? round(($jobStatusData['designing_dtp'] / $totalJobs) * 100, 1) : 0;
                                $exposingCtpPercent =
                                    $totalJobs > 0 ? round(($jobStatusData['exposing_ctp'] / $totalJobs) * 100, 1) : 0;
                                $ctpDispatchPercent =
                                    $totalJobs > 0 ? round(($jobStatusData['ctp_dispatch'] / $totalJobs) * 100, 1) : 0;
                                $billingPercent =
                                    $totalJobs > 0 ? round(($jobStatusData['billing'] / $totalJobs) * 100, 1) : 0;
                                $invoicingPercent =
                                    $totalJobs > 0 ? round(($jobStatusData['invoicing'] / $totalJobs) * 100, 1) : 0;

                                $cancelledPercent =
                                    $totalJobs > 0 ? round(($jobStatusData['cancelled'] / $totalJobs) * 100, 1) : 0;
                        @endphp

                            <div class="grid grid-cols-3 gap-x-6 gap-y-2 mb-4"
                                style="display: grid; grid-template-columns: repeat(3, 1fr);">
                            <!-- Column 1 -->
                                <div class="space-y-3">
                                <div class="flex items-center gap-3">
                                        <div class="w-4 h-4 rounded-full shadow-sm border-2 border-white job-status-dot job-status-pending"></div>
                                    <div class="flex-1">
                                            <div class="text-sm font-medium text-gray-800 dark:text-white">Pending
                                    </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                <span data-target="{{ $pendingPercent }}" data-suffix="%">0%</span> • <span data-target="{{ $jobStatusData['pending'] }}" data-suffix=" jobs">0 jobs</span></div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                        <div class="w-4 h-4 rounded-full shadow-sm border-2 border-white job-status-dot job-status-designing-dtp"></div>
                                    <div class="flex-1">
                                            <div class="text-sm font-medium text-gray-800 dark:text-white">
                                                Designing-DTP</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                <span data-target="{{ $designingDtpPercent }}" data-suffix="%">0%</span> • <span data-target="{{ $jobStatusData['designing_dtp'] }}" data-suffix=" jobs">0 jobs</span></div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                        <div class="w-4 h-4 rounded-full shadow-sm border-2 border-white job-status-dot job-status-exposing-ctp"></div>
                                    <div class="flex-1">
                                            <div class="text-sm font-medium text-gray-800 dark:text-white">Exposing-CTP
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                <span data-target="{{ $exposingCtpPercent }}" data-suffix="%">0%</span> • <span data-target="{{ $jobStatusData['exposing_ctp'] }}" data-suffix=" jobs">0 jobs</span>
                                    </div>
                                </div>
                            </div>

                                </div>
                            <!-- Column 2 -->
                                <div class="space-y-3">
                                <div class="flex items-center gap-3">
                                        <div class="w-4 h-4 rounded-full shadow-sm border-2 border-white job-status-dot job-status-ctp-dispatch"></div>
                                    <div class="flex-1">
                                            <div class="text-sm font-medium text-gray-800 dark:text-white">CTP/Dispatch
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                <span data-target="{{ $ctpDispatchPercent }}" data-suffix="%">0%</span> • <span data-target="{{ $jobStatusData['ctp_dispatch'] }}" data-suffix=" jobs">0 jobs</span>
                                            </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                        <div class="w-4 h-4 rounded-full shadow-sm border-2 border-white job-status-dot job-status-billing"></div>
                                    <div class="flex-1">
                                            <div class="text-sm font-medium text-gray-800 dark:text-white">Billing
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                <span data-target="{{ $billingPercent }}" data-suffix="%">0%</span> • <span data-target="{{ $jobStatusData['billing'] }}" data-suffix=" jobs">0 jobs</span></div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                        <div class="w-4 h-4 rounded-full shadow-sm border-2 border-white job-status-dot job-status-invoicing"></div>
                                    <div class="flex-1">
                                            <div class="text-sm font-medium text-gray-800 dark:text-white">Invoicing
                                    </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                <span data-target="{{ $invoicingPercent }}" data-suffix="%">0%</span> • <span data-target="{{ $jobStatusData['invoicing'] }}" data-suffix=" jobs">0 jobs</span>
                                </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Column 3 -->
                                <div class="space-y-3">


                                <div class="flex items-center gap-3">
                                        <div class="w-4 h-4 rounded-full shadow-sm border-2 border-white job-status-dot job-status-cancelled"></div>
                                    <div class="flex-1">
                                            <div class="text-sm font-medium text-gray-800 dark:text-white">Cancelled
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                <span data-target="{{ $cancelledPercent }}" data-suffix="%">0%</span> • <span data-target="{{ $jobStatusData['cancelled'] }}" data-suffix=" jobs">0 jobs</span>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



        <!-- Plate Consumption Analytics Chart -->
        <div class="col-span-12 lg:col-span-6 xl:col-span-6">
            <div
                class="rounded-2xl border border-gray-200 bg-white px-5 pt-5 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6 sm:pt-6 min-h-[500px]">
                <div class="flex flex-wrap items-start justify-between gap-5 mb-6">
                    <div class="flex items-center gap-3 mb-6 mt-2">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-orange-600 shadow-lg">
                            <img width="48" height="48" src="https://img.icons8.com/fluency/48/analytics.png"
                                alt="analytics" />
                        </div>
                        <div>
                            <h3 class="mb-1 text-lg font-semibold text-gray-800 dark:text-white/90">
                                Plate Consumption Overview
                            </h3>
                            <span class="block text-sm text-gray-500 dark:text-gray-400 ">
                                @if($activePeriod === 'daily')
                                    Today's plate consumption by item
                                @elseif($activePeriod === 'weekly')
                                    This week's plate consumption by item
                                @elseif($activePeriod === 'monthly')
                                    This month's plate consumption by item
                                @elseif($activePeriod === 'yearly')
                                    This year's plate consumption by item
                                @else
                                    Current plate consumption by item
                                @endif
                            </span>
                        </div>
                    </div>


                </div>

                <div id="chartFour" class="h-80 w-full"></div>


            </div>
        </div>

        <!-- Plate Consumption Table -->
        <div class="col-span-12 lg:col-span-6 xl:col-span-6">
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center justify-between border-b border-gray-200 p-6 dark:border-gray-800">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-orange-600 shadow-lg">
                            <img width="48" height="48"
                                src="https://img.icons8.com/fluency/48/financial-growth-analysis.png"
                                alt="financial-growth-analysis" />
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                            Plate Consumption
                        </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 ">
                            Yesterday's plate usage statistics
                        </p>
                    </div>
                </div>
                    <div
                        class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-orange-400 to-orange-600">

                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-800">
                            <th class="px-6 py-4 text-left text-sm font-medium text-gray-500 dark:text-gray-400">
                                Plate Name
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-medium text-gray-500 dark:text-gray-400">
                                Quantity
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                            @forelse($yesterdayPlateConsumption as $plate)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                    {{ $plate['item_name'] }}
                                </td>
                                    <td
                                        class="px-6 py-4 text-right text-sm font-medium text-gray-700 dark:text-gray-300">
                                        <span data-target="{{ $plate['quantity'] }}">0</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2"
                                    class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg class="h-8 w-8 text-gray-300" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span>No plate consumption data for yesterday</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                        <tfoot class="">
                            <tr class="  border-t border-gray-200 dark:border-gray-800">
                                <td class="px-6 py-4 text-sm font-semibold text-gray-800 dark:text-white">
                                    Total
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-semibold text-gray-800 dark:text-white">
                                    <span data-target="{{ array_sum(array_column($yesterdayPlateConsumption, 'quantity')) }}">0</span>
                                </td>
                            </tr>
                        </tfoot>
                </table>
            </div>
        </div>
    </div>

                                        <!-- Monthly Target Section -->
        <div class="col-span-12 lg:col-span-6 xl:col-span-6">
                    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-orange-600 shadow-lg">
                            <img width="45" height="45" src="https://img.icons8.com/fluency/48/goal--v1.png"
                                alt="goal--v1" />
                    </div>
                    <div>
                            <h3 class="mb-1 text-lg font-semibold text-gray-800 dark:text-white/90">
                                Monthly Target
                        </h3>
                            <span class="block text-sm text-gray-500 dark:text-gray-400">
                                @if(isset($currentMonthTarget) && $currentMonthTarget)
                                    {{ $currentMonthTarget->month }} {{ $currentMonthTarget->year }} Target
                                @else
                                    No target set for {{ now()->format('F Y') }}
                                @endif
                            </span>
                    </div>
                </div>


                </div>

                <!-- Progress Section -->
                <div class="text-center mb-6">
                    <div class="relative inline-block">
                        <div id="monthlyTargetChart" class="w-64 h-64"></div>
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <div class="text-3xl font-bold text-gray-800 dark:text-white" data-target="{{ $targetAchievementPercentage ?? 0 }}" data-suffix="%">
                                0%
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                @if(isset($currentMonthTarget) && $currentMonthTarget)
                                    @if(($targetAchievementPercentage ?? 0) >= 100)
                                        <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full dark:bg-green-900/20 dark:text-green-400">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                                            Target Achieved!
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium text-blue-700 bg-blue-100 rounded-full dark:bg-blue-900/20 dark:text-blue-400">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                            </svg>
                                            In Progress
                                        </span>
                                    @endif
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium text-gray-700 bg-gray-100 rounded-full dark:bg-gray-900/20 dark:text-gray-400">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        No Target Set
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 space-y-1">
                        @if(isset($currentMonthTarget) && $currentMonthTarget)
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                @if(($targetAchievementPercentage ?? 0) >= 100)
                                    Congratulations! You've achieved your monthly target.
                                @else
                                    You need Rs.<span data-target="{{ $currentMonthTarget->target_amount - ($currentMonthRevenue ?? 0) }}" data-prefix="Rs.">0</span> more to reach your target.
                                @endif
                        </p>
                        <p class="text-sm font-medium text-gray-800 dark:text-white">
                                @if(($targetAchievementPercentage ?? 0) >= 100)
                                    Excellent performance! 🎉
                                @else
                                    Keep pushing to reach your goal! 💪
                                @endif
                            </p>
                        @else
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                No monthly target has been set for {{ now()->format('F Y') }}.
                            </p>
                            <p class="text-sm font-medium text-gray-800 dark:text-white">
                                Set a target in Settings → Monthly Targets
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Key Metrics -->
                <div class="flex items-center justify-between border-t border-gray-200 dark:border-gray-700 pt-4">
                    <div class="text-center flex-1">
                        <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Target</div>
                        <div class="text-lg font-bold text-gray-800 dark:text-white" data-target="{{ isset($currentMonthTarget) && $currentMonthTarget ? $currentMonthTarget->target_amount / 1000 : 0 }}" data-prefix="Rs." data-suffix="K">
                            Rs.0K
                        </div>

                    </div>

                    <div class="w-px h-12 bg-gray-200 dark:bg-gray-700"></div>

                    <div class="text-center flex-1">
                        <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Revenue</div>
                        <div class="text-lg font-bold text-gray-800 dark:text-white" data-target="{{ ($currentMonthRevenue ?? 0) / 1000 }}" data-prefix="Rs." data-suffix="K">
                            Rs.0K
                        </div>

                    </div>

                    <div class="w-px h-12 bg-gray-200 dark:bg-gray-700"></div>

                    <div class="text-center flex-1">
                        <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Achievement</div>
                        <div class="text-lg font-bold text-gray-800 dark:text-white" data-target="{{ isset($currentMonthTarget) && $currentMonthTarget ? ($targetAchievementPercentage ?? 0) : 0 }}" data-suffix="%">
                            0%
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Cancelled Items Overview -->
        <div class="col-span-12 lg:col-span-6 xl:col-span-6">
            <div class="rounded-2xl border border-gray-200 bg-white px-5 pt-5 pb-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="mb-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-rose-600 shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="100" height="100" viewBox="0 0 48 48">
                            <linearGradient id="wRKXFJsqHCxLE9yyOYHkza_fYgQxDaH069W_gr1" x1="9.858" x2="38.142" y1="9.858" y2="38.142" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#f44f5a"></stop><stop offset=".443" stop-color="#ee3d4a"></stop><stop offset="1" stop-color="#e52030"></stop></linearGradient><path fill="url(#wRKXFJsqHCxLE9yyOYHkza_fYgQxDaH069W_gr1)" d="M44,24c0,11.045-8.955,20-20,20S4,35.045,4,24S12.955,4,24,4S44,12.955,44,24z"></path><path d="M33.192,28.95L28.243,24l4.95-4.95c0.781-0.781,0.781-2.047,0-2.828l-1.414-1.414	c-0.781-0.781-2.047-0.781-2.828,0L24,19.757l-4.95-4.95c-0.781-0.781-2.047-0.781-2.828,0l-1.414,1.414	c-0.781,0.781-0.781,2.047,0,2.828l4.95,4.95l-4.95,4.95c-0.781,0.781-0.781,2.047,0,2.828l1.414,1.414	c0.781,0.781,2.047,0.781,2.828,0l4.95-4.95l4.95,4.95c0.781,0.781,2.047,0.781,2.828,0l1.414-1.414	C33.973,30.997,33.973,29.731,33.192,28.95z" opacity=".05"></path><path d="M32.839,29.303L27.536,24l5.303-5.303c0.586-0.586,0.586-1.536,0-2.121l-1.414-1.414	c-0.586-0.586-1.536-0.586-2.121,0L24,20.464l-5.303-5.303c-0.586-0.586-1.536-0.586-2.121,0l-1.414,1.414	c-0.586,0.586-0.586,1.536,0,2.121L20.464,24l-5.303,5.303c-0.586,0.586-0.586,1.536,0,2.121l1.414,1.414	c0.586,0.586,1.536,0.586,2.121,0L24,27.536l5.303,5.303c0.586,0.586,1.536,0.586,2.121,0l1.414-1.414	C33.425,30.839,33.425,29.889,32.839,29.303z" opacity=".07"></path><path fill="#fff" d="M31.071,15.515l1.414,1.414c0.391,0.391,0.391,1.024,0,1.414L18.343,32.485	c-0.391,0.391-1.024,0.391-1.414,0l-1.414-1.414c-0.391-0.391-0.391-1.024,0-1.414l14.142-14.142	C30.047,15.124,30.681,15.124,31.071,15.515z"></path><path fill="#fff" d="M32.485,31.071l-1.414,1.414c-0.391,0.391-1.024,0.391-1.414,0L15.515,18.343	c-0.391-0.391-0.391-1.024,0-1.414l1.414-1.414c0.391-0.391,1.024-0.391,1.414,0l14.142,14.142	C32.876,30.047,32.876,30.681,32.485,31.071z"></path>
                            </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                            Cancelled Items
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Overview of cancelled receipts and invoices
                        </p>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 md:gap-6">
                <!-- Cancelled Receipts Box -->
                <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-100 dark:bg-red-900/20">
                                <img width="48" height="48" src="https://img.icons8.com/fluency/48/cancel-order.png" alt="cancel-order"/>
                            </div>
                            <h3 class="text-sm font-semibold text-gray-800 dark:text-white/90">
                                Cancelled Receipts
                            </h3>
                        </div>
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-600">
                            <svg class="h-4 w-4 fill-white" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M6 18L18 6M6 6L18 18" fill="currentColor" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="text-3xl font-bold text-error-600 pl-2 dark:text-error-500">
                            {{ $currentData['cancelled_receipts'] }}
                        </div>

                    </div>
                </div>

                <!-- Cancelled Invoices Box -->
                <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-yellow-100 dark:bg-yellow-900/20">
                                <img width="48" height="48" src="https://img.icons8.com/fluency/48/cancel-subscription.png" alt="cancel-subscription"/>
                            </div>
                            <h3 class="text-sm font-semibold text-gray-800 dark:text-white/90">
                                Cancelled Invoices
                            </h3>
                        </div>
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-yellow-600">

                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="text-3xl font-bold text-warning-600 pl-2 dark:text-warning-500">
                            {{ $currentData['cancelled_invoices'] }}
                        </div>

                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart Scripts -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check if ApexCharts is available
        if (typeof ApexCharts === 'undefined') {
            return;
        }

                // Add CSS for blink effect on all colored dots and icons
        const blinkStyle = document.createElement('style');
        blinkStyle.textContent = `
            .dashboard-blink {
                animation: dashboardBlink 3s ease-in-out infinite;
            }

            @keyframes dashboardBlink {
                0%, 100% {
                    opacity: 1;
                    transform: scale(1);
                    filter: drop-shadow(0 0 5px currentColor);
                }
                50% {
                    opacity: 0.8;
                    transform: scale(1.1);
                    filter: drop-shadow(0 0 10px currentColor);
                }
            }

            .dashboard-blink:hover {
                animation: dashboardBlink 1.5s ease-in-out infinite;
                filter: drop-shadow(0 0 15px currentColor);
            }

            /* Job Status Dot Colors - More Specific Selectors */
            .job-status-dot.job-status-pending {
                background-color: #F97316 !important;
                border-color: #F97316!important;
            }
            .job-status-dot.job-status-designing-dtp {
                background-color: #a855f7 !important;
                border-color: #a855f7 !important;
            }
            .job-status-dot.job-status-exposing-ctp {
                background-color: #3b82f6 !important;
                border-color: #3b82f6 !important;
            }
            .job-status-dot.job-status-ctp-dispatch {
                background-color: #6366f1 !important;
                border-color: #6366f1 !important;
            }
            .job-status-dot.job-status-billing {
                background-color: #14b8a6 !important;
                border-color: #14b8a6 !important;
            }
            .job-status-dot.job-status-invoicing {
                background-color: #06b6d4 !important;
                border-color: #06b6d4 !important;
            }
            .job-status-dot.job-status-cancelled {
                background-color: #ef4444 !important;
                border-color: #ef4444 !important;
            }
        `;
        document.head.appendChild(blinkStyle);

                        // Function to apply blink effect to colored dots and badges only
        function applyDashboardBlinkEffect() {
            // Legend color dots (excluding refresh button)
            const legendDots = document.querySelectorAll('.bg-brand-500:not(button), .bg-warning-500');
            legendDots.forEach(dot => {
                dot.classList.add('dashboard-blink');
            });

            // Job Status Overview color dots
            const jobStatusDots = document.querySelectorAll('.job-status-dot');
            jobStatusDots.forEach(dot => {
                dot.classList.add('dashboard-blink');
            });

            // Percentage badges
            const percentageBadges = document.querySelectorAll('.bg-success-50, .bg-warning-50, .bg-error-50');
            percentageBadges.forEach(badge => {
                badge.classList.add('dashboard-blink');
            });
        }

        // Apply blink effect after page loads
        setTimeout(() => {
            applyDashboardBlinkEffect();
        }, 1000);


        // Chart Twenty One (Cancelled Receipts)
        const chartTwentyOneOptions = {
            series: [{{ $currentData['cancelled_receipts'] }}],
            chart: {
                type: 'radialBar',
                height: 64,
                width: 80,
                sparkline: {
                    enabled: true
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 5000,
                    animateGradually: {
                        enabled: true,
                        delay: 500
                    },
                    dynamicAnimation: {
                        enabled: true,
                        speed: 800
                    }
                }
            },
            plotOptions: {
                radialBar: {
                    startAngle: -135,
                    endAngle: 135,
                    hollow: {
                        margin: 0,
                        size: '70%'
                    },
                    track: {
                        background: '#E5E7EB',
                        strokeWidth: '97%',
                        margin: 0
                    },
                    dataLabels: {
                        name: {
                            show: false
                        },
                        value: {
                            offsetY: 5,
                            fontSize: '12px',
                            color: '#EF4444',
                            fontWeight: 600
                        }
                    }
                }
            },
            fill: {
                colors: ['#EF4444']
            }
        };

        const chartTwentyOne = new ApexCharts(document.querySelector("#chartTwentyOne"), chartTwentyOneOptions);
        chartTwentyOne.render();

        // Chart Twenty Two (Cancelled Invoices)
        const chartTwentyTwoOptions = {
            series: [{{ $currentData['cancelled_invoices'] }}],
            chart: {
                type: 'radialBar',
                height: 64,
                width: 80,
                sparkline: {
                    enabled: true
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 5000,
                    animateGradually: {
                        enabled: true,
                        delay: 500
                    },
                    dynamicAnimation: {
                        enabled: true,
                        speed: 800
                    }
                }
            },
            plotOptions: {
                radialBar: {
                    startAngle: -135,
                    endAngle: 135,
                    hollow: {
                        margin: 0,
                        size: '70%'
                    },
                    track: {
                        background: '#E5E7EB',
                        strokeWidth: '97%',
                        margin: 0
                    },
                    dataLabels: {
                        name: {
                            show: false
                        },
                        value: {
                            offsetY: 5,
                            fontSize: '12px',
                            color: '#F59E0B',
                            fontWeight: 600
                        }
                    }
                }
            },
            fill: {
                colors: ['#F59E0B']
            }
        };

        const chartTwentyTwo = new ApexCharts(document.querySelector("#chartTwentyTwo"), chartTwentyTwoOptions);
        chartTwentyTwo.render();



        // Monthly Target Progress Chart
        const monthlyTargetOptions = {
            series: [{{ $targetAchievementPercentage ?? 0 }}],
            chart: {
                type: 'radialBar',
                height: 256,
                width: 256,
                sparkline: {
                    enabled: false
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 5000,
                    animateGradually: {
                        enabled: true,
                        delay: 500
                    },
                    dynamicAnimation: {
                        enabled: true,
                        speed: 500
                    }
                }
            },
            plotOptions: {
                radialBar: {
                    startAngle: -135,
                    endAngle: 135,
                    hollow: {
                        margin: 0,
                        size: '70%'
                    },
                    track: {
                        background: '#E5E7EB',
                        strokeWidth: '97%',
                        margin: 0
                    },
                    dataLabels: {
                        name: {
                            show: false
                        },
                        value: {
                            show: false
                        }
                    }
                }
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'light',
                    type: 'horizontal',
                    shadeIntensity: 0.25,
                    gradientToColors: ['#86EFAC'],
                    inverseColors: false,
                    opacityFrom: 1,
                    opacityTo: 1,
                    stops: [0, 100]
                }
            },
            colors: ['#F97316'],
            stroke: {
                lineCap: 'round',
                width: 8
            },
            states: {
                hover: {
                    filter: {
                        type: 'darken',
                        value: 0.1
                    }
                },
                active: {
                    filter: {
                        type: 'darken',
                        value: 0.1
                    }
                }
            }
        };

        const monthlyTargetChart = new ApexCharts(document.querySelector("#monthlyTargetChart"),
            monthlyTargetOptions);

        // Add smooth progress animation
        setTimeout(() => {
        monthlyTargetChart.render();
        }, 600);

        // Job Status Chart
        const jobStatusOptions = {
            series: [{{ $jobStatusData['pending'] }}, {{ $jobStatusData['designing_dtp'] }},
                {{ $jobStatusData['exposing_ctp'] }}, {{ $jobStatusData['ctp_dispatch'] }},
                {{ $jobStatusData['billing'] }}, {{ $jobStatusData['invoicing'] }},
                {{ $jobStatusData['cancelled'] }}
            ],
            chart: {
                type: 'donut',
                height: 240,
                width: 240,
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 5000,
                    animateGradually: {
                        enabled: true,
                        delay: 500
                    },
                    dynamicAnimation: {
                        enabled: true,
                        speed: 800
                    }
                }
            },
            colors: ['#eab308', '#a855f7', '#3b82f6', '#6366f1', '#14b8a6', '#06b6d4', '#ef4444'],
            labels: ['Pending', 'Designing-DTP', 'Exposing-CTP', 'CTP/Dispatch', 'Billing', 'Invoicing',
                'Cancelled'
            ],
            plotOptions: {
                pie: {
                    donut: {
                        size: '65%',
                        labels: {
                            show: false
                        }
                    }
                }
            },
            dataLabels: {
                enabled: false
            },
            legend: {
                show: false
            },
            stroke: {
                width: 0
            }
        };

        const jobStatusChart = new ApexCharts(document.querySelector("#jobStatusChart"), jobStatusOptions);

        // Add smooth animation delay
        setTimeout(() => {
        jobStatusChart.render();
        }, 800);

        // Financial Performance Chart
        let financialChart = null;
        let financialChartElement = null;
        const financialChartOptions = {
            series: [{
                name: 'Revenue',
                data: []
            }, {
                name: 'Payments',
                data: []
            }],
            chart: {
                type: 'area',
                height: 350,
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                },
                fontFamily: 'Inter, sans-serif',
                background: 'transparent',
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 1000,
                    animateGradually: {
                        enabled: true,
                        delay: 500
                    },
                    dynamicAnimation: {
                        enabled: true,
                        speed: 500
                    }
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 4,
                colors: ['#8B5CF6', '#F97316']
            },
            colors: ['#8B5CF6', '#F97316'],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.1,
                    stops: [0, 100],
                    colorStops: [{
                            offset: 0,
                            color: '#8B5CF6',
                            opacity: 0.7
                        },
                        {
                            offset: 100,
                            color: '#8B5CF6',
                            opacity: 0.1
                        }
                    ]
                }
            },
            xaxis: {
                categories: [],
                labels: {
                    style: {
                        colors: '#64748B',
                        fontSize: '12px',
                        fontFamily: 'Inter, sans-serif',
                        fontWeight: 500
                    },
                    rotate: 0,
                    trim: false
                },
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                },
                crosshairs: {
                    show: true,
                    width: 1,
                    position: 'back',
                    opacity: 0.9,
                    stroke: {
                        color: '#6366F1',
                        width: 1,
                        dashArray: 5
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: '#64748B',
                        fontSize: '12px',
                        fontFamily: 'Inter, sans-serif',
                        fontWeight: 500
                    },
                    formatter: function(value) {
                        if (value >= 1000000) {
                            return 'Rs.' + (value / 1000000).toFixed(1) + 'M';
                        } else if (value >= 1000) {
                            return 'Rs.' + (value / 1000).toFixed(0) + 'K';
                        } else {
                            return 'Rs.' + value.toLocaleString();
                        }
                    }
                },
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                }
            },
            grid: {
                borderColor: '#F1F5F9',
                strokeDashArray: 8,
                xaxis: {
                    lines: {
                        show: false
                    }
                },
                yaxis: {
                    lines: {
                        show: true,
                        color: '#F1F5F9',
                        opacity: 0.8
                    }
                },
                padding: {
                    top: 0,
                    right: 0,
                    bottom: 0,
                    left: 0
                }
            },
            legend: {
                show: true,
                position: 'top',
                horizontalAlign: 'right',
                fontSize: '13px',
                fontFamily: 'Inter, sans-serif',
                fontWeight: 600,
                labels: {
                    colors: '#374151'
                },
                markers: {
                    width: 16,
                    height: 16,
                    radius: 8,
                    fillColors: ['#8B5CF6', '#F97316'],
                    strokeColors: ['#8B5CF6', '#F97316'],
                    strokeWidth: 2
                },
                itemMargin: {
                    horizontal: 20,
                    vertical: 8
                },
                onItemClick: {
                    toggleDataSeries: true
                },
                onItemHover: {
                    highlightDataSeries: true
                }
            },
                                    tooltip: {
                enabled: true,
                theme: 'light',
                style: {
                    fontSize: '13px',
                    fontFamily: 'Inter, sans-serif'
                },
                x: {
                    show: true,
                    format: 'dd MMM yyyy'
                },
                y: {
                    formatter: function(value, {
                        seriesIndex,
                        dataPointIndex,
                        w
                    }) {
                        const color = seriesIndex === 0 ? '#6366F1' : '#10B981';

                        return `
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full" style="background-color: ${color}"></div>
                                <span>Rs. ${value.toLocaleString()}</span>
                            </div>
                        `;
                    }
                },
                marker: {
                    show: true
                },
                shared: true,
                intersect: false,
                followCursor: true,
                // Remove automatic series names to prevent duplicates
                custom: function({
                    series,
                    seriesIndex,
                    dataPointIndex,
                    w
                }) {
                    const category = w.globals.categoryLabels[dataPointIndex];
                    const revenueValue = w.globals.series[0][dataPointIndex];
                    const paymentValue = w.globals.series[1][dataPointIndex];

                    return `
                        <div class="bg-white border border-gray-200 rounded-lg shadow-lg p-4 min-w-[200px]">
                            <div class="text-sm font-medium text-gray-500 mb-3">${category}</div>

                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div class="w-3 h-3 rounded-full bg-brand-500"></div>
                                        <span class="text-sm text-gray-700">Rs. ${revenueValue.toLocaleString()}</span>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div class="w-3 h-3 rounded-full bg-warning-500"></div>
                                        <span class="text-sm text-gray-700">Rs. ${paymentValue.toLocaleString()}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                }
            },
            states: {
                hover: {
                    filter: {
                        type: 'lighten',
                        value: 0.1
                    }
                },
                active: {
                    filter: {
                        type: 'darken',
                        value: 0.1
                    }
                }
            },
            responsive: [{
                breakpoint: 768,
                options: {
                    chart: {
                        height: 300
                    },
                    legend: {
                        position: 'bottom',
                        horizontalAlign: 'center'
                    }
                }
            }]
        };

        // Initialize Financial Chart
        financialChartElement = document.querySelector("#financialChart");
        if (financialChartElement) {
            // Ensure the chart container has proper dimensions
            if (financialChartElement.offsetWidth === 0 || financialChartElement.offsetHeight === 0) {
                setTimeout(() => {
                    initializeFinancialChart();
                }, 1000);
            } else {
                initializeFinancialChart();
            }
        }

        function initializeFinancialChart() {
            if (financialChart) {
                return;
            }

            financialChart = new ApexCharts(financialChartElement, financialChartOptions);
            financialChart.render();

            // Load initial data (monthly by default)
            setTimeout(() => {
                updateFinancialChart('monthly');
            }, 500);
        }

        // Function to update Financial Performance chart based on period
        function updateFinancialChart(period) {
            if (!financialChart) {
                return;
            }

            // Fetch real data from the server
            fetch(`/api/financial-data?period=${period}`)
                .then(response => response.json())
                .then(data => {
                    if (financialChart) {
                        financialChart.updateOptions({
                            series: [{
                                name: 'Revenue',
                                data: data.revenue
                            }, {
                                name: 'Payments',
                                data: data.payments
                            }],
                            xaxis: {
                                categories: data.categories
                            }
                        }, true, true); // true = animate, true = redraw paths
                    }
                })
                .catch(error => {
                    // Handle error silently - chart will remain unchanged
                });
        }





        // Listen for period changes from top buttons
        document.addEventListener('period-changed', function(event) {
            updateFinancialChart(event.detail.period);
        });

        // Listen for plate consumption updates
        document.addEventListener('plate-consumption-updated', function(event) {
            // Update the Plate Consumption chart with new data
            if (window.chartFour && event.detail.data) {
                // Force the chart to re-render with new data
                window.chartFour.destroy();

                // Re-create the chart with updated data
                setTimeout(() => {
                    const newChartData = event.detail.data.map(item => item.quantity);
                    const newChartCategories = event.detail.data.map(item => item.item_name);

                    const newChartFourOptions = {
                        ...chartFourOptions,
                        series: [{
                            name: 'Plate Consumption',
                            data: newChartData
                        }],
                        xaxis: {
                            ...chartFourOptions.xaxis,
                            categories: newChartCategories
                        }
                    };

                    window.chartFour = new ApexCharts(document.querySelector("#chartFour"), newChartFourOptions);
                    window.chartFour.render();
                }, 100);
            }
        });

        // Add event listeners to top period selector buttons
        document.addEventListener('DOMContentLoaded', function() {
            // Wait a bit for the page to fully load
            setTimeout(() => {
                // Ensure chart is initialized
                if (!financialChart && financialChartElement) {
                    financialChart = new ApexCharts(financialChartElement,
                        financialChartOptions);
                    financialChart.render();
                }

                // Listen for Livewire events when period buttons are clicked
                if (typeof Livewire !== 'undefined') {
                    Livewire.on('period-changed', function(period) {
                        updateFinancialChart(period);
                    });
                }

                // Also listen for direct button clicks as fallback
                const dailyBtn = document.querySelector('button[wire\\:click="loadDailyData"]');
                const weeklyBtn = document.querySelector(
                    'button[wire\\:click="loadWeeklyData"]');
                const monthlyBtn = document.querySelector(
                    'button[wire\\:click="loadMonthlyData"]');
                const yearlyBtn = document.querySelector(
                    'button[wire\\:click="loadYearlyData"]');

                if (dailyBtn) {
                    dailyBtn.addEventListener('click', function() {
                        updateFinancialChart('daily');
                    });
                }
                if (weeklyBtn) {
                    weeklyBtn.addEventListener('click', function() {
                        updateFinancialChart('weekly');
                    });
                }
                if (monthlyBtn) {
                    monthlyBtn.addEventListener('click', function() {
                        updateFinancialChart('monthly');
                    });
                }
                if (yearlyBtn) {
                    yearlyBtn.addEventListener('click', function() {
                        updateFinancialChart('yearly');
                    });
                }
            }, 1000);
        });

        // Chart Four (Plate Consumption Analytics)
        const chartFourOptions = {
            series: [{
                name: 'Plate Consumption',
                data: @json(array_column($plateConsumption, 'quantity'))
            }],
            chart: {
                type: 'bar',
                height: 320,
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                },
                fontFamily: 'Inter, sans-serif',
                background: 'transparent',
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 5000,
                    animateGradually: {
                        enabled: true,
                        delay: 500
                    },
                    dynamicAnimation: {
                        enabled: true,
                        speed: 800
                    }
                }
            },
            colors: ['#0ea5e9', '#10b981', '#f59e0b', '#ef4444', '#6366f1', '#a855f7', '#22c55e', '#eab308', '#06b6d4', '#f97316'],
            plotOptions: {
                bar: {
                    borderRadius: 2,
                    columnWidth: '20%',
                    distributed: true,
                    dataLabels: {
                        position: 'top'
                    }
                }
            },
            dataLabels: {
                enabled: false
                },
            stroke: {
                width: 0
            },
            xaxis: {
                categories: @json(array_column($plateConsumption, 'item_name')),
                labels: {
                    style: {
                        colors: '#64748B',
                        fontSize: '8px',
                        fontFamily: 'Inter, sans-serif',
                        fontWeight: 500
                    },
                    rotate: -90,
                    rotateAlways: true,
                    maxHeight: 120
                },
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                }
            },
            yaxis: {
                title: {
                    text: 'Quantity Consumed',
                    style: {
                        color: '#64748B',
                        fontSize: '14px',
                        fontFamily: 'Inter, sans-serif',
                        fontWeight: 600
                    }
                },
                labels: {
                    style: {
                        colors: '#64748B',
                        fontSize: '12px',
                        fontFamily: 'Inter, sans-serif',
                        fontWeight: 500
                    },
                    formatter: function(value) {
                        return value.toLocaleString();
                    }
                },
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                }
            },
            grid: {
                borderColor: '#F1F5F9',
                strokeDashArray: 8,
                xaxis: {
                    lines: {
                        show: false
                    }
                },
                yaxis: {
                    lines: {
                        show: true,
                        color: '#F1F5F9',
                        opacity: 0.8
                    }
                }
            },
            tooltip: {
                enabled: true,
                theme: 'light',
                style: {
                    fontSize: '13px',
                    fontFamily: 'Inter, sans-serif'
                },
                x: {
                    formatter: function(value) {
                        return 'Plate: ' + value;
                    }
                },
                y: {
                    formatter: function(value) {
                        return value + ' units consumed';
                    }
                }
            },
            states: {
                hover: {
                    filter: {
                        type: 'lighten',
                        value: 0.1
                    }
                },
                active: {
                    filter: {
                        type: 'darken',
                        value: 0.1
                    }
                }
            }
        };

        // Only render chart if there's real data
        if (@json(!empty($plateConsumption))) {
            window.chartFour = new ApexCharts(document.querySelector("#chartFour"), chartFourOptions);
            window.chartFour.render();
        } else {
            // Show message when no data is available
            document.querySelector("#chartFour").innerHTML = `
                <div class="h-full w-full flex items-center justify-center">
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No Data Available</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">No plate consumption data found.</p>
                    </div>
                </div>
            `;
        }

        // Function to update Plate Consumption chart based on time period
        function updatePlateConsumptionChart(period) {

            // Update button states
            const buttons = document.querySelectorAll('[onclick^="updatePlateConsumptionChart"]');
            buttons.forEach(btn => {
                btn.classList.remove('bg-white', 'text-gray-900', 'shadow-sm', 'dark:bg-gray-800',
                    'dark:text-white');
                btn.classList.add('text-gray-500', 'hover:text-gray-700', 'dark:text-gray-400',
                    'dark:hover:text-gray-300');
            });

            // Highlight selected button
            const selectedBtn = document.querySelector(`[onclick="updatePlateConsumptionChart('${period}')"]`);
            if (selectedBtn) {
                selectedBtn.classList.remove('text-gray-500', 'hover:text-gray-700', 'dark:text-gray-400',
                    'dark:hover:text-gray-300');
                selectedBtn.classList.add('bg-white', 'text-gray-900', 'shadow-sm', 'dark:bg-gray-800',
                    'dark:text-white');
            }

            // Get data for the selected period
            let chartData = getPlateConsumptionData(period);

            // Update chart if it exists
            if (window.chartFour) {
                window.chartFour.updateOptions({
                    series: [{
                        name: 'Plate Consumption',
                        data: chartData.data
                    }],
                    xaxis: {
                        categories: chartData.categories
                    }
                }, true, true);
            }
        }

        // Get plate consumption data for different periods
        function getPlateConsumptionData(period) {
            switch (period) {
                case 'daily':
                case 'weekly':
                case 'yearly':
                    // For now, only monthly data is available
                    return {
                        data: @json(array_column($plateConsumption, 'quantity')),
                            categories: @json(array_column($plateConsumption, 'item_name'))
                    };
                case 'monthly':
                default:
                    return {
                        data: @json(array_column($plateConsumption, 'quantity')),
                            categories: @json(array_column($plateConsumption, 'item_name'))
                    };
            }
        }

        // Counter Animation Function
        function animateCounter(element, target, prefix = '', suffix = '') {
            const duration = 2000; // 2 seconds
            const start = 0;
            const increment = target / (duration / 16); // 60fps
            let current = start;

            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }

                const formattedValue = Math.floor(current).toLocaleString();
                element.textContent = prefix + formattedValue + suffix;
            }, 16);
        }

        // Initialize counter animations when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Wait for charts to load first
            setTimeout(() => {
                // Animate all counter elements
                const counterElements = document.querySelectorAll('[data-target]');
                counterElements.forEach(element => {
                    const target = parseInt(element.getAttribute('data-target'));
                    const prefix = element.getAttribute('data-prefix') || '';
                    const suffix = element.getAttribute('data-suffix') || '';

                    if (target > 0) {
                        animateCounter(element, target, prefix, suffix);
                    }
                });
            }, 1500); // Wait 1.5 seconds for charts to load
        });

        // Function to re-animate counters when data changes (for period switching)
        function reAnimateCounters() {
            const counterElements = document.querySelectorAll('[data-target]');
            counterElements.forEach(element => {
                const target = parseInt(element.getAttribute('data-target'));
                const prefix = element.getAttribute('data-prefix') || '';
                const suffix = element.getAttribute('data-suffix') || '';

                if (target > 0) {
                    animateCounter(element, target, prefix, suffix);
                }
            });
        }

        // Listen for period changes to re-animate counters
        document.addEventListener('period-changed', function(event) {
            setTimeout(() => {
                reAnimateCounters();
            }, 500);
        });

    });
</script>
