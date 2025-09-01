<?php

namespace App\Livewire\Invoice;

use App\Models\Customer;
use App\Models\Entry;
use App\Models\EntryItem;
use App\Models\EntryType;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Item;
use App\Models\JobOrder;
use App\Models\Ledger;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\App;


class InvoiceView extends Component
{
    public $invoiceId;
    public $invoice;
    public $status;
    public $assigned;
    public $searchTerm = '';
    public $searchResults = [];
    public $customer;
    public $invoiceItems = [];
    public $originalInvoiceItems = [];
    public $changesMade = false;
    public $total_amount = 0.00;
    public $backedQty = 0;
    public $isPlateBacking = false;
    public $backedPrice = 0.00;
    public $backedTotal = 0.00;
    public $originalBackedPrice;
    public $customer_po_number;

    public function mount($invoiceId)
    {
        // Fetch the invoice with its related customer, eager load the 'customer' relationship
        $this->invoice = Invoice::with('customer', 'order')->find($invoiceId);
        $this->customer_po_number = $this->invoice->order->customer_po_number ?? '';

        if (!$this->invoice) {
            session()->flash('error', 'Invoice not found!');
            //return redirect()->route('invoices.index');
        }

        $this->status = $this->invoice->status;
        $this->customer = Customer::find($this->invoice->customer_id);
        $this->total_amount = $this->invoice->total_amount;

        $invoiceItems = InvoiceItem::where('invoice_id', $invoiceId)->get();
        // $this->backedQty = $invoiceItems->invoice->order->backing_qty
        $this->backedQty = $this->invoice->order->backing_qty;
        $this->isPlateBacking = $this->invoice->order->plate_backing;
        $this->backedTotal = $this->backedPrice * $this->backedQty;
        $this->backedPrice = number_format($this->invoice->backed_plates_price, 2, '.', '');

        $this->invoiceItems = $invoiceItems->map(function ($invoiceItem) {

            return [
                'id' => $invoiceItem->id,
                'item_id' => $invoiceItem->item_id,
                'name' => $invoiceItem->item->item_name,
                'unit_price' => $invoiceItem->unit_price,
                'quantity' => $invoiceItem->quantity,
                'total_price' => $invoiceItem->total_price
            ];
        })->toArray();

        // Clone to track original values
        $this->originalInvoiceItems = $this->invoiceItems;

        $this->originalBackedPrice = $this->backedPrice;
        $this->calculateTotal();
        $this->changesMade = false;
    }



    public function checkForChanges()
    {
        $this->changesMade = false;

        // 1) line‐item changes
        foreach ($this->invoiceItems as $i => $item) {
            if (
                $item['unit_price']  != $this->originalInvoiceItems[$i]['unit_price']
                || $item['total_price'] != $this->originalInvoiceItems[$i]['total_price']
            ) {
                $this->changesMade = true;
                return;
            }
        }

        // 2) plates‐price changed?
        if ((float)$this->backedPrice !== (float)$this->originalBackedPrice) {
            $this->changesMade = true;
        }
    }


    public function updatedInvoiceItems()
    {
        $this->checkForChanges();
    }


