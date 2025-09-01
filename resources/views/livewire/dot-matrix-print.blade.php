<div>
    <button wire:click="print" class="btn btn-primary">🖨️ Print Invoice</button>

    @if (session()->has('message'))
        <div class="alert alert-success mt-2">
            {{ session('message') }}
        </div>
    @endif
</div>
