<?php

namespace App\Livewire\Customer;

use App\Models\Payment;
use Carbon\Carbon;
use Livewire\Component;

class RecevedPaymentListPrint extends Component
{
    public $searchTerm;
    public $statusFilter;
    public $startDate;
    public $endDate;
    public $customerId;


    protected $queryString = [
        'startDate',
        'endDate',
        'searchTerm',
        'statusFilter',
        'customerId',
    ];

    public function mount()
    {
        $this->searchTerm = request('searchTerm');
        $this->statusFilter = request('statusFilter');
        $this->startDate = request('startDate');
        $this->endDate = request('endDate');
    }

    public function render()
    {
        $query = Payment::query()
            ->with(['customer', 'bank', 'bankBranch'])
            ->withSum('paymentDetails', 'amount');

        if (!empty($this->statusFilter) && $this->statusFilter !== 'ALL') {
            $query->where('method', $this->statusFilter);
        }

        if ($this->customerId) {
            $query->where('customer_id', $this->customerId);
        }
        if ($this->startDate) {
            $query->whereDate('date', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $query->whereDate('date', '<=', $this->endDate);
        }
        if ($this->searchTerm) {
            $query->where(function ($q) {
                $q->where('payment_code', 'like', "%{$this->searchTerm}%")
                    ->orWhere('amount', 'like', "%{$this->searchTerm}%")
                    ->orWhere('check_number', 'like', "%{$this->searchTerm}%")
                    ->orWhereHas('customer', fn($q2) =>
                        $q2->where('name', 'like', "%{$this->searchTerm}%"));
            });
        }

        if ($this->startDate) {
            $start = Carbon::createFromFormat('Y-m-d', $this->startDate)->startOfDay();
            $query->whereDate('created_at', '>=', $start);
        }
        if ($this->endDate) {
            $end = Carbon::createFromFormat('Y-m-d', $this->endDate)->endOfDay();
            $query->whereDate('created_at', '<=', $end);
        }

        $payments = $query->orderBy('created_at', 'desc')->get();

        $bodyAttributes = 'x-data="{ page: \'RecevedPaymentsPrint\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
            x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                    $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
            :class="{\'dark bg-gray-900\': darkMode === true}"';

        return view('livewire.customer.receved-payment-list-print', [
            'payments' => $payments
        ])->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
