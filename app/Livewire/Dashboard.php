<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Invoice;
use App\Models\Payment;
use Livewire\Component;
use App\Models\JobOrder;
use App\Models\DamagedItem;
use App\Models\InvoiceItem;
use App\Models\VendorBillPayment;
// use Livewire\Component;
// use App\Models\JobOrder;

class Dashboard extends Component
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
    // mm
    public $designCount = 0;
    public $productionCount = 0;
    public $accountCount = 0;

    public $plateConsumption = [];

    public function mount()
    {
        $this->loadDailyData();
        $this->loadDailyData();
        $this->loadPlateConsumptionData();
        $this->loadJobOrderCounts();

        $this->authUser = auth()->user();
        $this->role = $this->authUser->mode;
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

            // 'cancelled_receipts' => Payment::whereBetween('date', [$start, $end])  // Changed to use 'date'
            // ->where('status', 'cancelled')
            // ->count(),
             
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
    }
//    yester day===================
    public function loadPlateConsumptionData()
    {
        $yesterday = Carbon::yesterday();

        $this->plateConsumption = InvoiceItem::with('item')
            ->whereHas('invoice', function ($query) use ($yesterday) {
                $query->whereDate('created_at', $yesterday)
                    ->where('status', '!=', 'cancelled');
            })
            ->selectRaw('item_id, sum(quantity) as total_quantity')
            ->groupBy('item_id')
            ->get()
            ->map(function ($item) {
                return [
                    'description' => $item->item->item_description ?? 'N/A',
                    'quantity' => $item->total_quantity
                ];
            })
            ->toArray();
    }

    // past month =====================================
    //     public function loadPlateConsumptionData()
    // {
    //     $startDate = Carbon::now()->subMonth()->startOfDay(); // 1 month ago
    //     $endDate = Carbon::now()->endOfDay(); // Today

    //     $this->plateConsumption = InvoiceItem::with('item')
    //         ->whereHas('invoice', function($query) use ($startDate, $endDate) {
    //             $query->whereBetween('created_at', [$startDate, $endDate])
    //                   ->where('status', '!=', 'cancelled');
    //         })
    //         ->selectRaw('item_id, sum(quantity) as total_quantity')
    //         ->groupBy('item_id')
    //         ->get()
    //         ->map(function($item) {
    //             return [
    //                 'description' => $item->item->item_description ?? 'N/A',
    //                 'quantity' => $item->total_quantity
    //             ];
    //         })
    //         ->toArray();
    // }
//    ======================= show cancel recipt and invoice ========
// payment table status cancel -> recipts 
//invoice table ststus cancel -> cancel invoice



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
    }


    public function render()
    {
        $bodyAttributes = 'x-data="{ page: \'dashboard\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
            x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                    $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
            :class="{\'dark bg-gray-900\': darkMode === true}"';

        return view('livewire.dashboard.dashboard', [
            'plateConsumption' => $this->plateConsumption
        ])->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
