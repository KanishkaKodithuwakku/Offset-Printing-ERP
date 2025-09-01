<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Payment;
use App\Helpers\NumberGenerator;

class UpdatePaymentCodes extends Command
{
    protected $signature = 'payments:update-codes';
    protected $description = 'Update all payment_code fields to new format';

    public function handle()
    {
        $payments = Payment::all();
        $updated = 0;
        $skipped = 0;

        foreach ($payments as $payment) {
            // Only update if not already in new format
            if (strpos($payment->payment_code, '/') === false) {
                $newCode = NumberGenerator::generatePaymentCode($payment);
                $oldCode = $payment->payment_code;
                $payment->payment_code = $newCode;
                $payment->save();
                $this->info("Updated Payment ID {$payment->id}: {$oldCode} -> {$newCode}");
                $updated++;
            } else {
                $skipped++;
            }
        }

        $this->info("Done! Updated: $updated, Skipped (already new): $skipped");
    }
}
