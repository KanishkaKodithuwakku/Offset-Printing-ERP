@props(['type' => 'success', 'message' => session($type)])

@if ($message)
    <div
        x-data="{ show: true }"
        x-init="setTimeout(() => show = false, 3000)"
        x-show="show"
        x-transition
        class="mb-4 rounded px-4 py-2 text-sm border
            {{ $type === 'success' ? 'bg-green-100 text-green-800 border-green-300' : 'bg-red-100 text-red-800 border-red-300' }}"
    >
        {{ $message }}
    </div>
@endif
