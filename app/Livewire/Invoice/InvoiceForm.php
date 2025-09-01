<?php

namespace App\Livewire\Invoice;

use App\Helpers\NumberGenerator;
use App\Models\Customer;
use App\Models\CustomerOrder;
use App\Models\Invoice;
use App\Models\JobOrder;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class InvoiceForm extends Component
{
    public $invoiceId;
    public $orderNumber;
    public $status;
    public $totalAmount;
    public $invoiceData;
    public $invoice;
    public $customer;
    public $order;
    public $order_id, $order_type, $customer_id, $invoice_number, $advance_payment, $payment_method, $bank_name, $cheque_no, $cheque_realization_date, $invoice_details;
    public $searchCustomer = '';
    public $searchResultsCustomer = [];
    public $searchOrder = '';
    public $searchResultsOrder = [];
    public $searchResults = [];
    public $order_customer, $order_number, $created_at, $total_amount, $customer_name, $customer_address, $customer_phone, $customer_email, $customer_city;

    public function mount($order_id = null)
    {
        // Initialize the properties or fetch data from the database
        $this->order_id = $order_id;
        $this->status = 'plan';
        $this->totalAmount = 0;
        $this->invoiceData = [];
    }

    public function updatedSearchCustomer()
    {
        if (strlen($this->searchCustomer) > 1) {
            $customer = Customer::select('id', 'name', 'email', 'phone', 'city')
                ->where('name', 'like', "%{$this->searchCustomer}%")
                ->orWhere('email', 'like', "%{$this->searchCustomer}%")
                ->limit(5)
                ->get();


            $this->searchResultsCustomer = $customer->map(function ($customer) {
                return [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'phone' => $customer->phone,
                    'city' => $customer->city,
                ];
            })->toArray();

            Log::info('Search Results: ' . json_encode($this->searchResultsCustomer));
        } else {
            $this->searchResultsCustomer = [];
            Log::info('Search Results Cleared');
        }
    }


    public function assignCustomer($customerId)
    {
        // Find the customer using the provided customer ID
        $customer = Customer::find($customerId);
        if (!$customer) {
            return response()->json(['error' => 'Customer not found'], 404);
        }
        $this->customer_id = $customer->id;
        $this->searchResultsCustomer = [];
        $this->searchCustomer = $customer->name;
        $this->customer_name = $customer->name;
        $this->customer_address =  $customer->address;
        $this->customer_phone =  $customer->phone;
        $this->customer_email =  $customer->email;
        $this->customer_city =  $customer->city;
        return response()->json(['message' => 'Customer assigned successfully'], 200);
    }


    public function updatedSearchOrder()
    {
        $query = CustomerOrder::with('customer')  // Eager load the customer relationship
            ->select('id', 'customer_id', 'order_number', 'total_amount')

            // Filter by status 'released'
            ->where('status', 'released');

        // If customer_id is set, filter orders by customer_id
        if (!empty($this->customer_id)) {
            $query->where('customer_id', $this->customer_id);
        }

        // If searchOrder is set, filter orders by order_number or total_amount
        if (strlen($this->searchOrder) > 1) {
            $query->where(function ($q) {
                $q->where('order_number', 'like', "%{$this->searchOrder}%")
                    ->orWhere('total_amount', 'like', "%{$this->searchOrder}%");
            });
        }

        // Limit the results to 5 and fetch the orders
        $orders = $query->limit(5)->get();

        // Map through orders and get the customer name
        $this->searchResultsOrder = $orders->map(function ($order) {
            $customer = $order->customer;  // Since we already eager-loaded the customer
            return [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'total_amount' => $order->total_amount,
                'order_customer_name' => $customer ? $customer->name : 'No customer',  // Safely handle null
            ];
        })->toArray();

        Log::info('Search Results: ' . json_encode($this->searchResultsOrder));
    }

    public $orderSelected = false;

    public function assignOrder($orderId)
    {
        $order = CustomerOrder::find($orderId);
        if (!$order) {
            session()->flash('error', 'Order not found');
            return;
        }

        $orderType = CustomerOrder::class;
        // Assign order fields
        $this->order_id = $orderId;
        $this->order_type = $orderType;
        $this->order_customer = $order->customer['name'];
        $this->order_number = $order->order_number;
        $this->searchOrder = $order->order_number;
        $this->total_amount = $order->total_amount;
        $this->advance_payment = $order->advance_payment;
        $this->payment_method = $order->payment_method;
        $this->bank_name = $order->bank_name;
        $this->cheque_no = $order->cheque_no;
        $this->cheque_realization_date = $order->cheque_realization_date;
        $this->created_at = $order->created_at;
        $this->searchResultsOrder = [];
        $this->orderSelected = true;

        session()->flash('message', 'Order assigned successfully');
    }
    public function accountsUpdate($invoice)
    {
        // Assuming the payment method is 'cash', adjust as necessary.
        if ($this->payment_method === 'cash') {
            // Insert the Cash Account Dr, Sales Account Cr entries in the transactions table
            Transaction::create([
                'sale_id' => $invoice->id,
                'account_id' => 1, // Cash Account, you need to reference the correct account ID
                'debit' => $invoice->total_amount,
                'credit' => 0,
                'transaction_type' => 'debit',  // Debit transaction
            ]);

            Transaction::create([
                'sale_id' => $invoice->id,
                'account_id' => 2, // Sales Account, adjust accordingly
                'debit' => 0,
                'credit' => $invoice->total_amount,
                'transaction_type' => 'credit',  // Credit transaction
            ]);
        } elseif ($this->payment_method  === 'bank') {
            // Handle bank payment similarly with Bank Account Dr, Sales Account Cr
            Transaction::create([
                'sale_id' => $invoice->id,
                'account_id' => 3, // Bank Account, replace with the actual account ID
                'debit' => $invoice->total_amount,
                'credit' => 0,
                'transaction_type' => 'debit',
            ]);

            Transaction::create([
                'sale_id' => $invoice->id,
                'account_id' => 2, // Sales Account
                'debit' => 0,
                'credit' => $invoice->total_amount,
                'transaction_type' => 'credit',
            ]);
        } else {
            // If it's a credit sale, update accordingly
            // Assuming Receivables Account Dr and Sales Revenue Account Cr
            Transaction::create([
                'sale_id' => $invoice->id,
                'account_id' => 4, // Receivables Account, replace with the correct ID
                'debit' => $invoice->total_amount,
                'credit' => 0,
                'transaction_type' => 'debit',
            ]);

            Transaction::create([
                'sale_id' => $invoice->id,
                'account_id' => 5, // Sales Revenue Account, adjust accordingly
                'debit' => 0,
                'credit' => $invoice->total_amount,
                'transaction_type' => 'credit',
            ]);
        }
    }

    public function createInvice()
    {
        $customerOrder = CustomerOrder::find($this->order_id);
        # Create a new customer
        $invoice = Invoice::create([
            'order_id' => $this->order_id,
            'order_type' => $this->order_type,
            'status' => 'invoiced',
            'customer_id' => $this->customer_id,
            'branch_id' => $this->authUser->branch->id,
            'invoice_number' => NumberGenerator::generateInvoiceNumber('invoices', 'INV',  $invoiceNumber = NumberGenerator::generateInvoiceNumber('invoices', 'INV', env('BRANCH_CODE', 'default'), 5), 5),
            'total_amount' => $this->total_amount,
            'advance_payment' => $this->advance_payment,
            'payment_method' => $this->payment_method,
            'bank_name' => $this->bank_name,
            'cheque_no' => $this->cheque_no,
            'cheque_realization_date' => $this->cheque_realization_date,
            'invoice_details' => $this->invoice_details,
        ]);
        $this->accountsUpdate($invoice);
        // Update the CustomerOrder status to 'invoiced'
        $customerOrder->status = 'invoiced';
        $customerOrder->save();
        $order = null;
        if ($invoice->order_type === CustomerOrder::class) {
            $order = CustomerOrder::with('orderItems')->find($this->order_id);
        } elseif ($invoice->order_type === JobOrder::class) {
            $order = JobOrder::with('orderItems')->find($this->order_id);
        }

        if ($order) {
            foreach ($order->orderItems as $item) {
                $invoice->invoiceItems()->create([
                    'item_id' => $item->item_id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->price,
                    'total_price' => $item->total,
                ]);
            }
        }

        session()->flash('success', 'Invoice has been created successfully!');

        return $this->redirect('/invoices', navigate: true);
    }


    public function generateInvoiceNumber($orderId, $orderType)
    {

        if ($orderType === 'CO') {
            $this->order = CustomerOrder::find($orderId);
        } elseif ($orderType === 'JO') {
            $this->order = JobOrder::find($orderId);
        }

        $lastInvoice = Invoice::where('order_id', $orderId)
            ->where('order_type', $orderType)
            ->orderByDesc('created_at')
            ->first();

        $invoiceNumberSuffix = $lastInvoice ? (substr($lastInvoice->invoice_number, -4) + 1) : 1;
        $formattedSuffix = str_pad($invoiceNumberSuffix, 4, '0', STR_PAD_LEFT);
        $invoiceNumber = 'INV-' . $orderId . '-' . $orderType . '-' . $formattedSuffix;
        return $invoiceNumber;
    }


    public function render()
    {
        $bodyAttributes = 'x-data="{ page: \'invoiceCreate\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
        x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
        :class="{\'dark bg-gray-900\': darkMode === true}"';

        return view('livewire.invoice.invoice-form')->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
