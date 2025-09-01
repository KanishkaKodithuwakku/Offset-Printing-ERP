<?php

namespace App\Livewire;

use App\Models\DispatchNote;
use Livewire\Component;
use App\Models\Customer;
use App\Models\DispatchItem;
use App\Models\JobOrder;

class DispatchNotePrintPreview extends Component
{
    public $dispatchNote;
    public $dispatchItems;
    public $dispatchStatus;

    // Optionally: Include customer-related data if needed
    public $customer_name;
    public $customer_address;

    public function mount($dispatchNoteId)
    {
        $this->dispatchNote = DispatchNote::with(['dispatchItems'])->find($dispatchNoteId);

        // Get the customer name and address if related to DispatchNote
        $this->customer_name = $this->dispatchNote->customer_name;  // Assuming `DispatchNote` has a `customer_name` attribute
        $this->customer_address = $this->dispatchNote->customer_address; // Assuming `DispatchNote` has a `customer_address` attribute

        $this->dispatchStatus = $this->dispatchNote->status;
        $this->dispatchItems = $this->dispatchNote->dispatchItems;
    }

    public function printPage()
    {
        // Dispatch print preview event (Ensure it's handled in your view's JavaScript)
        $this->dispatch('print-preview');

        //block the print button for 2nd print
    }

    public function render()
    {
        $bodyAttributes = 'x-data="{ page: \'addGrn\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
            x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                    $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
            :class="{\'dark bg-gray-900\': darkMode === true}"';

        return view('livewire.dispatch-note-print-preview', [
            'dispatchNote' => $this->dispatchNote,
            'dispatchItems' => $this->dispatchItems,
            'customer_name' => $this->customer_name,
            'customer_address' => $this->customer_address,
        ])->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }

    public function customer()
{
    return $this->belongsTo(Customer::class);
}

}