    public function viewPrintPreview()
    {

        DB::beginTransaction();
        try {
            // Fetch and update the invoice status
            $invoice = Invoice::find($this->invoiceId);
            $jobOrder = JobOrder::find($invoice->order_id);

            if (!$invoice) {
                throw new \Exception('Invoice not found.');
            }

            if ($invoice->status === 'invoicing') {

                $entryTypeId = EntryType::where('label', 'invoice')->value('id') ?? 4; // Fallback to 'journal'

                $entry = Entry::create([
                    'entrytype_id' => $entryTypeId,
                    'branch_id' => auth()->user()->branch_id,
                    'customer_id' => $invoice->customer->id,
                    'number' => 1,
                    'date' => now(),
                    'narration' => "Invoice {$invoice->invoice_number} for Customer {$invoice->customer->name}",
                    'dr_total' => $invoice->total_amount,
                    'cr_total' => $invoice->total_amount,
                ]);

                $accountsReceivableLedgerId = Ledger::where('name', 'Accounts Receivable')->value('id');
                $salesRevenueLedgerId = Ledger::where('name', 'Sales Revenue')->value('id');
                $vatPayableLedgerId = Ledger::where('name', 'VAT Payable')->value('id');

                // Calculate VAT amount if applicable (simplified example: assume 15%)
                $vatRate = 0.00;
                $vatAmount = round($invoice->total_amount * $vatRate, 2);
                $salesAmount = round($invoice->total_amount - $vatAmount, 2);

                // Debit Accounts Receivable (Customer)
                EntryItem::create([
                    'entry_id' => $entry->id,
                    'customer_id' => $invoice->customer->id,
                    'branch_id' => auth()->user()->branch_id,
                    'ledger_id' => $accountsReceivableLedgerId,
                    'dc' => 'D',
                    'amount' => $invoice->total_amount,
                ]);

                // Credit Sales Revenue
                EntryItem::create([
                    'entry_id' => $entry->id,
                    'customer_id' => $invoice->customer->id,
                    'branch_id' => auth()->user()->branch_id,
                    'ledger_id' => $salesRevenueLedgerId,
                    'dc' => 'C',
                    'amount' => $salesAmount,
                ]);

                // Credit VAT Payable
                EntryItem::create([
                    'entry_id' => $entry->id,
                    'customer_id' => $invoice->customer->id,
                    'branch_id' => auth()->user()->branch_id,
                    'ledger_id' => $vatPayableLedgerId,
                    'dc' => 'C',
                    'amount' => $vatAmount,
                ]);

                if ($invoice) {
                    $invoice->status = 'invoiced';
                    $invoice->created_at = now(); // Update created_at with current date
                    // $invoice->print_count = ($invoice->print_count ?? 0) + 1;
                    $invoice->save();


                    $jobOrder->status = 'invoiced';
                    $jobOrder->save();
                }

                DB::commit();
                session()->flash('success', 'Invoice and accounting entries created successfully!');
                return $this->redirect('/invoices');
            } else {
                // session()->flash('error', 'Invoice already processed!');
                // return $this->redirect('/invoices');
                return $this->redirect('/invoice/print-preview/' . $this->invoiceId, navigate: true);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error creating accounting entries: ' . $e->getMessage());
            $invoice->delete();
        }
        // Redirect to print preview
        // return $this->redirect('/invoice/print-preview/' . $this->invoiceId, navigate: true);
    }


    // public function cancelInvoice(int $invoiceId)
    // {
    //     //DB::beginTransaction();

    //     try {
    //         $invoice = Invoice::findOrFail($invoiceId);
    //         $jobOrder = JobOrder::findOrFail($invoice->order_id);


    //         $arLedgerId = Ledger::where('name', 'Accounts Receivable')->value('id');
    //         $custCreditLedgerId = Ledger::where('name', 'Customer Credit')->value('id');
    //         $salesRevenueLedgerId = Ledger::where('name', 'Sales Revenue')->value('id');
    //         $entryTypeId = EntryType::where('label', 'journal')->value('id');
    //         $vatPayableLedgerId = Ledger::where('name', 'VAT Payable')->value('id');


    //         $reversalEntry = Entry::create([
    //             'entrytype_id' => $entryTypeId,
    //             'branch_id' => $invoice->branch_id,
    //             'customer_id' => $invoice->customer_id,
    //             'number' => 1,
    //             'date' => now(),
    //             'narration' => "Cancel of Invoice {$invoice->invoice_number}",
    //             'dr_total' => $invoice->total_amount,
    //             'cr_total' => $invoice->total_amount,
    //         ]);


    //         if ($invoice->payment_status == 'paid') {
    //             EntryItem::create([
    //                 'entry_id' => $reversalEntry->id,
    //                 'customer_id' => $reversalEntry->customer_id,
    //                 'branch_id' => $reversalEntry->branch_id,
    //                 'ledger_id' => $salesRevenueLedgerId,
    //                 'dc' => 'D',
    //                 'amount' => $reversalEntry->dr_total,
    //             ]);

    //             EntryItem::create([
    //                 'entry_id' => $reversalEntry->id,
    //                 'customer_id' => $reversalEntry->customer_id,
    //                 'branch_id' => $reversalEntry->branch_id,
    //                 'ledger_id' => $custCreditLedgerId,
    //                 'dc' => 'C',
    //                 'amount' => $reversalEntry->dr_total,
    //             ]);

    //         }


    //         if ($invoice->payment_status == 'unpaid') {
    //             EntryItem::create([
    //                 'entry_id' => $reversalEntry->id,
    //                 'customer_id' => $reversalEntry->customer_id,
    //                 'branch_id' => $reversalEntry->branch_id,
    //                 'ledger_id' => $salesRevenueLedgerId,
    //                 'dc' => 'D',
    //                 'amount' => $reversalEntry->dr_total,
    //             ]);

    //             EntryItem::create([
    //                 'entry_id' => $reversalEntry->id,
    //                 'customer_id' => $reversalEntry->customer_id,
    //                 'branch_id' => $reversalEntry->branch_id,
    //                 'ledger_id' => $arLedgerId,
    //                 'dc' => 'C',
    //                 'amount' => $reversalEntry->dr_total,
    //             ]);

    //         }

    //         $invoice->status = 'cancelled';
    //         $invoice->save();

    //         $jobOrder->status = 'cancelled';
    //         $jobOrder->save();

    //         DB::commit();
    //         session()->flash('success', "Invoice {$invoice->invoice_number} cancelled and entries reversed.");
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         session()->flash('error', "Cancellation failed: " . $e->getMessage());
    //     }
    // }




    public function updatedSearchTerm()
    {
        // Searching functionality for invoice-related items, similar to job order view
        if (strlen($this->searchTerm) > 1) {
            $items = Item::where('item_name', 'like', "%{$this->searchTerm}%")
                ->orWhere('item_code', 'like', "%{$this->searchTerm}%")
                ->limit(5)
                ->get();

            $this->searchResults = $items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'item_name' => $item->item_name,
                    'item_code' => $item->item_code,
                    'sku_code' => $item->sku_code,
                    'selling_price' => $item->sales_price,
                    'purchase_price' => $item->purchase_price,
                ];
            })->toArray();
        } else {
            $this->searchResults = [];
        }
    }


    public function updateStatus()
    {
        // Update invoice status
        $this->invoice->update(['status' => $this->status]);
    }

    public function exportPDF()
    {
        $invoice = Invoice::with('invoiceItems.item')->find($this->invoiceId);
        $customer = Customer::find($invoice->customer_id);

        // Update print count in the database
        $invoice->increment('print_count'); // Increments the print count by 1

        $pdf = Pdf::loadView('livewire.invoice.invoice-pdf', compact('invoice', 'customer'));

        // Add watermark if print count > 1
        if ($invoice->print_count > 1) {
            $pdf->getDomPDF()->getCanvas()->set_opacity(0.1); // Set opacity for watermark
            $canvas = $pdf->getDomPDF()->getCanvas();
            $canvas->text(200, 400, 'Duplicated', null, 100); // Position of watermark
        }

        // Rename the PDF with invoice number
        $fileName = 'Invoice_' . $invoice->invoice_number . '.pdf';

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, $fileName);
    }


    public function _exportPDF()
    {
        $invoice = Invoice::with('invoiceItems.item')->find($this->invoiceId);
        $customer = Customer::find($invoice->customer_id);
        $pdf = Pdf::loadView('livewire.invoice.invoice-pdf', compact('invoice', 'customer'));

        // Rename the PDF with invoice number
        $fileName = 'Invoice_' . $invoice->invoice_number . '.pdf';

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, $fileName);
    }

    public function updateUnitPrice($index)
    {
        $this->invoiceItems[$index]['total_price']
            = $this->invoiceItems[$index]['quantity']
            * $this->invoiceItems[$index]['unit_price'];

        $this->calculateTotal();
        $this->checkForChanges();
    }

    public function calculateTotal()
    {
        // 1) figure out the baked‐plates total from whatever the user last typed
        $this->backedTotal = $this->backedPrice * $this->backedQty;

        // 2) line‐item totals
        $lineSum = array_sum(array_column($this->invoiceItems, 'total_price'));

        // 3) grand total = lines + baked‐plates
        $this->total_amount = number_format($lineSum + $this->backedTotal, 2, '.', '');

        // (optionally mark “dirty” if you want the Apply button to light up as soon as they type)
        $this->changesMade = true;
    }

    public function updateInvoiceItems()
    {
        $invoice = Invoice::findOrFail($this->invoiceId);

        if ($invoice->status !== 'invoicing') {
            session()->flash('error', 'Only invoices with status "invoicing" can be updated.');
            return;
        }

        // 1) Update each invoice-item line
        foreach ($this->invoiceItems as $item) {
            InvoiceItem::where('id', $item['id'])->update([
                'unit_price' => $item['unit_price'],
                'total_price' => $item['total_price'],
            ]);
        }

        // 2) Now update the baked-plates price on the invoice
        $invoice->update([
            'total_amount' => $this->total_amount,
            'amount_due' => $this->total_amount,
            'backed_plates_price' => $this->backedPrice,
        ]);

        session()->flash('success', 'Invoice updated successfully!');
        $this->changesMade = false;
    }



    public function updatedTotalAmount($value)
    {

        $this->total_amount = number_format($value, 2, '.', '') + $this->backedPrice;
    }

    public function updateBackedPrice()
    {
        // 1) strip commas and cast to float
        $clean = (float) str_replace(',', '', $this->backedPrice);

        // 2) re-format with exactly two decimal places
        $this->backedPrice = number_format($clean, 2, '.', '');

        // 3) re-calculate grand total
        $this->calculateTotal();

        // 4) re-check for any edits
        $this->checkForChanges();
    }


    public function invoicePrintPreview($invoiceId)
    {


        return redirect()->route('invoice.print-preview', ['invoiceId' => $invoiceId]);
    }

    public function printInvoice()
    {
        $this->dispatch('print-preview');
    }

    public function updatePoNumber()
    {
        $invoice = Invoice::findOrFail($this->invoiceId);

        if ($invoice->status !== 'invoicing') {
            session()->flash('error', 'Only invoices with status "invoicing" can be updated.');
            return;
        }

        // Update the customer PO number
        $invoice->order->update(['customer_po_number' => $this->customer_po_number]);

        session()->flash('success', 'Customer PO number updated successfully!');
    }

    public function render()
    {

        $bodyAttributes = 'x-data="{ page: \'invoiceView\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
        x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
        :class="{\'dark bg-gray-900\': darkMode === true}"';

        return view('livewire.invoice.invoice-view', ['invoice' => $this->invoice, 'invoiceItems' => $this->invoiceItems])->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
