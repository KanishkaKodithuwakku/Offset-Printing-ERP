<?php

namespace App\Livewire\Customer;

use App\Models\CustomerOrder;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use Livewire\Attributes\Title;

class CustomerOrderList extends Component
{
    use WithPagination, WithoutUrlPagination;

    #[Title('Customer Orders')]

    public $searchTerm = null;
    public $sortColumn = 'id';
    public $sortOrder = 'desc';

    protected $listeners = ['ordersUpdated' => 'refreshOrders'];

    public function refreshOrders()
    {
        Log::info('Livewire Event: Received ordersUpdated, refreshing orders list');
        $this->resetPage();
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

    /**
     * Computed Property: getOrdersProperty
     * Description: Fetches paginated orders dynamically
     */
    public function getOrdersProperty()
    {
        return CustomerOrder::whereHas('customer', function ($query) {
                $query->where('name', 'like', '%' . $this->searchTerm . '%')
                      ->orWhere('email', 'like', '%' . $this->searchTerm . '%');
            })
            ->orWhere('payment_method', 'like', '%' . $this->searchTerm . '%')
            ->orderBy($this->sortColumn, $this->sortOrder)
            ->paginate(25);
    }

    public function render()
    {
        $bodyAttributes = 'x-data="{ page: \'orderList\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
        x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
        :class="{\'dark bg-gray-900\': darkMode === true}"';

        // Fetch orders excluding those with 'invoiced' status and paginate the results
        $orders = CustomerOrder::where('status', '!=', 'invoiced')->paginate(10);

        return view('livewire.customer-orders.customer-order-list', [
            'orders' => $orders,  // Pass the paginated results directly
        ])->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }

    /**
     * Function: deleteOrder
     * Description: Deletes a customer order and refreshes pagination
     */
    public function deleteOrder(CustomerOrder $order)
    {
        if ($order) {
            $deleteResponse = $order->delete();

            if ($deleteResponse) {
                session()->flash('success', 'Order deleted successfully!');
            } else {
                session()->flash('error', 'Unable to delete order. Please try again!');
            }
        } else {
            session()->flash('error', 'Order not found. Please try again!');
        }

        // Reset pagination after deletion
        $this->resetPage();
    }
}
