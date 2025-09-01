<?php

namespace App\Livewire\Ledgers;

use App\Models\Ledger;
use App\Models\Group;
use Livewire\Component;

class EditLedger extends Component
{
    public $ledgerId;
    public $name;
    public $code;
    public $group_id;
    public $op_balance;
    public $op_balance_dc = 'D';
    public $type = 1; // default checked
    public $reconciliation = 1; // default checked
    public $notes;

    public $groups;

    public function mount($id)
    {
        $this->ledgerId = $id;
        $ledger = Ledger::findOrFail($id);  

        $this->name = $ledger->name;
        $this->code = $ledger->code;
        $this->group_id = $ledger->group_id;
        $this->op_balance = $ledger->op_balance;
        $this->op_balance_dc = $ledger->op_balance_dc;
        $this->type = (bool) $ledger->type;
        $this->reconciliation = (bool) $ledger->reconciliation;
        $this->notes = $ledger->notes;

        $this->groups = Group::orderBy('name')->get();
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:255|unique:ledgers,code,' . $this->ledgerId,
            'group_id' => 'required|exists:groups,id',
            'op_balance' => 'required|numeric|min:0',
            'op_balance_dc' => 'required|in:D,C',
            'type' => 'required|boolean',
            'reconciliation' => 'required|boolean',
            'notes' => 'nullable|string|max:500'
        ]);

        $ledger = Ledger::findOrFail($this->ledgerId);
        $ledger->update([
            'name' => $this->name,
            'code' => $this->code,
            'group_id' => $this->group_id,
            'op_balance' => $this->op_balance,
            'op_balance_dc' => $this->op_balance_dc,
            'type' => $this->type ? 1 : 0,
            'reconciliation' => $this->reconciliation ? 1 : 0,
            'notes' => $this->notes,
        ]);

        session()->flash('success', 'Ledger updated successfully.');
        return redirect()->route('accounts.chart');
    }

    public function render()
    {
        $bodyAttributes = 'x-data="{ page: \'dispatchNote\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
        x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
        :class="{\'dark bg-gray-900\': darkMode === true}"';
        return view('livewire.ledgers.edit-ledger')->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
