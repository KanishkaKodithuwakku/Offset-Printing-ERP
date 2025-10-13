<?php

namespace App\Livewire\OtherExpense;

use App\Models\OtherExpense;
use Livewire\Component;
use Livewire\WithPagination;

class OtherExpenseList extends Component
{
    use WithPagination;

    public $searchTerm = '';

    public function updatingSearchTerm()
    {
        $this->resetPage();
    }

    public function deleteExpense(OtherExpense $expense)
    {
        try {
            $expense->delete();
            session()->flash('success', 'Other expense deleted successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Error deleting expense: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $expensesQuery = OtherExpense::query();

        // Search filter
        if ($this->searchTerm) {
            $expensesQuery->where(function ($query) {
                $query->where('expense_name', 'like', "%{$this->searchTerm}%")
                    ->orWhere('description', 'like', "%{$this->searchTerm}%");
            });
        }

        $expenses = $expensesQuery->orderBy('created_at', 'desc')->paginate(25);

        // Append filters to pagination links
        $expenses->appends([
            'searchTerm' => $this->searchTerm,
        ]);

        $bodyAttributes = 'x-data="{ page: \'otherExpenseList\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
        x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
        :class="{\'dark bg-gray-900\': darkMode === true}"';

        return view('livewire.other-expense.other-expense-list', ['expenses' => $expenses])
            ->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
