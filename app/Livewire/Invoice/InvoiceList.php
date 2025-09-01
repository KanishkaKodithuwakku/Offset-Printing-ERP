<?php

namespace App\Livewire\Invoice;

use App\Models\Adjustment;
use App\Models\AdjustmentItem;
use App\Models\Entry;
use App\Models\EntryItem;
use App\Models\EntryType;
use App\Models\Invoice;
use App\Models\JobOrder;
use App\Models\Ledger;
use App\Models\Stock;
use App\Models\User;
use DB;
use Livewire\Component;
use Livewire\WithPagination;

class InvoiceList extends Component
{
    use WithPagination;

    public $statusFilter = '';
    public $searchTerm = '';
    public $authUser;
    public $role;
    public $status;
    public $startDate;
    public $endDate;
    public $user;

    public $cancelReason;

    public function updatingStartDate()
    {
        $this->resetPage();
    }
    public function updatingEndDate()
    {
        $this->resetPage();
    }


    public function mount()
    {
        $this->authUser = auth()->user();
        $this->role = $this->authUser->mode;
    }
    public function viewInvoice($invoiceId)
    {
        $invoice = Invoice::find($invoiceId);
        $this->status = $invoice->status;
        return redirect()->route('invoice.view', ['invoiceId' => $invoiceId]);
    }
    // Delete function
    public function deleteInvoice(Invoice $invoice)
    {
        if (!$invoice) {
            session()->flash('error', 'Invoice not found.');
            return;
        }

        if (!in_array($invoice->status, ['invoicing', 'invoiced'])) {
            session()->flash('error', 'Only invoices with status invoicing or invoiced can be deleted.');
            return;
        }

        if ($invoice->status === 'invoicing' || $invoice->status === 'invoiced') {
            // Permanently delete if status is 'invoicing'
            $invoice->forceDelete();
            session()->flash('success', 'Invoice permanently deleted successfully!');
        } else {
            // Soft delete otherwise
            //$invoice->delete();
            session()->flash('success', 'Invoice soft deleted successfully!');
        }
    }
    public function updatingSearchTerm()
    {
        $this->resetPage();
    }
    public function updatingStatusFilter()
    {
        $this->resetPage();
    }


    public function cancelInvoice(int $invoiceId)
    {
        DB::beginTransaction();

        try {
            $invoice = Invoice::with('invoiceItems.item')->findOrFail($invoiceId);
            $jobOrder = JobOrder::findOrFail($invoice->order_id);


            $arLedgerId = Ledger::where('name', 'Accounts Receivable')->value('id');
            $custCreditLedgerId = Ledger::where('name', 'Customer Credit')->value('id');
            $salesRevenueLedgerId = Ledger::where('name', 'Sales Revenue')->value('id');
            $entryTypeId = EntryType::where('label', 'journal')->value('id');
            $vatPayableLedgerId = Ledger::where('name', 'VAT Payable')->value('id');


            $reversalEntry = Entry::create([
                'entrytype_id' => $entryTypeId,
                'branch_id' => $invoice->branch_id,
                'customer_id' => $invoice->customer_id,
                'number' => 1,
                'date' => now(),
                'narration' => "Cancel of Invoice {$invoice->invoice_number}",
                'dr_total' => $invoice->total_amount,
                'cr_total' => $invoice->total_amount,
            ]);


            if ($invoice->payment_status == 'paid') {
                EntryItem::create([
                    'entry_id' => $reversalEntry->id,
                    'customer_id' => $reversalEntry->customer_id,
                    'branch_id' => $reversalEntry->branch_id,
                    'ledger_id' => $salesRevenueLedgerId,
                    'dc' => 'D',
                    'amount' => $reversalEntry->dr_total,
                ]);

                EntryItem::create([
                    'entry_id' => $reversalEntry->id,
                    'customer_id' => $reversalEntry->customer_id,
                    'branch_id' => $reversalEntry->branch_id,
                    'ledger_id' => $custCreditLedgerId,
                    'dc' => 'C',
                    'amount' => $reversalEntry->dr_total,
                ]);
            }


            if ($invoice->payment_status == 'unpaid') {
                EntryItem::create([
                    'entry_id' => $reversalEntry->id,
                    'customer_id' => $reversalEntry->customer_id,
                    'branch_id' => $reversalEntry->branch_id,
                    'ledger_id' => $salesRevenueLedgerId,
                    'dc' => 'D',
                    'amount' => $reversalEntry->dr_total,
                ]);

                EntryItem::create([
                    'entry_id' => $reversalEntry->id,
                    'customer_id' => $reversalEntry->customer_id,
                    'branch_id' => $reversalEntry->branch_id,
                    'ledger_id' => $arLedgerId,
                    'dc' => 'C',
                    'amount' => $reversalEntry->dr_total,
                ]);
            }

            $invoice->update(['status' => 'cancelled', 'deleted_by' => $this->authUser->id, 'cancel_reason' => $this->cancelReason]);
            $adjustment = Adjustment::create([
                'reason' => 'Invoice Cancelled - #' . $invoice->invoice_number,
                'status' => 'pending', // or 'approved' if auto
                'created_by' => auth()->id(),
            ]);

            // dd($invoice->invoiceItems);

            foreach ($invoice->invoiceItems as $invoiceItem) {
                $stockBalance = Stock::where('items_id', $invoiceItem->item_id)->sum('quantity') ?? 0;

                AdjustmentItem::create([
                    'adjustment_id' => $adjustment->id,
                    'item_id' => $invoiceItem->item_id,
                    'pre_qty' => $stockBalance,
                    'quantity' => $invoiceItem->quantity, // reversing quantity
                    'remark' => 'Reversed due to Invoice #' . $invoice->invoice_number,
                ]);
            }

            $jobOrder->status = 'cancelled';
            $jobOrder->save();

            DB::commit();
            session()->flash('success', "Invoice {$invoice->invoice_number} cancelled and entries reversed.");
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', "Cancellation failed: " . $e->getMessage());
        }
    }


