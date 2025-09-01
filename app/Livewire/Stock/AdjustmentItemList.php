<?php

namespace App\Livewire\Stock;

use App\Models\Adjustment;
use App\Models\AdjustmentItem;
use App\Models\Item;
use App\Models\Stock;
use Livewire\Component;

class AdjustmentItemList extends Component
{
    public $adjustmentId;
    public $adjustment;
    public $authUser;


     public function mount($adjustmentId = null)
    {
         $this->authUser = auth()->user();

        if ($adjustmentId) {
            $this->loadAdjustment($adjustmentId);
        }
    }
    public function loadAdjustment($adjustmentId)
    {
        $this->adjustmentId = $adjustmentId;
        $this->adjustment = Adjustment::with('adjustmentItems.item')->find($adjustmentId);
    }


    public function approveAdjustment()
    {
        // dd($this->authUser);
        if ($this->adjustment) {
            // Loop through each adjustment item
            foreach ($this->adjustment->adjustmentItems as $item) {
                // Calculate the difference between the current quantity and the adjusted quantity
                $difference = $item->quantity - $item->pre_qty;

                $plateItem = Item::find($item->item_id);

                $adjItem = AdjustmentItem::find($item->id);

                dd($this->authUser->id);

                // Find the item in the stock and adjust the quantity
                Stock::create([
                        'items_id' => $item->item_id,
                        'brands_id' => $plateItem->brand_id,
                        'user_id' => $this->authUser->id,
                        'branch_id' => $this->authUser->branch_id,
                        'quantity' => $difference,
                        'purchase_price' => $plateItem->purchase_price ?? 0,
                        'sales_price' => $plateItem ->sales_price ?? 0,
                        'mrp' => $plateItem ->mrp ?? 0,
                        'p_id' => $this->adjustment->id,
                        'f_id' => $item->id,
                        'table_name' => 'adjustment',
                        'effective_date' => now(),
                        'sku_code' => null,
                        'online' => 1,
                    ]);


            }

            // Update the adjustment status to 'approved'
            $this->adjustment->status = 'approved';
            $this->adjustment->save();



            // Session message to indicate success
            session()->flash('message', 'Adjustment approved and stock updated successfully!');
        }
    }


    public function render()
    {
        $bodyAttributes = 'x-data="{ page: \'AdjustmentItemList\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
            x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                    $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
            :class="{\'dark bg-gray-900\': darkMode === true}"';

        return view('livewire.stock.adjustment-item-list')->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
