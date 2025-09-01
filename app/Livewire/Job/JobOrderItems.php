<?php

namespace App\Livewire\Job;

use App\Models\Item;
use App\Models\Stock;
use Livewire\Component;

class JobOrderItems extends Component
{
    public $job_order_id;
    public $item_id;
    public $quantity;
    public $items;

    public function mount($job_order_id)
    {
        $this->job_order_id = $job_order_id;
        $this->items = Item::all();
    }

    public function addJobOrderItem(){
        $item = Item::find($this->item_id);
        $stock = Stock::where('item_id', $this->item_id)
                      ->orderBy('created_at', 'asc') // Get the oldest stock first
                      ->get();

    }


    public function render()
    {
        return view('livewire.job.job-order-item');
    }
}