    public function render()
    {
        // Start query builder
        $invoicesQuery = Invoice::query();

        // Filter by statusFilter if set, else default to these statuses based on role or logic
        if ($this->statusFilter) {
            $invoicesQuery->where('status', $this->statusFilter);
        } else {
            // Example: default statuses to show, adjust as needed
            $invoicesQuery->whereIn('status', ['invoicing', 'invoiced', 'cancelled']);
        }

        // Role-based filtering example (optional, like in JobOrderList)
        if ($this->role == 'billing') {
            $invoicesQuery->whereIn('status', ['invoicing', 'invoiced', 'cancelled']);
        }
        // Add more role-based filters here if needed

        // Search filter
        // if ($this->searchTerm) {
        //     $invoicesQuery->where(function ($query) {
        //         $query->where('invoice_number', 'like', "%{$this->searchTerm}%")
        //             ->orWhereHas('customer', function ($q) {
        //                 $q->where('name', 'like', "%{$this->searchTerm}%");
        //             });
        //     });
        // }

        if ($this->searchTerm) {
            $invoicesQuery->where(function ($query) {
                $query->where('invoice_number', 'like', "%{$this->searchTerm}%")
                    ->orWhereHas('customer', function ($q) {
                        $q->where('name', 'like', "%{$this->searchTerm}%");
                    })
                    ->orWhereHas('order', function ($q) {
                        $q->where('job_number', 'like', "%{$this->searchTerm}%")
                            ->orWhere('customer_po_number', 'like', "%{$this->searchTerm}%");
                    });
            });
        }

        // Date filters
        if ($this->startDate) {
            $invoicesQuery->whereDate('created_at', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $invoicesQuery->whereDate('created_at', '<=', $this->endDate);
        }


        if ($this->user) {
            $invoicesQuery->where('user_id', '=', $this->user);
        }

        // Ordering
        if ($this->statusFilter === 'cancelled') {
            // For cancelled invoices, order by updated_at (cancellation date) descending
            $invoicesQuery->orderBy('updated_at', 'desc');
        } else {
            // For other statuses, use the original ordering
            $invoicesQuery->orderByRaw("FIELD(status, 'invoicing', 'invoiced','cancelled')")
                ->orderBy('created_at', 'desc');
        }

        // Paginate results after building query
        $invoices = $invoicesQuery->paginate(25);

        // Append filters to pagination links
        $invoices->appends([
            'searchTerm' => $this->searchTerm,
            'statusFilter' => $this->statusFilter,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
        ]);

        $bodyAttributes = 'x-data="{ page: \'invoiceList\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
        x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
        :class="{\'dark bg-gray-900\': darkMode === true}"';

        return view('livewire.invoice.invoice-list', ['invoices' => $invoices, 'users' => User::all()])
            ->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
