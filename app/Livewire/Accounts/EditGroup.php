<?php
namespace App\Livewire\Accounts;

use App\Models\Group;
use Livewire\Component;

class EditGroup extends Component
{
    public $groupId;
    public $name;
    public $code;
    public $parent_id;

    public function mount($id)
    {
        $group = Group::findOrFail($id);
        $this->groupId = $group->id;
        $this->name = $group->name;
        $this->code = $group->code;
        $this->parent_id = $group->parent_id;
    }

    public function updateGroup()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:groups,id',
        ]);

        $group = Group::findOrFail($this->groupId);
        $group->update([
            'name' => $this->name,
            'code' => $this->code,
            'parent_id' => $this->parent_id,
        ]);

        session()->flash('success', 'Group updated successfully.');
        return redirect()->route('accounts.chart');
    }

    public function render()
    {
         $bodyAttributes = 'x-data="{ page: \'dispatchNote\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
        x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
        :class="{\'dark bg-gray-900\': darkMode === true}"';
        $parentGroups = Group::whereNull('parent_id')->orWhere('id', '!=', $this->groupId)->get();
        return view('livewire.accounts.edit-group', compact('parentGroups'))->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
