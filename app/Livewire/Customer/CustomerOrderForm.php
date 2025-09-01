<?php

namespace App\Livewire\Customer;

use App\Models\Customer;
use App\Models\CustomerOrder;
use App\Models\CustomerOrderItem;
use App\Models\Item;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use App\Helpers\NumberGenerator;

class CustomerOrderForm extends Component
{
    public $orderId;
    public $customer_id;
    public $orderItems = [];
    public $searchTerm = '';
    public $searchCustomer = '';
    public $searchResults = [];
    public $searchResultsCustomer = [];
    public $payment_method = 'cash';
    public $total_amount = 0;
    public $status = 'plan';
    public $customer;
    public $customer_name, $customer_address, $customer_phone, $customer_email, $customer_city;

    protected $listeners = ['orderSelected' => 'loadOrderDetails'];

    public function mount($orderId = null)
    {
        if ($orderId) {
            $this->loadOrderDetails($orderId);
        }
    }


    public $defaultResult = false;
    public function onSearchClick()
    {
        if ($this->defaultResult === false) {
            $customer = Customer::limit(5)->get();
            $this->searchResultsCustomer = $customer->map(function ($customer) {
                return [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'phone' => $customer->phone,
                    'city' => $customer->city,
                ];
            })->toArray();
        }
        $this->defaultResult = true;
        Log::info('Search input clicked');
    }

    public function updatedSearchCustomer()
    {
        if (strlen($this->searchCustomer) > 1) {
            $customer = Customer::where('name', 'like', "%{$this->searchCustomer}%")
                ->orWhere('email', 'like', "%{$this->searchCustomer}%")
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
            $this->searchResults = [];
            $this->defaultResult = false;
            Log::info('Search Results Cleared');
        }
    }

    public function assignCustomer($customerId)
    {
        // Find the customer using the provided customer ID
        $customer = Customer::find($customerId);
        if (!$customer) {
            return response()->json(['error' => 'Customer not found'], 404);
        }
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



    public function loadOrderDetails($orderId)
    {
        $this->customerOrder = CustomerOrder::with(['customer', 'orderItems'])
            ->where('id', $orderId)
            ->first();

        if ($this->customerOrder) {
            $this->customer_id = $this->customerOrder->customer_id;
            $this->payment_method = $this->customerOrder->payment_method;
            $this->total_amount = $this->customerOrder->total_amount;
            $this->status = $this->customerOrder->status;

            // Load order items

            $this->orderItems = $this->customerOrder->orderItems->map(function ($item) {
                $stockBalance = Stock::where('items_id', $item->item_id)->sum('quantity') ?? 0;
                return [
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


    public function addItem($itemId)
    {
        $item = Item::find($itemId);
        if (!$item) return;
        $stockBalance = Stock::where('items_id', $item->id)->sum('quantity') ?? 0;
        $this->orderItems[] = [
            'item_id' => $item->id,
            'name' => $item->item_name,
            'price' => $item->sales_price,
            'quantity' => 1,
            'total' => $item->sales_price,
            'stock_balance' => $stockBalance
        ];
        $this->calculateTotal();
        $this->searchTerm = '';
        $this->searchResults = [];
    }

    public function removeItem($index)
    {
        unset($this->orderItems[$index]);
        $this->orderItems = array_values($this->orderItems);
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->total_amount = array_sum(array_column($this->orderItems, 'total'));
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

    public function saveOrUpdateOrder()
    {

        if ($this->orderId) {
            // **Update existing order**
            $order = CustomerOrder::find($this->orderId);
            $order->update([
                'customer_id' => $this->customer_id,
                'total_amount' => $this->total_amount,
                'payment_method' => $this->payment_method,
                'status' => $this->status,
            ]);

            // Delete existing items & re-add
            $order->orderItems()->delete();
        } else {
            // **Create a new order**
            $order = CustomerOrder::create([
                'customer_id' => $this->customer_id,
                'order_number' => NumberGenerator::generateCode('customer_orders', 'CO','order_number', 5),
                'status' => 'plan',
                'total_amount' => $this->total_amount,
                'payment_method' => $this->payment_method,
            ]);
        }

        foreach ($this->orderItems as $item) {
            $orderItem = CustomerOrderItem::updateOrCreate([
                'order_id' => $order->id,
                'item_id' => $item['item_id'],
            ], [
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total' => $item['total'],
            ]);
            if ($this->status === 'released') {
                $product = Item::find($item['item_id']);

                $stockBalance = Stock::where('items_id', $product->id)->sum('quantity') ?? 0;

                if ($stockBalance > 0 && $item['quantity'] <= $stockBalance) {
                    $stock = DB::table('stocks')
                        ->select('*', DB::raw('sum(quantity) as qty'))
                        ->where('items_id', $item['item_id'])
                        ->groupBy('sku_code')
                        ->orderBy('created_at', 'asc')
                        ->get();

                    $remaining_quantity = $item['quantity'];

                    // Update stock and create JobOrderItem entries
                    foreach ($stock as $stock_entry) {
                        if ($remaining_quantity > 0) {
                            if ($stock_entry->qty >= $remaining_quantity) {
                                Stock::create([
                                    'items_id'       => $stock_entry->items_id,
                                    'brands_id'      => $stock_entry->brands_id,
                                    'quantity'       => -abs(intval($remaining_quantity)),
                                    'purchase_price' => $item['price'],
                                    'sales_price'    => $item['price'],
                                    'mrp'            => $item['price'],
                                    'p_id'           => $order->id,
                                    'f_id'           => $orderItem->id,
                                    'effective_date' => now(),
                                    'sku_code'       => $stock_entry->sku_code,
                                    'online'         => 1,
                                ]);
                                $remaining_quantity = 0;
                            } else {
                                Stock::create([
                                    'items_id'       => $product->id,
                                    'brands_id'      => $product->brands_id,
                                    'quantity'       => -abs(intval($stock_entry->qty)),
                                    'purchase_price' => $item['price'],
                                    'sales_price'    => $item['price'],
                                    'mrp'            => $item['price'],
                                    'p_id'           => $order->id,
                                    'f_id'           => $orderItem->id,
                                    'effective_date' => now(),
                                    'sku_code'       => $stock_entry->sku_code,
                                    'online'         => 1,
                                ]);

                                $remaining_quantity = $remaining_quantity - $stock_entry->qty;
                            }
                        }
                    }
                } else {
                    session()->flash('error', 'Order Not Updated!');
                }
            }
        }

        $this->calculateTotal();

        session()->flash('success', $this->orderId ? 'Order Updated Successfully!' : 'Order Created Successfully!');
        return $this->redirect('/customer-orders-list', navigate: true);
    }


    public function updatedSearchTerm()
    {
        Log::info('Search Term Updated: ' . $this->searchTerm);

        if (strlen($this->searchTerm) > 1) {
            $items = Item::where('item_name', 'like', "%{$this->searchTerm}%")
                ->orWhere('item_code', 'like', "%{$this->searchTerm}%")
                ->limit(5)
                ->get();

            $this->searchResults = $items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->item_name,
                    'code' => $item->item_code,
                    'price' => $item->selling,
                ];
            })->toArray();

            Log::info('Search Results: ' . json_encode($this->searchResults));
        } else {
            $this->searchResults = [];
            Log::info('Search Results Cleared');
        }
    }


    public function render()
    {
        $bodyAttributes = 'x-data="{ page: \'addOrder\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
        x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
        :class="{\'dark bg-gray-900\': darkMode === true}"';

        return view('livewire.customer-orders.customer-order-form', [
            'customers' => Customer::all(),
        ])->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
