<?php

namespace App\Livewire\Supplier;

use App\Models\Entry;
use App\Models\EntryItem;
use App\Models\EntryType;
use App\Models\Ledger;
use Livewire\Attributes\On;
use Livewire\Component;
use App\Models\Supplier; // Assuming you're using Supplier as vendor
use App\Models\VendorBill; // We will create this model
use App\Models\VendorBillPayment; // We will create this model
use Illuminate\Support\Facades\Auth;

class VendorBillCreate extends Component
{
    public $vendors;
    public $vendor_id;
    public $vendorBillPayments = []; // unpaid bills for selected vendor
    public $ledgers;          // list of ledgers for dropdown
    public $selectedLedger;
    public $billAmount;
    public $billMemo;
    public $memo;
    public $totalAmount = 0;

    // Bill general fields
    public $date;
    public $ref_no;
    public $bill_due_date;
    public $terms;
    public $ledger_id;

    public $expenses = [];



    public function mount()
    {
        $this->ledgers = Ledger::all();
        $this->vendors = Supplier::all(); // load suppliers as vendors
        $this->date = now()->format('Y-m-d');
        $this->bill_due_date = now()->addDays(30)->format('Y-m-d'); // default due date 30 days later
    }

    #[On('ledgerSelected')]
    public function setLedger($ledgerId)
    {
        $this->ledger_id = $ledgerId;
        $this->ledger = Ledger::find($ledgerId);
        $this->selectedLedger = $this->ledger_id;
    }


    public function addExpense()
    {
        if ($this->billAmount && $this->selectedLedger) {
            // Add expense to the array
            $ledger = Ledger::find($this->selectedLedger); // Retrieve Ledger instance

            if ($ledger) {
                $this->expenses[] = [
                    'id' => uniqid(),
                    'ledger' => $ledger->name,
                    'ledger_id' => $this->selectedLedger,
                    'amount' => $this->billAmount,
                    'amount_due' => 0.00,
                    'memo' => $this->billMemo ?? '', // Make memo optional
                ];
                // Recalculate total
                $this->calculateTotalAmount();
            } else {
                session()->flash('error', 'Ledger not found.');
            }

            // Reset form fields
            $this->reset(['billAmount', 'billMemo', 'selectedLedger']);
        } else {
            session()->flash('error', 'Please fill out Amount and Ledger fields before adding.');
        }
    }


    public function calculateTotalAmount()
    {
        $this->totalAmount = array_sum(array_column($this->expenses, 'amount'));
    }

    public function removeVendorBill($billId)
    {
        // Find the index of the bill to remove in the $expenses array
        $index = null;
        foreach ($this->expenses as $key => $bill) {
            if ($key == $billId) {  // Compare with index, not id
                $index = $key;
                break;
            }
        }

        // If bill found, remove it from the array
        if ($index !== null) {
            unset($this->expenses[$index]);
            // Re-index the array after removal
            $this->expenses = array_values($this->expenses);

            $this->calculateTotalAmount();
            session()->flash('success', 'Vendor bill removed successfully.');
        }
    }

    protected $rules = [
        'vendor_id' => 'required|exists:suppliers,id',
        'date' => 'required|date',
        // 'amount_due' => 'required|numeric|min:0',
        'bill_due_date' => 'required|date|after_or_equal:date',
    ];

    public function updatedVendorId($value)
    {
        $this->loadvendorBillPayments();
    }

    public function loadvendorBillPayments()
    {
        if ($this->vendor_id) {
            $this->vendorBillPayments = VendorBill::where('vendor_id', $this->vendor_id)
                ->where('amount_due', '>', 0)
                ->get();
        } else {
            $this->vendorBillPayments = [];
        }
    }

