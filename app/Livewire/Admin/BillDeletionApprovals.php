<?php

namespace App\Livewire\Admin;

use Livewire\WithPagination;
use Livewire\Component;
use App\Models\BillDeletionApproval;
use App\Models\VendorBill;
use App\Models\Entry;
use App\Models\EntryItem;
use App\Models\EntryType;
use App\Models\Ledger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BillDeletionApprovals extends Component
{
    use WithPagination;

    public $authUser;
    public $selectedApprovals = [];

    public function mount()
    {
        $this->authUser = auth()->user();
    }

    public function toggleSelectedApproval($id)
    {
        if (in_array($id, $this->selectedApprovals)) {
            $this->selectedApprovals = array_diff($this->selectedApprovals, [$id]);
        } else {
            $this->selectedApprovals[] = $id;
        }
    }

    public function approve($id = null)
    {
        try {
            $approvalIds = $id ? [$id] : $this->selectedApprovals;

            if (empty($approvalIds)) {
                session()->flash('error', 'Please select at least one bill to approve.');
                return;
            }

            DB::beginTransaction();

            foreach ($approvalIds as $approvalId) {
                $approval = BillDeletionApproval::with('vendorBill.vendorBillPayments')->findOrFail($approvalId);

                if ($approval->status !== 'pending') {
                    continue;
                }

                $bill = $approval->vendorBill;

                if (!$bill) {
                    // Bill already deleted, just update approval status
                    $approval->update([
                        'status' => 'approved',
                        'approved_by' => $this->authUser->id,
                        'approved_at' => now(),
                    ]);
                    continue;
                }

                // Store bill snapshot data before deletion
                $billSnapshot = [
                    'bill_date' => $bill->date,
                    'vendor_name' => $bill->vendor ? $bill->vendor->name : null,
                    'ref_no' => $bill->ref_no,
                    'total_amount' => $bill->total_amount,
                ];

                // Reverse all accounting entries related to this bill
                $this->reverseBillEntries($bill);

                // Delete related vendor bill payments
                $bill->vendorBillPayments()->delete();

                // Update approval status with bill snapshot data (before deleting bill)
                $approval->update(array_merge([
                    'status' => 'approved',
                    'approved_by' => $this->authUser->id,
                    'approved_at' => now(),
                ], $billSnapshot));

                // Delete the bill (this will set vendor_bill_id to null due to set null cascade)
                $bill->delete();
            }

            DB::commit();
            $this->selectedApprovals = [];
            session()->flash('success', 'Bill(s) approved and deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to approve bill deletion', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'Failed to approve bill deletion: ' . $e->getMessage());
        }
    }

    protected function reverseBillEntries($bill)
    {
        // Find all entries related to this bill and delete them
        // We need to find entries by checking entry items that reference this bill
        // Since entries are created when bills are created and when payments are made,
        // we need to delete both types

        $entryTypeId = EntryType::where('label', 'vendor')->value('id');
        $accountsPayableLedgerId = 16; // Accounts Payable ledger ID (from BillPaymentForm)

        // Get all entries that might be related to this bill
        // We'll search by vendor_id and date range
        $entries = Entry::with('entryitems')
            ->where('entrytype_id', $entryTypeId)
            ->whereHas('entryitems', function ($query) use ($bill) {
                $query->where('customer_id', $bill->vendor_id);
            })
            ->whereDate('date', $bill->date)
            ->get();

        $entryIdsToDelete = [];

        foreach ($entries as $entry) {
            // Check if this entry is related to our bill by checking narration or amount
            $isRelated = false;
            if (stripos($entry->narration, $bill->ref_no) !== false ||
                stripos($entry->narration, $bill->vendor->name) !== false) {
                $isRelated = true;
            }

            if ($isRelated) {
                $entryIdsToDelete[] = $entry->id;

                // Delete all entry items for this entry first
                EntryItem::where('entry_id', $entry->id)->delete();
            }
        }

        // Delete the entries
        if (!empty($entryIdsToDelete)) {
            Entry::whereIn('id', $entryIdsToDelete)->delete();
        }

        // Also delete payment entries if bill has payments
        if ($bill->vendorBillPayments && $bill->vendorBillPayments->count() > 0) {
            $paymentEntryIdsToDelete = [];

            foreach ($bill->vendorBillPayments as $payment) {
                // Find payment entries (these credit Accounts Payable and debit Bank/Cash)
                $paymentEntries = Entry::with('entryitems')
                    ->where('entrytype_id', $entryTypeId)
                    ->whereHas('entryitems', function ($query) use ($accountsPayableLedgerId, $bill) {
                        $query->where('ledger_id', $accountsPayableLedgerId)
                              ->where('customer_id', $bill->vendor_id);
                    })
                    ->whereDate('date', $payment->payment_date)
                    ->get();

                foreach ($paymentEntries as $paymentEntry) {
                    // Check if this payment entry is related to our bill
                    $isPaymentRelated = false;
                    if (stripos($paymentEntry->narration, $bill->ref_no) !== false ||
                        stripos($paymentEntry->narration, $bill->vendor->name) !== false ||
                        abs($paymentEntry->dr_total - $payment->amount) < 0.01) {
                        $isPaymentRelated = true;
                    }

                    if ($isPaymentRelated) {
                        $paymentEntryIdsToDelete[] = $paymentEntry->id;

                        // Delete all entry items for this payment entry first
                        EntryItem::where('entry_id', $paymentEntry->id)->delete();
                    }
                }
            }

            // Delete the payment entries
            if (!empty($paymentEntryIdsToDelete)) {
                Entry::whereIn('id', $paymentEntryIdsToDelete)->delete();
            }
        }
    }

    public function reject($id = null)
    {
        try {
            $approvalIds = $id ? [$id] : $this->selectedApprovals;

            if (empty($approvalIds)) {
                session()->flash('error', 'Please select at least one bill to reject.');
                return;
            }

            foreach ($approvalIds as $approvalId) {
                $approval = BillDeletionApproval::findOrFail($approvalId);

                if ($approval->status === 'pending') {
                    $approval->update([
                        'status' => 'rejected',
                        'approved_by' => $this->authUser->id,
                        'approved_at' => now(),
                    ]);
                }
            }

            $this->selectedApprovals = [];
            session()->flash('success', 'Bill deletion request(s) rejected.');
        } catch (\Exception $e) {
            Log::error('Failed to reject bill deletion', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'Failed to reject bill deletion: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $bodyAttributes = 'x-data="{ page: \'BillDeletionApprovals\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
        x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
        :class="{\'dark bg-gray-900\': darkMode === true}"';

        $approvals = BillDeletionApproval::with(['vendorBill.vendor', 'requestedBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(25);

        return view('livewire.admin.bill-deletion-approvals', [
            'approvals' => $approvals
        ])->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
