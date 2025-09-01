<div class="p-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Dispatch Process for Job Order: {{ $jobOrder->job_number }}
    </h2>

    <!-- Display success/error messages -->
    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @elseif (session()->has('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif

    <!-- Dispatch Note Form -->
    <form wire:submit.prevent="createDispatchNote" class="space-y-4">
        @if (!$canInvoice)
        <div style="width: 300px;">
            <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity (Plates to Dispatch)</label>
            <input type="number" wire:model="quantity" id="quantity" min="1"
                max="{{ $jobOrder->plates - ($jobOrder->dispatchNotes->sum('quantity') ?? 0) }}" required
                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" />

            @error('quantity')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
        @endif
        @if (!$canInvoice)
            <button type="submit" wire:click="createDispatchNote" style="background-color:#465FFF;padding 8px 15px;"
                class="mt-3 bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-md text-sm shadow-md transition duration-300 ease-in-out">
                Create Dispatch Note
            </button>
        @else
            <!-- Show this button if $canInvoice is true -->
            <button type="button" wire:click="openInvoice" style="background-color:#465FFF;padding 8px 15px;"
                class="mt-3 bg-green-600 hover:bg-green-700 text-white font-medium px-6 py-2 rounded-md text-sm shadow-md transition duration-300 ease-in-out">
                Open Invoice
            </button>
        @endif
    </form>



    <div class="mt-6">
        <h3 class="text-lg font-semibold text-gray-800">Current Status: {{ ucfirst($jobOrder->status) }}</h3>
        <p class="text-sm text-gray-500">Total Plates Dispatched: {{ $dispatchedPlates }}/ {{ $plates }}</p>
    </div>
</div>
