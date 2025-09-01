<?php

namespace App\Livewire\MisReports;

use Livewire\Component;
use App\Models\Invoice;

class SalesDatewiseReportPrint extends Component
{
    public $startDate;
    public $endDate;

    public function mount()
    {
        $this->startDate = request()->query('startDate', '');
        $this->endDate = request()->query('endDate', '');
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
        $sales = $salesQuery->get();

        return view('livewire.mis-reports.sales-datewise-report-print', [
            'sales' => $sales,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
        ])->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
