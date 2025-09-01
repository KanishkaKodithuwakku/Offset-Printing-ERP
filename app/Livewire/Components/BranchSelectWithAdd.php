<?php

namespace App\Livewire\Components;

use App\Models\BankBranch;
use Livewire\Component;

class BranchSelectWithAdd extends Component
{
    public $branches = [];
    public $selectedBranch = null;
    public $newBranchName = '';
    public $showAddForm = false;
    public $showDropdown = false;

    public function mount($selectedBranch = null)
    {
        $this->selectedBranch = $selectedBranch;
        $this->loadBranchs();
    }
    
    public function selectBranch($id)
    {
        $this->selectedBranch = $id;
        $this->showDropdown = false;
        $this->dispatch('selectedBranch', id: $id);
    }

    public function loadBranchs()
    {
        $this->branches = BankBranch::orderBy('name')->get();
    }

    public function addBranch()
    {
        $this->validate([
            'newBranchName' => 'required|string|unique:bank_branches,name',
        ]);

        $branch = BankBranch::create(['name' => $this->newBranchName]);
        $this->newBranchName = '';
        $this->showAddForm = false;
        $this->loadBranchs();
        $this->selectedBranch = $branch->id;
    }

    public function render()
    {
        return view('livewire.components.branch-select-with-add');
    }
}
