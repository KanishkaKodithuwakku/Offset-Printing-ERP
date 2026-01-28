<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Payment extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'customer_id',
        'payment_code',
        'user_id',
        'branch_id',
        'bank_id',
        'bank_branch_id',
        'entry_id',
        'amount',
        'date',
        'cheque_date',
        'method',         // "CA", "CH", "CR", "CA,CR", or "CH,CR"
        'check_number',
        'status',
        'deleted_by',
        'cancel_reason',
        'memo',
        'deleted_at'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Supplier::class, 'customer_id');
    }

    public function paymentDetails()
    {
        return $this->hasMany(PaymentDetail::class);
    }

    public function entry()
    {
        return $this->belongsTo(Entry::class);
    }


    public function customerCredit()
    {
        return $this->hasOne(CustomerCredit::class);
    }

    public function creditApplications()
    {
        return $this->hasManyThrough(
            CreditApplication::class,
            CustomerCredit::class,
            'payment_id',           // Foreign key on CustomerCredit table
            'customer_credit_id',   // Foreign key on CreditApplication table
            'id',                   // Local key on Payment table
            'id'                    // Local key on CustomerCredit table
        );
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    public function bankBranch()
    {
        return $this->belongsTo(BankBranch::class, 'bank_branch_id');
    }

    /**
     * Check if this payment has credit applied to pay invoices
     * Returns true ONLY if credit was USED to pay invoices (not if overpayment created credit)
     * 
     * Credit is used when:
     * - EntryItems debit Customer Credit ledger (dc='D') - this means credit was spent
     * - CreditApplications exist for invoices in this payment
     * 
     * Overpayment creates credit when:
     * - EntryItems credit Customer Credit ledger (dc='C') - this does NOT count as credit being used
     */
    public function hasCredit()
    {
        // Method 1: Check if Entry has EntryItems that DEBIT Customer Credit ledger
        // Debit (dc='D') means credit was USED to pay invoices
        // Credit (dc='C') means overpayment created credit (don't count this)
        if ($this->entry_id) {
            $customerCreditLedgerId = \App\Models\Ledger::where('name', 'Customer Credit')->value('id');
            if ($customerCreditLedgerId) {
                // Use eager-loaded relationship if available
                if ($this->relationLoaded('entry') && $this->entry && $this->entry->relationLoaded('entryitems')) {
                    $hasDebitEntry = $this->entry->entryitems
                        ->where('ledger_id', $customerCreditLedgerId)
                        ->where('dc', 'D') // Debit to Customer Credit means credit was used
                        ->isNotEmpty();
                    
                    if ($hasDebitEntry) {
                        return true;
                    }
                } else {
                    // Fallback to query if relationships not loaded
                    $hasDebitEntry = \App\Models\EntryItem::where('entry_id', $this->entry_id)
                        ->where('ledger_id', $customerCreditLedgerId)
                        ->where('dc', 'D') // Debit to Customer Credit means credit was used
                        ->exists();
                    
                    if ($hasDebitEntry) {
                        return true;
                    }
                }
            }
        }

        // Method 2: Check if any invoices in this payment have credit applications
        // This is the most reliable indicator - credit applications mean credit was used to pay invoices
        // We check CreditApplications directly by invoice_id, not through the relationship
        // because the creditApplications relationship can include credits created from overpayments
        // which doesn't mean credit was used in THIS payment
        $invoiceIds = [];
        if ($this->relationLoaded('paymentDetails')) {
            $invoiceIds = $this->paymentDetails->pluck('invoice_id')->filter()->unique()->toArray();
        } else {
            $invoiceIds = $this->paymentDetails()->pluck('invoice_id')->filter()->unique()->toArray();
        }
        
        if (!empty($invoiceIds)) {
            $hasCreditApplications = \App\Models\CreditApplication::whereIn('invoice_id', $invoiceIds)->exists();
            if ($hasCreditApplications) {
                return true;
            }
        }

        // Note: We do NOT check the creditApplications relationship because it can incorrectly
        // return credit applications from overpayments created by this payment, which doesn't
        // mean credit was used IN this payment. We only check CreditApplications by invoice_id
        // which is the reliable way to know if credit was used to pay invoices in this payment.
        
        // Note: We do NOT check for customerCredit creation (overpayment) because that's not
        // credit being used to pay - it's overpayment being stored as credit for future use
        
        return false;
    }

    /**
     * Get the display method string (e.g., "CA", "CH", "CR", "CA,CR", or "CH,CR")
     * 
     * Rules:
     * - Credit only → "CR"
     * - Cash only → "CA"
     * - Cheque only → "CH"
     * - Cash + Credit → "CA,CR"
     * - Cheque + Credit → "CH,CR"
     */
    public function getDisplayMethodAttribute()
    {
        $method = $this->method ?? '';
        
        // If method is already in the correct format (CR, CA,CH, CA,CR, CH,CR), return it directly
        // This handles new payments where method is saved correctly
        if (in_array($method, ['CR', 'CA', 'CH', 'CA,CR', 'CH,CR'])) {
            return $method;
        }
        
        // Fallback: For old records, determine method from EntryItems
        $hasCredit = $this->hasCredit();
        
        // If credit was used
        if ($hasCredit) {
            // DEFINITIVE CHECK: Check EntryItems for Bank/Cash ledger DEBITS
            // In double-entry accounting:
            // - When cash/cheque is RECEIVED: Debit Bank/Cash (dc='D'), Credit A/R (dc='C')
            // - When credit is USED: Debit Customer Credit (dc='D'), Credit A/R (dc='C')
            // For credit-only payments, NO EntryItems DEBIT Bank/Cash ledgers
            // For cash/cheque payments, EntryItems DO DEBIT Bank/Cash ledgers
            // This is the ONLY reliable indicator - ignore paymentDetails and method field
            $hasCashCheque = false;
            
            if ($this->entry_id) {
                $bankLedgerIds = \App\Models\Ledger::whereIn('type', ['bank', 'cash'])->pluck('id');
                if ($bankLedgerIds->isNotEmpty()) {
                    if ($this->relationLoaded('entry') && $this->entry && $this->entry->relationLoaded('entryitems')) {
                        // Check if ANY EntryItem DEBITS a Bank/Cash ledger
                        // Debit (dc='D') to Bank/Cash means cash/cheque was actually received
                        $hasCashCheque = $this->entry->entryitems
                            ->whereIn('ledger_id', $bankLedgerIds)
                            ->where('dc', 'D') // Debit to Bank/Cash means cash/cheque was received
                            ->isNotEmpty();
                    } else {
                        // Query version - check if EntryItems exist that DEBIT Bank/Cash
                        $hasCashCheque = \App\Models\EntryItem::where('entry_id', $this->entry_id)
                            ->whereIn('ledger_id', $bankLedgerIds)
                            ->where('dc', 'D') // Debit to Bank/Cash means cash/cheque was received
                            ->exists();
                    }
                }
            }
            
            // CRITICAL: EntryItems check is definitive
            // If NO EntryItems DEBIT Bank/Cash, then it's credit-only, regardless of:
            // - paymentDetails (might be incorrectly set)
            // - method field (might be incorrectly set to CA/CH)
            // - any other indicators
            
            // If credit-only (no Bank/Cash EntryItems found), show CR only
            if (!$hasCashCheque) {
                return 'CR';
            }

            // If both credit and cash/cheque detected (Bank/Cash EntryItems exist), show combined
            // Use method field to determine if it's CA,CR or CH,CR
            if ($hasCashCheque && $method) {
                return $method . ',CR';
            }

            // If cash/cheque detected but no method set, default to CA,CR
            if ($hasCashCheque) {
                return 'CA,CR'; // Default to cash if method not set
            }

            // Fallback: credit-only
            return 'CR';
        }

        // No credit used - show only payment method (CA or CH)
        if ($method) {
            return $method; // Return "CA" or "CH" as-is
        }

        return 'N/A';
    }
}
