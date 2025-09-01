<?php

namespace App\Livewire\Stock;

use App\Models\Branch;
use App\Models\DispatchItem;
use App\Models\Item;
use App\Models\JobOrder;
use App\Models\JobOrderItem;
use App\Models\Stock;
use App\Models\StockTransferItem;
use App\Models\StockTransferNote;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Helpers\NumberGenerator;

class StockTransferForm extends Component
{
    public $dispatchedCount = 0, $status, $customer_po_number, $jobOrderId, $job_number, $date_created, $customer_id, $branch_id, $description, $special_instruction, $printout, $plate_backing = false, $backing_qty = 1;
    public $authUser;
    public ?int $from_branch_id = null;
    public ?int $to_branch_id = null;
    public ?int $job_order_id = null;
    public int $quantity = 1;
    public $total_amount;
    public string $remark = '';
    public $searchTerm = '';
    public $dispatchItems = [];
    public $searchResults = [];
    public $searchResultsJobs;
    public $job_order_number;
    public $searchJob;
    public function mount()
    {
        $this->authUser = auth()->user();
        // $this->transfer_number = $this->generateTransferNumber();

    }

    public function updatedJobOrderId($job_order_id)
    {

        $this->dispatchItems = DispatchItem::with('item')
            ->where('order_id', $job_order_id)
            ->get()
            ->toArray();

        // dd($this->dispatchItems);
    }

    public function updatedSearchJob()
    {
        $jobs = JobOrder::with('customer')
            ->where('job_number', 'like', "%{$this->searchJob}%")
            ->limit(5)
            ->get();

        $this->searchResultsJobs = $jobs->map(function ($job) {
            return [
                'id' => $job->id,
                'job_number' => $job->job_number,
                'customer_name' => $job->customer?->name ?? 'N/A',
                'description' => $job->description,
                'total_amount' => $job->total_amount,
                'status' => $job->status
            ];
        })->toArray();
    }

    public function assignJobOrder($jobOrderId)
    {
        $jobOrder = JobOrder::find($jobOrderId);
        if (!$jobOrder) {
            return response()->json(['error' => 'Job Order not found'], 404);
        }

        $this->job_number = (string) $jobOrder->job_number ?? '';
        $this->job_order_number = (string) ($jobOrder->job_number ?? '');
        $this->job_order_id = $jobOrder->id;
        $this->searchJob = $jobOrder->job_number;
        $this->searchResultsJobs = [];
        return response()->json(['message' => 'Job Order assigned successfully'], 200);
    }

