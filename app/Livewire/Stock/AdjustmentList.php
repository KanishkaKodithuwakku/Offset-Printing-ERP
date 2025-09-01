<?php

namespace App\Livewire\Stock;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Adjustment;
use App\Models\AdjustmentItem;
use App\Models\Item;
use App\Models\Stock;

class AdjustmentList extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $selectedAdjustmentId = null;

    public function mount()
    {
        // Initialize selectedAdjustmentId as null
        $this->selectedAdjustmentId = null;
    }

    public function updatingSearch()
    {
        $this->resetPage(); // Reset page number on search change
    }

    public function selectAdjustment($adjustmentId)
    {
        // Set selected adjustment ID
        $this->selectedAdjustmentId = $adjustmentId;
    }

    public function updatingStatusFilter()
    {
        $this->resetPage(); // Reset page number on filter change
    }

    public function render()
    {


        $AdjustmentQuery = Adjustment::query();

    if ($this->statusFilter) {
        $AdjustmentQuery->where('status', $this->statusFilter);
    } else {
        $AdjustmentQuery->whereIn('status', ['approved', 'pending']);
    }

        $AdjustmentQuery->orderBy('created_at', 'desc');





        $bodyAttributes = 'x-data="{ page: \'AdjustmentItemList\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
            x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                    $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
            :class="{\'dark bg-gray-900\': darkMode === true}"';

        // Query to get adjustments with filters applied
        $query = Adjustment::query();

        if ($this->search) {
            $query->where('reason', 'like', "%{$this->search}%")
                ->orWhere('id', 'like', "%{$this->search}%");
        }
            if ($this->statusFilter) {
                // Filter by the selected status
                $query->where('status', $this->statusFilter);
            } else {
                // If no status filter is selected, show only approved or pending
                $query->whereIn('status', ['approved', 'pending']);
            }

            $adjustments = $query->orderBy('created_at', 'desc')->paginate(25);



        return view('livewire.stock.adjustment-list', [
            'adjustments' => $adjustments
        ])->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
