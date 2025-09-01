<?php

namespace App\Livewire\MisReports;

use Livewire\Component;
use App\Models\Invoice;

class CustomerWiseSalesReportPrint extends Component
{
    public $startDate;
    public $endDate;
    public $customerSearch = '';

    public function mount()
    {
        $this->startDate = request()->query('startDate', '');
        $this->endDate = request()->query('endDate', '');
        $this->customerSearch = request()->query('customerSearch', '');
    }

    public function render()
    {
          $bodyAttributes = 'x-data="{ page: \'SalesDataWiseReport\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
        x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
        :class="{\'dark bg-gray-900\': darkMode === true}"';

        $salesQuery = Invoice::with(['customer', 'order'])->where('status', 'invoiced');
        if ($this->startDate) {
            $salesQuery->whereDate('created_at', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $salesQuery->whereDate('created_at', '<=', $this->endDate);
        }
        if ($this->customerSearch) {
            $salesQuery->whereHas('customer', function($q) {
                $q->where('name', 'like', '%' . $this->customerSearch . '%');
            });
        }
        $salesQuery->orderBy('created_at', 'asc');
        $sales = $salesQuery->get();

        return view('livewire.mis-reports.customer-wise-sales-report-print', [
            'sales' => $sales,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'customerSearch' => $this->customerSearch,
        ])->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
