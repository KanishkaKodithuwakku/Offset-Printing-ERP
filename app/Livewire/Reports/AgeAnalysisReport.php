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
    $total_1_30_days = 0;
    $total_31_60_days = 0;
    $total_61_90_days = 0;
    $total_over_90_days = 0;

    $bodyAttributes = 'x-data="{ page: \'AgeAnalysisReport\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
    x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
            $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
    :class="{\'dark bg-gray-900\': darkMode === true}"';

    $currentDate = Carbon::now();

    $query = DB::table('invoices')
        ->join('customers', 'invoices.customer_id', '=', 'customers.id')
        ->where('invoices.amount_due', '>', 0)
        ->where('invoices.status', 'invoiced')
        // ->where('invoices.payment_status', 'unpaid')
        ->select(
            'customers.name as customer_name',
            'customers.customer_number',
            'invoices.customer_id',
            DB::raw('SUM(invoices.amount_due) as total_amount'),
            DB::raw('COUNT(invoices.id) as invoice_count'),
            'invoices.created_at',
            'invoices.payment_status'
        )
        ->groupBy('invoices.customer_id')
        ->orderBy('customer_name');

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

    // Customer filter - Moved this before executing the query
    if ($this->selectedCustomerId) {
        $query->where('invoices.customer_id', (int)$this->selectedCustomerId);
    }

    // If pagination is enabled, paginate the results
    if ($this->paginationEnabled) {
        $invoices = $query->orderBy('invoices.created_at', 'desc')
            ->paginate($this->perPage);

        $invoices->getCollection()->transform(function ($invoice) use ($currentDate, &$total_1_30_days, &$total_31_60_days, &$total_61_90_days, &$total_over_90_days) {
            $invoiceDate = Carbon::parse($invoice->created_at);
            $diffInDays = $invoiceDate->diffInDays($currentDate);

            $ageCategory = $this->getAgeCategory($diffInDays);

            if ($ageCategory == '1-30 Days') {
                $total_1_30_days += $invoice->total_amount;
            } elseif ($ageCategory == '31-60 Days') {
                $total_31_60_days += $invoice->total_amount;
            } elseif ($ageCategory == '61-90 Days') {
                $total_61_90_days += $invoice->total_amount;
            } else {
                $total_over_90_days += $invoice->total_amount;
            }

            return [
                'customer_name' => $invoice->customer_name,
                'customer_number' => $invoice->customer_number ?? '',
                'total_amount' => $invoice->total_amount,
                'invoice_date' => $invoice->created_at,
                'age_category' => $ageCategory,
                'payment_status' => $invoice->payment_status,
            ];
        });
    } else {
        $invoices = $query->orderBy('invoices.created_at', 'desc')
            ->get()
            ->map(function ($invoice) use ($currentDate, &$total_1_30_days, &$total_31_60_days, &$total_61_90_days, &$total_over_90_days) {
                $invoiceDate = Carbon::parse($invoice->created_at);
                $diffInDays = $invoiceDate->diffInDays($currentDate);

                $ageCategory = $this->getAgeCategory($diffInDays);

                if ($ageCategory == '1-30 Days') {
                    $total_1_30_days += $invoice->total_amount;
                } elseif ($ageCategory == '31-60 Days') {
                    $total_31_60_days += $invoice->total_amount;
                } elseif ($ageCategory == '61-90 Days') {
                    $total_61_90_days += $invoice->total_amount;
                } else {
                    $total_over_90_days += $invoice->total_amount;
                }

                return [
                    'customer_name' => $invoice->customer_name,
                    'customer_number' => $invoice->customer_number ?? '',
                    'total_amount' => $invoice->total_amount,
                    'invoice_date' => $invoice->created_at,
                    'age_category' => $ageCategory,
                    'payment_status' => $invoice->payment_status,
                ];
            });
    }

    return view('livewire.reports.age-analysis-report', [
        'invoices' => $invoices,
        'total_1_30_days' => $total_1_30_days,
        'total_31_60_days' => $total_31_60_days,
        'total_61_90_days' => $total_61_90_days,
        'total_over_90_days' => $total_over_90_days,
        'customers' => $this->customers,
        'selectedCustomerId' => $this->selectedCustomerId,
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

