<?php

use App\Livewire\Dashboard;
use App\Livewire\AdvancedDashboard;
use App\Livewire\Grn\GrnForm;
use App\Livewire\Grn\GrnList;
use App\Livewire\Uom\DotMatrixPrint;
use App\Livewire\Uom\UomForm;
use App\Livewire\Uom\UomList;
use App\Livewire\Grn\GrnItems;
use App\Livewire\Item\ItemForm;
use App\Livewire\Item\ItemList;
use App\Models\PostdatedCheque;
use App\Livewire\Stock\StockList;
use App\Livewire\Entries\AddEntry;
use App\Livewire\Job\JobOrderForm;
use App\Livewire\Job\JobOrderList;
use App\Livewire\Job\JobOrderView;
use App\Livewire\Dispatch\Dispatch;
use App\Livewire\Entries\EditEntry;
use App\Livewire\Entries\ViewEntry;
use App\Livewire\Ledgers\AddLedger;
use App\Livewire\Stock\StockByItem;
use App\Livewire\Accounts\EditGroup;
use App\Livewire\Ledgers\EditLedger;
use App\Livewire\Grn\DamagedItemForm;
use App\Livewire\Invoice\InvoiceForm;
use App\Livewire\Invoice\InvoiceList;
use App\Livewire\Invoice\InvoiceView;
use Illuminate\Support\Facades\Route;
use App\Livewire\Accounts\CreateGroup;
use App\Livewire\Customer\PaymentList;
use App\Livewire\Entries\AddEntryType;
use App\Livewire\Entries\IndexEntries;
use App\Livewire\Entries\PrintPreview;
use App\Livewire\Reports\BalanceSheet;
use App\Livewire\Stock\AdjustmentList;
use App\Livewire\Admin\DamagedItemList;
use App\Livewire\Dispatch\DispatchItem;
use App\Livewire\Entries\EditEntryType;
use App\Livewire\Supplier\SupplierEdit;
use App\Livewire\Supplier\SupplierForm;
use App\Livewire\Supplier\SupplierList;
use App\Livewire\Entries\ViewEntryTypes;
use App\Livewire\Customer\CustomerCreate;
use App\Livewire\Stock\StockTransferForm;
use App\Livewire\Stock\StockTransferList;
use App\Livewire\Supplier\SupplierCreate;
use App\Http\Controllers\BackupController;
use App\Livewire\Accounts\ChartOfAccounts;
// use App\Livewire\PrintPreview;
use App\Livewire\Customer\CustomerManager;
use App\Livewire\Customer\CustomerPayment;
use App\Livewire\DispatchNotePrintPreview;
use App\Livewire\MisReports\GeneralReport;
use App\Livewire\Reports\ProfitLossReport;
use App\Livewire\Stock\AdjustmentItemList;
use App\Livewire\Supplier\BillPaymentForm;
use App\Livewire\CheckManagement\CheckList;
use App\Livewire\Reports\AgeAnalysisReport;
use App\Livewire\Stock\StockAdjustmentForm;
use App\Livewire\Stock\StockAdjustmentList;
use App\Livewire\Supplier\VendorBillCreate;
use App\Livewire\Admin\DamagedItemApprovals;
use App\Livewire\Admin\BillDeletionApprovals;
use App\Livewire\Customer\CustomerOrderForm;
use App\Livewire\Customer\CustomerOrderList;
use App\Livewire\Reports\TrialBalanceReport;
use App\Livewire\CheckManagement\CheckCreate;
use App\Livewire\Invoice\InvoicePrintPreview;
use App\Livewire\Reports\LedgerEntriesReport;
use App\Livewire\Reports\StockMovementReport;
use App\Livewire\Transaction\TransactionList;
use App\Livewire\Customer\PrintPaymentReceipt;
use App\Livewire\Customer\CustomerOrderDetails;
use App\Livewire\OtherExpense\OtherExpenseCreate;
use App\Livewire\OtherExpense\OtherExpenseList;

use App\Livewire\Reports\LedgerStatementReport;
use App\Livewire\MisReports\SalesDatewiseReport;


