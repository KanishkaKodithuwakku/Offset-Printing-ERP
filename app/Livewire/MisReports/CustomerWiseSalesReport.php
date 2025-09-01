<?php

namespace App\Livewire\MisReports;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Invoice;
use App\Models\Customer;

class CustomerWiseSalesReport extends Component
{
    use WithPagination;

    public $startDate;
    public $endDate;
    public $perPage = 25;
    public $customerId = null;
    public $customerSearch = '';
    public $customerResults = [];

    public function mount()
    {
        $this->startDate = '';
        $this->endDate = '';
    }

    public function updatingStartDate() {
        $this->resetPage();
    }
    public function updatingEndDate() {
        $this->resetPage();
    }

    public function updatedCustomerSearch($value)
    {
        if (strlen($value) > 0) {
            $this->customerResults = Customer::where('name', 'like', "%{$value}%")
                ->limit(10)
                ->get();
        } else {
            $this->customerResults = [];
        }
    }

    public function selectCustomer($id)
    {
        $customer = Customer::find($id);
        if ($customer) {
            $this->customerId = $customer->id;
            $this->customerSearch = $customer->name;
            $this->customerResults = [];
        }
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
        if ($this->customerId) {
            $salesQuery->where('customer_id', $this->customerId);
        }
        $salesQuery->orderBy('created_at', 'asc');
        $sales = $salesQuery->paginate($this->perPage);

        return view('livewire.mis-reports.customer-wise-sales-report', [
            'sales' => $sales,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'customerSearch' => $this->customerSearch,
            'customerId' => $this->customerId,
            'customerResults' => $this->customerResults,
        ])->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }

    public function printReport()
    {
        $this->dispatchBrowserEvent('print-report');
    }
}
