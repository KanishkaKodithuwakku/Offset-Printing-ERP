<?php

namespace App\Livewire\Customer;

use App\Helpers\NumberGenerator;
use App\Models\CreditApplication;
use App\Models\Customer;
use App\Models\CustomerCredit;
use App\Models\Invoice;
use App\Models\Ledger;
use App\Models\Entry;
use App\Models\EntryItem;
use App\Models\EntryType;
use App\Models\Payment;
use App\Models\PaymentDetail;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\On;
use Mockery\CountValidator\Exception;

class CustomerPayment extends Component
{
    public $customers = [];
    public $invoices = [];
    public $selectedCustomerId;
    public $selectedInvoices = [];
    public $selectAll = false;
    public $paymentAmount = 0;
    public $paymentDate;
    public $payment_method = 'CA';
    public $check_number = '';
    public $memo = '';
    public $payments = [];
    public $availableCredits = 0;
    public $customerBalance = 0;

    public $remainingBalance = 0;
    public $difference = 0;

    public $totalOriginalAmount = 0;
    public $totalAmountDue = 0;
    public $totalCredit = 0;
    public $totalPayment = 0;
    public $selectedTotalAmountDue = 0;
    public $discountAndCreditsApplied = 0;
    public $selectedCustomer;
    public $customerId;
    public $selectedPayments = [];

    public $ledger_id;
    public $bank_id;
    public $branch_id;

    public $initialPayment = null;
    public $paymentLocked = false;

    protected $listeners = ['invoicesUpdated' => 'handleInvoicesUpdated'];

    public function mount()
    {
        $customers = Customer::orderBy('name')->get();
        $this->customers = $customers;
        $this->paymentDate = now()->format('Y-m-d');
    }


    #[On('selectedBank')]
    public function updateSelectedBank($id)
    {
        $this->bank_id = $id;
    }


    #[On('selectedBranch')]
    public function updateSelectedBranch($id)
    {
        $this->branch_id = $id;
    }


    #[On('ledgerSelected')]
    public function setLedger($ledgerId)
    {
        $this->ledger_id = $ledgerId;
        $this->ledger = Ledger::find($ledgerId);
    }



    public function handleInvoicesUpdated($invoices)
    {
        $this->invoices = $invoices;
    }
    public function updatedCustomerId($value)
    {
        $this->selectedCustomerId = $value;
        $this->selectedCustomer = Customer::find($value);
        $this->loadCustomerInvoices();
        $this->loadCustomerTotalCredit();
    }

    public function loadCustomerTotalDue()
    {
        if (!$this->selectedCustomerId) {
            $this->totalDue = 0;
            return;
        }

        $totalDue = Invoice::where('customer_id', $this->selectedCustomerId)
            ->where('status', '!=', 'paid')  // only unpaid or partially paid
            ->sum('amount_due');

        $this->totalAmountDue = $totalDue;
    }

    public $showCreditModal = false;
    public $credits = [];
    public function loadCustomerCredits($requiredBalance)
    {
        if (!$this->selectedCustomerId)
            return;

        $credits = CustomerCredit::where('customer_id', $this->selectedCustomerId)
            ->where('amount', '>', 0)
            ->orderBy('created_at', 'asc')
            ->get();

        $this->credits = [];

        foreach ($credits as $credit) {
            $amountToUse = min($credit->amount, $requiredBalance);
            $this->credits[] = [
                'id' => $credit->id,
                'date' => $credit->created_at->format('Y-m-d'),
                'number' => $credit->credit_number ?? 'CR-' . $credit->id,
                'amount' => $credit->amount,
                'amountToUse' => $amountToUse,
                'balance' => $credit->amount - $amountToUse,
            ];
            $requiredBalance -= $amountToUse;

            if ($requiredBalance <= 0) {
                break; // No more needed
            }
        }

        // Update UI
        $this->availableCredits = $credits->sum('amount');
        $this->showCreditModal = true;
    }

    public $customerAvlCredits = null;

    public function loadCustomerTotalCredit()
    {
        if (!$this->selectedCustomerId) {
            $this->totalCredit = 0;
            return;
        }

        // Sum the available credits for the selected customer
        $this->customerAvlCredits = CustomerCredit::where('customer_id', $this->selectedCustomerId)
            ->sum('amount');  // or sum('available_amount') if you have that column

        // dd($totalCredits);

        // $this->totalCredit = $totalCredits;
    }



