<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Customer;
use Livewire\WithPagination;

class CustomerManager extends Component
{
    use WithPagination;

    public $name, $email, $phone, $address, $city, $country, $status;
    public $customerId = null; // Track the ID of the customer for edit operations

    // For search functionality
    public $searchTerm = '';
    public $sortColumn = 'name';
    public $sortOrder = 'asc';

    public $authUser = null;
    // Rules for form validation
    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'nullable|email|unique:customers,email',
        'phone' => 'nullable|string|max:15',
        'address' => 'nullable|string',
        'city' => 'nullable|string',
        'country' => 'nullable|string',
        'status' => 'in:active,inactive',
    ];


     public function mount()
    {
        $this->authUser = auth()->user();
    }

    public function render()
    {
        $bodyAttributes = 'x-data="{ page: \'customerList\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
            x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                    $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
            :class="{\'dark bg-gray-900\': darkMode === true}"';

        $customers = Customer::where(function ($query) {
            $query->where('name', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('email', 'like', '%' . $this->searchTerm . '%');
        })
            ->when($this->authUser->mode !== 'admin', function ($query) {
                $query->where('branch_id', $this->authUser->branch_id);
            })
            ->orderBy($this->sortColumn, $this->sortOrder)
            ->paginate(25);


        return view('livewire.customer.customer-manager', compact('customers'))->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }

    // Store or Update Customer
    public function saveCustomer()
    {
        $this->validate();

        if ($this->customerId) {
            // Update existing customer
            $customer = Customer::find($this->customerId);
            $customer->update([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
                'city' => $this->city,
                'country' => $this->country,
                'status' => $this->status,
            ]);
            session()->flash('message', 'Customer updated successfully!');
        } else {
            // Create new customer
            Customer::create([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
                'city' => $this->city,
                'country' => $this->country,
                'status' => $this->status,
            ]);
            session()->flash('message', 'Customer created successfully!');
        }

        // Clear form after saving
        $this->resetForm();
    }

    // Delete customer
    public function deleteCustomer(Customer $customer)
    {
        if ($customer) {
            $deleteResponse = $customer->delete();

            if ($deleteResponse) {
                session()->flash('success', 'Customer deleted successfully!');
            } else {
                session()->flash('error', 'Unable to delete customer. Please try again!');
            }
        } else {
            session()->flash('error', 'Customer not found. Please try again!');
        }

        // Reset pagination after deletion
        $this->resetPage();
    }


    // Set customer data for editing
    public function editCustomer($customerId)
    {
        $customer = Customer::find($customerId);
        if ($customer) {
            $this->customerId = $customer->id;
            $this->name = $customer->name;
            $this->email = $customer->email;
            $this->phone = $customer->phone;
            $this->address = $customer->address;
            $this->city = $customer->city;
            $this->country = $customer->country;
            $this->status = $customer->status;
        }
    }

    // Reset form fields
    private function resetForm()
    {
        $this->reset(['name', 'email', 'phone', 'address', 'city', 'country', 'status', 'customerId']);
    }

    public function sortBy($columnName)
    {
        if ($this->sortColumn === $columnName) {
            $this->sortOrder = $this->sortOrder === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortColumn = $columnName;
            $this->sortOrder = 'asc';
        }
    }
}
