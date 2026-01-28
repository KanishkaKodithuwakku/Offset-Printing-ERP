<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Payment;
use App\Models\VendorPayment;
use App\Models\VendorBillPayment;
use App\Models\Entry;
use App\Models\EntryItem;
use App\Models\EntryType;
use Illuminate\Support\Facades\DB;

class BackfillPaymentCheckNumbers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:backfill-check-numbers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backfill check numbers and payment methods from payments to entries and entryitems tables';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting backfill of check numbers and methods...');
        
        $customerUpdated = $this->backfillCustomerPayments();
        $vendorUpdated = $this->backfillVendorPayments();
        
        $this->info("✅ Customer payments: Updated {$customerUpdated['entries']} entries and {$customerUpdated['entryitems']} entryitems");
        $this->info("✅ Vendor payments: Updated {$vendorUpdated['entries']} entries and {$vendorUpdated['entryitems']} entryitems");
        $this->info('Backfill completed successfully!');
        
        return 0;
    }

    /**
     * Backfill check numbers and methods from customer payments
     */
    private function backfillCustomerPayments()
    {
        $entriesUpdated = 0;
        $entryitemsUpdated = 0;
        
        // Get all customer payments that have check_number or method
        $payments = Payment::where(function($query) {
            $query->whereNotNull('check_number')
                  ->orWhereNotNull('method');
        })
        ->whereNotNull('entry_id')
        ->get();
        
        $this->info("Found {$payments->count()} customer payments to process...");
        
        $bar = $this->output->createProgressBar($payments->count());
        $bar->start();
        
        foreach ($payments as $payment) {
            $bar->advance();
            // Update the entry
            $entry = Entry::find($payment->entry_id);
            if ($entry) {
                $updateData = [];
                
                if ($payment->check_number && !$entry->check_no) {
                    $updateData['check_no'] = $payment->check_number;
                }
                
                if ($payment->method && !$entry->method) {
                    $updateData['method'] = $payment->method;
                }
                
                if (!empty($updateData)) {
                    $entry->update($updateData);
                    $entriesUpdated++;
                }
                
                // Update all entryitems for this entry
                $entryitems = EntryItem::where('entry_id', $entry->id)->get();
                foreach ($entryitems as $entryitem) {
                    $itemUpdateData = [];
                    
                    if ($payment->check_number && !$entryitem->check_no) {
                        $itemUpdateData['check_no'] = $payment->check_number;
                    }
                    
                    if ($payment->method && !$entryitem->method) {
                        $itemUpdateData['method'] = $payment->method;
                    }
                    
                    if (!empty($itemUpdateData)) {
                        $entryitem->update($itemUpdateData);
                        $entryitemsUpdated++;
                    }
                }
            }
        }
        
        $bar->finish();
        $this->newLine();
        
        return [
            'entries' => $entriesUpdated,
            'entryitems' => $entryitemsUpdated
        ];
    }

    /**
     * Backfill check numbers and methods from vendor payments
     */
    private function backfillVendorPayments()
    {
        $entriesUpdated = 0;
        $entryitemsUpdated = 0;
        
        // Get vendor entry type ID
        $vendorEntryTypeId = EntryType::where('label', 'vendor')->value('id');
        
        if (!$vendorEntryTypeId) {
            $this->warn('Vendor entry type not found. Skipping vendor payments.');
            return ['entries' => 0, 'entryitems' => 0];
        }
        
        // Get all vendor payments that have check_number or payment_method
        $vendorPayments = VendorPayment::where(function($query) {
            $query->whereNotNull('check_number')
                  ->orWhereNotNull('payment_method');
        })->get();
        
        $this->info("Found {$vendorPayments->count()} vendor payments to process...");
        
        $bar = $this->output->createProgressBar($vendorPayments->count());
        $bar->start();
        
        foreach ($vendorPayments as $vendorPayment) {
            $bar->advance();
            // Find VendorBills related to this VendorPayment
            $vendorBills = \App\Models\VendorBill::where('vendor_payment_id', $vendorPayment->id)->get();
            
            foreach ($vendorBills as $vendorBill) {
                // Get VendorBillPayments for this bill that match the payment date
                $billPayments = VendorBillPayment::where('vendor_bill_id', $vendorBill->id)
                    ->whereDate('payment_date', $vendorPayment->payment_date)
                    ->get();
                
                foreach ($billPayments as $billPayment) {
                    // Find the entry that matches this bill payment
                    // Match by: date, amount, vendor entry type, and vendor_id in entryitems
                    $entry = Entry::where('entrytype_id', $vendorEntryTypeId)
                        ->whereDate('date', $billPayment->payment_date)
                        ->where('dr_total', $billPayment->amount)
                        ->where('cr_total', $billPayment->amount)
                        ->whereHas('entryitems', function($query) use ($vendorBill) {
                            $query->where('customer_id', $vendorBill->vendor_id);
                        })
                        ->first();
                    
                    if ($entry) {
                        $updateData = [];
                        
                        if ($vendorPayment->check_number && !$entry->check_no) {
                            $updateData['check_no'] = $vendorPayment->check_number;
                        }
                        
                        if ($vendorPayment->payment_method && !$entry->method) {
                            $updateData['method'] = $vendorPayment->payment_method;
                        }
                        
                        if (!empty($updateData)) {
                            $entry->update($updateData);
                            $entriesUpdated++;
                        }
                        
                        // Update all entryitems for this entry
                        $entryitems = EntryItem::where('entry_id', $entry->id)->get();
                        foreach ($entryitems as $entryitem) {
                            $itemUpdateData = [];
                            
                            if ($vendorPayment->check_number && !$entryitem->check_no) {
                                $itemUpdateData['check_no'] = $vendorPayment->check_number;
                            }
                            
                            if ($vendorPayment->payment_method && !$entryitem->method) {
                                $itemUpdateData['method'] = $vendorPayment->payment_method;
                            }
                            
                            if (!empty($itemUpdateData)) {
                                $entryitem->update($itemUpdateData);
                                $entryitemsUpdated++;
                            }
                        }
                    }
                }
            }
        }
        
        $bar->finish();
        $this->newLine();
        
        return [
            'entries' => $entriesUpdated,
            'entryitems' => $entryitemsUpdated
        ];
    }
}
