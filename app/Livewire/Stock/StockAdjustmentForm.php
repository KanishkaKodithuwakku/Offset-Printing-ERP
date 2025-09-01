<?php

namespace App\Livewire\Stock;

use App\Models\AdjustmentItem;
use App\Models\Item;
use App\Models\Stock;
use App\Models\Adjustment;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class StockAdjustmentForm extends Component
{
    public $items;
    public $selectedItemId;
    public $adjustmentItems = [];
    public $reason;
    public $successMessage;
    public $errorMessage;
    public $searchTerm = '';
    public $searchResults = [];
    public $authUser;
    public $total_amount;

    public function mount()
    {
        // Get all items available for adjustment
        $this->items = Stock::all();
        $this->authUser = auth()->user();
    }

    public function updatedSearchTerm()
    {
        if (strlen($this->searchTerm) >= 1) {
            $items = Item::where(function ($query) {
                $query->where('item_name', 'like', "{$this->searchTerm}%")
                    ->orWhere('item_code', 'like', "{$this->searchTerm}%");
            })
                ->limit(5)
                ->get();

            $this->searchResults = $items->map(function ($item) {

                $stockBalance = Stock::where('branch_id', $this->authUser->branch_id)
                    ->where('items_id', $item->id)
                    ->sum('quantity') ?? 0;

                return [
                    'id' => $item->id,
                    'item_name' => $item->item_name,
                    'item_code' => $item->item_code,
                    'selling_price' => $item->sales_price,
                    'purchase_price' => $item->purchase_price,
                    'brands_id' => $item->brands_id,
                    'quantity' => $stockBalance > 0 ? 1 : 0,
                    'stock_balance' => $stockBalance,
                ];
            })->toArray();
        } else {
            $this->searchResults = [];
        }
    }

    public function addAdjustmentItem($itemId)
    {
        $this->selectedItemId = $itemId;
        $item = Item::find($this->selectedItemId);

        if ($item) {
            // Check if the item already exists in the adjustmentItems array
            $existingItemKey = null;
            foreach ($this->adjustmentItems as $index => $adjustmentItem) {
                if ($adjustmentItem['item_id'] == $item->id) {
                    $existingItemKey = $index;
                    break;
                }
            }

            $stockBalance = Stock::where('items_id', $item->id)->sum('quantity') ?? 0;

            // If item already exists, you may update quantity or ignore adding again
            if ($existingItemKey === null) {
                $this->adjustmentItems[] = [
                    'id' => $item->id,
                    'item_id' => $item->id,
                    'item_name' => $item->item_name,
                    'item_code' => $item->item_code,
                    'selling_price' => $item->sales_price,
                    'purchase_price' => $item->purchase_price,
                    'quantity' => $stockBalance > 0 ? $stockBalance : 1,
                    'total' => $stockBalance > 0 ? $item->sales_price : 0.00,
                    'remark' => '',
                ];
            }

            $this->calculateTotal();

            // Clear the search term and results
            $this->searchTerm = '';
            $this->searchResults = [];
        }

        Log::debug('Adjustment Items:', $this->adjustmentItems);
    }

    public function removeAdjustmentItem($itemId)
    {
        // Find the index of the item to remove in the $adjustmentItems array
        $index = null;
        foreach ($this->adjustmentItems as $key => $item) {
            if ($item['item_id'] == $itemId) {
                $index = $key;
                break;
            }
        }

        // If item found, remove it from the array
        if ($index !== null) {
            unset($this->adjustmentItems[$index]);
            // Re-index the array after removal
            $this->adjustmentItems = array_values($this->adjustmentItems);
            // Recalculate total after removal
            $this->calculateTotal();
        }
    }

    public function saveAdjustment()
    {

       // dd($this->adjustmentItems);
        // Validation can be added here as needed

        // Create the Adjustment record
        $adjustment = Adjustment::create([
            'reason' => $this->reason,
            'created_by' => auth()->id(),
        ]);

        // Save each adjustment item
        foreach ($this->adjustmentItems as $item) {
            $stockBalance = Stock::where('items_id', $item['item_id'])->sum('quantity') ?? 0;
            $diff = $item['quantity'] - $stockBalance;
            AdjustmentItem::create([
                'adjustment_id' => $adjustment->id,
                'item_id' => $item['item_id'],
                'pre_qty' => $stockBalance,  // before adjustment
                'quantity' => $diff,  // the adjustment quantity
                'remark' => $item['remark'] ?? '',
            ]);
        }

        session()->flash('success', 'Stock adjustment successfully saved.');
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->adjustmentItems = [];
        $this->reason = '';
        $this->searchTerm = '';
    }

    public function calculateTotal()
    {
        $this->total_amount = array_sum(array_column($this->adjustmentItems, 'total'));
    }

    public function render()
    {
        $bodyAttributes = 'x-data="{ page: \'StockAdjustment\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
            x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                    $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
            :class="{\'dark bg-gray-900\': darkMode === true}"';

        return view('livewire.stock.stock-adjustment-form')->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
