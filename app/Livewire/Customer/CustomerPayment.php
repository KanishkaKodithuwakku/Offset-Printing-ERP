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
    public $paymentFieldRefreshKey = 0;

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
        
        // CRITICAL: Clear all payment-related data when customer changes
        // This prevents stale payment data from previous customer being applied to new customer
        $this->payments = [];
        $this->paymentAmount = 0;
        $this->initialPayment = null;
        $this->paymentLocked = false;
        $this->remainingBalance = 0;
        $this->selectedInvoices = [];
        
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

        // Get current invoice ID (if opening modal for a specific invoice)
        $currentInvoiceId = null;
        if (!empty($this->selectedInvoices)) {
            $currentInvoiceId = is_array($this->selectedInvoices[0]) 
                ? ($this->selectedInvoices[0]['id'] ?? null)
                : $this->selectedInvoices[0];
        }

        // Calculate credits already allocated to OTHER invoices (excluding current invoice) in current session
        $totalAllocatedInSession = 0;
        foreach ($this->invoices as $invoice) {
            $invoiceId = (int) ($invoice['id'] ?? 0);
            $allocatedCredit = (float) ($invoice['credit'] ?? 0);
            
            // Skip current invoice and invoices with no credit allocated
            if ($invoiceId === (int) $currentInvoiceId || $allocatedCredit <= 0) {
                continue;
            }
            $totalAllocatedInSession += $allocatedCredit;
        }

        $credits = CustomerCredit::where('customer_id', $this->selectedCustomerId)
            ->where('amount', '>', 0)
            ->orderBy('created_at', 'asc')
            ->get();

        $this->credits = [];
        $remainingSessionAllocation = $totalAllocatedInSession;

        foreach ($credits as $credit) {
            // Get how much of this credit has been used (from database - credit_applications)
            $usedFromDB = \App\Models\CreditApplication::where('customer_credit_id', $credit->id)
                ->sum('amount_applied');
            
            // Calculate remaining credit after database usage
            $remainingCredit = max(0, $credit->amount - $usedFromDB);
            
            // Reduce by session allocations to OTHER invoices (allocate from oldest credit first)
            if ($remainingCredit > 0 && $remainingSessionAllocation > 0) {
                $sessionDeduction = min($remainingCredit, $remainingSessionAllocation);
                $remainingCredit = max(0, $remainingCredit - $sessionDeduction);
                $remainingSessionAllocation = max(0, $remainingSessionAllocation - $sessionDeduction);
            }
            
            if ($remainingCredit <= 0) {
                continue; // Skip fully used credits
            }
            
            // Calculate amount to use for this invoice
            $amountToUse = min($remainingCredit, $requiredBalance);
            
            $this->credits[] = [
                'id' => $credit->id,
                'date' => $credit->created_at->format('Y-m-d'),
                'number' => $credit->credit_number ?? 'CR-' . $credit->id,
                'amount' => $remainingCredit, // Show remaining amount, not original
                'amountToUse' => $amountToUse,
                'balance' => $remainingCredit - $amountToUse,
            ];
            $requiredBalance -= $amountToUse;

            if ($requiredBalance <= 0) {
                break; // No more needed
            }
        }

        // Update UI with actual available credits (after session allocations)
        $totalAvailableCredit = collect($this->credits)->sum('amount');
        $this->availableCredits = $totalAvailableCredit;
        $this->showCreditModal = true;
    }

    public $customerAvlCredits = null;
    public $totalCreditsFromDB = 0;

    public function loadCustomerTotalCredit()
    {
        if (!$this->selectedCustomerId) {
            $this->totalCredit = 0;
            $this->customerAvlCredits = 0;
            return;
        }

        // Use recalculateAvailableCredits to get the correct balance (accounting for allocations)
        $this->recalculateAvailableCredits();
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

        // Don't set payment when allocating credit - payment and credit are separate
        // $this->payments[$invoiceId] should remain unchanged when credit is allocated

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

        // Sum all applied credit for the selected invoice
        foreach ($this->credits as $credit) {
            $amountToUse = (float) ($credit['amountToUse'] ?? 0);
            if ($amountToUse > 0) {
                $totalCreditUsed += $amountToUse;
            }
        }

        // Find the invoice to get original_amount
        $invoice = collect($this->invoices)->firstWhere('id', $invoiceId);
        if (!$invoice) {
            return;
        }

        // Replace invoice with updated values
        // Recalculate amount_due from original_amount minus credit and payment
        $this->invoices = collect($this->invoices)->map(function ($inv) use ($invoiceId, $totalCreditUsed) {
            if ((int) $inv['id'] === $invoiceId) {
                $inv['credit'] = $totalCreditUsed;
                $originalAmount = (float) ($inv['original_amount'] ?? 0);
                $paymentApplied = (float) ($this->payments[$inv['id']] ?? 0);
                $inv['amount_due'] = max(0, $originalAmount - $totalCreditUsed - $paymentApplied);
            }
            return $inv;
        })->toArray();

        // Note: Removed automatic allocation to subsequent invoices
        // Users should manually allocate credits to each invoice through the modal

        // Recalculate available credits (total credits minus allocated credits)
        $this->recalculateAvailableCredits();
        
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

    public function updateInvoiceCredit($invoiceId, $creditAmount)
    {
        $creditAmount = (float) $creditAmount;
        
        // Find the invoice
        $invoice = collect($this->invoices)->firstWhere('id', $invoiceId);
        if (!$invoice) {
            session()->flash('error', 'Invoice not found.');
            return;
        }

        // Validation 1: Credit amount cannot be negative
        if ($creditAmount < 0) {
            session()->flash('error', 'Credit amount cannot be negative.');
            // Reset to 0
            $this->invoices = collect($this->invoices)->map(function ($inv) use ($invoiceId) {
                if ((int) $inv['id'] === (int) $invoiceId) {
                    $inv['credit'] = 0;
                }
                return $inv;
            })->toArray();
            $this->calculateTotals();
            return;
        }

        // Get invoice details
        $originalAmount = (float) ($invoice['original_amount'] ?? 0);
        $paymentApplied = (float) ($this->payments[$invoiceId] ?? 0);
        $currentAmountDue = max(0, $originalAmount - $paymentApplied);

        // Validation 2: Credit cannot exceed invoice original amount
        if ($creditAmount > $originalAmount) {
            session()->flash('error', 'Credit amount (' . number_format($creditAmount, 2) . ') cannot exceed the invoice original amount (' . number_format($originalAmount, 2) . ').');
            // Reset to the maximum allowed (original amount)
            $creditAmount = $originalAmount;
        }

        // Validation 3: Credit cannot exceed invoice amount_due (after considering payments)
        if ($creditAmount > $currentAmountDue) {
            session()->flash('error', 'Credit amount (' . number_format($creditAmount, 2) . ') cannot exceed the invoice amount due (' . number_format($currentAmountDue, 2) . ').');
            // Reset to the maximum allowed (current amount_due)
            $creditAmount = $currentAmountDue;
        }

        // Get total available credits from database
        if (!$this->selectedCustomerId) {
            session()->flash('error', 'Please select a customer first.');
            return;
        }

        $totalAvailableCredits = CustomerCredit::where('customer_id', $this->selectedCustomerId)
            ->where('amount', '>', 0)
            ->sum('amount');

        // Calculate total credits already allocated to OTHER invoices (excluding current invoice)
        $totalCreditsAllocatedToOthers = 0;
        foreach ($this->invoices as $inv) {
            $invId = (int) ($inv['id'] ?? 0);
            if ($invId !== (int) $invoiceId) {
                $totalCreditsAllocatedToOthers += (float) ($inv['credit'] ?? 0);
            }
        }

        // Validation 4: Ensure total credits don't exceed available credit balance
        $newTotalAllocated = $totalCreditsAllocatedToOthers + $creditAmount;
        if ($newTotalAllocated > $totalAvailableCredits) {
            $maxAllowedFromCreditBalance = max(0, $totalAvailableCredits - $totalCreditsAllocatedToOthers);
            // Also ensure it doesn't exceed the invoice original amount or amount_due
            $maxAllowedForThisInvoice = min($maxAllowedFromCreditBalance, $originalAmount, $currentAmountDue);
            
            session()->flash('error', 'Cannot allocate more than available credits. Maximum allowed for this invoice: ' . number_format($maxAllowedForThisInvoice, 2) . ' (Available credit: ' . number_format($maxAllowedFromCreditBalance, 2) . ', Invoice original: ' . number_format($originalAmount, 2) . ', Amount due: ' . number_format($currentAmountDue, 2) . ')');
            
            // Reset the credit amount to the maximum allowed
            $creditAmount = $maxAllowedForThisInvoice;
        }

        // Update the invoice credit and recalculate amount_due
        // IMPORTANT: When credit is entered, preserve existing payment_applied
        $updated = false;
        foreach ($this->invoices as $key => $inv) {
            if ((int) $inv['id'] === (int) $invoiceId) {
                // Get current payment_applied value (preserve it, don't reset it)
                $currentPaymentApplied = (float) ($inv['payment_applied'] ?? 0);
                
                // CRITICAL: Only reset payment_applied if it equals credit (which is wrong)
                // If payment_applied is different from credit, keep it as is
                if ($currentPaymentApplied > 0 && abs($currentPaymentApplied - $creditAmount) < 0.01) {
                    // Payment equals credit - this is wrong, reset to 0
                    $currentPaymentApplied = 0;
                    $this->invoices[$key]['payment_applied'] = 0;
                    $this->payments[$invoiceId] = null;
                } else {
                    // Payment is different from credit - preserve it
                    // Don't modify payment_applied when credit is entered
                }
                
                // Recalculate amount_due: original - credit - payment
                $newAmountDue = max(0, $originalAmount - $creditAmount - $currentPaymentApplied);
                
                // Only update if values actually changed
                if ($this->invoices[$key]['credit'] != $creditAmount || $this->invoices[$key]['amount_due'] != $newAmountDue) {
                    $this->invoices[$key]['credit'] = $creditAmount;
                    $this->invoices[$key]['amount_due'] = $newAmountDue;
                    $updated = true;
                }
                break;
            }
        }
        
        // Force Livewire to detect the array change by creating a new array reference
        if ($updated) {
            $this->invoices = array_values($this->invoices);
            // Increment refresh key to force payment field re-render
            $this->paymentFieldRefreshKey++;
        }

        // Recalculate available credits and totals
        $this->recalculateAvailableCredits();
        $this->calculateTotals();
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
        
        // Sync payments array with payment_applied from invoices
        foreach ($this->invoices as $invoice) {
            $paymentApplied = (float) ($invoice['payment_applied'] ?? 0);
            if ($paymentApplied > 0) {
                $this->payments[$invoice['id']] = $paymentApplied;
            }
        }

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
            ->sortBy('created_at');  // Fixed: changed 'date' to 'created_at'

        logger('Filtered invoices count: ' . $filteredInvoices->count());

        foreach ($filteredInvoices as $invoice) {
            if ($remaining <= 0)
                break;

            $amt = min($invoice['amount_due'], $remaining);
            $this->payments[$invoice['id']] = $amt;
            $remaining -= $amt;
        }

        // Update invoices array with payments - use map() instead of reference to ensure Livewire detects changes
        $this->invoices = collect($this->invoices)->map(function ($invoice) {
            $invoiceId = $invoice['id'];
            if (isset($this->payments[$invoiceId])) {
                $paymentAmount = $this->payments[$invoiceId];
                
                // Fixed: Recalculate amount_due from original_amount minus credit and payment
                $originalAmount = (float) ($invoice['original_amount'] ?? 0);
                $creditApplied = (float) ($invoice['credit'] ?? 0);
                $invoice['amount_due'] = max(0, $originalAmount - $creditApplied - $paymentAmount);

                // Store payment amount on invoice (this is what displays in TOTAL column)
                $invoice['payment_applied'] = $paymentAmount;
            } else {
                // If no payment for this invoice, ensure payment_applied is 0
                $invoice['payment_applied'] = 0;
            }
            return $invoice;
        })->toArray();

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
        $allocated = collect($this->payments)->sum(function($val) {
            return ($val === null || $val === '') ? 0 : floatval($val);
        });
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
        // Find the invoice
        $invoice = collect($this->invoices)->firstWhere('id', $invoiceId);
        if (!$invoice) {
            return;
        }

        // Clean and validate payments array
        // CRITICAL: Convert empty strings and null to null (not 0), so field stays blank
        $this->payments = array_map(function ($value) {
            if ($value === null || $value === '' || $value === '0' || $value === 0) {
                return null; // Keep as null so field displays empty
            }
            return is_numeric($value) ? floatval($value) : null;
        }, $this->payments);

        // Get the payment amount for this invoice - preserve what user entered
        // CRITICAL: Always read from $this->payments array, never from $invoice['credit']
        $rawAmount = $this->payments[$invoiceId] ?? null;
        $amount = ($rawAmount !== null && $rawAmount !== '') ? floatval($rawAmount) : 0;
        
        // CRITICAL: If payment equals credit, it's wrong - reset to 0 (empty)
        $invoiceCredit = (float) ($invoice['credit'] ?? 0);
        if ($amount > 0 && $invoiceCredit > 0 && abs($amount - $invoiceCredit) < 0.01) {
            // Payment matches credit - this is wrong, reset to empty
            $amount = 0;
            $this->payments[$invoiceId] = null;
        }
        
        // Get invoice details
        $originalAmount = (float) ($invoice['original_amount'] ?? 0);
        $creditApplied = (float) ($invoice['credit'] ?? 0);
        
        // Calculate amount due before payment: original - credit
        $amountDueBeforePayment = max(0, $originalAmount - $creditApplied);
        
        // Max payment is the original amount (allows paying full invoice even after credit)
        $maxPayment = $originalAmount;

        // Validation: Payment cannot exceed original amount
        if ($amount > $maxPayment && $maxPayment > 0) {
            session()->flash('error', 'Payment amount (' . number_format($amount, 2) . ') cannot exceed the invoice original amount (' . number_format($maxPayment, 2) . ').');
            // Reset to maximum allowed
            $amount = $maxPayment;
            $this->payments[$invoiceId] = $amount;
        }
        
        // Ensure payment is not negative
        if ($amount < 0) {
            $amount = 0;
            $this->payments[$invoiceId] = 0;
        }

        // Calculate totals - treat null/empty as 0 for calculations
        $this->paymentAmount = array_sum(array_map(function($val) {
            return ($val === null || $val === '') ? 0 : floatval($val);
        }, $this->payments));
        $this->totalPayment = collect($this->payments)->sum(function($val) {
            return ($val === null || $val === '') ? 0 : floatval($val);
        });
        $this->overpayment = 0;  // Reset the overpayment each time to calculate fresh

        // Update invoice with validated payment
        $this->invoices = collect($this->invoices)->map(function ($inv) use ($invoiceId, $amount, $originalAmount) {
            if ((int) $inv['id'] === (int) $invoiceId) {
                // Update payment_applied in invoice array (this is what displays in the payment column)
                $inv['payment_applied'] = $amount;
                $creditApplied = (float) ($inv['credit'] ?? 0);
                $inv['amount_due'] = max(0, $originalAmount - $creditApplied - $amount);
            }
            return $inv;
        })->toArray();
        
        // Also sync payments array with payment_applied for consistency
        // This ensures wire:model displays the correct value
        $this->payments[$invoiceId] = ($amount > 0) ? $amount : null;
        
        // Force Livewire to detect the change
        $this->payments = array_merge([], $this->payments);

        $this->recalculateRemaining();
        $this->calculateTotals();
    }




    public function __updateSelectedInvoiceAmount($invoiceId)
    {
        $this->paymentAmount = array_sum(array_map(function($val) {
            return ($val === null || $val === '') ? 0 : floatval($val);
        }, $this->payments));
        $amount = floatval($this->payments[$invoiceId] ?? 0);
        $this->totalPayment = collect($this->payments)->sum(function($val) {
            return ($val === null || $val === '') ? 0 : floatval($val);
        });

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

            // Use payment_applied from invoice array (source of truth) instead of payments array
            $paymentApplied = (float) ($invoice['payment_applied'] ?? 0);
            $this->totalPayment += $paymentApplied;
        }

        // Recalculate available credits after totals are calculated
        $this->recalculateAvailableCredits();

        $this->discountAndCreditsApplied = max(0, $this->selectedTotalAmountDue - $this->totalPayment);
        $this->difference = $this->paymentAmount - $this->totalPayment;

        // dd($this->difference);
    }

    public function getCreditLimitProgress()
    {
        if (!$this->selectedCustomer || !$this->selectedCustomer->is_credit_customer) {
            return null;
        }

        // Get the maximum credit limit (use credit_limit_1_amount as primary, or credit_limit_2_amount if higher)
        $creditLimit1 = (float) ($this->selectedCustomer->credit_limit_1_amount ?? 0);
        $creditLimit2 = (float) ($this->selectedCustomer->credit_limit_2_amount ?? 0);
        $creditLimit = max($creditLimit1, $creditLimit2);

        if ($creditLimit <= 0) {
            return null; // No credit limit set
        }

        // Current outstanding balance (amount_due from all invoices)
        $outstandingBalance = (float) $this->totalAmountDue;

        // Calculate percentage used
        $percentageUsed = min(100, ($outstandingBalance / $creditLimit) * 100);
        $remainingCredit = max(0, $creditLimit - $outstandingBalance);

        return [
            'credit_limit' => $creditLimit,
            'outstanding_balance' => $outstandingBalance,
            'remaining_credit' => $remainingCredit,
            'percentage_used' => $percentageUsed,
            'credit_limit_1' => $creditLimit1,
            'credit_limit_2' => $creditLimit2,
        ];
    }

    public function clearPayment($invoiceId)
    {
        // Clear payment for specific invoice
        unset($this->payments[$invoiceId]);
        
        // Update invoice array to remove payment_applied
        $this->invoices = collect($this->invoices)->map(function ($invoice) use ($invoiceId) {
            if ($invoice['id'] == $invoiceId) {
                $invoice['payment_applied'] = 0;
                // Recalculate amount_due: original_amount - credit (payment is now 0)
                $originalAmount = (float) ($invoice['original_amount'] ?? 0);
                $creditApplied = (float) ($invoice['credit'] ?? 0);
                $invoice['amount_due'] = max(0, $originalAmount - $creditApplied);
            }
            return $invoice;
        })->toArray();
        
        $this->recalculateRemaining();
        $this->calculateTotals();
    }

    public function clearAllPayments()
    {
        // Clear all payments
        $this->payments = [];
        
        // Update all invoices to remove payment_applied
        $this->invoices = collect($this->invoices)->map(function ($invoice) {
            $invoice['payment_applied'] = 0;
            // Recalculate amount_due: original_amount - credit (payment is now 0)
            $originalAmount = (float) ($invoice['original_amount'] ?? 0);
            $creditApplied = (float) ($invoice['credit'] ?? 0);
            $invoice['amount_due'] = max(0, $originalAmount - $creditApplied);
            return $invoice;
        })->toArray();
        
        $this->paymentAmount = 0;
        $this->remainingBalance = 0;
        $this->recalculateRemaining();
        $this->calculateTotals();
    }

    public function recalculateAvailableCredits()
    {
        if (!$this->selectedCustomerId) {
            $this->availableCredits = 0;
            $this->customerAvlCredits = 0;
            return;
        }

        // Get total credits from database
        $this->totalCreditsFromDB = CustomerCredit::where('customer_id', $this->selectedCustomerId)
            ->where('amount', '>', 0)
            ->sum('amount');

        // Calculate total credits already allocated to invoices
        $totalCreditsAllocated = collect($this->invoices)->sum(fn($inv) => (float)($inv['credit'] ?? 0));

        // Available credits = total credits - allocated credits
        $remainingCredits = max(0, $this->totalCreditsFromDB - $totalCreditsAllocated);
        $this->availableCredits = $remainingCredits;
        $this->customerAvlCredits = $remainingCredits;
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
        $totalCash = collect($this->payments)->sum(function($val) {
            return ($val === null || $val === '') ? 0 : floatval($val);
        }) ?? 0;                          // new cash/check
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
            
            // CRITICAL: Clean up payments array - remove any invoice IDs that don't exist in current invoices list
            // This prevents errors from stale data (e.g., invoice ID 1 from a previous customer)
            $validInvoiceIds = collect($this->invoices)->pluck('id')->toArray();
            $cleanedPayments = [];
            $removedPayments = [];
            foreach ($this->payments as $invId => $amt) {
                $invId = (int) $invId;
                // Only include payments for invoices that exist in the current invoices list
                if ($invId > 0 && in_array($invId, $validInvoiceIds)) {
                    $cleanedPayments[$invId] = $amt;
                } else {
                    // Track removed payments for logging
                    if ($amt > 0) {
                        $removedPayments[$invId] = $amt;
                    }
                }
            }
            
            // Log if any payments were removed (for debugging)
            if (!empty($removedPayments)) {
                $removedTotal = array_sum($removedPayments);
                \Log::warning('Removed invalid payments from array', [
                    'customer_id' => $cust->id,
                    'customer_name' => $cust->name,
                    'removed_payments' => $removedPayments,
                    'removed_total' => $removedTotal,
                    'valid_invoice_ids' => $validInvoiceIds
                ]);
                
                // Show user-friendly warning (but don't block the save)
                session()->flash('warning', 'Some payment allocations were removed because they referenced invoices that are not in the current customer\'s invoice list. Removed amount: ' . number_format($removedTotal, 2));
            }
            
            $this->payments = $cleanedPayments;
            
            // CRITICAL: Validate all payment invoices belong to selected customer BEFORE processing
            foreach ($this->payments as $invId => $amt) {
                // Skip null, empty, or zero amounts
                if ($amt === null || $amt === '' || $amt <= 0) continue;
                
                // Validate invoice ID is a valid positive integer
                $invId = (int) $invId;
                if ($invId <= 0) {
                    throw new \Exception("Invalid invoice ID: {$invId}. Payment amount: {$amt}");
                }
                
                $inv = Invoice::find($invId);
                if (!$inv) {
                    throw new \Exception("Invoice ID {$invId} not found. This invoice may have been deleted or the payment data is stale. Please refresh the page and try again.");
                }
                if ($inv->customer_id != $cust->id) {
                    throw new \Exception("Invoice #{$inv->invoice_number} (ID: {$invId}) belongs to a different customer. This usually happens when payment data from a previous customer session is still in memory. The invalid payment has been removed. Please check your payment allocations and try again.");
                }
            }
            
            // CRITICAL: Validate all credit invoices belong to selected customer BEFORE processing
            foreach ($this->invoices as $invData) {
                $use = $invData['credit'] ?? 0;
                if ($use <= 0) continue;
                $inv = Invoice::find($invData['id']);
                if (!$inv) {
                    throw new \Exception("Invoice ID {$invData['id']} not found.");
                }
                if ($inv->customer_id != $cust->id) {
                    throw new \Exception("Invoice #{$inv->invoice_number} (ID: {$invData['id']}) does not belong to customer {$cust->name} (ID: {$cust->id}). Credit cannot be applied.");
                }
            }

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
                    // Skip null, empty, or zero amounts
                    if ($amt === null || $amt === '' || $amt <= 0)
                        continue;
                    
                    // Validate invoice ID is a valid positive integer
                    $invId = (int) $invId;
                    if ($invId <= 0) {
                        throw new \Exception("Invalid invoice ID: {$invId}. Payment amount: {$amt}");
                    }
                    
                    // CRITICAL: Validate that invoice belongs to the selected customer
                    $inv = Invoice::findOrFail($invId);
                    if ($inv->customer_id != $cust->id) {
                        throw new \Exception("Invoice #{$inv->invoice_number} (ID: {$invId}) does not belong to customer {$cust->name} (ID: {$cust->id}). Payment cannot be applied.");
                    }
                    
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

                    $inv->amount_due = max(0, $inv->amount_due - $amt);
                    $inv->payment_status = $inv->amount_due === 0 ? 'paid' : 'partial';
                    //$inv->status = $inv->amount_due === 0 ? 'invoiced' : 'invoicing';
                    $inv->status = $inv->amount_due === 0 ? 'invoiced' : 'invoiced';
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
                    
                    // CRITICAL: Validate that invoice belongs to the selected customer
                    $inv = Invoice::findOrFail($invData['id']);
                    if ($inv->customer_id != $cust->id) {
                        throw new \Exception("Invoice #{$inv->invoice_number} (ID: {$invData['id']}) does not belong to customer {$cust->name} (ID: {$cust->id}). Credit cannot be applied.");
                    }
                    
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

                    $inv->amount_due = max(0, $inv->amount_due - $use);
                    $inv->payment_status = $inv->amount_due === 0 ? 'paid' : 'partial';
                    $inv->status = $inv->amount_due === 0 ? 'invoiced' : 'invoicing';
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
        // CRITICAL: Sync payments array with payment_applied from invoices before rendering
        // This ensures wire:model displays the correct payment_applied value, not credit
        // payment_applied is ALWAYS the source of truth for the payment field
        foreach ($this->invoices as $invoice) {
            $invoiceId = $invoice['id'];
            $paymentApplied = (float) ($invoice['payment_applied'] ?? 0);
            
            // ALWAYS sync payment_applied to payments array (this is what wire:model uses)
            // If payment_applied is 0, set to null (empty field)
            // If payment_applied > 0, use that value (even if it equals credit - that's a data issue, not display issue)
            $this->payments[$invoiceId] = ($paymentApplied > 0) ? $paymentApplied : null;
        }

        $this->listeners = ['invoicesUpdated' => 'handleInvoicesUpdated'];
        $bodyAttributes = 'x-data="{ page: \'CustomerPayment\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
            x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                    $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
            :class="{\'dark bg-gray-900\': darkMode === true}"';

        return view('livewire.customer.customer-payment')
            ->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
