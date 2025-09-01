<?php

namespace App\Livewire\CheckManagement;

use App\Models\Invoice;
use Livewire\Component;
use App\Models\Customer;
use Livewire\WithPagination;
use App\Models\PostdatedCheque;
use App\Models\PaidInvoice;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class CheckList extends Component
{
    use WithPagination;
    
    protected $listeners = ['refreshCheques' => '$refresh'];        
    
    // Search and Filters
    public $bank_id = '';
    public $search = '';
    public $status = '';
    public $dateFilter = '';
    
    // Customer Selection
    public $customer_name = '';
    public $searchCustomer = '';
    public $searchResultsCustomer = [];
    public $selectedCustomerId = '';
    
    // Cheque Selection
    public $selectedCheques = [];
    public $selectAll = false;
    
    // Modals
    public $selectedCheque = null;
    public $showChequeDetails = false;
    public $showInvoicesModal = false;
    public $chequeInvoices = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'dateFilter' => ['except' => ''],
        'selectedCustomerId' => ['except' => ''],
    ];

    public function updatedSearchCustomer($value)
    {
        if (strlen($value) >= 2) {
            $this->searchResultsCustomer = Customer::where('name', 'like', '%'.$value.'%')
                ->limit(5)
                ->get()
                ->toArray();
        } else {
            $this->searchResultsCustomer = [];
        }
    }

    public function assignCustomer($customerId)
    {
        $customer = Customer::find($customerId);
        if ($customer) {
            $this->selectedCustomerId = $customer->id;
            $this->customer_name = $customer->name;
            $this->searchResultsCustomer = [];
            $this->searchCustomer = $customer->name;
        }
    }

    public function clearCustomerFilter()
    {
        $this->selectedCustomerId = '';
        $this->customer_name = '';
        $this->searchCustomer = '';
        $this->searchResultsCustomer = [];
    }

    public function toggleSelectAll($checked)
    {
        $this->selectedCheques = $checked
            ? $this->getFilteredChequesQuery()
                ->pluck('id')
                ->toArray()
            : [];
    }

    public function bulkUpdateStatus($status)
    {
        $validStatuses = ['pending', 'deposited', 'return', 'realize', 'cancel'];

        if (!in_array($status, $validStatuses)) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Invalid status selected'
            ]);
            return;
        }

        if (empty($this->selectedCheques)) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Please select at least one cheque'
            ]);
            return;
        }

        try {
            \DB::transaction(function () use ($status) {
                $cheques = PostdatedCheque::with('paidInvoices.invoice')
                    ->whereIn('id', $this->selectedCheques)
                    ->get();
                
                if ($status === 'cancel') {
                    // Update associated invoices
                    $invoiceIds = $cheques->pluck('paidInvoices.*.invoice_id')->flatten()->filter();
                    
                    if ($invoiceIds->isNotEmpty()) {
                        Invoice::whereIn('id', $invoiceIds)
                            ->update(['is_used' => false]);
                    }
                    
                    // Delete the paid invoice records
                    PaidInvoice::whereIn('postdated_cheque_id', $this->selectedCheques)->delete();
                    
                    // Delete the cheques
                    $deleted = PostdatedCheque::whereIn('id', $this->selectedCheques)->delete();
                    
                    $this->dispatch('notify', [
                        'type' => 'success',
                        'message' => "Cancelled {$deleted} cheques and updated invoices"
                    ]);
                } else {
                    $updated = PostdatedCheque::whereIn('id', $this->selectedCheques)
                        ->update(['status' => $status]);
                    
                    $this->dispatch('notify', [
                        'type' => 'success',
                        'message' => "Updated {$updated} cheques to {$status} status"
                    ]);
                }
            });
        } catch (\Exception $e) {
            logger()->error('Cheque status update failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error: ' . $e->getMessage()
            ]);
        } finally {
            $this->selectedCheques = [];
            $this->selectAll = false;
            $this->dispatch('refreshCheques');
        }
    }

    public function updateChequeStatus($chequeId, $status)
    {
        $validStatuses = ['pending', 'deposited', 'return', 'realize', 'cancel'];

        if (!in_array($status, $validStatuses)) {
            $this->dispatch('alert', [
                'type' => 'error',
                'message' => 'Invalid status selected'
            ]);
            return;
        }

        $cheque = PostdatedCheque::with('paidInvoices.invoice')->find($chequeId);

        if (!$cheque) {
            $this->dispatch('alert', [
                'type' => 'error',
                'message' => 'Cheque not found'
            ]);
            return;
        }

        try {
            \DB::transaction(function () use ($cheque, $status) {
                if ($status === 'cancel') {
                    // Update associated invoices
                    $invoiceIds = $cheque->paidInvoices->pluck('invoice_id');
                    
                    if ($invoiceIds->isNotEmpty()) {
                        Invoice::whereIn('id', $invoiceIds)
                            ->update(['is_used' => false]);
                    }
                    
                    // Delete the paid invoice records
                    PaidInvoice::where('postdated_cheque_id', $cheque->id)->delete();
                    
                    // Delete the cheque
                    $cheque->delete();
                } else {
                    $cheque->update(['status' => $status]);
                }
            });
        } catch (\Exception $e) {
            $this->dispatch('alert', [
                'type' => 'error',
                'message' => 'Error updating cheque: ' . $e->getMessage()
            ]);
        }

        $this->dispatch('refreshCheques');
    }

    public function printCheque($chequeId)
    {
        return redirect()->route('check-management.print', [
            'id' => $chequeId,
            'details' => true
        ]);
    }

    public function printSelectedCheques()
    {
        if (empty($this->selectedCheques)) {
            session()->flash('error', 'No cheques selected for printing.');
            return;
        }

        return redirect()->route('check-management.print', [
            'ids' => implode(',', $this->selectedCheques),
            'details' => true
        ]);
    }

    public function printFilteredCheques()
    {
        $query = $this->getFilteredChequesQuery();
        $cheques = $query->get();
        
        if ($cheques->isEmpty()) {
            session()->flash('error', 'No cheques found to print.');
            return;
        }

        return redirect()->route('check-management.print', [
            'ids' => $cheques->pluck('id')->implode(','),
            'details' => true
        ]);
    }

    public function viewChequeDetails($chequeId)
    {
        $this->selectedCheque = PostdatedCheque::with(['paidInvoices.invoice'])
            ->find($chequeId);
        $this->showChequeDetails = true;
    }

    public function showChequeInvoices($chequeId)
    {
        $this->selectedCheque = PostdatedCheque::with(['paidInvoices.invoice'])
            ->find($chequeId);
        
        if ($this->selectedCheque) {
            $this->chequeInvoices = $this->selectedCheque->paidInvoices;
            $this->showInvoicesModal = true;
        }
    }

    public function closeModal()
    {
        $this->showChequeDetails = false;
        $this->showInvoicesModal = false;
        $this->selectedCheque = null;
        $this->chequeInvoices = [];
    }

    public function resetFilters()
    {
        $this->reset(['search', 'status', 'dateFilter', 'selectedCustomerId', 'customer_name', 'searchCustomer']);
        $this->resetPage();
    }

    protected function getFilteredChequesQuery()
    {
        return PostdatedCheque::query()
            ->with(['paidInvoices.invoice'])
            ->when($this->customer_name, fn($q) => $q->where('customer_name', $this->customer_name))
            ->when($this->search, fn($q) => $q->where(function($query) {
                $query->where('cheque_number', 'like', '%'.$this->search.'%')
                    ->orWhere('bank_name', 'like', '%'.$this->search.'%')
                    ->orWhere('branch_name', 'like', '%'.$this->search.'%')
                    ->orWhere('amount', 'like', '%'.$this->search.'%')
                    ->orWhere('customer_name', 'like', '%'.$this->search.'%')
                    ->orWhereHas('paidInvoices.invoice', function($q) {
                        $q->where('invoice_number', 'like', '%'.$this->search.'%');
                    });
            }))
            ->when($this->status, fn($q) => $q->where('status', $this->status))
            ->when($this->dateFilter, fn($q) => $q->whereDate('cheque_date', $this->dateFilter));
    }

    public function render()
    {
        $bodyAttributes = 'x-data="{ page: \'create-cheque\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
            x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                    $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
            :class="{\'dark bg-gray-900\': darkMode === true}"';
            
        $cheques = $this->getFilteredChequesQuery()
            ->latest()
            ->paginate(10);

        return view('livewire.check-management.check-list', [
            'cheques' => $cheques
        ])->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}