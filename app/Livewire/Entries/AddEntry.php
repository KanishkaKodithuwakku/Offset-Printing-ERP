<?php

namespace App\Livewire\Entries;

use App\Models\Entry;
use App\Models\EntryItem;
use App\Models\EntryType;
use App\Models\Ledger;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Validation\ValidationException;
use SebastianBergmann\Type\TrueType;

class AddEntry extends Component
{
    public $items = [
        ['dc' => 'dr', 'ledger_id' => null, 'dr_amount' => null, 'cr_amount' => null],
        ['dc' => 'dr', 'ledger_id' => null, 'dr_amount' => null, 'cr_amount' => null],
        ['dc' => 'dr', 'ledger_id' => null, 'dr_amount' => null, 'cr_amount' => null],
        ['dc' => 'dr', 'ledger_id' => null, 'dr_amount' => null, 'cr_amount' => null],
    ];

    public $ledgers = [];
    public string $entryType;
    public Entrytype $entryTypeModel;
    public $narration;

    public function mount($type)
    {
        $this->entryType = strtolower($type);
        $this->entryTypeModel = EntryType::where('label', $type)->firstOrFail();
        $this->ledgers = Ledger::all(); // Load ledger list

        // Now  can access:
        // $this->entryTypeModel->id
        // $this->entryTypeModel->name
        // $this->entryTypeModel->restriction_bankcash
    }


    public function addRow()
    {
        $this->items[] = ['dc' => 'dr', 'ledger_id' => null, 'dr_amount' => null, 'cr_amount' => null];
        // dd($this->items);
    }


    public function submit()
    {


        // $this->validate([
        //     'items.*.ledger_id' => 'required|exists:ledgers,id',
        //     'items.*.dc' => 'required|in:dr,cr',
        //     'items.*.dr_amount' => 'nullable|required_if:items.*.dc,dr|numeric|min:0.01',
        //     'items.*.cr_amount' => 'nullable|required_if:items.*.dc,cr|numeric|min:0.01',
        // ]);



        // Compute totals
        if ($msg = $this->getRestrictionViolationMessage()) {

            dd($msg);

            // associate it with the whole table
            throw ValidationException::withMessages([
                'items' => [$msg],
            ]);
        }



        $drTotal = collect($this->items)
            ->where('dc', 'dr')
            ->sum(fn($i) => floatval($i['dr_amount']));
        $crTotal = collect($this->items)
            ->where('dc', 'cr')
            ->sum(fn($i) => floatval($i['cr_amount']));

        if (abs($drTotal - $crTotal) > 0.005) {
            throw ValidationException::withMessages([
                'items' => ['Total Debits (' . number_format($drTotal, 2) . ') must equal Total Credits (' . number_format($crTotal, 2) . ').'],
            ]);
        }

        DB::beginTransaction();

        try {
            $entry = new Entry();
            $entry->entrytype_id = $this->entryTypeModel->id;
            $entry->number = Entry::where('entrytype_id', $this->entryTypeModel->id)->max('number') + 1;
            $entry->narration = $this->narration ?? 'Auto-generated entry';
            $entry->date = now()->toDateString();
            $entry->dr_total = $drTotal;
            $entry->cr_total = $crTotal;
            $entry->branch_id = auth()->user()->branch_id ?? 1; // replace or default
            // $entry->created_by = auth()->id(); // if applicable
            $entry->save();

            // dd($this->items);

            foreach ($this->items as $item) {
                if ($item['ledger_id'] != null) {
                    $entryItem = new EntryItem();
                    $entryItem->entry_id = $entry->id;
                    $entryItem->ledger_id = $item['ledger_id'];
                    $entryItem->dc = strtoupper($item['dc']);
                    $entryItem->amount = $item['dc'] === 'dr' ? $item['dr_amount'] : $item['cr_amount'];
                    $entryItem->branch_id = auth()->user()->branch_id ?? 1;
                    $entryItem->save();
                }
            }

            DB::commit();

            session()->flash('success', 'Entry saved successfully.');
            return redirect()->route('entries.index')->with(['message' => 'Success']);
        } catch (\Exception $e) {

            DB::rollBack();
            dd($e->getMessage());
            report($e);
            session()->flash('error', 'Failed to save entry. Try again.');
        }
    }


    public function removeRow($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items); // reindex
    }

    public function getTotalDrProperty()
    {
        return collect($this->items)->sum('dr_amount');
    }

    public function getTotalCrProperty()
    {
        return collect($this->items)->sum('cr_amount');
    }


    private function getRestrictionViolationMessage(): ?string
    {
        $restriction = $this->entryTypeModel->restriction_bankcash;
        $bankCashLedgerIds = Ledger::whereIn('type', ['bank', 'cash'])->pluck('id')->toArray();

        $hasBankCashDr = collect($this->items)
            ->filter(fn($i) => $i['dc'] === 'D' && in_array($i['ledger_id'], $bankCashLedgerIds))
            ->isNotEmpty();

        $hasBankCashCr = collect($this->items)
            ->filter(fn($i) => $i['dc'] === 'C' && in_array($i['ledger_id'], $bankCashLedgerIds))
            ->isNotEmpty();

        return match ($restriction) {
            0 => null,
            1 => !$hasBankCashDr ? 'At least one Bank or Cash account must be present on the Debit side.' : null,
            2 => !$hasBankCashCr ? 'At least one Bank or Cash account must be present on the Credit side.' : null,
            3 => !($hasBankCashDr && $hasBankCashCr) ? 'Only Bank or Cash accounts must be used on both Debit and Credit sides.' : null,
            4 => ($hasBankCashDr || $hasBankCashCr) ? 'Bank or Cash accounts are not allowed on either side.' : null,
            default => null
        };
    }


    private function violatesBankCashRestriction()
    {
        $restriction = $this->entryTypeModel->restriction_bankcash;
        $bankCashLedgerIds = Ledger::whereIn('type', ['bank', 'cash'])->pluck('id')->toArray();

        $hasBankCashDr = collect($this->items)
            ->filter(fn($i) => $i['dc'] === 'D' && in_array($i['ledger_id'], $bankCashLedgerIds))
            ->isNotEmpty();

        $hasBankCashCr = collect($this->items)
            ->filter(fn($i) => $i['dc'] === 'C' && in_array($i['ledger_id'], $bankCashLedgerIds))
            ->isNotEmpty();

        switch ($restriction) {
            case 0:
                return false; // Unrestricted
            case 1:
                return !$hasBankCashDr;
            case 2:
                return !$hasBankCashCr;
            case 3:
                return !($hasBankCashDr && $hasBankCashCr);
            case 4:
                return $hasBankCashDr || $hasBankCashCr;
            default:
                return false;
        }
    }


    public function render()
    {
        $bodyAttributes = 'x-data="{ page: \'dispatchNote\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
        x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
        :class="{\'dark bg-gray-900\': darkMode === true}"';

        return view('livewire.entries.add-entry')->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
