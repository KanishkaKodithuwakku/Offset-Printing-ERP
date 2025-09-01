{{-- <div class="mx-auto max-w-(--breakpoint-2xl) p-4 md:p-6">
            <div class="space-y-6">
                
              <!-- Metrics Start -->
              <div
  class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]"
>
  <div
    class="flex flex-col justify-between gap-4 mb-8 sm:flex-row sm:items-center"
  >
    <div>
      <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
        Overview 
      </h3>
    </div>
    <div class="flex gap-x-3.5">
      <div
        x-data="{selected: 'weekly'}"
        class="inline-flex w-full items-center gap-0.5 rounded-lg bg-gray-100 p-0.5 dark:bg-gray-900"
      >
        <button
          @click="selected = 'weekly'"
          :class="selected === 'weekly' ? 'shadow-theme-xs text-gray-900 dark:text-white bg-white dark:bg-gray-800' : 'text-gray-500 dark:text-gray-400'"
          class="w-full px-3 py-2 font-medium rounded-md text-theme-sm hover:text-gray-900 dark:hover:text-white"
        >
          Weekly
        </button>
        <button
          @click="selected = 'monthly'"
          :class="selected === 'monthly' ? 'shadow-theme-xs text-gray-900 dark:text-white bg-white dark:bg-gray-800' : 'text-gray-500 dark:text-gray-400'"
          class="w-full px-3 py-2 font-medium rounded-md text-theme-sm hover:text-gray-900 dark:hover:text-white"
        >
          Monthly
        </button>
        <button
          @click="selected = 'yearly'"
          :class="selected === 'yearly' ? 'shadow-theme-xs text-gray-900 dark:text-white bg-white dark:bg-gray-800' : 'text-gray-500 dark:text-gray-400'"
          class="w-full px-3 py-2 font-medium rounded-md text-theme-sm hover:text-gray-900 dark:hover:text-white"
        >
          Yearly
        </button>
      </div>
      <div>
        <button
          class="text-theme-sm shadow-theme-xs inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200"
        >
          <svg
            class="stroke-current fill-white dark:fill-gray-800"
            width="20"
            height="20"
            viewBox="0 0 20 20"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              d="M2.29004 5.90393H17.7067"
              stroke=""
              stroke-width="1.5"
              stroke-linecap="round"
              stroke-linejoin="round"
            />
            <path
              d="M17.7075 14.0961H2.29085"
              stroke=""
              stroke-width="1.5"
              stroke-linecap="round"
              stroke-linejoin="round"
            />
            <path
              d="M12.0826 3.33331C13.5024 3.33331 14.6534 4.48431 14.6534 5.90414C14.6534 7.32398 13.5024 8.47498 12.0826 8.47498C10.6627 8.47498 9.51172 7.32398 9.51172 5.90415C9.51172 4.48432 10.6627 3.33331 12.0826 3.33331Z"
              fill=""
              stroke=""
              stroke-width="1.5"
            />
            <path
              d="M7.91745 11.525C6.49762 11.525 5.34662 12.676 5.34662 14.0959C5.34661 15.5157 6.49762 16.6667 7.91745 16.6667C9.33728 16.6667 10.4883 15.5157 10.4883 14.0959C10.4883 12.676 9.33728 11.525 7.91745 11.525Z"
              fill=""
              stroke=""
              stroke-width="1.5"
            />
          </svg>

          <span class="hidden sm:block">Filter</span>
        </button>
      </div>
    </div>
  </div>
  <div
    class="grid bg-white border border-gray-200 rounded-2xl sm:grid-cols-2 xl:grid-cols-4 dark:border-gray-800 dark:bg-gray-900"
  >
    <div
      class="px-6 py-5 border-b border-gray-200 sm:border-r xl:border-b-0 dark:border-gray-800"
    >
      <span class="text-sm text-gray-500 dark:text-gray-400"
        >Total Revenue</span
      >
      <div class="flex items-end gap-3 mt-2">
        <h4
          class="font-bold text-gray-800 text-title-xs sm:text-title-sm dark:text-white/90"
        >
          $200,45.87
        </h4>
        <div>
          <span
            class="bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500 flex items-center gap-1 rounded-full py-0.5 pr-2.5 pl-2 text-sm font-medium"
          >
            +2.5%
          </span>
        </div>
      </div>
    </div>
    <div
      class="px-6 py-5 border-b border-gray-200 xl:border-r xl:border-b-0 dark:border-gray-800"
    >
      <span class="text-sm text-gray-500 dark:text-gray-400">Active Users</span>
      <div class="flex items-end gap-3 mt-2">
        <h4
          class="font-bold text-gray-800 text-title-xs sm:text-title-sm dark:text-white/90"
        >
          9,528
        </h4>
        <div>
          <span
            class="bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500 flex items-center gap-1 rounded-full py-0.5 pr-2.5 pl-2 text-sm font-medium"
          >
            + 9.5%
          </span>
        </div>
      </div>
    </div>
    <div
      class="px-6 py-5 border-b border-gray-200 sm:border-r sm:border-b-0 dark:border-gray-800"
    >
      <div>
        <span class="text-sm text-gray-500 dark:text-gray-400"
          >Customer Lifetime Value</span
        >
        <div class="flex items-end gap-3 mt-2">
          <h4
            class="font-bold text-gray-800 text-title-xs sm:text-title-sm dark:text-white/90"
          >
            $849.54
          </h4>
          <div>
            <span
              class="bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500 flex items-center gap-1 rounded-full py-0.5 pr-2.5 pl-2 text-sm font-medium"
            >
              -1.6%
            </span>
          </div>
        </div>
      </div>
    </div>
    <div class="px-6 py-5">
      <span class="text-sm text-gray-500 dark:text-gray-400"
        >Customer Acquisition Cost</span
      >
      <div class="flex items-end gap-3 mt-2">
        <h4
          class="font-bold text-gray-800 text-title-xs sm:text-title-sm dark:text-white/90"
        >
          9,528
        </h4>
        <div>
          <span
            class="bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500 flex items-center gap-1 rounded-full py-0.5 pr-2.5 pl-2 text-sm font-medium"
          >
            +3.5%
          </span>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Metrics End -->
              <div
                class="gap-5 space-y-5 sm:gap-6 sm:space-y-6 xl:grid xl:grid-cols-12 xl:space-y-0"
              >
                <div class="xl:col-span-7 2xl:col-span-8">
                  <div class="space-y-5 sm:space-y-6">
                    <!-- Chart -->
                    <div class="grid gap-5 sm:gap-6 lg:grid-cols-2">
                      <div
  class="overflow-hidden rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]"
>
  <div class="flex justify-between mb-6">
    <div>
      <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
        Churn Rate
      </h3>
      <p class="mt-1 text-gray-500 text-theme-sm dark:text-gray-400">
        Downgrade to Free plan
      </p>
    </div>
    <div x-data="{openDropDown: false}" class="relative h-fit">
      <button
        @click="openDropDown = !openDropDown"
        :class="openDropDown ? 'text-gray-700 dark:text-white' : 'text-gray-400 hover:text-gray-700 dark:hover:text-white'"
      >
        <svg
          class="fill-current"
          width="24"
          height="24"
          viewBox="0 0 24 24"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            fill-rule="evenodd"
            clip-rule="evenodd"
            d="M10.2441 6C10.2441 5.0335 11.0276 4.25 11.9941 4.25H12.0041C12.9706 4.25 13.7541 5.0335 13.7541 6C13.7541 6.9665 12.9706 7.75 12.0041 7.75H11.9941C11.0276 7.75 10.2441 6.9665 10.2441 6ZM10.2441 18C10.2441 17.0335 11.0276 16.25 11.9941 16.25H12.0041C12.9706 16.25 13.7541 17.0335 13.7541 18C13.7541 18.9665 12.9706 19.75 12.0041 19.75H11.9941C11.0276 19.75 10.2441 18.9665 10.2441 18ZM11.9941 10.25C11.0276 10.25 10.2441 11.0335 10.2441 12C10.2441 12.9665 11.0276 13.75 11.9941 13.75H12.0041C12.9706 13.75 13.7541 12.9665 13.7541 12C13.7541 11.0335 12.9706 10.25 12.0041 10.25H11.9941Z"
            fill=""
          />
        </svg>
      </button>
      <div
        x-show="openDropDown"
        @click.outside="openDropDown = false"
        class="absolute right-0 z-40 w-40 p-2 space-y-1 bg-white border border-gray-200 shadow-theme-lg dark:bg-gray-dark top-full rounded-2xl dark:border-gray-800"
      >
        <button
          class="flex w-full px-3 py-2 font-medium text-left text-gray-500 rounded-lg text-theme-xs hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300"
        >
          View More
        </button>
        <button
          class="flex w-full px-3 py-2 font-medium text-left text-gray-500 rounded-lg text-theme-xs hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300"
        >
          Delete
        </button>
      </div>
    </div>
  </div>
  <div class="flex justify-between">
    <div>
      <h3 class="font-semibold text-gray-800 text-title-xs dark:text-white/90">
        4.26%
      </h3>
      <p class="mt-1 text-gray-500 text-theme-xs dark:text-gray-400">
        <span class="inline-block mr-1 text-error-500">0.31%</span>
        than last Week
      </p>
    </div>
    <div class="max-w-full">
      <div id="chartTwentyOne" class="w-24 h-12"></div>
    </div>
  </div>
</div>
<div
  class="overflow-hidden rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]"
>
  <div class="flex justify-between mb-6">
    <div>
      <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
        User Growth
      </h3>
      <p class="mt-1 text-gray-500 text-theme-sm dark:text-gray-400">
        New signups website + mobile
      </p>
    </div>
    <div x-data="{openDropDown: false}" class="relative h-fit">
      <button
        @click="openDropDown = !openDropDown"
        :class="openDropDown ? 'text-gray-700 dark:text-white' : 'text-gray-400 hover:text-gray-700 dark:hover:text-white'"
      >
        <svg
          class="fill-current"
          width="24"
          height="24"
          viewBox="0 0 24 24"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            fill-rule="evenodd"
            clip-rule="evenodd"
            d="M10.2441 6C10.2441 5.0335 11.0276 4.25 11.9941 4.25H12.0041C12.9706 4.25 13.7541 5.0335 13.7541 6C13.7541 6.9665 12.9706 7.75 12.0041 7.75H11.9941C11.0276 7.75 10.2441 6.9665 10.2441 6ZM10.2441 18C10.2441 17.0335 11.0276 16.25 11.9941 16.25H12.0041C12.9706 16.25 13.7541 17.0335 13.7541 18C13.7541 18.9665 12.9706 19.75 12.0041 19.75H11.9941C11.0276 19.75 10.2441 18.9665 10.2441 18ZM11.9941 10.25C11.0276 10.25 10.2441 11.0335 10.2441 12C10.2441 12.9665 11.0276 13.75 11.9941 13.75H12.0041C12.9706 13.75 13.7541 12.9665 13.7541 12C13.7541 11.0335 12.9706 10.25 12.0041 10.25H11.9941Z"
            fill=""
          />
        </svg>
      </button>
      <div
        x-show="openDropDown"
        @click.outside="openDropDown = false"
        class="absolute right-0 z-40 w-40 p-2 space-y-1 bg-white border border-gray-200 shadow-theme-lg dark:bg-gray-dark top-full rounded-2xl dark:border-gray-800"
      >
        <button
          class="flex w-full px-3 py-2 font-medium text-left text-gray-500 rounded-lg text-theme-xs hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300"
        >
          View More
        </button>
        <button
          class="flex w-full px-3 py-2 font-medium text-left text-gray-500 rounded-lg text-theme-xs hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300"
        >
          Delete
        </button>
      </div>
    </div>
  </div>
  <div class="flex justify-between">
    <div>
      <h3 class="font-semibold text-gray-800 text-title-xs dark:text-white/90">
        3,768
      </h3>
      <p class="mt-1 text-gray-500 text-theme-xs dark:text-gray-400">
        <span class="inline-block mr-1 text-success-600">+3.85%</span>
        than last Week
      </p>
    </div>
    <div class="max-w-full">
      <div id="chartTwentyTwo" class="w-24 h-12"></div>
    </div>
  </div>
</div>
</div>
                    <!-- Funnel Chart -->
                    <div
  class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]"
