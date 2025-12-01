<?php

namespace App\Livewire\Job;

use App\Models\User;
use Livewire\Component;
use App\Models\JobOrder;
use App\Models\JobOrderItem;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class JobOrderList extends Component
{
    use WithPagination;

    public $statusFilter = '';
    public $searchTerm = '';
    public $authUser;
    public $role;
    public $status;
    public $startDate;
    public $endDate;

    public $user;

    public function updatingStartDate()
    {
        $this->resetPage();
    }
    public function updatingEndDate()
    {
        $this->resetPage();
    }

        public function updatingSearchTerm()
    {
        $this->resetPage();
    }

    public function clearSearch()
    {
        $this->searchTerm = '';
        $this->resetPage();
    }

    public function searchByJobNumber($jobNumber)
    {
        $this->searchTerm = $jobNumber;
        $this->resetPage();
    }


    public function mount()
    {
        $this->authUser = auth()->user();
        $this->role = $this->authUser->mode;
    }

    public function viewJobOrder($jobOrderId)
    {
        $jobOrder = JobOrder::find($jobOrderId);
        $this->status = $jobOrder->status;
        return redirect()->route('job-order.view', ['jobOrderId' => $jobOrderId]);
    }

    // Delete function
    public function deleteJobOrder(JobOrder $jobOrder)
    {

        if (!$jobOrder) {
            session()->flash('error', 'Job Order not found.');
            return;
        }

        if (!in_array($jobOrder->status, ['pending', 'designing','printing'])) {
            session()->flash('error', 'Only job orders with status pending or designing can be deleted.');
            return;
        }

        if ($jobOrder->status === 'pending' || $jobOrder->status = 'designing' || $jobOrder->status = 'printing') {
            // Permanently delete if status is 'pending'
            $jobOrder->forceDelete();
            session()->flash('success', 'Job Order permanently deleted successfully!');
        } else {
            // Soft delete otherwise
            // $jobOrder->delete();
            session()->flash('success', 'Job Order soft deleted successfully!');
        }

        // Redirect to job order list
        return $this->redirect('/job-orders', navigate: true);
    }

    /**
     * Check if all job order items are fully dispatched
     */
    private function checkIfFullyDispatched($orderId)
    {
        $jobOrderItems = JobOrderItem::where('order_id', $orderId)->get();
        
        if ($jobOrderItems->isEmpty()) {
            return false;
        }

        foreach ($jobOrderItems as $jobOrderItem) {
            $dispatchedQty = DB::table('dispatch_items')
                ->where('job_order_item_id', $jobOrderItem->id)
                ->sum('quantity');
            
            // If any item is not fully dispatched, return false
            if ($dispatchedQty < $jobOrderItem->quantity) {
                return false;
            }
        }

        return true;
    }

    public function render()
    {
        $jobOrdersQuery = JobOrder::query();

        if ($this->statusFilter) {
            $jobOrdersQuery->where('status', $this->statusFilter);
        }
        // else {
        //         if ($this->role == 'design') {
        //             $jobOrdersQuery->whereIn('status', [
        //                 'pending',
        //                 'designing',
        //                 'printing',
        //                 'ready-to-invoice',
        //                 'invoicing',
        //                 'invoiced',
        //             ]);

        //             // Custom status order first, then by created_at
        //             $jobOrdersQuery->orderByRaw("
        //     FIELD(status, 'pending', 'designing', 'printing', 'ready-to-invoice', 'invoicing', 'invoiced')
        // ")->orderBy('created_at', 'desc');
        //         } elseif ($this->role == 'dispatch') {
        //             $jobOrdersQuery->whereIn('status', ['printing', 'dispatching', 'paused', 'dispatched']);
        //         } elseif ($this->role == 'billing') {
        //             $jobOrdersQuery->whereIn('status', ['ready-to-invoice', 'invoiced', 'invoicing']);
        //         }
        //     }

        // if ($this->searchTerm) {
        //     $jobOrdersQuery->where(function ($query) {
        //         $query->where('job_number', 'like', "{$this->searchTerm}%")
        //             ->orWhereHas('customer', function ($q) {
        //                 $q->where('name', 'like', "{$this->searchTerm}%");
        //             })
        //             ->orWhereHas('assignTo', function ($q) {
        //                 $q->where('name', 'like', "%{$this->searchTerm}%")
        //                     ->orWhere('email', 'like', "%{$this->searchTerm}%");
        //             });
        //     });
        // }



        //add job serch by job id
        if (strlen($this->searchTerm) > 0) {
            $term = trim($this->searchTerm);

            $jobOrdersQuery->where(function ($q) use ($term) {
                $q->where('job_number', '=', $term)
                    // Priority 2: JOB number starts with search term
                    ->orWhere('job_number', 'like', "{$term}%")
                    // Priority 3: JOB number contains search term
                    ->orWhere('job_number', 'like', "%{$term}%")
                    // <-- NEW: match on the primary key as well
                    ->orWhere('id', $term)
                    // Search by description
                    ->orWhere('description', 'like', "%{$term}%")
                    ->orWhereHas('customer', function ($q) use ($term) {
                        $q->where('name', 'like', "{$term}%");
                    })
                    ->orWhereHas('assignTo', function ($q) use ($term) {
                        $q->where('name', 'like', "%{$term}%")
                            ->orWhere('email', 'like', "%{$term}%");
                    });
            });
        }


        if ($this->startDate) {
            $jobOrdersQuery->whereDate('created_at', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $jobOrdersQuery->whereDate('created_at', '<=', $this->endDate);
        }


        if ($this->user) {
            $jobOrdersQuery->where('assign_to', '=', $this->user);
        }


        // Ordering
        if ($this->statusFilter === 'cancelled') {
            $jobOrdersQuery->orderBy('updated_at', 'desc');
        } else {
            $jobOrdersQuery->orderBy('created_at', 'desc');
        }
        $jobOrders = $jobOrdersQuery->paginate(perPage: 25);

        // Add isFullyDispatched flag to each job order for the view
        $jobOrders->getCollection()->transform(function ($jobOrder) {
            $jobOrder->isFullyDispatched = $this->checkIfFullyDispatched($jobOrder->id);
            return $jobOrder;
        });

        $bodyAttributes = 'x-data="{ page: \'jobOrderList\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
            x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                    $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
            :class="{\'dark bg-gray-900\': darkMode === true}"';



        return view('livewire.job.job-order-list', ['jobOrders' => $jobOrders, 'users' => User::all()])->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
