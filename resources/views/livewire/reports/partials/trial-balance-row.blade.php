<ul class="w-full text-sm border-b pl-2 border-gray-300">
    <li class="flex py-2 items-center " style="transition:background 0.2s;"
        onmouseover="this.style.backgroundColor='#FFFF99';this.style.color='';"
        onmouseout="this.style.backgroundColor='';this.style.color='';">
        <!-- Account Name: flex-grow with indentation -->
        <div class="whitespace-nowrap"
            style="
                flex-grow: 1;
                padding-left: {{ $node['depth'] * 24 }}px;
                min-width: 0;
                width: 40%;
                @if ($node['depth'] == 0) color: #B54708; font-weight: bold;
                @elseif ($node['depth'] == 1)
                    color: #000000; font-weight: bold;
                @elseif ($node['depth'] == 2)
                    color: #465FFF; @endif
            ">
            {{ isset($node['code']) ? '[' . $node['code'] . '] ' : '' }}{{ $node['name'] }}
        </div>

        <!-- Type -->
        <div class="text-left"
            style="
                width: 12%;
                min-width: 0;
                @if ($node['depth'] == 0) color: #B54708; font-weight: bold;
                @elseif ($node['depth'] == 1)
                    color: #000000; font-weight: bold;
                @elseif ($node['depth'] == 2)
                    color: #465FFF; @endif
            ">
            {{ $node['type'] }}
        </div>

        <!-- O/P Balance -->
        <div style="width: 12%; min-width: 0;" class="text-left ">
            {{ \App\Helpers\FormatHelper::formatCurrency($node['op_total_dc'], $node['op_total']) }}
        </div>

        <!-- Debit Total -->
        <div style="width: 12%; min-width: 0;" class="text-left ">
            Dr {{ number_format($node['dr_total'], 2) }}
        </div>

        <!-- Credit Total -->
        <div style="width: 12%; min-width: 0;" class="text-left ">
            Cr {{ number_format($node['cr_total'], 2) }}
        </div>

        <!-- C/L Balance -->
        <div style="width: 12%; min-width: 0;" class="text-left ">
            {{ \App\Helpers\FormatHelper::formatCurrency($node['cl_total_dc'], $node['cl_total']) }}

        </div>
    </li>
</ul>

{{-- Render Ledgers --}}
@if (!empty($node['ledgers']))
    @foreach ($node['ledgers'] as $ledger)
        @include('livewire.reports.partials.trial-balance-row', ['node' => $ledger])
    @endforeach
@endif

{{-- Render Child Nodes --}}
@if (!empty($node['children']))
    @foreach ($node['children'] as $child)
        @include('livewire.reports.partials.trial-balance-row', ['node' => $child])
    @endforeach
@endif
