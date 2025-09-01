<?php

namespace App\Livewire\CheckManagement;

use App\Models\Bank;
use App\Models\Ledger;
use App\Models\Invoice;
use Livewire\Component;
use App\Models\Customer;
use App\Models\BankBranch;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use App\Models\PostdatedCheque;
use App\Models\cheqe_paid_invoice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckCreate extends Component
{
    use WithFileUploads;
    public $selectedInvoiceIds = []; // Initialize as empty array
    public $searchResultsCustomer = [];
    public $searchCustomer = '';
    public $customer_name = '';
    public $invoice_number = '';
    public $cheque_number = '';
    // public $bank_ledger_id = null;
    public $amount = '';
    public $cheque_date = '';
    public $details = '';
    public $cheque_image;
    public $temporaryUrl;
    public $showCustomerResults = false;

    // Invoice related properties
    public $customerInvoices = [];
    public $selectedInvoiceId = null;
    public $invoiceSearch = '';
    public $bank_id = '';
    public $branch_id = '';
    public $bank_name = '';
    public $branch_name = '';
    // public $selectedInvoiceIds = [];
    public $selectedInvoiceNumber = '';

    public $selectedCustomerId = null;
    #[On('selectedBank')]
    public function updateSelectedBank($id)
    {
        $this->bank_id = $id;
        $this->bank_name = Bank::find($id)->name ?? '';
    }

    #[On('selectedBranch')]
    public function updateSelectedBranch($id)
    {
        $this->branch_id = $id;
        $this->branch_name = BankBranch::find($id)->name ?? '';
    }
    protected $rules = [
        'customer_name' => 'required|min:3',
        'cheque_number' => 'required|unique:postdated_cheques,cheque_number',
        // 'bank_ledger_id' => 'required|exists:ledgers,id',
        'amount' => 'required|numeric|min:0.01',
        'cheque_date' => 'required|date|after_or_equal:today',
        'selectedInvoiceIds' => 'nullable|array',
    ];

    protected $messages = [

        'cheque_number.unique' => 'This cheque number already exists',
        'cheque_date.after_or_equal' => 'Cheque date must be today or in the future',
        'amount.min' => 'Amount must be at least 0.01',
    ];



    public function updatedSearchCustomer($value)
    {
        if (strlen($value) >= 2) {
            $this->searchResultsCustomer = Customer::where('name', 'like', '%' . $value . '%')
                ->limit(5)
                ->get()
                ->map(function ($customer) {
                    return [
                        'id' => $customer->id,
                        'name' => $customer->name,
                        'email' => $customer->email,
                        'phone' => $customer->phone,
                        'city' => $customer->city,
                    ];
                })->toArray();

            $this->showCustomerResults = true;
        } else {
            $this->searchResultsCustomer = [];
            $this->showCustomerResults = false;
        }
    }

    public function assignCustomer($customerId)
    {
        $customer = Customer::find($customerId);
        if ($customer) {
            $this->searchResultsCustomer = [];
            $this->customer_name = $customer->name;
            $this->searchCustomer = $customer->name;
            $this->selectedCustomerId = $customer->id;
            $this->showCustomerResults = false;
            $this->loadCustomerInvoices($customerId);
        }
    }

    protected function loadCustomerInvoices($customerId)
    {
        $query = Invoice::where('customer_id', $customerId)
            ->where('is_used', false) // Only show unused invoices
            ->where('status', '!=', 'paid'); // Optional: exclude already paid invoices

        if ($this->invoiceSearch) {
            $query->where(function ($q) {
                $q->where('invoice_number', 'like', '%' . $this->invoiceSearch . '%')
                    ->orWhere('amount_due', 'like', '%' . $this->invoiceSearch . '%');
            });
        }

        $this->customerInvoices = $query->get()
            ->map(function ($invoice) {
                return [
                    'id' => $invoice->id,
                    'invoice_number' => $invoice->invoice_number,
                    'amount' => $invoice->amount_due,
                    'date' => $invoice->created_at->format('Y-m-d'),
                ];
            })->toArray();
    }

    public function updatedInvoiceSearch()
    {
        if ($this->customer_name) {
            $customer = Customer::where('name', $this->customer_name)->first();
            if ($customer) {
                $this->loadCustomerInvoices($customer->id);
            }
        }
    }
    // public $invoice = '';

    public function selectInvoice($invoiceId, $invoiceNumber)
    {
        // Initialize as array if not already
        if (!is_array($this->selectedInvoiceIds)) {
            $this->selectedInvoiceIds = [];
        }

        // Convert all values to integers for consistency
        $currentSelection = array_map('intval', $this->selectedInvoiceIds);
        $invoiceId = (int) $invoiceId;

        if (($key = array_search($invoiceId, $currentSelection)) !== false) {
            // Remove from selection
            unset($currentSelection[$key]);
        } else {
            // Add to selection
            $currentSelection[] = $invoiceId;
        }

        // Re-index array and update property
        $this->selectedInvoiceIds = array_values($currentSelection);

        // Calculate total amount
        $this->calculateTotalAmount();

        // Update invoice numbers string
        $this->updateSelectedInvoiceNumbers();
    }

    protected function updateSelectedInvoiceNumbers()
    {
        if (empty($this->selectedInvoiceIds)) {
            $this->selectedInvoiceNumber = '';
            return;
        }

        $this->selectedInvoiceNumber = Invoice::whereIn('id', $this->selectedInvoiceIds)
            ->pluck('invoice_number')
            ->implode(', ');
    }

    protected function calculateTotalAmount()
    {
        $this->amount = Invoice::whereIn('id', $this->selectedInvoiceIds)
            ->sum('amount_due');
    }

    public function render()
    {
        $bodyAttributes = 'x-data="{ page: \'create-cheque\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
            x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                    $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
            :class="{\'dark bg-gray-900\': darkMode === true}"';

            return view('livewire.check-management.check-create')
            ->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }

public function save()
{
    $this->validate([
        'customer_name' => 'required|min:3',
        'bank_name' => 'required',
        'branch_name' => 'required',
        'cheque_number' => 'required|unique:postdated_cheques,cheque_number',
        'amount' => 'required|numeric|min:0.01',
        'cheque_date' => 'required|date|after_or_equal:today',
        'selectedInvoiceIds' => 'required|array|min:1',
    ], [
        'selectedInvoiceIds.required' => 'Please select at least one invoice',
    ]);

    try {
        DB::beginTransaction();

        $bank = Bank::findOrFail($this->bank_id);
        $branch = BankBranch::findOrFail($this->branch_id);

        // Create the cheque
        $cheque = PostdatedCheque::create([
            'customer_name' => $this->customer_name,
            'cheque_number' => $this->cheque_number,
            'bank_name' => $bank->name,
            'branch_name' => $branch->name,
            'amount' => $this->amount,
            'cheque_date' => $this->cheque_date,
            'details' => $this->details,
            'status' => 'pending',
        ]);

        // Attach invoices with their amounts
        foreach ($this->selectedInvoiceIds as $invoiceId) {
            $invoice = Invoice::findOrFail($invoiceId);
            
            $cheque->paidInvoices()->create([
                'invoice_id' => $invoiceId,
                'amount_paid' => $invoice->amount_due
            ]);

            // Mark invoice as allocated
            $invoice->update([
                'is_used' => true,
                'status' => 'allocated' // Consider adding this status
            ]);
        }

        DB::commit();

        session()->flash('success', 'Cheque created successfully with ' . count($this->selectedInvoiceIds) . ' invoices!');
        return redirect()->route('check-management.list');

    } catch (\Exception $e) {
        DB::rollBack();
        session()->flash('error', 'Error creating cheque: ' . $e->getMessage());
        Log::error('Cheque creation failed: ' . $e->getMessage());
    }
}

    
}