>
  <div class="flex justify-between mb-6">
    <div>
      <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
        Conversion Funnel
      </h3>
    </div>
    <div x-data="{openDropDown: false}" class="relative h-fit">
      <button
        @click="openDropDown = !openDropDown"
        :class="openDropDown ? 'text-gray-700 dark:text-white' : 'text-gray-400 hover:text-gray-700 dark:hover:text-white'"
      >
        <svg
          class="fill-current"
          width="24"
          height="24"
          viewBox="0 0 24 24"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            fill-rule="evenodd"
            clip-rule="evenodd"
            d="M10.2441 6C10.2441 5.0335 11.0276 4.25 11.9941 4.25H12.0041C12.9706 4.25 13.7541 5.0335 13.7541 6C13.7541 6.9665 12.9706 7.75 12.0041 7.75H11.9941C11.0276 7.75 10.2441 6.9665 10.2441 6ZM10.2441 18C10.2441 17.0335 11.0276 16.25 11.9941 16.25H12.0041C12.9706 16.25 13.7541 17.0335 13.7541 18C13.7541 18.9665 12.9706 19.75 12.0041 19.75H11.9941C11.0276 19.75 10.2441 18.9665 10.2441 18ZM11.9941 10.25C11.0276 10.25 10.2441 11.0335 10.2441 12C10.2441 12.9665 11.0276 13.75 11.9941 13.75H12.0041C12.9706 13.75 13.7541 12.9665 13.7541 12C13.7541 11.0335 12.9706 10.25 12.0041 10.25H11.9941Z"
            fill=""
          />
        </svg>
      </button>
      <div
        x-show="openDropDown"
        @click.outside="openDropDown = false"
        class="absolute right-0 z-40 w-40 p-2 space-y-1 bg-white border border-gray-200 shadow-theme-lg dark:bg-gray-dark top-full rounded-2xl dark:border-gray-800"
      >
        <button
          class="flex w-full px-3 py-2 font-medium text-left text-gray-500 rounded-lg text-theme-xs hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300"
        >
          View More
        </button>
        <button
          class="flex w-full px-3 py-2 font-medium text-left text-gray-500 rounded-lg text-theme-xs hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300"
        >
          Delete
        </button>
      </div>
    </div>
  </div>
  <div class="max-w-full overflow-x-auto custom-scrollbar">
    <div id="chartSeventeen" class="-ml-5 min-w-[700px] pl-2"></div>
  </div>