    public function removeExpense($index)
    {
        if (isset($this->expenses[$index])) {
            unset($this->expenses[$index]);
            $this->expenses = array_values($this->expenses); // Reindex the array
        }
    }
    public function save()
    {
        $this->validate([
            'vendor_id' => 'required|exists:suppliers,id',
            'date' => 'required|date',
            'bill_due_date' => 'required|date|after_or_equal:date',
        ]);

        if (empty($this->expenses)) {
            // If no expenses, flash an error message and stop the process
            session()->flash('error', 'Please add at least one expense before saving.');
            return;
        }

        // Create vendor bill
        $bill = VendorBill::create([
            'vendor_id' => $this->vendor_id,
            'date' => $this->date,
            'ref_no' => $this->ref_no,
            'bill_due_date' => $this->bill_due_date,
            'terms' => $this->terms,
            'memo' => $this->billMemo,
            'total_amount' => $this->totalAmount,
            'amount_due' => $this->totalAmount,
            'created_by' => auth()->id(),
        ]);

        // Loop over expenses and create vendor bill payments and accounting entries
        foreach ($this->expenses as $expense) {
            // Create Vendor Bill Payment
            VendorBillPayment::create([
                'vendor_bill_id' => $bill->id,
                'ledger_id' => $expense['ledger_id'], // Use ledger_id from expense
                'amount' => $expense['amount'],
                'memo' => $expense['memo'],
                'created_by' => auth()->id(),
            ]);

            // Create accounting entry for each expense
            $entry = Entry::create([
                'entrytype_id' => EntryType::where('label', 'vendor')->value('id'),
                'number' => 1, // You should generate this properly
                'date' => $this->date,
                'narration' => "Vendor Name: " . Supplier::find($this->vendor_id)->name . " and Invoice No: " . $this->ref_no,
                'dr_total' => $expense['amount'], // Debit based on expense amount
                'cr_total' => $expense['amount'], // Credit based on expense amount
                'branch_id' => auth()->user()->branch_id,
            ]);

            // Debit: Expense Ledger (use the ledger_id from the expense)
            EntryItem::create([
                'entry_id' => $entry->id,
                'customer_id' => $this->vendor_id,
                'ledger_id' => $expense['ledger_id'], // Debit from the ledger of the expense
                'dc' => 'D',
                'amount' => $expense['amount'],
                'branch_id' => auth()->user()->branch_id,
            ]);

            // Credit: Accounts Payable Ledger for vendor (should be vendor's ledger_id)
            $vendorLedgerId = Supplier::find($this->vendor_id)->ledger_id;

            EntryItem::create([
                'entry_id' => $entry->id,
                'customer_id' => $this->vendor_id,
                'ledger_id' => 16, // Accounts payable ledger
                'dc' => 'C',
                'amount' => $expense['amount'],
                'branch_id' => auth()->user()->branch_id,
            ]);
        }

        // Set a success message after saving
        session()->flash('success', 'Bill added successfully.');

        // Reset the form and expenses
        $this->reset([
            'vendor_id',
            'ref_no',
            'date',
            'bill_due_date',
            'terms',
            'billMemo',
            'expenses',
            'totalAmount'
        ]);

        // Reload the vendor bill payments to reflect the changes
        $this->loadvendorBillPayments();
    }



    // public function _save()
    // {
    //     $this->validate([
    //         'vendor_id' => 'required|exists:suppliers,id',
    //         'billMemo' => 'nullable|string',
    //         'date' => 'required|date',
    //         'bill_due_date' => 'required|date|after_or_equal:date',

    //     ]);

    //     // Create vendor bill
    //     $bill = VendorBill::create([
    //         'vendor_id' => $this->vendor_id,
    //         'date' => $this->date,
    //         'ref_no' => $this->ref_no,
    //         'bill_due_date' => $this->bill_due_date,
    //         'terms' => $this->terms,
    //         'memo' => $this->billMemo,
    //         'total_amount' => $this->totalAmount,
    //         'created_by' => auth()->id(),
    //     ]);



    //     foreach ($this->expenses as $expense) {
    //         VendorBillPayment::create([
    //             'vendor_bill_id' => $bill->id,
    //             'ledger_id' => $this->ledger_id,
    //             'amount' => $expense['amount'],
    //             'memo' => $expense['memo'],
    //             'created_by' => auth()->id(),
    //         ]);
    //     }




    //     // Create accounting entry: Debit expense ledger, Credit accounts payable for vendor
    //     $entry = Entry::create([
    //         'entrytype_id' => EntryType::where('label', 'vendor')->value('id'),
    //         'number' => 1, // You should generate this properly
    //         'date' => $this->date,
    //         'narration' => "Bill from vendor ID {$this->vendor_id}",
    //         'dr_total' => $this->totalAmount,
    //         'cr_total' => $this->totalAmount,
    //         'branch_id' => auth()->user()->branch_id,
    //     ]);

    //     // Debit: Expense Ledger (selectedLedger)
    //     EntryItem::create([
    //         'entry_id' => $entry->id,
    //         'customer_id' => $this->vendor_id,
    //         'ledger_id' => $this->selectedLedger,
    //         'dc' => 'D',
    //         'amount' => $this->billAmount,
    //         'branch_id' => auth()->user()->branch_id,
    //     ]);

    //     // Credit: Accounts Payable Ledger for vendor (should be vendor's ledger_id)
    //     $vendorLedgerId = Supplier::find($this->vendor_id)->ledger_id;

    //     EntryItem::create([
    //         'entry_id' => $entry->id,
    //         'customer_id' => $this->vendor_id,
    //         'ledger_id' => 16, //Account payble lerdger
    //         'dc' => 'C',
    //         'amount' => $this->billAmount,
    //         'branch_id' => auth()->user()->branch_id,
    //     ]);

    //     // Reload bills
    //     $this->loadvendorBillPayments();

    //     // Reset input fields for new bill entry
    //     $this->reset(['selectedLedger', 'billAmount', 'billMemo', 'ref_no', 'terms']);

    //     session()->flash('message', 'Bill added successfully.');
    // }

    public function render()
    {
        $bodyAttributes = 'x-data="{ page: \'VendorPayment\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
            x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                    $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
            :class="{\'dark bg-gray-900\': darkMode === true}"';

        return view('livewire.supplier.vendor-bill-create')->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
