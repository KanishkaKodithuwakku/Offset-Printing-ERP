<?php

namespace App\Livewire\Stock;

use App\Models\Item;
use App\Models\Stock;
use Livewire\Component;
use App\Models\StockTransferNote;
use App\Models\StockTransferItem;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class StockTransferList extends Component
{
    use WithPagination;
    public $statusFilter = '';
    public $selectedTransferId = null;
    public $items = [];
    public $authUser;
    public $status;
    public $showPartialTransferModal = false;
    public $partialQuantity;
    public $modalItemId;
    public $modalTransferId;
    public $modalItemMaxQuantity;
    public $modalItemName;
    public $isAddToStock = false;

    public function mount()
    {
        $this->authUser = auth()->user();
    }

    public function showItems($transferId)
    {
        if ($this->selectedTransferId === $transferId) {
            $this->selectedTransferId = null;
            $this->items = [];
        } else {
            $this->selectedTransferId = $transferId;
            $this->items = StockTransferItem::with(['item', 'order', 'orderItem'])
                ->where('stock_transfer_id', $transferId)
                ->get();
            // Ensure transferred_quantity is set (for legacy data)
            foreach ($this->items as $item) {
                if (!isset($item->transferred_quantity)) {
                    $item->transferred_quantity = 0;
                }
            }
        }
    }

    public function openPartialTransferModal($itemId, $transferId)
    {
        $this->modalItemId = $itemId;
        $this->modalTransferId = $transferId;
        $item = StockTransferItem::with('item')->findOrFail($itemId);
        $this->modalItemMaxQuantity = $item->quantity - ($item->transferred_quantity ?? 0); // Set max to balance
        $this->modalItemName = $item->item->item_name ?? '';
        $this->partialQuantity = $this->modalItemMaxQuantity; // Set default to balance
        $this->isAddToStock = false;
        $this->showPartialTransferModal = true;
    }

    public function closePartialTransferModal()
    {
        $this->showPartialTransferModal = false;
        $this->partialQuantity = null;
        $this->modalItemId = null;
        $this->modalTransferId = null;
        $this->modalItemMaxQuantity = null;
        $this->modalItemName = null;
        $this->isAddToStock = false;
    }

    public function submitPartialTransfer($isAddToStock = false)
    {
        $this->validate([
            'partialQuantity' => 'required|integer|min:1|max:' . $this->modalItemMaxQuantity,
        ]);

        $item = StockTransferItem::findOrFail($this->modalItemId);
        $balance = $item->quantity - $item->transferred_quantity;
        if ($this->partialQuantity > $balance) {
            $this->addError('partialQuantity', 'Partial quantity cannot exceed available balance.');
            return;
        }

        // Update transferred_quantity
        $item->transferred_quantity += $this->partialQuantity;
        $item->save();

        $transferNote = StockTransferNote::findOrFail($item->stock_transfer_id);

        if ($isAddToStock) {
            // Directly add stock to TO branch using StockTransferItem data, no need to check stock balance
            Stock::create([
                'items_id' => $item->item_id,
                'brands_id' => $item->brand_id ?? null,
                'branch_id' => $transferNote->from_branch_id,
                'user_id' => $this->authUser->id,
                'quantity' => abs($this->partialQuantity),
                'purchase_price' => $item->purchase_price ?? 0,
                'sales_price' => $item->price ?? 0,
                'mrp' => $item->mrp ?? 0,
                'p_id' => $transferNote->id,
                'f_id' => $item->id,
                'table_name' => 'stock_transfer',
                'effective_date' => now(),
                'sku_code' => $item->sku_code ?? null,
                'online' => 1,
            ]);
        } else {
            // Track partial quantity not yet added to stock
            $item->partially_added_quantity = ($item->partially_added_quantity ?? 0) + $this->partialQuantity;
            $item->save();
        }

        // Update the item in the items array to reflect the new transferred quantity
        foreach ($this->items as $key => $itm) {
            if ($itm->id == $item->id) {
                $this->items[$key]->transferred_quantity = $item->transferred_quantity;
                break;
            }
        }

        session()->flash('success', 'Partial transfer of ' . $this->partialQuantity . ' for ' . $this->modalItemName . ' completed!');

        $this->closePartialTransferModal();
    }


    public function markAsComplete($transferId,$complete)
    {
         
        $transferNote = StockTransferNote::with('items')->findOrFail($transferId);

        if ($transferNote->status === 'complete') {
            session()->flash('info', 'This transfer is already marked as complete.');
            return;
        }

        try {
            foreach ($transferNote->items as $tn_item) {
                $item = Item::find($tn_item->item_id);
                if (!$item)
                    continue;

                $pendingQty = $tn_item->partially_added_quantity ?? 0;
                if ($pendingQty > 0) {
                    // Decrement from from_branch_id
                    Stock::create([
                        'items_id' => $tn_item->item_id,
                        'brands_id' => $tn_item->brand_id ?? $item->brands_id ?? null,
                        'branch_id' => $transferNote->from_branch_id,
                        'user_id' => $this->authUser->id,
                        'quantity' => abs($pendingQty),
                        'purchase_price' => $item->purchase_price ?? 0,
                        'sales_price' => $item->sales_price ?? 0,
                        'mrp' => $item->mrp ?? 0,
                        'p_id' => $transferNote->id,
                        'f_id' => $tn_item->id,
                        'table_name' => 'stock_transfer',
                        'effective_date' => now(),
                        'sku_code' => null,
                        'online' => 1,
                    ]);
                  
                    // Reset partially_added_quantity
                    $tn_item->partially_added_quantity = 0;
                    $tn_item->save();
                }
            }
            if($complete){
                $transferNote->status = 'complete';
                $transferNote->accepted_by = $this->authUser->id;
            }
            $transferNote->save();

            session()->flash('success', 'Stock Transfer marked as complete.');
            // Refresh items in UI
            if ($this->selectedTransferId) {
                $this->showItems($this->selectedTransferId);
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error completing stock transfer: ' . $e->getMessage());
        }
    }

        public function updatingStatusFilter()
        {
            $this->resetPage();
        }


    public function render()
    {

        $transferQuery = StockTransferItem::query();


            if ($this->statusFilter !== 'complete') {
                $transferQuery->where('status', $this->statusFilter);
            } else {
                $transferQuery->whereIn('status', ['complete', 'rejected', 'pending']);
            }

        $transferQuery->orderBy('created_at', 'desc');

        $stockTransfers = $transferQuery->paginate(25);
        // $stockTransfers = $transferQuery->with('item')->paginate(2);


        $bodyAttributes = 'x-data="{ page: \'stockTnList\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
            x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                    $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
            :class="{\'dark bg-gray-900\': darkMode === true}"';

        $transfers = StockTransferNote::with(['fromBranch', 'toBranch', 'job', 'user'])->latest()->paginate(25);

        return view('livewire.stock.stock-transfer-list', [
            'transfers' => $transfers,
            'items' => $this->items,
        ])->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