    public function _allocateCreditatoSelectedInvoice()
    {
        $selectedInvoice = $this->selectedInvoices[0] ?? null;
        if (!$selectedInvoice)
            return;

        $invoiceId = (int) $selectedInvoice['id'];

        // Find the invoice in $this->invoices
        $invoice = collect($this->invoices)->firstWhere('id', $invoiceId);
        if (!$invoice) {
            // Invoice not found, just return
            return;
        }

        $invoiceAmountDue = $invoice['amount_due'];

        $totalCreditUsed = 0;

        foreach ($this->credits as $credit) {
            $amountToUse = (float) ($credit['amountToUse'] ?? 0);
            if ($amountToUse > 0) {
                $totalCreditUsed += $amountToUse;
            }
        }

        // Ensure total credit used does not exceed invoice amount due
        if ($totalCreditUsed > $invoiceAmountDue) {
            $totalCreditUsed = $invoiceAmountDue;
        }

        // Update the invoices array with adjusted credit and amount_due
        $this->invoices = collect($this->invoices)->map(function ($invoice) use ($invoiceId, $totalCreditUsed) {
            if ((int) $invoice['id'] === $invoiceId) {
                $invoice['credit'] = $totalCreditUsed;
                $invoice['amount_due'] = max(0, $invoice['amount_due'] - $totalCreditUsed);
            }
            return $invoice;
        })->toArray();

        // Update payments and paymentAmount for UI binding
        $this->payments[$invoiceId] = $totalCreditUsed;
        //$this->paymentAmount = $totalCreditUsed;

        $this->showCreditModal = false;
        $this->calculateTotals();
    }



    public function allocateCreditatoSelectedInvoice()
    {
        $selectedInvoice = $this->selectedInvoices[0] ?? null;
        if (!$selectedInvoice)
            return;

        $invoiceId = (int) $selectedInvoice['id'];
        $totalCreditUsed = 0;

        // Sum all applied credit
        foreach ($this->credits as $credit) {
            $amountToUse = (float) ($credit['amountToUse'] ?? 0);
            if ($amountToUse > 0) {
                $totalCreditUsed += $amountToUse;
            }
        }

        // Replace invoice with updated values
        $this->invoices = collect($this->invoices)->map(function ($invoice) use ($invoiceId, $totalCreditUsed) {
            if ((int) $invoice['id'] === $invoiceId) {
                $invoice['credit'] = $totalCreditUsed;
                $invoice['amount_due'] = max(0, $invoice['amount_due'] - $totalCreditUsed);
            }
            return $invoice;
        })->toArray();

        $this->showCreditModal = false;
        $this->calculateTotals();
    }





    public function allocateCreditsToLastInvoice()
    {
        if (!$this->selectedInvoices || count($this->credits) === 0) {
            return;
        }

        $lastInvoiceId = collect($this->selectedInvoices)->first();
        $invoice = Invoice::find($lastInvoiceId);

        if (!$invoice)
            return;

        foreach ($this->credits as $creditData) {
            $available = $creditData['balance'] ?? 0;
            $toUse = $creditData['amountToUse'] ?? 0;

            if ($toUse > 0 && $available >= $toUse) {
                CreditApplication::create([
                    'customer_credit_id' => $creditData['id'], //must include this ID in $credits array
                    'invoice_id' => $invoice->id,
                    'amount_applied' => $toUse,
                    'applied_date' => now(),
                ]);

                // Update the invoice
                $invoice->amount_due = max(0, $invoice->amount_due - $toUse);
                $invoice->credit = ($invoice->credit ?? 0) + $toUse;

            }
        }

        $invoice->save();
        $this->calculateTotals();
    }

    public function loadCustomerCreditsAndAllocate($balance)
    {
        $this->loadCustomerCredits($balance);
        $this->allocateCreditsToLastInvoice();
    }

    public function finalizeCreditAllocation()
    {
        $this->allocateCreditsToLastInvoice();
        $this->showCreditModal = false;
    }


    public function handlePaymentAmountChange()
    {

        if (!$this->paymentLocked) {
            $this->initialPayment = $this->paymentAmount;
            $this->paymentLocked = true;
            $this->recalculateRemaining();
        }

        $this->distributePayment();
        $this->recalculateRemaining();


    }

