<?php

namespace App\Livewire\Uom;

use App\Models\Uom;
use Livewire\Component;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;

class UomForm extends Component
{
    public $uomId;
    public $description;
    public $status = 'active';

    #[Validate('required|min:3', message: 'Name is required')]
    public $name;

    #[Validate('required|min:1', message: 'Abbreviation is required')]
    public $abbreviation;

    public function mount($uomId = null)
    {
        if ($uomId) {
            $uom = Uom::find($uomId);
            $this->uomId = $uom->id;
            $this->name = $uom->name;
            $this->abbreviation = $uom->abbreviation;
            $this->description = $uom->description;
            $this->status = $uom->status;
        }
    }

    public function saveUom()
    {
        $this->validate([
            'name' => [
                'required',
                'min:3',
                Rule::unique('uoms', 'name')->ignore($this->uomId), // <- unique with ignore for edit
            ],
            'abbreviation' => 'required|min:1',
        ]);

        if ($this->uomId) {
            $uom = Uom::find($this->uomId);
            $uom->update([
                'name' => $this->name,
                'abbreviation' => $this->abbreviation,
                'description' => $this->description,
                'status' => $this->status,
            ]);
            session()->flash('success', 'UOM updated successfully!');
        } else {
            Uom::create([
                'name' => $this->name,
                'abbreviation' => $this->abbreviation,
                'description' => $this->description,
                'status' => $this->status,
            ]);
            session()->flash('success', 'UOM created successfully!');
        }

        return $this->redirect('/uoms', navigate: true);
    }

    public function render()
    {
        $bodyAttributes = 'x-data="{ page: \'addUom\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
            x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                    $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
            :class="{\'dark bg-gray-900\': darkMode === true}"';

        return view('livewire.uom.uom-form')->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
