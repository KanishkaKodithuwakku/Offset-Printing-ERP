<?php

namespace App\Livewire\Customer;

use App\Models\Payment;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Customer;

class RecevedPaymentList extends Component
{
    use WithPagination;

    public $searchTerm = '';
    public $statusFilter = 'ALL';
    public $methodFilter = '';
    public $startDate;
    public $endDate;
    public $customers;
    public $customerId = '';
    public $paginationEnabled = true;

    public function applyFilters()
    {
        $this->resetPage();
    }

    public function mount()
    {
        $this->customers = \App\Models\Customer::select('id', 'name')->get();
    }

    public function loadCustomerInvoices()
    {
        // Your logic here, e.g., filter payments by $this->customerId
    }

    public function updatingStartDate()
    {
        $this->resetPage();
    }

    public function updatingEndDate()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingCustomerId()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Payment::query()->with(['customer', 'bank', 'bankBranch'])->withSum('paymentDetails', 'amount');

        if (!empty($this->statusFilter) && $this->statusFilter !== 'ALL') {
            $query->where('method', $this->statusFilter);
        }

        if (request('customerId')) {
            $query->where('customer_id', request('customerId'));
        }

        // Search filter
        if ($this->searchTerm) {
            $query->where(function ($q) {
                $q->where('payment_code', 'like', "%{$this->searchTerm}%")
                    ->orWhere('amount', 'like', "%{$this->searchTerm}%")
                    ->orWhere('check_number', 'like', "%{$this->searchTerm}%")
                    ->orWhereHas('customer', fn($q2) =>
                        $q2->where('name', 'like', "%{$this->searchTerm}%"));
            });
        }

        // Date range filter
        if ($this->startDate) {
            $start = Carbon::createFromFormat('Y-m-d', $this->startDate)->startOfDay();
            $query->whereDate('created_at', '>=', $start);
        }

        if ($this->endDate) {
            $end = Carbon::createFromFormat('Y-m-d', $this->endDate)->endOfDay();
            $query->whereDate('created_at', '<=', $end);
        }

        if (!empty($this->customerId)) {
            $query->where('customer_id', $this->customerId);
        }

        $payments = $this->paginationEnabled
            ? $query->orderBy('created_at', 'asc')->paginate(25)
            : $query->orderBy('created_at', 'asc')->get();
        $this->customers = \App\Models\Customer::select('id', 'name')->get();

        $bodyAttributes = 'x-data="{ page: \'RecevedPaymentsPrint\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
            x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                    $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
            :class="{\'dark bg-gray-900\': darkMode === true}"';

        return view('livewire.customer.receved-payment-list', [
            'payments' => $payments,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'searchTerm' => $this->searchTerm,
            'statusFilter' => $this->statusFilter,
            'customers' => $this->customers,
            'customerId' => $this->customerId,
        ])->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
