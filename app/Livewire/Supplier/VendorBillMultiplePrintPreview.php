<?php

namespace App\Livewire\Supplier;

use App\Models\VendorBillPayment;
use App\Models\VendorPayment;
use App\Models\Ledger;
use Livewire\Component;

class VendorBillMultiplePrintPreview extends Component
{
    public $payments = [];
    public $totalAmount = 0;
    public $paymentDate;
    public $paymentMethod;
    public $checkNumber;
    public $checkDate;
    public $remark;
    public $payment_voucher_no;
    public $ledger;
    public $receiptNumber;

    public function mount($id = null)
    {
                if ($id) {
            // Get payment data directly from the vendor payment ID
            $vendorPayment = VendorPayment::with(['vendorBills.vendor', 'bankLedger'])->findOrFail($id);

            // Use the vendor bills directly linked to this payment
            $this->payments = $vendorPayment->vendorBills->map(function($bill) use ($vendorPayment) {
                // Create a payment object that matches the expected structure for the view
                return (object) [
                    'id' => $bill->id,
                    'amount' => $bill->total_amount - $bill->amount_due, // Calculate amount paid
                    'payment_date' => $vendorPayment->payment_date,
                    'vendorBill' => $bill,
                ];
            });

            $this->totalAmount = $vendorPayment->total_amount;
            $this->paymentDate = $vendorPayment->payment_date;
            $this->paymentMethod = $vendorPayment->payment_method;
            $this->checkNumber = $vendorPayment->check_number;
            $this->checkDate = $vendorPayment->check_date;
            $this->remark = '';
            $this->payment_voucher_no = $vendorPayment->payment_voucher_no;
            $this->ledger = $vendorPayment->bankLedger;
            $this->receiptNumber = $vendorPayment->receipt_number;
        } else {
            // Fallback to session data for backward compatibility
            $paymentIds = session('multiple_payment_ids', []);

            if (empty($paymentIds)) {
                session()->flash('error', 'No payment data found.');
                return redirect()->route('vendor-bill-payment-form');
            }

            // Load all payments with their related data
            $this->payments = VendorBillPayment::with(['vendorBill.vendor', 'ledger'])
                ->whereIn('id', $paymentIds)
                ->orderBy('id')
                ->get();

            // Get other payment details from session
            $this->totalAmount = session('total_payment_amount', 0);
            $this->paymentDate = session('payment_date');
            $this->paymentMethod = session('payment_method');
            $this->checkNumber = session('check_number');
            $this->checkDate = session('check_date');
            $this->remark = session('remark');
            $this->payment_voucher_no = session('payment_voucher_no');

            $ledgerId = session('ledger_id');
            $this->ledger = Ledger::find($ledgerId);
            $this->receiptNumber = session('receipt_number');

            // Clear session data after loading
            session()->forget([
                'multiple_payment_ids',
                'total_payment_amount',
                'payment_date',
                'payment_method',
                'check_number',
                'check_date',
                'remark',
                'payment_voucher_no',
                'ledger_id',
                'receipt_number'
            ]);
        }
    }

    public function render()
    {
        $bodyAttributes = 'x-data="{ page: \'multiplePrintPreview\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
            x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                    $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
            :class="{\'dark bg-gray-900\': darkMode === true}"';

        return view('livewire.supplier.vendor-bill-multiple-print-preview', [
            'payments' => $this->payments,
            'totalAmount' => $this->totalAmount,
            'paymentDate' => $this->paymentDate,
            'paymentMethod' => $this->paymentMethod,
            'checkNumber' => $this->checkNumber,
            'checkDate' => $this->checkDate,
            'remark' => $this->remark,
            'payment_voucher_no' => $this->payment_voucher_no,
            'ledger' => $this->ledger,
            'receiptNumber' => $this->receiptNumber,
        ])->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
