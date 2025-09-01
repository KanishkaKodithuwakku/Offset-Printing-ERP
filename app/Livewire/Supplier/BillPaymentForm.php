<?php

namespace App\Livewire\Supplier;

use App\Models\Bank;
use App\Models\Entry;
use App\Models\EntryItem;
use App\Models\EntryType;
use App\Models\Ledger;
use App\Models\Supplier;
use Livewire\Attributes\On;
use Livewire\Component;
use App\Models\VendorBill;
use App\Models\VendorBillPayment;
use App\Models\BankAccount;
use App\Models\VendorPayment;
use App\Helpers\NumberGenerator;
use Illuminate\Support\Carbon;

class BillPaymentForm extends Component
{
    public $dueDateFilter;
    public bool $applyDueFilter = false;
    public bool   $showAllBills   = false;
    public $selectedBills;
    public $paymentType = 'cash';
    public $chequeNumber;
    public $bankAccountId;
    public $paymentAmount;
    public $paymentDate;
    public $payment_method;
    public $check_number;
    public $check_date;
    public $remark;
    public $payment_voucher_no;
    public $ledger_id =  null;
    public $bills = [];
    public $filterBy = '';
    public $selectedVendor = '';
    public $selectedStatus = '';
    public $sortBy = 'bill_due_date';
    public $vendors = [];
    public $statuses = ['open', 'paid']; // Example
    public $highlightedBill = null;
    public $highlightedBillId = null;
    public $totalAmountToPay = 0;
    public $validForm = false;

    public function mount()
    {
        $this->applyDueFilter = false;
        $this->showAllBills    = false;
        $this->selectedBills = []; // Initialize selectedBills to an empty array
        $this->loadBills(); // Load all the bills
        $this->paymentDate = now()->toDateString();
        $this->dueDateFilter    = Carbon::today()->toDateString();
        $this->vendors = Supplier::all();
    }


    public function updated($field)
    {
        $this->validForm = $this->validateOnly($field);
    }

    public function resetFilter()
    {
        $this->dueDateFilter = Carbon::today()->toDateString();
        $this->loadBills();
    }

    public function updatedShowAllBills($value)
    {
        if ($value) {
            // if they check "show all", clear the other filters
            $this->applyDueFilter  = false;
            $this->dueDateFilter  = Carbon::today()->toDateString();
            $this->selectedVendor  = '';
        }
        $this->loadBills();
    }



    #[On('ledgerSelected')]
    public function setLedger($ledgerId)
    {
        $this->ledger_id = $ledgerId;
        // $this->ledger = Ledger::find($ledgerId);
    }

    public function updatedApplyDueFilter($value)
    {
        if (! $value) {
            // when they uncheck, reset the date picker to today
            $this->dueDateFilter = Carbon::today()->toDateString();
        }
        $this->loadBills();
    }

    public function updatedDueDateFilter($value)
    {
        $this->loadBills();
    }
    public function updatedSelectedVendor($value)
    {
        $this->loadBills();
    }
    public function updatedSortBy($value)
    {
        $this->loadBills();
    }

    public function loadBills()
    {
        $query = VendorBill::with(['vendor', 'payments'])->where('payment_status', 'unpaid');

        // Add filter conditions as needed
        if ($this->applyDueFilter && $this->dueDateFilter) {
            $query->whereDate('bill_due_date', '<=', $this->dueDateFilter);
        }

        if ($this->selectedVendor) {
            $query->where('vendor_id', $this->selectedVendor);
        }

        if ($this->sortBy) {
            $query->orderBy($this->sortBy);
        }

        // Get the bills and calculate amount_to_pay
        $this->bills = $query->get()->map(function ($bill) {
            $totalPayments = $bill->payments->sum('total_amount'); // Calculate total payments made
            $bill->amount_to_pay = max(0, $bill->total_amount - $totalPayments); // Calculate amount to pay
            return $bill;
        });

        $this->calculateTotalAmountToPay();
    }


    public function toggleSelectedBill($billId)
    {
        // Find the bill in the bills collection to get the amount_to_pay
        $bill = VendorBill::find($billId);
        $amountToPay = $bill ? $bill->total_amount : 0;

        // Initialize selectedBills if it's null
        $this->selectedBills = $this->selectedBills ?? [];

        // If the bill is already selected, remove it from selectedBills
        if (array_key_exists($billId, $this->selectedBills)) {
            unset($this->selectedBills[$billId]);
        }
        // Otherwise, add it to selectedBills with the amount_to_pay
        else {
            $this->selectedBills[$billId] = $amountToPay;
        }

        // Recalculate the total amount to pay
        $this->calculateTotalAmountToPay();
    }


