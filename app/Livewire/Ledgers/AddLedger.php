<?php

namespace App\Livewire\Ledgers;

use App\Models\Ledger;
use App\Models\Group;
use Livewire\Component;
use Illuminate\Support\Arr;
use Livewire\Attributes\On;




class AddLedger extends Component
{
    public $ledger = [
        'name' => '',
        'code' => '',
        'group_id' => '',
        'op_balance_dc' => 'D',
        'op_balance' => 0.00,
        'type' => 0,
        'reconciliation' => 0,
        'notes' => '',
    ];

    public $groups = [];
    public $name, $code, $group_id, $op_balance_dc = 'D', $op_balance = 0, $type = 0, $reconciliation = 0, $notes;

    public function mount()
    {
        $this->groups = Group::all();
    }


    #[On('ledgerSelected')]
public function setLedger($ledgerId)
{
    $this->ledger_id = $ledgerId;
    $this->ledger = Ledger::find($ledgerId);
}

    public function submit()
    {
        // $this->validate([
        //     'ledger.name' => 'required|string|max:255|unique:ledgers,name',
        //     'ledger.group_id' => 'required|exists:groups,id',
        //     'ledger.code' => 'nullable|string|max:255|unique:ledgers,code',
        //     'ledger.op_balance' => 'required|numeric|min:0',
        //     'ledger.op_balance_dc' => 'required|in:D,C',
        //     'ledger.type' => 'required|boolean',
        //     'ledger.reconciliation' => 'required|boolean',
        //     'ledger.notes' => 'nullable|string|max:500',
        // ]);

        Ledger::create([
            'name' => $this->name,
            'code' => $this->code,
            'group_id' => $this->group_id,
            'op_balance_dc' => $this->op_balance_dc,
            'op_balance' => $this->op_balance,
            'type' => $this->type,
            'reconciliation' => $this->reconciliation,
            'notes' => $this->notes,
        ]);

        session()->flash('success', 'Ledger added successfully.');
        return redirect()->route('accounts.chart');
    }

    public function render()
    {
        $bodyAttributes = 'x-data="{ page: \'dispatchNote\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
        x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
        :class="{\'dark bg-gray-900\': darkMode === true}"';
        return view('livewire.ledgers.add-ledger')->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
