<?php

namespace App\Livewire\Customer;

use App\Models\Customer;
use App\Models\Invoice;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Title;
use App\Helpers\CustomerNumberGenerator;

#[Title('Manage Customers')]
class CustomerCreate extends Component
{
    public $customer = null;
    public $isView = false;
    public $authUser = null;

    #[Validate('required|string|max:255', message: 'Name is required')]
    public $name;

    #[Validate('nullable|email|unique:customers,email', message: 'Invalid or duplicate email')]
    public $email;

    #[Validate('nullable|string|max:15', message: 'Phone must not exceed 15 characters')]
    public $phone;

    #[Validate('nullable|string', message: 'Address is optional')]
    public $address;

    #[Validate('nullable|string', message: 'City is optional')]
    public $city;

    #[Validate('nullable|string', message: 'Country is optional')]
    public $country;

    #[Validate('nullable|in:active,inactive', message: 'Invalid status')]
    public $status = 'active';

    #[Validate('nullable|string|max:15', message: 'Mobile number must not exceed 15 characters')]
    public $mobile_number;

    // Credit Limit fields
    #[Validate('nullable|integer|min:0', message: 'Credit days must be a positive number')]
    public $credit_limit_1_days;

    #[Validate('nullable|numeric|min:0', message: 'Credit amount must be a positive number')]
    public $credit_limit_1_amount;

    #[Validate('nullable|integer|min:0', message: 'Credit days must be a positive number')]
    public $credit_limit_2_days;

    #[Validate('nullable|numeric|min:0', message: 'Credit amount must be a positive number')]
    public $credit_limit_2_amount;

    public $customer_type = 'credit'; // Default to credit customer
    public $outstanding_balance = 0;
    public $original_customer_type = null; // Store original customer type
    public $current_outstanding = 0; // Current outstanding balance to display

    public function updatedCustomerType($value)
    {
        // Check if trying to change from credit to cash customer in edit mode
        if ($this->customer && $this->original_customer_type === 'credit' && $value === 'cash') {
            $outstanding = Invoice::where('customer_id', $this->customer->id)
                ->where('amount_due', '>', 0)
                ->sum('amount_due');

            if ($outstanding > 0) {
                $this->customer_type = 'credit'; // Revert to credit
                $this->outstanding_balance = $outstanding;
                session()->flash('error', "Customer have {$outstanding} outstanding balance. Please clear balance and select as cash customer.");
            }
        }

        // Load outstanding balance when selecting credit customer
        if ($value === 'credit' && $this->customer) {
            $this->loadOutstandingBalance();
        } else {
            $this->current_outstanding = 0;
        }
    }

    public function loadOutstandingBalance()
    {
        if ($this->customer) {
            $this->current_outstanding = Invoice::where('customer_id', $this->customer->id)
                ->where('amount_due', '>', 0)
                ->sum('amount_due');
        }
    }

    public function checkOutstandingBalanceWhenTabActive()
    {
        // This method is called when user switches to Credit Limit & Period tab
        // It checks if customer has outstanding balance and updates the UI accordingly
        $this->loadOutstandingBalance();
    }

    public function mount(Customer $customer)
    {
        $this->authUser = auth()->user();
        $this->isView = request()->routeIs('customers.view');

        if ($customer->id) {
            $this->customer = $customer;
            $this->name = $customer->name;
            $this->email = $customer->email;
            $this->phone = $customer->phone;
            $this->address = $customer->address;
            $this->city = $customer->city;
            $this->country = $customer->country;
            $this->status = $customer->status;
            $this->mobile_number = $customer->mobile_number;
            $this->credit_limit_1_days = $customer->credit_limit_1_days;
            $this->credit_limit_1_amount = $customer->credit_limit_1_amount;
            $this->credit_limit_2_days = $customer->credit_limit_2_days;
            $this->credit_limit_2_amount = $customer->credit_limit_2_amount;

            // Store original customer type for validation
            if ($customer->is_credit_customer) {
                $this->original_customer_type = 'credit';
                $this->customer_type = 'credit'; // Set based on actual customer type
            } else {
                $this->original_customer_type = 'cash';
                $this->customer_type = 'cash'; // Set based on actual customer type
            }

            // Load outstanding balance if the original was a credit customer
            if ($customer->is_credit_customer) {
                $this->loadOutstandingBalance();
            }
        }
    }

