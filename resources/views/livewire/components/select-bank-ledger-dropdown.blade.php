<div>
    @if ($showLabel)
    <label class="block text-sm font-medium text-gray-800 mb-1">{{ $label }}</label>
    @endif
    <select
        class="block w-full rounded-lg border border-gray-300 shadow-xs focus:border-blue-400 focus:ring-blue-400 text-gray-900 text-xs px-4  h-8 py-2"
        wire:model="model" wire:change="$dispatch('ledgerSelected', { ledgerId: $event.target.value })">
        <option value="">{{ $placeholder }}</option>
        @foreach ($ledgers as $id => $name)
        <option value="{{ $id }}">{{ $name }}</option>
        @endforeach
    </select>

</div>
