<?php

namespace App\Livewire\Grn;

use App\Helpers\NumberGenerator;
use App\Models\Branch;
use App\Models\Grn;
use App\Models\Supplier;
use App\Models\Item;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class GrnForm extends Component
{
    public $user;
    public $grnId;
    public $searchTerm = '';
    public $searchResults = [];
    public $grnItems = [];
    public $total_amount = 0;
    public $supplier_id = 1;
    public $grn_code, $order_type, $order_id, $grn_date, $eff_date, $status = 'pending', $remark;
    public $delivery_date, $delivery_location, $delivery_remark, $grn_type;
    public $showItemsSection = false;
    public $searchSupplier;
    public $searchResultsSupplier;
    public $supplier_name;
    public $supplier_address;
    public $supplier_phone;
    public $supplier_email;
    public $defaultResult = false;



    public function mount(Grn $grn)
    {
        $this->user = Auth::user();
        $this->grn_date = now()->format('Y-m-d');
        $this->eff_date = now()->addDays(1)->format('Y-m-d');
        $this->delivery_date = now()->addDays(1)->format('Y-m-d');
        if ($grn) {
            $this->loadGrnDetails($grn->id);
        }

        $this->delivery_location = $this->user->branch_id;
    }


    public function updatedSearchSupplier()
    {
        if (strlen($this->searchSupplier) >= 1) {
            $suppliers = Supplier::where('name', 'like', "{$this->searchSupplier}%")
                ->orWhere('email', 'like', "%{$this->searchSupplier}%")
                ->limit(5)
                ->get();

            $this->searchResultsSupplier = $suppliers->map(function ($supplier) {
                return [
                    'id' => $supplier->id,
                    'name' => $supplier->name,
                    'email' => $supplier->email,
                    'phone' => $supplier->phone,
                    'address' => $supplier->address,
                ];
            })->toArray();
        } else {
            $this->searchResultsSupplier = [];
            $this->defaultResult = false;
        }
    }

    public function assignSupplier($supplierId)
    {
        // Find the customer using the provided customer ID
        $supplier = Supplier::find($supplierId);
        if (!$supplier) {
            return response()->json(['error' => 'Supplier not found'], 404);
        }

        $this->supplier_id = $supplier->id;
        $this->supplier_name = $supplier->name;
        $this->supplier_address =  $supplier->address;
        $this->supplier_phone =  $supplier->phone;
        $this->supplier_email =  $supplier->email;
        $this->searchSupplier = $supplier->name;
        $this->searchResultsSupplier = [];
        return response()->json(['message' => 'Supplier assigned successfully'], 200);
    }



    public function loadGrnDetails($grnId)
    {
        $grn = Grn::with('grnItems.item')->find($grnId);
        if ($grn) {
            $this->grnId = $grn->id;
            $this->grn_code = $grn->grn_code;
            $this->supplier_id = $grn->supplier_id;
            $this->grn_date = $grn->grn_date;
            $this->status = $grn->status;
            $this->delivery_date = $grn->delivery_date;
            $this->delivery_location = $grn->delivery_location;
            $this->grn_type = $grn->grn_type;
            $this->remark = $grn->remark;
            $this->order_type = $grn->order_type;
            $this->order_id = $grn->order_id;
            $this->eff_date = $grn->eff_date;
            $this->delivery_remark = $grn->delivery_remark;
            $this->user->id = $grn->user_id;

            $this->grnItems = $grn->grnItems->map(function ($grnItem) {
                return [
                    'item_id' => $grnItem->item_id,
                    'name' => $grnItem->item->item_name,
                    'price' => $grnItem->price,
                    'quantity' => $grnItem->quantity,
                    'total' => $grnItem->total,
                ];
            })->toArray();
        } else {
            //session()->flash('error', 'GRN not found!');
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
                return [
                    'id' => $item->id,
                    'name' => $item->item_name,
                    'price' => $item->cost,
                ];
            })->toArray();
        } else {
            $this->searchResults = [];
        }
    }

    public function addItem($itemId)
    {
        $item = Item::find($itemId);
        if ($item) {
            $this->grnItems[] = [
                'item_id' => $item->id,
                'name' => $item->item_name,
                'selling_price' => $item->cost,
                'quantity' => 1,
                'total' => $item->cost,
            ];
            $this->calculateTotal();
            $this->searchTerm = '';
            $this->searchResults = [];
        }
    }

    public function updateTotal($index)
    {
        if (isset($this->grnItems[$index])) {
            $this->grnItems[$index]['total'] = $this->grnItems[$index]['quantity'] * $this->grnItems[$index]['price'];
        }

        $this->calculateTotal();
    }

    public function removeItem($index)
    {
        unset($this->grnItems[$index]);
        $this->grnItems = array_values($this->grnItems);
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->total_amount = array_sum(array_column($this->grnItems, 'total'));
    }

    public function saveGrn()
    {

        $this->validate([
            'searchSupplier' => 'required',
            'delivery_date' => 'required|date',
            'eff_date' => 'required|date',
            'delivery_location' => 'required',
            'delivery_remark' => 'nullable',
            'grn_type' => 'nullable',
            'order_type' => 'nullable',
            'remark' => 'nullable',
        ]);

        $data = [
            'grn_code' => NumberGenerator::generateCode('grns', 'GRN','grn_code', 5),
            'supplier_id' => $this->supplier_id,
            'branch_id' => $this->delivery_location,
            'grn_date' => $this->grn_date,
            'total_amount' => $this->total_amount,
            'status' => $this->status,
            'delivery_date' => $this->delivery_date,
            'delivery_location' => $this->delivery_location,
            'grn_type' => $this->grn_type,
            'remark' => $this->remark,
            'order_type' => $this->order_type,
            'order_id' => 1,
            'eff_date' => $this->eff_date,
            'delivery_remark' => $this->delivery_remark,
            'user_id' => $this->user->id,
        ];

        if ($this->grnId) {
            Grn::find($this->grnId)->update($data);
            session()->flash('success', 'GRN Saved successfully!');
        } else {
            Grn::create($data);
            session()->flash('success', 'GRN Saved successfully!');
        }

        $this->showItemsSection = true;

        // Reset all form variables here
        $this->reset([
            'supplier_id',
            'grn_date',
            'total_amount',
            'status',
            'delivery_date',
            'delivery_location',
            'grn_type',
            'remark',
            'order_type',
            'eff_date',
            'delivery_remark',
            'grnId', // If needed to reset
            'showItemsSection', // If you want to hide the items section after save
        ]);

        return $this->redirect('/grns');
    }

    public function render()
    {
        $bodyAttributes = 'x-data="{ page: \'addGrn\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
        x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
        :class="{\'dark bg-gray-900\': darkMode === true}"';


        return view('livewire.grns.grn-form', [
            'suppliers' => Supplier::all(),
            'users' => User::all(),
            'branches' => Branch::all(),
            'authUser' =>  $this->user
        ])->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
