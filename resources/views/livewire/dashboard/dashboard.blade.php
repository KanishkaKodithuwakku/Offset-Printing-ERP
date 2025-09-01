 <div class="mx-auto max-w-(--breakpoint-2xl) p-4 md:p-6">

     @if ($this->role === 'admin')
         <div class="space-y-6">
             <!-- Metrics Start -->
             <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                 <div class="flex flex-col justify-between gap-4 mb-8 sm:flex-row sm:items-center">
                     <div>
                         <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Overview</h3>
                     </div>
                     <div class="flex gap-x-3.5">
                         <div
                             class="inline-flex w-full items-center gap-0.5 rounded-lg bg-gray-100 p-0.5 dark:bg-gray-900">
                             <button wire:click="loadDailyData"
                                 class="w-full px-3 py-2 font-medium rounded-md text-theme-sm hover:text-gray-900 dark:hover:text-white {{ $activePeriod === 'daily' ? 'shadow-theme-xs text-gray-900 dark:text-white bg-white dark:bg-gray-800' : 'text-gray-500 dark:text-gray-400' }}">
                                 Daily
                             </button>
                             <button wire:click="loadWeeklyData"
                                 class="w-full px-3 py-2 font-medium rounded-md text-theme-sm hover:text-gray-900 dark:hover:text-white {{ $activePeriod === 'weekly' ? 'shadow-theme-xs text-gray-900 dark:text-white bg-white dark:bg-gray-800' : 'text-gray-500 dark:text-gray-400' }}">
                                 Weekly
                             </button>
                             <button wire:click="loadMonthlyData"
                                 class="w-full px-3 py-2 font-medium rounded-md text-theme-sm hover:text-gray-900 dark:hover:text-white {{ $activePeriod === 'monthly' ? 'shadow-theme-xs text-gray-900 dark:text-white bg-white dark:bg-gray-800' : 'text-gray-500 dark:text-gray-400' }}">
                                 Monthly
                             </button>
                             <button wire:click="loadYearlyData"
                                 class="w-full px-3 py-2 font-medium rounded-md text-theme-sm hover:text-gray-900 dark:hover:text-white {{ $activePeriod === 'yearly' ? 'shadow-theme-xs text-gray-900 dark:text-white bg-white dark:bg-gray-800' : 'text-gray-500 dark:text-gray-400' }}">
                                 Yearly
                             </button>
                         </div>
                         <div>
                             <button
                                 class="text-theme-sm shadow-theme-xs inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">
                                 <svg class="stroke-current fill-white dark:fill-gray-800" width="20" height="20"
                                     viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                     <path d="M2.29004 5.90393H17.7067" stroke="" stroke-width="1.5"
                                         stroke-linecap="round" stroke-linejoin="round" />
                                     <path d="M17.7075 14.0961H2.29085" stroke="" stroke-width="1.5"
                                         stroke-linecap="round" stroke-linejoin="round" />
                                     <path
                                         d="M12.0826 3.33331C13.5024 3.33331 14.6534 4.48431 14.6534 5.90414C14.6534 7.32398 13.5024 8.47498 12.0826 8.47498C10.6627 8.47498 9.51172 7.32398 9.51172 5.90415C9.51172 4.48432 10.6627 3.33331 12.0826 3.33331Z"
                                         fill="" stroke="" stroke-width="1.5" />
                                     <path
                                         d="M7.91745 11.525C6.49762 11.525 5.34662 12.676 5.34662 14.0959C5.34661 15.5157 6.49762 16.6667 7.91745 16.6667C9.33728 16.6667 10.4883 15.5157 10.4883 14.0959C10.4883 12.676 9.33728 11.525 7.91745 11.525Z"
                                         fill="" stroke="" stroke-width="1.5" />
                                 </svg>
                                 <span class="hidden sm:block">Filter</span>
                             </button>
                         </div>
                     </div>
                 </div>

                 <div
                     class="grid bg-white border border-gray-200 rounded-2xl sm:grid-cols-2 xl:grid-cols-4 dark:border-gray-800 dark:bg-gray-900">
                     <div class="px-6 py-5 border-b border-gray-200 sm:border-r xl:border-b-0 dark:border-gray-800">
                         <span class="text-sm text-gray-500 dark:text-gray-400">Total Revenue</span>
                         <div class="flex items-end gap-3 mt-2">
                             {{-- <h4 class="font-bold text-s">Rs {{ number_format($currentData['revenue'], 2) }}</h4> --}}
                             <div>
                                 <span
                                     class="bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500 flex items-center gap-1 rounded-full py-2 pr-2.5 pl-2 text-sm font-medium">
                                     <h4 class="font-bold text-s">Rs {{ number_format($currentData['revenue'], 2) }}
                                     </h4>
                                 </span>
                             </div>
                         </div>
                     </div>
                     <div class="px-6 py-5 border-b border-gray-200 xl:border-r xl:border-b-0 dark:border-gray-800">
                         <span class="text-sm text-gray-500 dark:text-gray-400">Payment Received</span>
                         <div class="flex items-end gap-3 mt-2">

                             <div>
                                 <span
                                     class="bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500 flex items-center gap-1 rounded-full p-2 pr-2.5 pl-2 text-sm font-medium">
                                     <h4 class="font-bold ">
                                         Rs {{ number_format($currentData['payments'], 2) }}
                                     </h4>
                                 </span>
                             </div>
                         </div>
                     </div>
                     <div class="px-6 py-5 border-b border-gray-200 sm:border-r sm:border-b-0 dark:border-gray-800">
                         <div>
                             <span class="text-sm text-gray-500 dark:text-gray-400">Vendor Payments</span>
                             <div class="flex items-end gap-3 mt-2">

                                 <div>
                                     <span
                                         class="bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500 flex items-center gap-1 rounded-full p-2 pr-2.5 pl-2 text-sm font-medium">
                                         <h4 class="font-bold">
                                             Rs {{ number_format($currentData['vendor_payments'], 2) }}
                                         </h4>
                                     </span>
                                 </div>
                             </div>
                         </div>
                     </div>
                     <div class="px-6 py-5">
                         <span class="text-sm text-gray-500 dark:text-gray-400">Damage Plates</span>
                         <div class="flex items-end gap-3 mt-2">

                             <div>
                                 <span
                                     class="bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500 flex items-center gap-1 rounded-full p-2 pr-2.5 pl-2 text-sm font-medium">
                                     <h4 class="font-bold ">
                                         {{ number_format($currentData['damaged_plates']) }}
                                     </h4>
                                 </span>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
             {{-- =====================================end over view =========================================================== --}}
             <!-- Metrics End -->
             <div class="gap-5 space-y-5 sm:gap-6 sm:space-y-6 xl:grid xl:grid-cols-12 xl:space-y-0">
                
                 <div class="xl:col-span-7 2xl:col-span-8">
                     <div class="space-y-5 sm:space-y-6">

                         {{-- =============================================Plate Consumption Table= ============================ --}}

                         <div
                             class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                             <div class="px-6 py-4">
                                 <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                                     Plate Consumption {{ Carbon\Carbon::yesterday()->format('d/m/Y') }}
                                 </h3>
                             </div>
                             <div class="overflow-x-auto custom-scrollbar">
                                 <table class="min-w-full">
                                     <thead>
                                         <tr class="bg-gray-50 dark:bg-gray-900">
                                             <th
                                                 class="px-6 py-4 text-sm font-medium text-left text-gray-500 whitespace-nowrap dark:text-gray-400">
                                                 Plate Descriptions
                                             </th>
                                             <th
                                                 class="px-6 py-4 text-sm font-medium text-right text-gray-500 whitespace-nowrap dark:text-gray-400">
                                                 Quantity
                                             </th>
                                         </tr>
                                     </thead>
                                     <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                                         @forelse($plateConsumption as $plate)
                                             <tr>
                                                 <td
                                                     class="px-6 py-4 text-sm text-left text-gray-700 whitespace-nowrap dark:text-gray-400">
                                                     {{ $plate['description'] }}
                                                 </td>
                                                 <td
                                                     class="px-6 py-4 text-sm text-right text-gray-700 whitespace-nowrap dark:text-gray-400">
                                                     {{ $plate['quantity'] }}
                                                 </td>
                                             </tr>
                                         @empty
                                             <tr>
                                                 <td colspan="2"
                                                     class="px-6 py-4 text-sm text-center text-gray-500 dark:text-gray-400">
                                                     No plate consumption data for yesterday
                                                 </td>
                                             </tr>
                                         @endforelse
                                     </tbody>
                                 </table>
                             </div>
                         </div>
                         <!-- Chart -->
                         <div class="grid gap-5 sm:gap-6 lg:grid-cols-2">
                             <div
                                 class="overflow-hidden rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                                 <div class="flex justify-between mb-6">
                                     <div>
                                         <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                                             Cancelled Receipts
                                         </h3>
                                         <p class="mt-1 text-gray-500 text-theme-sm dark:text-gray-400">

                                         </p>
                                     </div>
                                     {{-- <div x-data="{ openDropDown: false }" class="relative h-fit">
                                     <button @click="openDropDown = !openDropDown"
                                         :class="openDropDown ? 'text-gray-700 dark:text-white' :
                                             'text-gray-400 hover:text-gray-700 dark:hover:text-white'">
                                         <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24"
                                             fill="none" xmlns="http://www.w3.org/2000/svg">
                                             <path fill-rule="evenodd" clip-rule="evenodd"
                                                 d="M10.2441 6C10.2441 5.0335 11.0276 4.25 11.9941 4.25H12.0041C12.9706 4.25 13.7541 5.0335 13.7541 6C13.7541 6.9665 12.9706 7.75 12.0041 7.75H11.9941C11.0276 7.75 10.2441 6.9665 10.2441 6ZM10.2441 18C10.2441 17.0335 11.0276 16.25 11.9941 16.25H12.0041C12.9706 16.25 13.7541 17.0335 13.7541 18C13.7541 18.9665 12.9706 19.75 12.0041 19.75H11.9941C11.0276 19.75 10.2441 18.9665 10.2441 18ZM11.9941 10.25C11.0276 10.25 10.2441 11.0335 10.2441 12C10.2441 12.9665 11.0276 13.75 11.9941 13.75H12.0041C12.9706 13.75 13.7541 12.9665 13.7541 12C13.7541 11.0335 12.9706 10.25 12.0041 10.25H11.9941Z"
                                                 fill="" />
                                         </svg>
                                     </button>
                                     <div x-show="openDropDown" @click.outside="openDropDown = false"
                                         class="absolute right-0 z-40 w-40 p-2 space-y-1 bg-white border border-gray-200 shadow-theme-lg dark:bg-gray-dark top-full rounded-2xl dark:border-gray-800">
                                         <button
                                             class="flex w-full px-3 py-2 font-medium text-left text-gray-500 rounded-lg text-theme-xs hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300">
                                             View More
                                         </button>
                                         <button
                                             class="flex w-full px-3 py-2 font-medium text-left text-gray-500 rounded-lg text-theme-xs hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300">
                                             Delete
                                         </button>
                                     </div>
                                 </div> --}}
                                 </div>
                                 <div class="flex justify-between">
                                    <div>
                                             <span
                                                 class="bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500 flex items-center gap-1 rounded-full p-2 pr-2.5 pl-2 text-sm font-medium">
                                                 <h4 class="font-bold ">
                                                     {{ $currentData['cancelled_receipts'] }}
                                                 </h4>
                                             </span>
                                         </div>
                                     <div class="max-w-full">
                                         <div id="chartTwentyOne" class="w-24 h-12"></div>
                                     </div>
                                 </div>
                             </div>
                             
                             <div
                                 class="overflow-hidden rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                                 <div class="flex justify-between mb-6">
                                     <div>
                                         <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                                             Cancelled Invoices
                                         </h3>
                                         <p class="mt-1 text-gray-500 text-theme-sm dark:text-gray-400">

                                         </p>
                                     </div>
                                     {{-- <div x-data="{ openDropDown: false }" class="relative h-fit">
                                     <button @click="openDropDown = !openDropDown"
                                         :class="openDropDown ? 'text-gray-700 dark:text-white' :
                                             'text-gray-400 hover:text-gray-700 dark:hover:text-white'">
                                         <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24"
                                             fill="none" xmlns="http://www.w3.org/2000/svg">
                                             <path fill-rule="evenodd" clip-rule="evenodd"
                                                 d="M10.2441 6C10.2441 5.0335 11.0276 4.25 11.9941 4.25H12.0041C12.9706 4.25 13.7541 5.0335 13.7541 6C13.7541 6.9665 12.9706 7.75 12.0041 7.75H11.9941C11.0276 7.75 10.2441 6.9665 10.2441 6ZM10.2441 18C10.2441 17.0335 11.0276 16.25 11.9941 16.25H12.0041C12.9706 16.25 13.7541 17.0335 13.7541 18C13.7541 18.9665 12.9706 19.75 12.0041 19.75H11.9941C11.0276 19.75 10.2441 18.9665 10.2441 18ZM11.9941 10.25C11.0276 10.25 10.2441 11.0335 10.2441 12C10.2441 12.9665 11.0276 13.75 11.9941 13.75H12.0041C12.9706 13.75 13.7541 12.9665 13.7541 12C13.7541 11.0335 12.9706 10.25 12.0041 10.25H11.9941Z"
                                                 fill="" />
                                         </svg>
                                     </button>
                                     <div x-show="openDropDown" @click.outside="openDropDown = false"
                                         class="absolute right-0 z-40 w-40 p-2 space-y-1 bg-white border border-gray-200 shadow-theme-lg dark:bg-gray-dark top-full rounded-2xl dark:border-gray-800">
                                         <button
                                             class="flex w-full px-3 py-2 font-medium text-left text-gray-500 rounded-lg text-theme-xs hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300">
                                             View More
                                         </button>
                                         <button
                                             class="flex w-full px-3 py-2 font-medium text-left text-gray-500 rounded-lg text-theme-xs hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300">
                                             Delete
                                         </button>
                                     </div>
                                 </div> --}}
                                 </div>
                                 <div class="flex justify-between">
                                     <div>
                                             <span
                                                 class="bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500 flex items-center gap-1 rounded-full p-2 pr-2.5 pl-2 text-sm font-medium">
                                                 <h4 class="font-bold ">
                                                      {{ $currentData['cancelled_invoices'] }}
                                                 </h4>
                                             </span>
                                         </div>
                                        <div class="max-w-full">
                                        
                                         <div id="chartTwentyTwo" class="w-24 h-12"></div>
                                     </div>
                                 </div>
                             </div>
                         </div>
                         {{-- =========================end second=================================== --}}
                         {{-- <div
                         class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                         <div class="flex justify-between mb-6">
                             <div>
                                 <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                                     Conversion Funnel
                                 </h3>
                             </div>
                             <div x-data="{ openDropDown: false }" class="relative h-fit">
                                 <button @click="openDropDown = !openDropDown"
                                     :class="openDropDown ? 'text-gray-700 dark:text-white' :
                                         'text-gray-400 hover:text-gray-700 dark:hover:text-white'">
                                     <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24"
                                         fill="none" xmlns="http://www.w3.org/2000/svg">
                                         <path fill-rule="evenodd" clip-rule="evenodd"
                                             d="M10.2441 6C10.2441 5.0335 11.0276 4.25 11.9941 4.25H12.0041C12.9706 4.25 13.7541 5.0335 13.7541 6C13.7541 6.9665 12.9706 7.75 12.0041 7.75H11.9941C11.0276 7.75 10.2441 6.9665 10.2441 6ZM10.2441 18C10.2441 17.0335 11.0276 16.25 11.9941 16.25H12.0041C12.9706 16.25 13.7541 17.0335 13.7541 18C13.7541 18.9665 12.9706 19.75 12.0041 19.75H11.9941C11.0276 19.75 10.2441 18.9665 10.2441 18ZM11.9941 10.25C11.0276 10.25 10.2441 11.0335 10.2441 12C10.2441 12.9665 11.0276 13.75 11.9941 13.75H12.0041C12.9706 13.75 13.7541 12.9665 13.7541 12C13.7541 11.0335 12.9706 10.25 12.0041 10.25H11.9941Z"
                                             fill="" />
                                     </svg>
                                 </button>
                                 <div x-show="openDropDown" @click.outside="openDropDown = false"
                                     class="absolute right-0 z-40 w-40 p-2 space-y-1 bg-white border border-gray-200 shadow-theme-lg dark:bg-gray-dark top-full rounded-2xl dark:border-gray-800">
                                     <button
                                         class="flex w-full px-3 py-2 font-medium text-left text-gray-500 rounded-lg text-theme-xs hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300">
                                         View More
                                     </button>
                                     <button
                                         class="flex w-full px-3 py-2 font-medium text-left text-gray-500 rounded-lg text-theme-xs hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300">
                                         Delete
                                     </button>
                                 </div>
                             </div>
                         </div>
                         <div class="max-w-full overflow-x-auto custom-scrollbar">
                             <div id="chartSeventeen" class="-ml-5 min-w-[700px] pl-2"></div>
                         </div>
                     </div> --}}
                         <!-- Funnel Chart End -->

                     </div>
                 </div>
                 {{-- ================================================================================================================== --}}
                 <!-- resources/views/livewire/dashboard/jobs-performance.blade.php -->
                 <div class="space-y-6 xl:col-span-5 2xl:col-span-4">
                     <!-- Jobs Performance -->
                     <div
                         class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                         <div class="flex justify-between mb-6">
                             <div>
                                 <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                                     Jobs Process Performance
                                 </h3>
                             </div>
                         </div>
                         <div x-data="{ selected: 'design' }">
                             <div
                                 class="flex w-full items-center gap-0.5 rounded-lg bg-gray-100 p-0.5 dark:bg-gray-900">
                                 <button @click="selected = 'design'"
                                     :class="selected === 'design' ?
                                         'shadow-theme-xs text-gray-900 dark:text-white bg-white dark:bg-gray-800' :
                                         'text-gray-500 dark:text-gray-400'"
                                     class="w-full px-3 py-2 font-medium rounded-md text-theme-sm hover:text-gray-900 dark:hover:text-white">
                                     Design
                                 </button>
                                 <button @click="selected = 'production'"
                                     :class="selected === 'production' ?
                                         'shadow-theme-xs text-gray-900 dark:text-white bg-white dark:bg-gray-800' :
                                         'text-gray-500 dark:text-gray-400'"
                                     class="w-full px-3 py-2 font-medium rounded-md text-theme-sm hover:text-gray-900 dark:hover:text-white">
                                     Production
                                 </button>
                                 <button @click="selected = 'account'"
                                     :class="selected === 'account' ?
                                         'shadow-theme-xs text-gray-900 dark:text-white bg-white dark:bg-gray-800' :
                                         'text-gray-500 dark:text-gray-400'"
                                     class="w-full px-3 py-2 font-medium rounded-md text-theme-sm hover:text-gray-900 dark:hover:text-white">
                                     Account
                                 </button>
                             </div>

                             <!-- Tab Panels -->
                             <div class="mt-4">
                                 <!-- Design Panel -->
                                 <div x-show="selected === 'design'" class="space-y-4">
                                     <div
                                         class="grid grid-cols-2 justify-between gap-10 divide-x divide-gray-100 rounded-xl border border-gray-100 bg-white py-4 dark:divide-gray-800 dark:border-gray-800 dark:bg-gray-800/[0.03]">
                                         <div class="px-5 ">
                                             <span
                                                 class="block text-sm text-gray-500 dark:text-gray-400">Pending</span>
                                             <div class="flex items-center gap-2 mt-1">
                                                 <span
                                                     class="inline-flex items-center justify-center px-2 py-1 text-green-600 rounded-md size-5">
                                                     <h4 class="text-xl font-semibold ">
                                                         {{ $pendingCount }}
                                                     </h4>
                                                 </span>
                                             </div>
                                         </div>
                                         <div class="px-5">
                                             <span
                                                 class="block text-sm text-gray-500 dark:text-gray-400">Designing</span>
                                             <div class="flex items-center gap-2 mt-1">
                                                 <span
                                                     class="inline-flex items-center justify-center px-2 py-1 text-green-600 rounded-md bg-green-50 size-5">
                                                     <h4 class="text-xl font-semibold">
                                                         {{ $designingCount }}
                                                     </h4>
                                                 </span>
                                             </div>
                                         </div>
                                     </div>

                                     <div class="px-5 py-4">
                                         <div
                                             class="flex flex-col items-center justify-center p-4 bg-success-50 rounded-xl dark:bg-green-800">
                                             <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Total
                                                 Design Jobs</span>
                                             <h3 class="mt-2 text-2xl font-bold text-green-800 dark:text-green-200">
                                                 {{ $designCount }}
                                             </h3>
                                         </div>
                                     </div>
                                 </div>

                                 <!-- Production Panel -->
                                 <div x-show="selected === 'production'" class="space-y-4">
                                     <div
                                         class="flex flex-wrap items-center justify-between gap-6 bg-white p-4 rounded-xl border border-gray-100 dark:bg-gray-800/[0.03] dark:border-gray-800">
                                         <div class="flex flex-col items-center">
                                             <span class="text-sm text-gray-500 dark:text-gray-400">Paused</span>
                                             <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                                                 {{ $pausedCount }}</h4>
                                         </div>
                                         <div class="flex flex-col items-center">
                                             <span class="text-sm text-gray-500 dark:text-gray-400">Exposing
                                                 (CTP)</span>
                                             <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                                                 {{ $printingCount }}</h4>
                                         </div>
                                         <div class="flex flex-col items-center">
                                             <span class="text-sm text-gray-500 dark:text-gray-400">CTP Dispatch</span>
                                             <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                                                 {{ $dispatchingCount }}</h4>
                                         </div>
                                     </div>

                                     <div class="px-5 py-4">
                                         <div
                                             class="flex flex-col items-center justify-center p-4 bg-success-50 rounded-xl dark:bg-green-800">
                                             <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Total
                                                 Production Jobs</span>
                                             <h3 class="mt-2 text-2xl font-bold text-green-800 dark:text-green-200">
                                                 {{ $productionCount }}
                                             </h3>
                                         </div>
                                     </div>
                                 </div>

                                 <!-- Account Panel -->
                                 <div x-show="selected === 'account'" class="space-y-4">
                                     <div
                                         class="grid grid-cols-2 bg-white divide-x divide-gray-200 rounded-lg shadow-sm dark:divide-gray-700 dark:bg-gray-800">
                                         <div class="p-4">
                                             <span
                                                 class="block text-xs font-medium text-gray-500 dark:text-gray-400">Billing</span>
                                             <div class="flex items-center gap-2 mt-1">
                                                 <span
                                                     class="inline-flex items-center justify-center w-5 h-5 text-blue-600 bg-blue-100 rounded-full dark:bg-blue-900/50 dark:text-blue-300">
                                                     <!-- Icon would go here -->
                                                 </span>
                                                 <h4 class="text-lg font-semibold text-gray-700 dark:text-white">
                                                     {{ $readyToInvoiceCount }}
                                                 </h4>
                                             </div>
                                         </div>
                                         <div class="p-4">
                                             <span
                                                 class="block text-xs font-medium text-gray-500 dark:text-gray-400">Invoicing</span>
                                             <div class="flex items-center gap-2 mt-1">
                                                 <span
                                                     class="inline-flex items-center justify-center w-5 h-5 text-green-600 bg-green-100 rounded-full dark:bg-green-900/50 dark:text-green-300">
                                                     <!-- Icon would go here -->
                                                 </span>
                                                 <h4 class="text-lg font-semibold text-gray-700 dark:text-white">
                                                     {{ $invoicingCount }}
                                                 </h4>
                                             </div>
                                         </div>
                                     </div>

                                     <div class="px-5 py-4">
                                         <div
                                             class="flex flex-col items-center justify-center p-4 bg-success-50 rounded-xl dark:bg-green-800">
                                             <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Total
                                                 Account Jobs</span>
                                             <h3 class="mt-2 text-2xl font-bold text-green-800 dark:text-green-200">
                                                 {{ $accountCount }}
                                             </h3>
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

 @endif

 </div>
 </main>
 <!-- ===== Main Content End ===== -->
 </div>
 <!-- ===== Content Area End ===== -->
 </div>
 <!-- ===== Page Wrapper End ===== -->
 <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
 <script defer src="bundle.js"></script>
 <script defer src="https://static.cloudflareinsights.com/beacon.min.js/vcd15cbe7772f49c399c6a5babf22c1241717689176015"
     integrity="sha512-ZpsOmlRQV6y907TI0dKBHq9Md29nnaEIPlkf84rnaERnq6zvWvPUqr2ft8M1aS28oN72PdrCzSjY4U6VaAw1EQ=="
     data-cf-beacon='{"rayId":"9559b1c26e70ce57","version":"2025.6.2","r":1,"token":"67f7a278e3374824ae6dd92295d38f77","serverTiming":{"name":{"cfExtPri":true,"cfEdge":true,"cfOrigin":true,"cfL4":true,"cfSpeedBrain":true,"cfCacheStatus":true}}}'
     crossorigin="anonymous"></script>
