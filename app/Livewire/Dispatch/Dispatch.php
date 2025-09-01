<?php

namespace App\Livewire\Dispatch;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\JobOrder;
use App\Models\DispatchNote;

class Dispatch extends Component
{

    public $jobOrder;
    public $quantity;
    public $statusFilter;
    public $plates;
    public $dispatchedPlates;
    public $canInvoice = false;


    public function mount($jobOrderId)
    {
        $this->jobOrder = JobOrder::find($jobOrderId);
        $this->statusFilter = '';

        $this->plates = $this->jobOrder->plates;
        $this->dispatchedPlates = $this->jobOrder->dispatchNotes->sum('quantity');

        if($this->plates === $this->dispatchedPlates){
            $this->canInvoice  = true;
        }
    }

    public function updatedStatusFilter()
    {
        $this->jobOrder = $this->jobOrder->where('status', $this->statusFilter);
    }


    public function createDispatchNote()
    {
        // $this->validate();

        // Ensure the user is authorized to make the dispatch (based on user mode)
        if (Auth::user()->mode !== 'printing') {
            session()->flash('error', 'You do not have permission to create a dispatch note.');
            return;
        }

        // Check if the requested quantity doesn't exceed the remaining plates
        // $remainingPlates = $this->jobOrder->plates - $this->jobOrder->dispatchNotes->sum('quantity');

        // if ($this->quantity > $remainingPlates) {
        //     session()->flash('error', 'You cannot dispatch more plates than available.');
        //     return;
        // }

        $lastDispatchNote = DispatchNote::where('job_order_id', $this->jobOrder->id)
            ->orderBy('id', 'desc')
            ->first();

        // Calculate the next dispatch number
        $nextDispatchNumber = $lastDispatchNote ? $lastDispatchNote->id + 1 : 1;

        // Generate the dispatch number
        $dispatchNumber = $this->jobOrder->job_number . '-' . $this->quantity . '-' . $nextDispatchNumber;

        // Create the dispatch note
        DispatchNote::create([
            'job_order_id' => $this->jobOrder->id,
            'dispatch_number' => $dispatchNumber,
            'quantity' => $this->quantity,
            'dispatched_at' => now(),
        ]);

         // Refresh the jobOrder to get the updated dispatchNotes
        $this->jobOrder->load('dispatchNotes');
        $this->dispatchedPlates =$this->jobOrder->dispatchNotes->sum('quantity');


        if($this->dispatchedPlates === $this->plates){
            $this->canInvoice = true;

        }


        // Check if all plates have been dispatched and update the job order status
        $totalDispatched = $this->jobOrder->dispatchNotes->sum('quantity');

        if ($totalDispatched >= $this->jobOrder->plates) {
            // Update the job order status
            $this->jobOrder->status = 'dispatch';  // Change this as per your business logic
            $this->jobOrder->save();
        }

        session()->flash('success', 'Dispatch note created successfully.');

        // Reset the quantity field
        $this->quantity = '';

        // return $this->redirect('/job-orders', navigate: true);
    }


    public function render()
    {
        $bodyAttributes = 'x-data="{ page: \'dispatchNote\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
        x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
        :class="{\'dark bg-gray-900\': darkMode === true}"';

        return view('livewire.dispatch.dispatch-note')->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
