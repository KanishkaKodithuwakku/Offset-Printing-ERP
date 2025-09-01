<?php

namespace App\Livewire\Supplier;

use Livewire\Component;
use App\Models\Supplier;
use Livewire\Attributes\Title;

#[Title('Edit Supplier')]
class SupplierEdit extends Component
{
    public Supplier $supplier;
    public $name, $email, $phone, $address, $status,$company_name;

    public function mount(Supplier $supplier)
    {
        $this->supplier = $supplier;
        $this->name = $supplier->name;
        $this->company_name = $supplier->company_name;
        $this->email = $supplier->email;
        $this->phone = $supplier->phone;
        $this->address = $supplier->address;
        $this->status = $supplier->status;
    }

    public function saveSupplier()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $this->supplier->update([
            'name' => $this->name,
            'company_name' => $this->company_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'status' => $this->status,
        ]);

        session()->flash('success', 'Supplier updated successfully!');
        return $this->redirect('/suppliers', navigate: true);
    }

    public function render()
    {
        $bodyAttributes = 'x-data="{ page: \'editSupplier\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
        x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
        :class="{\'dark bg-gray-900\': darkMode === true}"';

        return view('livewire.supplier.supplier-form')->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
