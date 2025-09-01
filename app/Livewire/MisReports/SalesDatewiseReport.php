<?php

namespace App\Livewire\MisReports;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Invoice;

class SalesDatewiseReport extends Component
{
    use WithPagination;

    public $startDate;
    public $endDate;
    public $perPage = 25;

    public function mount()
    {
        $this->startDate = '';
        $this->endDate = '';
    }

    public function updatingStartDate() {
        $this->resetPage();
    }
    public function updatedStartDate() {

    }
    public function updatingEndDate() {
        $this->resetPage();
    }
    public function updatedEndDate() {

    }

    public function render()
    {
        $bodyAttributes = 'x-data="{ page: \'SalesDataWiseReport\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
        x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
        :class="{\'dark bg-gray-900\': darkMode === true}"';

        $salesQuery = Invoice::with(['customer', 'order'])
            ->where('status', 'invoiced');
        if ($this->startDate) {
            $salesQuery->whereDate('created_at', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $salesQuery->whereDate('created_at', '<=', $this->endDate);
        }
        $salesQuery->orderBy('created_at', 'asc');
        $sales = $salesQuery->paginate($this->perPage);

        return view('livewire.mis-reports.sales-datewise-report', [
            'sales' => $sales,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
        ])->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }

    public function printReport()
    {
        $this->dispatchBrowserEvent('print-report');
    }
}
