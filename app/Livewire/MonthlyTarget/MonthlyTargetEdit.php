<?php

namespace App\Livewire\MonthlyTarget;

use App\Models\MonthlyTarget;
use Livewire\Component;
use Carbon\Carbon;

class MonthlyTargetEdit extends Component
{
    public $monthlyTargetId;
    public $month;
    public $year;
    public $target_amount;
    public $description;
    public $status = 'active';

            public function mount($id)
    {
        $this->monthlyTargetId = $id;

        // Load the monthly target data
        $monthlyTarget = MonthlyTarget::findOrFail($id);

        $this->month = $monthlyTarget->month;
        $this->year = $monthlyTarget->year;
        $this->target_amount = $monthlyTarget->target_amount;
        $this->description = $monthlyTarget->description;
        $this->status = $monthlyTarget->status;
    }

    public function updateMonthlyTarget()
    {
        // Simple validation without uniqueness checks
        $this->validate([
            'month' => 'required|string|max:20',
            'year' => 'required|integer|min:2020|max:2030',
            'target_amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive'
        ]);

        try {
            // Update the monthly target
            $monthlyTarget = MonthlyTarget::findOrFail($this->monthlyTargetId);

            $monthlyTarget->update([
                'month' => $this->month,
                'year' => $this->year,
                'target_amount' => $this->target_amount,
                'description' => $this->description,
                'status' => $this->status,
                'updated_by' => auth()->id()
            ]);

            session()->flash('success', 'Monthly target updated successfully!');

            // Redirect back to the list
            return $this->redirect('/monthly-targets', navigate: true);

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update monthly target: ' . $e->getMessage());
        }
    }

    public function getMonthOptionsProperty()
    {
        return [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];
    }

    public function getYearOptionsProperty()
    {
        $currentYear = Carbon::now()->year;
        $years = [];
        for ($i = $currentYear - 2; $i <= $currentYear + 3; $i++) {
            $years[] = $i;
        }
        return $years;
    }

    public function render()
    {
        $bodyAttributes = 'x-data="{ page: \'monthlyTargetEdit\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
            x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                    $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
            :class="{\'dark bg-gray-900\': darkMode === true}"';

        return view('livewire.monthly-target.monthly-target-edit', compact('bodyAttributes'))
            ->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
