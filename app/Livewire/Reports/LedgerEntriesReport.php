<?php

namespace App\Livewire\Reports;

use App\Models\Ledger;
use App\Services\Reports\LedgerEntriesService;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class LedgerEntriesReport extends Component
{
    use WithPagination;

    public $ledger_id;
    public $start_date;
    public $end_date;
    public $ledgers = [];
    public $ledger;
    public $opening_balance;
    public $closing_balance;
    public $entries = [];
    public $submitted = false;


    public function mount()
    {
        $this->ledgers = Ledger::orderBy('name')->get();
    }


    #[On('ledgerSelected')]
    public function setLedger($ledgerId)
    {
        $this->ledger_id = $ledgerId;
        $this->ledger = Ledger::find($ledgerId);
    }

    public function submit()
    {
        $this->resetPage();

        if (!$this->ledger_id) {
            $this->addError('ledger_id', 'Please select a ledger.');
            return;
        }

        $service = new LedgerEntriesService();
        $data = $service->generate(
            $this->ledger_id,
            $this->start_date,
            $this->end_date
        );

        $this->ledger = $data['ledger'];
        $this->opening_balance = $data['opening_balance'];
        $this->closing_balance = $data['closing_balance'];
        $this->entries = $data['entries'];
        $this->submitted = true;
    }

    public function render()
    {
        $bodyAttributes = 'x-data="{ page: \'LedgerEntriesReport\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
        x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
        :class="{\'dark bg-gray-900\': darkMode === true}"';

        return view('livewire.reports.ledger-entries-report')->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
