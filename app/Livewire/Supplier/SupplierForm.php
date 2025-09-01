<?php

namespace App\Livewire\Supplier;

use App\Models\Supplier;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Title('Create Supplier')]
class SupplierForm extends Component
{
    #[Validate('required|string|max:255', message: 'Name is required')]
    public $name;

    #[Validate('required|string|max:255', message: 'Company is required')]
    public $company_name;

    #[Validate('required|email|unique:suppliers,email', message: 'Invalid or duplicate email')]
    public $email;

    #[Validate('required|string|max:15', message: 'Phone must not exceed 15 characters')]
    public $phone;

    #[Validate('nullable|string', message: 'Address is optional')]
    public $address;

    #[Validate('required|in:active,inactive', message: 'Invalid status')]
    public $status = 'active';

    public function saveSupplier()
    {
        $this->validate();

        Supplier::create([
            'name'    => $this->name,
            'company_name'    => $this->company_name,
            'email'   => $this->email,
            'phone'   => $this->phone,
            'address' => $this->address,
            'status'  => $this->status,
        ]);

        session()->flash('message', 'Supplier has been created successfully!');


        return $this->redirect('/suppliers', navigate: true);
    }

    public function render()
    {
        $bodyAttributes = 'x-data="{ page: \'addSupplier\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
            x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                    $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
            :class="{\'dark bg-gray-900\': darkMode === true}"';

        return view('livewire.supplier.supplier-form')->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