</div>
<!-- Funnel Chart End -->
                    <!-- Invoice Table -->
                    <div
  class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]"
>
  <div class="px-6 py-4">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
      Recent Invoices
    </h3>
  </div>
  <div class="overflow-x-auto custom-scrollbar">
    <table class="min-w-full">
      <thead>
        <tr class="bg-gray-50 dark:bg-gray-900">
          <th
            class="px-6 py-4 text-sm font-medium text-left text-gray-500 whitespace-nowrap dark:text-gray-400"
          >
            Serial No:
          </th>
          <th
            class="px-6 py-4 text-sm font-medium text-left text-gray-500 whitespace-nowrap dark:text-gray-400"
          >
            Close Date
          </th>
          <th
            class="px-6 py-4 text-sm font-medium text-left text-gray-500 whitespace-nowrap dark:text-gray-400"
          >
            User
          </th>
          <th
            class="px-6 py-4 text-sm font-medium text-left text-gray-500 whitespace-nowrap dark:text-gray-400"
          >
            Amount
          </th>
          <th
            class="px-6 py-4 text-sm font-medium text-left text-gray-500 whitespace-nowrap dark:text-gray-400"
          >
            Status
          </th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
        <tr>
          <td
            class="px-6 py-4 text-sm text-left text-gray-700 whitespace-nowrap dark:text-gray-400"
          >
            #DF429
          </td>
          <td
            class="px-6 py-4 text-sm text-left text-gray-700 whitespace-nowrap dark:text-gray-400"
          >
            April 28, 2016
          </td>
          <td
            class="px-6 py-4 text-sm text-left text-gray-700 whitespace-nowrap dark:text-gray-400"
          >
            Jenny Wilson
          </td>
          <td
            class="px-6 py-4 text-sm text-left text-gray-700 whitespace-nowrap dark:text-gray-400"
          >
            $473.85
          </td>
          <td class="px-6 py-4 text-left">
            <span
              class="bg-success-50 text-theme-xs text-success-600 dark:bg-success-500/15 dark:text-success-500 rounded-full px-2 py-0.5 font-medium"
            >
              Complete
            </span>
          </td>
        </tr>
        <tr>
          <td
            class="px-6 py-4 text-sm text-left text-gray-700 whitespace-nowrap dark:text-gray-400"
          >
            #HTY274
          </td>
          <td
            class="px-6 py-4 text-sm text-left text-gray-700 whitespace-nowrap dark:text-gray-400"
          >
            October 30, 2017
          </td>
          <td
            class="px-6 py-4 text-sm text-left text-gray-700 whitespace-nowrap dark:text-gray-400"
          >
            Wade Warren
          </td>
          <td
            class="px-6 py-4 text-sm text-left text-gray-700 whitespace-nowrap dark:text-gray-400"
          >
            $293.01
          </td>
          <td
            class="px-6 py-4 text-sm text-left text-gray-700 whitespace-nowrap dark:text-gray-400"
          >
            <span
              class="bg-success-50 text-theme-xs text-success-600 dark:bg-success-500/15 dark:text-success-500 rounded-full px-2 py-0.5 font-medium"
            >
              Complete
            </span>
          </td>
        </tr>
        <tr>
          <td
            class="px-6 py-4 text-sm text-left text-gray-700 whitespace-nowrap dark:text-gray-400"
          >
            #LKE600
          </td>
          <td
            class="px-6 py-4 text-sm text-left text-gray-700 whitespace-nowrap dark:text-gray-400"
          >
            May 29, 2017
          </td>
          <td
            class="px-6 py-4 text-sm text-left text-gray-700 whitespace-nowrap dark:text-gray-400"
          >
            Darlene Robertson
          </td>
          <td
            class="px-6 py-4 text-sm text-left text-gray-700 whitespace-nowrap dark:text-gray-400"
          >
            $782.01
          </td>
          <td
            class="px-6 py-4 text-sm text-left text-gray-700 whitespace-nowrap dark:text-gray-400"
          >
            <span
              class="bg-warning-50 text-theme-xs text-warning-600 dark:bg-warning-500/15 dark:text-warning-500 rounded-full px-2 py-0.5 font-medium"
            >
              Pending
            </span>
          </td>
        </tr>
        <tr>
          <td
            class="px-6 py-4 text-sm text-left text-gray-700 whitespace-nowrap dark:text-gray-400"
          >
            #HRP447
          </td>
          <td
            class="px-6 py-4 text-sm text-left text-gray-700 whitespace-nowrap dark:text-gray-400"
          >
            May 20, 2015
          </td>
          <td
            class="px-6 py-4 text-sm text-left text-gray-700 whitespace-nowrap dark:text-gray-400"
          >
            Arlene McCoy
          </td>
          <td
            class="px-6 py-4 text-sm text-left text-gray-700 whitespace-nowrap dark:text-gray-400"
          >
            $202.87
          </td>
          <td
            class="px-6 py-4 text-sm text-left text-gray-700 whitespace-nowrap dark:text-gray-400"
          >
            <span
              class="bg-error-50 text-theme-xs text-error-600 dark:bg-error-500/15 dark:text-error-500 rounded-full px-2 py-0.5 font-medium"
            >
              Cancelled
            </span>
          </td>
        </tr>
        <tr>
          <td
            class="px-6 py-4 text-sm text-left text-gray-700 whitespace-nowrap dark:text-gray-400"
          >
            #WRH647
          </td>
          <td
            class="px-6 py-4 text-sm text-left text-gray-700 whitespace-nowrap dark:text-gray-400"
          >
            March 13, 2014
          </td>
          <td
            class="px-6 py-4 text-sm text-left text-gray-700 whitespace-nowrap dark:text-gray-400"
          >
            Bessie Cooper
          </td>
          <td
            class="px-6 py-4 text-sm text-left text-gray-700 whitespace-nowrap dark:text-gray-400"
          >
            $490.51
          </td>
          <td
            class="px-6 py-4 text-sm text-left text-gray-700 whitespace-nowrap dark:text-gray-400"
          >
            <span
              class="bg-success-50 text-theme-xs text-success-600 dark:bg-success-500/15 dark:text-success-500 rounded-full px-2 py-0.5 font-medium"
            >
              Complete
            </span>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