    public function calculateTotalAmountToPay()
    {
        $bills = is_array($this->selectedBills) ? $this->selectedBills : [];
        $this->totalAmountToPay = array_sum($bills);
    }

    public function updatedSelectedBills()
    {
        $this->loadBills();  // Recalculate total amount to pay
    }

    public $rules = [
    'paymentType' => 'required|in:cash,cheque',
    'chequeNumber' => 'nullable|string|max:255',
    'paymentDate' => 'required|date',
    'remark' => 'nullable|string|max:1000',
    'payment_voucher_no' => 'nullable|string|max:255',
    'ledger_id' => 'required|exists:ledgers,id',
    'selectedBills' => 'required|array|min:1',
    'selectedBills.*' => 'numeric|min:0.01',
];
    public function paySelectedBills()
{
    try {
        // 1) Validate the fields that apply to *every* payment
        $this->validate([
            'payment_method'  => 'required|in:CA,CH',
            'check_number' => $this->payment_method === 'CH'
                ? 'required|string|max:255'
                : 'nullable',
            'check_date' => $this->payment_method === 'CH'
                ? 'required|date'
                : 'nullable',
            'paymentDate'  => 'required|date',
            'remark'       => 'nullable|string|max:1000',
            'ledger_id'    => 'required|exists:ledgers,id',  // your bank/cash ledger
            'selectedBills' => 'required|array|min:1',
            'selectedBills.*' => 'numeric|min:0.01',         // each value is the amount to pay
        ]);

                $firstPaymentId = null; // To store the first payment ID for redirect

        // Create vendor payment record first
        $firstBill = VendorBill::findOrFail(array_key_first($this->selectedBills));
        $receiptNumber = NumberGenerator::generateVendorBillReceiptNumber(auth()->user()->branch_id);

        $vendorPayment = VendorPayment::create([
            'vendor_id' => $firstBill->vendor_id,
            'payment_date' => $this->paymentDate,
            'receipt_number' => $receiptNumber,
            'payment_method' => $this->payment_method,
            'check_number' => $this->payment_method === 'CH' ? $this->check_number : null,
            'check_date' => $this->payment_method === 'CH' ? $this->check_date : null,
            'total_amount' => $this->totalAmountToPay,
            'bank_ledger_id' => $this->ledger_id,
            'payment_voucher_no' => $this->payment_voucher_no,
        ]);

        // 2) For each selected bill
        foreach ($this->selectedBills as $billId => $amountToPay) {
            $bill = VendorBill::findOrFail($billId);

            // 3) Create the payment record
            $payment = VendorBillPayment::create([
                'vendor_bill_id' => $bill->id,
                'ledger_id'      => $this->ledger_id,
                'amount'         => $amountToPay,
                'payment_date'   => $this->paymentDate,
                'check_date'     => $this->payment_method === 'CH' ? $this->check_date : null,
                'remark'         => $this->remark,
                'created_by'     => auth()->id(),
            ]);

            // Update receipt number
            $payment->update(['receipt_number' => $receiptNumber]);

            // Store the first payment ID for redirect
            if ($firstPaymentId === null) {
                $firstPaymentId = $payment->id;
            }

            // 4) Create an accounting entry for *this* payment
            $entry = Entry::create([
                'entrytype_id' => EntryType::where('label', 'vendor')->value('id'),
                'number'       => 1, // generate properly in real life
                'date'         => $this->paymentDate,
               'narration'    => "Customer: " . $bill->vendor->name . " Check No: " . ($this->check_number ?? 'N/A') . " Voucher No: " . ($this->payment_voucher_no ?? 'N/A'),
                'dr_total'     => $payment->amount,
                'cr_total'     => $payment->amount,
                'branch_id'    => auth()->user()->branch_id,
            ]);

            // Debit Accounts Payable (liability down)
            EntryItem::create([
                'entry_id'    => $entry->id,
                'customer_id' => $bill->vendor_id,
                'ledger_id'   => 16,           // your AP ledger ID
                'dc'          => 'D',
                'amount'      => $payment->amount,
                'branch_id'   => auth()->user()->branch_id,
            ]);

            // Credit Cash/Bank (asset down)
            EntryItem::create([
                'entry_id'    => $entry->id,
                'customer_id' => $bill->vendor_id,
                'ledger_id'   => $this->ledger_id,
                'dc'          => 'C',
                'amount'      => $payment->amount,
                'branch_id'   => auth()->user()->branch_id,
            ]);

            // 5) Update the bill itself
            $bill->amount_due      = max(0, $bill->amount_due - $amountToPay);
            $bill->payment_status = $bill->amount_due == 0 ? 'paid' : 'partial';
            $bill->payment_date    = now();
            $bill->payment_type    = $this->payment_method;
            $bill->cheque_number   = $this->payment_method === 'CH' ? $this->check_number : null;
            $bill->vendor_payment_id = $vendorPayment->id;
            $bill->save();
        }

        session()->flash('success', 'Payment recorded successfully for all selected bills.');
        return redirect()->route('vendor-bill.multiple-print-preview', ['id' => $vendorPayment->id]);

    } catch (\Exception $e) {
        session()->flash('error', 'An error occurred while processing the payment: ' . $e->getMessage());
    }
}



