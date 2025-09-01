<?php

namespace App\Livewire\Customer;

use App\Models\CreditApplication;
use App\Models\CustomerCredit;
use App\Models\Entry;
use App\Models\EntryItem;
use App\Models\EntryType;
use App\Models\Invoice;
use App\Models\Ledger;
use App\Models\Payment;
use App\Models\PaymentDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class PaymentList extends Component
{
    use WithPagination;

    public $searchTerm = '';
    public $statusFilter;
    public $methodFilter = '';
    public $startDate;
    public $endDate;
    public $authUser = null;

    public $cancelReason = '';


    public function mount($payment = null)
    {
        $this->authUser = auth()->user();
        if ($payment) {
            $this->cancelPayment($payment);

            // after cancelling, drop the query-string off
            return redirect()->route('payments.index');
        }
    }

    public function applyFilters()
    {
        $this->resetPage();
        $payments = Payment::query();
        if ($this->startDate) {
            $payments->whereDate('created_at', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $payments->whereDate('created_at', '<=', $this->endDate);
        }

        // Get filtered data
        $payments->get();
    }


    public function cancelPayment($paymentId)
    {


        if ($this->cancelReason === '') {
            session()->flash('error', 'Payment cancelation reason is required!.');
            return;
        }

        DB::beginTransaction();

        try {
            $payment = Payment::with(['entry.entryitems', 'paymentDetails'])->findOrFail($paymentId);

            if ($payment->status === 'cancelled') {
                session()->flash('error', 'This payment is already cancelled.');
                return;
            }

            // 1) Build a reversal journal entry
            $receiptTypeId = EntryType::where('label', 'receipt')->value('id');

            $reversal = Entry::create([
                'entrytype_id' => $receiptTypeId,
                'number'       => Entry::getNextNumber($receiptTypeId),
                'customer_id'  => $payment->customer_id,
                'branch_id'    => $payment->branch_id,
                'date'         => now(),
                'narration'    => "Reversal of Payment {$payment->payment_code}",
                'dr_total'     => $payment->entry->cr_total,  // flip
                'cr_total'     => $payment->entry->dr_total,
            ]);


            // 2) Flip every original line‐item
            foreach ($payment->entry->entryitems as $line) {
                EntryItem::create([
                    'entry_id'    => $reversal->id,
                    'ledger_id'   => $line->ledger_id,
                    'dc'          => $line->dc === 'D' ? 'C' : 'D',
                    'amount'      => $line->amount,
                    'customer_id' => $line->customer_id,
                    'branch_id'   => $line->branch_id,
                ]);
            }


            // 3) Un‐apply each PaymentDetail and restore Invoices & Credits
            $arLedgerId        = Ledger::where('name', 'Accounts Receivable')->value('id');
            $custCreditLedgerId = Ledger::where('name', 'Customer Credit')->value('id');

            foreach ($payment->paymentDetails as $detail) {
                // a) Restore the invoice
                $inv = Invoice::findOrFail($detail->invoice_id);
                $inv->amount_due     = $inv->amount_due + $detail->amount;
                $inv->payment_status = $inv->amount_due === 0 ? 'paid' : 'partial';
                $inv->status         = $inv->amount_due === 0 ? 'invoiced' : 'invoicing';
                $inv->save();

                // b) If this was a credit‐memo application, rollback the CustomerCredit
                if ($detail->is_credit) {
                    // find the credit_application record
                    $app = DB::table('credit_applications')
                        ->where('invoice_id', $detail->invoice_id)
                        ->where('amount_applied', $detail->amount)
                        ->first();

                    if ($app) {
                        // bump up the CustomerCredit balance
                        $cc = CustomerCredit::find($app->customer_credit_id);
                        $cc->amount += $app->amount_applied;
                        $cc->save();


                        // Create an entry to reverse the credit application (double entry accounting)
                        $entry = Entry::create([
                            'entrytype_id' => $receiptTypeId,  // Use the appropriate entry type for reversal
                            'number' => Entry::getNextNumber($receiptTypeId),
                            'customer_id' => $cc->customer_id,
                            'branch_id' => $cc->branch_id,
                            'date' => now(),
                            'narration' => "Reversal of Credit Application for Invoice ID: {$detail->invoice_id}",
                            'dr_total' => $app->amount_applied,  // Debit the customer credit
                            'cr_total' => $app->amount_applied,  // Credit the accounts receivable
                        ]);

                        // Debit the Customer Credit (reversing the original entry)
                        EntryItem::create([
                            'entry_id' => $entry->id,
                            'ledger_id' => Ledger::where('name', 'Customer Credit')->value('id'),
                            'dc' => 'D',  // Debit
                            'amount' => $app->amount_applied,
                            'customer_id' => $cc->customer_id,
                            'branch_id' => $cc->branch_id,
                        ]);

                        // Credit the Accounts Receivable (reversing the original entry)
                        EntryItem::create([
                            'entry_id' => $entry->id,
                            'ledger_id' => Ledger::where('name', 'Accounts Receivable')->value('id'),
                            'dc' => 'C',  // Credit
                            'amount' => $app->amount_applied,
                            'customer_id' => $cc->customer_id,
                            'branch_id' => $cc->branch_id,
                        ]);

                        // delete the application row
                        DB::table('credit_applications')->where('id', $app->id)->delete();
                    }
                }

                $inv->update(['amount_due' => $inv->total_amount, 'payment_status' => 'unpaid']);
                $inv->save();
            }



            // 4) Soft-cancel the payment and its details
            $payment->update(['status' => 'cancelled', 'deleted_by' => $this->authUser->id,'cancel_reason'=> $this->cancelReason]);
            foreach ($payment->paymentDetails as $detail) {
                $detail->update(['status' => 'cancelled']); // Only update status, do not delete
            }
            // $payment->delete(); // Do not delete the payment

            DB::commit();
            session()->flash('message', 'Payment reversed successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            session()->flash('error', 'Cancellation failed: ' . $e->getMessage());
        }

        // redirect back to your list
        return redirect()->route('payments.index');
    }


    public function render()
    {
        $query = Payment::query()
            ->withSum('paymentDetails', 'amount')
            ->withSum([
                'paymentDetails as credit_amount' => function ($q) {
                    $q->where('is_credit', 1);
                },
            ], 'amount');

        // Status/method filter
        if (!empty($this->statusFilter)) {
            if ($this->statusFilter === 'cancelled') {
                $query->where('status', 'cancelled');
            } else {
                $query->where('method', $this->statusFilter);
                $query->where('status', '!=', 'cancelled');
            }
        } else {
            // If no filter, exclude cancelled payments
            $query->where('status', '!=', 'cancelled');
        }

        // Search filter
        if ($this->searchTerm) {
            $query->where(function ($q) {
                $q->where('payment_code', 'like', "%{$this->searchTerm}%")
                    ->orWhere('amount', 'like', "%{$this->searchTerm}%")
                    ->orWhere('check_number', 'like', "%{$this->searchTerm}%")
                    ->orWhereHas('customer', fn($q2) =>
                    $q2->where('name', 'like', "%{$this->searchTerm}%"));
            });
        }

        // Date range filter
        if ($this->startDate) {
            $start = Carbon::createFromFormat('Y-m-d', $this->startDate)->startOfDay();
            $query->whereDate('created_at', '>=', $start);
        }

        if ($this->endDate) {
            $end = Carbon::createFromFormat('Y-m-d', $this->endDate)->endOfDay();
            $query->whereDate('created_at', '<=', $end);
        }

        $payments = $query->orderBy('created_at', 'desc')->paginate(25);

        $bodyAttributes = 'x-data="{ page: \'RecevedPayments\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
            x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                    $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
            :class="{\'dark bg-gray-900\': darkMode === true}"';

        return view('livewire.customer.customer-payment-list', [
            'payments' => $payments
        ])->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