    public function openCreditModal($invoiceId): void
    {
        $invoice = Invoice::find($invoiceId);
        $manualPayment = $this->payments[$invoiceId] ?? 0;
        $requiredBalance = max(0, $invoice->amount_due - $manualPayment);

        $this->selectedInvoices = [
            [
                'id' => $invoiceId,
                'amount' => $manualPayment,
            ]
        ];

        $this->loadCustomerCredits($requiredBalance); // Pass only required balance
    }


    public function loadCustomerInvoices()
    {
        $this->invoices = Invoice::where('customer_id', $this->selectedCustomer->id)
            ->where('payment_status', '!=', 'paid')
            ->where('status', '!=', 'cancelled')
            ->where('amount_due', '>', 0)
            ->get()
            ->map(function ($invoice) {
                return [
                    'id' => $invoice->id,
                    'invoice_number' => $invoice->invoice_number,
                    'created_at' => $invoice->created_at,
                    'original_amount' => $invoice->total_amount,
                    'amount_due' => $invoice->amount_due,
                    'credit' => 0,
                    'credits_applied' => [], // Track applied credits [{credit_id, amount}]
                    'credit_total' => 0,     // For display and calculation
                    'payment_applied' => 0,     // For display and calculation
                ];
            })
            ->toArray();

        $this->selectedInvoices = [];
        $this->payments = [];
        $this->selectAll = false;
        $this->paymentAmount = 0;

        $this->calculateTotals();
    }


    public function distributePayment()
    {
        logger('Customer is : ' . $this->customerId);
        logger('Distributing payment: ' . $this->paymentAmount);

        $this->payments = [];
        $remaining = $this->paymentAmount;

        $filteredInvoices = collect($this->invoices)
            ->where('amount_due', '>', 0)
            ->sortBy('date');

        logger('Filtered invoices count: ' . $filteredInvoices->count());

        foreach ($filteredInvoices as $invoice) {
            if ($remaining <= 0)
                break;

            $amt = min($invoice['amount_due'], $remaining);
            $this->payments[$invoice['id']] = $amt;
            $remaining -= $amt;
        }

        foreach ($this->invoices as &$invoice) {  // Use reference to update in place
            $invoiceId = $invoice['id'];
            if (isset($this->payments[$invoiceId])) {
                // For example, reduce amount_due by payment amount
                $paymentAmount = $this->payments[$invoiceId];

                // Update amount_due after payment
                $invoice['amount_due'] = max(0, $invoice['amount_due'] - $paymentAmount);

                // Optionally store payment amount on invoice if needed
                $invoice['payment_applied'] = $paymentAmount;
            }
        }

        $this->remainingBalance = max(0, $remaining);
        $this->calculateTotals();

        logger('Payments: ', $this->payments);
    }


    public function toggleInvoiceSelection($invoiceId)
    {
        // Check if it's already selected
        $existing = collect($this->selectedInvoices)->firstWhere('id', $invoiceId);

        if ($existing) {
            // Remove if already selected
            $this->selectedInvoices = collect($this->selectedInvoices)
                ->reject(fn($item) => $item['id'] == $invoiceId)
                ->values()
                ->toArray();
        } else {
            // Get amount from payments array (default to 0 if not yet filled)
            $amount = $this->payments[$invoiceId] ?? 0;

            $this->selectedInvoices[] = [
                'id' => $invoiceId,
                'amount' => $amount,
            ];
        }
    }

    public function updatedPayments($value, $name): void
    {
        /**
         * $value = the new value entered (e.g., 2200)
         * $name = the name of the property updated (e.g., 'payments.4')
         */
        $parts = explode('.', $name);

        /**
         * This is a guard clause to make sure the $name string has at least one dot (.) — meaning it's in the correct format like 'payments.4'.
         * If it's not, the function exits early to avoid Undefined array key errors.
         *
         */
        if (count($parts) < 2) {
            return;
        }

        $invoiceId = $parts[1];

        /**
         * looping through all selected invoices (previously checked by the user).
         */

        foreach ($this->selectedInvoices as &$invoice) {
            if ($invoice['id'] == $invoiceId) {
                $invoice['amount'] = $value;
                break;
            }
        }

        $this->recalculateRemaining();
    }

    protected function recalculateRemaining()
    {
        // subtract the sum of all allocated payments from the original
        $allocated = collect($this->payments)->sum();
        $this->remainingBalance = max(0, $this->initialPayment - $allocated);
    }


