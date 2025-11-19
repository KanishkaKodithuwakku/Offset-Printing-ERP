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

        // Get all VendorBillPayments for this bill
        // Note: VendorBillPayments without payment_date are expense lines (bill creation)
        // VendorBillPayments with payment_date are actual payments
        $billPayments = $bill->vendorBillPayments()->whereNull('payment_date')->get();

        $entryIdsToDelete = [];

        // For each VendorBillPayment (expense line), find and delete its corresponding Entry
        foreach ($billPayments as $billPayment) {
            // Find entries that match this bill payment:
            // - Has an EntryItem with the expense ledger_id (debit) and matching amount
            // - Has an EntryItem with accounts payable ledger_id 16 (credit) and matching amount
            // - Same date as bill
            // - Same vendor_id
            $entries = Entry::with('entryitems')
                ->where('entrytype_id', $entryTypeId)
                ->whereDate('date', $bill->date)
                ->whereHas('entryitems', function ($query) use ($bill, $billPayment, $accountsPayableLedgerId) {
                    $query->where('customer_id', $bill->vendor_id)
                          ->where(function($q) use ($billPayment, $accountsPayableLedgerId) {
                              // Match expense ledger (debit)
                              $q->where(function($subQ) use ($billPayment) {
                                  $subQ->where('ledger_id', $billPayment->ledger_id)
                                       ->where('dc', 'D')
                                       ->where('amount', $billPayment->amount);
                              })
                              // OR match accounts payable ledger (credit)
                              ->orWhere(function($subQ) use ($accountsPayableLedgerId, $billPayment) {
                                  $subQ->where('ledger_id', $accountsPayableLedgerId)
                                       ->where('dc', 'C')
                                       ->where('amount', $billPayment->amount);
                              });
                          });
                })
                ->get();

            foreach ($entries as $entry) {
                // Verify this entry has both the expense ledger (debit) and accounts payable (credit) entry items
                $hasExpenseLedger = $entry->entryitems->where('ledger_id', $billPayment->ledger_id)
                    ->where('dc', 'D')
                    ->where('amount', $billPayment->amount)
                    ->count() > 0;

                $hasAccountsPayable = $entry->entryitems->where('ledger_id', $accountsPayableLedgerId)
                    ->where('dc', 'C')
                    ->where('amount', $billPayment->amount)
                    ->count() > 0;

                // Only delete if this entry has both entry items (complete bill creation entry)
                if ($hasExpenseLedger && $hasAccountsPayable && !in_array($entry->id, $entryIdsToDelete)) {
                    $entryIdsToDelete[] = $entry->id;

                    // Delete all entry items for this entry (both debit and credit)
                    EntryItem::where('entry_id', $entry->id)->delete();
                }
            }
        }

        // Delete the entries
        if (!empty($entryIdsToDelete)) {
            Entry::whereIn('id', $entryIdsToDelete)->delete();
        }

        // Also delete payment entries if bill has payments
        // Only process VendorBillPayments that have payment_date (actual payments, not expense lines)
        $actualPayments = $bill->vendorBillPayments()->whereNotNull('payment_date')->get();
        if ($actualPayments && $actualPayments->count() > 0) {
            $paymentEntryIdsToDelete = [];

            foreach ($actualPayments as $payment) {
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