    // public function paySelectedBills()
    // {
    //     try {
    //         // 1) Validate the fields that apply to *every* payment
    //         $this->validate([
    //             'paymentType'  => 'required|in:cash,cheque',
    //             'chequeNumber' => $this->paymentType === 'cheque'
    //                 ? 'required|string|max:255'
    //                 : 'nullable',
    //             'paymentDate'  => 'required|date',
    //             'remark'       => 'nullable|string|max:1000',
    //             'ledger_id'    => 'required|exists:ledgers,id',  // your bank/cash ledger
    //             'selectedBills' => 'required|array|min:1',
    //             'selectedBills.*' => 'numeric|min:0.01',         // each value is the amount to pay
    //         ]);

    //         // 2) For each selected bill

    //         foreach ($this->selectedBills as $billId => $amountToPay) {
    //             $bill = VendorBill::findOrFail(id: $billId);

    //             // 3) Create the payment record
    //             $payment = VendorBillPayment::updateOrCreate(
    //                 ['vendor_bill_id' => $bill->id],
    //                 [
    //                     'payment_type'  => $this->paymentType,
    //                     'cheque_number' => $this->paymentType === 'cheque'
    //                         ? $this->chequeNumber
    //                         : null,
    //                     'amount'        => $amountToPay,
    //                     'payment_date'  => $this->paymentDate,
    //                     'created_by'    => auth()->id(),
    //                     'branch_id'     => auth()->user()->branch_id ?? 1,
    //                     'remark'        => $this->remark,
    //                 ]
    //             );

    //             // 4) Create an accounting entry for *this* payment
    //             $entry = Entry::create([
    //                 'entrytype_id' => EntryType::where('label', 'vendor')->value('id'),
    //                 'number'       => 1, // generate properly in real life
    //                 'date'         => $this->paymentDate,
    //                 'narration'    => "Payment for Bill #{$bill->id}",
    //                 'dr_total'     => $payment->amount,
    //                 'cr_total'     => $payment->amount,
    //                 'branch_id'    => auth()->user()->branch_id,
    //             ]);

    //             // Debit Accounts Payable (liability down)
    //             EntryItem::create([
    //                 'entry_id'    => $entry->id,
    //                 'customer_id' => $bill->vendor_id,
    //                 'ledger_id'   => 16,           // your AP ledger ID
    //                 'dc'          => 'D',
    //                 'amount'      => $payment->amount,
    //                 'branch_id'   => auth()->user()->branch_id,
    //             ]);

    //             // Credit Cash/Bank (asset down)
    //             EntryItem::create([
    //                 'entry_id'    => $entry->id,
    //                 'customer_id' => $bill->vendor_id,
    //                 'ledger_id'   => $payment->ledger_id,
    //                 'dc'          => 'C',
    //                 'amount'      => $payment->amount,
    //                 'branch_id'   => auth()->user()->branch_id,
    //             ]);

    //             // 5) Update the bill itself
    //             $bill->amount_due      = max(0, $bill->amount_due - $amountToPay);
    //             // $bill->payment_status  = $bill->amount_due === 0 ? 'paid' : 'partial';
    //             $bill->payment_status = 'paid';
    //             $bill->payment_date    = now();
    //             $bill->save();

    //             $payment->payment_date = now();
    //             $payment->save();
    //         }

    //         // 6) Reset everything and reload
    //         $this->reset([
    //             'paymentType',
    //             'chequeNumber',
    //             'paymentDate',
    //             'remark',
    //             'ledger_id',
    //             'selectedBills',
    //         ]);
    //         $this->totalAmountToPay = 0;
    //         $this->loadBills();

    //         session()->flash('success', 'Payment recorded successfully for all selected bills.');
    //     } catch (\Exception $e) {
    //         session()->flash('error', 'An error occurred while processing the payment: ' . $e->getMessage());
    //     }
    // }


    public function render()
    {

        $bodyAttributes = 'x-data="{ page: \'VendorBill\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
        x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
        :class="{\'dark bg-gray-900\': darkMode === true}"';

        return view('livewire.supplier.bill-payment-form', [
            'bankAccounts' => Bank::all(),
            'vendors' => Supplier::all()
        ])->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