    public function validateCreditAmount($index)
    {
        if (!isset($this->credits[$index]))
            return;

        $credit = &$this->credits[$index];

        // Optional safety check: default to 0 if not set
        $credit['amountToUse'] = $credit['amountToUse'] ?? 0;

        // Ensure the amount does not exceed available credit
        if ($credit['amountToUse'] > $credit['amount']) {
            $credit['amountToUse'] = $credit['amount'];
        }

        // Optional: Round to 2 decimal places
        $credit['amountToUse'] = round($credit['amountToUse'], 2);

        // Update credit balance accordingly (if shown)
        $credit['balance'] = round($credit['amount'] - $credit['amountToUse'], 2);
    }


    public $overpayment = 0;
    public function updateSelectedInvoiceAmount($invoiceId)
    {

        $this->payments = array_map(function ($value) {
            return is_numeric($value) ? floatval($value) : 0;
        }, $this->payments);

        $this->paymentAmount = array_sum(array_map('floatval', $this->payments));
        $amount = floatval($this->payments[$invoiceId] ?? 0);
        $this->totalPayment = collect($this->payments)->sum();
        $this->overpayment = 0;  // Reset the overpayment each time to calculate fresh

        // Update overpayment logic
        foreach ($this->invoices as &$invoice) {
            if ($invoice['id'] == $invoiceId) {
                $invoice['payment_applied'] = $amount;

                // Explicit casting
                $amountDue = floatval($invoice['amount_due']);
                $invoice['amount_due'] = max(0, $invoice['original_amount'] - $invoice['payment_applied']);
            }

            // Check for overpayment condition
            if (isset($invoice['payment_applied']) && is_numeric($invoice['payment_applied']) && $invoice['payment_applied'] > $invoice['original_amount']) {
                // Collect overpayments
                $this->overpayment += $invoice['payment_applied'] - $invoice['original_amount'];
            }
        }
        $this->recalculateRemaining();
        // Debug overpayment collection
        logger("Total Overpayment: " . $this->overpayment);
    }




    public function __updateSelectedInvoiceAmount($invoiceId)
    {
        $this->paymentAmount = array_sum($this->payments);
        $amount = floatval($this->payments[$invoiceId] ?? 0);
        $this->totalPayment = collect($this->payments)->sum();

        foreach ($this->invoices as &$invoice) {
            if ($invoice['id'] == $invoiceId) {
                // Ensure payment does not exceed the amount due
                $maxAmount = min($invoice['amount_due'], $amount);
                $invoice['payment_applied'] = $maxAmount;

                // Explicit casting to float
                $amountDue = floatval($invoice['amount_due']);
                $invoice['amount_due'] = max(0, $amountDue - $maxAmount);
                break;
            }
        }
    }



    public function invoicesUpdated($invoices)
    {
        $this->invoices = $invoices;
    }



    public function recalculatePayments()
    {
        $this->calculateTotals();
    }

    public function calculateTotals()
    {
        $this->totalOriginalAmount = 0;
        $this->totalAmountDue = 0;
        $this->totalCredit = 0;
        $this->totalPayment = 0;
        $this->selectedTotalAmountDue = 0;

        foreach ($this->invoices as $invoice) {
            $this->totalOriginalAmount += $invoice['original_amount'];
            $this->totalAmountDue += $invoice['amount_due'];
            $this->totalCredit += $invoice['credit'];

            if (in_array($invoice['id'], array_column($this->selectedInvoices, 'id'))) {
                $this->selectedTotalAmountDue += $invoice['amount_due'];
            }

            if (isset($this->payments[$invoice['id']])) {
                $this->totalPayment += $this->payments[$invoice['id']];
            }
        }

        $this->discountAndCreditsApplied = max(0, $this->selectedTotalAmountDue - $this->totalPayment);
        $this->difference = $this->paymentAmount - $this->totalPayment;

        // dd($this->difference);
    }

    public function generateNextEntryNumber($label)
    {
        $entryType = EntryType::where('label', $label)->firstOrFail();
        $lastEntry = Entry::where('entrytype_id', $entryType->id)->latest()->first();
        $next = $lastEntry ? ((int) $lastEntry->number + 1) : 1;
        return str_pad($next, $entryType->zero_padding ?? 0, '0', STR_PAD_LEFT);
    }

