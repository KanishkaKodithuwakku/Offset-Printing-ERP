<?php

namespace App\Livewire\Entries;

use App\Models\EntryType;
use Livewire\Component;

class EditEntryType extends Component
{
    public $entrytype;
    public $label;
    public $name;
    public $description;
    public $prefix;
    public $suffix;
    public $zero_padding;
    public $restriction_bankcash;
    public $mode = 'edit';

    public function mount($id)
    {
        $this->entrytype = EntryType::findOrFail($id);

        $this->label = $this->entrytype->label;
        $this->name = $this->entrytype->name;
        $this->description = $this->entrytype->description;
        $this->prefix = $this->entrytype->prefix;
        $this->suffix = $this->entrytype->suffix;
        $this->zero_padding = $this->entrytype->zero_padding;
        $this->restriction_bankcash = $this->entrytype->restriction_bankcash;
    }

    public function update()
    {
        $this->validate([
            'label' => 'required|string|max:50',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'prefix' => 'nullable|string|max:20',
            'suffix' => 'nullable|string|max:20',
            'zero_padding' => 'nullable|integer|min:0|max:10',
            'restriction_bankcash' => 'required|integer',
        ]);

        $this->entrytype->update([
            'label' => $this->label,
            'name' => $this->name,
            'description' => $this->description,
            'prefix' => $this->prefix,
            'suffix' => $this->suffix,
            'zero_padding' => $this->zero_padding,
            'restriction_bankcash' => $this->restriction_bankcash,
        ]);

        session()->flash('success', 'Entry Type updated successfully!');
        return redirect()->route('entrytypes.index');
    }

    public function render()
    {

        $bodyAttributes = 'x-data="{ page: \'dispatchNote\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
        x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
        :class="{\'dark bg-gray-900\': darkMode === true}"';

        return view('livewire.entries.edit-entry-type')->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
