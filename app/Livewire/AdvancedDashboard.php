<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\MonthlyTarget;
use Livewire\Component;
use App\Models\JobOrder;
use App\Models\DamagedItem;
use App\Models\InvoiceItem;
use App\Models\VendorBillPayment;
use Illuminate\Support\Facades\DB;

class AdvancedDashboard extends Component
{
    // Individual status counts
    public $pendingCount = 0;
    public $designingCount = 0;
    public $pausedCount = 0;
    public $printingCount = 0;
    public $dispatchingCount = 0;
    public $readyToInvoiceCount = 0;
    public $invoicingCount = 0;

    public $role;
    public $authUser;

    public $activePeriod = 'daily';
    public $currentData = [
        'revenue' => 0,
        'payments' => 0,
        'vendor_payments' => 0,
        'damaged_plates' => 0,
        'cancelled_receipts' => 0,
        'cancelled_invoices' => 0
    ];

    public $designCount = 0;
    public $productionCount = 0;
    public $accountCount = 0;

    public $plateConsumption = [];
    public $yesterdayPlateConsumption = [];

    // Job status data for chart
    public $jobStatusData = [
        'pending' => 0,
        'exposing_ctp' => 0,
        'ctp_dispatch' => 0,
        'designing_dtp' => 0,
        'paused' => 0,
        'billing' => 0,
        'invoicing' => 0,
        'invoiced' => 0,
        'cancelled' => 0
    ];

    // Chart data properties
    public $chartData = [
        'revenue' => [],
        'payments' => [],
        'labels' => []
    ];

    // Monthly target properties
    public $currentMonthTarget = null;
    public $currentMonthRevenue = 0;
    public $targetAchievementPercentage = 0;

    public function mount()
    {
        $this->loadMonthlyData(); // Changed from daily to monthly for better overview
        $this->loadPlateConsumptionData();
        $this->loadYesterdayPlateConsumptionData();
        $this->loadJobOrderCounts();
        $this->loadChartData();
        $this->loadMonthlyTargetData();

        $this->authUser = auth()->user();
        $this->role = $this->authUser->mode;
    }

    public function refreshData()
    {
        $this->loadDailyData();
        $this->loadPlateConsumptionData();
        $this->loadYesterdayPlateConsumptionData();
        $this->loadJobOrderCounts();
        $this->loadChartData();
        $this->loadMonthlyTargetData();
    }

    public function getFormattedCurrency($amount)
    {
        return number_format($amount, 0);
    }

    public function getPercentageChange($current, $previous)
    {
        if ($previous == 0) return 0;
        return round((($current - $previous) / $previous) * 100, 1);
    }

        public function loadDailyData()
    {
        $this->activePeriod = 'daily';
        $today = Carbon::today();

        $this->currentData = [
            'revenue' => Invoice::whereDate('created_at', $today)
                ->where('status', '!=', 'cancelled')
                ->sum('total_amount'),
            'payments' => Payment::whereDate('date', $today)
                ->sum('amount'),
            'vendor_payments' => VendorBillPayment::whereDate('payment_date', $today)
                ->sum('amount'),
            'damaged_plates' => DamagedItem::whereDate('created_at', $today)
                ->sum('quantity'),
            'cancelled_receipts' => Payment::withTrashed()
            ->whereDate('created_at', $today)
            ->where(function($query) {
                $query->where('status', 'cancelled')
                      ->orWhereNotNull('deleted_at');
            })
            ->count(),
            'cancelled_invoices' => Invoice::whereDate('created_at', $today)
                ->where('status', 'cancelled')
                ->count()
        ];

        $this->loadChartData();
        $this->loadDailyPlateConsumptionData();
        $this->dispatch('period-changed', period: 'daily');
        $this->dispatch('plate-consumption-updated', data: $this->plateConsumption);
    }

    public function loadWeeklyData()
    {
        $this->activePeriod = 'weekly';
        $start = Carbon::now()->startOfWeek();
        $end = Carbon::now()->endOfWeek();

        $this->currentData = [
            'revenue' => Invoice::whereBetween('created_at', [$start, $end])
                ->where('status', '!=', 'cancelled')
                ->sum('total_amount'),
            'payments' => Payment::whereBetween('date', [$start, $end])
                ->sum('amount'),
            'vendor_payments' => VendorBillPayment::whereBetween('payment_date', [$start, $end])
                ->sum('amount'),
            'damaged_plates' => DamagedItem::whereBetween('created_at', [$start, $end])
                ->sum('quantity'),
           'cancelled_receipts' => Payment::withTrashed()
            ->whereBetween('date', [$start, $end])
            ->where(function($query) {
                $query->where('status', 'cancelled')
                      ->orWhereNotNull('deleted_at');
            })
            ->count(),
            'cancelled_invoices' => Invoice::whereBetween('created_at', [$start, $end])
                ->where('status', 'cancelled')
                ->count()
        ];

        $this->loadChartData();
        $this->loadWeeklyPlateConsumptionData();
        $this->dispatch('period-changed', period: 'weekly');
        $this->dispatch('plate-consumption-updated', data: $this->plateConsumption);
    }

