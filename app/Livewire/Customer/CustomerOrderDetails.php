<?php

namespace App\Livewire\Customer;

use App\Helpers\NumberGenerator;
use App\Models\Customer;
use App\Models\CustomerOrder;
use App\Models\CustomerOrderItem;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Item;
use App\Models\Stock;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class CustomerOrderDetails extends Component
{
    public $searchTerm = '';
    public $sortColumn = 'id';
    public $sortOrder = 'desc';
    public $orderId;
    public $customer_id;
    public $orderItems = [];
    public $searchResults = [];
    public $payment_method = 'cash';
    public $total_amount = 0;
    public $status = 'plan';
    public $customerOrder;
    public $customer_name;



    public $items = [];
    public function updatedSearchTerm()
    {
        if (strlen($this->searchTerm) > 1) {
            $items = Item::where('item_name', 'like', "%{$this->searchTerm}%")
                ->orWhere('item_code', 'like', "%{$this->searchTerm}%")
                ->limit(5)
                ->get();

            $this->searchResults = $items->map(function ($item) {

                $stockBalance = Stock::where('items_id', $item->id)->sum('quantity') ?? 0;

                return [
                    'id' => $item->id,
                    'item_name' => $item->item_name,
                    'item_code' => $item->item_code,
                    'selling_price' => $item->sales_price,
                    'purchase_price' => $item->purchase_price,
                    'brands_id' => $item->brands_id,
                    'quantity' => 1,
                    'stock_balance' => $stockBalance,
                ];
            })->toArray();
        } else {
            $this->searchResults = [];
        }
    }

    public function addOrderItem($itemId)
    {
        $item = Item::find($itemId);
        if ($item) {
            $stockBalance = Stock::where('items_id', $item->id)->sum('quantity') ?? 0;

            CustomerOrderItem::updateOrCreate(
                ['order_id' => $this->orderId, 'item_id' => $item->id],
                [
                    'quantity' => $stockBalance ? 1 : 0,
                    'price' => $item->sales_price,
                    'total' => $stockBalance ? $item->sales_price : 0,
                ]
            );
            $this->searchTerm = '';
            $this->searchResults = [];
        }
        $this->getOrderItems($this->orderId);
        $this->calculateTotal();
    }


    public function generateInvoiceFromOrder($orderId)
    {
        // Fetch the order with its related customer and order items
        $customerOrder = CustomerOrder::with('orderItems')->find($orderId);
        $this->payment_method = $customerOrder->payment_method;

        if (!$customerOrder) {
            session()->flash('error', 'Order not found!');
            return;
        }

        // Generate invoice number (You can modify this logic as needed)
        $invoiceNumber =  NumberGenerator::generateCode('invoices', 'INV', 'invoice_number', 5);

        // Create the invoice record
        $invoice = Invoice::create([
            'order_id' => $customerOrder->id,
            'order_type' => CustomerOrder::class,  // Set order_type to the model class name
            'customer_id' => $customerOrder->customer_id,
            'invoice_number' => $invoiceNumber,
            'status' => 'invoiced',  // Set the invoice status as 'released' or modify as needed
            'total_amount' => $customerOrder->total_amount, // Use the order's total amount
            'advance_payment' => $customerOrder->advance_payment,
            'payment_method' => $customerOrder->payment_method,
            'bank_name' => $customerOrder->bank_name,
            'cheque_no' => $customerOrder->cheque_no,
            'cheque_realization_date' => $customerOrder->cheque_realization_date,
            'invoice_details' => null,  // You can add any extra invoice details if needed
        ]);



        // Process the order items and insert them into the invoice_items table
        foreach ($customerOrder->orderItems as $orderItem) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'item_id' => $orderItem->item_id,  // Item associated with the order
                'quantity' => $orderItem->quantity,
                'unit_price' => $orderItem->price,  // Price of each unit
                'total_price' => $orderItem->total, // Total price for this item (quantity * unit price)
            ]);
        }

        // Update the CustomerOrder status to 'invoiced'
        $customerOrder->status = 'invoiced';
        $customerOrder->save();

        $this->accountsUpdate();

        session()->flash('success', 'Invoice created successfully!');
        return $this->redirect('/invoices');
    }


    public function accountsUpdate()
    {
        // Assuming the payment method is 'cash', adjust as necessary.
        if ($this->payment_method === 'cash') {
            // Insert the Cash Account Dr, Sales Account Cr entries in the transactions table
            Transaction::create([
                'sale_id' => $this->invoice->id,
                'account_id' => 1, // Cash Account, you need to reference the correct account ID
                'debit' => $this->invoice->total_amount,
                'credit' => 0,
                'transaction_type' => 'debit',  // Debit transaction
            ]);

            Transaction::create([
                'sale_id' => $this->invoice->id,
                'account_id' => 2, // Sales Account, adjust accordingly
                'debit' => 0,
                'credit' => $this->invoice->total_amount,
                'transaction_type' => 'credit',  // Credit transaction
            ]);
        } elseif ($this->invoice->payment_method === 'bank') {
            // Handle bank payment similarly with Bank Account Dr, Sales Account Cr
            Transaction::create([
                'sale_id' => $this->invoice->id,
                'account_id' => 3, // Bank Account, replace with the actual account ID
                'debit' => $this->invoice->total_amount,
                'credit' => 0,
                'transaction_type' => 'debit',
            ]);

            Transaction::create([
                'sale_id' => $this->invoice->id,
                'account_id' => 2, // Sales Account
                'debit' => 0,
                'credit' => $this->invoice->total_amount,
                'transaction_type' => 'credit',
            ]);
        } else {
            // If it's a credit sale, update accordingly
            // Assuming Receivables Account Dr and Sales Revenue Account Cr
            Transaction::create([
                'sale_id' => $this->invoice->id,
                'account_id' => 4, // Receivables Account, replace with the correct ID
                'debit' => $this->invoice->total_amount,
                'credit' => 0,
                'transaction_type' => 'debit',
            ]);

            Transaction::create([
                'sale_id' => $this->invoice->id,
                'account_id' => 5, // Sales Revenue Account, adjust accordingly
                'debit' => 0,
                'credit' => $this->invoice->total_amount,
                'transaction_type' => 'credit',
            ]);
        }
    }


    public function releseOrder($order_id)
    {
        $customerOrder = CustomerOrder::with('orderItems')->find($order_id);
        $customerOrderItems = $customerOrder->orderItems;

        Log::info("Starting release for order: {$order_id}");

        DB::beginTransaction();

        try {
            foreach ($customerOrderItems as $customerOrderItem) {
                $quantity = $customerOrderItem->quantity * -1; // Make the quantity negative for stock out

                Log::info("Processing item ID: {$customerOrderItem->item_id}, Quantity to release: {$quantity}");

                $item = Item::find($customerOrderItem->item_id);

                // Fetch the stock records for the item, ordered by creation date (FIFO)
                $stocks = Stock::where('items_id', $customerOrderItem->item_id)
                    ->orderBy('created_at', 'asc') // Process in FIFO order
                    ->get();

                if ($stocks->isEmpty()) {
                    Log::error("No stock available for item ID: {$customerOrderItem->item_id}");
                    throw new \Exception("No stock available for the item.");
                }

                $remainingQuantity = $quantity; // The total quantity to release

                foreach ($stocks as $stock) {
                    // If there's stock available in this batch (positive quantity)
                    if ($stock->quantity > 0) {
                        // Determine the quantity to subtract from this batch (either the remaining quantity or the batch quantity)
                        $subtractQuantity = min($remainingQuantity, $stock->quantity);

                        // Insert a new stock record with the negative quantity (stock out)
                        Stock::create([
                            'brands_id' => $customerOrder->brands_id,
                            'supplier_id' => $customerOrder->supplier_id,
                            'items_id' => $customerOrderItem->item_id,
                            'quantity' => -abs($subtractQuantity), // Negative quantity for stock out
                            'sales_price' => $item->sales_price,
                            'purchase_price' => $item->purchase_price,
                            'mrp' => $item->mrp,
                            'p_id' => $customerOrder->id,
                            'f_id' => $customerOrderItem->id,
                            'purchase_date' => $stock->created_at,
                            'weight' => $item->weight,
                            'dimensions' => $stock->dimensions,
                            'sku_code' => $stock->sku_code,
                            'effective_date' => $stock->effective_date,
                            'online' => 1,
                        ]);

                        // Subtract the quantity from the remaining required quantity
                        $remainingQuantity -= $subtractQuantity;

                        // If the required quantity is satisfied, stop processing further batches
                        if ($remainingQuantity <= 0) {
                            break;
                        }
                    }
                }

                // If after processing all batches, there's still remaining quantity, handle it
                if ($remainingQuantity > 0) {
                    throw new \Exception("Not enough stock available to process the full quantity.");
                }


                // If after processing all batches, there's still remaining quantity, handle it
                if ($remainingQuantity > 0) {
                    Log::error("Not enough stock available for item ID: {$customerOrderItem->item_id}. Remaining: {$remainingQuantity}");
                    throw new \Exception("Not enough stock available to process the full quantity.");
                }
            }

            // Update GRN status to "released"
            $customerOrder->update([
                'status' => 'released',
            ]);
            $this->status = 'released';

            Log::info("Customer order {$order_id} status updated to released.");

            DB::commit();
            $this->dispatch('statusUpdated');
            session()->flash('success', 'Stock updated successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Error occurred while processing order {$order_id}: " . $e->getMessage());
            session()->flash('error', 'Error updating stock: ' . $e->getMessage());
        }

        // return $this->redirect('/invoices');
    }



    public function render()
    {
        $bodyAttributes = 'x-data="{ page: \'customerCreate\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
        x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
        :class="{\'dark bg-gray-900\': darkMode === true}"';

        return view('livewire.customer-orders.customer-order-details', [
            'customers' => Customer::all(),
        ])->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
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

    public function mount($orderId = null)
    {
        if ($orderId) {
            $this->loadOrderDetails($orderId);
        }
    }

    public function loadOrderDetails($orderId)
    {
        $this->customerOrder = CustomerOrder::with(['customer', 'orderItems'])
            ->where('id', $orderId)
            ->first();

        if ($this->customerOrder) {
            $this->orderId = $orderId;
            $this->customer_id = $this->customerOrder->customer_id;
            $this->payment_method = $this->customerOrder->payment_method;
            $this->total_amount = $this->customerOrder->total_amount;
            $this->status = $this->customerOrder->status;
            $this->customer_name = $this->customerOrder->customer->name;

            // Load order items
            $this->orderItems = $this->customerOrder->orderItems->map(function ($item) {
                $stockBalance = Stock::where('items_id', $item->item_id)->sum('quantity') ?? 0;
                return [
                    'id' => $item->id,
                    'item_id' => $item->item_id,
                    'name' => $item->item ? $item->item->item_name : 'Unknown',  // Avoid errors if item is missing
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'total' => $item->total,
                    'stock_balance' => $stockBalance
                ];
            })->toArray();
        } else {
            session()->flash('error', 'Order not found!');
            return $this->redirect('/customer-orders');
        }
    }

    public function updateTotal($index)
    {
        if (isset($this->orderItems[$index])) {
            // Ensure the quantity does not exceed the stock balance
            if ($this->orderItems[$index]['quantity'] > $this->orderItems[$index]['stock_balance']) {
                $this->orderItems[$index]['quantity'] = $this->orderItems[$index]['stock_balance'];
                session()->flash('error', 'Quantity cannot exceed available stock.');
            }

            // Recalculate the total
            $this->orderItems[$index]['total'] =
                $this->orderItems[$index]['quantity'] * $this->orderItems[$index]['price'];
        }

        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->total_amount = array_sum(array_column($this->orderItems, 'total'));
        CustomerOrder::where('id', $this->orderId)
            ->update([
                'total_amount' => $this->total_amount
            ]);
    }

    public function updateOrderItems()
    {
        foreach ($this->orderItems as $item) {
            CustomerOrderItem::updateOrCreate([
                'order_id' => $this->orderId,
                'item_id' => $item['item_id'],
            ], [
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total' => $item['total'],
            ]);
        }
        $this->getOrderItems($this->orderId);
        $this->calculateTotal();
        session()->flash('success', 'Order Updated successfully!');
    }

    public function getOrderItems($orderId)
    {
        $this->customerOrder = CustomerOrder::with(['customer', 'orderItems'])
            ->where('id', $orderId)
            ->first();

        if ($this->customerOrder) {
            $this->orderId = $orderId;
            $this->customer_id = $this->customerOrder->customer_id;
            $this->payment_method = $this->customerOrder->payment_method;
            $this->total_amount = $this->customerOrder->total_amount;
            $this->status = $this->customerOrder->status;
            $this->customer_name = $this->customerOrder->customer->name;

            // Load order items
            $this->orderItems = $this->customerOrder->orderItems->map(function ($item) {
                $stockBalance = Stock::where('items_id', $item->item_id)->sum('quantity') ?? 0;
                return [
                    'id' => $item->id,
                    'item_id' => $item->item_id,
                    'name' => $item->item ? $item->item->item_name : 'Unknown',
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'total' => $item->total,
                    'stock_balance' => $stockBalance
                ];
            })->toArray();
        }
    }

    public function removeItem($id)
    {
        CustomerOrderItem::where('id', $id)
            ->delete();
        $this->getOrderItems($this->orderId);
        $this->calculateTotal();
    }
}
