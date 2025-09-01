<div class="relative w-full">

    {{-- Toggle Button --}}
    <div class="border rounded-md px-3  h-8 py-2 bg-white cursor-pointer text-gray-500 text-xs"
        wire:click="$toggle('showDropdown')">
        {{ $branches->find($selectedBranch)?->name ?? 'Select Branch' }}
    </div>



    {{-- Dropdown Options --}}
    @if($showDropdown)
    <div class="absolute bg-white border rounded shadow mt-1 w-full z-50 text-gray-500 text-xs">
        @foreach ($branches as $branch)
        <div class="px-3 py-1 hover:bg-gray-100 cursor-pointer" wire:click="selectBranch({{ $branch->id }})">
            {{ $branch->name }}
        </div>
        @endforeach

        {{-- Add New Branch --}}
        @if (!$showAddForm)
        <div class="px-3 py-1 border-t text-blue-600 hover:bg-gray-100 cursor-pointer"
            wire:click="$set('showAddForm', true)">
            + Add New Branch
        </div>
        @endif

        {{-- New Branch Input --}}
        @if ($showAddForm)
        <div class="flex items-center px-3 py-1 border-t gap-2 flex-wrap">
            <input type="text" wire:model.defer="newBranchName" placeholder="New Branch"
                class="flex-1 border px-2 py-1 text-xs rounded" />
            <button wire:click="addBranch"
                class="shrink-0 bg-brand-500 text-white px-3 py-1 text-xs rounded hover:bg-brand-700">+ ADD</button>
        </div>
        @error('newBranchName')
        <p class="text-error-500 text-xs px-3 mt-1">{{ $message }}</p>
        @enderror
        @endif
    </div>
    @endif
</div>
