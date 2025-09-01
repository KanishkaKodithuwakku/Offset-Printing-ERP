<?php

namespace App\Livewire\Entries;

use App\Models\Entry;
use App\Models\EntryType;
use App\Models\Ledger;
use App\Models\EntryItem;
use App\Models\Tag;
use Livewire\Component;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class ViewEntry extends Component
{
    public string $type;
    public int $id;
    public Entry $entry;
    public EntryType $entryType;
    public $items = [];
    public $ledgers;
    public $tags;
    public $mode = 'view';
    public $narration;
    public $tag_id;
    public $date;
    public $number;

    public function mount($type, $id)
    {
        $this->entryType = EntryType::where('label', $type)->firstOrFail();
        $this->entry = Entry::with(['entryitems.ledger', 'tag', 'entrytype'])->findOrFail($id);

        $this->narration = $this->entry->narration;
        $this->tag_id = $this->entry->tag_id;
        $this->number = $this->entry->number;
        $this->date = Carbon::parse($this->entry->date);  // Parse to Carbon object
        $this->ledgers = Ledger::all();
        $this->tags = Tag::all();

        $this->items = $this->entry->entryitems->map(function ($item) {
            return [
                'dc' => $item->dc,
                'ledger_id' => $item->ledger_id,
                'dr_amount' => $item->dc === 'D' ? $item->amount : null,
                'cr_amount' => $item->dc === 'C' ? $item->amount : null,
            ];
        })->toArray();
    }

    public function getTotalDrProperty()
    {
        return collect($this->items)->sum('dr_amount');
    }

    public function getTotalCrProperty()
    {
        return collect($this->items)->sum('cr_amount');
    }

    public function addRow()
    {
        $this->items[] = ['dc' => 'D', 'ledger_id' => null, 'dr_amount' => null, 'cr_amount' => null];
    }

    public function removeRow($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function submit()
    {
        $this->validate([
            'number' => 'required|integer',
            'date' => 'required|date',
            'narration' => 'nullable|string|max:500',
            'items' => 'required|array|min:2',
        ]);

        $drTotal = $this->getTotalDrProperty();
        $crTotal = $this->getTotalCrProperty();

        if (bccomp($drTotal, $crTotal, 2) !== 0) {
            session()->flash('error', 'Debit and Credit totals do not match.');
            return;
        }

        DB::beginTransaction();

        try {
            $this->entry->update([
                'number' => $this->number,
                'date' => $this->date,
                'narration' => $this->narration,
                'tag_id' => $this->tag_id,
                'dr_total' => $drTotal,
                'cr_total' => $crTotal,
            ]);

            $this->entry->entryitems()->delete();

            foreach ($this->items as $item) {
                if (!$item['ledger_id']) continue;

                EntryItem::create([
                    'entry_id' => $this->entry->id,
                    'ledger_id' => $item['ledger_id'],
                    'dc' => $item['dc'],
                    'amount' => $item['dc'] === 'D' ? $item['dr_amount'] : $item['cr_amount'],
                ]);
            }

            DB::commit();
            session()->flash('success', 'Entry updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to update entry: ' . $e->getMessage());
        }
    }

    public function render()
    {
         $bodyAttributes = 'x-data="{ page: \'ViewEntry\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
        x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
        :class="{\'dark bg-gray-900\': darkMode === true}"';

        return view('livewire.entries.view-entry', [
            'ledgers' => $this->ledgers,
            'tags' => $this->tags,
        ])->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
