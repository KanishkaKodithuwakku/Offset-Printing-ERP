<?php

namespace App\Livewire\Reports;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Livewire\WithPagination;

class AgeAnalysisReport extends Component
{
    use WithPagination;

    public $paginationEnabled = true;
    public $startDate;
    public $endDate;
    public $searchInvoice = '';
    public $perPage = 25;
    public $customers = [];
    public $selectedCustomerId = '';

    public function updatingPaginationEnabled()
    {
        $this->resetPage();
    }

    public function updatedSearchInvoice()
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

    public function mount()
    {
        $this->customers = \App\Models\Customer::orderBy('name')->get();
    }

    public function render()
    {
        // Reset totals for this render
        $total_1_30_days = 0;
        $total_31_60_days = 0;
        $total_61_90_days = 0;
        $total_over_90_days = 0;

        $bodyAttributes = 'x-data="{ page: \'AgeAnalysisReport\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
    x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
            $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
    :class="{\'dark bg-gray-900\': darkMode === true}"';

        $currentDate = Carbon::now();

        // Get individual invoices first (no grouping)
        $query = DB::table('invoices')
            ->join('customers', 'invoices.customer_id', '=', 'customers.id')
            ->where('invoices.amount_due', '>', 0)
            ->where('invoices.status', 'invoiced')
            ->whereIn('invoices.payment_status', ['partial', 'unpaid'])
            ->select(
                'customers.name as customer_name',
                'customers.customer_number',
                'invoices.customer_id',
                'invoices.amount_due',
                'invoices.created_at',
                'invoices.payment_status',
                'invoices.id as invoice_id'
            )
            ->orderBy('customers.name')
            ->orderBy('invoices.created_at', 'desc');

        // Search filter
        if ($this->searchInvoice) {
            $query->where('invoices.invoice_number', 'like', $this->searchInvoice . '%');
        }

        // Date range filter
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('invoices.created_at', [
                $this->startDate . ' 00:00:00',
                $this->endDate . ' 23:59:59',
            ]);
        }

        // Customer filter
        if ($this->selectedCustomerId) {
            $query->where('invoices.customer_id', (int)$this->selectedCustomerId);
        }

        // Get all invoices and process them individually
        $allInvoices = $query->get();

        // Debug: Check if customer filter is working
        if ($this->selectedCustomerId) {
            // If a customer is selected, ensure we only have invoices for that customer
            $allInvoices = $allInvoices->where('customer_id', (int)$this->selectedCustomerId);
        }

        // Group by customer and calculate aging for each invoice
        $groupedInvoices = $allInvoices->groupBy('customer_id');
        $processedInvoices = collect();

        foreach ($groupedInvoices as $customerId => $customerInvoices) {
            $customerTotals = [
                '1-30' => 0,
                '31-60' => 0,
                '61-90' => 0,
                '90+' => 0
            ];

            // Process each invoice individually for aging
            foreach ($customerInvoices as $invoice) {
                $invoiceDate = Carbon::parse($invoice->created_at);
                $diffInDays = $invoiceDate->diffInDays($currentDate);
                $ageCategory = $this->getAgeCategory($diffInDays);

                // Add to customer totals based on individual invoice aging
                if ($ageCategory == '1-30 Days') {
                    $customerTotals['1-30'] += $invoice->amount_due;
                } elseif ($ageCategory == '31-60 Days') {
                    $customerTotals['31-60'] += $invoice->amount_due;
                } elseif ($ageCategory == '61-90 Days') {
                    $customerTotals['61-90'] += $invoice->amount_due;
                } else {
                    $customerTotals['90+'] += $invoice->amount_due;
                }
            }

            // Create customer summary record
            $customerTotal = $customerTotals['1-30'] + $customerTotals['31-60'] + $customerTotals['61-90'] + $customerTotals['90+'];

            // Determine the primary age category for this customer (the one with the highest amount)
            $primaryCategory = '1-30 Days';
            $maxAmount = $customerTotals['1-30'];

            if ($customerTotals['31-60'] > $maxAmount) {
                $maxAmount = $customerTotals['31-60'];
                $primaryCategory = '31-60 Days';
            }
            if ($customerTotals['61-90'] > $maxAmount) {
                $maxAmount = $customerTotals['61-90'];
                $primaryCategory = '61-90 Days';
            }
            if ($customerTotals['90+'] > $maxAmount) {
                $maxAmount = $customerTotals['90+'];
                $primaryCategory = 'Over 90 Days';
            }

            $processedInvoices->push([
                'customer_name' => $customerInvoices->first()->customer_name,
                'customer_number' => $customerInvoices->first()->customer_number ?? '',
                'total_amount' => $customerTotal,
                'amount_1_30' => $customerTotals['1-30'],
                'amount_31_60' => $customerTotals['31-60'],
                'amount_61_90' => $customerTotals['61-90'],
                'amount_over_90' => $customerTotals['90+'],
                'age_category' => $primaryCategory, // Add this field for the view
                'payment_status' => $customerInvoices->first()->payment_status,
                'invoice_count' => $customerInvoices->count(),
            ]);
        }

        // Apply pagination if enabled
        if ($this->paginationEnabled) {
            $currentPage = request()->get('page', 1);
            $offset = ($currentPage - 1) * $this->perPage;
            $paginatedItems = $processedInvoices->slice($offset, $this->perPage);

            $invoices = new \Illuminate\Pagination\LengthAwarePaginator(
                $paginatedItems,
                $processedInvoices->count(),
                $this->perPage,
                $currentPage,
                ['path' => request()->url(), 'pageName' => 'page']
            );
        } else {
            $invoices = $processedInvoices;
        }

        // Calculate totals from the processed invoices (filtered by selected customer if any)
        $displayTotal_1_30_days = $processedInvoices->sum('amount_1_30');
        $displayTotal_31_60_days = $processedInvoices->sum('amount_31_60');
        $displayTotal_61_90_days = $processedInvoices->sum('amount_61_90');
        $displayTotal_over_90_days = $processedInvoices->sum('amount_over_90');

        // Debug: Log the totals for troubleshooting
        if ($this->selectedCustomerId) {
            \Log::info('Customer ID: ' . $this->selectedCustomerId);
            \Log::info('Processed Invoices Count: ' . $processedInvoices->count());
            \Log::info('Totals: 1-30=' . $displayTotal_1_30_days . ', 31-60=' . $displayTotal_31_60_days . ', 61-90=' . $displayTotal_61_90_days . ', 90+=' . $displayTotal_over_90_days);
        }

        return view('livewire.reports.age-analysis-report', [
            'invoices' => $invoices,
            'total_1_30_days' => $displayTotal_1_30_days,
            'total_31_60_days' => $displayTotal_31_60_days,
            'total_61_90_days' => $displayTotal_61_90_days,
            'total_over_90_days' => $displayTotal_over_90_days,
            'customers' => $this->customers,
            'selectedCustomerId' => $this->selectedCustomerId,
            'bodyAttributes' => $bodyAttributes,
        ])->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }

    // Function to determine the age category
    private function getAgeCategory($days)
    {
        if ($days <= 30) {
            return '1-30 Days';
        } elseif ($days <= 60) {
            return '31-60 Days';
        } elseif ($days <= 90) {
            return '61-90 Days';
        } else {
            return 'Over 90 Days';
        }
    }
}
