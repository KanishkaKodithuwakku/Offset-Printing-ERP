<?php

namespace App\Livewire\Uom;

use App\Models\Uom;
use Livewire\Component;
use Livewire\WithPagination;

class UomList extends Component
{
    use WithPagination;

    public function render()
    {
        $bodyAttributes = 'x-data="{ page: \'uomList\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
            x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                    $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
            :class="{\'dark bg-gray-900\': darkMode === true}"';

        // ✅ Only show non-deleted UOMs
        $uoms = Uom::latest()->paginate(25);

        return view('livewire.uom.uom-list', compact('uoms'))->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }

    public function deleteUom(Uom $uom)
    {
        if ($uom) {
            $uom->delete(); // This will now soft delete
            session()->flash('success', 'UOM deleted successfully!');
        } else {
            session()->flash('error', 'UOM not found. Please try again!');
        }

        return $this->redirect('/uoms', navigate: true);
    }
}