</div>
                </div>
                <div class="space-y-6 xl:col-span-5 2xl:col-span-4">
                  <!-- Product Performance -->
                  <div
                    class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]"
                  >
                    <div class="flex justify-between mb-6">
                      <div>
                        <h3
                          class="text-lg font-semibold text-gray-800 dark:text-white/90"
                        >
                          Product Performance
                        </h3>
                      </div>
                      <div
                        x-data="{openDropDown: false}"
                        class="relative h-fit"
                      >
                        <button
                          @click="openDropDown = !openDropDown"
                          :class="openDropDown ? 'text-gray-700 dark:text-white' : 'text-gray-400 hover:text-gray-700 dark:hover:text-white'"
                        >
                          <svg
                            class="fill-current"
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                          >
                            <path
                              fill-rule="evenodd"
                              clip-rule="evenodd"
                              d="M10.2441 6C10.2441 5.0335 11.0276 4.25 11.9941 4.25H12.0041C12.9706 4.25 13.7541 5.0335 13.7541 6C13.7541 6.9665 12.9706 7.75 12.0041 7.75H11.9941C11.0276 7.75 10.2441 6.9665 10.2441 6ZM10.2441 18C10.2441 17.0335 11.0276 16.25 11.9941 16.25H12.0041C12.9706 16.25 13.7541 17.0335 13.7541 18C13.7541 18.9665 12.9706 19.75 12.0041 19.75H11.9941C11.0276 19.75 10.2441 18.9665 10.2441 18ZM11.9941 10.25C11.0276 10.25 10.2441 11.0335 10.2441 12C10.2441 12.9665 11.0276 13.75 11.9941 13.75H12.0041C12.9706 13.75 13.7541 12.9665 13.7541 12C13.7541 11.0335 12.9706 10.25 12.0041 10.25H11.9941Z"
                              fill=""
                            />
                          </svg>
                        </button>
                        <div
                          x-show="openDropDown"
                          @click.outside="openDropDown = false"
                          class="absolute right-0 z-40 w-40 p-2 space-y-1 bg-white border border-gray-200 shadow-theme-lg dark:bg-gray-dark top-full rounded-2xl dark:border-gray-800"
                        >
                          <button
                            class="flex w-full px-3 py-2 font-medium text-left text-gray-500 rounded-lg text-theme-xs hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300"
                          >
                            View More
                          </button>
                          <button
                            class="flex w-full px-3 py-2 font-medium text-left text-gray-500 rounded-lg text-theme-xs hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300"
                          >
                            Delete
                          </button>
                        </div>
                      </div>
                    </div>
                    <div x-data="{selected: 'daily'}">
                      <div
                        class="flex w-full items-center gap-0.5 rounded-lg bg-gray-100 p-0.5 dark:bg-gray-900"
                      >
                        <button
                          @click="selected = 'daily'"
                          :class="selected === 'daily' ? 'shadow-theme-xs text-gray-900 dark:text-white bg-white dark:bg-gray-800' : 'text-gray-500 dark:text-gray-400'"
                          class="w-full px-3 py-2 font-medium rounded-md text-theme-sm hover:text-gray-900 dark:hover:text-white"
                        >
                          Daily Sales
                        </button>
                        <button
                          @click="selected = 'online'"
                          :class="selected === 'online' ? 'shadow-theme-xs text-gray-900 dark:text-white bg-white dark:bg-gray-800' : 'text-gray-500 dark:text-gray-400'"
                          class="w-full px-3 py-2 font-medium rounded-md text-theme-sm hover:text-gray-900 dark:hover:text-white"
                        >
                          Online Sales
                        </button>
                        <button
                          @click="selected = 'new'"
                          :class="selected === 'new' ? 'shadow-theme-xs text-gray-900 dark:text-white bg-white dark:bg-gray-800' : 'text-gray-500 dark:text-gray-400'"
                          class="w-full px-3 py-2 font-medium rounded-md text-theme-sm hover:text-gray-900 dark:hover:text-white"
                        >
                          New Users
                        </button>
                      </div>
                      <!-- Tab Panels -->
                      <div class="mt-4">
                        <!-- Daily Sales Panel -->
                        <div x-show="selected === 'daily'" class="space-y-4">
                          <div
                            class="grid grid-cols-2 justify-between gap-10 divide-x divide-gray-100 rounded-xl border border-gray-100 bg-white py-4 dark:divide-gray-800 dark:border-gray-800 dark:bg-gray-800/[0.03]"
                          >
                            <div class="px-5">
                              <span
                                class="block text-sm text-gray-500 dark:text-gray-400"
                                >Digital Product</span
                              >
                              <div class="flex items-center gap-2 mt-1">
                                <span
                                  class="inline-flex items-center justify-center rounded-full bg-success-50 dark:bg-success-500/15 text-success-600 size-5"
                                >
                                  <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="12"
                                    height="12"
                                    viewBox="0 0 12 12"
                                    fill="none"
                                  >
                                    <path
                                      fill-rule="evenodd"
                                      clip-rule="evenodd"
                                      d="M5.56462 1.62411C5.70194 1.47091 5.90136 1.37451 6.12329 1.37451C6.1236 1.37451 6.1239 1.37451 6.12421 1.37451C6.3163 1.37434 6.50845 1.4475 6.65505 1.594L9.65514 4.59199C9.94814 4.88478 9.94831 5.35966 9.65552 5.65265C9.36272 5.94565 8.88785 5.94581 8.59486 5.65302L6.87329 3.93267L6.87329 10.1252C6.87329 10.5394 6.53751 10.8752 6.12329 10.8752C5.70908 10.8752 5.37329 10.5394 5.37329 10.1252L5.37329 3.93597L3.65516 5.65301C3.36218 5.94581 2.8873 5.94566 2.5945 5.65267C2.3017 5.35968 2.30185 4.88481 2.59484 4.59201L5.56462 1.62411Z"
                                      fill="currentColor"
                                    />
                                  </svg>
                                </span>
                                <h4
                                  class="text-xl font-semibold text-gray-800 dark:text-white/90"
                                >
                                  790
                                </h4>
                              </div>
                            </div>
                            <div class="px-5">
                              <span
                                class="block text-sm text-gray-500 dark:text-gray-400"
                                >Physical Product</span
                              >
                              <div class="flex items-center gap-2 mt-1">
                                <span
                                  class="inline-flex items-center justify-center rounded-full bg-error-50 dark:bg-error-500/15 text-error-600 size-5"
                                >
                                  <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="12"
                                    height="12"
                                    viewBox="0 0 12 12"
                                    fill="none"
                                  >
                                    <path
                                      fill-rule="evenodd"
                                      clip-rule="evenodd"
                                      d="M5.31462 10.3761C5.45194 10.5293 5.65136 10.6257 5.87329 10.6257C5.8736 10.6257 5.8739 10.6257 5.87421 10.6257C6.0663 10.6259 6.25845 10.5527 6.40505 10.4062L9.40514 7.4082C9.69814 7.11541 9.69831 6.64054 9.40552 6.34754C9.11273 6.05454 8.63785 6.05438 8.34486 6.34717L6.62329 8.06753L6.62329 1.875C6.62329 1.46079 6.28751 1.125 5.87329 1.125C5.45908 1.125 5.12329 1.46079 5.12329 1.875L5.12329 8.06422L3.40516 6.34719C3.11218 6.05439 2.6373 6.05454 2.3445 6.34752C2.0517 6.64051 2.05185 7.11538 2.34484 7.40818L5.31462 10.3761Z"
                                      fill="currentColor"
                                    />
                                  </svg>
                                </span>
                                <h4
                                  class="text-xl font-semibold text-gray-800 dark:text-white/90"
                                >
                                  572
                                </h4>
                              </div>
                            </div>
                          </div>
                          <div class="px-5 py-4 border border-gray-100 rounded-xl dark:border-gray-800">
  <div class="flex items-start justify-between mb-3">
    <div>
      <span class="font-medium text-gray-500 text-theme-sm dark:text-gray-400"
        >Average Daily Sales</span
      >
      <h3 class="font-semibold text-gray-800 text-title-sm dark:text-white/90">
        $2,950
      </h3>
    </div>
    <div>
      <span
        class="bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500 flex items-center gap-1 rounded-full py-0.5 pr-2.5 pl-2 text-sm font-medium"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="12"
          height="12"
          viewBox="0 0 12 12"
          fill="none"
        >
          <path
            fill-rule="evenodd"
            clip-rule="evenodd"
            d="M5.31462 10.3761C5.45194 10.5293 5.65136 10.6257 5.87329 10.6257C5.8736 10.6257 5.8739 10.6257 5.87421 10.6257C6.0663 10.6259 6.25845 10.5527 6.40505 10.4062L9.40514 7.4082C9.69814 7.11541 9.69831 6.64054 9.40552 6.34754C9.11273 6.05454 8.63785 6.05438 8.34486 6.34717L6.62329 8.06753L6.62329 1.875C6.62329 1.46079 6.28751 1.125 5.87329 1.125C5.45908 1.125 5.12329 1.46079 5.12329 1.875L5.12329 8.06422L3.40516 6.34719C3.11218 6.05439 2.6373 6.05454 2.3445 6.34752C2.0517 6.64051 2.05185 7.11538 2.34484 7.40818L5.31462 10.3761Z"
            fill="currentColor"
          /></svg
        >0.52%</span
      >
    </div>
  </div>
  <!-- Chart -->
  <div class="overflow-x-auto">
    <div id="chartEighteen" class="-ml-3"></div>
  </div>
