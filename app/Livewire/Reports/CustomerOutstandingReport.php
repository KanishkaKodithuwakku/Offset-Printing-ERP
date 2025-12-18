<?php

namespace App\Livewire\Reports;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Invoice;
use App\Models\Customer;
use Carbon\Carbon;

class CustomerOutstandingReport extends Component
{
    use WithPagination;

    public $startDate;
    public $endDate;
    public $searchInvoice = '';
    public $perPage = 25;
    public $paginationEnabled = false;
    public $customers = [];
    public $selectedCustomerId = '';

    public function mount()
    {
        // default to last 30 days
        $this->startDate = Carbon::now()->subDays(30)->toDateString();
        $this->endDate   = Carbon::now()->toDateString();
        $this->customers = Customer::orderBy('name')->get();
    }

    // reset to page 1 when filters change
    public function updatingSearchInvoice()
    {
        $this->resetPage();
    }
    public function updatedStartDate()
    {
        $this->resetPage();
    }
    public function updatedEndDate()
    {
        $this->resetPage();
    }
    public function updatedSelectedCustomerId()
    {
        $this->resetPage();
    }

    public function render()
    {
        $bodyAttributes = 'x-data="{ page: \'CustomerOutstandingReport\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
    x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
            $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
    :class="{\'dark bg-gray-900\': darkMode === true}"';

        $query = Invoice::with('order')
            // Filter: status = 'invoiced' AND payment_status in ['partial', 'unpaid']
            ->where('status', 'invoiced')
            ->whereIn('payment_status', ['partial', 'unpaid'])
            ->when(
                $this->searchInvoice,
                fn($q) =>
                $q->where('invoice_number', 'like', $this->searchInvoice . '%')
            )
            ->when(
                $this->startDate && $this->endDate,
                fn($q) =>
                $q->whereBetween('created_at', [
                    $this->startDate . ' 00:00:00',
                    $this->endDate  . ' 23:59:59',
                ])
            )
            ->when(
                $this->selectedCustomerId,
                fn($q) =>
                $q->where('customer_id', (int)$this->selectedCustomerId)
            )
            ->where('amount_due', '>', 0)
            ->orderBy('created_at', 'asc');

        if ($this->paginationEnabled) {
            // Clone the query to get all invoices for totals (before pagination)
            $allInvoices = (clone $query)->get();
            // Then paginate for display
            $invoices = $query->paginate($this->perPage);
        } else {
            $invoices = $query->get();
            $allInvoices = $invoices;
        }

        return view('livewire.reports.customer-outstanding-report', [
            'invoices' => $invoices,
            'allInvoices' => $allInvoices,
            'customers' => $this->customers,
            'selectedCustomerId' => $this->selectedCustomerId,
        ])->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
