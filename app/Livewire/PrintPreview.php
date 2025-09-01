<?php

namespace App\Livewire;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Livewire\Component;

class PrintPreview extends Component
{
    public $invoice;
    public $invoiceItems;

    public function mount($invoiceId)
    {
        $this->invoice = Invoice::findOrFail($invoiceId);
        $this->invoiceItems = InvoiceItem::where('invoice_id', $invoiceId)->get();
    }

    public function printPage()
    {
        $this->dispatch('print-preview');
    }

    public function render()
    {

        $bodyAttributes = 'x-data="{ page: \'addGrn\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
        x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
        :class="{\'dark bg-gray-900\': darkMode === true}"';
        return view('livewire.print-preview')->layout('layouts.app');
    }


}
