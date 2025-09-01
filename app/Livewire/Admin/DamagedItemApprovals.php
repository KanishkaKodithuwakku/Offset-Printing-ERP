<?php

namespace App\Livewire\Admin;
use Livewire\WithPagination;
use App\Models\DamagedItem;
use App\Models\Stock;
use Livewire\Component;
use Log;

class DamagedItemApprovals extends Component
{
    use WithPagination;

    public $authUser;
    public function mount()
    {
        $this->authUser = auth()->user();
    }
    public function approve($id)
    {

        try {
            $damagedItem = DamagedItem::find($id);

            Stock::create([
                'items_id' => $damagedItem->item_id,
                'brands_id' => $damagedItem->brands_id,
                'branch_id' => $this->authUser->branch_id,
                'user_id' => $this->authUser->id,
                'quantity' => -abs(intval($damagedItem->quantity)),
                'purchase_price' => $damagedItem->item->purchase_price,
                'sales_price' => $damagedItem->item->sales_price,
                'mrp' => $damagedItem->item->mrp,
                'p_id' => $damagedItem->id,
                'f_id' => $damagedItem->id,
                'table_name' => 'damagedItem',
                'effective_date' => now(),
                'sku_code' => '',
                'online' => 1,
            ]);
            DamagedItem::where('id', $id)->update(['status' => 'approved']);
            session()->flash('success', 'Damaged item approved.');

        } catch (\Exception $e) {
            Log::error('Failed to approve damaged item', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'Failed to approve damaged item');
        }
    }

    public function reject($id)
    {
        DamagedItem::where('id', $id)->update(['status' => 'rejected']);
        session()->flash('error', 'Item rejected.');
    }

    public function render()
{
    $bodyAttributes = 'x-data="{ page: \'DamagedItemsApprove\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
        x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
        :class="{\'dark bg-gray-900\': darkMode === true}"';

    $items = DamagedItem::with('customer', 'item')
        ->orderBy('created_at', 'desc')
        ->paginate(25);
         

    return view('livewire.admin.damaged-item-approvals', [
        'items' => $items
    ])->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
}

}
