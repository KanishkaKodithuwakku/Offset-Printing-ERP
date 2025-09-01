<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class CustomerNumberGenerator
{
    /**
     * Generate a unique customer number for a branch.
     *
     * Format: CUS{branch_id}{0001}
     * Example: CUS12 0001 (if branch_id=12, first customer)
     *
     * @param int $branchId
     * @param int $sequenceLength
     * @return string
     */
    public static function generate(int $branchId, int $sequenceLength = 4): string
    {
        // Build the prefix
        $prefix = 'CUS' . $branchId;
        $likePattern = $prefix . '%';

        // Find the last customer number for this branch
        $last = DB::table('customers')
            ->where('customer_number', 'like', $likePattern)
            ->orderByDesc('id')
            ->first();

        // Extract and increment the sequence
        if ($last && preg_match('/(\\d{' . $sequenceLength . '})$/', $last->customer_number, $m)) {
            $next = (int)$m[1] + 1;
        } else {
            $next = 1;
        }

        $seqStr = str_pad($next, $sequenceLength, '0', STR_PAD_LEFT);
        return $prefix . $seqStr;
    }

    /**
     * Assign customer numbers to all customers who do not have one.
     *
     * @return int Number of customers updated
     */
    public static function assignToExistingCustomers(): int
    {
        $count = 0;
        $customers = \App\Models\Customer::whereNull('customer_number')
            ->orWhere('customer_number', '')
            ->orderBy('id')
            ->get();

        foreach ($customers as $customer) {
            if ($customer->branch_id) {
                // Always generate based on the current DB state
                $newNumber = self::generate($customer->branch_id);

                // Double-check for uniqueness before saving
                if (!\App\Models\Customer::where('customer_number', $newNumber)->exists()) {
                    $customer->customer_number = $newNumber;
                    $customer->save();
                    $count++;
                } else {
                    // Optionally, log or report the skipped customer
                    \Log::warning('Skipped duplicate customer number: ' . $newNumber . ' for customer ID ' . $customer->id);
                }
            }
        }
        return $count;
    }
}
