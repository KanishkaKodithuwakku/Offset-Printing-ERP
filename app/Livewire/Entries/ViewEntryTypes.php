<?php

// Livewire Component: app/Livewire/Entries/ViewEntryTypes.php

namespace App\Livewire\Entries;

use App\Models\EntryType;
use Livewire\Component;

class ViewEntryTypes extends Component
{
    public function delete($id)
    {
        EntryType::findOrFail($id)->delete();
        session()->flash('success', 'Entry Type deleted successfully.');
    }

    public function render()
    {
         $bodyAttributes = 'x-data="{ page: \'ViewEntryTypes\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
        x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
        :class="{\'dark bg-gray-900\': darkMode === true}"';

        $entrytypes = EntryType::orderBy('id')->get();

        return view('livewire.entries.view-entry-types', [
            'entrytypes' => $entrytypes,
        ])->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}

