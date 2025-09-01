<?php

namespace App\Livewire\Customer;

use App\Models\Customer;
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
        }
    }

    public function saveCustomer()
    {
        //$this->validate();

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
            ]);

            session()->flash('success', 'Customer has been updated successfully!');
        } else {
            $this->validate();

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