use App\Livewire\Reports\StockMovementReportBatch;
use App\Livewire\Reports\CustomerOutstandingReport;
use App\Livewire\Reports\LedgerReconciliationReport;
use App\Livewire\Dispatch\DispatchItem as DispatchDispatchItem;
use App\Http\Controllers\MisReports\SalesDatewiseReportPrintController;
use Illuminate\Http\Request;

// Route::get('/dot-matrix-print',DotMatrixPrint::class)
//     ->name('dotmatrix.print');

Route::get('/backup-database', [BackupController::class, 'backupDatabase'])->name('backup.database');

Route::get('/advanced-dashboard', AdvancedDashboard::class)->name('advanced-dashboard');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/items', ItemList::class)->name('items');

    Route::get('items/create', ItemForm::class)->name('items.create');
    Route::get('items/{item}/view', ItemForm::class)->name('items.view');
    Route::get('items/{item}/edit', ItemForm::class)->name('items.edit');
    Route::get('/customers', CustomerManager::class)->name('customers');
    Route::get('/customers/create', CustomerCreate::class)->name('customers.create');
    Route::get('customers/{customer}/view', CustomerCreate::class)->name('customers.view');
    Route::get('customers/{customer}/edit', CustomerCreate::class)->name('customers.edit');
    Route::get('/customer-orders-list', CustomerOrderList::class)->name('customer.orders.list');
    Route::get('/customer-orders/create', CustomerOrderForm::class)->name('customer.orders.create');
    Route::get('/customer-orders/{orderId}', CustomerOrderForm::class)
        ->name('customer.orders.update');
    Route::get('/customer-orders/{orderId}/view', CustomerOrderDetails::class)
        ->name('customer.orders.view');

    Route::get('/customer-payment', CustomerPayment::class)->name('customer.payment');

    Route::get('/stocks', StockList::class)->name('stocks');
    Route::get('/stock-by-item', StockByItem::class)->name('stock.by-item');

    Route::get('/suppliers/create', SupplierForm::class)->name('suppliers.create');
    Route::get('/suppliers/{supplier}/edit', SupplierEdit::class)->name('suppliers.edit');
    Route::get('/suppliers', SupplierList::class)->name('supplier.list');
    Route::get('/suppliers', SupplierList::class)->name('suppliers.index');

    Route::get('/grns/create', GrnForm::class)->name('grns.create');
    Route::get('/grns/{grn}/edit', GrnForm::class)->name('grns.edit');
    Route::get('/grns', GrnList::class)->name('grns');
    Route::get('/grns/{grnId}/view', GrnItems::class)
        ->name('grn.items.view');

    Route::get('/job-orders/{jobOrderId}/edit', JobOrderForm::class)->name('job-orders.edit');
    Route::get('/job-order', JobOrderForm::class)->name('job-order');
    Route::get('/job-orders', JobOrderList::class)->name('job-orders');
    Route::get('/job-order/{jobOrderId}', JobOrderView::class)->name('job-order.view');
    Route::get('/job-orders/{jobOrderId}', JobOrderView::class)->name('job-orders.view');

    Route::get('/job-order/{jobOrderId}/dispatch', Dispatch::class)->name('job-order.dispatch');

    Route::get('/invoice', InvoiceForm::class)->name('invoice.create');
    Route::get('/invoices', InvoiceList::class)->name('invoices');
    Route::get('/invoices/{invoiceId}/view', InvoiceView::class)->name('invoice.view');
    Route::get('/invoice/{invoice}/edit', InvoiceForm::class)->name('invoice.edit');

    Route::get('/transactions', TransactionList::class)->name('transactions');

    Route::get('/uoms', UomList::class)->name('uoms.list');
    Route::get('/uoms/create', UomForm::class)->name('uoms.create');
    Route::get('/uoms/{uomId}/edit', UomForm::class)->name('uoms.edit');

    Route::get('/other-expenses', OtherExpenseList::class)->name('other-expenses');
    Route::get('/other-expenses/create', OtherExpenseCreate::class)->name('other-expense.create');
    Route::get('/other-expenses/{expenseId}/edit', OtherExpenseCreate::class)->name('other-expense.edit');

    Route::get('/dispatch-item/{orderId}', DispatchItem::class)->name('dispatch-items');
    Route::get('/print', PrintPreview::class)->name('print-items');

    Route::get('/invoice/print-preview/{invoiceId}', InvoicePrintPreview::class)->name('invoice.print-preview');
    Route::get('/dispatch-note/print-preview/{dispatchNoteId}', DispatchNotePrintPreview::class)->name('dispatch-note.print-preview');

    Route::get('/vendor-bill/multiple-print-preview/{id}', \App\Livewire\Supplier\VendorBillMultiplePrintPreview::class)->name('vendor-bill.multiple-print-preview');

    Route::get('/stock-transfer', StockTransferForm::class)->name('stock.transfer');
    Route::get('/stock-transfers', StockTransferList::class)->name('stock.transfers');

    Route::get('/accounts/chart', ChartOfAccounts::class)->name('accounts.chart');

    Route::get('/accounts/groups/edit/{id}', EditGroup::class)->name('edit-group');
    Route::get('/accounts/groups/add', CreateGroup::class)->name('create-group');

    Route::get('/ledgers/add', AddLedger::class)->name('ledgers.add');
    Route::get('/ledgers/edit/{id}', EditLedger::class)->name('ledgers.edit');

    Route::get('/entries', IndexEntries::class)->name('entries.index');
    Route::get('/entries/add/{type}', AddEntry::class)->name('entries.add');
    Route::get('/entries/view/{type}/{id}', ViewEntry::class)->name('entries.view');
    Route::get('/entries/edit/{type}/{id}', EditEntry::class)->name('entries.edit');
    Route::get('/entries/print-preview/{id}', PrintPreview::class)->name('entries.print-preview');

    Route::get('/reports/balance-sheet', BalanceSheet::class)->name('reports.balance-sheet');
    Route::get('/reports/profit-loss', ProfitLossReport::class)->name('reports.profit-loss');
    Route::get('/reports/trialbalance', TrialBalanceReport::class)->name('reports.trialbalance');
    // Route::get('/reports/ledger-statement', LedgerStatementReport::class)->name('reports.ledger-statement');
    Route::get('/reports/ledger-statement/{ledger_id?}', LedgerStatementReport::class)->name('reports.ledger-statement');

    Route::get('/reports/ledger-entries', LedgerEntriesReport::class)->name('reports.ledger-entries');
    Route::get('/reports/reconciliation', LedgerReconciliationReport::class)->name('reports.reconciliation');

    Route::get('/entrytypes/add', AddEntryType::class)->name('entrytypes.add');
    Route::get('/entrytypes', ViewEntryTypes::class)->name('entrytypes.index');
    Route::get('/entrytypes/edit/{id}', EditEntryType::class)->name('entrytypes.edit');

    Route::get('/vendor-bills/create', VendorBillCreate::class)->name('vendor-bills.create');
    Route::get('/payments', PaymentList::class)->name('payments.index');
    Route::get('/payments/{payment}/cancel', PaymentList::class)->name('payment.cancel');
    Route::get('/print/payment-receipt/{paymentId}', PrintPaymentReceipt::class)->name('payment.print');

    Route::get('/damaged-items/report', DamagedItemForm::class)->name('damaged-items.report');
    Route::get('/admin/damaged-items', DamagedItemApprovals::class)->name('admin.damaged-items');
    Route::get('/admin/damaged-list', DamagedItemList::class)->name('admin.damaged-list');
    Route::get('/admin/bill-deletion-approvals', BillDeletionApprovals::class)->name('admin.bill-deletion-approvals');

    Route::get('/stock-adjustment', StockAdjustmentForm::class)->name('stock.adjustment');
    Route::get('/stock-adjustments', StockAdjustmentList::class)->name('stock-adjustments.index');
    Route::get('/stock/adjustments', AdjustmentList::class)->name('stock.adjustments');

    Route::get('/stock-movement-report', StockMovementReport::class)->name('stock.movement.report');
    Route::get('/stock-movement-report-batch', StockMovementReportBatch::class)->name('stock.movement.report.batch');

    Route::get('/vendor-bill-payments', BillPaymentForm::class)->name('bill.payments');

    Route::get('/stock-transfer/print/{transferCode}', \App\Livewire\Stock\StockTransferPrint::class)->name('stock-transfer.print');
    Route::get('/customer/received-payments', \App\Livewire\Customer\RecevedPaymentList::class)->name('customer.received-payments');
    Route::get('/customer/received-payments/print', \App\Livewire\Customer\RecevedPaymentListPrint::class)->name('customer.received-payments.print');

    Route::get('/vendor/bill-payments', \App\Livewire\Supplier\VendorBillPaymentList::class)->name('vendor.bill-payments');

    Route::get('/mis-reports/general-report', GeneralReport::class)->name('mis-reports.general-report');
    Route::get('/mis-reports/sales-datewise-report', SalesDatewiseReport::class)->name('mis-reports.sales-datewise-report');

    Route::get('/mis-reports/sales-datewise-report/print', App\Livewire\MisReports\SalesDatewiseReportPrint::class)
        ->name('mis-reports.sales-datewise-report.print');
    Route::get('/mis-reports/customer-wise-sales-report', App\Livewire\MisReports\CustomerWiseSalesReport::class)
        ->name('mis-reports.customer-wise-sales-report');
    Route::get('/mis-reports/customer-wise-sales-report/print', \App\Livewire\MisReports\CustomerWiseSalesReportPrint::class)
        ->name('mis-reports.customer-wise-sales-report.print');
    Route::get('/mis-reports/used-plates-report', App\Livewire\MisReports\UsedPlatesReport::class)
        ->name('mis-reports.used-plates-report');
    Route::get('/mis-reports/plate-summary-report', App\Livewire\MisReports\PlateSummaryReport::class)
        ->name('mis-reports.plate-summary-report');

    Route::get('/mis-reports/sales-datewise-report/print', App\Livewire\MisReports\SalesDatewiseReportPrint::class)->name('mis-reports.sales-datewise-report.print');

    Route::get('/customer-outstanding', CustomerOutstandingReport::class)->name('customer-outstanding');

    Route::get('/reports/age-analysis', AgeAnalysisReport::class)->name('reports.age-analysis');


    Route::get('/check-management/list', CheckList::class)->name('check-management.list');
    Route::get('/check-management/create', CheckCreate::class)->name('check-management.create');




    Route::get('/check-management/print', function (Request $request) {
    $ids = $request->input('ids');  // Changed from query() to input()
    $id = $request->input('id');    // Changed from query() to input()

    if ($id) {
        $cheques = PostdatedCheque::with(['customer', 'paidInvoices.invoice'])
            ->where('id', $id)
            ->get();
    } else {
        $ids = explode(',', $ids);
        $cheques = PostdatedCheque::with(['customer', 'paidInvoices.invoice'])
            ->whereIn('id', $ids)
            ->get();
    }

    return view('livewire.check-management.print', ['cheques' => $cheques]);
})->name('check-management.print');

// Monthly Targets Routes
Route::get('/monthly-targets', \App\Livewire\MonthlyTarget\MonthlyTargetList::class)->name('monthly-targets.index');
Route::get('/monthly-targets/create', \App\Livewire\MonthlyTarget\MonthlyTargetForm::class)->name('monthly-targets.create');
Route::get('/monthly-targets/{id}/edit', \App\Livewire\MonthlyTarget\MonthlyTargetEdit::class)->name('monthly-targets.edit');

// Database Backup Routes
Route::get('/database-backup', App\Livewire\Admin\DatabaseBackup::class)->name('admin.database.backup');
Route::get('/database-backup/list', App\Livewire\Admin\BackupList::class)->name('admin.database.backup.list');
Route::get('/database-backup/download/{file}', [App\Http\Controllers\Admin\BackupController::class, 'download'])->name('admin.database.backup.download');
Route::delete('/database-backup/delete/{file}', [App\Http\Controllers\Admin\BackupController::class, 'delete'])->name('admin.database.backup.delete');

});
