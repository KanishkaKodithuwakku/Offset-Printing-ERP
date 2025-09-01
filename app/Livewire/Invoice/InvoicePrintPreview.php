<?php

namespace App\Livewire\Invoice;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\JobOrder;
use Livewire\Component;

class InvoicePrintPreview extends Component
{
    public $invoice;
    public $invoiceItems;
    public $backedPrice;
    public $backedQty;
    public $backedTotal;
    public $printCount;
    public $showModal = false;
    public $customerTotalAmount;

    public $printedCount = 0;

    public function mount($invoiceId)
    {
        $this->invoice = Invoice::find($invoiceId);
        $this->printedCount = $this->invoice->print_count;
        $this->invoiceItems = InvoiceItem::where('invoice_id', $invoiceId)->get();
        $this->backedPrice = $this->invoice->backed_plates_price;
        $this->backedQty = $this->invoice->order->backing_qty;
        $this->backedTotal = $this->backedPrice * $this->backedQty;
        $this->printCount = $this->invoice->print_count > 0 ? 'DUPLICATE COPY - ' . $this->invoice->print_count : '';
        $backed_plates_price = $this->backedPrice*$this->backedQty;
        $this->customerTotalAmount = $this->getCustomerTotalAmount($this->invoice->customer_id)+$backed_plates_price;
    }

    public function getCustomerTotalAmount($customerId)
    {
        return Invoice::where('customer_id', $customerId)
            ->sum('amount_due');

    }

    // public function printPage()
    // {
    //     Invoice::where('id', $this->invoice->id)
    //         ->increment('print_count', 1);

    //     Invoice::where('id', $this->invoice->id)
    //         ->update([
    //             'status' => 'invoiced'
    //         ]);
    //     JobOrder::where('id', $this->invoice->order->id)
    //         ->update([
    //             'status' => 'invoiced'
    //         ]);

    //     $invoice = Invoice::find($this->invoice->id);
    //     $this->printCount = $invoice->print_count > 0 ? 'DUPLICATE COPY - ' . $invoice->print_count : '';
    //     $this->showModal = false;
    //     if ($this->showModal === false) {
    //         $this->dispatch('print-preview');
    //     }

    // }

    public function printPage()
    {
        $invoice = Invoice::find($this->invoice->id);

        // if ($invoice->print_count > 1) {
        //     session()->flash('error', 'This invoice has already been printed.');
        //     return;
        // }

        $invoice->increment('print_count');
        $invoice->update(['status' => 'invoiced']);
        $this->mount($this->invoice->id);

        JobOrder::where('id', $invoice->order->id)->update(['status' => 'invoiced']);

        $this->invoice = Invoice::find($invoice->id);

        if ($invoice->print_count > 1) {
            $this->printCount = 'DUPLICATE COPY - ' . $invoice->print_count;
        }
        $this->showModal = false;

        if ($this->showModal === false) {
            $this->dispatch('print-preview');
        }

    }


    public function incrementPrintCount()
    {
        $invoice = Invoice::find($this->invoice->id);

        if ($invoice) {
            $invoice->increment('print_count');
            $invoice->save();

            // Refresh value from DB
            $this->printedCount = $invoice->print_count;
            $this->invoice = Invoice::find($this->invoice->id);
        }


    }


    public function confirmPrint()
    {
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }


    public function render()
    {
        $bodyAttributes = 'x-data="{ page: \'addGrn\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
        x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
        :class="{\'dark bg-gray-900\': darkMode === true}"';

        return view('livewire.invoice.invoice-print', [
            'invoice' => $this->invoice,
            'invoiceItems' => $this->invoiceItems,
            'customerTotalAmount' => $this->customerTotalAmount
        ])->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
