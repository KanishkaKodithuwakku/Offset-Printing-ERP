<?php
namespace App\Livewire\Transaction;

use Livewire\Component;
use App\Models\Transaction;

class TransactionList extends Component
{
    // Declare a public property to hold the transactions
    public $transactions;

    public function mount()
    {
        // Fetch all transactions from the database
        $this->transactions = Transaction::with('account')->get();

    }

    public function render()
    {

        $bodyAttributes = 'x-data="{ page: \'transactions\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
        x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
        :class="{\'dark bg-gray-900\': darkMode === true}"';
        // Return the view with the transactions data
        return view('livewire.transaction.transaction-list', ['transactions' => $this->transactions])->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
