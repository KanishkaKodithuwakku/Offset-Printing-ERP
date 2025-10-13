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
use App\Models\OtherExpense;
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
    public $availableOtherExpenses = [];
    public $selectedOtherExpenses = [];

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

        $invoiceItems = InvoiceItem::with(['item', 'expense'])->where('invoice_id', $invoiceId)->get();
        // $this->backedQty = $invoiceItems->invoice->order->backing_qty
        $this->backedQty = $this->invoice->order->backing_qty;
        $this->isPlateBacking = $this->invoice->order->plate_backing;
        $this->backedTotal = $this->backedPrice * $this->backedQty;
        $this->backedPrice = number_format($this->invoice->backed_plates_price, 2, '.', '');

        $this->invoiceItems = $invoiceItems->map(function ($invoiceItem) {
            if ($invoiceItem->item) {
                // Regular invoice item
                return [
                    'id' => $invoiceItem->id,
                    'item_id' => $invoiceItem->item_id,
                    'name' => $invoiceItem->item->item_name,
                    'unit_price' => $invoiceItem->unit_price,
                    'quantity' => $invoiceItem->quantity,
                    'total_price' => $invoiceItem->total_price
                ];
            } elseif ($invoiceItem->expense) {
                // Other expense item
                return [
                    'id' => $invoiceItem->id,
                    'expense_id' => $invoiceItem->expense_id,
                    'name' => $invoiceItem->expense->expense_name,
                    'unit_price' => $invoiceItem->unit_price,
                    'quantity' => $invoiceItem->quantity,
                    'total_price' => $invoiceItem->total_price,
                    'is_other_expense' => true
                ];
            }
            return null;
        })->filter()->values()->toArray();

        // Clone to track original values
        $this->originalInvoiceItems = $this->invoiceItems;

        $this->originalBackedPrice = $this->backedPrice;
        $this->calculateTotal();
        $this->changesMade = false;

        // Load available other expenses
        $this->loadAvailableOtherExpenses();
    }



    public function checkForChanges()
    {
        $this->changesMade = false;

        // 1) line‐item changes
        foreach ($this->invoiceItems as $i => $item) {
            // Check if we have a corresponding original item
            if (isset($this->originalInvoiceItems[$i])) {
                $originalItem = $this->originalInvoiceItems[$i];

                // Compare based on item type
                if (isset($item['is_other_expense']) && $item['is_other_expense']) {
                    // For other expenses, compare expense_id, quantity, unit_price, total_price
                    if ($item['expense_id'] != ($originalItem['expense_id'] ?? null) ||
                        $item['quantity'] != ($originalItem['quantity'] ?? 0) ||
                        $item['unit_price'] != ($originalItem['unit_price'] ?? 0) ||
                        $item['total_price'] != ($originalItem['total_price'] ?? 0)) {
                        $this->changesMade = true;
                        return;
                    }
                } else {
                    // For regular items, compare unit_price and total_price
                    if ($item['unit_price'] != ($originalItem['unit_price'] ?? 0) ||
                        $item['total_price'] != ($originalItem['total_price'] ?? 0)) {
                        $this->changesMade = true;
                        return;
                    }
                }
            } else {
                // New item added
                $this->changesMade = true;
                return;
            }
        }

        // Check if items were removed
        if (count($this->invoiceItems) != count($this->originalInvoiceItems)) {
            $this->changesMade = true;
            return;
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

                // Credit Limit Validation
                $customer = $invoice->customer;

                // Calculate customer's current outstanding balance (excluding this invoice)
                $currentOutstanding = Invoice::where('customer_id', $customer->id)
                    ->where('status', '!=', 'cancelled')
                    ->where('amount_due', '>', 0)
                    ->where('id', '!=', $invoice->id) // Exclude current invoice
                    ->sum('amount_due');

                // Calculate total amount including this invoice (including other expenses)
                $allInvoiceItems = InvoiceItem::where('invoice_id', $invoice->id)->get();
                $calculatedTotalAmount = $allInvoiceItems->sum('total_price') + ($invoice->backed_plates_price * $invoice->order->backing_qty);
                $totalAmountWithThisInvoice = $currentOutstanding + $calculatedTotalAmount;

                // Check Credit Limit 2 (Hard limit - prevent invoice generation)
                if ($customer->credit_limit_2_amount && $totalAmountWithThisInvoice > $customer->credit_limit_2_amount) {
                    session()->flash('warning', "Credit limit 2 has been exceeded Rs. " . number_format($customer->credit_limit_2_amount, 2) . "");
                    DB::rollBack();
                    return;
                }

                // Check Credit Limit 1 (Soft limit - show warning but allow generation)
                if ($customer->credit_limit_1_amount && $totalAmountWithThisInvoice > $customer->credit_limit_1_amount) {
                    session()->flash('warning', "Credit limit 1 exceeded {" . number_format($customer->credit_limit_1_amount, 2) . "}");
                }

                $entryTypeId = EntryType::where('label', 'invoice')->value('id') ?? 4; // Fallback to 'journal'

                $entry = Entry::create([
                    'entrytype_id' => $entryTypeId,
                    'branch_id' => auth()->user()->branch_id,
                    'customer_id' => $invoice->customer->id,
                    'number' => 1,
                    'date' => now(),
                    'narration' => "Invoice {$invoice->invoice_number} for Customer {$invoice->customer->name}",
                    'dr_total' => $calculatedTotalAmount,
                    'cr_total' => $calculatedTotalAmount,
                ]);

                $accountsReceivableLedgerId = Ledger::where('name', 'Accounts Receivable')->value('id');
                $salesRevenueLedgerId = Ledger::where('name', 'Sales Revenue')->value('id');
                $vatPayableLedgerId = Ledger::where('name', 'VAT Payable')->value('id');

                // Calculate VAT amount if applicable (simplified example: assume 15%)
                $vatRate = 0.00;
                $vatAmount = round($calculatedTotalAmount * $vatRate, 2);
                $salesAmount = round($calculatedTotalAmount - $vatAmount, 2);

                // Debit Accounts Receivable (Customer)
                EntryItem::create([
                    'entry_id' => $entry->id,
                    'customer_id' => $invoice->customer->id,
                    'branch_id' => auth()->user()->branch_id,
                    'ledger_id' => $accountsReceivableLedgerId,
                    'dc' => 'D',
                    'amount' => $calculatedTotalAmount,
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

                // Other expenses should already be saved by "Apply Changes" button
                // No need to duplicate the saving here

                if ($invoice) {
                    // Recalculate total amount based on all invoice items (including other expenses)
                    $allInvoiceItems = InvoiceItem::where('invoice_id', $invoice->id)->get();
                    $totalAmount = $allInvoiceItems->sum('total_price') + ($invoice->backed_plates_price * $invoice->order->backing_qty);

                    $invoice->update([
                        'status' => 'invoiced',
                        'total_amount' => $totalAmount,
                        'amount_due' => $totalAmount,
                        'created_at' => now()
                    ]);

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
        $invoice = Invoice::with(['invoiceItems.item', 'invoiceItems.expense'])->find($this->invoiceId);
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
        $invoice = Invoice::with(['invoiceItems.item', 'invoiceItems.expense'])->find($this->invoiceId);
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

        // Don't automatically mark as changed - let checkForChanges handle this
    }

    public function updateInvoiceItems()
    {
        try {
            $invoice = Invoice::findOrFail($this->invoiceId);

            if ($invoice->status !== 'invoicing') {
                session()->flash('error', 'Only invoices with status "invoicing" can be updated.');
                return;
            }

            \Log::info('UpdateInvoiceItems called', [
                'invoice_id' => $this->invoiceId,
                'invoice_items_count' => count($this->invoiceItems),
                'invoice_items' => $this->invoiceItems
            ]);

            DB::beginTransaction();

            $otherExpensesProcessed = 0;
            $regularItemsProcessed = 0;

            // 1) Update each existing invoice-item line
            foreach ($this->invoiceItems as $item) {
                if (isset($item['id']) && $item['id']) {
                    // Update existing invoice item
                    InvoiceItem::where('id', $item['id'])->update([
                        'unit_price' => $item['unit_price'],
                        'total_price' => $item['total_price'],
                    ]);
                    $regularItemsProcessed++;
                } elseif (isset($item['is_other_expense']) && $item['is_other_expense']) {
                    // Handle other expenses - ensure expense_id exists
                    if (!isset($item['expense_id']) || !$item['expense_id']) {
                        \Log::error('Other expense item missing expense_id', ['item' => $item]);
                        continue;
                    }

                    // Ensure we have valid quantity and prices
                    $quantity = isset($item['quantity']) ? (int)$item['quantity'] : 1;
                    $unitPrice = isset($item['unit_price']) ? (float)$item['unit_price'] : 0;
                    $totalPrice = isset($item['total_price']) ? (float)$item['total_price'] : ($quantity * $unitPrice);

                    $existingItem = InvoiceItem::where('invoice_id', $invoice->id)
                        ->where('item_id', null)
                        ->where('expense_id', $item['expense_id'])
                        ->first();

                    if ($existingItem) {
                        // Update existing other expense item
                        $existingItem->update([
                            'quantity' => $quantity,
                            'unit_price' => $unitPrice,
                            'total_price' => $totalPrice,
                        ]);
                        \Log::info('Updated existing other expense', [
                            'expense_id' => $item['expense_id'],
                            'quantity' => $quantity,
                            'unit_price' => $unitPrice,
                            'total_price' => $totalPrice
                        ]);
                    } else {
                        // Create new invoice item for other expense
                        $newItem = InvoiceItem::create([
                            'invoice_id' => $invoice->id,
                            'item_id' => null,
                            'expense_id' => $item['expense_id'],
                            'quantity' => $quantity,
                            'unit_price' => $unitPrice,
                            'total_price' => $totalPrice,
                        ]);
                        \Log::info('Created new other expense invoice item', [
                            'expense_id' => $item['expense_id'],
                            'quantity' => $quantity,
                            'unit_price' => $unitPrice,
                            'total_price' => $totalPrice,
                            'invoice_item_id' => $newItem->id
                        ]);
                    }
                    $otherExpensesProcessed++;
                }
            }

            \Log::info('Processing complete', [
                'regular_items_processed' => $regularItemsProcessed,
                'other_expenses_processed' => $otherExpensesProcessed
            ]);

            // 2) Now update the baked-plates price on the invoice
            $invoice->update([
                'total_amount' => $this->total_amount,
                'amount_due' => $this->total_amount,
                'backed_plates_price' => $this->backedPrice,
            ]);

            DB::commit();
            session()->flash('success', 'Invoice updated successfully! Regular items: ' . $regularItemsProcessed . ', Other expenses: ' . $otherExpensesProcessed);
            $this->changesMade = false;

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error updating invoice: ' . $e->getMessage());
            \Log::error('UpdateInvoiceItems Error: ' . $e->getMessage(), [
                'invoice_id' => $this->invoiceId,
                'invoice_items' => $this->invoiceItems,
                'trace' => $e->getTraceAsString()
            ]);
        }
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

    public function loadAvailableOtherExpenses()
    {
        $this->availableOtherExpenses = OtherExpense::orderBy('expense_name')->get()->toArray();
    }

    public function addSelectedExpenses()
    {
        if (empty($this->selectedOtherExpenses)) {
            session()->flash('error', 'Please select at least one expense to add.');
            return;
        }

        \Log::info('Adding selected expenses', [
            'selected_expenses' => $this->selectedOtherExpenses,
            'current_invoice_items_count' => count($this->invoiceItems)
        ]);

        foreach ($this->selectedOtherExpenses as $expenseId) {
            $expense = OtherExpense::find($expenseId);
            if ($expense) {
                // Check if expense is already added
                $alreadyExists = collect($this->invoiceItems)->contains('expense_id', $expenseId);
                if (!$alreadyExists) {
                    $newItem = [
                        'id' => null,
                        'item_id' => null,
                        'expense_id' => $expense->id,
                        'name' => $expense->expense_name,
                        'unit_price' => $expense->price,
                        'quantity' => 1,
                        'total_price' => $expense->price,
                        'is_other_expense' => true
                    ];

                    $this->invoiceItems[] = $newItem;

                    \Log::info('Added other expense to invoice items', [
                        'expense_id' => $expense->id,
                        'expense_name' => $expense->expense_name,
                        'price' => $expense->price
                    ]);
                } else {
                    \Log::info('Expense already exists in invoice items', ['expense_id' => $expenseId]);
                }
            } else {
                \Log::error('Expense not found', ['expense_id' => $expenseId]);
            }
        }

        $this->selectedOtherExpenses = [];
        $this->calculateTotal();
        $this->changesMade = true;

        \Log::info('Finished adding expenses', [
            'final_invoice_items_count' => count($this->invoiceItems),
            'changes_made' => $this->changesMade
        ]);

        session()->flash('success', 'Selected expenses added to invoice successfully!');
    }

    public function refreshInvoiceItems()
    {
        // Re-index the array to ensure proper indexing
        $this->invoiceItems = array_values($this->invoiceItems);
    }

    public function removeOtherExpense($index)
    {
        if (isset($this->invoiceItems[$index]) && isset($this->invoiceItems[$index]['is_other_expense'])) {
            unset($this->invoiceItems[$index]);
            $this->refreshInvoiceItems(); // Use the refresh method
            $this->calculateTotal();
            $this->changesMade = true;
            session()->flash('success', 'Expense removed from invoice successfully!');
        }
    }

    public function updateOtherExpenseQuantity($index)
    {
        try {
            // Validate that the index exists and the item is an other expense
            if (!isset($this->invoiceItems[$index]) || !isset($this->invoiceItems[$index]['is_other_expense'])) {
                session()->flash('error', 'Invalid item selected for quantity update.');
                return;
            }

            $quantity = (int)$this->invoiceItems[$index]['quantity'];

            // Ensure minimum quantity of 1
            if ($quantity < 1) {
                $quantity = 1;
                $this->invoiceItems[$index]['quantity'] = $quantity;
            }

            // Update total price based on unit price and quantity
            $this->invoiceItems[$index]['total_price'] = $this->invoiceItems[$index]['unit_price'] * $quantity;

            $this->calculateTotal();
            $this->changesMade = true;

        } catch (\Exception $e) {
            session()->flash('error', 'Error updating quantity: ' . $e->getMessage());
        }
    }

    public function updateOtherExpenseQuantityById($expenseId, $quantity)
    {
        try {
            // Find the item by expense ID and update it
            foreach ($this->invoiceItems as $index => $item) {
                if (isset($item['expense_id']) && $item['expense_id'] == $expenseId && isset($item['is_other_expense'])) {
                    $quantity = (int)$quantity;

                    // Ensure minimum quantity of 1
                    if ($quantity < 1) {
                        $quantity = 1;
                    }

                    // Update the quantity and total price
                    $this->invoiceItems[$index]['quantity'] = $quantity;
                    $this->invoiceItems[$index]['total_price'] = $this->invoiceItems[$index]['unit_price'] * $quantity;

                    \Log::info('Updated other expense quantity', [
                        'expense_id' => $expenseId,
                        'new_quantity' => $quantity,
                        'unit_price' => $this->invoiceItems[$index]['unit_price'],
                        'new_total_price' => $this->invoiceItems[$index]['total_price']
                    ]);

                    $this->calculateTotal();
                    $this->changesMade = true;
                    return;
                }
            }

            session()->flash('error', 'Expense not found for quantity update.');

        } catch (\Exception $e) {
            session()->flash('error', 'Error updating quantity: ' . $e->getMessage());
        }
    }

    public function removeOtherExpenseById($expenseId)
    {
        try {
            // Find and remove the item by expense ID
            foreach ($this->invoiceItems as $index => $item) {
                if (isset($item['expense_id']) && $item['expense_id'] == $expenseId && isset($item['is_other_expense'])) {
                    // If the item has an ID, it means it was saved to database, so delete it
                    if (isset($item['id']) && $item['id']) {
                        InvoiceItem::where('id', $item['id'])->delete();
                    }

                    unset($this->invoiceItems[$index]);
                    $this->refreshInvoiceItems(); // Use the refresh method
                    $this->calculateTotal();
                    $this->changesMade = true;
                    session()->flash('success', 'Expense removed from invoice successfully!');
                    return;
                }
            }

            session()->flash('error', 'Expense not found for removal.');

        } catch (\Exception $e) {
            session()->flash('error', 'Error removing expense: ' . $e->getMessage());
        }
    }

    public function render()
    {

        $bodyAttributes = 'x-data="{ page: \'invoiceView\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
        x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
        :class="{\'dark bg-gray-900\': darkMode === true}"';

        return view('livewire.invoice.invoice-view', [
            'invoice' => $this->invoice,
            'invoiceItems' => $this->invoiceItems,
            'availableOtherExpenses' => $this->availableOtherExpenses
        ])->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
