<?php

namespace App\Livewire\Grn;

use App\Models\DamagedItem;
use App\Models\Customer;
use App\Models\Item;
use App\Models\JobOrder;
use App\Models\Stock;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class DamagedItemForm extends Component
{
    public $job_number, $job_id, $customer_id, $item, $item_id, $quantity, $reason;
    public $searchCustomer;
    public $searchItem;
    public $searchJob;
    public $searchResultsCustomer;
    public $searchItemsResults;
    public $searchResultsJobs;
    public $authUser;
    public $customer_name;
    public $customer_address;
    public $customer_phone;
    public $customer_email;
    public $customer_city;
    public $job_order_id;
    public $job_order_number;
    public $selectedItemId;
    public $damagedItems = [];
    public function mount()
    {
        $this->authUser = auth()->user();
    }

    public function save()
    {


        $this->validate([
            'job_number' => 'required|string',
            'job_id' => 'required|integer',
            'customer_id' => 'required|exists:customers,id',
            'item_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string',
        ]);


        Log::debug('Saving damaged item', [
            'job_number' => $this->job_number,
            'job_id' => $this->job_id,
            'customer_id' => $this->customer_id,
            'item_id' => $this->item_id,
            'quantity' => $this->quantity,
            'reason' => $this->reason,
        ]);


        try {
            DamagedItem::create([
                'job_number' => $this->job_number,
                'job_id' => $this->job_id,
                'customer_id' => $this->customer_id,
                'item_id' => $this->item_id,
                'quantity' => $this->quantity,
                'reason' => $this->reason,
            ]);

            session()->flash('success', 'Damage report submitted for approval.');
            $this->reset();

        } catch (\Exception $e) {
            Log::error('Failed to save damaged item', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'error. ' . $e);
        }


    }

    public function updatedSearchCustomer()
    {
        if (strlen($this->searchCustomer) >= 1) {
            $customer = Customer::where(function ($query) {
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

    public function assignCustomer($customerId)
    {
        $customer = Customer::find($customerId);
        if (!$customer) {
            return response()->json(['error' => 'Customer not found'], 404);
        }

        $this->customer_id = $customer->id;
        $this->searchResultsCustomer = [];
        $this->searchCustomer = $customer->name;
        $this->customer_name = $customer->name;
        $this->customer_address = $customer->address;
        $this->customer_phone = $customer->phone;
        $this->customer_email = $customer->email;
        $this->customer_city = $customer->city;
        return response()->json(['message' => 'Customer assigned successfully'], 200);
    }



    public function updatedSearchItem()
    {
        if (strlen($this->searchItem) >= 1) {
            $items = Item::where(function ($query) {
                $query->where('item_name', 'like', "{$this->searchItem}%")
                    ->orWhere('item_code', 'like', "{$this->searchItem}%");
            })
                ->limit(5)
                ->get();

            // $items = JobOrderItem::with('item')
            //     ->where('order_id', $this->job_id)
            //     ->whereHas('item', function ($query) {
            //         $query->where(function ($q) {
            //             $q->where('item_name', 'like', "{$this->searchItem}%")
            //                 ->orWhere('item_code', 'like', "{$this->searchItem}%");
            //         });
            //     })
            //     ->limit(5)
            //     ->get();

            $this->searchItemsResults = $items->map(callback: function ($item) {

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
            $this->searchItemsResults = [];
        }
    }



    public function addDamagedItem($itemId)
    {
        $this->selectedItemId = $itemId;
        $item = Item::find($this->selectedItemId);
        $this->searchItem = $item->item_name;
        $this->item_id = $this->selectedItemId;

        if ($item) {
            // Check if the item already exists in the damaged items array
            $existingItemKey = null;
            foreach ($this->damagedItems as $index => $damagedItem) {
                if ($damagedItem['item_id'] == $item->id) {
                    $existingItemKey = $index;
                    break;
                }
            }
            $stockBalance = Stock::where('items_id', $item->id)->sum('quantity') ?? 0;
            if ($stockBalance == 0) {
                $this->stockProcess = false;
            }

            if ($existingItemKey !== null) {
                if ($stockBalance < $this->damagedItems[$existingItemKey]['quantity'] + 1) {
                    session()->flash('error', 'Quantity cannot exceed available stock since the available stock balance is  (' . $stockBalance . ')');
                } else {
                    $this->damagedItems[$existingItemKey]['quantity'] += 1; // Increment quantity by 1
                    // Update the total for the item based on the updated quantity
                    $this->damagedItems[$existingItemKey]['total'] = $this->damagedItems[$existingItemKey]['quantity'] * $this->damagedItems[$existingItemKey]['selling_price'];
                }

                // If the item exists, update the quantity

            } else {
                // If the item does not exist, add it to the array
                if ($stockBalance == 0) {
                    session()->flash('error', 'Quantity cannot exceed available stock since the available stock balance is (0)');
                }
                $this->damagedItems[] = [
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

            // Clear the search term and results
            // $this->searchItem = '';
            $this->searchItemsResults = [];
        }

        // Check if Damaged Items is updated
        Log::debug('Damaged Items:', $this->damagedItems);
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
        $this->job_id = $jobOrder->id;
        $this->searchJob = $jobOrder->job_number;
        $this->searchResultsJobs = [];
        $this->assignCustomer($jobOrder->customer->id);
        return response()->json(['message' => 'Job Order assigned successfully'], 200);
    }


    public function render()
    {
        $bodyAttributes = 'x-data="{ page: \'AddDamagedItem\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
            x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                    $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
            :class="{\'dark bg-gray-900\': darkMode === true}"';

        return view('livewire.grns.damaged-item-form', [
            'customers' => Customer::all()
        ])->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}

