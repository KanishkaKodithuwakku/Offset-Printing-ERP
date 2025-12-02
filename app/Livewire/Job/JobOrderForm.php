<?php

namespace App\Livewire\Job;

use App\Helpers\NumberGenerator;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\DispatchItem;
use App\Models\Invoice;
use App\Models\Item;
use Livewire\Component;
use App\Models\JobOrder;
use App\Models\JobOrderItem;
use App\Models\Stock;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JobOrderForm extends Component
{

    public $branch_code, $dispatchedCount = 0, $status, $previous_status, $customer_po_number, $authUser, $jobOrderId, $job_number, $date_created, $customer_id, $branch_id, $description, $special_instruction, $printout, $plate_backing = false, $backing_qty = 1;
    public $job_done_by, $job_checked_by, $color_print, $delivery_date;
    public $searchCustomer;
    public $searchResultsCustomer;
    public $defaultResult = false;
    public $customer_name, $customer_address, $customer_phone, $customer_email, $customer_city;
    public $searchTerm = '';
    public $searchResults = [];
    public $dispatchedItems = [];
    public $stockProcess = true;
    public $total_amount;
    public $customerDue = 0;
    public array $branchOptions = [];
    public bool $showBranchDropdown = false;
    public ?int $selectedBranchId = null;
    public $hasDispatchedItems = false;
    public $hasPendingQtyUpdateRequest = false;
    public $isFullyDispatched = false;
    public $showUpdateQtyModal = false;
    public $showRequestQtyModal = false;
    public $showRequestSuccessModal = false;
    public $qtyUpdateApproved = false;
    public $isEditingDispatchQty = false; // Flag to prevent modal when editing dispatched quantity

    protected $rules = [
        'customer_id' => 'required|exists:customers,id',
        'description' => 'required|string|max:255',
        'special_instruction' => 'nullable|string|max:255',
        'job_done_by' => 'nullable|exists:users,id',
        'job_checked_by' => 'nullable|exists:users,id',
        'color_print' => 'nullable|boolean',
        // 'delivery_date' => 'nullable|date|after_or_equal:today',
    ];

    public function mount($jobOrderId = null)
    {
        $this->authUser = auth()->user();
        $this->branch_id = $this->authUser->branch_id;

        $this->branch_code = optional(Branch::find($this->authUser->branch_id))->branch_code ?? 'N/A';

        $this->delivery_date = now()->toDateString();
        $this->date_created = now()->toDateString();

        // Log component initialization
        Log::channel('job_order_log')->info('JobOrderForm component initialized', [
            'user_id' => $this->authUser->id,
            'user_name' => $this->authUser->name,
            'branch_id' => $this->branch_id,
            'branch_code' => $this->branch_code,
            'job_order_id' => $jobOrderId,
            'mode' => $jobOrderId ? 'edit' : 'create'
        ]);

        if ($jobOrderId) {

            $this->jobOrderId = $jobOrderId;
            $jobOrder = JobOrder::with('orderItems', 'customer', 'orderItems.item')->findOrFail($jobOrderId);

            // Log job order loading
            Log::channel('job_order_log')->info('Job order loaded for editing', [
                'job_order_id' => $jobOrderId,
                'job_number' => $jobOrder->job_number,
                'customer_id' => $jobOrder->customer_id,
                'current_status' => $jobOrder->status,
                'user_id' => $this->authUser->id
            ]);

            if (in_array($jobOrder->status, ['printing', 'dispatching', 'dispatched'])) {

                $jobOrder->previous_status = $jobOrder->status;
                if ($this->authUser->mode === 'dispatch') {
                    $jobOrder->status = 'paused';
                }
                $jobOrder->save();

                // Update the status property to reflect the change
                $this->status = $jobOrder->status;

                // Log status change
                Log::channel('job_order_log')->info('Job order status changed to paused', [
                    'job_order_id' => $jobOrderId,
                    'job_number' => $jobOrder->job_number,
                    'previous_status' => $jobOrder->previous_status,
                    'new_status' => $jobOrder->status,
                    'user_id' => $this->authUser->id,
                    'user_mode' => $this->authUser->mode
                ]);
            } else {
                // Set status property if not changed
                $this->status = $jobOrder->status;
            }
            
            // Store previous_status for view condition
            // When status is changed to 'paused', previous_status is set above
            $this->previous_status = $jobOrder->previous_status;
            
            // Debug: Log the status values for troubleshooting
            Log::channel('job_order_log')->debug('JobOrderForm mount - Status values', [
                'job_order_id' => $jobOrderId,
                'status' => $this->status,
                'previous_status' => $this->previous_status,
                'hasDispatchedItems' => $this->hasDispatchedItems,
                'user_mode' => $this->authUser->mode
            ]);

            //get the dispatched items with job order item relationship
            $this->dispatchedItems = DispatchItem::with('item', 'jobOrderItem')
                ->where('order_id', $jobOrderId)
                ->get();

            // Check if there are dispatched items
            $dispatchedCount = DB::table('dispatch_items')
                ->where('order_id', $jobOrderId)
                ->sum('quantity');
            $this->dispatchedCount = (int)$dispatchedCount;
            $this->hasDispatchedItems = $this->dispatchedCount > 0;
            
            // Check if all items are fully dispatched
            $this->isFullyDispatched = $this->checkIfFullyDispatched($jobOrderId);
            
            // Note: Removed validation that prevented dispatch users from editing fully dispatched job orders
            // Dispatch users can now continuously edit job order quantities even if all items are fully dispatched
            
            // Update debug log with correct values
            Log::channel('job_order_log')->debug('JobOrderForm mount - After checking dispatched items', [
                'job_order_id' => $jobOrderId,
                'status' => $this->status,
                'previous_status' => $this->previous_status,
                'dispatchedCount' => $this->dispatchedCount,
                'hasDispatchedItems' => $this->hasDispatchedItems,
                'isFullyDispatched' => $this->isFullyDispatched,
                'user_mode' => $this->authUser->mode
            ]);

            // Check if there's a pending quantity update request
            $this->hasPendingQtyUpdateRequest = DB::table('job_orders')
                ->where('id', $jobOrderId)
                ->where('qty_update_requested', true)
                ->exists();
            
            // Reset approval flag when loading (will be set to true after admin approves)
            $this->qtyUpdateApproved = false;

            // Job Order data
            $this->job_number = $jobOrder->job_number;
            $this->date_created = optional($jobOrder->date_created)->toDateString();
            $this->customer_id = $jobOrder->customer_id;
            $this->branch_id = $jobOrder->branch_id;
            $this->description = $jobOrder->description;
            $this->customer_po_number = $jobOrder->customer_po_number;
            $this->special_instruction = $jobOrder->special_instruction;
            $this->job_done_by = $jobOrder->job_done_by;
            $this->job_checked_by = $jobOrder->job_checked_by;
            $this->delivery_date = optional($jobOrder->delivery_date)->toDateString();
            $this->plate_backing = $jobOrder->plate_backing;
            $this->backing_qty = $jobOrder->backing_qty;
            $this->total_amount = $jobOrder->total_amount;

            // Customer details
            $this->customer_name = $jobOrder->customer->name ?? '';
            $this->customer_address = $jobOrder->customer->address ?? '';
            $this->customer_phone = $jobOrder->customer->phone ?? '';
            $this->customer_email = $jobOrder->customer->email ?? '';
            $this->customer_city = $jobOrder->customer->city ?? '';

            // Load job order items
            $this->jobOrderItems = $jobOrder->orderItems->map(function ($item) {
                //get the dispatch count
                $this->dispatchedCount = DB::table('dispatch_items')
                    ->where('order_id', $item->order_id)
                    ->where('item_id', $item->item_id)
                    ->sum('quantity');

                return [
                    'id' => $item->id,
                    'item_id' => $item->item_id,
                    'name' => $item->item->item_name ?? '',
                    'code' => $item->item->item_code ?? '',
                    'selling_price' => $item->price,
                    'purchase_price' => $item->item->purchase_price ?? 0,
                    'quantity' => $item->quantity - $this->dispatchedCount,
                    'dispatchedCount' => $this->dispatchedCount,
                    'total' => $item->total,
                ];
            })->toArray();
        }
        $this->branchOptions = Branch::all(['id', 'branch_code', 'branch_name'])->toArray();
    }



    public function updatedSearchTerm()
    {
        if (strlen($this->searchTerm) >= 1) {
            $items = Item::where(function ($query) {
                $query->where('item_name', 'like', "{$this->searchTerm}%")
                    ->orWhere('item_code', 'like', "{$this->searchTerm}%");
            })
                // ->when($this->authUser->mode !== 'admin', function ($query) {
                //     $query->where('branch_id', $this->authUser->branch_id);
                // })
                ->limit(5)
                ->get();

            $this->searchResults = $items->map(function ($item) {

                // $stockBalance = Stock::where('items_id', $item->id)->sum('quantity') ?? 0;
                // $stockBalance = Stock::when($this->authUser->mode !== 'admin', function ($query) {
                //     $query->where('branch_id', $this->authUser->branch_id);
                // })
                $stockBalance = Stock::where('branch_id', $this->authUser->branch_id)
                    ->where('items_id', $item->id)
                    ->sum('quantity') ?? 0;


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
    public $jobOrderItems  = [];
    public function addOrderItem($itemId)
    {
        $this->selectedItemId = $itemId;
        $item = Item::find($this->selectedItemId);

        // Log item addition attempt
        Log::channel('job_order_log')->info('Attempting to add item to job order', [
            'item_id' => $itemId,
            'item_name' => $item->item_name ?? 'Unknown',
            'user_id' => $this->authUser->id,
            'job_order_id' => $this->jobOrderId
        ]);

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
                    Log::channel('job_order_log')->warning('Stock insufficient for item quantity increase', [
                        'item_id' => $itemId,
                        'item_name' => $item->item_name,
                        'current_quantity' => $this->jobOrderItems[$existingItemKey]['quantity'],
                        'stock_balance' => $stockBalance,
                        'user_id' => $this->authUser->id
                    ]);
                    session()->flash('error', 'Quantity cannot exceed available stock since the available stock balance is  (' . $stockBalance . ')');
                } else {
                    $oldQuantity = $this->jobOrderItems[$existingItemKey]['quantity'];
                    $this->jobOrderItems[$existingItemKey]['quantity'] += 1; // Increment quantity by 1
                    // Update the total for the item based on the updated quantity
                    $this->jobOrderItems[$existingItemKey]['total'] = $this->jobOrderItems[$existingItemKey]['quantity'] * $this->jobOrderItems[$existingItemKey]['selling_price'];

                    Log::channel('job_order_log')->info('Item quantity increased', [
                        'item_id' => $itemId,
                        'item_name' => $item->item_name,
                        'old_quantity' => $oldQuantity,
                        'new_quantity' => $this->jobOrderItems[$existingItemKey]['quantity'],
                        'new_total' => $this->jobOrderItems[$existingItemKey]['total'],
                        'user_id' => $this->authUser->id
                    ]);
                }

                // If the item exists, update the quantity

            } else {
                // If the item does not exist, add it to the array
                if ($stockBalance == 0) {
                    Log::channel('job_order_log')->warning('Attempted to add item with zero stock', [
                        'item_id' => $itemId,
                        'item_name' => $item->item_name,
                        'stock_balance' => $stockBalance,
                        'user_id' => $this->authUser->id
                    ]);
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

                Log::channel('job_order_log')->info('New item added to job order', [
                    'item_id' => $itemId,
                    'item_name' => $item->item_name,
                    'item_code' => $item->item_code,
                    'selling_price' => $item->sales_price,
                    'quantity' => $stockBalance > 0 ? 1 : 0,
                    'stock_balance' => $stockBalance,
                    'user_id' => $this->authUser->id
                ]);
            }

            // Call calculateTotal or any other necessary function
            $this->calculateTotal();

            // Clear the search term and results
            $this->searchTerm = '';
            $this->searchResults = [];
        } else {
            Log::channel('job_order_log')->error('Item not found when attempting to add to job order', [
                'item_id' => $itemId,
                'user_id' => $this->authUser->id
            ]);
        }
    }


    public function updateTotal($index)
    {
        // Get the item from the job order items array
        $item = $this->jobOrderItems[$index];

        // Get the stock balance for the specific item
        $stockBalance = Stock::where('items_id', $item['item_id'])->sum('quantity') ?? 0;

        $orderItemsCount = JobOrderItem::where('order_id', $this->jobOrderId)
            ->where('item_id', $item['item_id'])
            ->value('quantity');

        $this->dispatchedCount = DB::table('dispatch_items')
            ->where('order_id', $this->jobOrderId)
            ->where('item_id', $item['item_id'])
            ->where('status', 'dispatched')
            ->sum('quantity');

        Log::channel('job_order_log')->info('Job order loaded for editing dispatched count', [
            'job_order_id' => $this->dispatchedCount
        ]);

        // Check if the quantity entered is greater than the stock balance
        if ($item['quantity'] > $stockBalance) {
            session()->flash('error', 'Quantity cannot exceed available stock since the available stock balance is  (' . $stockBalance . ')');
            // Optionally, reset the quantity to the stock balance to prevent overselling
            $this->jobOrderItems[$index]['quantity'] = $stockBalance;
            return;
        }

        // Ensure dispatched count doesn't exceed order items count
        if ($this->dispatchedCount > $orderItemsCount) {
            session()->flash('error', 'Dispatched count cannot exceed order items count');
            return;
        }

        Log::channel('job_order_log')->info('Item qty and dispatched count for validation', [
            'dispatched_count' => $this->dispatchedCount,
            'item_count' => $item['quantity']
        ]);

        // Handle negative quantities, ensure it's not less than dispatched count
        if ($item['quantity'] < 0) {
            // Calculate the remaining quantity that can still be decreased
            $remainingQuantity = $orderItemsCount - $this->dispatchedCount;

            // Validate that absolute value of quantity doesn't exceed the remaining quantity
            if (abs($item['quantity']) > $remainingQuantity) {
                session()->flash('error', 'Negative quantity cannot exceed the remaining quantity that can be reduced');
                return;
            }
        }

        // Update the total for the specific item based on the new quantity
        $this->jobOrderItems[$index]['total'] = abs($this->jobOrderItems[$index]['quantity'] * $this->jobOrderItems[$index]['selling_price']);

        // Optionally, recalculate the overall total amount
        $this->calculateTotal();
    }



    public function __updateTotal($index)
    {
        // Get the item from the job order items array
        $item = $this->jobOrderItems[$index];

        // Get the stock balance for the specific item
        $stockBalance = Stock::where('items_id', $item['item_id'])->sum('quantity') ?? 0;

        $orderItemsCount = JobOrderItem::where('order_id', $this->jobOrderId)
            ->where('item_id', $item['item_id'])
            ->value('quantity');

        $this->dispatchedCount = DB::table('dispatch_items')
            ->where('order_id', $this->jobOrderId)
            ->where('item_id', $item['item_id'])
            ->where('status', 'dispatched')
            ->sum('quantity');



        Log::channel('job_order_log')->info('Job order loaded for editing dispatched count', [
            'job_order_id' => $this->dispatchedCount
        ]);

        // Check if the quantity entered is greater than the stock balance
        if ($item['quantity'] > $stockBalance) {
            session()->flash('error', 'Quantity cannot exceed available stock since the available stock balance is  (' . $stockBalance . ')');
            // Optionally, reset the quantity to the stock balance to prevent overselling
            $this->jobOrderItems[$index]['quantity'] = $stockBalance;
            // Return to prevent further processing
            // return;
        }

        Log::channel('job_order_log')->info('Item qty and dispatched count for validation', [
            'dispatched_count' => $this->dispatchedCount,
            'item_count' => $item['quantity']
        ]);


        if ($item['quantity'] < $this->dispatchedCount) {
            $this->jobOrderItems[$index]['quantity'] = $item['quantity'];
            session()->flash('error', 'Quantity cannot exceed already dispatched count');

            // Optionally, reset the quantity to the stock balance to prevent overselling
            //$this->jobOrderItems[$index]['quantity'] = $this->dispatchedCount;

            // Return to prevent further processing
            // return;
        }

        // Update the total for the specific item based on the new quantity
        // Ensure quantity is always positive
        $this->jobOrderItems[$index]['total'] = abs($this->jobOrderItems[$index]['quantity'] * $this->jobOrderItems[$index]['selling_price']);

        // Optionally, recalculate the overall total amount
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->total_amount = abs(num: array_sum(array_column($this->jobOrderItems, 'total')));
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

            // If all items are fully dispatched and current status is 'dispatching' or 'paused', update to 'dispatched'
            if ($allItemsFullyDispatched) {
                $jobOrder = JobOrder::find($jobOrderId);
                if ($jobOrder && in_array($jobOrder->status, ['dispatching', 'paused'])) {
                    $previousStatus = $jobOrder->status;
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
                        'previous_status' => $previousStatus,
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


    public function assignCustomer($customerId)
    {
        // Find the customer using the provided customer ID
        $customer = Customer::find($customerId);
        if (!$customer) {
            return response()->json(['error' => 'Customer not found'], 404);
        }


        $this->customerDue =  Invoice::where('customer_id', $customerId)
            ->sum('total_amount');

        $this->customer_id = $customer->id;
        $this->searchResultsCustomer = [];
        $this->searchCustomer = $customer->name;
        $this->customer_name = $customer->name;
        $this->customer_address =  $customer->address;
        $this->customer_phone =  $customer->phone;
        $this->customer_email =  $customer->email;
        $this->customer_city =  $customer->city;
        return response()->json(['message' => 'Customer assigned successfully'], 200);
    }

    public function updatedSearchCustomer()
    {
        if (strlen($this->searchCustomer) >= 1) {
            $customer = Customer::where('status', 'active')
                ->where(function ($query) {
                    $query->where('name', 'like', "{$this->searchCustomer}%")
                        ->orWhere('email', 'like', "{$this->searchCustomer}%");
                })
                ->when($this->authUser->mode !== 'admin', function ($query) {
                    $query->where('branch_id', $this->branch_id);
                })
                ->limit(5)
                ->get();

            $this->searchResultsCustomer = $customer->map(function ($customer) {
                return [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'phone' => $customer->phone,
                    'city' => $customer->city,
                ];
            })->toArray();

            Log::info('Search Results: ' . json_encode($this->searchResultsCustomer));
        } else {
            $this->searchResultsCustomer = [];
            $this->defaultResult = false;
            Log::info('Search Results Cleared');
        }
    }


    public function save()
    {
        try {
            $this->validate();

            // Update mode
            if ($this->jobOrderId) {
                $jobOrder = JobOrder::findOrFail($this->jobOrderId);

                //Restore status if paused and user is admin
                if (
                    $jobOrder->status === 'paused' &&
                    ($this->authUser->mode === 'admin' || $this->authUser->mode === 'manager') &&
                    !empty($jobOrder->previous_status)
                ) {
                    // Update the status of the job order
                    $jobOrder->status = ($jobOrder->previous_status === 'dispatched') ? 'dispatching' : $jobOrder->previous_status;

                    // Update the status of JobOrderItem with the same logic
                    JobOrderItem::where('order_id', $this->jobOrderId)->update([  // use order_id instead of job order id for consistency
                        'status' => ($jobOrder->previous_status === 'dispatched') ? 'dispatching' : $jobOrder->previous_status
                    ]);

                    // Reset the previous status after updating
                    $jobOrder->previous_status = null;
                }

                // Update all other fields
                $jobOrder->update([
                    'customer_po_number' => $this->customer_po_number,
                    'user_id' => $this->authUser->id,
                    'customer_id' => $this->customer_id,
                    'branch_id' => $this->authUser->branch->id,
                    'description' => $this->description,
                    'special_instruction' => $this->special_instruction,
                    'job_done_by' => $this->job_done_by,
                    'job_checked_by' => $this->job_checked_by,
                    'delivery_date' => $this->delivery_date,
                    'plate_backing' => $this->plate_backing,
                    'backing_qty' => $this->backing_qty,
                    'total_amount' => $this->total_amount,
                    'status' => $jobOrder->status, // already updated above
                    'previous_status' => $jobOrder->previous_status, // cleared above
                ]);
            }
            // Create mode
            else {
                $jobNumber = NumberGenerator::generateJobOrderNumber($this->authUser->branch_id);
                $jobOrder = JobOrder::create([
                    'job_number' => $jobNumber,
                    'date_created' => Carbon::now(),
                    'user_id' => $this->authUser->id,
                    'customer_id' => $this->customer_id,
                    'branch_id' => $this->authUser->branch->id,
                    'invoice_branch' => $this->selectedBranchId,
                    'description' => $this->description,
                    'customer_po_number' => $this->customer_po_number,
                    'special_instruction' => $this->special_instruction,
                    'job_done_by' => $this->job_done_by,
                    'job_checked_by' => $this->job_checked_by,
                    'delivery_date' => $this->delivery_date,
                    'plate_backing' => $this->plate_backing,
                    'backing_qty' => $this->backing_qty,
                    'total_amount' => $this->total_amount,
                    'status' => 'pending'
                ]);
            }

            // Check if any item remaining quantity becomes 0 (all items dispatched) before saving
            // Skip this check if:
            // 1. Admin has already approved the request (qtyUpdateApproved = true), OR
            // 2. There's no pending request (hasPendingQtyUpdateRequest = false)
            $allItemsDispatched = false;
            $shouldCheckModal = true;
            
            if ($this->jobOrderId) {
                // Skip modal check if:
                // - Admin has already approved OR if there's no pending request
                // - User is currently editing dispatched quantity (not saving)
                if ($this->authUser->mode === 'admin') {
                    if ($this->qtyUpdateApproved || !$this->hasPendingQtyUpdateRequest) {
                        $shouldCheckModal = false;
                    }
                }
                
                // Don't show modal if user is just editing dispatched quantity
                if ($this->isEditingDispatchQty) {
                    $shouldCheckModal = false;
                }
                
                if ($shouldCheckModal) {
                    foreach ($this->jobOrderItems as $item) {
                        // Get dispatched count for the item
                        $dispatchedCount = DB::table('dispatch_items')
                            ->where('order_id', $jobOrder->id)
                            ->where('item_id', $item['item_id'])
                            ->sum('quantity');

                        // Get job order item count
                        $jobOrderItemCount = JobOrderItem::where('order_id', $jobOrder->id)->where('item_id', $item['item_id'])->value('quantity');

                        // Calculate final quantity based on the form input
                        $quantity = $item['quantity'];
                        if (($jobOrderItemCount ?? 0) - $dispatchedCount != $item['quantity']) {
                            if ($item['quantity'] >= 0) {
                                $quantity = $item['quantity'] + $dispatchedCount;
                            } else {
                                $currentRemaining = ($jobOrderItemCount ?? 0) - $dispatchedCount;
                                $newRemaining = $currentRemaining + $item['quantity'];
                                $quantity = $newRemaining + $dispatchedCount;
                            }
                        } else {
                            $quantity = $jobOrderItemCount;
                        }

                        // Check if remaining quantity becomes 0 (all items dispatched)
                        // Remaining = final quantity - dispatched count
                        $remainingQty = $quantity - $dispatchedCount;
                        if ($remainingQty == 0 && $dispatchedCount > 0) {
                            $allItemsDispatched = true;
                            break;
                        }
                    }

                    // If all items are dispatched, show modal instead of saving
                    if ($allItemsDispatched) {
                        if ($this->authUser->mode === 'dispatch') {
                            // Show request modal for dispatch users
                            $this->showRequestQtyModal = true;
                            return;
                        } elseif ($this->authUser->mode === 'admin') {
                            // Show update modal for admin users
                            $this->showUpdateQtyModal = true;
                            return;
                        }
                    }
                }
            }

            // Validate and save items
            foreach ($this->jobOrderItems as $item) {

                // Get dispatched count for the item
                $dispatchedCount = DB::table('dispatch_items')
                    ->where('order_id', $jobOrder->id)
                    ->where('item_id', $item['item_id'])
                    ->sum('quantity');

                // Get job order item count
                $jobOrderItemCount = JobOrderItem::where('order_id', $jobOrder->id)->where('item_id', $item['item_id'])->value('quantity');

                $quantity = $item['quantity'];

                // Handle validation for negative quantities
                if ($item['quantity'] < 0) {
                    // Calculate the remaining quantity that can still be reduced
                    $remainingQuantity = $jobOrderItemCount - $dispatchedCount;

                    // Ensure the absolute value of the quantity does not exceed the remaining quantity
                    if (abs($item['quantity']) > $remainingQuantity) {
                        session()->flash('error', 'Negative quantity cannot exceed the remaining quantity that can be reduced');
                        return;
                    }
                }

                // Proceed with saving the item if validation is passed
                if (($jobOrderItemCount ?? 0) - $dispatchedCount != $item['quantity']) {

                    // The form shows remaining quantity (total - dispatched)
                    // If user enters positive number: they want that as the new remaining quantity
                    // If user enters negative number: they want to reduce the remaining by that amount
                    if ($item['quantity'] >= 0) {
                        // Positive: set remaining to entered value
                        $quantity = $item['quantity'] + $dispatchedCount;
                    } else {
                        // Negative: reduce remaining by entered amount (only for existing items)
                        $currentRemaining = ($jobOrderItemCount ?? 0) - $dispatchedCount;
                        $newRemaining = $currentRemaining + $item['quantity']; // Adding negative reduces it
                        $quantity = $newRemaining + $dispatchedCount;
                    }

                    // Prevent updating to 0 if there are no dispatched items
                    if ($quantity == 0 && $dispatchedCount == 0) {
                        $itemName = $item['name'] ?? 'Item';
                        session()->flash('error', "Cannot update quantity to 0 for '{$itemName}'. At least one item must be dispatched before the quantity can be set to 0.");
                        return;
                    }

                    // Calculate and validate that total price matches price * quantity
                    $expectedTotal = abs($quantity * $item['selling_price']);
                    $providedTotal = abs($item['total']);
                    
                    // Check if there's a mismatch (allow small floating point differences of 0.01)
                    if (abs($expectedTotal - $providedTotal) > 0.01) {
                        $itemName = $item['name'] ?? 'Item';
                        // Build a clearer message explaining the calculation
                        $quantityInfo = '';
                        if ($dispatchedCount > 0) {
                            $quantityInfo = " (Entered remaining: {$item['quantity']} + Dispatched: {$dispatchedCount} = Final: {$quantity})";
                        }
                        // Show a warning but continue - the correct total will be saved
                        session()->flash('warning', "Total price auto-corrected for '{$itemName}'. Provided: " . number_format($providedTotal, 2) . ", Corrected to: " . number_format($expectedTotal, 2) . " based on Price × Final Quantity = " . number_format($item['selling_price'], 2) . " × " . $quantity . $quantityInfo);
                    }

                    try {
                        $jobOrderItem = JobOrderItem::updateOrCreate([
                            'order_id' => $jobOrder->id,
                            'item_id' => $item['item_id'],
                        ], [
                            'quantity' => $quantity,
                            'price' => $item['selling_price'],
                            'total' => $expectedTotal, // Use calculated total instead of provided total
                        ]);
                        // Optional: Log or debug to check if it worked
                        Log::info('JobOrderItem saved/updated', [
                            'order_id' => $jobOrder->id,
                            'item_id' => $item['item_id'],
                            'quantity' => $item['quantity'],
                            'price' => $item['selling_price'],
                            'total' => $item['total'],
                            'jobOrderItem_id' => $jobOrderItem->id ?? null,
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Error saving JobOrderItem', [
                            'order_id' => $jobOrder->id,
                            'item_id' => $item['item_id'],
                            'error' => $e->getMessage(),
                        ]);
                        session()->flash('error', 'Error saving job order item: ' . $e->getMessage());
                    }
                }
            }

            // Check and update dispatch status if admin is editing and job order exists
            if ($this->jobOrderId && $this->authUser->mode === 'admin') {
                $this->checkAndUpdateDispatchStatus($this->jobOrderId);
            }

            session()->flash('success', $this->jobOrderId ? 'Job successfully updated.' : 'Job successfully created.');

            $this->reset();
            return $this->redirect('/job-orders', navigate: true);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::channel('job_order_log')->info('Validation Errors', $e->errors());
            session()->flash('error', collect($e->errors())->flatten()->first());
        } catch (\Exception $e) {
            Log::channel('job_order_log')->error('Error saving job: ' . $e->getMessage(), [
                'error_message' => $e->getMessage(),
                'jobOrderItems' => $this->jobOrderItems,
                'customer_id' => $this->customer_id,
            ]);

            session()->flash('error', 'Error saving job: ' . $e->getMessage());
        }
    }


    public function __save()
    {


        try {
            $this->validate();



            // Update mode
            if ($this->jobOrderId) {
                $jobOrder = JobOrder::findOrFail($this->jobOrderId);

                //Restore status if paused and user is admin
                if (
                    $jobOrder->status === 'paused' &&
                    ($this->authUser->mode === 'admin' || $this->authUser->mode === 'manager') &&
                    !empty($jobOrder->previous_status)
                ) {
                    // Update the status of the job order
                    $jobOrder->status = ($jobOrder->previous_status === 'dispatched') ? 'dispatching' : $jobOrder->previous_status;

                    // Update the status of JobOrderItem with the same logic
                    JobOrderItem::where('order_id', $this->jobOrderId)->update([  // use order_id instead of job order id for consistency
                        'status' => ($jobOrder->previous_status === 'dispatched') ? 'dispatching' : $jobOrder->previous_status
                    ]);

                    // Reset the previous status after updating
                    $jobOrder->previous_status = null;
                }


                // Update all other fields
                $jobOrder->update([
                    'customer_po_number' => $this->customer_po_number,
                    'user_id' => $this->authUser->id,
                    'customer_id' => $this->customer_id,
                    'branch_id' => $this->authUser->branch->id,
                    'description' => $this->description,
                    'special_instruction' => $this->special_instruction,
                    'job_done_by' => $this->job_done_by,
                    'job_checked_by' => $this->job_checked_by,
                    'delivery_date' => $this->delivery_date,
                    'plate_backing' => $this->plate_backing,
                    'backing_qty' => $this->backing_qty,
                    'total_amount' => $this->total_amount,
                    'status' => $jobOrder->status, // already updated above
                    'previous_status' => $jobOrder->previous_status, // cleared above
                ]);
            }
            // Create mode
            else {
                $jobNumber = NumberGenerator::generateJobOrderNumber($this->authUser->branch_id);
                $jobOrder = JobOrder::create([
                    'job_number' => $jobNumber,
                    'date_created' => Carbon::now(),
                    'user_id' => $this->authUser->id,
                    'customer_id' => $this->customer_id,
                    'branch_id' => $this->authUser->branch->id,
                    'invoice_branch' => $this->selectedBranchId,
                    'description' => $this->description,
                    'customer_po_number' => $this->customer_po_number,
                    'special_instruction' => $this->special_instruction,
                    'job_done_by' => $this->job_done_by,
                    'job_checked_by' => $this->job_checked_by,
                    'delivery_date' => $this->delivery_date,
                    'plate_backing' => $this->plate_backing,
                    'backing_qty' => $this->backing_qty,
                    'total_amount' => $this->total_amount,
                    'status' => 'pending'
                ]);
            }


            //    if ($this->authUser->mode != 'admin') {

            // Common logic: save items
            foreach ($this->jobOrderItems as $item) {

                $dispatchedCount = DB::table('dispatch_items')
                    ->where('order_id', $this->jobOrderId)
                    ->where('item_id', $item['item_id'])
                    ->sum('quantity');

                $jobOrderItemCount = JobOrderItem::where('order_id', $jobOrder->id)->where('item_id', $item['item_id'])->value('quantity');

                $quantity = $item['quantity'];

                //dd();

                if ($jobOrderItemCount - $dispatchedCount != $item['quantity']) {

                    if ($jobOrderItemCount > 0) {
                        $quantity = $jobOrderItemCount + $item['quantity'];
                    }

                    //dd($quantity);

                    try {
                        $jobOrderItem = JobOrderItem::updateOrCreate([
                            'order_id' => $jobOrder->id,
                            'item_id' => $item['item_id'],
                        ], [
                            'quantity' =>   $quantity,
                            'price' => $item['selling_price'],
                            'total' => $item['total'],
                        ]);
                        // Optional: Log or debug to check if it worked
                        Log::info('JobOrderItem saved/updated', [
                            'order_id' => $jobOrder->id,
                            'item_id' => $item['item_id'],
                            'quantity' => $item['quantity'],
                            'price' => $item['selling_price'],
                            'total' => $item['total'],
                            'jobOrderItem_id' => $jobOrderItem->id ?? null,
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Error saving JobOrderItem', [
                            'order_id' => $jobOrder->id,
                            'item_id' => $item['item_id'],
                            'error' => $e->getMessage(),
                        ]);
                        session()->flash('error', 'Error saving job order item: ' . $e->getMessage());
                    }
                }
            }
            // }

            // Check and update dispatch status if admin is editing and job order exists
            if ($this->jobOrderId && $this->authUser->mode === 'admin') {
                $this->checkAndUpdateDispatchStatus($this->jobOrderId);
            }

            session()->flash('success', $this->jobOrderId ? 'Job successfully updated.' : 'Job successfully created.');

            $this->reset();
            return $this->redirect('/job-orders', navigate: true);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::channel('job_order_log')->info('Validation Errors', $e->errors());
            session()->flash('error', collect($e->errors())->flatten()->first());
        } catch (\Exception $e) {

            Log::channel('job_order_log')->error('Error saving job: ' . $e->getMessage(), [
                'error_message' => $e->getMessage(),
                'jobOrderItems' => $this->jobOrderItems,
                'customer_id' => $this->customer_id,
            ]);

            session()->flash('error', 'Error saving job: ' . $e->getMessage());
        }
    }





    public function generateJobNumber()
    {
        $today = Carbon::today()->format('Ymd');  // Get today's date in 'YYYYMMDD' format
        $userId = auth()->user()->id;  // Get the current authenticated user's ID

        // Get the last job number created today for this user
        $lastJob = JobOrder::where('job_number', 'like', "{$userId}-{$today}-%")
            ->orderBy('job_number', 'desc')
            ->first();

        // Check if there is a last job for today and increment the number
        if ($lastJob) {
            // Get the last three digits of the job number and increment it
            $lastNumber = substr($lastJob->job_number, -3); // Extract last 3 digits
            $nextNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);  // Increment and pad with zeroes
        } else {
            // If no jobs for today, start with 001
            $nextNumber = '001';
        }

        // Create the job number using user ID, today's date, and the next sequential number
        $jobNumber = "{$userId}-{$today}-{$nextNumber}";

        return $jobNumber;
    }



    // public function removeItem($index, $id)
    // {
    //     $jobOrderItem = JobOrderItem::find($id);

    //     if (!$jobOrderItem) {
    //         // session()->flash('error', 'Item not found.');
    //         // return;
    //         $this->removeFromJobOrderItems($index);
    //     } else {
    //         // Check if the item is already dispatched
    //         $count = DispatchItem::where('job_order_item_id', $jobOrderItem->id)->count();
    //         if ($count > 0) {
    //             session()->flash('error', 'Cannot delete the item since it is already dispatched!');
    //             return;
    //         }
    //         // Proceed to delete
    //         $jobOrderItem->delete();
    //         $this->removeFromJobOrderItems($index);
    //     }
    // }


    public function removeItem(int $index, int $id): void
    {
        // Try to find the job order item.
        $jobOrderItem = JobOrderItem::find($id);

        // If the item doesn't exist in the database,
        // remove it from the local array and exit.
        if (!$jobOrderItem) {
            $this->removeFromJobOrderItems($index);
            return;
        }

        // Check if the item has already been dispatched.
        // $dispatchedCount = DispatchItem::where('job_order_item_id', $jobOrderItem->id)->count();
        // if ($dispatchedCount > 0) {
        //     session()->flash('error', 'Cannot delete the item since it is already dispatched!');
        //     return;
        // }

        // Delete the item from the database.
        $jobOrderItem->delete();

        // log that the item was removed.
        Log::channel('job_order_log')->info("Job Order item #{$id} was removed by {$this->authUser->name}.");

        // Remove the item from the local array.
        $this->removeFromJobOrderItems($index);

        // success message.
        session()->flash('success', 'Item successfully removed.');
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


    public function selectBranch(int $branchId): void
    {
        $this->selectedBranchId = $branchId;
    }

    public function changeWorkingBranch(): void
    {
        $this->branch_id = $this->selectedBranchId;

        $branch = Branch::find($this->selectedBranchId);
        $this->branch_code = $branch ? $branch->branch_code : null;

        $this->showBranchDropdown = false;

        session()->flash('success', 'Branch updated successfully. Stock will be adjusted accordingly.');
    }

    /**
     * Check if all job order items are fully dispatched
     */
    private function checkIfFullyDispatched($jobOrderId)
    {
        $jobOrderItems = JobOrderItem::where('order_id', $jobOrderId)->get();
        
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
    public function requestQtyUpdateToAdmin($jobOrderId)
    {
        try {
            $jobOrder = JobOrder::find($jobOrderId);
            
            if (!$jobOrder) {
                session()->flash('error', 'Job order not found.');
                $this->showRequestQtyModal = false;
                return redirect()->back();
            }

            // Check if there are dispatched items
            $dispatchedCount = DB::table('dispatch_items')
                ->where('order_id', $jobOrderId)
                ->sum('quantity');

            if ($dispatchedCount == 0) {
                session()->flash('error', 'No dispatched items found. Cannot request quantity update.');
                $this->showRequestQtyModal = false;
                return redirect()->back();
            }

            // Set the request flag
            $jobOrder->qty_update_requested = true;
            $jobOrder->qty_update_requested_by = $this->authUser->id;
            $jobOrder->qty_update_requested_at = now();
            $jobOrder->save();

            $this->hasPendingQtyUpdateRequest = true;
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
    public function updateJobOrderQuantityToDispatched($jobOrderId)
    {
        // Check if user is admin
        if ($this->authUser->mode !== 'admin') {
            session()->flash('error', 'Only administrators can update job order quantities.');
            return redirect()->back();
        }

        DB::beginTransaction();
        
        try {
            $jobOrder = JobOrder::with('orderItems')->find($jobOrderId);
            
            if (!$jobOrder) {
                session()->flash('error', 'Job order not found.');
                return redirect()->back();
            }

            $totalAmount = 0;
            $hasAnyDispatchedItems = false;

            // Loop through all job order items
            foreach ($jobOrder->orderItems as $jobOrderItem) {
                // Get total dispatched quantity for this item
                $dispatchedQty = DB::table('dispatch_items')
                    ->where('job_order_item_id', $jobOrderItem->id)
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

            // Consolidate dispatch items: merge multiple dispatch items for the same job_order_item_id into one
            // Group by job_order_item_id and merge quantities
            $dispatchItemsByJobOrderItem = DB::table('dispatch_items')
                ->where('order_id', $jobOrderId)
                ->select('job_order_item_id', DB::raw('SUM(quantity) as total_quantity'), DB::raw('SUM(total_amount) as total_amount_sum'))
                ->groupBy('job_order_item_id')
                ->get();

            foreach ($dispatchItemsByJobOrderItem as $group) {
                // Get all dispatch items for this job_order_item_id
                $dispatchItems = DispatchItem::where('order_id', $jobOrderId)
                    ->where('job_order_item_id', $group->job_order_item_id)
                    ->orderBy('id')
                    ->get();

                if ($dispatchItems->count() > 1) {
                    // Keep the first one and update it with total quantity
                    $firstItem = $dispatchItems->first();
                    $firstItem->quantity = $group->total_quantity;
                    $firstItem->total_amount = $group->total_amount_sum;
                    $firstItem->status = 'dispatched';
                    $firstItem->save();

                    // Delete the rest
                    $dispatchItems->skip(1)->each(function ($item) {
                        $item->delete();
                    });
                } else {
                    // Just update status if only one record
                    $firstItem = $dispatchItems->first();
                    if ($firstItem) {
                        $firstItem->status = 'dispatched';
                        $firstItem->save();
                    }
                }
            }

            // Update all dispatch notes status
            DB::table('dispatch_notes')
                ->where('job_order_id', $jobOrderId)
                ->update(['status' => 'dispatched']);

            DB::commit();

            // Reload dispatched items
            $this->hasPendingQtyUpdateRequest = false;
            $this->showUpdateQtyModal = false;
            $this->qtyUpdateApproved = true; // Mark as approved to enable Save Order button

            // Don't call mount() as it resets qtyUpdateApproved
            // Just reload the necessary data
            $this->dispatchedItems = DispatchItem::with('item', 'jobOrderItem')
                ->where('order_id', $jobOrderId)
                ->get();
            
            // Reload job order to get updated status
            $jobOrder = JobOrder::with('orderItems', 'customer', 'orderItems.item')->find($jobOrderId);
            $this->status = $jobOrder->status;
            $this->previous_status = $jobOrder->previous_status;
            
            // Recalculate dispatched count
            $dispatchedCount = DB::table('dispatch_items')
                ->where('order_id', $jobOrderId)
                ->sum('quantity');
            $this->dispatchedCount = (int)$dispatchedCount;
            $this->hasDispatchedItems = $this->dispatchedCount > 0;
            $this->isFullyDispatched = $this->checkIfFullyDispatched($jobOrderId);

            session()->flash('success', 'Job order quantity updated to match dispatched quantity successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to update job order quantity: ' . $e->getMessage());
        }
    }

    /**
     * Cancel the pending quantity update request
     * Can be called by admin or dispatch user (who sent the request)
     */
    public function cancelQtyUpdateRequest()
    {
        if (!$this->jobOrderId) {
            session()->flash('error', 'Job order not found.');
            return;
        }

        DB::beginTransaction();
        
        try {
            $jobOrder = JobOrder::find($this->jobOrderId);
            
            if (!$jobOrder) {
                session()->flash('error', 'Job order not found.');
                return;
            }

            // Check if there's actually a pending request
            if (!$jobOrder->qty_update_requested) {
                session()->flash('error', 'No pending request to cancel.');
                DB::rollBack();
                return;
            }

            // Clear the request flags
            $jobOrder->qty_update_requested = false;
            $jobOrder->qty_update_requested_by = null;
            $jobOrder->qty_update_requested_at = null;
            $jobOrder->save();

            DB::commit();

            // Update component state
            $this->hasPendingQtyUpdateRequest = false;
            $this->qtyUpdateApproved = false;

            session()->flash('success', 'Dispatch request has been cancelled successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to cancel request: ' . $e->getMessage());
        }
    }

    /**
     * Update dispatch item quantity
     * When user edits the dispatched quantity, it adds to existing dispatched quantity
     * and updates the job order item quantity accordingly
     */
    public function updateDispatchItemQuantity($dispatchItemId, $newQuantity)
    {
        // Set flag to prevent modal from appearing during dispatch quantity edit
        $this->isEditingDispatchQty = true;
        
        DB::beginTransaction();
        
        try {
            // Load dispatch item with item relationship
            $dispatchItem = DispatchItem::with('item')->find($dispatchItemId);
            
            if (!$dispatchItem) {
                session()->flash('error', 'Dispatch item not found.');
                DB::rollBack();
                $this->mount($this->jobOrderId);
                return;
            }

            // Validate and convert quantity
            if (empty($newQuantity) || $newQuantity === '') {
                session()->flash('error', 'Quantity cannot be empty.');
                DB::rollBack();
                $this->mount($this->jobOrderId);
                return;
            }

            $newQuantity = (int)$newQuantity;

            if ($newQuantity < 0) {
                session()->flash('error', 'Quantity cannot be negative.');
                DB::rollBack();
                $this->mount($this->jobOrderId);
                return;
            }

            // Get the job order item
            $jobOrderItem = JobOrderItem::find($dispatchItem->job_order_item_id);
            if (!$jobOrderItem) {
                session()->flash('error', 'Job order item not found.');
                DB::rollBack();
                $this->mount($this->jobOrderId);
                return;
            }

            // Get current total dispatched quantity for this job order item
            $currentTotalDispatched = DB::table('dispatch_items')
                ->where('job_order_item_id', $dispatchItem->job_order_item_id)
                ->sum('quantity');

            // The new quantity entered represents the TARGET job order quantity, not the dispatched quantity
            // Example: If current dispatched is 12, and user enters 14:
            // - Job order item qty should become 14
            // - Dispatched should remain 12 (not change)
            // - Balance to dispatch = 14 - 12 = 2
            $oldDispatchItemQty = $dispatchItem->quantity;
            
            // Don't update the dispatch item quantity - keep it as is
            // The field value represents the target job order quantity, not dispatch quantity
            // The actual dispatched quantity ($currentTotalDispatched) remains unchanged
            
            // Don't update dispatch item - the field represents target job order quantity, not dispatch quantity
            // The actual dispatched quantity should remain unchanged
            
            // Update job order item quantity to the new target quantity
            // Example: If user enters 14, job order qty becomes 14
            // Dispatched remains 12, so balance = 14 - 12 = 2
            $oldJobOrderItemQty = $jobOrderItem->quantity;
            $jobOrderItem->quantity = $newQuantity; // Set to the new target quantity
            $jobOrderItem->total = $jobOrderItem->price * $newQuantity;
            
            // If job order item was fully dispatched and now has a balance, change status back
            if ($jobOrderItem->status === 'dispatched' && $newQuantity > $currentTotalDispatched) {
                // There's now a balance to dispatch, so change status back to pending
                $jobOrderItem->status = 'pending';
            }
            $jobOrderItem->save();

            // Update job order total amount and status
            $jobOrder = JobOrder::find($this->jobOrderId);
            if ($jobOrder) {
                $totalAmount = JobOrderItem::where('order_id', $this->jobOrderId)->sum('total');
                $jobOrder->total_amount = $totalAmount;
                
                // Check if there's now a balance to dispatch (job order qty > dispatched qty)
                $allItemsFullyDispatched = true;
                $jobOrderItems = JobOrderItem::where('order_id', $this->jobOrderId)->get();
                foreach ($jobOrderItems as $item) {
                    $itemDispatchedQty = DB::table('dispatch_items')
                        ->where('job_order_item_id', $item->id)
                        ->sum('quantity');
                    if ($itemDispatchedQty < $item->quantity) {
                        $allItemsFullyDispatched = false;
                        break;
                    }
                }
                
                // If status was 'dispatched' and there's now a balance, change back to 'dispatching'
                if ($jobOrder->status === 'dispatched' && !$allItemsFullyDispatched) {
                    $jobOrder->status = 'dispatching';
                    // Update previous_status to remember it was dispatched
                    if (!$jobOrder->previous_status || $jobOrder->previous_status === 'dispatched') {
                        $jobOrder->previous_status = 'dispatched';
                    }
                } else {
                    // Use the model's updateDispatchStatus method to handle status updates
                    $jobOrder->fresh(); // Reload relationships
                    $jobOrder->updateDispatchStatus();
                }
                
                $jobOrder->save();
            }

            DB::commit();

            // Reload dispatched items to reflect changes
            $this->dispatchedItems = DispatchItem::with('item', 'jobOrderItem')
                ->where('order_id', $this->jobOrderId)
                ->get();

            // Reload job order items to reflect updated quantities
            $jobOrder = JobOrder::with('orderItems', 'orderItems.item')->find($this->jobOrderId);
            if ($jobOrder) {
                // Update component status to reflect changes
                $this->status = $jobOrder->status;
                $this->previous_status = $jobOrder->previous_status;
                
                $this->jobOrderItems = $jobOrder->orderItems->map(function ($item) {
                    $dispatchedCount = DB::table('dispatch_items')
                        ->where('job_order_item_id', $item->id)
                        ->sum('quantity');

                    return [
                        'id' => $item->id,
                        'item_id' => $item->item_id,
                        'name' => $item->item->item_name ?? '',
                        'code' => $item->item->item_code ?? '',
                        'selling_price' => $item->price,
                        'purchase_price' => $item->item->purchase_price ?? 0,
                        'quantity' => $item->quantity - $dispatchedCount, // This will show the balance (e.g., 14-12=2)
                        'dispatchedCount' => $dispatchedCount,
                        'total' => $item->total,
                    ];
                })->toArray();
            }

            // Recalculate dispatched count
            $dispatchedCount = DB::table('dispatch_items')
                ->where('order_id', $this->jobOrderId)
                ->sum('quantity');
            $this->dispatchedCount = (int)$dispatchedCount;
            $this->hasDispatchedItems = $this->dispatchedCount > 0;
            $this->isFullyDispatched = $this->checkIfFullyDispatched($this->jobOrderId);

            // Ensure modals are closed - this is just an edit, not a save action
            $this->showRequestQtyModal = false;
            $this->showUpdateQtyModal = false;
            $this->showRequestSuccessModal = false;
            
            // Reset the flag after successful update
            $this->isEditingDispatchQty = false;

            session()->flash('success', 'Job order quantity updated successfully from ' . $oldJobOrderItemQty . ' to ' . $newQuantity . '. Balance to dispatch: ' . ($newQuantity - $currentTotalDispatched) . '.');
        } catch (\Exception $e) {
            DB::rollBack();
            // Reset the flag even on error
            $this->isEditingDispatchQty = false;
            Log::channel('job_order_log')->error('Error updating dispatch item quantity', [
                'dispatch_item_id' => $dispatchItemId,
                'new_quantity' => $newQuantity,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Failed to update dispatch item quantity: ' . $e->getMessage());
            // Reload to reset on error
            $this->mount($this->jobOrderId);
        }
    }

    public function render()
    {
        $bodyAttributes = 'x-data="{ page: \'jobOrder\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
        x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
        :class="{\'dark bg-gray-900\': darkMode === true}"';

        $customers = Customer::where('status', 'active')->get();
        $users = User::all();

        return view('livewire.job.job-order', ['customers' =>  $customers, 'users' => $users])->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
