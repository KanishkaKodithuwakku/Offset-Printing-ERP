<?php

namespace App\Livewire\Reports;

use App\Services\Reports\LedgerStatementService;
use App\Models\Ledger;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Date;
use Livewire\Attributes\On;

class LedgerStatementReport extends Component
{
    use WithPagination;

    // #[On('ledgerSelected')]
    public $ledger_id;
    public $start_date;
    public $end_date;
    public $ledgers = [];
    public $ledger;
    public $opening_balance;
    public $closing_balance;
    public $transactions = [];
    public $submitted = false;

    public function mount($ledger_id = null): void
    {

         $this->ledgers = Ledger::orderBy('name')->get();

        if ($ledger_id) {
            $this->ledger = Ledger::find( $ledger_id);
            $this->ledger_id = $ledger_id;
            $this->submit();
        }

        $this->start_date = now()->startOfYear()->month < 4
            ? now()->subYear()->setMonth(4)->setDay(1)->format('Y-m-d')
            : now()->setMonth(4)->setDay(1)->format('Y-m-d');

        $this->end_date = now()->startOfYear()->month < 4
            ? now()->setMonth(3)->setDay(31)->format('Y-m-d')
            : now()->addYear()->setMonth(3)->setDay(31)->format('Y-m-d');
        $this->ledgers = Ledger::orderBy('name')->get();
    }

    #[On('ledgerSelected')]
    public function setLedger($ledgerId)
    {
        $this->ledger_id = $ledgerId;
        $this->ledger = Ledger::find($ledgerId);
    }

    public function updatedLedgerId($value)
    {
        $this->ledger = Ledger::find($value);
    }



    public function submit()
    {
        $this->resetPage();

        if (!$this->ledger_id) {
            $this->addError('ledger_id', 'Please select a ledger.');
            return;
        }

        $service = new LedgerStatementService();
        $data = $service->generate(
            $this->ledger_id,
            $this->start_date,
            $this->end_date,
            request()->get('page', 1)
        );

        $this->ledger = $data['ledger'];
        $this->opening_balance = $data['opening'];
        $this->closing_balance = $data['closing'];
        $this->transactions = $data['entries'];
        $this->submitted = true;
    }

    public function render()
    {

        $bodyAttributes = 'x-data="{ page: \'LedgerStatement\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
        x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
        :class="{\'dark bg-gray-900\': darkMode === true}"';


        return view('livewire.reports.ledger-statement-report')->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
