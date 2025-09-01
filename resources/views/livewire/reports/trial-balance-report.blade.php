<div
    class="overflow-hidden rounded-xl border px-4 py-4 border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
    <!-- Header -->
    <div class="text-center mb-5">
        <div class="font-semibold text-lg text-gray-800">{{ config('app.company_name') }}</div>
        <div class="text-sm text-gray-600 mt-1">Trial Balance from 01-Apr-2025 to 31-Mar-2026</div>
    </div>

    <!-- Table Header -->
    <ul class="w-full text-sm  bg-gray-100 font-semibold text-gray-700 flex border-t  border-gray-300">
        <li class="flex-1 pl-2 py-2 text-left" style="width: 40%;">Account Name</li>
        <li class=" py-2 text-left" style="width: 12%;">Type</li>
        <li class=" py-2 text-left" style="width: 12%;">O/P Balance (Rs)</li>
        <li class=" py-2 text-left" style="width: 12%;">Debit Total (Rs)</li>
        <li class=" py-2 text-left" style="width: 12%;">Credit Total (Rs)</li>
        <li class=" py-2 text-left" style="width: 12%;">C/L Balance (Rs)</li>
    </ul>

    <!-- Table Body -->
    <ul class="w-full text-xs  border-gray-300">
        @foreach ($accounts as $group)
            @include('livewire.reports.partials.trial-balance-row', ['node' => $group])
        @endforeach

        <!-- Total Row -->
        <li class="flex text-sm font-semibold border-b bg-gray-50 ">
            <div class="flex-1  px-2 py-1" style="width: 40%;">Total</div>
            <div class=" border-gray-300 px-2 py-1" style="width: 12%;"></div>
            <div class="border-r border-gray-300 px-2 py-1" style="width: 12%;"></div>
            <div class="border-r border-gray-300 px-2 py-1 text-left" style="width: 12%;">Dr
                {{ number_format($dr_total, 2) }}</div>
            <div class="border-r border-gray-300 px-2 py-1 text-left" style="width: 12%;">Cr
                {{ number_format($cr_total, 2) }}</div>
            <div class="px-2 py-1 text-left" style="width: 12%;">
                @if ($dr_total == $cr_total)
                    <span class="text-success-600">✓</span>
                @else
                    <span class="text-error-600">✗</span>
                @endif
            </div>
        </li>
    </ul>
    
</div>
