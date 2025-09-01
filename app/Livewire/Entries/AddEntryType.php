<?php

namespace App\Livewire\Entries;

use App\Models\EntryType;
use Livewire\Component;

class AddEntryType extends Component
{

    public $label, $name, $description, $numbering = 1, $prefix, $suffix, $zero_padding = 0, $restriction_bankcash = 0;
    public $mode = 'add';
    public function submit()
    {
        $this->validate([
            'label' => 'required|string',
            'name' => 'required|string',
            'description' => 'nullable|string',
            'numbering' => 'required|integer',
            'prefix' => 'nullable|string',
            'suffix' => 'nullable|string',
            'zero_padding' => 'nullable|integer',
            'restriction_bankcash' => 'required|integer',
        ]);

        EntryType::create([
            'label' => $this->label,
            'name' => $this->name,
            'description' => $this->description,
            'numbering' => $this->numbering,
            'prefix' => $this->prefix,
            'suffix' => $this->suffix,
            'zero_padding' => $this->zero_padding,
            'restriction_bankcash' => $this->restriction_bankcash,
        ]);

        session()->flash('success', 'Entry type added.');
        return redirect()->route('entrytypes.index');
    }


    public function render()
    {
        $bodyAttributes = 'x-data="{ page: \'AddEntryType\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
        x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
        :class="{\'dark bg-gray-900\': darkMode === true}"';

        return view('livewire.entries.add-entry-type')->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
