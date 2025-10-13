<?php

namespace App\Livewire\OtherExpense;

use App\Models\OtherExpense;
use Livewire\Component;
use Livewire\Attributes\Validate;

class OtherExpenseCreate extends Component
{
    public $expenseId;

    #[Validate('required|string|max:255', message: 'Expense name is required')]
    public $expense_name = '';

    #[Validate('nullable|string', message: 'Description must be text')]
    public $description = '';

    #[Validate('required|numeric|min:0', message: 'Price must be a positive number')]
    public $price = '';

    public function mount($expenseId = null)
    {
        if ($expenseId) {
            $expense = OtherExpense::find($expenseId);
            if ($expense) {
                $this->expenseId = $expense->id;
                $this->expense_name = $expense->expense_name;
                $this->description = $expense->description;
                $this->price = $expense->price;
            }
        }
    }

    public function saveExpense()
    {
        $this->validate();

        try {
            if ($this->expenseId) {
                // Update existing expense
                $expense = OtherExpense::find($this->expenseId);
                $expense->update([
                    'expense_name' => $this->expense_name,
                    'description' => $this->description,
                    'price' => $this->price,
                ]);
                session()->flash('success', 'Other expense updated successfully!');
            } else {
                // Create new expense
                OtherExpense::create([
                    'expense_name' => $this->expense_name,
                    'description' => $this->description,
                    'price' => $this->price,
                ]);
                session()->flash('success', 'Other expense created successfully!');
            }

            $this->resetForm();
        } catch (\Exception $e) {
            session()->flash('error', 'Error saving expense: ' . $e->getMessage());
        }
    }

    public function resetForm()
    {
        $this->expenseId = null;
        $this->expense_name = '';
        $this->description = '';
        $this->price = '';
    }

    public function render()
    {
        $bodyAttributes = 'x-data="{ page: \'otherExpenseCreate\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
        x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
        :class="{\'dark bg-gray-900\': darkMode === true}"';

        return view('livewire.other-expense.other-expense-create')
            ->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
