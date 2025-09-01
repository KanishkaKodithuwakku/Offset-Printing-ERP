<?php

namespace App\Livewire\Entries;

use App\Models\Entry;
use App\Models\EntryItem;
use App\Models\Ledger;
use App\Models\Tag;
use Livewire\Component;

class EditEntry extends Component
{
    public $entry;
    public $entryId;
    public $type;
    public $date;
    public $number;
    public $narration;
    public $tag_id;
    public $items = [];
    public $ledgers = [];
    public $tags = [];

    public function mount($type, $id)
    {
        $this->entry = Entry::with(['entryitems', 'entrytype', 'tag'])->findOrFail($id);
        $this->entryId = $id;
        $this->type = $type;
        $this->number = $this->entry->number;
        $this->date = $this->entry->date;
        $this->narration = $this->entry->narration;
        $this->tag_id = $this->entry->tag_id;

        $this->ledgers = Ledger::all();
        $this->tags = Tag::all();

        $this->items = $this->entry->entryitems->map(function ($item) {
            return [
                'dc' => $item->dc === 'D' ? 'dr' : 'cr',
                'ledger_id' => $item->ledger_id,
                'dr_amount' => $item->dc === 'D' ? $item->amount : null,
                'cr_amount' => $item->dc === 'C' ? $item->amount : null,
            ];
        })->toArray();
    }

    public function addRow()
    {
        $this->items[] = [
            'dc' => 'D',
            'ledger_id' => '',
            'dr_amount' => null,
            'cr_amount' => null,
        ];
    }

    public function removeRow($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function getTotalDrProperty()
    {
        return collect($this->items)->filter(fn($i) => $i['dc'] === 'D')->sum('dr_amount');
    }

    public function getTotalCrProperty()
    {
        return collect($this->items)->filter(fn($i) => $i['dc'] === 'C')->sum('cr_amount');
    }

    public function submit()
    {
        if ($this->totalDr != $this->totalCr) {
            session()->flash('error', 'Debit and Credit totals must be equal.');
            return;
        }

        $this->entry->update([
            'date' => $this->date,
            'narration' => $this->narration,
            'tag_id' => $this->tag_id,
            'dr_total' => $this->totalDr,
            'cr_total' => $this->totalCr,
        ]);

        $this->entry->entryitems()->delete();

        foreach ($this->items as $item) {
            if (!empty($item['ledger_id'])) {
                $amount = $item['dc'] === 'D' ? $item['dr_amount'] : $item['cr_amount'];

                EntryItem::create([
                    'entry_id' => $this->entry->id,
                    'ledger_id' => $item['ledger_id'],
                    'dc' => $item['dc'],
                    'amount' => $amount,
                ]);
            }
        }

        session()->flash('success', 'Entry updated successfully!');
        return redirect()->route('entries.index');
    }

    public function deleteEntry()
    {
        $this->entry->entryitems()->delete();
        $this->entry->delete();
        session()->flash('success', 'Entry deleted successfully!');
        return redirect()->route('entries.index');
    }


    public function render()
    {
        $bodyAttributes = 'x-data="{ page: \'dispatchNote\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
        x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
        :class="{\'dark bg-gray-900\': darkMode === true}"';

        return view('livewire.entries.edit-entry')->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
