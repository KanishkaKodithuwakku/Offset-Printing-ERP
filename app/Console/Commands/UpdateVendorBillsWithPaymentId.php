<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateVendorBillsWithPaymentId extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vendor-bills:update-payment-id';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update existing vendor bills with vendor_payment_id based on payment relationships';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to update vendor bills with payment IDs...');

        // Get all vendor payments
        $vendorPayments = \App\Models\VendorPayment::all();
        $updatedCount = 0;

        foreach ($vendorPayments as $vendorPayment) {
            // Find vendor bills that match the vendor and payment date
            $vendorBills = \App\Models\VendorBill::where('vendor_id', $vendorPayment->vendor_id)
                ->whereDate('payment_date', $vendorPayment->payment_date)
                ->whereNull('vendor_payment_id')
                ->get();

            foreach ($vendorBills as $vendorBill) {
                $vendorBill->vendor_payment_id = $vendorPayment->id;
                $vendorBill->save();
                $updatedCount++;
            }
        }

        $this->info("Updated {$updatedCount} vendor bills with payment IDs.");
        $this->info('Update completed successfully!');
    }
}
