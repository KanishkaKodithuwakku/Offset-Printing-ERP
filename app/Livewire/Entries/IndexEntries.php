<?php

namespace App\Livewire\Entries;

use App\Models\Entry;
use App\Models\EntryType;
use App\Models\Tag;
use Livewire\Component;
use Livewire\WithPagination;

class IndexEntries extends Component
{
    use WithPagination;

    public $perPage = 25;
    public $entrytype_id = '';
    public $tag_id = '';
    public string $selectedType = '';

    public function updating($field)
    {
        $this->resetPage();
    }

    public function updatedSelectedType($value)
    {
        if ($value) {
            return $this->redirectRoute('entries.add', ['type' => $value], navigate: true);
        }
    }

    public function render()
    {
        $bodyAttributes = 'x-data="{ page: \'Entries\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
        x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
        :class="{\'dark bg-gray-900\': darkMode === true}"';

        $entries = Entry::with(['entryitems.ledger', 'entryType', 'tag'])
            ->when($this->entrytype_id, fn($q) => $q->where('entrytype_id', $this->entrytype_id))
            ->when($this->tag_id, fn($q) => $q->where('tag_id', $this->tag_id))
            ->orderByDesc('date')
            ->orderBy('number')
            ->paginate($this->perPage)
            ->onEachSide(-1);

        return view('livewire.entries.index-entries', [
            'entries' => $entries,
            'entrytypes' => EntryType::all(),
            'tags' => Tag::all(),
        ])->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
