<?php

namespace App\Livewire\Item;

use App\Models\Account;
use App\Models\Item;
use App\Models\Brand;
use App\Models\Supplier;
use App\Models\Uom;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Title;
use App\Helpers\NumberGenerator;

class ItemForm extends Component
{
    #[Title('Manage Items')]

    public $item = null;
    public $isView = false;

    #[Validate('required', message: 'Brand is required')]
    public $brands_id =1;

    #[Validate('required', message: 'Item name is required')]
    #[Validate('min:3', message: 'Item name must be at least 3 characters long')]
    #[Validate('max:150', message: 'Item name must not exceed 150 characters')]
    public $item_name;

    // #[Validate('nullable', message: 'Item code is required')]
    #[Validate('unique:items,item_code', message: 'Item code must be unique')]
    public $item_code;

    #[Validate('required', message: 'Item description is required')]
    public $item_description;

    #[Validate('nullable', message: 'Short description is optional')]
    public $item_short_description;

    #[Validate('required|string|min:0', message: 'item type validation faild')]
    public $item_type = 'RW';

    #[Validate('required|numeric|regex:/^\d+(\.\d{1,2})?$/', message: 'Selling price must be a positive number')]
    public $selling;

    #[Validate('required|numeric|regex:/^\d+(\.\d{1,2})?$/', message: 'MRP must be a positive number')]
    public $mrp;

    #[Validate('required|numeric|regex:/^\d+(\.\d{1,2})?$/', message: 'Discount must be a positive number')]
    public $discount;

    #[Validate('required', message: 'Unit of Measure is required')]
    public $uom;

    #[Validate('required|in:active,inactive', message: 'Invalid status')]
    public $status = 'active';

    #[Validate('required', message: 'returnable is required')]
    public $returnable;

    #[Validate('required', message: 'dimensions is required')]
    public $dimensions;

    #[Validate('required', message: 'weight is required')]
    public $weight;

    #[Validate('required', message: 'sales_price is required')]
    public $sales_price;

    #[Validate('required', message: 'purchase_price is required')]
    public $purchase_price;

    #[Validate('required', message: 'sales_account is required')]
    public $sales_account;

    #[Validate('required', message: 'purchase_account is required')]
    public $purchase_account;

    #[Validate('required', message: 'sales_tax is required')]
    public $sales_tax;

    #[Validate('required', message: 'purchase_tax is required')]
    public $purchase_tax;

    #[Validate('required', message: 'sales_price is required')]
    public $preferred_vendor;

    #[Validate('required', message: 'inventory_account is required')]
    public $inventory_account;

    public function mount(Item $item)
    {
        $this->isView = request()->routeIs('items.view');
        if ($item->id) {
            $this->item = $item;
            $this->brands_id = $item->brands_id ?? 1;
            $this->item_name = $item->item_name;
            $this->item_code = $item->item_code;
            $this->item_description = $item->item_description;
            $this->item_short_description = $item->item_short_description;
            $this->uom = $item->uom;
            $this->mrp = $item->mrp;
            $this->discount = $item->discount;
            $this->status = $item->status;
            $this->item_type = $item->item_type;
            $this->returnable = $item->returnable;
            $this->dimensions = $item->dimensions;
            $this->weight = $item->weight;
            $this->sales_price = $item->sales_price ?? $item->mrp;
            $this->purchase_price = $item->purchase_price;
            $this->sales_account = $item->sales_account;
            $this->purchase_account = $item->purchase_account;
            $this->sales_tax = $item->sales_tax;
            $this->purchase_tax = $item->purchase_tax;
            $this->preferred_vendor = $item->preferred_vendor;
            $this->inventory_account = $item->inventory_account;
        }
    }

    public function generateItemCode()
    {
        // Define the prefix
        $prefix = 'prefix-';

        // Get the latest item (the one with the highest ID)
        $latestItem = Item::latest()->first();

        // Generate the next item code by incrementing the last code
        if ($latestItem) {
            // Get the numeric part of the last item code
            $lastCode = substr($latestItem->item_code, strlen($prefix));
            $nextCode = str_pad((int)$lastCode + 1, 6, '0', STR_PAD_LEFT);
        } else {
            // If no items exist, start with 000001
            $nextCode = '000001';
        }

        // Combine the prefix and the new code
        $this->item_code = $prefix . $nextCode;
    }


    public function saveItem()
    {
        try{

            $itemCode = NumberGenerator::generateCode('items', 'ITEM', 'item_code', 5);
        if ($this->item) {
            // Update Functionality
            $this->item->update([
                'brands_id' => $this->brands_id,
                'item_name' => $this->item_name,
                // 'item_code' => $itemCode,
                'item_description' => $this->item_description,
                'item_short_description' => $this->item_short_description,
                'uom' => $this->uom,
                'mrp' => $this->mrp,
                'discount' => $this->discount,
                'status' => $this->status,
                'item_type' => $this->item_type,
                'returnable' => $this->returnable ?? 0,
                'dimensions' => $this->dimensions,
                'weight' => $this->weight,
                'sales_price' => $this->mrp,
                'purchase_price' => $this->purchase_price,
                'sales_account' => $this->sales_account,
                'purchase_account' => $this->purchase_account,
                'sales_tax' => $this->sales_tax,
                'purchase_tax' => $this->purchase_tax,
                'preferred_vendor' => $this->preferred_vendor,
                'inventory_account' => $this->inventory_account,
            ]);

            session()->flash('success', 'Item has been updated successfully!');
        } else {
            // Create New Item
            Item::create([
                'brands_id' => $this->brands_id,
                'item_name' => $this->item_name,
                'item_code' => $itemCode,
                'item_description' => $this->item_description,
                'item_short_description' => $this->item_short_description,
                'uom' => $this->uom,
                'mrp' => $this->mrp,
                'discount' => $this->discount,
                'status' => $this->status,
                'item_type' => $this->item_type,
                'returnable' => $this->returnable ?? 0,
                'dimensions' => $this->dimensions,
                'weight' => $this->weight,
                'sales_price' => $this->sales_price,
                'purchase_price' => $this->purchase_price,
                'sales_account' => $this->sales_account,
                'purchase_account' => $this->purchase_account,
                'sales_tax' => $this->sales_tax,
                'purchase_tax' => $this->purchase_tax,
                'preferred_vendor' => $this->preferred_vendor,
                'inventory_account' => $this->inventory_account,
            ]);

            session()->flash('success', 'Item has been created successfully!');
        }

        }catch (\Exception $e) {
            // Flash error message to session
            session()->flash('error', 'Unable to create an Item. Please try again with valid details!');
        }


        return $this->redirect('/items', navigate: true);
    }

    public function render()
    {
        $bodyAttributes = 'x-data="{ page: \'addItem\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
        x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
        :class="{\'dark bg-gray-900\': darkMode === true}"';

        return view('livewire.item.item-form', [
            'brands' => Brand::all(),
            'accounts' => Account::all(),
            'vendors' => Supplier::all(),
            'uoms' => Uom::all(),
        ])->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