</div>
</div>

                        <!-- Online Sales Panel -->
                        <div x-show="selected === 'online'" class="space-y-4">
                          <div
                            class="grid justify-between grid-cols-2 gap-10 py-4 border border-gray-100 divide-x divide-gray-100 rounded-xl dark:divide-gray-800 dark:border-gray-800"
                          >
                            <div class="px-5">
                              <span
                                class="block text-sm text-gray-500 dark:text-gray-400"
                                >Digital Product</span
                              >
                              <div class="flex items-center gap-2 mt-1">
                                <span
                                  class="inline-flex items-center justify-center rounded-full bg-success-50 dark:bg-success-500/15 text-success-600 size-5"
                                >
                                  <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="12"
                                    height="12"
                                    viewBox="0 0 12 12"
                                    fill="none"
                                  >
                                    <path
                                      fill-rule="evenodd"
                                      clip-rule="evenodd"
                                      d="M5.56462 1.62411C5.70194 1.47091 5.90136 1.37451 6.12329 1.37451C6.1236 1.37451 6.1239 1.37451 6.12421 1.37451C6.3163 1.37434 6.50845 1.4475 6.65505 1.594L9.65514 4.59199C9.94814 4.88478 9.94831 5.35966 9.65552 5.65265C9.36272 5.94565 8.88785 5.94581 8.59486 5.65302L6.87329 3.93267L6.87329 10.1252C6.87329 10.5394 6.53751 10.8752 6.12329 10.8752C5.70908 10.8752 5.37329 10.5394 5.37329 10.1252L5.37329 3.93597L3.65516 5.65301C3.36218 5.94581 2.8873 5.94566 2.5945 5.65267C2.3017 5.35968 2.30185 4.88481 2.59484 4.59201L5.56462 1.62411Z"
                                      fill="currentColor"
                                    />
                                  </svg>
                                </span>
                                <h4
                                  class="text-xl font-semibold text-gray-800 dark:text-white/90"
                                >
                                  8,490
                                </h4>
                              </div>
                            </div>
                            <div class="px-5">
                              <span
                                class="block text-sm text-gray-500 dark:text-gray-400"
                                >Physical Product</span
                              >
                              <div class="flex items-center gap-2 mt-1">
                                <span
                                  class="inline-flex items-center justify-center rounded-full bg-error-50 dark:bg-error-500/15 text-error-600 size-5"
                                >
                                  <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="12"
                                    height="12"
                                    viewBox="0 0 12 12"
                                    fill="none"
                                  >
                                    <path
                                      fill-rule="evenodd"
                                      clip-rule="evenodd"
                                      d="M5.31462 10.3761C5.45194 10.5293 5.65136 10.6257 5.87329 10.6257C5.8736 10.6257 5.8739 10.6257 5.87421 10.6257C6.0663 10.6259 6.25845 10.5527 6.40505 10.4062L9.40514 7.4082C9.69814 7.11541 9.69831 6.64054 9.40552 6.34754C9.11273 6.05454 8.63785 6.05438 8.34486 6.34717L6.62329 8.06753L6.62329 1.875C6.62329 1.46079 6.28751 1.125 5.87329 1.125C5.45908 1.125 5.12329 1.46079 5.12329 1.875L5.12329 8.06422L3.40516 6.34719C3.11218 6.05439 2.6373 6.05454 2.3445 6.34752C2.0517 6.64051 2.05185 7.11538 2.34484 7.40818L5.31462 10.3761Z"
                                      fill="currentColor"
                                    />
                                  </svg>
                                </span>
                                <h4
                                  class="text-xl font-semibold text-gray-800 dark:text-white/90"
                                >
                                  950
                                </h4>
                              </div>
                            </div>
                          </div>
                          <div class="px-5 py-4 border border-gray-100 rounded-xl dark:border-gray-800">
  <div class="flex items-start justify-between mb-3">
    <div>
      <span class="font-medium text-gray-500 text-theme-sm dark:text-gray-400"
        >Total Online Sales</span
      >
      <h3 class="font-semibold text-gray-800 text-title-sm dark:text-white/90">
        $59,410
      </h3>
    </div>
    <div>
      <span
        class="bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500 flex items-center gap-1 rounded-full py-0.5 pr-2.5 pl-2 text-sm font-medium"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="12"
          height="12"
          viewBox="0 0 12 12"
          fill="none"
        >
          <path
            fill-rule="evenodd"
            clip-rule="evenodd"
            d="M5.56462 1.62411C5.70194 1.47091 5.90136 1.37451 6.12329 1.37451C6.1236 1.37451 6.12391 1.37451 6.12421 1.37451C6.3163 1.37434 6.50845 1.4475 6.65505 1.594L9.65514 4.59199C9.94814 4.88478 9.94831 5.35966 9.65552 5.65265C9.36272 5.94565 8.88785 5.94581 8.59486 5.65302L6.87329 3.93267L6.87329 10.1252C6.87329 10.5394 6.53751 10.8752 6.12329 10.8752C5.70908 10.8752 5.37329 10.5394 5.37329 10.1252L5.37329 3.93597L3.65516 5.65301C3.36218 5.94581 2.8873 5.94566 2.5945 5.65267C2.3017 5.35968 2.30185 4.88481 2.59484 4.59201L5.56462 1.62411Z"
            fill="currentColor"
          />
        </svg>
        0.52%</span
      >
    </div>
  </div>
  <!-- Chart -->
  <div class="max-w-full overflow-x-auto custom-scrollbar">
    <div id="chartNineteen" class="w-full -ml-3"></div>
  </div>
