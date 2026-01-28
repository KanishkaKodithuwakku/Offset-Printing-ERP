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

    #[On('bankLedgerSelected')]
    public function setBankLedger($ledgerId = null, $fieldType = null)
    {
        // Handle event dispatched as object from blade: { ledgerId: ..., fieldType: ... }
        if (is_array($ledgerId) && isset($ledgerId['ledgerId'])) {
            $this->ledger_id = $ledgerId['ledgerId'];
        } elseif ($ledgerId) {
            // Handle event dispatched with individual parameters
            $this->ledger_id = $ledgerId;
        }
        
        if ($this->ledger_id) {
            $this->ledger = Ledger::find($this->ledger_id);
        }
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
    public $remainingCreditToAllocate = 0;
    public $creditAllocations = []; // Track credit allocations: ['credit_id' => allocated_amount]
    public $currentInvoiceIdForCreditModal = null;
    
    public function loadCustomerCredits($requiredBalance, $excludeInvoiceId = null)
    {
        if (!$this->selectedCustomerId)
            return;

        // Calculate total credits already allocated in current session
        $alreadyAllocated = collect($this->invoices)->sum(fn($inv) => $inv['credit'] ?? 0);

        // Get total available credits from database
        $totalCreditsFromDB = CustomerCredit::where('customer_id', $this->selectedCustomerId)
            ->where('amount', '>', 0)
            ->sum('amount');

        // Calculate remaining available credits (total - already allocated)
        $remainingAvailableCredits = max(0, $totalCreditsFromDB - $alreadyAllocated);

        // Update customerAvlCredits to show remaining balance
        $this->customerAvlCredits = $remainingAvailableCredits;
        $this->availableCredits = $remainingAvailableCredits;

        // Get credits from database
        $credits = CustomerCredit::where('customer_id', $this->selectedCustomerId)
            ->where('amount', '>', 0)
            ->orderBy('created_at', 'asc')
            ->get();

        // Calculate how much of each credit has been allocated in current session
        // We need to track this per credit ID - aggregate across all invoices
        // Exclude the current invoice being edited (if provided)
        $creditAllocationsByCreditId = [];
        foreach ($this->invoices as $invoice) {
            // Skip the current invoice being edited
            if ($excludeInvoiceId && (int) $invoice['id'] === (int) $excludeInvoiceId) {
                continue;
            }
            
            // Check if this invoice has credit_details (new format)
            if (isset($invoice['credit_details']) && is_array($invoice['credit_details'])) {
                foreach ($invoice['credit_details'] as $creditDetail) {
                    $creditId = $creditDetail['credit_id'] ?? null;
                    $allocatedAmount = (float) ($creditDetail['amount'] ?? 0);
                    if ($creditId && $allocatedAmount > 0) {
                        $creditAllocationsByCreditId[$creditId] = ($creditAllocationsByCreditId[$creditId] ?? 0) + $allocatedAmount;
                    }
                }
            }
            // Also check credits_applied for backward compatibility
            elseif (isset($invoice['credits_applied']) && is_array($invoice['credits_applied'])) {
                foreach ($invoice['credits_applied'] as $creditDetail) {
                    $creditId = $creditDetail['credit_id'] ?? null;
                    $allocatedAmount = (float) ($creditDetail['amount'] ?? 0);
                    if ($creditId && $allocatedAmount > 0) {
                        $creditAllocationsByCreditId[$creditId] = ($creditAllocationsByCreditId[$creditId] ?? 0) + $allocatedAmount;
                    }
                }
            }
        }

        $this->credits = [];

        // Use the due amount (requiredBalance) as the limit, not the total available credits
        // This ensures AMT. TO USE shows the due amount, not the original credit amount
        $remainingBalance = $requiredBalance; // This is the due amount
        $totalDueAmount = $requiredBalance; // Store total due amount for validation
        
        // Initialize remaining credit to allocate (will be updated as user enters amounts)
        $this->remainingCreditToAllocate = $remainingAvailableCredits;

        foreach ($credits as $credit) {
            // Get original credit amount from database
            $originalCreditAmount = $credit->amount;
            
            // Calculate how much of this credit has been allocated in current session
            $alreadyAllocatedFromThisCredit = $creditAllocationsByCreditId[$credit->id] ?? 0;
            
            // Calculate remaining available amount for this credit
            $creditAvailable = max(0, $originalCreditAmount - $alreadyAllocatedFromThisCredit);
            
            if ($creditAvailable <= 0) {
                continue; // Skip credits that are fully used
            }
            
            // Calculate amountToUse: should not exceed the remaining balance (due amount)
            // This ensures AMT. TO USE shows the due amount, not the full credit amount
            $amountToUse = min($creditAvailable, $remainingBalance);
            if ($amountToUse <= 0) {
                continue;
            }

            // Round amountToUse to 2 decimal places
            $amountToUse = round($amountToUse, 2);

            // Calculate balance: original amount - already allocated - amount to use
            $creditBalance = max(0, $originalCreditAmount - $alreadyAllocatedFromThisCredit - $amountToUse);
            $creditBalance = round($creditBalance, 2);

            $this->credits[] = [
                'id' => $credit->id,
                'date' => $credit->created_at->format('Y-m-d'),
                'number' => $credit->credit_number ?? 'CR-' . $credit->id,
                'amount' => round($originalCreditAmount, 2), // Show original credit amount, rounded
                'amountToUse' => $amountToUse, // This will be limited to due amount, not original, rounded to 2 decimals
                'balance' => $creditBalance, // Balance after allocation, rounded to 2 decimals
                'alreadyAllocated' => round($alreadyAllocatedFromThisCredit, 2), // For reference, rounded
                'maxDueAmount' => round($totalDueAmount, 2), // Store due amount for validation, rounded
            ];
            $remainingBalance -= $amountToUse;

            if ($remainingBalance <= 0) {
                break; // No more needed
            }
        }

        // Calculate initial remaining credit to allocate based on loaded credits
        $totalAllocated = collect($this->credits)->sum('amountToUse');
        $this->remainingCreditToAllocate = max(0, $remainingAvailableCredits - $totalAllocated);

        $this->showCreditModal = true;
    }

    public $customerAvlCredits = null;

    public function loadCustomerTotalCredit()
    {
        if (!$this->selectedCustomerId) {
            $this->totalCredit = 0;
            $this->customerAvlCredits = 0;
            return;
        }

        // Calculate total credits from database
        $totalCreditsFromDB = CustomerCredit::where('customer_id', $this->selectedCustomerId)
            ->sum('amount');

        // Calculate already allocated credits in current session
        $alreadyAllocated = collect($this->invoices)->sum(fn($inv) => $inv['credit'] ?? 0);

        // Calculate remaining available credits
        $this->customerAvlCredits = max(0, $totalCreditsFromDB - $alreadyAllocated);
    }

    public function updateAvailableCredits()
    {
        if (!$this->selectedCustomerId) {
            $this->customerAvlCredits = 0;
            $this->availableCredits = 0;
            return;
        }

        // Calculate total credits from database
        $totalCreditsFromDB = CustomerCredit::where('customer_id', $this->selectedCustomerId)
            ->sum('amount');

        // Calculate already allocated credits in current session
        $alreadyAllocated = collect($this->invoices)->sum(fn($inv) => $inv['credit'] ?? 0);

        // Calculate remaining available credits
        $remainingCredits = max(0, $totalCreditsFromDB - $alreadyAllocated);
        $this->customerAvlCredits = $remainingCredits;
        $this->availableCredits = $remainingCredits;
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
        $creditDetails = [];

        // Sum all applied credit and track which credits were used
        foreach ($this->credits as $credit) {
            $amountToUse = (float) ($credit['amountToUse'] ?? 0);
            if ($amountToUse > 0) {
                $totalCreditUsed += $amountToUse;
                // Track which credit was used and how much
                $creditDetails[] = [
                    'credit_id' => $credit['id'],
                    'amount' => $amountToUse,
                ];
            }
        }

        // Replace invoice with updated values
        $this->invoices = collect($this->invoices)->map(function ($invoice) use ($invoiceId, $totalCreditUsed, $creditDetails) {
            if ((int) $invoice['id'] === $invoiceId) {
                $invoice['credit'] = $totalCreditUsed;
                // Calculate amount_due as: original_amount - credit - payment
                $originalAmount = floatval($invoice['original_amount'] ?? 0);
                $paymentAmount = floatval($this->payments[$invoiceId] ?? 0);
                $invoice['amount_due'] = max(0, $originalAmount - $totalCreditUsed - $paymentAmount);
                // Store credit details for tracking
                $invoice['credit_details'] = $creditDetails;
            } else {
                // Recalculate amount_due for other invoices too: original_amount - credit - payment
                $originalAmount = floatval($invoice['original_amount'] ?? 0);
                $creditAmount = floatval($invoice['credit'] ?? 0);
                $paymentAmount = floatval($this->payments[$invoice['id']] ?? 0);
                $invoice['amount_due'] = max(0, $originalAmount - $creditAmount - $paymentAmount);
            }
            return $invoice;
        })->toArray();

        // Update available credits after allocation
        $this->updateAvailableCredits();

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

        // Distribute the payment amount to invoices
        $this->distributePayment();
        $this->recalculateRemaining();
        
        // Force update to ensure UI reflects the distributed payments
        $this->dispatch('payment-distributed');
    }

    public function openCreditModal($invoiceId): void
    {
        $invoice = Invoice::find($invoiceId);
        
        // Get the invoice from the current session
        $sessionInvoice = collect($this->invoices)->firstWhere('id', $invoiceId);
        
        // Use the database amount_due as the base, then subtract any session payments
        // This ensures we show the correct due amount for credit allocation
        $dbAmountDue = floatval($sessionInvoice['amount_due_db'] ?? $sessionInvoice['amount_due'] ?? $invoice->amount_due ?? 0);
        $sessionPayment = floatval($this->payments[$invoiceId] ?? 0);
        $sessionCredit = floatval($sessionInvoice['credit'] ?? 0);
        
        // Calculate the actual due amount: database amount_due - session payment
        // (session credit is already included in database amount_due if it was saved)
        $invoiceAmountDue = max(0, $dbAmountDue - $sessionPayment);
        
        // For credit allocation, we can allocate up to the amount_due
        $requiredBalance = $invoiceAmountDue;

        $this->selectedInvoices = [
            [
                'id' => $invoiceId,
                'amount' => $sessionPayment,
            ]
        ];

        // Store current invoice ID to exclude it from already allocated calculation
        $this->currentInvoiceIdForCreditModal = $invoiceId;

        // Update available credits before opening modal
        $this->updateAvailableCredits();
        
        $this->loadCustomerCredits($requiredBalance, $invoiceId); // Pass required balance and current invoice ID
    }


    public function loadCustomerInvoices()
    {
        $this->invoices = Invoice::where('customer_id', $this->selectedCustomer->id)
            ->where('status', '!=', 'cancelled')
            // Exclude fully paid invoices: payment_status = 'paid' OR amount_due = 0
            ->where(function($query) {
                $query->where(function($q) {
                    // Include unpaid or partial invoices with amount_due > 0
                    $q->whereIn('payment_status', ['unpaid', 'partial'])
                      ->where('amount_due', '>', 0);
                })
                ->orWhere(function($q) {
                    // Include invoices with null payment_status and amount_due > 0
                    $q->whereNull('payment_status')
                      ->where('amount_due', '>', 0);
                });
            })
            ->get()
            ->filter(function ($invoice) {
                // Additional filter: exclude if fully paid (amount_due = 0 OR payment_status = 'paid')
                // This is a safety check in case the query above doesn't catch all cases
                return $invoice->amount_due > 0 && $invoice->payment_status !== 'paid';
            })
            ->map(function ($invoice) {
                // Don't load credit from database - credit already applied is reflected in amount_due
                // Only show credit amounts allocated in current session
                // The credit column in database is for reference, but we don't display it
                // because it's already been applied and is reflected in the amount_due calculation
                
                return [
                    'id' => $invoice->id,
                    'invoice_number' => $invoice->invoice_number,
                    'created_at' => $invoice->created_at,
                    'original_amount' => $invoice->total_amount,
                    'amount_due' => $invoice->amount_due, // Use database value (already has credit subtracted)
                    'amount_due_db' => $invoice->amount_due, // Store original DB value
                    'credit' => 0, // Don't show saved credit - only show session credit allocations
                    'credit_details' => [], // Track applied credits [{credit_id, amount}]
                    'credits_applied' => [], // Track applied credits [{credit_id, amount}]
                    'credit_total' => 0,     // For display and calculation
                    'payment_applied' => 0,     // For display and calculation
                ];
            })
            ->values() // Re-index array after filter
            ->toArray();

        $this->selectedInvoices = [];
        $this->payments = [];
        $this->selectAll = false;
        $this->paymentAmount = 0;

        // Update available credits when loading invoices
        $this->loadCustomerTotalCredit();
        $this->calculateTotals();
    }


    public function distributePayment()
    {
        logger('Customer is : ' . $this->customerId);
        logger('Distributing payment: ' . $this->paymentAmount);

        // Reset payments for invoices with amount_due > 0
        $this->payments = [];
        $remaining = $this->paymentAmount;

        // Filter invoices that have amount_due > 0 (after credit allocation) and sort by date
        // Use database amount_due as base, then subtract session credit and payment
        $filteredInvoices = collect($this->invoices)
            ->map(function ($invoice) {
                // Get database amount_due
                $dbAmountDue = floatval($invoice['amount_due_db'] ?? $invoice['amount_due'] ?? 0);
                $creditAmount = floatval($invoice['credit'] ?? 0);
                $paymentAmount = floatval($this->payments[$invoice['id']] ?? 0);
                
                // Calculate actual amount_due: database amount_due - session credit - session payment
                // If credit was allocated and amount_due becomes 0, don't apply payment
                $calculatedAmountDue = max(0, $dbAmountDue - $creditAmount - $paymentAmount);
                $invoice['calculated_amount_due'] = $calculatedAmountDue;
                return $invoice;
            })
            ->where('calculated_amount_due', '>', 0) // Only invoices with amount_due > 0 after credit
            ->sortBy('created_at');

        logger('Filtered invoices count: ' . $filteredInvoices->count());

        // Distribute payment only to invoices with amount_due > 0 (after credit allocation)
        foreach ($filteredInvoices as $invoice) {
            if ($remaining <= 0)
                break;

            $calculatedAmountDue = $invoice['calculated_amount_due'];
            $amt = min($calculatedAmountDue, $remaining);
            $this->payments[$invoice['id']] = $amt;
            $remaining -= $amt;
        }

        // Update payment_applied and recalculate amount_due for all invoices
        // Only apply payment if amount_due > 0 after credit allocation
        foreach ($this->invoices as &$invoice) {
            $invoiceId = $invoice['id'];
            
            // Get database amount_due and session credit
            $dbAmountDue = floatval($invoice['amount_due_db'] ?? $invoice['amount_due'] ?? 0);
            $creditAmount = floatval($invoice['credit'] ?? 0);
            
            // Calculate amount_due after credit: database amount_due - session credit
            $amountDueAfterCredit = max(0, $dbAmountDue - $creditAmount);
            
            // Only apply payment if amount_due > 0 after credit
            if ($amountDueAfterCredit > 0) {
                $paymentAmount = floatval($this->payments[$invoiceId] ?? 0);
                $invoice['payment_applied'] = $paymentAmount;
                // Recalculate amount_due: database amount_due - credit - payment
                $invoice['amount_due'] = max(0, $amountDueAfterCredit - $paymentAmount);
            } else {
                // If amount_due is 0 after credit, don't apply payment
                $invoice['payment_applied'] = 0;
                $invoice['amount_due'] = 0;
                // Remove payment from payments array if it exists
                unset($this->payments[$invoiceId]);
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

        // Get already allocated amount for this credit from current session
        $alreadyAllocated = $credit['alreadyAllocated'] ?? 0;
        
        // Calculate maximum available credit (original amount - already allocated)
        $maxAvailableCredit = max(0, $credit['amount'] - $alreadyAllocated);
        
        // Calculate remaining due amount (total due - already allocated in other credits)
        $totalAllocatedInOtherCredits = collect($this->credits)->sum(function($c, $i) use ($index) {
            return ($i !== $index) ? ($c['amountToUse'] ?? 0) : 0;
        });
        $maxDueAmount = floatval($credit['maxDueAmount'] ?? 0);
        $remainingDueAmount = max(0, $maxDueAmount - $totalAllocatedInOtherCredits);
        
        // Limit amountToUse to both: available credit AND remaining due amount
        $maxAllowed = min($maxAvailableCredit, $remainingDueAmount);

        // Ensure the amount does not exceed available credit OR due amount
        if ($credit['amountToUse'] > $maxAllowed) {
            $credit['amountToUse'] = $maxAllowed;
        }

        // Optional: Round to 2 decimal places
        $credit['amountToUse'] = round($credit['amountToUse'], 2);

        // Update credit balance: original amount - already allocated - amount to use
        $credit['balance'] = round($credit['amount'] - $alreadyAllocated - $credit['amountToUse'], 2);

        // Update remaining credit to allocate
        $totalAllocated = collect($this->credits)->sum('amountToUse');
        $this->remainingCreditToAllocate = max(0, $this->availableCredits - $totalAllocated);
    }


    public $overpayment = 0;
    public function updateSelectedInvoiceAmount($invoiceId)
    {
        // Get the current payment value - handle empty strings and preserve decimals
        $currentValue = $this->payments[$invoiceId] ?? '';
        
        // Handle empty or invalid values
        if ($currentValue === '' || $currentValue === null) {
            $amount = 0;
        } else {
            // Convert to float and round to 2 decimals for final validation
            $amount = is_numeric($currentValue) ? round(floatval($currentValue), 2) : 0;
        }
        
        // Normalize and round other payments to 2 decimals to avoid floating point artifacts
        $this->payments = array_map(function ($value) {
            if ($value === '' || $value === null) {
                return 0;
            }
            return is_numeric($value) ? round(floatval($value), 2) : 0;
        }, $this->payments);
        
        // Update the current invoice payment with the rounded amount
        $this->payments[$invoiceId] = $amount;
        
        // Calculate remaining balance before this payment
        // Sum all payments except the current invoice
        $currentAllocated = 0;
        foreach ($this->payments as $key => $val) {
            if ($key != $invoiceId) {
                $currentAllocated += floatval($val);
            }
        }
        $remainingBalance = max(0, $this->initialPayment - $currentAllocated);
        
        // Get the invoice to check max payment
        $invoice = collect($this->invoices)->firstWhere('id', $invoiceId);
        if ($invoice) {
            // Get the database amount_due (this already accounts for previous payments)
            $dbAmountDue = floatval($invoice['amount_due_db'] ?? $invoice['amount_due'] ?? 0);
            $creditAmount = floatval($invoice['credit'] ?? 0);
            
            // Calculate amount_due after credit: database amount_due - session credit
            // The database amount_due already accounts for previous payments, so we only subtract session credit
            $amountDueAfterCredit = max(0, $dbAmountDue - $creditAmount);
            
            // Only allow payment if amount_due > 0 after credit
            if ($amountDueAfterCredit > 0) {
                // Calculate max payment: min of (amount_due after credit, remaining balance to allocate)
                $maxPaymentByDue = $amountDueAfterCredit;
                $maxPaymentByRemaining = $remainingBalance;
                $maxPayment = min($maxPaymentByDue, $maxPaymentByRemaining);
                
                // Limit amount to max payment - this ensures payment cannot exceed AMT. DUE
                if ($amount > $maxPaymentByDue) {
                    $amount = $maxPaymentByDue;
                }
                $amount = min($amount, max(0, $maxPayment));
                $this->payments[$invoiceId] = $amount;
            } else {
                // If amount_due is 0 after credit, don't allow payment
                $this->payments[$invoiceId] = 0;
                $amount = 0;
            }
        } else {
            // If invoice not found, still limit by remaining balance
            $amount = min($amount, max(0, $remainingBalance));
            $this->payments[$invoiceId] = $amount;
        }

        $this->paymentAmount = array_sum(array_map('floatval', $this->payments));
        $this->totalPayment = collect($this->payments)->sum();
        $this->overpayment = 0;  // Reset the overpayment each time to calculate fresh

        // Update payment_applied and recalculate amount_due for each invoice
        // Only apply payment if amount_due > 0 after credit allocation
        foreach ($this->invoices as &$invoice) {
            $invoiceIdKey = $invoice['id'];
            
            // Get database amount_due and session credit
            $dbAmountDue = floatval($invoice['amount_due_db'] ?? $invoice['amount_due'] ?? 0);
            $creditAmount = floatval($invoice['credit'] ?? 0);
            
            // Calculate amount_due after credit: database amount_due - session credit
            $amountDueAfterCredit = max(0, $dbAmountDue - $creditAmount);
            
            // Only apply payment if amount_due > 0 after credit
            if ($amountDueAfterCredit > 0) {
                $paymentAmount = floatval($this->payments[$invoiceIdKey] ?? 0);
                $invoice['payment_applied'] = $paymentAmount;
                // Recalculate amount_due: database amount_due - credit - payment
                $invoice['amount_due'] = max(0, $amountDueAfterCredit - $paymentAmount);
                
                // Check for overpayment condition (payment + credit > original)
                $originalAmount = floatval($invoice['original_amount'] ?? 0);
                $totalApplied = $creditAmount + $paymentAmount;
                if ($totalApplied > $originalAmount) {
                    // Collect overpayments
                    $this->overpayment += $totalApplied - $originalAmount;
                }
            } else {
                // If amount_due is 0 after credit, don't apply payment
                $invoice['payment_applied'] = 0;
                $invoice['amount_due'] = 0;
                // Remove payment from payments array if it exists
                unset($this->payments[$invoiceIdKey]);
            }
        }
        $this->recalculateRemaining();
        $this->calculateTotals();
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

        // Calculate amount_due: if credit or payment allocated in CURRENT SESSION, calculate dynamically
        // Otherwise, use database amount_due (which already has saved credit subtracted)
        foreach ($this->invoices as &$invoice) {
            $originalAmount = floatval($invoice['original_amount'] ?? 0);
            // Get credit from session (only shows credit allocated in current session, not saved credit)
            $sessionCreditAmount = floatval($invoice['credit'] ?? 0);
            // Get payment from session (may include newly allocated payment)
            $sessionPaymentAmount = floatval($this->payments[$invoice['id']] ?? 0);
            
            // If there's session credit or payment, calculate from database amount_due
            // Otherwise, use database amount_due directly
            if ($sessionCreditAmount > 0 || $sessionPaymentAmount > 0) {
                $dbAmountDue = floatval($invoice['amount_due_db'] ?? $invoice['amount_due'] ?? 0);
                // For session credit: subtract from database amount_due (which already has saved credit)
                // For session payment: subtract from database amount_due
                $invoice['amount_due'] = max(0, $dbAmountDue - $sessionCreditAmount - $sessionPaymentAmount);
            } else {
                // No session changes, use database amount_due directly
                $invoice['amount_due'] = floatval($invoice['amount_due_db'] ?? $invoice['amount_due'] ?? 0);
            }
            
            $invoice['payment_applied'] = $sessionPaymentAmount;

            $this->totalOriginalAmount += $originalAmount;
            $this->totalAmountDue += $invoice['amount_due'];
            $this->totalCredit += $sessionCreditAmount; // Use session credit amount (only current session)

            if (in_array($invoice['id'], array_column($this->selectedInvoices, 'id'))) {
                $this->selectedTotalAmountDue += $invoice['amount_due'];
            }

            $this->totalPayment += $sessionPaymentAmount;
        }

        $this->discountAndCreditsApplied = max(0, $this->selectedTotalAmountDue - $this->totalPayment);
        $this->difference = $this->paymentAmount - $this->totalPayment;

        // Update available credits to reflect current allocation
        $this->updateAvailableCredits();
    }

    public function generateNextEntryNumber($label)
    {
        $entryType = EntryType::where('label', $label)->firstOrFail();
        $lastEntry = Entry::where('entrytype_id', $entryType->id)->latest()->first();
        $next = $lastEntry ? ((int) $lastEntry->number + 1) : 1;
        return str_pad($next, $entryType->zero_padding ?? 0, '0', STR_PAD_LEFT);
    }

    /**
     * Helper method to build EntryItem data with conditional foreign currency columns
     */
    private function buildEntryItemData($baseData, $exchangeRate, $currency)
    {
        $data = $baseData;
        
        // Only add foreign currency columns if they exist in the table
        if (DB::getSchemaBuilder()->hasColumn('entryitems', 'amount_foreign')) {
            $data['amount_foreign'] = ($baseData['amount'] ?? 0) * $exchangeRate;
        }
        if (DB::getSchemaBuilder()->hasColumn('entryitems', 'currency_id')) {
            $data['currency_id'] = $currency ? $currency->id : null;
        }
        
        return $data;
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

            // Get currency for exchange rate
            $currency = null;
            $exchangeRate = 1.0;
            try {
                if (DB::getSchemaBuilder()->hasTable('currencies')) {
                    $currency = DB::table('currencies')->first();
                    $exchangeRate = $currency ? (float) ($currency->exchange_rate ?? 1.0) : 1.0;
                }
            } catch (\Exception $e) {
                // Table doesn't exist, use default exchange rate of 1.0
                $exchangeRate = 1.0;
            }
            $totalAmount = $totalCash + $totalCredit;

            // 2) Create the single "receipt" journal entry
            $entryData = [
                'entrytype_id' => $receiptTypeId,
                'number' => Entry::getNextNumber($receiptTypeId),
                'customer_id' => $cust->id,
                'branch_id' => $branchId,
                'date' => now(),
                'narration' => $this->memo ?: "Payment from {$cust->name}",
                'dr_total' => $totalAmount,
                'cr_total' => 0,  // will fill in below
                'check_no' => $this->check_number ?: null,
                'method' => $this->payment_method ?: null,
            ];
            
            // Only add foreign currency columns if they exist in the table
            if (DB::getSchemaBuilder()->hasColumn('entries', 'dr_total_foreign')) {
                $entryData['dr_total_foreign'] = $totalAmount * $exchangeRate;
            }
            if (DB::getSchemaBuilder()->hasColumn('entries', 'cr_total_foreign')) {
                $entryData['cr_total_foreign'] = 0;  // will fill in below
            }
            if (DB::getSchemaBuilder()->hasColumn('entries', 'currency_id')) {
                $entryData['currency_id'] = $currency ? $currency->id : null;
            }
            
            $entry = Entry::create($entryData);

            $allocated = 0;

            $paymentId = null;

            // 3) CASH/CHECK portion
            if ($totalCash > 0) {
                // a) debit bank
                EntryItem::create($this->buildEntryItemData([
                    'entry_id' => $entry->id,
                    'ledger_id' => $bankLedgerId,
                    'dc' => 'D',
                    'amount' => $this->initialPayment,
                    'customer_id' => $cust->id,
                    'branch_id' => $branchId,
                    'check_no' => $this->check_number ?: null,
                    'method' => $this->payment_method ?: null,
                ], $exchangeRate, $currency));

                // b) record one Payment model
                // Determine method: "CA", "CH", "CA,CR", or "CH,CR"
                $paymentMethod = $this->payment_method; // CA or CH
                if ($totalCredit > 0) {
                    $paymentMethod = $this->payment_method . ',CR'; // CA,CR or CH,CR
                }
                
                $payment = Payment::create([
                    'customer_id' => $cust->id,
                    'entry_id' => $entry->id,
                    'amount' => $this->initialPayment,
                    'date' => now(),
                    'method' => $paymentMethod, // Save combined method: CA,CR or CH,CR
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
                // Track invoices with payments for later combination with credits
                $invoicesWithPayments = [];
                
                foreach ($this->payments as $invId => $amt) {
                    // Ensure amount is a valid number
                    $amt = floatval($amt);
                    if ($amt <= 0)
                        continue;
                    $allocated += $amt;
                    
                    // Log payment allocation for debugging
                    logger("Processing payment for invoice {$invId}: amount = {$amt}");

                    EntryItem::create($this->buildEntryItemData([
                        'entry_id' => $entry->id,
                        'ledger_id' => $arLedgerId,
                        'dc' => 'C',
                        'amount' => $amt,
                        'customer_id' => $cust->id,
                        'branch_id' => $branchId,
                        'check_no' => $this->check_number ?: null,
                        'method' => $this->payment_method ?: null,
                    ], $exchangeRate, $currency));

                    // Check if invoice also has credit - will combine in credit section
                    $invoiceData = collect($this->invoices)->firstWhere('id', $invId);
                    $creditAmount = floatval($invoiceData['credit'] ?? 0);
                    
                    if ($creditAmount > 0) {
                        // Store for combination in credit section
                        $invoicesWithPayments[$invId] = $amt;
                        // Don't create PaymentDetail here - will be created in credit section with combined amount
                        // Don't update invoice here - will be updated in credit section with both credit and payment
                        logger("Payment {$amt} for invoice {$invId} stored for combination with credit {$creditAmount} in credit section");
                    } else {
                        // Only payment, no credit - create PaymentDetail now
                        PaymentDetail::create([
                            'payment_id' => $payment->id,
                            'invoice_id' => $invId,
                            'branch_id' => $branchId,
                            'amount' => $amt,
                            'is_credit' => 0,
                        ]);
                        
                        // Get the original invoice amount directly from database
                        $inv = Invoice::findOrFail($invId);
                        $originalAmount = floatval($inv->total_amount);
                        
                        // Get payment amount from database (previous sessions, before this payment)
                        // We need to get payments that existed before we created the PaymentDetail above
                        $dbPaymentAmount = PaymentDetail::where('invoice_id', $invId)
                            ->where('is_credit', 0)
                            ->where('payment_id', '!=', $payment->id) // Exclude payments from current transaction
                            ->sum('amount');
                        $dbPaymentAmount = floatval($dbPaymentAmount);
                        
                        // Total payment = current payment + previous payments
                        $totalPaymentAmount = $amt + $dbPaymentAmount;
                        
                        // Calculate amount_due correctly: original_amount - payment (no credit)
                        $newAmountDue = max(0, $originalAmount - $totalPaymentAmount);
                        $newPaymentStatus = $newAmountDue === 0 ? 'paid' : 'partial';
                        
                        // Use direct database update to ensure it's persisted
                        DB::table('invoices')
                            ->where('id', $invId)
                            ->update([
                                'amount_due' => $newAmountDue,
                                'payment_status' => $newPaymentStatus,
                                'status' => 'invoiced',
                                'updated_at' => now(),
                            ]);
                        
                        // Refresh the model
                        $inv->refresh();
                        
                        // Log for debugging
                        logger("Payment-only saved for invoice {$invId}: original_amount = {$originalAmount}, amount_due = {$newAmountDue}, payment_status = {$newPaymentStatus}, current_payment = {$amt}, db_payment = {$dbPaymentAmount}, total_payment = {$totalPaymentAmount}");
                    }
                }
            }

            // 4) CREDIT portion
            if ($totalCredit > 0) {
                if ($totalCash == 0) {
                    // Credit-only payment - method should be "CR"
                    $payment = Payment::create([
                        'customer_id' => $cust->id,
                        'entry_id' => $entry->id,
                        'amount' => $this->initialPayment ?? 0,
                        'date' => now(),
                        'method' => 'CR', // Credit-only payment
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

                // Track which invoices have been processed to combine credit and payment
                $processedInvoices = [];

                foreach ($this->invoices as $invData) {
                    $use = $invData['credit'] ?? 0;
                    if ($use <= 0)
                        continue;
                    $allocated += $use;

                    EntryItem::create($this->buildEntryItemData([
                        'entry_id' => $entry->id,
                        'ledger_id' => $custCreditLedgerId,
                        'dc' => 'D',
                        'amount' => $use,
                        'customer_id' => $cust->id,
                        'branch_id' => $branchId,
                        'check_no' => $this->check_number ?: null,
                        'method' => $this->payment_method ?: null,
                    ], $exchangeRate, $currency));

                    EntryItem::create($this->buildEntryItemData([
                        'entry_id' => $entry->id,
                        'ledger_id' => $arLedgerId,
                        'dc' => 'C',
                        'amount' => $use,
                        'customer_id' => $cust->id,
                        'branch_id' => $branchId,
                        'check_no' => $this->check_number ?: null,
                        'method' => $this->payment_method ?: null,
                    ], $exchangeRate, $currency));

                    // Check if this invoice also has a payment (cash/check) from current session
                    $sessionPaymentAmount = floatval($this->payments[$invData['id']] ?? 0);
                    
                    // Get payment amount from database (previous sessions, excluding current transaction)
                    $dbPaymentAmount = PaymentDetail::where('invoice_id', $invData['id'])
                        ->where('is_credit', 0)
                        ->where('payment_id', '!=', $paymentId) // Exclude payments from current transaction
                        ->sum('amount');
                    $dbPaymentAmount = floatval($dbPaymentAmount);
                    
                    // Total payment = session payment + database payment (from previous sessions)
                    $totalPaymentAmount = $sessionPaymentAmount + $dbPaymentAmount;
                    
                    // TOT.PAID.AMT = credit + current session payment (for this PaymentDetail record)
                    // Note: Previous payments are already in separate PaymentDetail records
                    $totalPaidAmount = $use + $sessionPaymentAmount;
                    
                    // Check if PaymentDetail already exists (created in payment section for invoices with both credit and payment)
                    $existingPaymentDetail = PaymentDetail::where('payment_id', $paymentId)
                        ->where('invoice_id', $invData['id'])
                        ->where('is_credit', 0)
                        ->first();
                    
                    if ($existingPaymentDetail) {
                        // Update existing PaymentDetail to include credit amount
                        $existingPaymentDetail->amount = $totalPaidAmount;
                        $existingPaymentDetail->save();
                    } else {
                        // Create PaymentDetail for credit (or credit + payment if payment exists)
                        PaymentDetail::create([
                            'payment_id' => $paymentId,
                            'invoice_id' => $invData['id'],
                            'branch_id' => $branchId,
                            'amount' => $totalPaidAmount, // TOT.PAID.AMT: credit + current session payment
                            'is_credit' => 0, // Mark as payment (but includes credit) for receipt display
                        ]);
                    }
                    
                    $processedInvoices[] = $invData['id'];

                    // Process credit_details to create CreditApplication records for each credit used
                    $creditDetails = $invData['credit_details'] ?? $invData['credits_applied'] ?? [];
                    
                    foreach ($creditDetails as $creditDetail) {
                        $creditId = $creditDetail['credit_id'] ?? null;
                        $creditAmount = floatval($creditDetail['amount'] ?? 0);
                        
                        if ($creditId && $creditAmount > 0) {
                            // Create CreditApplication record
                            DB::table('credit_applications')->insert([
                                'customer_credit_id' => $creditId,
                                'invoice_id' => $invData['id'],
                                'amount_applied' => $creditAmount,
                                'applied_date' => now(),
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                            
                            // Update CustomerCredit amount
                            $creditRecord = CustomerCredit::find($creditId);
                            if ($creditRecord) {
                                $creditRecord->amount = max(0, $creditRecord->amount - $creditAmount);
                                $creditRecord->save();
                            }
                        }
                    }

                    // Get the original invoice amount directly from database (before any updates)
                    $inv = Invoice::findOrFail($invData['id']);
                    $originalAmount = floatval($inv->total_amount);
                    
                    // Get payment amount from current session
                    $sessionPaymentAmount = floatval($this->payments[$invData['id']] ?? 0);
                    
                    // Get payment amount from database (previous sessions, excluding the one we might create in this transaction)
                    // We need to get payments that were saved before this transaction
                    $dbPaymentAmount = PaymentDetail::where('invoice_id', $invData['id'])
                        ->where('is_credit', 0)
                        ->where('payment_id', '!=', $paymentId) // Exclude payments from current transaction
                        ->sum('amount');
                    $dbPaymentAmount = floatval($dbPaymentAmount);
                    
                    // Total payment = session payment + database payment (from previous sessions)
                    $totalPaymentAmount = $sessionPaymentAmount + $dbPaymentAmount;
                    
                    // Calculate amount_due correctly: original_amount - credit - (session_payment + db_payment)
                    // Use original total_amount, not current amount_due, to avoid double-counting
                    $newAmountDue = max(0, $originalAmount - $use - $totalPaymentAmount);
                    $newPaymentStatus = $newAmountDue === 0 ? 'paid' : 'partial';
                    
                    // Use direct database update to ensure it's persisted
                    DB::table('invoices')
                        ->where('id', $invData['id'])
                        ->update([
                            'amount_due' => $newAmountDue,
                            'payment_status' => $newPaymentStatus,
                            'status' => 'invoiced',
                            'updated_at' => now(),
                        ]);
                    
                    // Also update credit field if column exists
                    if (DB::getSchemaBuilder()->hasColumn('invoices', 'credit')) {
                        DB::table('invoices')
                            ->where('id', $invData['id'])
                            ->update(['credit' => $use]);
                    }
                    
                    // Refresh the model
                    $inv->refresh();
                    
                    // Log for debugging
                    logger("Credit allocation saved for invoice {$invData['id']}: original_amount = {$originalAmount}, amount_due = {$newAmountDue}, payment_status = {$newPaymentStatus}, credit = {$use}, session_payment = {$sessionPaymentAmount}, db_payment = {$dbPaymentAmount}, total_payment = {$totalPaymentAmount}");
                    
                    // Log for debugging
                    logger("Invoice {$invData['id']} saved (credit section): amount_due = {$newAmountDue}, original = {$originalAmount}, credit = {$use}, total_payment = {$totalPaymentAmount}");
                }
            }

            // 5) Finalize journal totals (before remainingBalance)
            $entry->cr_total = $allocated;
            if (DB::getSchemaBuilder()->hasColumn('entries', 'cr_total_foreign')) {
                $entry->cr_total_foreign = $allocated * $exchangeRate;
            }

            // 6) NOW HANDLE ANY remainingBalance:
            //    (you've been keeping $this->remainingBalance = initialPayment - allocated)
            if ($this->remainingBalance > 0) {
                // a) post the GL line to Customer Credit
                EntryItem::create($this->buildEntryItemData([
                    'entry_id' => $entry->id,
                    'ledger_id' => $custCreditLedgerId,
                    'dc' => 'C',
                    'amount' => $this->remainingBalance,
                    'customer_id' => $cust->id,
                    'branch_id' => $branchId,
                    'check_no' => $this->check_number ?: null,
                    'method' => $this->payment_method ?: null,
                ], $exchangeRate, $currency));

                // Add remainingBalance to credit totals
                $entry->cr_total += $this->remainingBalance;
                if (DB::getSchemaBuilder()->hasColumn('entries', 'cr_total_foreign')) {
                    $entry->cr_total_foreign += $this->remainingBalance * $exchangeRate;
                }

                // b) persist it as a new CustomerCredit
                $cc = CustomerCredit::firstOrNew([
                    'customer_id' => $cust->id,
                ]);
                $cc->amount = ($cc->amount ?? 0) + $this->remainingBalance;
                $cc->date = now();
                $cc->payment_id = $payment->id ?? null;
                $cc->save();
            }

            // Save entry with final totals
            $entry->save();

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
        // Normalize payment inputs to 2 decimals on every render to avoid float artifacts
        $this->payments = array_map(function ($value) {
            return is_numeric($value) ? round((float) $value, 2) : 0;
        }, $this->payments ?? []);

        // Calculate amount_due: if credit or payment allocated in CURRENT SESSION, calculate dynamically
        // Otherwise, use database amount_due (which already has saved credit subtracted)
        foreach ($this->invoices as &$invoice) {
            // Get credit from session (only shows credit allocated in current session, not saved credit)
            $sessionCreditAmount = floatval($invoice['credit'] ?? 0);
            // Get payment from session (may include newly allocated payment)
            $sessionPaymentAmount = floatval($this->payments[$invoice['id']] ?? 0);
            
            // If there's session credit or payment, calculate from original_amount
            // Otherwise, use database amount_due (which already has saved credit/payment subtracted)
            if ($sessionCreditAmount > 0 || $sessionPaymentAmount > 0) {
                $originalAmount = floatval($invoice['original_amount'] ?? 0);
                // Calculate amount_due: original_amount - session_credit - session_payment
                // Note: saved credit is already in amount_due_db, so we only subtract session credit
                $dbAmountDue = floatval($invoice['amount_due_db'] ?? $invoice['amount_due'] ?? 0);
                // For session credit: subtract from database amount_due (which already has saved credit)
                // For session payment: subtract from database amount_due
                $invoice['amount_due'] = max(0, $dbAmountDue - $sessionCreditAmount - $sessionPaymentAmount);
            } else {
                // No session changes, use database amount_due directly
                $invoice['amount_due'] = floatval($invoice['amount_due_db'] ?? $invoice['amount_due'] ?? 0);
            }
            
            $invoice['payment_applied'] = $sessionPaymentAmount;
        }

        // Recalculate totals based on normalized values
        $this->paymentAmount = array_sum(array_map('floatval', $this->payments));
        $this->totalPayment = collect($this->payments)->sum();

        $this->listeners = ['invoicesUpdated' => 'handleInvoicesUpdated'];
        $bodyAttributes = 'x-data="{ page: \'CustomerPayment\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
            x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                    $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
            :class="{\'dark bg-gray-900\': darkMode === true}"';

        return view('livewire.customer.customer-payment')
            ->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
