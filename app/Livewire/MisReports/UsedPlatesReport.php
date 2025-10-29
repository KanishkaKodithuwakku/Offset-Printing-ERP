<?php

namespace App\Livewire\MisReports;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Item;
use App\Models\Stock;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UsedPlatesReport extends Component
{
    use WithPagination;

    public $startDate;
    public $endDate;
    public $perPage = 25;
    public $selectedItemId = null;
    public $authUser;
    public $showAll = false;

    public function mount()
    {
        $this->authUser = auth()->user();
        $this->startDate = Carbon::now()->subDays(30)->toDateString();
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

    public function updatedShowAll()
    {
        $this->resetPage();
    }

    public function render()
    {
        $bodyAttributes = 'x-data="{ page: \'UsedPlatesReport\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
        x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
        :class="{\'dark bg-gray-900\': darkMode === true}"';

        $branchId = $this->authUser->branch_id;

        // Build query to get used plates from dispatch_items table
        // This query gets all dispatched plates with their details
        $query = DB::table('dispatch_items as di')
            ->join('items as i', 'i.id', '=', 'di.item_id') // Get plate name and code from items table
            ->leftJoin('dispatch_notes as dn', 'dn.id', '=', 'di.dispatch_id') // Get dispatch number
            ->leftJoin('job_orders as jo', 'jo.id', '=', 'di.order_id') // Get job order details
            ->leftJoin('customers as c', 'c.id', '=', 'jo.customer_id') // Get customer name
            ->select(
                'i.id as item_id',
                'i.item_code',
                'i.item_name',
                'di.quantity', // Dispatched quantity from dispatch_items table
                'di.created_at as used_date',
                'dn.dispatch_number',
                'jo.job_number',
                'c.name as customer_name',
                'di.dispatch_id'
            )
            ->whereNotNull('di.quantity') // Ensure quantity exists
            ->where('di.quantity', '>', 0); // Ensure positive quantity

        // Filter by date range
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('di.created_at', [
                $this->startDate . ' 00:00:00',
                $this->endDate . ' 23:59:59'
            ]);
        }

        // Filter by selected item
        if ($this->selectedItemId) {
            $query->where('i.id', $this->selectedItemId);
        }

        // Order by date descending (most recent first)
        $query->orderBy('di.created_at', 'desc');

        // If showAll is true, get all records without pagination, otherwise paginate
        if ($this->showAll) {
            $usedPlates = new \Illuminate\Pagination\LengthAwarePaginator(
                $query->get(),
                $query->count(),
                $query->count(),
                1
            );
        } else {
            $usedPlates = $query->paginate($this->perPage);
        }

        // Get all plates that have been dispatched (from dispatch_items)
        // This shows only plates that have actually been used
        $plates = DB::table('dispatch_items as di')
            ->join('items as i', 'i.id', '=', 'di.item_id')
            ->select('i.id', 'i.item_name', 'i.item_code')
            ->distinct()
            ->orderBy('i.item_name')
            ->get();

        return view('livewire.mis-reports.used-plates-report', [
            'usedPlates' => $usedPlates,
            'plates' => $plates,
        ])->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}

