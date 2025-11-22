<?php

namespace App\Livewire\MisReports;

use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PlateSummaryReport extends Component
{
    use WithPagination;

    public $startDate;
    public $endDate;
    public $perPage = 25;
    public $authUser;
    public $selectedItemId = null;

    public function mount()
    {
        $this->authUser = auth()->user();
        $this->startDate = Carbon::now()->startOfMonth()->toDateString();
        $this->endDate = Carbon::now()->toDateString();
    }

    public function updatedStartDate()
    {
        $this->resetPage();
    }

    public function updatedEndDate()
    {
        $this->resetPage();
    }

    public function updatedSelectedItemId()
    {
        $this->resetPage();
    }

    public function render()
    {
        $bodyAttributes = 'x-data="{ page: \'PlateSummaryReport\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
        x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
        :class="{\'dark bg-gray-900\': darkMode === true}"';

        $branchId = $this->authUser->branch_id;

        // Get plate summary data
        $plateSummary = $this->getPlateSummaryData();

        // Plates for filter (only plates that appear in dispatch_items)
        $plates = DB::table('dispatch_items as di')
            ->join('items as i', 'i.id', '=', 'di.item_id')
            ->select('i.id', 'i.item_name', 'i.item_code')
            ->distinct()
            ->orderBy('i.item_name')
            ->get();

        return view('livewire.mis-reports.plate-summary-report', [
            'plateSummary' => $plateSummary,
            'plates' => $plates,
        ])->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }

    private function getPlateSummaryData()
    {
        // Get dispatched quantities by plate type
        $dispatchedData = DB::table('dispatch_items as di')
            ->join('items as i', 'i.id', '=', 'di.item_id')
            ->select(
                'i.item_name as plate_type',
                DB::raw('SUM(di.quantity) as dispatched_qty')
            )
            ->whereNotNull('di.quantity')
            ->where('di.quantity', '>', 0);

        // Filter by item
        if ($this->selectedItemId) {
            $dispatchedData->where('i.id', $this->selectedItemId);
        }

        // Filter by date range
        if ($this->startDate && $this->endDate) {
            $dispatchedData->whereBetween('di.created_at', [
                $this->startDate . ' 00:00:00',
                $this->endDate . ' 23:59:59'
            ]);
        }

        $dispatchedData = $dispatchedData->groupBy('i.item_name')->get()->keyBy('plate_type');

        // Get damaged quantities by plate type (approved)
        $damagedApprovedData = DB::table('damaged_items as d')
            ->join('items as i', 'i.id', '=', 'd.item_id')
            ->select(
                'i.item_name as plate_type',
                DB::raw('SUM(d.quantity) as damaged_approved')
            )
            ->where('d.status', 'approved')
            ->whereNotNull('d.quantity')
            ->where('d.quantity', '>', 0);

        if ($this->selectedItemId) {
            $damagedApprovedData->where('i.id', $this->selectedItemId);
        }

        // Filter by date range
        if ($this->startDate && $this->endDate) {
            $damagedApprovedData->whereBetween('d.created_at', [
                $this->startDate . ' 00:00:00',
                $this->endDate . ' 23:59:59'
            ]);
        }

        $damagedApprovedData = $damagedApprovedData->groupBy('i.item_name')->get()->keyBy('plate_type');

        // Get damaged quantities by plate type (pending)
        $damagedPendingData = DB::table('damaged_items as d')
            ->join('items as i', 'i.id', '=', 'd.item_id')
            ->select(
                'i.item_name as plate_type',
                DB::raw('SUM(d.quantity) as damaged_pending')
            )
            ->where('d.status', 'pending')
            ->whereNotNull('d.quantity')
            ->where('d.quantity', '>', 0);

        if ($this->selectedItemId) {
            $damagedPendingData->where('i.id', $this->selectedItemId);
        }

        // Filter by date range
        if ($this->startDate && $this->endDate) {
            $damagedPendingData->whereBetween('d.created_at', [
                $this->startDate . ' 00:00:00',
                $this->endDate . ' 23:59:59'
            ]);
        }

        $damagedPendingData = $damagedPendingData->groupBy('i.item_name')->get()->keyBy('plate_type');

        // Combine all plate types
        $allPlateTypes = collect()
            ->merge($dispatchedData->keys())
            ->merge($damagedApprovedData->keys())
            ->merge($damagedPendingData->keys())
            ->unique()
            ->sort()
            ->values();

        $summaryData = [];
        $totalDispatched = 0;
        $totalDamagedApproved = 0;
        $totalDamagedPending = 0;
        $grandTotal = 0;

        foreach ($allPlateTypes as $plateType) {
            $dispatchedQty = $dispatchedData->get($plateType)->dispatched_qty ?? 0;
            $damagedApproved = $damagedApprovedData->get($plateType)->damaged_approved ?? 0;
            $damagedPending = $damagedPendingData->get($plateType)->damaged_pending ?? 0;
            $total = $dispatchedQty + $damagedApproved + $damagedPending;

            $summaryData[] = [
                'plate_type' => $plateType,
                'dispatched_qty' => $dispatchedQty,
                'damaged_approved' => $damagedApproved,
                'damaged_pending' => $damagedPending,
                'total' => $total
            ];

            $totalDispatched += $dispatchedQty;
            $totalDamagedApproved += $damagedApproved;
            $totalDamagedPending += $damagedPending;
            $grandTotal += $total;
        }

        return [
            'data' => $summaryData,
            'totals' => [
                'dispatched_qty' => $totalDispatched,
                'damaged_approved' => $totalDamagedApproved,
                'damaged_pending' => $totalDamagedPending,
                'grand_total' => $grandTotal
            ]
        ];
    }
}
