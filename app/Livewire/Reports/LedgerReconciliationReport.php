<?php

namespace App\Livewire\Reports;

use App\Models\Ledger;
use App\Models\EntryItem;
use App\Models\Entry;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Helpers\AccountingHelper;
use Livewire\Attributes\On;

class LedgerReconciliationReport extends Component
{
    use WithPagination;


    public $ledger_id;
    public $start_date;
    public $end_date;
    public $ledgers = [];
    public $ledger;
    public $entries = [];
    public $opening;
    public $closing;
    public $reconcile = [];
    public $dr_pending;
    public $cr_pending;
    public $startDate = '2025-04-01';
    public $endDate = '2026-03-31';

    public $submitted = false;


    #[On('ledgerSelected')]
public function setLedger($ledgerId)
{
    $this->ledger_id = $ledgerId;
    $this->ledger = Ledger::find($ledgerId);
}


    public function mount()
    {
        $this->startDate = now()->startOfYear()->month < 4
            ? now()->subYear()->setMonth(4)->setDay(1)->format('Y-m-d')
            : now()->setMonth(4)->setDay(1)->format('Y-m-d');

        $this->endDate = now()->startOfYear()->month < 4
            ? now()->setMonth(3)->setDay(31)->format('Y-m-d')
            : now()->addYear()->setMonth(3)->setDay(31)->format('Y-m-d');

        $this->ledgers = Ledger::where('reconciliation', 1)->orderBy('name')->get();
        // $this->ledgers = Ledger::orderBy('name')->get();

        // if ($this->ledger_id) {
        //     $this->ledger = Ledger::findOrFail($this->ledger_id);
        // }
    }


    public function updatedLedgerId($value)
    {
        $this->ledger = Ledger::find($value);
    }


    public function updatePendingTotals()
    {
        $pending = AccountingHelper::reconciliationPending($this->ledger_id, $this->start_date, $this->end_date);
        $this->dr_pending = $pending['dr_total'];
        $this->cr_pending = $pending['cr_total'];
    }


    public function submit()
    {
        $this->resetPage();

        $this->validate([
            'ledger_id' => 'required|exists:ledgers,id',
        ]);

        $this->ledger = Ledger::findOrFail($this->ledger_id);
        $this->opening = AccountingHelper::openingBalance($this->ledger_id, $this->start_date);
        $this->closing = AccountingHelper::closingBalance($this->ledger_id, null, $this->end_date);

        $query = EntryItem::select('entryitems.*', 'entries.*')
            ->join('entries', 'entries.id', '=', 'entryitems.entry_id')
            ->where('entryitems.ledger_id', $this->ledger_id)
            ->whereNull('entryitems.reconciliation_date');

        if ($this->start_date) {
            $query->where('entries.date', '>=', $this->start_date);
        }

        if ($this->end_date) {
            $query->where('entries.date', '<=', $this->end_date);
        }

        $this->entries = $query->orderBy('entries.date')->get();
        $this->submitted = true;
        $this->updatePendingTotals();
    }

    public function reconcile()
    {
        foreach ($this->reconcile as $itemId => $date) {
            if ($date) {
                EntryItem::where('id', $itemId)->update([
                    'reconciliation_date' => Carbon::parse($date)->format('Y-m-d'),
                ]);
            }
        }

        session()->flash('success', 'Selected entries reconciled.');
        $this->submit();
        $this->updatePendingTotals();
    }

    public function render()
    {

        $bodyAttributes = 'x-data="{ page: \'ReconciliationReport\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
        x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
        :class="{\'dark bg-gray-900\': darkMode === true}"';

        return view('livewire.reports.ledger-reconciliation-report')
            ->layout('layouts.app')->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
