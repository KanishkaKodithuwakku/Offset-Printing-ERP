<?php

namespace App\Livewire\Grn;

use App\Models\Grn;
use App\Models\Supplier;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class GrnList extends Component
{
    use WithPagination;

    #[Title('GRN Management')]

    public $searchTerm = '';
    public $sortColumn = 'id';
    public $sortOrder = 'desc';
    public $isLoading = false;
    public $statusFilter = 'pending';
    public $startDate;
    public $endDate;

    protected $listeners = ['grnsUpdated' => 'refreshGrns'];


    public function applyFilters()
    {
        $grns = Grn::query();

        // Apply status filter
        if ($this->statusFilter) {
            $grns->where('status', $this->statusFilter);
        }

        // Apply date range filter
        if ($this->startDate) {
            $grns->whereDate('created_at', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $grns->whereDate('created_at', '<=', $this->endDate);
        }

        // Get filtered data
        $grns->get();


    }
    public function showLoading()
    {
        $this->isLoading = true;
    }

    public function hideLoading()
    {
        $this->isLoading = false;
    }

    public function refreshGrns()
    {
        Log::info('Livewire Event: Received grnsUpdated, refreshing GRN list');
        $this->resetPage();
    }

    public function sortBy($columnName)
    {
        if ($this->sortColumn === $columnName) {
            $this->sortOrder = $this->sortOrder === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortColumn = $columnName;
            $this->sortOrder = 'asc';
        }
    }

    public function getGrnsProperty()
    {
        return Grn::whereHas('supplier', function ($query) {
                $query->where('name', 'like', '%' . $this->searchTerm . '%');
            })
            ->orWhere('grn_code', 'like', '%' . $this->searchTerm . '%')
            ->orderBy($this->sortColumn, $this->sortOrder)
            ->paginate(10);
    }

    public function deleteGrn($grnId)
    {
        $grn = Grn::find($grnId);
        if ($grn) {
            $grn->delete();
            session()->flash('success', 'GRN record deleted successfully!');
            $this->resetPage();
        } else {
            session()->flash('error', 'GRN record not found.');
        }

        if (!in_array(Auth::user()->mode, [ 'admin'])) {
            session()->flash('error', 'Invalid action! You do not have permission to delete this GRN.');
            return;
        }

    }

    public function openGrnForm($grnId)
    {
        return redirect()->route('grns.edit', ['grn' => $grnId]);
    }

    public function render()
    {
        $bodyAttributes = 'x-data="{ page: \'grnList\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
        x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
        :class="{\'dark bg-gray-900\': darkMode === true}"';

        // Paginate the GRNs, filtering based on status if necessary
        $grnsQuery = Grn::query();

        // Filter by status if a filter is applied
        if ($this->statusFilter) {
            $grnsQuery->where('status', $this->statusFilter);
        } else {
            $grnsQuery->where('status', 'pending'); // Default to pending status
        }

        // Apply date range filter
        if ($this->startDate) {
            $grnsQuery->whereDate('created_at', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $grnsQuery->whereDate('created_at', '<=', $this->endDate);
        }

        // Paginate results
        $grns = $grnsQuery->paginate(25); // Adjust the number of items per page as needed

        return view('livewire.grns.grn-list', [
            'grns' => $grns,  // Passing paginated data
        ])->layout('layouts.app',['bodyAttributes'=>$bodyAttributes]);
    }


}