    public function loadMonthlyData()
    {
        $this->activePeriod = 'monthly';
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();

        $this->currentData = [
            'revenue' => Invoice::whereBetween('created_at', [$start, $end])
                ->where('status', '!=', 'cancelled')
                ->sum('total_amount'),
            'payments' => Payment::whereBetween('date', [$start, $end])
                ->where('status', '!=', 'cancelled')
                ->sum('amount'),
            'vendor_payments' => VendorBillPayment::whereBetween('payment_date', [$start, $end])
                ->sum('amount'),
            'damaged_plates' => DamagedItem::whereBetween('created_at', [$start, $end])
                ->sum('quantity'),
            'cancelled_receipts' => Payment::withTrashed()
            ->whereBetween('date', [$start, $end])
            ->where(function($query) {
                $query->where('status', 'cancelled')
                      ->orWhereNotNull('deleted_at');
            })
            ->count(),
            'cancelled_invoices' => Invoice::whereBetween('created_at', [$start, $end])
                ->where('status', 'cancelled')
                ->count()
        ];

        $this->loadChartData();
        $this->loadMonthlyPlateConsumptionData();
        $this->dispatch('period-changed', period: 'monthly');
        $this->dispatch('plate-consumption-updated', data: $this->plateConsumption);
    }

    public function loadYearlyData()
    {
        $this->activePeriod = 'yearly';
        $start = Carbon::now()->startOfYear();
        $end = Carbon::now()->endOfYear();

        $this->currentData = [
            'revenue' => Invoice::whereBetween('created_at', [$start, $end])
                ->where('status', '!=', 'cancelled')
                ->sum('total_amount'),
            'payments' => Payment::whereBetween('date', [$start, $end])
                ->where('status', '!=', 'cancelled')
                ->sum('amount'),
            'vendor_payments' => VendorBillPayment::whereBetween('payment_date', [$start, $end])
                ->sum('amount'),
            'damaged_plates' => DamagedItem::whereBetween('created_at', [$start, $end])
                ->sum('quantity'),
            'cancelled_receipts' => Payment::withTrashed()
            ->whereBetween('date', [$start, $end])
            ->where(function($query) {
                $query->where('status', 'cancelled')
                      ->orWhereNotNull('deleted_at');
            })
            ->count(),
            'cancelled_invoices' => Invoice::whereBetween('created_at', [$start, $end])
                ->where('status', 'cancelled')
                ->count()
        ];

        $this->loadChartData();
        $this->loadYearlyPlateConsumptionData();
        $this->dispatch('period-changed', period: 'yearly');
        $this->dispatch('plate-consumption-updated', data: $this->plateConsumption);
    }

