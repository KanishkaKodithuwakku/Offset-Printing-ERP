<?php

namespace App\Livewire\Supplier;

use App\Models\Supplier;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use Livewire\Attributes\Title;

class SupplierList extends Component
{
    use WithPagination, WithoutUrlPagination;

    #[Title('Supplier List')]

    public $searchTerm = null;
    public $sortColumn = 'id';
    public $sortOrder = 'desc';
    public $authUser = null;

    protected $listeners = ['suppliersUpdated' => 'refreshSuppliers'];


    public function mount()
    {
        $this->authUser = auth()->user();
    }
    public function refreshSuppliers()
    {
        Log::info('Livewire Event: Received suppliersUpdated, refreshing supplier list');
        $this->resetPage();
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

    /**
     * Computed Property: getSuppliersProperty
     * Description: Fetches paginated suppliers dynamically
     */
    public function getSuppliersProperty()
    {
        return Supplier::where('name', 'like', $this->searchTerm . '%')
            ->orWhere('email', 'like', $this->searchTerm . '%')
            ->orWhere('company_name', 'like', $this->searchTerm . '%') // Added company_name check
            ->when($this->authUser->mode !== 'admin', function ($query) {
                $query->where('branch_id', $this->authUser->branch_id); 
            })
            ->orderBy($this->sortColumn, $this->sortOrder)
            ->paginate(25);
    }

    public function render()
    {
        $bodyAttributes = 'x-data="{ page: \'supplierList\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
        x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
        :class="{\'dark bg-gray-900\': darkMode === true}"';

        // Fetch suppliers and paginate the results
        $suppliers = $this->suppliers;

        return view('livewire.supplier.supplier-list', [
            'suppliers' => $suppliers,  // Pass the paginated results directly
        ])->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }

    /**
     * Function: deleteSupplier
     * Description: Deletes a supplier and refreshes pagination
     */
    public function deleteSupplier(Supplier $supplier)
    {
        if ($supplier) {
            $deleteResponse = $supplier->delete();

            if ($deleteResponse) {
                session()->flash('success', 'Supplier deleted successfully!');
            } else {
                session()->flash('error', 'Unable to delete supplier. Please try again!');
            }
        } else {
            session()->flash('error', 'Supplier not found. Please try again!');
        }

        // Reset pagination after deletion
        $this->resetPage();
    }
}
