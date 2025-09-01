<?php

namespace App\Livewire\Stock;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Adjustment;

class StockAdjustmentList extends Component
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
        $bodyAttributes = 'x-data="{ page: \'StockAdjustmentApproval\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
            x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                    $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
            :class="{\'dark bg-gray-900\': darkMode === true}"';

        // Query to get adjustments with filters applied
        $query = Adjustment::query();

        if ($this->search) {
            $query->where('reason', 'like', "%{$this->search}%")
                ->orWhere('id', 'like', "%{$this->search}%");
        }

        // if ($this->statusFilter) {
        //     $query->where('status', $this->statusFilter);
        // }

        // if ($this->statusFilter) {
        //     $query->where('status', 'pending');
        // }

         $query->where('status', 'pending');

        // Paginate results
        $adjustments = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('livewire.stock.stock-adjustment-list', [
            'adjustments' => $adjustments
        ])->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
