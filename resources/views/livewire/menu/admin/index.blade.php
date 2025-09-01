<!-- Menu Group -->
<div>
    <h3 class="mb-1 text-xs uppercase leading-[20px] text-gray-400">
        <span class="menu-group-title" :class="sidebarToggle ? 'lg:hidden' : ''">
            MANAGEMENT
        </span>

        {{-- <svg :class="sidebarToggle ? 'lg:block hidden' : 'hidden'" class="menu-group-icon mx-auto fill-current"
            width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd"
                d="M5.99915 10.2451C6.96564 10.2451 7.74915 11.0286 7.74915 11.9951V12.0051C7.74915 12.9716 6.96564 13.7551 5.99915 13.7551C5.03265 13.7551 4.24915 12.9716 4.24915 12.0051V11.9951C4.24915 11.0286 5.03265 10.2451 5.99915 10.2451ZM17.9991 10.2451C18.9656 10.2451 19.7491 11.0286 19.7491 11.9951V12.0051C19.7491 12.9716 18.9656 13.7551 17.9991 13.7551C17.0326 13.7551 16.2491 12.9716 16.2491 12.0051V11.9951C16.2491 11.0286 17.0326 10.2451 17.9991 10.2451ZM13.7491 11.9951C13.7491 11.0286 12.9656 10.2451 11.9991 10.2451C11.0326 10.2451 10.2491 11.0286 10.2491 11.9951V12.0051C10.2491 12.9716 11.0326 13.7551 11.9991 13.7551C12.9656 13.7551 13.7491 12.9716 13.7491 12.0051V11.9951Z"
                fill="" />
        </svg> --}}
    </h3>

    <ul class="mb-1 flex flex-col gap-1">
        <!-- Menu Payment -->
         @if (auth()->user()->mode === 'admin' || auth()->user()->mode === 'accounts')
        <li>
            <a href="#" @click.prevent="selected = (selected === 'Approvals' ? '' : 'Approvals')" class="menu-item group"
                :class="(selected === 'Approvals') || (page === 'Approvals') ?
                            'menu-item-active' : 'menu-item-inactive'">

                <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                    Approvals
                </span>

                <svg class="menu-item-arrow" :class="[(selected === 'Payments') ? 'menu-item-arrow-active' : 'menu-item-arrow-inactive',
                                    sidebarToggle ? 'lg:hidden' : ''
                                ]" width="20" height="20" viewBox="0 0 20 20" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585" stroke="" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </a>

            <!-- Dropdown Menu Start -->
            <div class="translate transform overflow-hidden" :class="(selected === 'Approvals') ? 'block' : 'hidden'">
                <ul :class="sidebarToggle ? 'lg:hidden' : 'flex'" class="menu-dropdown mt-2 flex flex-col pl-9">
                    <li>
                        <a wire:navigate href="{{ route('admin.damaged-items') }}" class="menu-dropdown-item group"
                            :class="page === 'DamagedItemsApprove' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                            Damage Item Approvals
                            <span class="absolute right-3 flex items-center gap-1">
                                <span class="menu-dropdown-badge" :class="page === 'DamagedItemsApprove' ? 'menu-dropdown-badge-active' :
                                                    'menu-dropdown-badge-inactive'">
                                </span>
                            </span>
                        </a>
                    </li>


                      <li>
                        <a wire:navigate href="{{ route('stock-adjustments.index') }}" class="menu-dropdown-item group"
                            :class="page === 'StockAdjustmentApproval' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                            Adjustment Item Approvals
                            <span class="absolute right-3 flex items-center gap-1">
                                <span class="menu-dropdown-badge" :class="page === 'StockAdjustmentApproval' ? 'menu-dropdown-badge-active' :
                                                    'menu-dropdown-badge-inactive'">
                                </span>
                            </span>
                        </a>
                    </li>

                </ul>
            </div>
            <!-- Dropdown Menu End -->
        </li>
        @endif
    </ul>
</div>