    public function saveCustomer()
    {
        // Check if trying to change from credit to cash customer with outstanding balance
        if ($this->customer && $this->original_customer_type === 'credit' && $this->customer_type === 'cash') {
            $outstanding = Invoice::where('customer_id', $this->customer->id)
                ->where('amount_due', '>', 0)
                ->sum('amount_due');

            if ($outstanding > 0) {
                session()->flash('error', "Customer have {$outstanding} outstanding balance. Please clear balance and select as cash customer.");
                return;
            }
        }

        // If customer type is credit, validate credit limit fields
        if ($this->customer_type === 'credit') {
            $this->validate([
                'name' => 'required|string|max:255',
                'email' => 'nullable|email',
                'phone' => 'nullable|string|max:15',
                'address' => 'nullable|string',
                'city' => 'nullable|string',
                'country' => 'nullable|string',
                'mobile_number' => 'nullable|string|max:15',
                'credit_limit_1_days' => 'required|integer|min:1',
                'credit_limit_1_amount' => 'required|numeric|gt:0',
                'credit_limit_2_days' => 'required|integer|min:1',
                'credit_limit_2_amount' => 'required|numeric|gt:0',
            ], [
                'credit_limit_1_days.required' => 'Credit Limit 1 Days is required for credit customers.',
                'credit_limit_1_days.min' => 'Credit Limit 1 Days must be at least 1 day.',
                'credit_limit_1_amount.required' => 'Credit Limit 1 Amount is required for credit customers.',
                'credit_limit_1_amount.gt' => 'Credit Limit 1 Amount must be greater than 0.00.',
                'credit_limit_2_days.required' => 'Credit Limit 2 Days is required for credit customers.',
                'credit_limit_2_days.min' => 'Credit Limit 2 Days must be at least 1 day.',
                'credit_limit_2_amount.required' => 'Credit Limit 2 Amount is required for credit customers.',
                'credit_limit_2_amount.gt' => 'Credit Limit 2 Amount must be greater than 0.00.',
            ]);
        } else {
            // For cash customers, validate only basic fields
            $this->validate([
                'name' => 'required|string|max:255',
                'email' => 'nullable|email',
                'phone' => 'nullable|string|max:15',
                'address' => 'nullable|string',
                'city' => 'nullable|string',
                'country' => 'nullable|string',
                'mobile_number' => 'nullable|string|max:15',
            ]);
        }

        if ($this->customer) {
            # Update existing customer
            $this->customer->update([
                'name' => $this->name,
                'branch_id'=> $this->authUser->branch_id,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
                'city' => $this->city,
                'country' => $this->country,
                'status' => $this->status,
                'mobile_number' => $this->mobile_number,
                'credit_limit_1_days' => $this->customer_type === 'credit' ? $this->credit_limit_1_days : null,
                'credit_limit_1_amount' => $this->customer_type === 'credit' ? $this->credit_limit_1_amount : null,
                'credit_limit_2_days' => $this->customer_type === 'credit' ? $this->credit_limit_2_days : null,
                'credit_limit_2_amount' => $this->customer_type === 'credit' ? $this->credit_limit_2_amount : null,
                'is_credit_customer' => $this->customer_type === 'credit',
                'is_cash_customer' => $this->customer_type === 'cash',
            ]);

            session()->flash('success', 'Customer has been updated successfully!');
        } else {
            // Validation already done above based on customer type

            # Create a new customer with generated customer_number
            $customerNumber = CustomerNumberGenerator::generate($this->authUser->branch_id);
            Customer::create([
                'name' => $this->name,
                'branch_id'=> $this->authUser->branch_id,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
                'city' => $this->city,
                'country' => $this->country,
                'status' => $this->status,
                'mobile_number' => $this->mobile_number,
                'customer_number' => $customerNumber,
                'credit_limit_1_days' => $this->customer_type === 'credit' ? $this->credit_limit_1_days : null,
                'credit_limit_1_amount' => $this->customer_type === 'credit' ? $this->credit_limit_1_amount : null,
                'credit_limit_2_days' => $this->customer_type === 'credit' ? $this->credit_limit_2_days : null,
                'credit_limit_2_amount' => $this->customer_type === 'credit' ? $this->credit_limit_2_amount : null,
                'is_credit_customer' => $this->customer_type === 'credit',
                'is_cash_customer' => $this->customer_type === 'cash',
            ]);

            session()->flash('success', 'Customer has been created successfully!');
        }

        return $this->redirect('/customers', navigate: true);
    }

    public function render()
    {
        $bodyAttributes = 'x-data="{ page: \'addCustomer\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
            x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                    $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
            :class="{\'dark bg-gray-900\': darkMode === true}"';
        return view('livewire.customer.customer-create')->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
