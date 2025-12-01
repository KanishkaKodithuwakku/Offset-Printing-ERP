<?php

namespace App\Livewire\Job;

use App\Helpers\NumberGenerator;
use App\Helpers\StatusHelper;
use App\Models\Branch;
use App\Models\DispatchItem;
use App\Models\DispatchNote;
use App\Models\Entry;
use App\Models\EntryItem;
use App\Models\EntryType;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Item;
use App\Models\Ledger;
use Livewire\Component;
use App\Models\JobOrder;
use App\Models\JobOrderItem;
use App\Models\Stock;
use App\Models\StockTransferItem;
use App\Models\StockTransferNote;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JobOrderView extends Component
{

    public $jobOrderId, $plate_backing, $backing_qty, $description, $special_instruction;
    public $jobOrder;
    public $status;
    public $assigned;
    public $searchTerm = '';
    public $searchResults = [];
    public $orderItems = [];
    public $customer_id, $payment_method, $total_amount, $customer_name;
    public $authUser;
    public $isEligibleToDispatch;
    public $dispatchNotes;
    public $stockProcess = true;
    public $statusText;
    public $role;
    public $invoice;

    public $dispatchItems = [];
    public $hasDispatchedItems = false;

    public $customer_po_number;



    public function mount($jobOrderId)
    {
        $this->authUser = auth()->user();
        $this->role = $this->authUser->mode;
        $this->jobOrderId = $jobOrderId;
        $this->jobOrder = JobOrder::with('customer', 'user', 'jobDoneBy', 'jobCheckedBy')->find($jobOrderId);
        $this->status = $this->jobOrder->status;
        $this->statusText = StatusHelper::getJobOrderStatus($this->jobOrder->status);
        $this->assigned = $this->jobOrder->assign_to;
        $this->isEligibleToDispatch = $this->isEligibleToDispatch($jobOrderId);

        $this->invoice = Invoice::where('order_id', $jobOrderId)->first();

        // Check if there are dispatched items
        $dispatchedCount = DB::table('dispatch_items')
            ->where('order_id', $jobOrderId)
            ->sum('quantity');
        $this->hasDispatchedItems = $dispatchedCount > 0;

        if ($jobOrderId) {
            $this->loadOrderDetails($jobOrderId);
        }
    }

    // public function selectBranch(int $branchId)
    // {
    //     $this->selectedBranchId = $branchId;

    //     // Optional: save to job order or prepare for submission
    //     $this->jobOrder->branch_id = $branchId;
    //     $this->showBranchDropdown = false;
    // }




    public function isEligibleToDispatch($jobOrderId)
    {

        $data = DB::table('job_order_items')
            ->leftJoin('dispatch_items', 'job_order_items.id', '=', 'dispatch_items.job_order_item_id')
            ->selectRaw('sum(dispatch_items.quantity) as dispatched_quantity, job_order_items.quantity')
            ->where('job_order_items.order_id', $jobOrderId)
            ->groupBy('job_order_items.id', 'job_order_items.quantity')
            ->havingRaw('COALESCE(sum(dispatch_items.quantity), 0) < job_order_items.quantity')
            ->exists();

        return $data;
    }


    /**
     * Check if all items in the job order are fully dispatched
     * and update status from 'dispatching' to 'dispatched' if applicable
     */
    public function checkAndUpdateDispatchStatus($jobOrderId)
    {
        try {
            // Get all job order items for this order
            $jobOrderItems = JobOrderItem::where('order_id', $jobOrderId)->get();

            if ($jobOrderItems->isEmpty()) {
                return false;
            }

            $allItemsFullyDispatched = true;

            foreach ($jobOrderItems as $jobOrderItem) {
                // Get total dispatched quantity for this item (including both pending and dispatched status)
                $totalDispatchedQuantity = DB::table('dispatch_items')
                    ->where('order_id', $jobOrderId)
                    ->where('item_id', $jobOrderItem->item_id)
                    ->sum('quantity');

                // Check if total dispatched quantity equals job order item quantity
                if ($totalDispatchedQuantity != $jobOrderItem->quantity) {
                    $allItemsFullyDispatched = false;
                    break;
                }
            }

            // If all items are fully dispatched and current status is 'dispatching', update to 'dispatched'
            if ($allItemsFullyDispatched) {
                $jobOrder = JobOrder::find($jobOrderId);
                if ($jobOrder && $jobOrder->status === 'dispatching') {
                    // Update job order status
                    $jobOrder->status = 'dispatched';
                    $jobOrder->save();

                    // Update the status of all job order items
                    JobOrderItem::where('order_id', $jobOrderId)->update(['status' => 'dispatched']);

                    // Update all dispatch_items status from 'pending' to 'dispatched' for this job order
                    DB::table('dispatch_items')
                        ->where('order_id', $jobOrderId)
                        ->where('status', 'pending')
                        ->update(['status' => 'dispatched']);

                    Log::channel('job_order_log')->info('Job order status updated to dispatched', [
                        'job_order_id' => $jobOrderId,
                        'job_number' => $jobOrder->job_number,
                        'previous_status' => 'dispatching',
                        'new_status' => 'dispatched',
                        'user_id' => $this->authUser->id,
                        'user_mode' => $this->authUser->mode,
                        'dispatch_items_updated' => 'pending to dispatched'
                    ]);

                    return true;
                }
            }

            return false;
        } catch (\Exception $e) {
            Log::channel('job_order_log')->error('Error checking dispatch status: ' . $e->getMessage(), [
                'job_order_id' => $jobOrderId,
                'error_message' => $e->getMessage()
            ]);
            return false;
        }
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
                foreach ($this->orderItems as $items) {
                    if ($items['item_id'] === $item->id) {
                        $sameItem = true;
                        break;
                    }
                }

                $stockBalance = Stock::where('items_id', $item->id)->sum('quantity') ?? 0;
                if ($stockBalance == 0) {
                    $this->stockProcess = false;
                }

                return [
                    'id' => $item->id,
                    'item_name' => $item->item_name,
                    'item_code' => $item->item_code,
                    'sku_code' => $item->sku_code,
                    'selling_price' => $item->sales_price,
                    'purchase_price' => $item->purchase_price,
                    'brands_id' => $item->brands_id,
                    'quantity' => $stockBalance > 0 ? 1 : 0,
                    'same_item' => $sameItem,
                    'stock_balance' => $stockBalance,
                    'isFromDb' => false
                ];
            })->toArray();
        } else {
            $this->searchResults = [];
        }
    }

    public $selectedItemId;

    public function addOrderItem($itemId)
    {
        $this->selectedItemId = $itemId;
        $item = Item::find($this->selectedItemId);

        if ($item) {
            // Check if the item already exists in the jobOrderItems array (via the DB)
            $existingItem = JobOrderItem::where('item_id', $item->id)
                ->where('order_id', $this->jobOrderId) // assuming you have a job order ID
                ->first();

            // Get stock balance for the selected item
            $stockBalance = Stock::where('items_id', $item->id)->sum('quantity') ?? 0;

            if ($stockBalance == 0) {
                // Early return if no stock is available
                session()->flash('error', 'Quantity cannot exceed available stock since the available stock balance is (0)');
                return;
            }

            if ($existingItem) {
                // If the item exists in the order, check if the quantity is within the available stock
                if ($stockBalance < $existingItem->quantity + 1) {
                    session()->flash('error', 'Quantity cannot exceed available stock since the available stock balance is (' . $stockBalance . ')');
                } else {
                    // Update the quantity and total of the existing item
                    $existingItem->quantity += 1;
                    $existingItem->total = $existingItem->quantity * $existingItem->selling_price;
                    $existingItem->save();  // Save the updated item
                }
            } else {
                // If the item does not exist in the order, create a new record in job_order_items table
                $newItem = JobOrderItem::create([
                    'order_id' => $this->jobOrderId, // assuming jobOrderId is available
                    'item_id' => $item->id,
                    'quantity' => 1,
                    'price' => $item->sales_price,
                    'total' => $item->sales_price, // Assuming total is selling_price * quantity
                ]);
            }

            // After adding or updating the item, update the $this->orderItems array
            $this->loadOrderDetails($this->jobOrderId);

            // Call calculateTotal or any other necessary function to update the total
            $this->calculateTotal();

            // Clear the search term and results
            $this->searchTerm = '';
            $this->searchResults = [];
        }

        // Log updated job order items (optional)
        Log::debug('Job Order Items:', $this->orderItems);
    }



    public function __assignJob()
    {
        // Ensure the current user is authenticated
        // if ($this->user->mode = 'admin') {
        // Assign the current authenticated user as the 'assign_to' user
        $this->jobOrder->assign_to = $this->authUser->id;
        $this->jobOrder->status = 'designing';
        $this->jobOrder->save();
        $this->assigned = $this->authUser->id;
        $this->status = $this->jobOrder->status;
        $this->statusText = StatusHelper::getJobOrderStatus($this->jobOrder->status);
        session()->flash('success', 'Job successfully assigned to you.');
        // } else {
        //     session()->flash('error', 'User is not authenticated.');
        // }
    }

    public function assignJob()
    {
        // Check if branch_id is null, then assign from authUser
        if (is_null($this->jobOrder->branch_id)) {
            $this->jobOrder->branch_id = $this->authUser->branch_id;
        }

        if (is_null($this->jobOrder->invoice_branch)) {
            $this->jobOrder->invoice_branch = $this->authUser->branch_id;
        }


        // Assign current authenticated user as assign_to
        $this->jobOrder->assign_to = $this->authUser->id;
        $this->jobOrder->status = 'designing';
        $this->jobOrder->save();

        $this->assigned = $this->authUser->id;
        $this->status = $this->jobOrder->status;
        $this->statusText = StatusHelper::getJobOrderStatus($this->jobOrder->status);

        session()->flash('success', 'Job successfully assigned to you.');
    }





    public function unAssignJob()
    {
        // if (Auth::check()) {
        // Assign the current authenticated user as the 'assign_to' user
        $this->jobOrder->assign_to = null;
        $this->jobOrder->status = 'pending';
        $this->jobOrder->save();
        $this->assigned = null;
        $this->status = $this->jobOrder->status;
        $this->statusText = StatusHelper::getJobOrderStatus($this->jobOrder->status);
        session()->flash('success', 'Job successfully un-assigned.');
        // } else {
        //     session()->flash('error', 'User is not authenticated.');
        // }
    }

    public function updateStatus()
    {
        // Get the currently logged in user
        $user = auth()->user();

        // Check the status transition rules
        if ($this->status == 'printing' && $user->mode != 'design') {
            session()->flash('error', 'You are not authorized to change the status to Printing.');
            return;
        }

        if ($this->status == 'dispatch' && $user->mode != 'print') {
            session()->flash('error', 'You are not authorized to change the status to Dispatch.');
            return;
        }

        if ($this->status == 'invoiced' && $user->mode != 'invoice') {
            session()->flash('error', 'You are not authorized to change the status to Invoiced.');
            return;
        }

        // Ensure that the status transition follows the allowed order
        if ($this->status == 'printing' && $this->jobOrder->status != 'pending') {
            session()->flash('error', 'Job Order must be in "Pending" status to transition to "Printing".');
            return;
        }

        if ($this->status == 'dispatch' && $this->jobOrder->status != 'printing') {
            session()->flash('error', 'Job Order must be in "Printing" status to transition to "Dispatch".');
            return;
        }

        if ($this->status == 'invoiced' && $this->jobOrder->status != 'dispatch') {
            session()->flash('error', 'Job Order must be in "Dispatch" status to transition to "Invoiced".');
            return;
        }

        // Update the status
        $this->jobOrder->update(['status' => $this->status]);

        session()->flash('success', 'Job Order status updated successfully!');
    }

    public function deleteJobOrder()
    {
        $this->jobOrder->delete();

        session()->flash('message', 'Job Order deleted successfully!');
        return redirect()->route('job-orders.index');
    }

    public function dispatchPrintPreview($dispatchNoteId)
    {


        return redirect()->route('dispatch-note.print-preview', ['dispatchNoteId' => $dispatchNoteId]);
    }

    public function loadOrderDetails($orderId)
    {
        $this->jobOrder = JobOrder::with(['customer', 'orderItems'])
            ->where('id', $orderId)
            ->first();

        $this->plate_backing = $this->jobOrder->plate_backing;
        $this->backing_qty = $this->jobOrder->backing_qty;
        $this->description = $this->jobOrder->description;
        $this->special_instruction = $this->jobOrder->special_instruction;
        $this->customer_po_number = $this->jobOrder->customer_po_number;

        $this->dispatchNotes = DispatchNote::withCount([
            'dispatchItems as quantity' => function ($query) {
                $query->select(DB::raw("SUM(quantity)"));
            }
        ])->where('job_order_id', $orderId)->get();

        if ($this->jobOrder) {
            $this->jobOrderId = $orderId;
            $this->customer_id = $this->jobOrder->customer_id;
            $this->payment_method = $this->jobOrder->payment_method;
            $this->total_amount = $this->jobOrder->total_amount;
            $this->status = $this->jobOrder->status;
            $this->statusText = StatusHelper::getJobOrderStatus($this->jobOrder->status);
            $this->customer_name = $this->jobOrder->customer->name;
            
            // Check if there are dispatched items
            $dispatchedCount = DB::table('dispatch_items')
                ->where('order_id', $orderId)
                ->sum('quantity');
            $this->hasDispatchedItems = $dispatchedCount > 0;
            
            // Load order items
            $this->orderItems = $this->jobOrder->orderItems->map(function ($item) {
                $stockBalance = Stock::where('items_id', $item->item_id)->sum('quantity') ?? 0;
                if ($stockBalance == 0) {
                    $this->stockProcess = false;
                }

                $dispatchedCount = DB::table('dispatch_items')
                    ->where('order_id', $item->order_id)
                    ->where('item_id', $item->item_id)
                    ->sum('quantity');


                return [
                    'id' => $item->id,
                    'item_id' => $item->item_id,
                    'name' => $item->item ? $item->item->item_name : 'Unknown',  // Avoid errors if item is missing
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'dispatched_qty' => $dispatchedCount,
                    'total' => $item->total,
                    'stock_balance' => $stockBalance,
                    'isFromDb' => true
                ];
            })->toArray();
        } else {
            session()->flash('error', 'Order not found!');
            return $this->redirect('/customer-orders');
        }
    }

    public function viewDispatchNote($dispatchNoteId)
    {
        return redirect()->route('dispatch-items', ['dispatchId' => $dispatchNoteId]);
    }

    public function updateOrderItems()
    {
        try {
            // Loop through all order items and save them to the database
            foreach ($this->orderItems as $orderItem) {
                // Check if the quantity exceeds the available stock
                if ($orderItem['quantity'] > $orderItem['stock_balance']) {
                    session()->flash('error', 'Quantity cannot exceed available stock for item: ' . $orderItem['name']);
                    return;
                }

                // Update the JobOrderItem in the database
                $jobOrderItem = JobOrderItem::find($orderItem['id']);
                if ($jobOrderItem) {
                    $jobOrderItem->quantity = $orderItem['quantity'];
                    $jobOrderItem->total = $orderItem['quantity'] * $jobOrderItem->price;
                    $jobOrderItem->save();
                }
            }

            // Recalculate the overall total for the job order
            $this->calculateTotal();

            // Reload the order details to reflect changes
            $this->loadOrderDetails($this->jobOrderId);

            session()->flash('success', 'Job Order updated successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update job order: ' . $e->getMessage());
        }
    }

    public function releseOrder($jobOrderId)
    {
        $jobOrder = JobOrder::find($jobOrderId);
        $jobOrder->status = 'printing';
        $jobOrder->save();
        // return $this->redirect('/job-orders');
        // return redirect()->route('/job-orders');
        //$this->redirect('/job-orders');
        return $this->redirect('/job-orders', navigate: true);
    }


    public function updateField($field, $value)
    {
        // Fetch the job order based on the provided job order ID
        $jobOrder = JobOrder::find($this->jobOrderId);

        // Check if the job order exists and if its status is 'pending' or 'designing'
        if (!$jobOrder) {
            session()->flash('error', 'Job Order not found!');
            return;
        }

        // Check if the status of the job order is valid ('pending' or 'designing')
        if (!in_array($jobOrder->status, ['pending', 'designing'])) {
            session()->flash('error', 'Invalid action! Job Order status must be "pending" or "designing".');
            return;
        }

        // Check if the user is allowed to update the fields (user mode should be 'designing' or 'admin')
        if (!in_array(auth()->user()->mode, ['design', 'admin'])) {
            session()->flash('error', 'Invalid action! You do not have permission to perform this update.');
            return;
        }

        try {
            // Handle field-specific validation and updates
            switch ($field) {
                case 'plate_backing':
                    // Validate the plate_backing field value (must be 1 or 0)
                    if (!in_array($value, [1, 0])) {
                        session()->flash('error', 'Invalid Plate Backing value!');
                        return;
                    }
                    $this->plate_backing = $value;
                    break;

                case 'backing_qty':
                    // Validate that the quantity is a positive number
                    if ($value < 1) {
                        session()->flash('error', 'Backing Quantity must be a positive number!');
                        return;
                    }
                    $this->backing_qty = $value;
                    break;

                case 'description':
                    $this->description = $value;
                    break;

                case 'customer_po_number':
                    $this->customer_po_number = $value;
                    break;

                case 'special_instruction':
                    $this->special_instruction = $value;
                    break;

                // Handle more fields as needed
                default:
                    session()->flash('error', 'Invalid field!');
                    return;
            }

            // Update the job order with the new field values
            $jobOrder->description = $this->description;
            $jobOrder->customer_po_number = $this->customer_po_number;
            $jobOrder->special_instruction = $this->special_instruction;
            $jobOrder->backing_qty = $this->backing_qty;
            $jobOrder->plate_backing = $this->plate_backing;
            $jobOrder->save();

            // Check and update dispatch status if admin is editing
            if ($this->authUser->mode === 'admin') {
                $this->checkAndUpdateDispatchStatus($this->jobOrderId);
            }

            // Flash success message
            session()->flash('success', ucfirst(str_replace('_', ' ', $field)) . ' updated successfully!');
        } catch (\Exception $e) {
            // Handle errors and flash an error message
            session()->flash('error', 'Failed to update the field: ' . $e->getMessage());
        }
    }



    public function invoicePrintPreview($orderId)
    {
        $this->invoice = Invoice::where('order_id', $orderId)->first();

        // Check if the invoice exists
        if (!$this->invoice) {
            // Handle the case when the invoice is not found (e.g., redirect or show an error message)
            session()->flash('error', 'Invoice not found for this order.');
            //return redirect()->route('invoice.index'); // Or any appropriate redirect
        }
    }



    public function updateTotal($index)
    {

        // Fetch the job order based on the provided job order ID
        $jobOrder = JobOrder::find($this->jobOrderId);

        // Check if the job order exists and if its status is 'pending' or 'designing'
        if (!$jobOrder) {
            session()->flash('error', 'Job Order not found!');
            return;
        }

        // Check if the status of the job order is valid ('pending' or 'designing')
        if (!in_array($jobOrder->status, ['pending', 'designing'])) {
            session()->flash('error', 'Invalid action! Job Order status must be "pending" or "designing".');
            return;
        }

        // Check if the user is allowed to update the fields (user mode should be 'designing' or 'admin')
        if (!in_array(auth()->user()->mode, ['design', 'admin'])) {
            session()->flash('error', 'Invalid action! You do not have permission to perform this update.');
            return;
        }
        if (isset($this->orderItems[$index])) {
            // Check if the quantity exceeds the available stock
            if ($this->orderItems[$index]['quantity'] > $this->orderItems[$index]['stock_balance']) {
                // Flash an error message
                session()->flash('error', 'Quantity cannot exceed available stock since the available stock balance is  (' . $this->orderItems[$index]['stock_balance'] . ')');
                return; // Return early if the quantity exceeds available stock
            }

            // Recalculate the total
            $this->orderItems[$index]['total'] = $this->orderItems[$index]['quantity'] * $this->orderItems[$index]['price'];

            // Update the corresponding JobOrderItem in the database
            $jobOrderItem = JobOrderItem::find($this->orderItems[$index]['id']); // Use 'id' to find the item
            if ($jobOrderItem) {
                $jobOrderItem->quantity = $this->orderItems[$index]['quantity'];  // Update the quantity
                $jobOrderItem->total = $this->orderItems[$index]['total'];  // Optionally, update the total
                $jobOrderItem->save();  // Save the updated model
            }

            // Flash success message after saving the item
            session()->flash('success', 'Job Order updated successfully!');
        }

        // Recalculate the overall total for the job order
        $this->calculateTotal();
    }


    public function duplicateOrder($jobOrderId)
    {
        try {
            // Find the original job order by ID
            $originalJobOrder = JobOrder::findOrFail($jobOrderId);

            // Generate a new job number
            $jobNumber = NumberGenerator::generateJobOrderNumber($this->authUser->branch->id, $jobOrderId);

            // Duplicate the job order
            $newJobOrder = JobOrder::create([
                'job_number' => $jobNumber,
                'date_created' => Carbon::now(),
                'user_id' => $this->authUser->id,
                'customer_id' => $originalJobOrder->customer_id,
                'branch_id' => $this->authUser->branch->id,
                'description' => $originalJobOrder->description,
                'customer_po_number' => $originalJobOrder->customer_po_number,
                'special_instruction' => $originalJobOrder->special_instruction,
                'job_done_by' => $originalJobOrder->job_done_by,
                'job_checked_by' => $originalJobOrder->job_checked_by,
                'delivery_date' => $originalJobOrder->delivery_date,
                'plate_backing' => $originalJobOrder->plate_backing,
                'backing_qty' => $originalJobOrder->backing_qty,
                'total_amount' => $originalJobOrder->total_amount,
                'status' => 'pending',
            ]);


            if (!$originalJobOrder->orderItems || $originalJobOrder->orderItems->isEmpty()) {
                // If orderItems is null or empty, stop the process
                session()->flash('error', 'No items found in the original job order.');
                return;
            }

            // Loop through the order items and check if stock is available before duplicating
            foreach ($originalJobOrder->orderItems as $item) {
                // Check if sufficient stock is available
                $stockBalance = Stock::where('items_id', $item->item_id)->sum('quantity') ?? 0;

                if ($stockBalance < $item->quantity) {
                    // If stock is insufficient, stop the process and return an error
                    session()->flash('error', 'Insufficient stock for item: ' . $item->item->item_name);
                    return;
                }

                // If stock is available, duplicate the job order item
                JobOrderItem::create([
                    'order_id' => $newJobOrder->id,
                    'item_id' => $item->item_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total' => $item->total,
                ]);
            }

            session()->flash('message', 'Job order duplicated successfully!');
            return $this->redirect('/job-orders', navigate: true);
        } catch (\Exception $e) {
            session()->flash('error', 'Error duplicating job order: ' . $e->getMessage());
        }
    }



    public function calculateTotal()
    {

        $this->total_amount = array_sum(array_column($this->orderItems, 'total'));
        JobOrder::where('id', $this->jobOrderId)
            ->update([
                'total_amount' => $this->total_amount
            ]);
    }


    public function generateDispatchFromOrder($orderId)
    {
        $jobOrder = JobOrder::with('orderItems')->find($orderId);
        if (!$jobOrder) {
            session()->flash('error', 'Job Order not found!');
            return;
        }

        $this->payment_method = $jobOrder->payment_method;
        $this->dispatchItems = []; // clear or init the array

        foreach ($jobOrder->orderItems as $orderItem) {
            // Calculate $dispatchBalance same as before
            $this->dispatchItems[] = [
                'item_id' => $orderItem->item_id,
                'user_id' => $this->authUser->id,
                'order_id' => $orderItem->order_id,
                'job_order_item_id' => $orderItem->id,
                'quantity' => $orderItem->quantity,
                'total_amount' => $orderItem->price * $orderItem->quantity,
                'status' => $orderItem->total,
                'branch_assigned' => 1,
                'branch_done' => 1,
            ];
        }
        // Save the job order status or defer until save
        $jobOrder->status = 'dispatching';
        $jobOrder->save();
        return $this->redirect('/dispatch-item/' . $jobOrder->id);
    }


    public function saveDispatch()
    {
        DB::beginTransaction();
        try {
            $dispatchCode = NumberGenerator::generateCode('dispatch_notes', 'DN', 'dispatch_number', 5);
            $dispatch = DispatchNote::create([
                'user_id' => $this->authUser->id,
                'dispatch_number' => $dispatchCode,
                'job_order_id' => $this->dispatchItems[0]['order_id'] ?? null, // or pass job order id explicitly
                'quantity' => array_sum(array_column($this->dispatchItems, 'quantity')),
                'balance_qty' => 0, // calculate if needed
                'total_amount' => array_sum(array_column($this->dispatchItems, 'total_amount')),
                'dispatched_at' => now(),
            ]);
            foreach ($this->dispatchItems as $item) {
                DispatchItem::create([
                    'dispatch_id' => $dispatch->id,
                    'item_id' => $item['item_id'],
                    'order_id' => $item['order_id'],
                    'job_order_item_id' => $item['job_order_item_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['total_amount'] / $item['quantity'],
                    'total' => $item['total_amount'],
                    'status' => $item['status'],
                    'branch_assigned' => $item['branch_assigned'],
                    'branch_done' => $item['branch_done'],
                ]);
            }
            DB::commit();
            session()->flash('success', 'Dispatch Note created successfully!');
            return $this->redirect('/dispatch-item/' . $dispatch->id);
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to save dispatch: ' . $e->getMessage());
        }
    }

    public function generateInvoiceFromOrder($orderId)
    {
        $invoice = null;
        // Create accounting entry (double-entry)
        DB::beginTransaction();

        try {

            // Fetch the order with its related customer and order items
            $jobOrder = JobOrder::with('orderItems')->find($orderId);

            if (!$jobOrder) {
                session()->flash('error', 'Order not found!');
                return;
            }

            // Check if the job order already has at least one invoice
            $existingInvoice = Invoice::where('order_id', $jobOrder->id)
                ->where('order_type', JobOrder::class)
                ->exists();

            if ($existingInvoice) {
                session()->flash('error', 'Job Order already has an invoice!');
                return;
            }

            $customer = $jobOrder->customer;

            // Generate invoice number (You can modify this logic as needed)
            $invoiceNumber = NumberGenerator::generateInvoiceNumber('invoices', 'INV', env('BRANCH_CODE', 'default'), 5);

            // Create the invoice record
            $invoice = Invoice::create([
                'order_id' => $jobOrder->id,
                'order_type' => JobOrder::class,  // Set order_type to the model class name
                'customer_id' => $jobOrder->customer_id,
                'branch_id' => $jobOrder->invoice_branch,
                'invoice_number' => $invoiceNumber,
                'status' => 'invoicing',
                'total_amount' => $jobOrder->total_amount,
                'amount_due' => $jobOrder->total_amount,
                'advance_payment' => $jobOrder->advance_payment ?? 0,
                'payment_method' => $jobOrder->payment_method,
                'bank_name' => $jobOrder->bank_name,
                'cheque_no' => $jobOrder->cheque_no,
                'cheque_realization_date' => $jobOrder->cheque_realization_date,
                'invoice_details' => null,
            ]);

            // Process the order items and insert them into the invoice_items table
            foreach ($jobOrder->orderItems as $orderItem) {
                $invoiceItem = InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'item_id' => $orderItem->item_id,
                    'quantity' => $orderItem->quantity,
                    'unit_price' => $orderItem->price,
                    'total_price' => $orderItem->total,
                ]);
                Log::info('InvoiceItem created:', $invoiceItem->toArray());
            }

            // Update the CustomerOrder status to 'invoicing'
            $jobOrder->status = 'invoicing';
            $jobOrder->save();
            DB::commit();

            session()->flash('success', 'Invoice and accounting entries created successfully!');
            return $this->redirect('/invoices');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error creating accounting entries: ' . $e->getMessage());
            // Optionally delete invoice if entry fails
            $invoice->delete();
        }
    }


    public function removeItem($id)
    {
        $jobOrderItem = JobOrderItem::find($id);

        if ($jobOrderItem) {
            $orderId = $jobOrderItem->order_id;
            $jobOrderItem->delete();

            $this->loadOrderDetails($orderId);
            session()->flash('success', 'Job order item Deleted!');
        } else {
            session()->flash('error', 'Job order item not found!');
        }
    }

    public function compleateDispatch($jobOrderId)
    {
        $isEligible = $this->isEligibleToDispatch($jobOrderId);
        if ($isEligible) {
            session()->flash('error', 'Dispatch has not been fully completed!');
            return;
        }

        $jobOrder = JobOrder::find($jobOrderId);
        $jobOrder->status = 'ready-to-invoice';
        $jobOrder->save();

        $this->status = 'ready-to-invoice';
        $this->statusText = StatusHelper::getJobOrderStatus('ready-to-invoice');

        $dispatchItems = DispatchItem::where('order_id', $jobOrderId)->get();
        $dispatchItemCount = $dispatchItems->count(); // or count($dispatchItems);

        //if automated TN activated - master data and
        if ($jobOrder->branch_id != $jobOrder->invoice_branch) {
            $stockTransferNote = StockTransferNote::create([
                'from_branch_id' => $jobOrder->invoice_branch,
                'to_branch_id' => $jobOrder->branch_id,
                'job_order_id' => $jobOrderId,
                'user_id' => $this->authUser->id,
                'quantity' => $dispatchItemCount,
                'remark' => 'system TN',
            ]);


            // Create stock_transfer_items based on dispatch_items
            foreach ($dispatchItems as $dispatchItem) {
                $jobOrderItem = JobOrderItem::find($dispatchItem->job_order_item_id)->first();
                StockTransferItem::create([
                    'stock_transfer_id' => $stockTransferNote->id,
                    'order_id' => $dispatchItem->order_id,
                    'order_item_id' => $dispatchItem->job_order_item_id,
                    'item_id' => $dispatchItem->item_id,
                    'quantity' => $dispatchItem->quantity,
                    'price' => $jobOrderItem->price / max($dispatchItem->quantity, 1), // prevent division by zero
                    'total' => $dispatchItem->total_amount,
                    'status' => $dispatchItem->status,
                ]);
            }
        }
        session()->flash('success', 'Dispatch successfully completed!');
        return $this->redirect('/job-orders', navigate: true);
    }

    /**
     * Update job order item quantity to match dispatched quantity
     * This removes the remaining balance from the job order
     */
    public function updateJobOrderQuantityToDispatched($jobOrderId)
    {
        DB::beginTransaction();
        
        try {
            $jobOrder = JobOrder::with('orderItems')->find($jobOrderId);
            
            if (!$jobOrder) {
                session()->flash('error', 'Job order not found.');
                return redirect()->back();
            }

            $totalAmount = 0;

            // Loop through all job order items
            foreach ($jobOrder->orderItems as $jobOrderItem) {
                // Get total dispatched quantity for this item
                $dispatchedQty = DB::table('dispatch_items')
                    ->where('job_order_item_id', $jobOrderItem->id)
                    ->sum('quantity');

                // Only update if there's a dispatched quantity
                if ($dispatchedQty > 0) {
                    // Update the job order item quantity to match dispatched quantity
                    $jobOrderItem->quantity = $dispatchedQty;
                    $jobOrderItem->total = $jobOrderItem->price * $dispatchedQty;
                    $jobOrderItem->status = 'dispatched';
                    $jobOrderItem->save();

                    $totalAmount += $jobOrderItem->total;
                }
            }

            // Update job order total amount
            $jobOrder->total_amount = $totalAmount;
            $jobOrder->save();

            // Update dispatch status
            $jobOrder->fresh();
            $jobOrder->updateDispatchStatus();

            // Update all dispatch items status to 'dispatched'
            DB::table('dispatch_items')
                ->where('order_id', $jobOrderId)
                ->update(['status' => 'dispatched']);

            // Update all dispatch notes status
            DB::table('dispatch_notes')
                ->where('job_order_id', $jobOrderId)
                ->update(['status' => 'dispatched']);

            DB::commit();

            // Reload order details
            $this->loadOrderDetails($jobOrderId);
            $this->status = $this->jobOrder->status;
            $this->statusText = StatusHelper::getJobOrderStatus($this->jobOrder->status);

            session()->flash('success', 'Job order quantity updated to match dispatched quantity successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to update job order quantity: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $bodyAttributes = 'x-data="{ page: \'jobOrderView\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
        x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
        :class="{\'dark bg-gray-900\': darkMode === true}"';

        return view('livewire.job.job-order-view')->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
