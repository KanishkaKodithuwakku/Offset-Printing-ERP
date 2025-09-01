<?php
// Livewire Component: SelectLedgerDropdown.php

namespace App\Livewire\Components;

use Livewire\Component;
use App\Models\Group;
use App\Models\Ledger;

class SelectLedgerDropdown extends Component
{
    public $model;

    public $ledgers = [];
    public $name = 'ledger_id';
    public $label = 'Select Ledger';
    public $placeholder = 'Choose Ledger';

    public function mount($model = null)
    {
        $this->model = $model; // Important
        $this->ledgers = $this->buildLedgerOptions();
    }

    public function updatedModel($value)
    {
        $this->dispatch('ledgerSelected', ledgerId: $value);
    }

    private function buildLedgerOptions()
    {
        $options = [];
        $groups = Group::with(['ledgers' => function ($query) {
            $query->orderBy('name');
        }])->orderBy('name')->get();

        foreach ($groups as $group) {
            if ($group->ledgers->isEmpty()) continue;
            $optgroup = [];
            foreach ($group->ledgers as $ledger) {
                $optgroup[$ledger->id] = "[{$ledger->code}] {$ledger->name}";
            }
            $options[$group->name] = $optgroup;
        }
        return $options;
    }

    public function render()
    {
        return view('livewire.components.select-ledger-dropdown');
    }
}