    public function savePayment()
    {
        // 1) Totals for what the user entered:
        $totalCash = collect($this->payments)->sum() ?? 0;                          // new cash/check
        $totalCredit = collect($this->invoices)->sum(fn($inv) => $inv['credit'] ?? 0);

        // Validation for bank and branch when payment method is check
        if ($this->payment_method === 'CH') {
            if (empty($this->bank_id)) {
                session()->flash('error', 'Bank is required for cheque payments.');
                return;
            }
            if (empty($this->branch_id)) {
                session()->flash('error', 'Branch is required for cheque payments.');
                return;
            }
        }

        if ($totalCash + $totalCredit <= 0) {
            session()->flash('error', 'Nothing to apply: enter an amount or use credits.');
            return;
        }

        DB::beginTransaction();
        try {
            $cust = Customer::findOrFail($this->customerId);
            $branchId = auth()->user()->branch_id;

            $bankLedgerId = $this->ledger_id;  // required if $totalCash > 0
            // $accountsReceivableLedgerId = Ledger::where('name', 'Accounts Receivable')->value('id');
            $arLedgerId = Ledger::where('name', 'Accounts Receivable')->value('id');
            $custCreditLedgerId = Ledger::where('name', 'Customer Credit')->value('id');
            $receiptTypeId = EntryType::where('label', 'receipt')->value('id');

            if ($totalCash > 0 && !$bankLedgerId) {
                throw new \Exception('Please select the bank when entering a payment.');
            }

            // 2) Create the single "receipt" journal entry
            $entry = Entry::create([
                'entrytype_id' => $receiptTypeId,
                'number' => Entry::getNextNumber($receiptTypeId),
                'customer_id' => $cust->id,
                'branch_id' => $branchId,
                'date' => now(),
                'narration' => $this->memo ?: "Payment from {$cust->name}",
                'dr_total' => $totalCash + $totalCredit,
                'cr_total' => 0,  // will fill in below
            ]);

            $allocated = 0;

            $paymentId = null;

            // 3) CASH/CHECK portion
            if ($totalCash > 0) {
                // a) debit bank
                EntryItem::create([
                    'entry_id' => $entry->id,
                    'ledger_id' => $bankLedgerId,
                    'dc' => 'D',
                    'amount' => $this->initialPayment,
                    'customer_id' => $cust->id,
                    'branch_id' => $branchId,
                ]);

                // b) record one Payment model
                $payment = Payment::create([
                    'customer_id' => $cust->id,
                    'entry_id' => $entry->id,
                    'amount' => $this->initialPayment,
                    'date' => now(),
                    'method' => $this->payment_method,
                    'check_number' => $this->check_number,
                    'cheque_date' => $this->paymentDate,
                    'bank_id' => $this->bank_id,
                    'bank_branch_id' => $this->branch_id,
                    'memo' => $this->memo,
                    'user_id' => auth()->id(),
                    'branch_id' => $branchId,
                ]);
                $payment->payment_code = NumberGenerator::generatePaymentCode($payment);
                $payment->save();

                $paymentId = $payment->id;

                // c) allocate across invoices & credit A/R
                foreach ($this->payments as $invId => $amt) {
                    if ($amt <= 0)
                        continue;
                    $allocated += $amt;

                    EntryItem::create([
                        'entry_id' => $entry->id,
                        'ledger_id' => $arLedgerId,
                        'dc' => 'C',
                        'amount' => $amt,
                        'customer_id' => $cust->id,
                        'branch_id' => $branchId,
                    ]);

                    PaymentDetail::create([
                        'payment_id' => $payment->id,
                        'invoice_id' => $invId,
                        'branch_id' => $branchId,
                        'amount' => $amt,
                        'is_credit' => 0,
                    ]);

                    $inv = Invoice::findOrFail($invId);
                    $inv->amount_due = max(0, $inv->amount_due - $amt);
                    $inv->payment_status = $inv->amount_due === 0 ? 'paid' : 'partial';
                    $inv->status = 'invoiced';
                    $inv->save();
                }
            }

            // 4) CREDIT portion
            if ($totalCredit > 0) {
                if ($totalCash == 0) {
                    $payment = Payment::create([
                        'customer_id' => $cust->id,
                        'entry_id' => $entry->id,
                        'amount' => $this->initialPayment ?? 0,
                        'date' => now(),
                        'method' => $this->payment_method,
                        'check_number' => $this->check_number,
                        'cheque_date' => $this->paymentDate,
                        'bank_id' => $this->bank_id,
                        'bank_branch_id' => $this->branch_id,
                        'memo' => $this->memo,
                        'user_id' => auth()->id(),
                        'branch_id' => $branchId,
                    ]);
                    $payment->payment_code = NumberGenerator::generatePaymentCode($payment);
                    $payment->save();
                    $paymentId = $payment->id;
                }


                foreach ($this->invoices as $invData) {
                    $use = $invData['credit'] ?? 0;
                    if ($use <= 0)
                        continue;
                    $allocated += $use;

                    EntryItem::create([
                        'entry_id' => $entry->id,
                        'ledger_id' => $custCreditLedgerId,
                        'dc' => 'D',
                        'amount' => $use,
                        'customer_id' => $cust->id,
                        'branch_id' => $branchId,
                    ]);

                    EntryItem::create([
                        'entry_id' => $entry->id,
                        'ledger_id' => $arLedgerId,
                        'dc' => 'C',
                        'amount' => $use,
                        'customer_id' => $cust->id,
                        'branch_id' => $branchId,
                    ]);

                    PaymentDetail::create([
                        'payment_id' => $paymentId,
                        'invoice_id' => $invData['id'],
                        'branch_id' => $branchId,
                        'amount' => $use,
                        'is_credit' => 1
                    ]);

                    // CreditApplication::create([
                    //     'customer_credit_id' => $invData['credit_application_id'] ?? $invData['id'],
                    //     'invoice_id'         => $invData['id'],
                    //     'amount_applied'     => $use,
                    //     'applied_date'       => now(),
                    // ]);

                    $creditRecord = CustomerCredit::where('customer_id', $this->customerId)
                        ->where('amount', '>=', $use)
                        ->first();

                    if ($creditRecord) {
                        DB::table('credit_applications')->insert([
                            'customer_credit_id' => $creditRecord->id,
                            'invoice_id' => $invData['id'],
                            'amount_applied' => $use,
                            'applied_date' => now(),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        $creditRecord->amount -= $use;
                        $creditRecord->save();
                    }



                    // $ccRec = CustomerCredit::find($invData['credit_application_id'] ?? $invData['id']);
                    // if ($ccRec) {
                    //     $ccRec->amount = max(0, $ccRec->amount - $use);
                    //     $ccRec->save();
                    // }

                    $inv = Invoice::findOrFail($invData['id']);
                    $inv->amount_due = max(0, $inv->amount_due - $use);
                    $inv->payment_status = $inv->amount_due === 0 ? 'paid' : 'partial';
                    $inv->status = 'invoiced';
                    $inv->save();
                }
            }

            // 5) Finalize journal totals
            $entry->cr_total = $allocated;
            $entry->save();

            // 6) NOW HANDLE ANY remainingBalance:
            //    (you've been keeping $this->remainingBalance = initialPayment - allocated)
            if ($this->remainingBalance > 0) {
                // a) post the GL line to Customer Credit
                EntryItem::create([
                    'entry_id' => $entry->id,
                    'ledger_id' => $custCreditLedgerId,
                    'dc' => 'C',
                    'amount' => $this->remainingBalance,
                    'customer_id' => $cust->id,
                    'branch_id' => $branchId,
                ]);

                // b) persist it as a new CustomerCredit
                $cc = CustomerCredit::firstOrNew([
                    'customer_id' => $cust->id,
                ]);
                $cc->amount = ($cc->amount ?? 0) + $this->remainingBalance;
                $cc->date = now();
                $cc->payment_id = $payment->id ?? null;
                $cc->save();
            }

            DB::commit();
            session()->flash('message', 'Payment recorded successfully!');
            return redirect()->route('customer.payment');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error saving payment: ' . $e->getMessage());
        }
    }






    public function clear()
    {
        // 1) reset any filters / state
        // $this->reset(['search', 'from', 'to' /* etc */]);

        // 2) redirect back to the index route
        $this->redirectRoute('customer.payment');
    }



    public function saveAndClose()
    {
        if ($this->savePayment()) {
            return redirect()->route('customer.payment');
        }
    }

    public function saveAndNew()
    {
        if ($this->savePayment()) {
            $this->clear();
        }
    }



    public function render()
    {

        $this->listeners = ['invoicesUpdated' => 'handleInvoicesUpdated'];
        $bodyAttributes = 'x-data="{ page: \'CustomerPayment\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
            x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                    $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
            :class="{\'dark bg-gray-900\': darkMode === true}"';

        return view('livewire.customer.customer-payment')
            ->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
