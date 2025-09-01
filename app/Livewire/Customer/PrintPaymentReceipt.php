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
        $this->payment = Payment::with(['customer', 'creditApplications.invoice'])
            ->findOrFail($paymentId);
    }

    public function render()
    {
        $payment = Payment::with('customer')->findOrFail($this->paymentId);

        // Get invoice allocations from payment_details
        $paymentDetails = $payment->paymentDetails()->with('invoice')->get();
        $total = PaymentDetail::where('payment_id', $this->paymentId)
                      ->sum('amount');

        // Get overpayment (if any) from credit applications related to this payment
        $creditApplications = \App\Models\CreditApplication::whereIn('invoice_id', $paymentDetails->pluck('invoice_id'))
            ->with('invoice')
            ->get();

        $bodyAttributes = 'x-data="{ page: \'orderList\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
            x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                    $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
            :class="{\'dark bg-gray-900\': darkMode === true}"';

        return view('livewire.customer.print-payment-receipt', [
            'payment' => $payment,
            'paymentDetails' => $paymentDetails,
            'creditApplications' => $creditApplications,
            'total' => $total,
        ])->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }

}
