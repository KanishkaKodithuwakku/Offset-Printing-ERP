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
    public $customerName = '';
    public $paginationEnabled = true;
    public $showSuggestions = false;

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

    public function updatingCustomerName()
    {
        $this->showSuggestions = true;
        $this->resetPage();
    }

    public function render()
    {
        $query = Payment::query()
            ->with(['customer', 'bank', 'bankBranch'])
            ->withSum('paymentDetails', 'amount')
            ->where('status', '!=', 'cancelled'); // hide cancelled payments

        if (!empty($this->statusFilter) && $this->statusFilter !== 'ALL') {
            $query->where('method', $this->statusFilter);
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

        if (!empty($this->customerName)) {
            $query->whereHas('customer', function ($q) {
                $q->where('name', 'like', "%{$this->customerName}%");
            });
        }

        $payments = $this->paginationEnabled
            ? $query->orderBy('created_at', 'asc')->paginate(25)
            : $query->orderBy('created_at', 'asc')->get();
        $this->customers = \App\Models\Customer::select('id', 'name')->get();

        // Build a lightweight suggestion list for the customer search input
        $customerSuggestions = collect();
        if ($this->showSuggestions && $this->customerName) {
            $customerSuggestions = \App\Models\Customer::select('name')
                ->where('name', 'like', "%{$this->customerName}%")
                ->orderBy('name')
                ->limit(10)
                ->get();
        }

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
            'customerName' => $this->customerName,
            'customerSuggestions' => $customerSuggestions,
            'showSuggestions' => $this->showSuggestions,
        ])->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }

    /**
     * Allow clicking a suggestion to populate the search box.
     */
    public function selectCustomer(string $name): void
    {
        $this->customerName = $name;
        $this->resetPage();
        $this->showSuggestions = false;
    }
}
