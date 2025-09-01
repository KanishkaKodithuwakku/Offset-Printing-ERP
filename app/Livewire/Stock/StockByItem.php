<?php

namespace App\Livewire\Stock;

use Livewire\Component;
use App\Models\Item;
use App\Models\Stock;

class StockByItem extends Component
{
    public $isModalOpen = false;
    public $itemName = '';

    public function render()
{
    return view('livewire.stock.stock-by-item')
        ->layout('layouts.app'); // <- tells Livewire to use your existing layout
}

// In your Livewire component

public $itemFilter;

public function applyFilters()
{
    $this->filteredStock = Stock::whereHas('item', function ($query) {
        $query->where('name', 'like', '%' . $this->itemFilter . '%');
    })->get();
}


}
