<?php
namespace App\Livewire\Components;

use Livewire\Component;
use App\Models\Ledger;

class SelectBankLedgerDropdown extends Component
{
    public $model;
    public $name = 'bank_ledger_id';
    public $label = 'Select Bank Ledger';
    public $placeholder = 'Choose Bank';
    public $groupId = 17; // This should be the ID for your 'Bank' group
    public $showLabel = true;

    public $ledgers = [];

    public function mount($model = null, $showLabel = true)
    {
        $this->model = $model;
        $this->showLabel = $showLabel;
        $this->ledgers = $this->loadBankLedgers();
    }

    public function updatedModel($value)
    {
        $this->dispatch('bankLedgerSelected', ledgerId: $value);
    }

    public function loadBankLedgers()
    {
        return Ledger::where('group_id', $this->groupId)
                     ->orderBy('name')
                     ->pluck('name', 'id')
                     ->mapWithKeys(fn($name, $id) => [$id => "[{$id}] $name"])
                     ->toArray();
    }

    public function render()
    {
        return view('livewire.components.select-bank-ledger-dropdown');
    }
}
