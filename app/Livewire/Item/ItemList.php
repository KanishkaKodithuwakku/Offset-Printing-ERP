<?php

namespace App\Livewire\Item;

use App\Models\Item;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use Livewire\Attributes\Title;

class ItemList extends Component
{
    use WithPagination, WithoutUrlPagination;

    #[Title('Livewire 3 CRUD - Items Listing')]

    public $searchTerm = null;
    public $sortColumn = 'id';
    public $sortOrder = 'desc';

    protected $listeners = ['itemsUpdated' => 'refreshItems','importStarted' => 'showLoading', 'importFinished' => 'hideLoading'];
    public $isLoading = false;



    public function showLoading()
    {
        $this->isLoading = true;
    }

    public function hideLoading()
    {
        $this->isLoading = false;
    }

    public function refreshItems()
    {
        Log::info('Livewire Event: Received itemsUpdated, refreshing items list');
        $this->resetPage();
    }

    public function sortBy($columnName)
    {
        if ($this->sortColumn === $columnName) {
            $this->sortOrder = $this->sortOrder === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortColumn = $columnName;
            $this->sortOrder = 'asc';
        }
    }

    /**
     * Computed Property: getItemsProperty
     * Description: Fetches paginated items dynamically
     */
    public function getItemsProperty()
    {
        return Item::where('item_name', 'like', '%' . $this->searchTerm . '%')
            ->orWhere('item_code', 'like', '%' . $this->searchTerm . '%')
            ->orderBy($this->sortColumn, $this->sortOrder)
            ->paginate(10);

    }

    public function render()
    {
        $bodyAttributes = 'x-data="{ page: \'itemList\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
            x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                    $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
            :class="{\'dark bg-gray-900\': darkMode === true}"';

        return view('livewire.item.item-list', [
            'items' => Item::where('item_name', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('item_code', 'like', '%' . $this->searchTerm . '%')
                ->orderBy($this->sortColumn, $this->sortOrder)
                ->paginate(25),

        ])->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }

    /**
     * Function: deleteItem
     * Description: Deletes an item and refreshes pagination
     */
    public function deleteItem(Item $item)
    {
        if ($item) {
            $deleteResponse = $item->delete();

            if ($deleteResponse) {
                session()->flash('success', 'Item deleted successfully!');
            } else {
                session()->flash('error', 'Unable to delete Item. Please try again!');
            }
        } else {
            session()->flash('error', 'Item not found. Please try again!');
        }

        // Reset pagination after deletion
        $this->resetPage();
    }




}