</div>
</div>

                        <!-- New Users Panel -->
                        <div x-show="selected === 'new'" class="space-y-4">
                          <div
                            class="grid justify-between grid-cols-2 gap-4 py-4 border border-gray-100 rounded-xl dark:border-gray-800"
                          >
                            <div class="px-5">
                              <div class="flex -space-x-3">
                                <img
                                  src="src/images/user/user-01.jpg"
                                  class="rounded-full size-12 ring-2 ring-white dark:ring-gray-800/90"
                                  alt=""
                                />
                                <img
                                  src="src/images/user/user-02.jpg"
                                  class="rounded-full size-12 ring-2 ring-white dark:ring-gray-800/90"
                                  alt=""
                                />
                                <img
                                  src="src/images/user/user-03.jpg"
                                  class="rounded-full size-12 ring-2 ring-white dark:ring-gray-800/90"
                                  alt=""
                                />
                                <div
                                  class="inline-flex items-center justify-center text-sm font-medium text-gray-700 bg-gray-200 rounded-full size-12 shrink-0 ring-2 ring-white dark:ring-gray-800/90"
                                >
                                  10+
                                </div>
                              </div>
                            </div>
                            <div class="px-5">
                              <span
                                class="block text-sm text-gray-500 dark:text-gray-400"
                                >Added last month</span
                              >
                              <div class="flex items-center gap-2 mt-1">
                                <h4
                                  class="text-xl font-semibold text-gray-800 dark:text-white/90"
                                >
                                  8,490
                                </h4>
                              </div>
                            </div>
                          </div>
                          <div class="px-5 py-4 border border-gray-100 rounded-xl dark:border-gray-800">
  <div class="flex items-start justify-between mb-3">
    <div>
      <span class="font-medium text-gray-500 text-theme-sm dark:text-gray-400"
        >Total New Users</span
      >
      <h3 class="font-semibold text-gray-800 text-title-sm dark:text-white/90">
        5.9K
      </h3>
    </div>
    <div>
      <span
        class="bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500 flex items-center gap-1 rounded-full py-0.5 pr-2.5 pl-2 text-sm font-medium"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="12"
          height="12"
          viewBox="0 0 12 12"
          fill="none"
        >
          <path
            fill-rule="evenodd"
            clip-rule="evenodd"
            d="M5.56462 1.62411C5.70194 1.47091 5.90136 1.37451 6.12329 1.37451C6.1236 1.37451 6.12391 1.37451 6.12421 1.37451C6.3163 1.37434 6.50845 1.4475 6.65505 1.594L9.65514 4.59199C9.94814 4.88478 9.94831 5.35966 9.65552 5.65265C9.36272 5.94565 8.88785 5.94581 8.59486 5.65302L6.87329 3.93267L6.87329 10.1252C6.87329 10.5394 6.53751 10.8752 6.12329 10.8752C5.70908 10.8752 5.37329 10.5394 5.37329 10.1252L5.37329 3.93597L3.65516 5.65301C3.36218 5.94581 2.8873 5.94566 2.5945 5.65267C2.3017 5.35968 2.30185 4.88481 2.59484 4.59201L5.56462 1.62411Z"
            fill="currentColor"
          />
        </svg>
        0.52%</span
      >
    </div>
  </div>
  <!-- Chart -->
  <div>
    <div id="chartTwenty" class="w-full -ml-2"></div>
  </div>
</div>
</div>
                      </div>
                    </div>
                  </div>
                  <!-- Product Performance End --> --}}