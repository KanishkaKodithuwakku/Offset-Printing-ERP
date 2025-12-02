<?php

namespace App\Livewire\Dispatch;

use App\Helpers\NumberGenerator;
use App\Models\DispatchItem as ModelsDispatchItem;
use App\Models\DispatchNote;
use App\Models\Item;
use App\Models\JobOrder;
use App\Models\JobOrderItem;
use App\Models\Stock;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DispatchItem extends Component
{
    public $authUser;
    public $item_id;
    public $quantity;
    public $total_amount;
    public $status;
    public $dispatch_id;
    public $job_order_item_id;
    public $branch_assigned = null;
    public $branch_done = null;
    public $dispatchNote;
    public $jobOrder;
    public $jobOrderId;
    public $customer_id, $payment_method, $customer_name;
    public $dispatchItems = [];
    public $dispatchStatus;
    public $hasDispatchedItems = false;
    public $hasPendingQtyUpdateRequest = false;
    public $isFullyDispatched = false;
    public $showRequestQtyModal = false;
    public $showRequestSuccessModal = false;

    protected $rules = [
        'item_id' => 'required|exists:items,id',
        'quantity' => 'required|integer|min:1',
        'total_amount' => 'required|numeric',
        'status' => 'required|string',
    ];

    // Mount method to initialize the component with data
    public function mount($orderId = null)
    {
        // dd($orderId);
        $this->authUser = auth()->user();
        if ($orderId) {
            // Check if there are dispatched items (must be > 0)
            $dispatchedCount = DB::table('dispatch_items')
                ->where('order_id', $orderId)
                ->sum('quantity');
            $this->hasDispatchedItems = ((int)$dispatchedCount) > 0;
        
        // Check if there's a pending quantity update request
        $this->hasPendingQtyUpdateRequest = DB::table('job_orders')
            ->where('id', $orderId)
            ->where('qty_update_requested', true)
            ->exists();
        
            // $this->dispatch_id = $dispatchId;
            $this->loadOrderDetails($orderId);
        }
    }

    public function loadOrderDetails($orderId)
    {
        $this->dispatchNotes = DispatchNote::with('dispatchItems')
            ->where('job_order_id', $orderId)
            ->get();

        $this->jobOrder = JobOrder::with('orderItems')->find($orderId);
        if ($this->jobOrder) {
            $this->jobOrderId = $this->jobOrder->id;
            $this->customer_id = $this->jobOrder->customer_id;
            $this->payment_method = $this->jobOrder->payment_method;
            $this->total_amount = $this->jobOrder->total_amount;
            $this->status = $this->jobOrder->status;
            $this->customer_name = $this->jobOrder->customer->name;
            $this->dispatchStatus = $this->jobOrder->status;

            // Check if there are dispatched items
            $dispatchedCount = DB::table('dispatch_items')
                ->where('order_id', $this->jobOrderId)
                ->sum('quantity');
            $this->hasDispatchedItems = $dispatchedCount > 0;

            // Check if all items are fully dispatched
            $this->isFullyDispatched = $this->checkIfFullyDispatched($this->jobOrderId);

            // Check if there's a pending quantity update request
            $this->hasPendingQtyUpdateRequest = DB::table('job_orders')
                ->where('id', $this->jobOrderId)
                ->where('qty_update_requested', true)
                ->exists();

            //Load dispatch items with remaining balance
            $this->dispatchItems = [];

            foreach ($this->jobOrder->orderItems as $orderItem) {
                $dispatchedQty = DB::table('dispatch_items')
                    ->where('job_order_item_id', $orderItem->id)
                    ->sum('quantity');

                $dispatchBalance = $orderItem->quantity - $dispatchedQty;

                $item = Item::find($orderItem->item_id);

                if ($dispatchBalance > 0) {
                    $this->dispatchItems[] = [
                        'item_id' => $orderItem->item_id,
                        'item_name' => $item?->item_name ?? 'Unknown',
                        'item_code' => $item?->item_code ?? null,
                        'job_order_item_id' => $orderItem->id,
                        'dispatch_id' => null,
                        'order_id' => $orderItem->order_id,
                        'quantity' => $dispatchBalance,
                        'dispatchBalance' => $dispatchBalance,
                        'price' => $orderItem->price,
                        'purchase_price' => $item?->purchase_price ?? 0,
                        'sales_price' => $item?->sales_price ?? 0,
                        'total_amount' => $orderItem->price * $dispatchBalance,
                        'item_name' => $orderItem->item->item_name ?? 'Unknown',
                        'status' => $orderItem->status,
                    ];
                }
            }
        }
    }


    public function validateDispatchQuantity($index)
    {
        $orderItem = $this->dispatchItems[$index];

        // Check if the quantity is within the allowed limit
        $maxQuantity = $orderItem['dispatchBalance'] == 0 ? $orderItem['quantity'] : $orderItem['dispatchBalance'];

        // Validate the quantity
        if ($orderItem['quantity'] > $maxQuantity) {
            $this->dispatchItems[$index]['quantity'] = $maxQuantity; // Set the quantity to max if it exceeds
            session()->flash('error', 'Quantity cannot exceed the available dispatch balance.');
        }
    }


    public function _updateDispatchItems($orderId)
    {
        DB::beginTransaction();

        try {
            $this->dispatchItems = array_values(array_filter($this->dispatchItems, fn($item) => $item['quantity'] != 0));

            if (empty($this->dispatchItems)) {
                throw new \Exception('Can not save! Dispatch items count is 0.');
            }

            $jobOrder = JobOrder::find($orderId);

            //Validate quantities for all items first
            foreach ($this->dispatchItems as $item) {
                $jobOrderItem = JobOrderItem::find($item['job_order_item_id']);


                if (!$jobOrderItem) {
                    continue; // or handle invalid item
                }

                $orderedQty = $jobOrderItem->quantity;
                $alreadyDispatchedQty = ModelsDispatchItem::where('job_order_item_id', $item['job_order_item_id'])->sum('quantity');
                $newTotalDispatchedQty = $alreadyDispatchedQty + $item['quantity'];

                if ($newTotalDispatchedQty > $orderedQty) {
                    throw new \Exception('Cannot dispatch more than ordered for item: ' . $item['item_id']);
                }
            }

            // If validation passed, create DispatchNote
            $dispatchCode = NumberGenerator::generateCode('dispatch_notes', 'DN', 'dispatch_number',  $invoiceNumber = NumberGenerator::generateInvoiceNumber('invoices', 'INV', env('BRANCH_CODE', 'default'), 5), 5);

            $dispatch = DispatchNote::create([
                'user_id' => $this->authUser->id,
                'dispatch_number' => $dispatchCode,
                'job_order_id' => $jobOrder->id,
                'quantity' => 0,
                'balance_qty' => 0,
                'total_amount' => 0,
                'dispatched_at' => Carbon::now(),
                'status' => 'pending', // initial status
            ]);

            //Create dispatch items and update statuses
            foreach ($this->dispatchItems as $item) {

                $stockBalance = Stock::where('items_id', $item->item_id)->sum('quantity') ?? 0;

                $jobOrderItem = JobOrderItem::find($item['job_order_item_id']);
                if (!$jobOrderItem) {
                    continue;
                }

                $orderedQty = (int) $jobOrderItem->quantity;
                $alreadyDispatchedQty = (int) ModelsDispatchItem::where('job_order_item_id', $item['job_order_item_id'])->sum('quantity');
                $newTotalDispatchedQty = $alreadyDispatchedQty + (int) $item['quantity'];

                $dispatchItem = ModelsDispatchItem::create([
                    'item_id' => $item['item_id'],
                    'user_id' => $this->authUser->id,
                    'order_id' => $jobOrder->id,
                    'dispatch_id' => $dispatch->id,
                    'job_order_item_id' => $item['job_order_item_id'],
                    'quantity' => $item['quantity'],
                    'total_amount' => $item['price'] * $item['quantity'],
                    'status' => $item['status'],
                    'branch_assigned' => 1,
                    'branch_done' => 1,
                ]);

                if ($newTotalDispatchedQty >= $orderedQty) {
                    $jobOrderItem->status = 'dispatched';
                    $dispatchItem->status = 'dispatched';
                    $jobOrderItem->save();
                    $dispatchItem->save();
                }
            }

            //Update dispatch note & job order status
            // Use the new helper method for consistent status update
            $jobOrder->fresh(); // Reload to get latest relationships
            $jobOrder->updateDispatchStatus();
            
            // Update current dispatch note status
            if ($jobOrder->isFullyDispatched()) {
                $dispatch->update(['status' => 'dispatched']);
            } else {
                $dispatch->update(['status' => 'dispatching']);
            }

            $this->dispatchStatus = $dispatch->status;
            $this->updateStock($dispatch->id);
            $this->loadOrderDetails($this->jobOrderId);

            DB::commit();

            session()->flash('success', 'Dispatch items updated successfully.');
            return $this->redirect('/job-order/' . $this->jobOrderId, navigate: true);
        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', $e->getMessage());
        }
    }

    public function updateDispatchItems(int $orderId)
    {
        // 1. Clean out zero-qty items
        $this->dispatchItems = array_values(
            array_filter($this->dispatchItems, fn($i) => ($i['quantity'] ?? 0) > 0)
        );

        if (empty($this->dispatchItems)) {
            session()->flash('error', 'Cannot save: no dispatch items provided.');
            return redirect()->back();
        }

        // 2. Load the JobOrder (or fail)
        $jobOrder = JobOrder::find($orderId);
        if (! $jobOrder) {
            session()->flash('error', 'Job order not found.');
            return redirect()->back();
        }

        // 3. Pre-validate all items
        foreach ($this->dispatchItems as $item) {
            // a) Ensure the order-item exists
            $jobOrderItem = JobOrderItem::find($item['job_order_item_id']);
            if (! $jobOrderItem) {
                throw new \Exception("Item #{$item['job_order_item_id']} not found on that order.");
            }

            // b) Check dispatch vs ordered qty
            $already = ModelsDispatchItem::where('job_order_item_id', $item['job_order_item_id'])
                ->sum('quantity');
            $newTotal = $already + $item['quantity'];
            if ($newTotal > $jobOrderItem->quantity) {
                throw new \Exception(
                    "Cannot dispatch more than ordered for item {$item['item_id']}."
                );
            }

            // c) Check stock availability
            $stockBalance = Stock::where('items_id', $item['item_id'])->sum('quantity') ?? 0;
            if ($item['quantity'] > $stockBalance) {
                // throw new \Exception(
                //     "Insufficient stock for item {$item['item_id']}: "
                //         . "{$item['quantity']} requested, only {$stockBalance} available."
                // );
                session()->flash(
                    'error',
                    "Insufficient stock for item {$item['item_id']}: "
                        . "{$item['quantity']} requested, only {$stockBalance} available."
                );
                return;  // ← return so Livewire re-renders with the flash
            }
        }

        // 4. If we made it here, all validation passed: create the dispatch
        DB::beginTransaction();
        try {
            $dispatchCode = NumberGenerator::generateCode(
                'dispatch_notes',            // table
                'DN',                        // prefix
                'dispatch_number',           // column
                env('BRANCH_CODE', 'default'), // branch code
                5                            // padding length
            );


            $dispatch = DispatchNote::create([
                'user_id'        => $this->authUser->id,
                'dispatch_number' => $dispatchCode,
                'job_order_id'   => $jobOrder->id,
                'dispatched_at'  => now(),
                'status'         => 'pending',
            ]);

            $totalQty   = 0;
            $totalValue = 0;

            foreach ($this->dispatchItems as $item) {
                $line = ModelsDispatchItem::create([
                    'item_id'           => $item['item_id'],
                    'user_id'           => $this->authUser->id,
                    'order_id'          => $jobOrder->id,
                    'dispatch_id'       => $dispatch->id,
                    'job_order_item_id' => $item['job_order_item_id'],
                    'quantity'          => $item['quantity'],
                    'total_amount'      => $item['price'] * $item['quantity'],
                ]);

                $totalQty   += $item['quantity'];
                $totalValue += $item['price'] * $item['quantity'];

                // mark fully-dispatched items
                $jobOrderItem = JobOrderItem::find($item['job_order_item_id']);
                $already = (int) ModelsDispatchItem::where('job_order_item_id', $item['job_order_item_id'])
                    ->sum('quantity');
                if ($already >= (int) $jobOrderItem->quantity) {
                    $jobOrderItem->status = 'dispatched';
                    $jobOrderItem->save();
                    $line->status        = 'dispatched';
                    $line->save();
                }
            }

            // 5. Update totals & overall status
            $dispatch->update([
                'quantity'     => $totalQty,
                'total_amount' => $totalValue,
            ]);

            // Use the new helper method for consistent status update
            $jobOrder->fresh(); // Reload to get latest relationships
            $jobOrder->updateDispatchStatus();
            
            // Update current dispatch note status based on job order status
            if ($jobOrder->isFullyDispatched()) {
                $dispatch->update(['status' => 'dispatched']);
            } else {
                $dispatch->update(['status' => 'dispatching']);
            }

            DB::commit();

            // 6. Finally, adjust stock, reload details, notify user
            $this->updateStock($dispatch->id);
            $this->loadOrderDetails($this->jobOrderId);

            session()->flash('success', 'Dispatch items updated successfully.');
            return $this->redirect("/job-order/{$this->jobOrderId}", navigate: true);
        } catch (\Throwable $e) {
            DB::rollBack();
            session()->flash('error', $e->getMessage());
            return $this->redirect(request()->header('Referer') ?? "/job-order/{$orderId}");
        }
    }



    public function updateStock($dispatchId)
    {
        try {
            $dispatchItems = ModelsDispatchItem::where('dispatch_id', $dispatchId)->get();
            foreach ($dispatchItems as $dispatchItem) {
                $stockBalance = Stock::where('items_id', $dispatchItem->item_id)->sum('quantity') ?? 0;
                $product = Item::find($dispatchItem->item_id);

                if ($stockBalance > 0 && $dispatchItem->quantity <= $stockBalance) {
                    $stock = DB::table('stocks')
                        ->select('*', DB::raw('sum(quantity) as qty'))
                        ->where('items_id', $dispatchItem->item_id)
                        // ->where('branch_id', $this->jobOrder->branch_id)
                        ->orderBy('created_at', 'asc')
                        ->get();

                    $remaining_quantity = $dispatchItem['quantity'];

                    // Update stock and create JobOrderItem entries
                    foreach ($stock as $stock_entry) {
                        if ($remaining_quantity > 0) {
                            if ($stock_entry->qty >= $remaining_quantity) {
                                Stock::create([
                                    'items_id' => $stock_entry->items_id,
                                    'brands_id' => $stock_entry->brands_id,
                                    'branch_id' => $this->jobOrder->branch_id,
                                    'user_id' => $this->authUser->id,
                                    'quantity' => -abs(intval($remaining_quantity)),
                                    'purchase_price' => $dispatchItem->item->purchase_price,
                                    'sales_price' => $dispatchItem->item->sales_price,
                                    'mrp' => $dispatchItem->item->mrp,
                                    'p_id' => $dispatchId,
                                    'f_id' => $dispatchItem->id,
                                    'table_name' => 'dispatch',
                                    'effective_date' => now(),
                                    'sku_code' => $stock_entry->sku_code,
                                    'online' => 1,
                                ]);
                                $remaining_quantity = 0;
                            } else {
                                if ($stock_entry->qty != 0) {
                                    Stock::create([
                                        'items_id' => $product->id,
                                        'brands_id' => $product->brands_id,
                                        'branch_id' => $this->jobOrder->branch_id,
                                        'user_id' => $this->authUser->id,
                                        'quantity' => -abs(intval($stock_entry->qty)),
                                        'purchase_price' => $dispatchItem->item->purchase_price,
                                        'sales_price' => $dispatchItem->item->sales_price,
                                        'mrp' => $dispatchItem->item->mrp,
                                        'p_id' => $dispatchId,
                                        'f_id' => $dispatchItem->id,
                                        'table_name' => 'dispatch',
                                        'effective_date' => now(),
                                        'sku_code' => $stock_entry->sku_code,
                                        'online' => 1,
                                    ]);

                                    $remaining_quantity -= $stock_entry->qty;
                                }
                            }
                        }
                    }
                } else {
                    session()->flash('error', 'Order Not Updated!');
                }
            }
        } catch (\Exception $e) {
            // Flash error message to session
            session()->flash('error', $e->getMessage());
        }
    }

    public function removeItem($id)
    {
        return;
        // ModelsDispatchItem::where('id', $id)
        //     ->delete();
        // $this->getDispatchItems($id);
    }

    public function getDispatchItems($id)
    {
        $index = array_search($id, array_column($this->dispatchItems, 'id'));

        if ($index !== false) {
            // Remove the item at that index from the array
            unset($this->dispatchItems[$index]);
        }

        // Reindex the array to ensure proper indexing
        $this->dispatchItems = array_values($this->dispatchItems);
    }

    public function getDispatchItemBalance($itemId, $orderId)
    {
        $dispatchedSum = DB::table('dispatch_items')
            ->join('job_order_items', 'job_order_items.id', '=', 'dispatch_items.job_order_item_id')
            ->selectRaw('sum(dispatch_items.quantity) as dispatched_quantity, job_order_items.quantity as item_qty')
            ->where('dispatch_items.item_id', $itemId)
            ->where('dispatch_items.order_id', $orderId)
            ->first();
        $dispatchedQuantity = $dispatchedSum ? $dispatchedSum->dispatched_quantity : 0;
        $dispatchBalance = $dispatchedSum->item_qty - $dispatchedQuantity;
        return $dispatchBalance;
    }


    public function dispatchPrintPreview($dispatchNoteId)
    {
        return redirect()->route('dispatch-note.print-preview', ['dispatchNoteId' => $dispatchNoteId]);
    }

    /**
     * Check if all job order items are fully dispatched
     */
    private function checkIfFullyDispatched($orderId)
    {
        $jobOrderItems = JobOrderItem::where('order_id', $orderId)->get();
        
        if ($jobOrderItems->isEmpty()) {
            return false;
        }

        foreach ($jobOrderItems as $jobOrderItem) {
            $dispatchedQty = DB::table('dispatch_items')
                ->where('job_order_item_id', $jobOrderItem->id)
                ->sum('quantity');
            
            // If any item is not fully dispatched, return false
            if ($dispatchedQty < $jobOrderItem->quantity) {
                return false;
            }
        }

        return true;
    }

    /**
     * Request admin to update job order quantity to match dispatched quantity
     * This is for dispatch role users
     */
    public function requestQtyUpdateToAdmin($orderId)
    {
        try {
            $jobOrder = JobOrder::find($orderId);
            
            if (!$jobOrder) {
                session()->flash('error', 'Job order not found.');
                $this->showRequestQtyModal = false;
                return;
            }

            // Check if there are dispatched items
            $dispatchedCount = DB::table('dispatch_items')
                ->where('order_id', $orderId)
                ->sum('quantity');

            if ($dispatchedCount == 0) {
                session()->flash('error', 'No dispatched items found. Cannot request quantity update.');
                $this->showRequestQtyModal = false;
                return;
            }

            // Set the request flag
            $jobOrder->qty_update_requested = true;
            $jobOrder->qty_update_requested_by = $this->authUser->id;
            $jobOrder->qty_update_requested_at = now();
            $jobOrder->save();

            $this->hasPendingQtyUpdateRequest = true;
            $this->loadOrderDetails($orderId);
            $this->showRequestQtyModal = false;
            $this->showRequestSuccessModal = true;
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to send request: ' . $e->getMessage());
            $this->showRequestQtyModal = false;
        }
    }

    /**
     * Handle redirect to job order list after success modal
     */
    public function redirectToJobOrderList()
    {
        $this->showRequestSuccessModal = false;
        return $this->redirect('/job-orders', navigate: true);
    }

    /**
     * Update job order item quantity to match dispatched quantity
     * This removes the remaining balance from the job order
     * Only admin can execute this
     */
    public function updateJobOrderQuantityToDispatched($orderId)
    {
        // Check if user is admin
        if ($this->authUser->mode !== 'admin') {
            session()->flash('error', 'Only administrators can update job order quantities.');
            return redirect()->back();
        }

        DB::beginTransaction();
        
        try {
            $jobOrder = JobOrder::with('orderItems')->find($orderId);
            
            if (!$jobOrder) {
                session()->flash('error', 'Job order not found.');
                return redirect()->back();
            }

            $totalAmount = 0;
            $hasAnyDispatchedItems = false;

            // Loop through all job order items
            foreach ($jobOrder->orderItems as $jobOrderItem) {
                // Get total dispatched quantity for this item
                $dispatchedQty = ModelsDispatchItem::where('job_order_item_id', $jobOrderItem->id)
                    ->sum('quantity');

                // Prevent updating to 0 - skip items with no dispatched quantity
                if ($dispatchedQty > 0) {
                    $hasAnyDispatchedItems = true;
                    // Update the job order item quantity to match dispatched quantity
                    $jobOrderItem->quantity = $dispatchedQty;
                    $jobOrderItem->total = $jobOrderItem->price * $dispatchedQty;
                    $jobOrderItem->status = 'dispatched';
                    $jobOrderItem->save();

                    $totalAmount += $jobOrderItem->total;
                }
            }

            // Check if we have any items to update
            if (!$hasAnyDispatchedItems) {
                DB::rollBack();
                session()->flash('error', 'Cannot update: No dispatched items found. Quantity cannot be set to 0.');
                return redirect()->back();
            }

            // Update job order total amount
            $jobOrder->total_amount = $totalAmount;
            // Clear the request flag after approval
            $jobOrder->qty_update_requested = false;
            $jobOrder->qty_update_requested_by = null;
            $jobOrder->qty_update_requested_at = null;
            $jobOrder->save();

            // Update dispatch status
            $jobOrder->fresh();
            $jobOrder->updateDispatchStatus();

            // Update all dispatch items status to 'dispatched'
            ModelsDispatchItem::where('order_id', $orderId)
                ->update(['status' => 'dispatched']);

            // Update all dispatch notes status
            DispatchNote::where('job_order_id', $orderId)
                ->update(['status' => 'dispatched']);

            DB::commit();

            // Reload order details
            $this->loadOrderDetails($orderId);
            $this->hasPendingQtyUpdateRequest = false;

            session()->flash('success', 'Job order quantity updated to match dispatched quantity successfully.');
            return $this->redirect("/job-order/{$orderId}", navigate: true);
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to update job order quantity: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function render()
    {
        $bodyAttributes = 'x-data="{ page: \'dispatchView\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
        x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
        :class="{\'dark bg-gray-900\': darkMode === true}"';

        return view('livewire.dispatch.dispatch-item-view')->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
