<?php

namespace App\Livewire\Customer;

use App\Models\PaymentDetail;
use Livewire\Component;
use App\Models\Payment;
use App\Models\Customer;
use Illuminate\Support\Carbon;
use App\Models\Invoice;

class PrintPaymentReceipt extends Component
{
    public $payment;
    public $paymentId;
    public $invoices = [];

    public function mount($paymentId)
    {
        $this->payment = Payment::with(['customer', 'creditApplications.invoice', 'creditApplications', 'customerCredit', 'entry.entryitems', 'paymentDetails'])
            ->findOrFail($paymentId);
        $this->paymentId = $paymentId;
    }

    public function render()
    {
        $payment = Payment::with(['customer', 'creditApplications', 'customerCredit', 'paymentDetails', 'entry.entryitems'])->findOrFail($this->paymentId);

        // Get invoice allocations from payment_details - only for this specific payment
        $paymentDetails = $payment->paymentDetails()->with('invoice')->get();
        $total = PaymentDetail::where('payment_id', $this->paymentId)
                      ->sum('amount');

        // Get all credit applications for invoices in this payment (for display in table)
        $invoiceIds = $paymentDetails->pluck('invoice_id')->filter()->unique();
        $invoicesWithCredit = collect();
        $creditAmountsByInvoice = collect(); // Store credit amounts per invoice
        if ($invoiceIds->isNotEmpty()) {
            // Get invoice IDs with credit
            $invoicesWithCredit = \App\Models\CreditApplication::whereIn('invoice_id', $invoiceIds)
                ->pluck('invoice_id')
                ->unique();
            
            // Get credit amounts per invoice using direct query to access amount_applied column
            $creditAmountsByInvoice = \App\Models\CreditApplication::whereIn('invoice_id', $invoiceIds)
                ->selectRaw('invoice_id, SUM(amount_applied) as total_credit')
                ->groupBy('invoice_id')
                ->pluck('total_credit', 'invoice_id');
        }

        $bodyAttributes = 'x-data="{ page: \'orderList\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
            x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                    $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
            :class="{\'dark bg-gray-900\': darkMode === true}"';

        return view('livewire.customer.print-payment-receipt', [
            'payment' => $payment,
            'paymentDetails' => $paymentDetails,
            'invoicesWithCredit' => $invoicesWithCredit,
            'creditAmountsByInvoice' => $creditAmountsByInvoice,
            'total' => $total,
        ])->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }

}
