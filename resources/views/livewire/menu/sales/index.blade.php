            <!-- Menu Group -->
            <div>
                <h3 class="mb-1 text-xs uppercase leading-[20px] text-gray-400">
                    <span class="menu-group-title" :class="sidebarToggle ? 'lg:hidden' : ''">
                        SALES
                    </span>

                    {{-- <svg :class="sidebarToggle ? 'lg:block hidden' : 'hidden'"
                        class="menu-group-icon mx-auto fill-current" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M5.99915 10.2451C6.96564 10.2451 7.74915 11.0286 7.74915 11.9951V12.0051C7.74915 12.9716 6.96564 13.7551 5.99915 13.7551C5.03265 13.7551 4.24915 12.9716 4.24915 12.0051V11.9951C4.24915 11.0286 5.03265 10.2451 5.99915 10.2451ZM17.9991 10.2451C18.9656 10.2451 19.7491 11.0286 19.7491 11.9951V12.0051C19.7491 12.9716 18.9656 13.7551 17.9991 13.7551C17.0326 13.7551 16.2491 12.9716 16.2491 12.0051V11.9951C16.2491 11.0286 17.0326 10.2451 17.9991 10.2451ZM13.7491 11.9951C13.7491 11.0286 12.9656 10.2451 11.9991 10.2451C11.0326 10.2451 10.2491 11.0286 10.2491 11.9951V12.0051C10.2491 12.9716 11.0326 13.7551 11.9991 13.7551C12.9656 13.7551 13.7491 12.9716 13.7491 12.0051V11.9951Z"
                            fill="" />
                    </svg> --}}
                </h3>

                <ul class="mb-6 flex flex-col gap-2">
                    <!-- Menu Item Invoice -->
                    @if (auth()->user()->mode === 'admin' || auth()->user()->mode === 'billing' || auth()->user()->mode === 'accounts')
                    <li>
                        <a href="#" @click.prevent="selected = (selected === 'Invoices' ? '':'Invoices')"
                            class="menu-item group"
                            :class="(selected === 'Invoices') || (page === 'invoiceCreate' ||
                                page === 'invoiceList' || page === 'returnInvoice' || page === 'archivedInvoices') ?
                            'menu-item-active' : 'menu-item-inactive'">
                            {{-- <svg :class="(selected === 'Invoices') || (page === 'invoiceCreate' || page === 'invoiceList' ||
                                page === 'returnInvoice' || page === 'archivedInvoices' ) ?
                            'menu-item-icon-active' : 'menu-item-icon-inactive'"
                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M5.617 2.076a1 1 0 0 1 1.09.217L8 3.586l1.293-1.293a1 1 0 0 1 1.414 0L12 3.586l1.293-1.293a1 1 0 0 1 1.414 0L16 3.586l1.293-1.293A1 1 0 0 1 19 3v18a1 1 0 0 1-1.707.707L16 20.414l-1.293 1.293a1 1 0 0 1-1.414 0L12 20.414l-1.293 1.293a1 1 0 0 1-1.414 0L8 20.414l-1.293 1.293A1 1 0 0 1 5 21V3a1 1 0 0 1 .617-.924ZM9 7a1 1 0 0 0 0 2h6a1 1 0 1 0 0-2H9Zm0 4a1 1 0 1 0 0 2h6a1 1 0 1 0 0-2H9Zm0 4a1 1 0 1 0 0 2h6a1 1 0 1 0 0-2H9Z" clip-rule="evenodd"/>
                            </svg> --}}



                                <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                    Invoices
                                </span>

                                <svg class="menu-item-arrow"
                                    :class="[(selected === 'Invoices') ? 'menu-item-arrow-active' : 'menu-item-arrow-inactive',
                                        sidebarToggle ? 'lg:hidden' : ''
                                    ]"
                                    width="20" height="20" viewBox="0 0 20 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585" stroke=""
                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </a>

                            <!-- Dropdown Menu Start -->
                            <div class="translate transform overflow-hidden"
                                :class="(selected === 'Invoices') ? 'block' : 'hidden'">
                                <ul :class="sidebarToggle ? 'lg:hidden' : 'flex'"
                                    class="menu-dropdown mt-1 flex flex-col pl-9">
                                    {{-- <li>
                                    <a wire:navigate href="{{ route('invoice.create') }}" class="menu-dropdown-item group"
                                        :class="page === 'invoiceCreate' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Add Invoice
                                    </a>
                                </li> --}}
                                    <li>
                                        <a wire:navigate href="{{ route('invoices') }}" class="menu-dropdown-item group"
                                            :class="page === 'invoiceList' ? 'menu-dropdown-item-active' :
                                                'menu-dropdown-item-inactive'">
                                            Invoice List
                                            <span class="absolute right-3 flex items-center gap-1">
                                                <span class="menu-dropdown-badge"
                                                    :class="page === 'invoiceList' ? 'menu-dropdown-badge-active' :
                                                        'menu-dropdown-badge-inactive'">

                                                </span>
                                            </span>
                                        </a>
                                    </li>

                                </ul>
                            </div>
                            <!-- Dropdown Menu End -->
                        </li>
                        <!-- Menu Item Invoice -->
                    @endif

                    <!-- Menu Item Job -->
                    <li>
                        <a href="#" @click.prevent="selected = (selected === 'Job' ? '':'Job')"
                            class="menu-item group"
                            :class="(selected === 'Job') || (page === 'jobOrder' ||
                                page === 'jobOrderList') ?
                            'menu-item-active' : 'menu-item-inactive'">
                            {{-- <svg :class="(selected === 'Job') || (page === 'jobOrder' || page === 'jobOrderList') ?
                            'menu-item-icon-active' : 'menu-item-icon-inactive'"
                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M12.512 8.72a2.46 2.46 0 0 1 3.479 0 2.461 2.461 0 0 1 0 3.479l-.004.005-1.094 1.08a.998.998 0 0 0-.194-.272l-3-3a1 1 0 0 0-.272-.193l1.085-1.1Zm-2.415 2.445L7.28 14.017a1 1 0 0 0-.289.702v2a1 1 0 0 0 1 1h2a1 1 0 0 0 .703-.288l2.851-2.816a.995.995 0 0 1-.26-.189l-3-3a.998.998 0 0 1-.19-.26Z" clip-rule="evenodd"/>
                                <path fill-rule="evenodd" d="M7 3a1 1 0 0 1 1 1v1h3V4a1 1 0 1 1 2 0v1h3V4a1 1 0 1 1 2 0v1h1a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h1V4a1 1 0 0 1 1-1Zm10.67 8H19v8H5v-8h3.855l.53-.537a1 1 0 0 1 .87-.285c.097.015.233.13.277.087.045-.043-.073-.18-.09-.276a1 1 0 0 1 .274-.873l1.09-1.104a3.46 3.46 0 0 1 4.892 0l.001.002A3.461 3.461 0 0 1 17.67 11Z" clip-rule="evenodd"/>
                            </svg> --}}



                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                Job
                            </span>

                            <svg class="menu-item-arrow"
                                :class="[(selected === 'Job') ? 'menu-item-arrow-active' : 'menu-item-arrow-inactive',
                                    sidebarToggle ? 'lg:hidden' : ''
                                ]"
                                width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585" stroke=""
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>

                        <!-- Dropdown Menu Start -->
                        <div class="translate transform overflow-hidden mt-1"
                            :class="(selected === 'Job') ? 'block' : 'hidden'">
                            <ul :class="sidebarToggle ? 'lg:hidden' : 'flex'"
                                class="menu-dropdown mt- flex flex-col pl-9">
                                @if (auth()->user()->mode === 'admin' || auth()->user()->mode === 'design')
                                    <li>
                                        <a wire:navigate href="{{ route('job-order') }}"
                                            class="menu-dropdown-item group"
                                            :class="page === 'jobOrder' ? 'menu-dropdown-item-active' :
                                                'menu-dropdown-item-inactive'">
                                            Add Job
                                        </a>
                                    </li>
                                @endif
                                <li>
                                    <a wire:navigate href="{{ route('job-orders') }}" class="menu-dropdown-item group"
                                        :class="page === 'jobOrderList' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Job List
                                        <span class="absolute right-3 flex items-center gap-1">
                                            <span class="menu-dropdown-badge"
                                                :class="page === 'jobOrderList' ? 'menu-dropdown-badge-active' :
                                                    'menu-dropdown-badge-inactive'">

                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a wire:navigate href="{{ route('damaged-items.report') }}"
                                        class="menu-dropdown-item group"
                                        :class="page === 'AddDamagedItem' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Add Damaged Item
                                        <span class="absolute right-3 flex items-center gap-1">
                                            <span class="menu-dropdown-badge"
                                                :class="page === 'AddDamagedItem' ? 'menu-dropdown-badge-active' :
                                                    'menu-dropdown-badge-inactive'">

                                            </span>
                                        </span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                        <!-- Dropdown Menu End -->
                    </li>


                    <!-- Menu Item Order -->
                    {{-- <li>
                        <a href="#" @click.prevent="selected = (selected === 'CustomerOrder' ? '':'CustomerOrder')"
                            class="menu-item group"
                            :class="(selected === 'CustomerOrder') || (page === 'addOrder' || page === 'orderList') ?
                            'menu-item-active' : 'menu-item-inactive'">
                            <svg :class="(selected === 'CustomerOrder') || (page === 'addOrder' || page === 'orderList') ?
                            'menu-item-icon-active' : 'menu-item-icon-inactive'"
                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.7141 15h4.268c.4043 0 .732-.3838.732-.8571V3.85714c0-.47338-.3277-.85714-.732-.85714H6.71411c-.55228 0-1 .44772-1 1v4m10.99999 7v-3h3v3h-3Zm-3 6H6.71411c-.55228 0-1-.4477-1-1 0-1.6569 1.34315-3 3-3h2.99999c1.6569 0 3 1.3431 3 3 0 .5523-.4477 1-1 1Zm-1-9.5c0 1.3807-1.1193 2.5-2.5 2.5s-2.49999-1.1193-2.49999-2.5S8.8334 9 10.2141 9s2.5 1.1193 2.5 2.5Z"/>
                            </svg>

                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                Orders
                            </span>

                            <svg class="menu-item-arrow"
                                :class="[(selected === 'CustomerOrder') ? 'menu-item-arrow-active' : 'menu-item-arrow-inactive',
                                    sidebarToggle ? 'lg:hidden' : ''
                                ]"
                                width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585" stroke=""
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>

                        <!-- Dropdown Menu Start -->
                        <div class="translate transform overflow-hidden"
                            :class="(selected === 'CustomerOrder') ? 'block' : 'hidden'">
                            <ul :class="sidebarToggle ? 'lg:hidden' : 'flex'"
                                class="menu-dropdown mt-2 flex flex-col gap-1 pl-9">
                                <li>
                                    <a wire:navigate href="{{ route('customer.orders.create') }}" class="menu-dropdown-item group"
                                        :class="page === 'addOrder' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Add Order
                                    </a>
                                </li>
                                <li>
                                    <a wire:navigate href="{{ route('customer.orders.list') }}" class="menu-dropdown-item group"
                                        :class="page === 'orderList' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Order List
                                        <span class="absolute right-3 flex items-center gap-1">
                                            <span class="menu-dropdown-badge"
                                                :class="page === 'analytics' ? 'menu-dropdown-badge-active' :
                                                    'menu-dropdown-badge-inactive'">
                                                10
                                            </span>
                                        </span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                        <!-- Dropdown Menu End -->
                    </li> --}}
                    <!-- Menu Item Order -->
                </ul>
            </div>
