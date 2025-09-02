<?php

namespace App\Livewire\MonthlyTarget;

use App\Models\MonthlyTarget;
use Livewire\Component;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Carbon\Carbon;

class MonthlyTargetForm extends Component
{
    public $monthlyTargetId;
    public $month;
    public $year;
    public $target_amount;
    public $description;
    public $status = 'active';



    public function mount($monthlyTargetId = null)
    {
        if ($monthlyTargetId) {
            $monthlyTarget = MonthlyTarget::find($monthlyTargetId);
            if ($monthlyTarget) {
                $this->monthlyTargetId = $monthlyTarget->id;
                $this->month = $monthlyTarget->month;
                $this->year = $monthlyTarget->year;
                $this->target_amount = $monthlyTarget->target_amount;
                $this->description = $monthlyTarget->description;
                $this->status = $monthlyTarget->status;
            }
        } else {
            // Set default values for new target
            $this->month = Carbon::now()->format('F');
            $this->year = Carbon::now()->year;
        }
    }

    public function saveMonthlyTarget()
    {
        // Debug logging
        \Log::info('saveMonthlyTarget called', [
            'monthlyTargetId' => $this->monthlyTargetId,
            'month' => $this->month,
            'year' => $this->year,
            'isEdit' => !empty($this->monthlyTargetId)
        ]);

        // Validate basic field requirements
        $this->validate([
            'month' => 'required|string|max:20',
            'year' => 'required|integer|min:2020|max:2030',
            'target_amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive'
        ]);

        // No uniqueness validation needed - database constraint will handle this

        $data = [
            'month' => $this->month,
            'year' => $this->year,
            'target_amount' => $this->target_amount,
            'description' => $this->description,
            'status' => $this->status,
            'updated_by' => auth()->id()
        ];

        try {
            if ($this->monthlyTargetId) {
                // Update existing target
                $monthlyTarget = MonthlyTarget::find($this->monthlyTargetId);
                $monthlyTarget->update($data);
                session()->flash('success', 'Monthly target updated successfully!');
            } else {
                // Create new target
                $data['created_by'] = auth()->id();
                MonthlyTarget::create($data);
                session()->flash('success', 'Monthly target created successfully!');
            }
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database constraint violations
            if (str_contains($e->getMessage(), 'monthly_targets_month_year_unique')) {
                session()->flash('error', 'A monthly target for ' . $this->month . ' ' . $this->year . ' already exists.');
            } else {
                session()->flash('error', 'Database error: ' . $e->getMessage());
            }
            return;
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
            return;
        }

        return $this->redirect('/monthly-targets', navigate: true);
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
        $bodyAttributes = 'x-data="{ page: \'monthlyTargetForm\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
            x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                    $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
            :class="{\'dark bg-gray-900\': darkMode === true}"';

        return view('livewire.monthly-target.monthly-target-form', compact('bodyAttributes'))
            ->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
