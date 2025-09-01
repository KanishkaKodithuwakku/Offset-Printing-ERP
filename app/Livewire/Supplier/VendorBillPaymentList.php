<?php

namespace App\Livewire\Supplier;

use App\Models\VendorPayment;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class VendorBillPaymentList extends Component
{
    use WithPagination;

    public $searchTerm = '';
    public $statusFilter = 'ALL';
    public $methodFilter = '';
    public $startDate;
    public $endDate;
    public $suppliers;
    public $supplierId = '';
    public $paginationEnabled = true;

    public function applyFilters()
    {
        $this->resetPage();
    }

    public function mount()
    {
        $this->suppliers = \App\Models\Supplier::select('id', 'name')->get();
    }

    public function loadSupplierBills()
    {
        // Your logic here, e.g., filter payments by $this->supplierId
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

    public function updatingSupplierId()
    {
        $this->resetPage();
    }

    public function printPayment($paymentId)
    {
        // Redirect to the multiple print preview page with the payment ID
        // Now the component will use the direct relationship between vendor_payments and vendor_bills
        return redirect()->route('vendor-bill.multiple-print-preview', ['id' => $paymentId]);
    }

    public function deletePayment($paymentId)
    {
        try {
            $payment = VendorPayment::findOrFail($paymentId);
            $payment->delete();
            session()->flash('success', 'Payment deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error deleting payment: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $query = VendorPayment::query()
            ->with(['vendor']);

        if (!empty($this->statusFilter) && $this->statusFilter !== 'ALL') {
            $query->where('payment_method', $this->statusFilter);
        }

        if (request('supplierId')) {
            $query->where('vendor_id', request('supplierId'));
        }

        // Search filter
        if ($this->searchTerm) {
            $query->where(function ($q) {
                $q->where('total_amount', 'like', "%{$this->searchTerm}%")
                    ->orWhere('receipt_number', 'like', "%{$this->searchTerm}%")
                    ->orWhere('check_number', 'like', "%{$this->searchTerm}%")
                    ->orWhereHas('vendor', fn($q2) =>
                        $q2->where('name', 'like', "%{$this->searchTerm}%"));
            });
        }

        // Date range filter
        if ($this->startDate) {
            $start = Carbon::createFromFormat('Y-m-d', $this->startDate)->startOfDay();
            $query->whereDate('payment_date', '>=', $start);
        }

        if ($this->endDate) {
            $end = Carbon::createFromFormat('Y-m-d', $this->endDate)->endOfDay();
            $query->whereDate('payment_date', '<=', $end);
        }

        if (!empty($this->supplierId)) {
            $query->where('vendor_id', $this->supplierId);
        }

        // Pagination logic
        if ($this->paginationEnabled) {
            $payments = $query->orderBy('id', 'desc')->paginate(25);
        } else {
            $payments = $query->orderBy('id', 'desc')->get();
        }

        $bodyAttributes = 'x-data="{ page: \'VendorBillPayments\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
            x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                    $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
            :class="{\'dark bg-gray-900\': darkMode === true}"';

        return view('livewire.supplier.vendor-bill-payment-list', [
            'payments' => $payments
        ])->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
