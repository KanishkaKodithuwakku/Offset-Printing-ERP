<?php

namespace App\Livewire\MonthlyTarget;

use App\Models\MonthlyTarget;
use Livewire\Component;
use Livewire\WithPagination;

class MonthlyTargetList extends Component
{
    use WithPagination;

    public $search = '';
    public $yearFilter = '';
    public $statusFilter = '';
    public $confirmingDelete = false;
    public $targetToDelete = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'yearFilter' => ['except' => ''],
        'statusFilter' => ['except' => '']
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingYearFilter()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['search', 'yearFilter', 'statusFilter']);
        $this->resetPage();
    }

    public function confirmDelete($monthlyTargetId)
    {
        $this->targetToDelete = $monthlyTargetId;
        $this->confirmingDelete = true;
    }

    public function deleteMonthlyTarget()
    {
        if ($this->targetToDelete) {
            $monthlyTarget = MonthlyTarget::find($this->targetToDelete);
            if ($monthlyTarget) {
                $monthlyTarget->delete();
                session()->flash('success', 'Monthly target deleted successfully!');
            } else {
                session()->flash('error', 'Monthly target not found. Please try again!');
            }
        }

        $this->confirmingDelete = false;
        $this->targetToDelete = null;
    }

    public function cancelDelete()
    {
        $this->confirmingDelete = false;
        $this->targetToDelete = null;
    }

    public function getYearOptionsProperty()
    {
        $years = MonthlyTarget::distinct()->pluck('year')->sort()->toArray();
        return array_combine($years, $years);
    }

    public function render()
    {
        $query = MonthlyTarget::query()
            ->with(['createdBy', 'updatedBy']);

        // Apply search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('month', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        // Apply year filter
        if ($this->yearFilter) {
            $query->where('year', $this->yearFilter);
        }

        // Apply status filter
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        $monthlyTargets = $query->latest()->paginate(25);

        $bodyAttributes = 'x-data="{ page: \'monthlyTargetList\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
            x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                    $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
            :class="{\'dark bg-gray-900\': darkMode === true}"';

        return view('livewire.monthly-target.monthly-target-list', compact('monthlyTargets', 'bodyAttributes'))
            ->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
