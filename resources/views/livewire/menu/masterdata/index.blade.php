            <!-- Menu Group -->
            <div>
                <h3 class="mb-1 text-xs uppercase leading-[20px] text-gray-400">
                    <span class="menu-group-title" :class="sidebarToggle ? 'lg:hidden' : ''">
                        MASTER DATA
                    </span>
                    {{--
                    <svg :class="sidebarToggle ? 'lg:block hidden' : 'hidden'"
                        class="menu-group-icon mx-auto fill-current" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M5.99915 10.2451C6.96564 10.2451 7.74915 11.0286 7.74915 11.9951V12.0051C7.74915 12.9716 6.96564 13.7551 5.99915 13.7551C5.03265 13.7551 4.24915 12.9716 4.24915 12.0051V11.9951C4.24915 11.0286 5.03265 10.2451 5.99915 10.2451ZM17.9991 10.2451C18.9656 10.2451 19.7491 11.0286 19.7491 11.9951V12.0051C19.7491 12.9716 18.9656 13.7551 17.9991 13.7551C17.0326 13.7551 16.2491 12.9716 16.2491 12.0051V11.9951C16.2491 11.0286 17.0326 10.2451 17.9991 10.2451ZM13.7491 11.9951C13.7491 11.0286 12.9656 10.2451 11.9991 10.2451C11.0326 10.2451 10.2491 11.0286 10.2491 11.9951V12.0051C10.2491 12.9716 11.0326 13.7551 11.9991 13.7551C12.9656 13.7551 13.7491 12.9716 13.7491 12.0051V11.9951Z"
                            fill="" />
                    </svg> --}}
                </h3>

                <ul class="mb-1 flex flex-col">
                    <!-- Menu Item Supplier -->
                    @if (auth()->user()->mode === 'admin' || auth()->user()->mode === 'billing'|| auth()->user()->mode === 'accounts')
                    <li>
                        <a href="#" @click.prevent="selected = (selected === 'Suppliers' ? '':'Suppliers')"
                            class="menu-item group"
                            :class="(selected === 'Suppliers') || (page === 'addsupplier' || page === 'supplierList') ?
                            'menu-item-active' : 'menu-item-inactive'">
                            {{-- <svg :class="(selected === 'Suppliers') && (page === 'addsupplier' || page === 'supplierList') ? 'menu-item-icon-active' :
                            'menu-item-icon-inactive'"
                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M12 3.5C7.30558 3.5 3.5 7.30558 3.5 12C3.5 14.1526 4.3002 16.1184 5.61936 17.616C6.17279 15.3096 8.24852 13.5955 10.7246 13.5955H13.2746C15.7509 13.5955 17.8268 15.31 18.38 17.6167C19.6996 16.119 20.5 14.153 20.5 12C20.5 7.30558 16.6944 3.5 12 3.5ZM17.0246 18.8566V18.8455C17.0246 16.7744 15.3457 15.0955 13.2746 15.0955H10.7246C8.65354 15.0955 6.97461 16.7744 6.97461 18.8455V18.856C8.38223 19.8895 10.1198 20.5 12 20.5C13.8798 20.5 15.6171 19.8898 17.0246 18.8566ZM2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12ZM11.9991 7.25C10.8847 7.25 9.98126 8.15342 9.98126 9.26784C9.98126 10.3823 10.8847 11.2857 11.9991 11.2857C13.1135 11.2857 14.0169 10.3823 14.0169 9.26784C14.0169 8.15342 13.1135 7.25 11.9991 7.25ZM8.48126 9.26784C8.48126 7.32499 10.0563 5.75 11.9991 5.75C13.9419 5.75 15.5169 7.32499 15.5169 9.26784C15.5169 11.2107 13.9419 12.7857 11.9991 12.7857C10.0563 12.7857 8.48126 11.2107 8.48126 9.26784Z"
                                    fill="" />
                            </svg> --}}

                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                Supplier
                            </span>

                            <svg class="menu-item-arrow"
                                :class="[(selected === 'Suppliers') ? 'menu-item-arrow-active' : 'menu-item-arrow-inactive',
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
                            :class="(selected === 'Suppliers') ? 'block' : 'hidden'">
                            <ul :class="sidebarToggle ? 'lg:hidden' : 'flex'"
                                class="menu-dropdown mt-1 flex flex-col pl-9">
                                <li>
                                    <a wire:navigate href="{{ route('suppliers.create') }}" class="menu-dropdown-item group"
                                        :class="page === 'addSupplier' ? 'menu-dropdown-item-active' : 'menu-dropdown-item-inactive'">
                                        Add Supplier
                                    </a>
                                </li>
                                <li>
                                    <a wire:navigate href="{{ route('suppliers.index') }}" class="menu-dropdown-item group"
                                        :class="page === 'supplierList' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Supplier List

                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!-- Dropdown Menu End -->
                    </li>
                    <!-- Menu Item Supplier -->
                    @endif

                    <!-- Menu Item Customer -->

                     @if (auth()->user()->mode === 'admin' || auth()->user()->mode === 'billing'|| auth()->user()->mode === 'accounts')
                    <li>
                        <a href="#" @click.prevent="selected = (selected === 'Customers' ? '':'Customers')"
                            class="menu-item group"
                            :class="(selected === 'Customers') || (page === 'addCustomer' || page === 'customerList') ? 'menu-item-active' :
                            'menu-item-inactive'">
                            {{-- <svg :class="(selected === 'Customers') && (page === 'addCustomer') ? 'menu-item-icon-active' :
                            'menu-item-icon-inactive'"
                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M12 20a7.966 7.966 0 0 1-5.002-1.756l.002.001v-.683c0-1.794 1.492-3.25 3.333-3.25h3.334c1.84 0 3.333 1.456 3.333 3.25v.683A7.966 7.966 0 0 1 12 20ZM2 12C2 6.477 6.477 2 12 2s10 4.477 10 10c0 5.5-4.44 9.963-9.932 10h-.138C6.438 21.962 2 17.5 2 12Zm10-5c-1.84 0-3.333 1.455-3.333 3.25S10.159 13.5 12 13.5c1.84 0 3.333-1.455 3.333-3.25S13.841 7 12 7Z" clip-rule="evenodd"/>
                            </svg> --}}

                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                Customer
                            </span>

                            <svg class="menu-item-arrow absolute right-2.5 top-1/2 -translate-y-1/2 stroke-current"
                                :class="[(selected === 'Customers') ? 'menu-item-arrow-active' : 'menu-item-arrow-inactive',
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
                            :class="(selected === 'Customers') ? 'block' : 'hidden'">
                            <ul :class="sidebarToggle ? 'lg:hidden' : 'flex'"
                                class="menu-dropdown mt-1 flex flex-col pl-9">
                                <li>
                                    <a wire:navigate href="{{ route('customers.create') }}" class="menu-dropdown-item group"
                                        :class="page === 'addCustomer' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Add Customer
                                    </a>
                                </li>
                                <li>
                                    <a wire:navigate href="{{ route('customers') }}" class="menu-dropdown-item group"
                                        :class="page === 'customerList' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Customer List
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!-- Dropdown Menu End -->
                    </li>
                    @endif
                    <!-- Menu Item Customer -->

                </ul>
            </div>
