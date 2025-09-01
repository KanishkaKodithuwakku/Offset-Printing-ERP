<?php

namespace App\Livewire\Reports;

use App\Models\Item;
use App\Models\Stock;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StockMovementReport extends Component
{
    use WithPagination;

    public $items;
    public $selectedItemId = '';
    public $movementType = '';
    public $startDate;
    public $endDate;
    public $perPage = 25;
    public $searchTerm = '';
    public $searchResults = [];

    public $authUser;
    public $paginationEnabled = true;

    // Set default date range to last 30 days
    public function mount()
    {
        $this->authUser = auth()->user();
        // load all items for the dropdown
        $this->items = Item::orderBy('item_name')->get();

        // default date range: last 30 days
        $this->startDate = Carbon::now()->subDays(30)->toDateString();
        $this->endDate = Carbon::now()->toDateString();
    }

    public function updatedSearchTerm()
    {
        if (strlen($this->searchTerm) >= 1) {
            $items = Item::where(function ($query) {
                $query->where('item_name', 'like', "{$this->searchTerm}%")
                    ->orWhere('item_code', 'like', "{$this->searchTerm}%");
            })
                ->limit(5)
                ->get();

            $this->searchResults = $items->map(function ($item) {

                $stockBalance = Stock::where('branch_id', $this->authUser->branch_id)
                    ->where('items_id', $item->id)
                    ->sum('quantity') ?? 0;

                return [
                    'id' => $item->id,
                    'item_name' => $item->item_name,
                    'item_code' => $item->item_code,
                    'selling_price' => $item->sales_price,
                    'purchase_price' => $item->purchase_price,
                    'brands_id' => $item->brands_id,
                    'quantity' => $stockBalance > 0 ? 1 : 0,
                    'stock_balance' => $stockBalance,
                ];
            })->toArray();
        } else {
            $this->searchResults = [];
        }
    }

    public function setItemId($itemId)
    {
        $item = Item::find($itemId);
        $this->selectedItemId = $itemId;
        $this->searchTerm = $item->item_name;
        $this->searchResults = [];
    }

    // Fetch stock movements based on filters
    public function _render()
    {
        $bodyAttributes = 'x-data="{ page: \'StockMovementReport\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
    x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
            $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
    :class="{\'dark bg-gray-900\': darkMode === true}"';

        // Using DB::table for query builder
        $branchId = $this->authUser->branch_id;

        $query = DB::table('stocks as s')
            ->join('items as i', 'i.id', '=', 's.items_id')
            ->select(
                'i.item_code',
                'i.item_name',
                's.table_name',
                's.p_id',
                's.created_at as last_movement_date',
                's.quantity as total_quantity',
                DB::raw("
                    (SELECT COALESCE(SUM(q2.quantity),0)
                     FROM stocks q2
                     WHERE q2.items_id = s.items_id
                       AND q2.branch_id = {$branchId}
                       AND q2.created_at < s.created_at
                    ) as previous_balance
                "),
                DB::raw("
                    (SELECT SUM(q3.quantity)
                     FROM stocks q3
                     WHERE q3.items_id = s.items_id
                       AND q3.branch_id = {$branchId}
                       AND q3.created_at <= s.created_at
                    ) as current_balance
                ")
            )
            ->where('s.branch_id', $branchId);

        // Filter by selected item
        if ($this->selectedItemId) {
            $query->where('s.items_id', $this->selectedItemId);
        }
        $movementTypeExists = DB::table('stocks')
            ->where('table_name', $this->movementType)
            ->where('branch_id', $branchId)
            ->exists();

        // Filter by movement type
        if ($this->movementType && $movementTypeExists) {
            $query->where('s.table_name', $this->movementType);
        }

        // Filter by date range
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('s.created_at', [
                $this->startDate . ' 00:00:00',
                $this->endDate . ' 23:59:59',
            ]);
        }

        if ($this->paginationEnabled) {
            $movements = $query->orderBy('i.item_code', 'asc')
                ->orderBy('s.created_at', 'asc')
                ->paginate($this->perPage);
        } else {
            $movements = $query->orderBy('i.item_code', 'asc')
                ->orderBy('s.created_at', 'asc')
                ->get();
        }

        if (!$movementTypeExists && !empty($this->movementType)) {
            // If no records found for the selected movementType, reset the filter
            session()->flash('error', 'No records found for the selected movement type.');
            $this->movementType = '';  // Reset the movement type filter
        }

        return view('livewire.reports.stock-movement-report', [
            'movements' => $movements,
        ])->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }

    public function render()
{
    $bodyAttributes = 'x-data="{ page: \'StockMovementReport\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
        x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
        :class="{\'dark bg-gray-900\': darkMode === true}"';

    $branchId = $this->authUser->branch_id;

    // Build the base query
    $query = DB::table('stocks as s')
        ->join('items as i', 'i.id', '=', 's.items_id')

        // Only join dispatch_notes when this is a dispatch row:
        ->leftJoin('dispatch_notes as d', function($join) {
            $join->on('d.id', '=', 's.p_id')
                 ->where('s.table_name', 'dispatch');
        })
        // Then join job_orders to get the job_number
        ->leftJoin('job_orders as o', 'o.id', '=', 'd.job_order_id')

        ->select([
            'i.item_code',
            'i.item_name',
            's.table_name',
            's.p_id',
            's.created_at as last_movement_date',
            's.quantity as total_quantity',

            // Subquery for previous balance
            DB::raw("
                (SELECT COALESCE(SUM(q2.quantity),0)
                 FROM stocks q2
                 WHERE q2.items_id = s.items_id
                   AND q2.branch_id = {$branchId}
                   AND q2.created_at < s.created_at
                ) as previous_balance
            "),
            // Subquery for current balance
            DB::raw("
                (SELECT SUM(q3.quantity)
                 FROM stocks q3
                 WHERE q3.items_id = s.items_id
                   AND q3.branch_id = {$branchId}
                   AND q3.created_at <= s.created_at
                ) as current_balance
            "),
            // ← Here’s the new column:
            'o.job_number as job_order_number',
        ])
        ->where('s.branch_id', $branchId);

    // Apply filters
    if ($this->selectedItemId) {
        $query->where('s.items_id', $this->selectedItemId);
    }
    if ($this->movementType) {
        $query->where('s.table_name', $this->movementType);
    }
    if ($this->startDate && $this->endDate) {
        $query->whereBetween('s.created_at', [
            $this->startDate . ' 00:00:00',
            $this->endDate   . ' 23:59:59',
        ]);
    }

    // Ordering and pagination
    $query->orderBy('i.item_code', 'asc')
          ->orderBy('s.created_at', 'asc');

    $movements = $this->paginationEnabled
        ? $query->paginate($this->perPage)
        : $query->get();

    return view('livewire.reports.stock-movement-report', [
        'movements' => $movements,
    ])->layout('layouts.app', [
        'bodyAttributes' => $bodyAttributes,
    ]);
}

}
