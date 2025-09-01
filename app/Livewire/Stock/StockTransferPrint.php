<?php

namespace App\Livewire\Stock;

use Livewire\Component;
use App\Models\StockTransferNote;

class StockTransferPrint extends Component
{
    public $transfer;
    public $transferItems;
    public $transferStatus;

    // Optionally: Include branch-related data if needed
    public $from_branch_name;
    public $to_branch_name;

    public function mount($transferCode)
    {
        $this->transfer = StockTransferNote::with(['items.item', 'fromBranch', 'toBranch'])->where('transfer_code', $transferCode)->firstOrFail();

        // Get the branch names if related to StockTransferNote
        $this->from_branch_name = $this->transfer->fromBranch->branch_name ?? '';
        $this->to_branch_name = $this->transfer->toBranch->branch_name ?? '';

        $this->transferStatus = $this->transfer->status;
        $this->transferItems = $this->transfer->items;
    }

    public function printPage()
    {
        // Dispatch print preview event (Ensure it's handled in your view's JavaScript)
        $this->dispatch('print-preview');
        // block the print button for 2nd print if needed
    }

    public function render()
    {
        $bodyAttributes = 'x-data="{ page: \'stockTransferPrint\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
            x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                    $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
            :class="{\'dark bg-gray-900\': darkMode === true}"';

        return view('livewire.stock.stock-transfer-print', [
            'transfer' => $this->transfer,
            'transferItems' => $this->transferItems,
            'from_branch_name' => $this->from_branch_name,
            'to_branch_name' => $this->to_branch_name,
        ])->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