    public function loadPlateConsumptionData()
    {
        // Load all-time plate consumption data for the chart (default)
        $this->plateConsumption = InvoiceItem::selectRaw('
                items.item_name,
                SUM(invoice_items.quantity) as quantity
            ')
            ->join('items', 'invoice_items.item_id', '=', 'items.id')
            ->groupBy('items.item_name')
            ->orderBy('quantity', 'desc')
            ->limit(10)
            ->get()
            ->toArray();
    }

    public function loadDailyPlateConsumptionData()
    {
        // Load daily plate consumption data for the chart
        $today = Carbon::today();

        $this->plateConsumption = InvoiceItem::selectRaw('
                items.item_name,
                SUM(invoice_items.quantity) as quantity
            ')
            ->join('items', 'invoice_items.item_id', '=', 'items.id')
            ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
            ->whereDate('invoices.created_at', $today)
            ->where('invoices.status', '!=', 'cancelled')
            ->groupBy('items.item_name')
            ->orderBy('quantity', 'desc')
            ->limit(10)
            ->get()
            ->toArray();
    }

    public function loadWeeklyPlateConsumptionData()
    {
        // Load weekly plate consumption data for the chart
        $start = Carbon::now()->startOfWeek();
        $end = Carbon::now()->endOfWeek();

        $this->plateConsumption = InvoiceItem::selectRaw('
                items.item_name,
                SUM(invoice_items.quantity) as quantity
            ')
            ->join('items', 'invoice_items.item_id', '=', 'items.id')
            ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
            ->whereBetween('invoices.created_at', [$start, $end])
            ->where('invoices.status', '!=', 'cancelled')
            ->groupBy('items.item_name')
            ->orderBy('quantity', 'desc')
            ->limit(10)
            ->get()
            ->toArray();
    }

    public function loadMonthlyPlateConsumptionData()
    {
        // Load monthly plate consumption data for the chart
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();

        $this->plateConsumption = InvoiceItem::selectRaw('
                items.item_name,
                SUM(invoice_items.quantity) as quantity
            ')
            ->join('items', 'invoice_items.item_id', '=', 'items.id')
            ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
            ->whereBetween('invoices.created_at', [$start, $end])
            ->where('invoices.status', '!=', 'cancelled')
            ->groupBy('items.item_name')
            ->orderBy('quantity', 'desc')
            ->limit(10)
            ->get()
            ->toArray();
    }

    public function loadYearlyPlateConsumptionData()
    {
        // Load yearly plate consumption data for the chart
        $start = Carbon::now()->startOfYear();
        $end = Carbon::now()->endOfYear();

        $this->plateConsumption = InvoiceItem::selectRaw('
                items.item_name,
                SUM(invoice_items.quantity) as quantity
            ')
            ->join('items', 'invoice_items.item_id', '=', 'items.id')
            ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
            ->whereBetween('invoices.created_at', [$start, $end])
            ->where('invoices.status', '!=', 'cancelled')
            ->groupBy('items.item_name')
            ->orderBy('quantity', 'desc')
            ->limit(10)
            ->get()
            ->toArray();
    }

    public function loadYesterdayPlateConsumptionData()
    {
        // Load yesterday's plate consumption data only for the table
        $yesterday = Carbon::yesterday();

        $this->yesterdayPlateConsumption = InvoiceItem::selectRaw('
                items.item_name,
                SUM(invoice_items.quantity) as quantity
            ')
            ->join('items', 'invoice_items.item_id', '=', 'items.id')
            ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
            ->whereDate('invoices.created_at', $yesterday)
            ->where('invoices.status', '!=', 'cancelled')
            ->groupBy('items.item_name')
            ->orderBy('quantity', 'desc')
            ->limit(10)
            ->get()
            ->toArray();
    }

    public function getPlateConsumptionChartDataProperty()
    {
        return [
            'data' => array_column($this->plateConsumption, 'quantity'),
            'categories' => array_column($this->plateConsumption, 'item_name')
        ];
    }

    public function loadJobOrderCounts()
    {
        // Individual status counts
        $this->pendingCount = JobOrder::where('status', 'pending')->count();
        $this->designingCount = JobOrder::where('status', 'designing')->count();
        $this->pausedCount = JobOrder::where('status', 'paused')->count();
        $this->printingCount = JobOrder::where('status', 'printing')->count();
        $this->dispatchingCount = JobOrder::where('status', 'dispatching')->count();
        $this->readyToInvoiceCount = JobOrder::where('status', 'ready-to-invoice')->count();
        $this->invoicingCount = JobOrder::where('status', 'invoicing')->count();

        // Group counts
        $this->designCount = $this->pendingCount + $this->designingCount;
        $this->productionCount = $this->pausedCount + $this->printingCount + $this->dispatchingCount;
        $this->accountCount = $this->readyToInvoiceCount + $this->invoicingCount;

        // Load comprehensive job status data for chart based on actual database statuses
        $this->jobStatusData = [
            'pending' => JobOrder::where('status', 'pending')->count(),
            'designing_dtp' => JobOrder::where('status', 'designing')->count(),
            'exposing_ctp' => JobOrder::where('status', 'printing')->count(),
            'ctp_dispatch' => JobOrder::where('status', 'dispatching')->count(),
            'billing' => JobOrder::where('status', 'ready-to-invoice')->count(),
            'invoicing' => JobOrder::where('status', 'invoicing')->count(),
            'invoiced' => JobOrder::where('status', 'invoiced')->count(),
            'cancelled' => JobOrder::where('status', 'cancelled')->count()
        ];
    }

    public function loadChartData()
    {
        $labels = [];
        $revenueData = [];
        $paymentData = [];

        switch ($this->activePeriod) {
            case 'daily':
                // Load last 7 days
                for ($i = 6; $i >= 0; $i--) {
                    $date = Carbon::now()->subDays($i);
                    $labels[] = $date->format('D');

                    $revenue = Invoice::whereDate('created_at', $date)
                        ->where('status', '!=', 'cancelled')
                        ->sum('total_amount');
                    $revenueData[] = $revenue;

                    $payment = Payment::whereDate('date', $date)
                        ->where('status', '!=', 'cancelled')
                        ->sum('amount');
                    $paymentData[] = $payment;
                }
                break;

            case 'weekly':
                // Load last 8 weeks
                for ($i = 7; $i >= 0; $i--) {
                    $date = Carbon::now()->subWeeks($i);
                    $labels[] = 'W' . $date->weekOfYear;

                    $startOfWeek = $date->copy()->startOfWeek();
                    $endOfWeek = $date->copy()->endOfWeek();

                    $revenue = Invoice::whereBetween('created_at', [$startOfWeek, $endOfWeek])
                        ->where('status', '!=', 'cancelled')
                        ->sum('total_amount');
                    $revenueData[] = $revenue;

                    $payment = Payment::whereBetween('date', [$startOfWeek, $endOfWeek])
                        ->where('status', '!=', 'cancelled')
                        ->sum('amount');
                    $paymentData[] = $payment;
                }
                break;

            case 'monthly':
                // Load last 6 months
                for ($i = 5; $i >= 0; $i--) {
                    $date = Carbon::now()->subMonths($i);
                    $labels[] = $date->format('M');

                    $revenue = Invoice::whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)
                        ->where('status', '!=', 'cancelled')
                        ->sum('total_amount');
                    $revenueData[] = $revenue;

                    $payment = Payment::whereYear('date', $date->year)
                        ->whereMonth('date', $date->month)
                        ->where('status', '!=', 'cancelled')
                        ->sum('amount');
                    $paymentData[] = $payment;
                }
                break;

            case 'yearly':
            default:
                // Load last 5 years
                for ($i = 4; $i >= 0; $i--) {
                    $date = Carbon::now()->subYears($i);
                    $labels[] = $date->format('Y');

                    $revenue = Invoice::whereYear('created_at', $date->year)
                        ->where('status', '!=', 'cancelled')
                        ->sum('total_amount');
                    $revenueData[] = $revenue;

                    $payment = Payment::whereYear('date', $date->year)
                        ->where('status', '!=', 'cancelled')
                        ->sum('amount');
                    $paymentData[] = $payment;
                }
                break;
        }

        $this->chartData = [
            'labels' => $labels,
            'revenue' => $revenueData,
            'payments' => $paymentData
        ];
    }

    public function loadMonthlyTargetData()
    {
        $currentMonth = Carbon::now()->format('F');
        $currentYear = Carbon::now()->year;

        // Get current month's target
        $this->currentMonthTarget = MonthlyTarget::where('month', $currentMonth)
            ->where('year', $currentYear)
            ->where('status', 'active')
            ->first();

        // Get current month's revenue
        $this->currentMonthRevenue = Invoice::whereYear('created_at', $currentYear)
            ->whereMonth('created_at', Carbon::now()->month)
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');

        // Calculate achievement percentage
        if ($this->currentMonthTarget && $this->currentMonthTarget->target_amount > 0) {
            $this->targetAchievementPercentage = round(($this->currentMonthRevenue / $this->currentMonthTarget->target_amount) * 100, 1);
        } else {
            $this->targetAchievementPercentage = 0;
        }
    }

    public function loadAllTimeData()
    {
        $this->activePeriod = 'all-time';

        $this->currentData = [
            'revenue' => Invoice::where('status', '!=', 'cancelled')->sum('total_amount'),
            'payments' => Payment::where('status', '!=', 'cancelled')->sum('amount'),
            'vendor_payments' => VendorBillPayment::sum('amount'),
            'damaged_plates' => DamagedItem::sum('quantity'),
            'cancelled_receipts' => Payment::withTrashed()
                ->where(function($query) {
                    $query->where('status', 'cancelled')
                          ->orWhereNotNull('deleted_at');
                })
                ->count(),
            'cancelled_invoices' => Invoice::where('status', 'cancelled')->count()
        ];
    }

    public function render()
    {
        // Ensure chart data is always loaded
        if (empty($this->chartData)) {
            $this->loadChartData();
        }

        $bodyAttributes = 'x-data="{ page: \'advanced-dashboard\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
            x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                    $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
            :class="{\'dark bg-gray-900\': darkMode === true}"';

        return view('livewire.advanced-dashboard.dashboard', [
            'currentData' => $this->currentData,
            'plateConsumption' => $this->plateConsumption,
            'chartData' => $this->chartData,
            'jobStatusData' => $this->jobStatusData,
            'currentMonthTarget' => $this->currentMonthTarget,
            'currentMonthRevenue' => $this->currentMonthRevenue,
            'targetAchievementPercentage' => $this->targetAchievementPercentage
        ])->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
