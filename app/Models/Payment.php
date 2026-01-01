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
        'method',
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
     * Get the display method string (e.g., "CA,CR", "CH,CR", "Cash", "Cheque", or "CR")
     */
    public function getDisplayMethodAttribute()
    {
        $hasCredit = $this->hasCredit();
        $method = $this->method ?? '';

        // If credit was used
        if ($hasCredit) {
            // Check if there's actual cash/cheque payment
            $hasCashCheque = false;
            if ($this->relationLoaded('paymentDetails')) {
                $hasCashCheque = $this->paymentDetails->where('is_credit', 0)->isNotEmpty();
            } else {
                $hasCashCheque = $this->paymentDetails()->where('is_credit', 0)->exists();
            }
            
            // Also check payment amount as fallback
            if (!$hasCashCheque && $this->amount > 0) {
                $hasCashCheque = true;
            }

            // If credit-only (no cash/cheque), show CR only
            if (!$hasCashCheque) {
                return 'CR';
            }

            // If both credit and cash/cheque, show combined
            if ($hasCashCheque && $method) {
                return $method . ',CR';
            }

            // If only credit (no method set)
            return 'CR';
        }

        // No credit used - show only payment method (cheque-only or cash-only payments)
        if ($method) {
            return match($method) {
                'CA' => 'Cash',      // Cash-only payment
                'CH' => 'Cheque',    // Cheque-only payment
                default => $method
            };
        }

        return 'N/A';
    }
}