    public function save()
    {
        $this->validate([
            'from_branch_id' => 'required|different:to_branch_id|exists:branches,id',
            'to_branch_id' => 'required|exists:branches,id',
            'quantity' => 'required|integer|min:1',
            'remark' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        $transferCode = NumberGenerator::generateStockTransferCode(
            'stock_transfer_notes',
            'ST',                 // your fixed prefix
            $this->from_branch_id, // branch code/ID
            'transfer_code'       // the column in your table
        );


        try {
            // Create the transfer note
            $transferNote = StockTransferNote::create([
                'transfer_code' => $transferCode,
                'from_branch_id' => $this->from_branch_id,
                'to_branch_id' => $this->to_branch_id,
                'job_order_id' => $this->job_order_id,
                'user_id' => $this->authUser->id,
                'quantity' => $this->quantity,
                'remark' => $this->remark,
                'status' => 'pending',
            ]);

            // Insert each jobOrderItem into stock_transfer_items
            foreach ($this->jobOrderItems as $item) {
                StockTransferItem::create([
                    'stock_transfer_id' => $transferNote->id,
                    'item_id' => $item['item_id'],
                    'order_id' => $this->job_order_id,
                    'quantity' => $item['quantity'],
                    'price' => $item['selling_price'],
                    'total' => $item['total'],
                    'status' => 'pending',
                ]);
            }


             $this->processTransferNote($transferNote->id, false);

            DB::commit();
            session()->flash('success', 'Stock transfer note and items created.');
            $this->reset();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error saving transfer: ' . $e->getMessage());
        }
    }



    public function processTransferNote($transferId, $complete)
    {
        // Fetch the transfer note and its related items
        $transferNote = StockTransferNote::with('items')->findOrFail($transferId);

        if ($transferNote->status === 'complete') {
            session()->flash('info', 'This transfer is already marked as complete.');
            return;
        }

        DB::beginTransaction();
        try {
            // Loop through each item in the transfer note and increase stock in TO branch
            foreach ($transferNote->items as $tn_item) {
                $item = Item::find($tn_item->item_id);
                if (!$item)
                    continue;

                // Increase stock in TO branch for each item
                Stock::create([
                    'items_id' => $tn_item->item_id,
                    'brands_id' => $tn_item->brand_id ?? $item->brands_id ?? null,
                    'branch_id' => $transferNote->from_branch_id,
                    'user_id' => $this->authUser->id,
                    'quantity' => -abs($tn_item->quantity),
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
            }

            //$transferNote->status = 'complete';
            //$transferNote->save();

            DB::commit();
            session()->flash('success', 'Stock Transfer marked as complete and items increased in TO branch.');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error completing stock transfer: ' . $e->getMessage());
        }
    }



    public function updatedSearchTerm()
    {
        if (strlen($this->searchTerm) >= 1) {
            $items = Item::where('item_name', 'like', "%{$this->searchTerm}%")
                ->orWhere('item_code', 'like', "%{$this->searchTerm}%")
                ->limit(5)
                ->get();

            $this->searchResults = $items->map(function ($item) {

                $stockBalance = Stock::where('items_id', $item->id)->sum('quantity') ?? 0;
                if ($stockBalance == 0) {
                    $this->stockProcess = false;
                }
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
    public $selectedItemId;
    public $jobOrderItems = [];

    public function addOrderItem($itemId)
    {
        $this->selectedItemId = $itemId;
        $item = Item::find($this->selectedItemId);

        if ($item) {
            // Check if the item already exists in the jobOrderItems array
            $existingItemKey = null;
            foreach ($this->jobOrderItems as $index => $jobOrderItem) {
                if ($jobOrderItem['item_id'] == $item->id) {
                    $existingItemKey = $index;
                    break;
                }
            }
            $stockBalance = Stock::where('items_id', $item->id)->sum('quantity') ?? 0;
            if ($stockBalance == 0) {
                $this->stockProcess = false;
            }

            if ($existingItemKey !== null) {
                if ($stockBalance < $this->jobOrderItems[$existingItemKey]['quantity'] + 1) {
                    session()->flash('error', 'Quantity cannot exceed available stock since the available stock balance is  (' . $stockBalance . ')');
                } else {
                    $this->jobOrderItems[$existingItemKey]['quantity'] += 1; // Increment quantity by 1
                    // Update the total for the item based on the updated quantity
                    $this->jobOrderItems[$existingItemKey]['total'] = $this->jobOrderItems[$existingItemKey]['quantity'] * $this->jobOrderItems[$existingItemKey]['selling_price'];
                }

                // If the item exists, update the quantity

            } else {
                // If the item does not exist, add it to the array
                if ($stockBalance == 0) {
                    session()->flash('error', 'Quantity cannot exceed available stock since the available stock balance is (0)');
                }
                $this->jobOrderItems[] = [
                    'id' => $item->id,
                    'item_id' => $item->id,
                    'name' => $item->item_name,
                    'code' => $item->item_code,
                    'selling_price' => $item->sales_price,
                    'purchase_price' => $item->purchase_price,
                    'quantity' => $stockBalance > 0 ? 1 : 0,
                    'total' => $stockBalance > 0 ? $item->sales_price : 0.00,
                ];
            }

            // Call calculateTotal or any other necessary function
            $this->calculateTotal();

            // Clear the search term and results
            $this->searchTerm = '';
            $this->searchResults = [];
        }

        // // Check if jobOrderItems is updated
        // Log::debug('Job Order Items:', $this->jobOrderItems);
    }

    public function removeItem($index, $id)
    {
        $jobOrderItem = JobOrderItem::find($id);

        if (!$jobOrderItem) {
            // session()->flash('error', 'Item not found.');
            // return;

            $this->removeFromJobOrderItems($index);
        } else {
            // Check if the item is already dispatched
            $count = DispatchItem::where('job_order_item_id', $jobOrderItem->id)->count();
            if ($count > 0) {
                session()->flash('error', 'Cannot delete the item since it is already dispatched!');
                return;
            }
            // Proceed to delete
            $jobOrderItem->delete();
            $this->removeFromJobOrderItems($index);
        }
    }
    public function removeFromJobOrderItems($index)
    {

        if ($index !== false) {
            // Remove the item at that index from the array
            unset($this->jobOrderItems[$index]);
        }

        // Reindex the array to ensure proper indexing
        $this->jobOrderItems = array_values($this->jobOrderItems);
    }

    public function updateTotal($index)
    {
        // Get the item from the job order items array
        $item = $this->jobOrderItems[$index];

        // Get the stock balance for the specific item
        $stockBalance = Stock::where('items_id', $item['item_id'])->sum('quantity') ?? 0;

        $this->dispatchedCount = DB::table('dispatch_items')
            ->where('order_id', $this->jobOrderId)
            ->where('item_id', $item['item_id'])
            ->sum('quantity');

        // Check if the quantity entered is greater than the stock balance
        if ($item['quantity'] > $stockBalance) {
            // Display a message or prevent the update
            session()->flash('error', 'Quantity cannot exceed available stock since the available stock balance is  (' . $stockBalance . ')');

            // Optionally, reset the quantity to the stock balance to prevent overselling
            $this->jobOrderItems[$index]['quantity'] = $stockBalance;

            // Return to prevent further processing
            // return;
        }

        if ($item['quantity'] < $this->dispatchedCount) {
            // Display a message or prevent the update
            session()->flash('error', 'Quantity cannot exceed already dispatched count');

            // Optionally, reset the quantity to the stock balance to prevent overselling
            $this->jobOrderItems[$index]['quantity'] = $this->dispatchedCount;

            // Return to prevent further processing
            // return;
        }

        // Update the total for the specific item based on the new quantity
        $this->jobOrderItems[$index]['total'] = $this->jobOrderItems[$index]['quantity'] * $this->jobOrderItems[$index]['selling_price'];

        // Optionally, recalculate the overall total amount
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->total_amount = array_sum(array_column($this->jobOrderItems, 'total'));
    }

    public function completeTransferNote($transfer_note_id)
    {
        try {
            $transferNote = StockTransferNote::find($transfer_note_id);
            $transferItems = StockTransferItem::where('stock_transfer_id', $transfer_note_id)->get();
            foreach ($transferItems as $transferItem) {
                $product = Item::find($transferItem->item_id);
                // if ($stockBalance > 0 && $dispatchItem->quantity <= $stockBalance) {
                $stock = DB::table('stocks')
                    ->select('*', DB::raw('sum(quantity) as qty'))
                    ->where('items_id', $transferItem->item_id)
                    ->where('branch_id', $transferNote->from_branch_id)
                    ->groupBy('sku_code')
                    ->orderBy('created_at', 'asc')
                    ->get();


                //from
                Stock::create([
                    'items_id' => $stock->items_id,
                    'brands_id' => $stock->brands_id,
                    'branch_id' => $transferNote->from_branch_id,
                    'user_id' => $this->authUser->id,
                    'quantity' => -abs(intval($remaining_quantity)),
                    'purchase_price' => $transferItem->item->purchase_price,
                    'sales_price' => $transferItem->item->sales_price,
                    'mrp' => $transferItem->item->mrp,
                    'p_id' => $transferItem->dispatch_id,
                    'f_id' => $transferItem->id,
                    'table_name' => 'dispatch',
                    'effective_date' => now(),
                    'sku_code' => $stock->sku_code,
                    'online' => 1,
                ]);

                //to
                Stock::create([
                    'items_id' => $stock_entry->items_id,
                    'brands_id' => $stock_entry->brands_id,
                    'branch_id' => $transferNote->from_branch_id,
                    'user_id' => $this->authUser->id,
                    'quantity' => intval($remaining_quantity),
                    'purchase_price' => $dispatchItem->item->purchase_price,
                    'sales_price' => $dispatchItem->item->sales_price,
                    'mrp' => $dispatchItem->item->mrp,
                    'p_id' => $dispatchItem->dispatch_id,
                    'f_id' => $dispatchItem->id,
                    'table_name' => 'dispatch',
                    'effective_date' => now(),
                    'sku_code' => $stock_entry->sku_code,
                    'online' => 1,
                ]);
                $remaining_quantity = 0;
            }
        } catch (\Exception $e) {
            // Flash error message to session
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        $bodyAttributes = 'x-data="{ page: \'stockTn\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
        x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
        :class="{\'dark bg-gray-900\': darkMode === true}"';

        return view('livewire.stock.stock-transfer-form', [
            'branches' => Branch::all(),
            'jobs' => JobOrder::where('status', 'ready-to-invoice')->get(),
        ])->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
