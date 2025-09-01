<?php

namespace App\Livewire\Stock;

use App\Models\Item;
use App\Models\Stock;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Log;

class StockList extends Component
{
    use WithPagination;

    #[Title('Stock Management')]

    public $searchTerm = '';
    public $sortColumn = 'items_id';
    public $sortOrder = 'desc';
    public $isLoading = false;
    public $stock = null;
    public $authUser = null;
    public $paginationEnabled = true;
    protected $listeners = ['stocksUpdated' => 'refreshStocks'];

    public function mount()
    {
        $this->authUser = auth()->user();
    }

    public function showLoading()
    {
        $this->isLoading = true;
    }

    public function hideLoading()
    {
        $this->isLoading = false;
    }

    public function refreshStocks()
    {
        Log::info('🔹 Livewire Event: Received stocksUpdated, refreshing stocks list');
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


    public function getStocksProperty()
    {
        $query = Item::selectRaw('
        COALESCE(SUM(stocks.quantity), 0) as total_qty,
        stocks.id,
        stocks.branch_id,
        stocks.purchase_price,
        stocks.sales_price,
        items.item_name,
        items.item_code,
        brands.brand_name,
        branches.branch_name
        ')
            ->join('stocks', 'stocks.items_id', '=', 'items.id')
            ->join('brands', 'items.brands_id', '=', 'brands.id')
            ->join('branches', 'stocks.branch_id', '=', 'branches.id')
            ->when($this->authUser->mode !== 'admin', function ($query) {
                $query->where('stocks.branch_id', $this->authUser->branch_id);
            })
            ->where('stocks.online', 1)
            ->when($this->searchTerm, function ($query) {
                $query->where(function ($q) {
                    $q->where('branches.branch_name', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('items.item_name', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('items.item_code', 'like', '%' . $this->searchTerm . '%');
                });
            })
            ->groupBy(
                'stocks.branch_id',
                // 'stocks.purchase_price',
                // 'stocks.sales_price',
                'items.item_name',
                'items.item_code',
                'brands.brand_name',
                'branches.branch_name'
            )
            ->orderBy($this->sortColumn, $this->sortOrder);

        if ($this->paginationEnabled) {
            return $query->paginate(25);
        } else {
            return $query->get();
        }
    }




    public function deleteStock($stockId)
    {
        $stock = Stock::find($stockId);
        if ($stock) {
            $stock->delete();
            session()->flash('success', 'Stock record deleted successfully!');
            $this->resetPage();
        } else {
            session()->flash('error', 'Stock record not found.');
        }
    }

    public function render()
    {
        $bodyAttributes = 'x-data="{ page: \'stockSummary\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
        x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
        :class="{\'dark bg-gray-900\': darkMode === true}"';

        return view('livewire.stock.stock-list', [
            'stocks' => $this->stocks,
        ])->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
