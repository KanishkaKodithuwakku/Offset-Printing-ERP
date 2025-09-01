<?php
namespace App\Livewire\Entries;

use App\Models\Entry;
use App\Models\EntryItem;
use App\Models\Ledger;
use App\Models\EntryType;
use Livewire\Component;

class PrintPreview extends Component
{
    public $entry;
    public $entryitems = [];
    public $entrytype;
    public $entryId;

    public function mount($id)
    {
        $this->entry = Entry::with('entryitems')->findOrFail($id);
        $this->entryId = $id;

        $this->entrytype = Entrytype::findOrFail($this->entry->entrytype_id);

        foreach ($this->entry->entryitems as $item) {
            $this->entryitems[] = [
                'dc' => $item->dc,
                'ledger_id' => $item->ledger_id,
                'ledger_name' => Ledger::find($item->ledger_id)->name ?? 'N/A',
                'dr_amount' => $item->dc === 'D' ? number_format($item->amount, 2) : '',
                'cr_amount' => $item->dc === 'C' ? number_format($item->amount, 2) : '',
            ];
        }
    }

    public function render()
    {

        $bodyAttributes = 'x-data="{ page: \'dispatchNote\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
        x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
        :class="{\'dark bg-gray-900\': darkMode === true}"';

        return view('livewire.entries.print-preview')->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
