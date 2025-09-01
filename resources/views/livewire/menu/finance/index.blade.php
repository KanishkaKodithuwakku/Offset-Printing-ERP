<!-- Menu Group -->
<div>
    <h3 class="mb-1 text-xs uppercase leading-[20px] text-gray-400">
        <span class="menu-group-title" :class="sidebarToggle ? 'lg:hidden' : ''">
            FINANCE
        </span>

        {{-- <svg :class="sidebarToggle ? 'lg:block hidden' : 'hidden'" class="mx-auto fill-current menu-group-icon"
            width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd"
                d="M5.99915 10.2451C6.96564 10.2451 7.74915 11.0286 7.74915 11.9951V12.0051C7.74915 12.9716 6.96564 13.7551 5.99915 13.7551C5.03265 13.7551 4.24915 12.9716 4.24915 12.0051V11.9951C4.24915 11.0286 5.03265 10.2451 5.99915 10.2451ZM17.9991 10.2451C18.9656 10.2451 19.7491 11.0286 19.7491 11.9951V12.0051C19.7491 12.9716 18.9656 13.7551 17.9991 13.7551C17.0326 13.7551 16.2491 12.9716 16.2491 12.0051V11.9951C16.2491 11.0286 17.0326 10.2451 17.9991 10.2451ZM13.7491 11.9951C13.7491 11.0286 12.9656 10.2451 11.9991 10.2451C11.0326 10.2451 10.2491 11.0286 10.2491 11.9951V12.0051C10.2491 12.9716 11.0326 13.7551 11.9991 13.7551C12.9656 13.7551 13.7491 12.9716 13.7491 12.0051V11.9951Z"
                fill="" />
        </svg> --}}
    </h3>

    <ul class="flex flex-col gap-1 mb-1">
        <!-- Menu Tranactions -->
        <li>
            <a href="#" @click.prevent="selected = (selected === 'Transactions' ? '':'Transactions')"
                class="menu-item group"
                :class="(selected === 'Transactions') || (page === 'transactions') ?
                'menu-item-active' : 'menu-item-inactive'">
                {{-- <svg :class="(selected === 'Transactions') || (page === 'transactions' ) ?
                            'menu-item-icon-active' : 'menu-item-icon-inactive'" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                        d="M6 4v10m0 0a2 2 0 1 0 0 4m0-4a2 2 0 1 1 0 4m0 0v2m6-16v2m0 0a2 2 0 1 0 0 4m0-4a2 2 0 1 1 0 4m0 0v10m6-16v10m0 0a2 2 0 1 0 0 4m0-4a2 2 0 1 1 0 4m0 0v2" />
                </svg> --}}


                <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                    Accounts
                </span>

                <svg class="menu-item-arrow"
                    :class="[(selected === 'Transactions') ? 'menu-item-arrow-active' : 'menu-item-arrow-inactive',
                        sidebarToggle ? 'lg:hidden' : ''
                    ]"
                    width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585" stroke="" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </a>

            <!-- Dropdown Menu Start -->
            <div class="overflow-hidden transform translate"
                :class="(selected === 'Transactions') ? 'block' : 'hidden'">
                <ul :class="sidebarToggle ? 'lg:hidden' : 'flex'" class="flex flex-col mt-2 menu-dropdown pl-9">
                    {{-- <li>
                        <a wire:navigate href="{{ route('invoice.create') }}" class="menu-dropdown-item group" :class="page === 'invoiceCreate' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                            Add Invoice
                        </a>
                    </li> --}}
                    <li>
                        {{-- <a wire:navigate href="{{ route('accounts.chart') }}" class="menu-dropdown-item group"
                            :class="page === 'ChartOfAccount' ? 'menu-dropdown-item-active' :
                                'menu-dropdown-item-inactive'">
                            Accounts administration
                            <span class="absolute flex items-center gap-1 right-3">
                                <span class="menu-dropdown-badge"
                                    :class="page === 'ChartOfAccount' ? 'menu-dropdown-badge-active' :
                                        'menu-dropdown-badge-inactive'">

                                </span>
                            </span>
                        </a> --}}
                        <a href="https://finance.mastergraphicslk.com/wzusers/login" target="_blank" class="menu-dropdown-item group"
                            :class="page === 'ChartOfAccount' ? 'menu-dropdown-item-active' :
                                'menu-dropdown-item-inactive'">
                            Accounts administration
                            <span class="absolute flex items-center gap-1 right-3">
                                <span class="menu-dropdown-badge"
                                    :class="page === 'ChartOfAccount' ? 'menu-dropdown-badge-active' :
                                        'menu-dropdown-badge-inactive'">

                                </span>
                            </span>
                        </a>
                    </li>


                </ul>
            </div>
            <!-- Dropdown Menu End -->
        </li>
        <!-- Menu Item Tranactions -->
        <!-- Menu Payment -->
        <li>
            <a href="#" @click.prevent="selected = (selected === 'Payments' ? '' : 'Payments')"
                class="menu-item group"
                :class="(selected === 'Payments') || (page === '') ?
                'menu-item-active' : 'menu-item-inactive'">

                <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                    Payment
                </span>

                <svg class="menu-item-arrow"
                    :class="[(selected === 'Payments') ? 'menu-item-arrow-active' : 'menu-item-arrow-inactive',
                        sidebarToggle ? 'lg:hidden' : ''
                    ]"
                    width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585" stroke="" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </a>

            <!-- Dropdown Menu Start -->
            <div class="overflow-hidden transform translate" :class="(selected === 'Payments') ? 'block' : 'hidden'">
                <ul :class="sidebarToggle ? 'lg:hidden' : 'flex'" class="flex flex-col mt-2 menu-dropdown pl-9">
                    <li class="hidden">
                        <a wire:navigate href="{{ route('entries.index') }}" class="menu-dropdown-item group"
                            :class="page === 'Entries' ? 'menu-dropdown-item-active' :
                                'menu-dropdown-item-inactive'">
                            Entries
                            <span class="absolute flex items-center gap-1 right-3">
                                <span class="menu-dropdown-badge"
                                    :class="page === 'Entries' ? 'menu-dropdown-badge-active' :
                                        'menu-dropdown-badge-inactive'">
                                </span>
                            </span>
                        </a>
                    </li>

                    <li class="hidden">
                        <a wire:navigate href="{{ route('entrytypes.index') }}" class="menu-dropdown-item group"
                            :class="page === 'ViewEntryTypes' ? 'menu-dropdown-item-active' :
                                'menu-dropdown-item-inactive'">
                            Entry Types
                            <span class="absolute flex items-center gap-1 right-3">
                                <span class="menu-dropdown-badge"
                                    :class="page === 'ViewEntryTypes' ? 'menu-dropdown-badge-active' :
                                        'menu-dropdown-badge-inactive'">
                                </span>
                            </span>
                        </a>
                    </li>

                    <li>
                        <a wire:navigate href="{{ route('customer.payment') }}" class="menu-dropdown-item group"
                            :class="page === 'CustomerPayment' ? 'menu-dropdown-item-active' :
                                'menu-dropdown-item-inactive'">
                            Customer Payment
                            <span class="absolute flex items-center gap-1 right-3">
                                <span class="menu-dropdown-badge"
                                    :class="page === 'CustomerPayment' ? 'menu-dropdown-badge-active' :
                                        'menu-dropdown-badge-inactive'">

                                </span>
                            </span>
                        </a>
                    </li>


                    <li>
                        <a wire:navigate href="{{ route('payments.index') }}" class="menu-dropdown-item group"
                            :class="page === 'RecevedPayments' ? 'menu-dropdown-item-active' :
                                'menu-dropdown-item-inactive'">
                            Received Payments
                            <span class="absolute flex items-center gap-1 right-3">
                                <span class="menu-dropdown-badge"
                                    :class="page === 'RecevedPayments' ? 'menu-dropdown-badge-active' :
                                        'menu-dropdown-badge-inactive'">

                                </span>
                            </span>
                        </a>
                    </li>

                    <li>
                        <a wire:navigate href="{{ route('customer.received-payments') }}" class="menu-dropdown-item group"
                            :class="page === 'RecevedPaymentsPrint' ? 'menu-dropdown-item-active' :
                                'menu-dropdown-item-inactive'">
                           Payment Receipt Listing
                            <span class="absolute flex items-center gap-1 right-3">
                                <span class="menu-dropdown-badge"
                                    :class="page === 'RecevedPaymentsPrint' ? 'menu-dropdown-badge-active' :
                                        'menu-dropdown-badge-inactive'">

                                </span>
                            </span>
                        </a>
                    </li>


                    <li>
                        <a wire:navigate href="{{ route('vendor-bills.create') }}" class="menu-dropdown-item group"
                            :class="page === 'VendorPayment' ? 'menu-dropdown-item-active' :
                                'menu-dropdown-item-inactive'">
                            Vendor Payment
                            <span class="absolute flex items-center gap-1 right-3">
                                <span class="menu-dropdown-badge"
                                    :class="page === 'VendorPayment' ? 'menu-dropdown-badge-active' :
                                        'menu-dropdown-badge-inactive'">

                                </span>
                            </span>
                        </a>
                    </li>



                    <li>
                        <a wire:navigate href="{{ route('bill.payments') }}" class="menu-dropdown-item group"
                            :class="page === 'VendorBill' ? 'menu-dropdown-item-active' :
                                'menu-dropdown-item-inactive'">
                            Vendor bill Payments
                            <span class="absolute flex items-center gap-1 right-3">
                                <span class="menu-dropdown-badge"
                                    :class="page === 'VendorBill' ? 'menu-dropdown-badge-active' :
                                        'menu-dropdown-badge-inactive'">

                                </span>
                            </span>
                        </a>
                    </li>

                    <li>
                        <a wire:navigate href="{{ route('vendor.bill-payments') }}" class="menu-dropdown-item group"
                            :class="page === 'VendorBillPayments' ? 'menu-dropdown-item-active' :
                                'menu-dropdown-item-inactive'">
                            Vendor Bill Payment List
                            <span class="absolute flex items-center gap-1 right-3">
                                <span class="menu-dropdown-badge"
                                    :class="page === 'VendorBillPayments' ? 'menu-dropdown-badge-active' :
                                        'menu-dropdown-badge-inactive'">

                                </span>
                            </span>
                        </a>
                    </li>


                </ul>
            </div>
            <!-- Dropdown Menu End -->
        </li>


        <li class="hidden">
            <a href="#" @click.prevent="selected = (selected === 'Reports' ? '' : 'Reports')"
                class="menu-item group"
                :class="(selected === 'Reports') || (page === 'reports') ?
                'menu-item-active' : 'menu-item-inactive'">

                <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                    Reports
                </span>

                <svg class="menu-item-arrow"
                    :class="[(selected === 'Reports') ? 'menu-item-arrow-active' : 'menu-item-arrow-inactive',
                        sidebarToggle ? 'lg:hidden' : ''
                    ]"
                    width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585" stroke="" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </a>

            <!-- Dropdown Menu Start -->
            <div class="overflow-hidden transform translate" :class="(selected === 'Reports') ? 'block' : 'hidden'">
                <ul :class="sidebarToggle ? 'lg:hidden' : 'flex'" class="flex flex-col mt-2 menu-dropdown pl-9">
                    <li>
                        <a wire:navigate href="{{ route('reports.balance-sheet') }}" class="menu-dropdown-item group"
                            :class="page === 'BlanceSheet' ? 'menu-dropdown-item-active' :
                                'menu-dropdown-item-inactive'">
                            Balancs Sheet
                            <span class="absolute flex items-center gap-1 right-3">
                                <span class="menu-dropdown-badge"
                                    :class="page === 'BlanceSheet' ? 'menu-dropdown-badge-active' :
                                        'menu-dropdown-badge-inactive'">
                                </span>
                            </span>
                        </a>
                    </li>


                    <li>
                        <a wire:navigate href="{{ route('reports.profit-loss') }}" class="menu-dropdown-item group"
                            :class="page === 'ProfitAndLoss' ? 'menu-dropdown-item-active' :
                                'menu-dropdown-item-inactive'">
                            Profit and Loss
                            <span class="absolute flex items-center gap-1 right-3">
                                <span class="menu-dropdown-badge"
                                    :class="page === 'ProfitAndLoss' ? 'menu-dropdown-badge-active' :
                                        'menu-dropdown-badge-inactive'">
                                </span>
                            </span>
                        </a>
                    </li>


                    <li>
                        <a wire:navigate href="{{ route('reports.trialbalance') }}" class="menu-dropdown-item group"
                            :class="page === 'TrialBalanceReport' ? 'menu-dropdown-item-active' :
                                'menu-dropdown-item-inactive'">
                            Trial Balance
                            <span class="absolute flex items-center gap-1 right-3">
                                <span class="menu-dropdown-badge"
                                    :class="page === 'TrialBalanceReport' ? 'menu-dropdown-badge-active' :
                                        'menu-dropdown-badge-inactive'">
                                </span>
                            </span>
                        </a>
                    </li>


                    <li>
                        <a wire:navigate href="{{ route('reports.ledger-statement') }}"
                            class="menu-dropdown-item group"
                            :class="page === 'LedgerStatement' ? 'menu-dropdown-item-active' :
                                'menu-dropdown-item-inactive'">
                            Ledger Statement
                            <span class="absolute flex items-center gap-1 right-3">
                                <span class="menu-dropdown-badge"
                                    :class="page === 'LedgerStatement' ? 'menu-dropdown-badge-active' :
                                        'menu-dropdown-badge-inactive'">
                                </span>
                            </span>
                        </a>
                    </li>


                    <li>
                        <a wire:navigate href="{{ route('reports.ledger-entries') }}"
                            class="menu-dropdown-item group"
                            :class="page === 'LedgerEntriesReport' ? 'menu-dropdown-item-active' :
                                'menu-dropdown-item-inactive'">
                            Ledger Entries
                            <span class="absolute flex items-center gap-1 right-3">
                                <span class="menu-dropdown-badge"
                                    :class="page === 'LedgerEntriesReport' ? 'menu-dropdown-badge-active' :
                                        'menu-dropdown-badge-inactive'">
                                </span>
                            </span>
                        </a>
                    </li>

                    <li>
                        <a wire:navigate href="{{ route('reports.reconciliation') }}"
                            class="menu-dropdown-item group"
                            :class="page === 'ReconciliationReport' ? 'menu-dropdown-item-active' :
                                'menu-dropdown-item-inactive'">
                            Reconcilation
                            <span class="absolute flex items-center gap-1 right-3">
                                <span class="menu-dropdown-badge"
                                    :class="page === 'ReconciliationReport' ? 'menu-dropdown-badge-active' :
                                        'menu-dropdown-badge-inactive'">
                                </span>
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- Dropdown Menu End -->
        </li>
        <h3 class="mb-1 text-xs uppercase leading-[20px] text-gray-400">
        <span class="menu-group-title" :class="sidebarToggle ? 'lg:hidden' : ''">
            Postdated Cheque
        </span>

        {{-- <svg :class="sidebarToggle ? 'lg:block hidden' : 'hidden'" class="mx-auto fill-current menu-group-icon"
            width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd"
                d="M5.99915 10.2451C6.96564 10.2451 7.74915 11.0286 7.74915 11.9951V12.0051C7.74915 12.9716 6.96564 13.7551 5.99915 13.7551C5.03265 13.7551 4.24915 12.9716 4.24915 12.0051V11.9951C4.24915 11.0286 5.03265 10.2451 5.99915 10.2451ZM17.9991 10.2451C18.9656 10.2451 19.7491 11.0286 19.7491 11.9951V12.0051C19.7491 12.9716 18.9656 13.7551 17.9991 13.7551C17.0326 13.7551 16.2491 12.9716 16.2491 12.0051V11.9951C16.2491 11.0286 17.0326 10.2451 17.9991 10.2451ZM13.7491 11.9951C13.7491 11.0286 12.9656 10.2451 11.9991 10.2451C11.0326 10.2451 10.2491 11.0286 10.2491 11.9951V12.0051C10.2491 12.9716 11.0326 13.7551 11.9991 13.7551C12.9656 13.7551 13.7491 12.9716 13.7491 12.0051V11.9951Z"
                fill="" />
        </svg> --}}
    </h3>

        <!-- Menu Check Management -->
        <li>
            <a href="#" @click.prevent="selected = (selected === 'CheckManagement' ? '' : 'CheckManagement')" class="menu-item group"
                :class="(selected === 'CheckManagement') || (page === 'check-management') ?
                            'menu-item-active' : 'menu-item-inactive'">

                <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                    Check Management
                </span>

                <svg class="menu-item-arrow" :class="[(selected === 'CheckManagement') ? 'menu-item-arrow-active' : 'menu-item-arrow-inactive',
                                    sidebarToggle ? 'lg:hidden' : ''
                                ]" width="20" height="20" viewBox="0 0 20 20" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585" stroke="" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </a>

            <!-- Dropdown Menu Start -->
            <div class="overflow-hidden transform translate" :class="(selected === 'CheckManagement') ? 'block' : 'hidden'">
                <ul :class="sidebarToggle ? 'lg:hidden' : 'flex'" class="flex flex-col mt-2 menu-dropdown pl-9">
                    <li>
                        <a wire:navigate href="{{ route('check-management.create') }}" class="menu-dropdown-item group"
                            :class="page === 'check-management.create' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                            Add New Cheque
                            <span class="absolute flex items-center gap-1 right-3">
                                <span class="menu-dropdown-badge" :class="page === 'check-management.create' ? 'menu-dropdown-badge-active' :
                                                    'menu-dropdown-badge-inactive'">
                                </span>
                            </span>
                        </a>
                    </li>

                    <li>
                        <a wire:navigate href="{{ route('check-management.list') }}" class="menu-dropdown-item group"
                            :class="page === 'check-management' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                            Manage Cheques
                            <span class="absolute flex items-center gap-1 right-3">
                                <span class="menu-dropdown-badge" :class="page === 'check-management' ? 'menu-dropdown-badge-active' :
                                                    'menu-dropdown-badge-inactive'">
                                </span>
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- Dropdown Menu End -->
        </li>


    </ul>

</div>
