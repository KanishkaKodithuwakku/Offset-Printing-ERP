<?php

namespace App\Livewire\Components;

use Livewire\Component;
use App\Models\Bank;

class BankSelectWithAdd extends Component
{
    public $banks = [];
    public $selectedBank = null;
    public $newBankName = '';
    public $showAddForm = false;
    public $showDropdown = false;

    public function mount($selectedBank = null)
    {
        $this->selectedBank = $selectedBank;
        $this->loadBanks();
    }
    
    public function selectBank($id)
    {
        $this->selectedBank = $id;
        $this->showDropdown = false;
        $this->dispatch('selectedBank', id: $id);
    }

    public function loadBanks()
    {
        $this->banks = Bank::orderBy('name')->get();
    }

    public function addBank()
    {
        $this->validate([
            'newBankName' => 'required|string|unique:banks,name',
        ]);

        $bank = Bank::create(['name' => $this->newBankName]);
        $this->newBankName = '';
        $this->showAddForm = false;
        $this->loadBanks();
        $this->selectedBank = $bank->id;
    }

    public function render()
    {
        return view('livewire.components.bank-select-with-add');
    }
}
