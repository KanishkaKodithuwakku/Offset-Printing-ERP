<?php

namespace App\Livewire\Grn;

use App\Models\Grn;
use Livewire\Component;
use App\Models\GrnItem;
use App\Models\Item;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GrnItems extends Component
{
    public $grnId;
    public $searchTerm = '';
    public $searchResults = [];
    public $grnItems = [];
    public $grn;
    public $grn_code;
    public $supplier;
    public $total_amount;
    public $status;
    public $updatedQuantity = [];
    protected $listeners = ['statusUpdated' => 'refreshPage'];
    public $authUser;


    public function mount($grnId)
    {
        $this->authUser = auth()->user();
        $this->grnId = $grnId;
        $this->loadGrnItems();
        foreach ($this->searchResults as $item) {
            $this->updatedQuantity[$item['id']] = 1;  // Set default value to 1
        }
    }

    public function refreshPage()
    {
        $this->render();
    }

    public function loadGrnItems()
    {
        $this->grn = Grn::with(['grnItems', 'supplier'])
            ->where('id', $this->grnId)
            ->first();

        if ($this->grn) {
            $this->grn_code = $this->grn->grn_code;
            $this->supplier = $this->grn->supplier;
            $this->total_amount = $this->grn->total_amount;
            $this->status = $this->grn->status;

            // Load order items
            $this->grnItems = $this->grn->grnItems->map(function ($item) {
                return [
                    'item_id' => $item->item_id,
                    'quantity' => $item->quantity,
                    'item_name' => $item->item ? $item->item->item_name : 'No Item Found',
                    'sku_code' => $item->item ? $item->sku_code : '',
                    'total' => $item->total,
                    'selling_price' => $item->selling_price,
                    'purchase_price' => $item->purchase_price,
                    'brands_id' => $item->item->brands_id,
                    'mrp' => $item->mrp,
                ];
            })->toArray();

        }
        $this->calculateTotal();
    }

    public function updatedSearchTerm()
    {
        if (strlen($this->searchTerm) > 1) {
            $items = Item::where('item_name', 'like', "%{$this->searchTerm}%")
                ->orWhere('item_code', 'like', "%{$this->searchTerm}%")
                ->limit(5)
                ->get();

            $this->searchResults = $items->map(function ($item) {

                // Check if the item already exists in the $grnItems array
                $sameItem = false;
                foreach ($this->grnItems as $grnItem) {
                    if ($grnItem['item_id'] === $item->id) {
                        $sameItem = true;
                        break;
                    }
                }
                return [
                    'id' => $item->id,
                    'item_name' => $item->item_name,
                    'item_code' => $item->item_code,
                    'sku_code' => $item->sku_code,
                    'selling_price' => $item->sales_price,
                    'purchase_price' => $item->purchase_price,
                    'brands_id' => $item->brands_id,
                    'quantity' => 1,
                    'same_item' => $sameItem
                ];
            })->toArray();
        } else {
            $this->searchResults = [];
        }
    }

    public function updateItem($itemId)
    {
        $itemIndex = collect($this->grnItems)->search(fn($grnItem) => $grnItem['item_id'] == $itemId);
        if ($itemIndex !== false) {
            $this->grnItems[$itemIndex]['quantity']++;
            $this->grnItems[$itemIndex]['total'] = $this->grnItems[$itemIndex]['quantity'] * $this->grnItems[$itemIndex]['purchase_price'];
        }
        $this->calculateTotal();
    }



    public function updateItemQty($itemId)
    {
        // Get the updated quantity from the model binding
        $updatedQty = $this->updatedQuantity[$itemId];

        // Find the corresponding item in grnItems
        $itemIndex = collect($this->grnItems)->search(fn($grnItem) => $grnItem['item_id'] == $itemId);

        if ($itemIndex !== false) {
            // Update the quantity and total price of the item
            $this->grnItems[$itemIndex]['quantity'] = $updatedQty;
            $this->grnItems[$itemIndex]['total'] = $updatedQty * $this->grnItems[$itemIndex]['purchase_price'];
        }

        // Recalculate the total amount
        $this->calculateTotal();
    }


    public function addItem($itemId)
    {
        $item = Item::find($itemId);

        if (!$item) return;
        $existingItemIndex = collect($this->grnItems)->search(fn($grnItem) => $grnItem['item_id'] == $item->id);
        // Get the last SKU code generated for this brand_id, and extract the last number
        $lastSku = GrnItem::where('brands_id', $item->brands_id)
            ->orderBy('id', 'desc')
            ->first();

        // Generate the next sequential number (default to 000001 if no previous SKU)
        $nextNumber = $lastSku ? intval(substr($lastSku->sku_code, -6)) + 1 : 1;

        // Pad the sequential number with leading zeros (e.g., 000001, 000002, etc.)
        $formattedNumber = str_pad($nextNumber, 6, '0', STR_PAD_LEFT);

        // Generate unique SKU code
        $uniqueSkuCode = 'SKU-' . $item->brands_id . '-' . $formattedNumber;

        $existingSkuCodeIndex = collect($this->grnItems)->search(fn($grnItem) => $grnItem['sku_code'] == $uniqueSkuCode);

        // If SKU code exists, increment the formatted number until it's unique
        while ($existingSkuCodeIndex !== false) {
            // Increment the number
            $nextNumber++;
            $formattedNumber = str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
            $uniqueSkuCode = 'SKU-' . $item->brands_id . '-' . $formattedNumber;

            // Check if the new SKU code exists
            $existingSkuCodeIndex = collect($this->grnItems)->search(fn($grnItem) => $grnItem['sku_code'] == $uniqueSkuCode);
        }


        if ($existingItemIndex === false) {
            // Add the item to the GRN
            $this->grnItems[] = [
                'item_id' => $item->id,
                'item_name' => $item->item_name,
                'sku_code' => $uniqueSkuCode,
                'selling_price' => $item->sales_price,
                'purchase_price' => $item->purchase_price,
                'quantity' => 1,
                'total' => $item->purchase_price,
                'brands_id' => $item->brands_id,
                'mrp' => $item->mrp,
            ];
        } else {
            // If item exists, just update the quantity
            $this->grnItems[$existingItemIndex]['quantity']++;
            $this->grnItems[$existingItemIndex]['total'] = $this->grnItems[$existingItemIndex]['quantity'] * $item->purchase_price;
        }

        $this->searchTerm = '';
        $this->searchResults = [];
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->total_amount = array_sum(array_column($this->grnItems, 'total'));
    }
    public function updateTotal($index)
    {
        if (isset($this->grnItems[$index])) {
            $this->grnItems[$index]['total'] =
                $this->grnItems[$index]['quantity'] * $this->grnItems[$index]['purchase_price'];
        }
        $this->calculateTotal();
    }

    public function removeItem($index)
    {
        unset($this->grnItems[$index]);
        $this->grnItems = array_values($this->grnItems);
    }

    public function saveGrnItems()
    {
        $currentGrn = Grn::find($this->grnId);

        // Delete existing GRN items before adding new ones
        $currentGrn->grnItems()->delete();

        foreach ($this->grnItems as $item) {
            // Ensure selling_price and other values are not null before proceeding
            if ($item['selling_price'] === null || $item['selling_price'] < 0) {
                session()->flash('error', 'Selling price cannot be null or negative for item: ' . $item['sku_code']);
                return;
            }

            // Check if all required fields are present for each item
            GrnItem::updateOrCreate(
                ['grn_id' => $this->grnId, 'item_id' => $item['item_id']],
                [
                    'selling_price' => $item['selling_price'],
                    'purchase_price' => $item['purchase_price'],
                    'sku_code' => $item['sku_code'],
                    'quantity' => $item['quantity'],
                    'total' => $item['total'],
                    'brands_id' => $item['brands_id'],
                ]
            );
        }

        // Calculate the total amount and update the GRN record
        $total = GrnItem::where('grn_id', $this->grnId)->sum('total') ?? 0;
        $currentGrn->total_amount = $total;
        $currentGrn->save();

        // Flash a success message
        session()->flash('success', 'GRN Items saved successfully!');
    }





    public function processGrnItems()
    {
        $currentGrn = Grn::with(['grnItems.item', 'supplier'])
            ->where('id', $this->grnId)
            ->first();

        if (!$currentGrn) {
            session()->flash('error', 'GRN not found.');
            return;
        }

        if ($currentGrn->status !== 'pending') {
            session()->flash('error', 'This GRN cannot be processed because it is not in "pending" status.');
            return;
        }

        DB::beginTransaction();

        try {
            foreach ($currentGrn->grnItems as $grnItem) {
                $quantity = $grnItem->quantity;
                if ($currentGrn->grn_type === 'return') {
                    $quantity = -1 * $quantity;
                }

                $item = Item::find($grnItem->item_id);
                $status = 'pending';
                if ($item) {
                    $status = 'completed';
                    // $dimensions = json_decode($item->dimensions, true);
                    $length = $item->length;
                    $width = $item->width;
                    $height = $item->height;

                    // If stock does not exist, create a new record
                    Stock::create([
                        'brands_id' => $grnItem->brands_id,
                        'supplier_id' => $grnItem->supplier_id,
                        'branch_id' => $currentGrn->branch_id,
                        'user_id' => $this->authUser->id,
                        'items_id' => $grnItem->item_id,
                        'quantity' =>  $grnItem->quantity,
                        'sales_price' => $grnItem->selling_price,
                        'purchase_price' => $grnItem->purchase_price,
                        'mrp' => $grnItem->price,
                        'p_id' => $currentGrn->id,
                        'f_id' => $grnItem->id,
                        'table_name' => 'grn',
                        'purchase_date' => $grnItem->eff_date,
                        'weight' => $grnItem->eff_date,
                        'dimensions' => json_encode([
                            'length' =>  $length,
                            'width' => $width,
                            'height' => $height
                        ]),
                        'sku_code' => $grnItem->sku_code,
                        'effective_date' => $currentGrn->eff_date,
                        'online' => 1
                    ]);
                }
            }

            // Update GRN status to "completed"
            $currentGrn->update([
                'status' => $status,
            ]);
            $this->status = $status;

            DB::commit();
            $this->dispatch('statusUpdated');
            session()->flash('success', 'Stock updated successfully!');
            return $this->redirect('/grns');
        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', 'Error updating stock: ' . $e->getMessage());
        }
    }


    public function render()
    {
        $bodyAttributes = 'x-data="{ page: \'grnItems\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
        x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
        :class="{\'dark bg-gray-900\': darkMode === true}"';

        return view('livewire.grns.grn-items')->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
