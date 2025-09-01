<div >
    <label class="block text-sm font-medium text-gray-800 mb-1">{{ $label }}</label>
    <select
        class="block w-full rounded-lg border border-gray-300 shadow-xs focus:border-blue-400 focus:ring-blue-400 text-gray-900 text-sm px-4 py-1"
        wire:model="model"
        wire:change="$dispatch('ledgerSelected', { ledgerId: $event.target.value })"
    >
        <option  value="">{{ $placeholder }}</option>
        @foreach ($ledgers as $group => $ledgerList)
            <optgroup label="{{ $group }}">
                @foreach ($ledgerList as $id => $text)
                    <option value="{{ $id }}">{{ $text }}</option>
                @endforeach
            </optgroup>
        @endforeach
    </select>
</div>
