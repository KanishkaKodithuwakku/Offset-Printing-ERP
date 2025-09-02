<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Financial Performance Chart API
Route::get('/financial-data', function (Request $request) {
    $period = $request->query('period', 'monthly');

    // Get current date
    $now = now();

    switch ($period) {
        case 'daily':
            // Get last 7 days data
            $categories = [];
            $revenue = [];
            $payments = [];

            for ($i = 6; $i >= 0; $i--) {
                $date = $now->copy()->subDays($i);
                $categories[] = $date->format('D');

                // Get revenue from invoices for this date
                $dayRevenue = \App\Models\Invoice::whereDate('created_at', $date)
                    ->where('status', '!=', 'cancelled')
                    ->sum('total_amount');
                $revenue[] = round($dayRevenue, 0);

                // Get payments for this date
                $dayPayments = \App\Models\Payment::whereDate('created_at', $date)
                    ->sum('amount');
                $payments[] = round($dayPayments, 0);
            }
            break;

        case 'weekly':
            // Get last 4 weeks data
            $categories = [];
            $revenue = [];
            $payments = [];

            for ($i = 3; $i >= 0; $i--) {
                $weekStart = $now->copy()->subWeeks($i)->startOfWeek();
                $weekEnd = $weekStart->copy()->endOfWeek();
                $categories[] = 'W' . ($i + 1);

                // Get revenue from invoices for this week
                $weekRevenue = \App\Models\Invoice::whereBetween('created_at', [$weekStart, $weekEnd])
                    ->where('status', '!=', 'cancelled')
                    ->sum('total_amount');
                $revenue[] = round($weekRevenue, 0);

                // Get payments for this week
                $weekPayments = \App\Models\Payment::whereBetween('created_at', [$weekStart, $weekEnd])
                    ->sum('amount');
                $payments[] = round($weekPayments, 0);
            }
            break;

        case 'monthly':
            // Get last 12 months data
            $categories = [];
            $revenue = [];
            $payments = [];

            for ($i = 11; $i >= 0; $i--) {
                $month = $now->copy()->subMonths($i);
                $categories[] = $month->format('M \'y');

                // Get revenue from invoices for this month
                $monthRevenue = \App\Models\Invoice::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->sum('total_amount');
                $revenue[] = round($monthRevenue, 0);

                // Get payments for this month
                $monthPayments = \App\Models\Payment::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->sum('amount');
                $payments[] = round($monthPayments, 0);
            }
            break;

        case 'yearly':
            // Get last 5 years data
            $categories = [];
            $revenue = [];
            $payments = [];

            for ($i = 4; $i >= 0; $i--) {
                $year = $now->copy()->subYears($i);
                $categories[] = $year->format('Y');

                // Get revenue from invoices for this year
                $yearRevenue = \App\Models\Invoice::whereYear('created_at', $year->year)
                    ->sum('total_amount');
                $revenue[] = round($yearRevenue, 0);

                // Get payments for this year
                $yearPayments = \App\Models\Payment::whereYear('created_at', $year->year)
                    ->sum('amount');
                $payments[] = round($yearPayments, 0);
            }
            break;

        default:
            $categories = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
            $revenue = [0, 0, 0, 0, 0, 0];
            $payments = [0, 0, 0, 0, 0, 0];
    }

    return response()->json([
        'categories' => $categories,
        'revenue' => $revenue,
        'payments' => $payments
    ]);
});
