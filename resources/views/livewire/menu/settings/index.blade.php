            <!-- Menu Group -->
            <div>
                <h3 class="mb-1 text-xs uppercase leading-[20px] text-gray-400">
                    <span class="menu-group-title" :class="sidebarToggle ? 'lg:hidden' : ''">
                        SETTINGS
                    </span>

                    {{-- <svg :class="sidebarToggle ? 'lg:block hidden' : 'hidden'"
                        class="menu-group-icon mx-auto fill-current" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M5.99915 10.2451C6.96564 10.2451 7.74915 11.0286 7.74915 11.9951V12.0051C7.74915 12.9716 6.96564 13.7551 5.99915 13.7551C5.03265 13.7551 4.24915 12.9716 4.24915 12.0051V11.9951C4.24915 11.0286 5.03265 10.2451 5.99915 10.2451ZM17.9991 10.2451C18.9656 10.2451 19.7491 11.0286 19.7491 11.9951V12.0051C19.7491 12.9716 18.9656 13.7551 17.9991 13.7551C17.0326 13.7551 16.2491 12.9716 16.2491 12.0051V11.9951C16.2491 11.0286 17.0326 10.2451 17.9991 10.2451ZM13.7491 11.9951C13.7491 11.0286 12.9656 10.2451 11.9991 10.2451C11.0326 10.2451 10.2491 11.0286 10.2491 11.9951V12.0051C10.2491 12.9716 11.0326 13.7551 11.9991 13.7551C12.9656 13.7551 13.7491 12.9716 13.7491 12.0051V11.9951Z"
                            fill="" />
                    </svg> --}}
                </h3>

                <ul class="mb-6 flex flex-col gap-4">
                    <!-- Menu Item Uom -->
                    <li>
                        <a href="#" @click.prevent="selected = (selected === 'Uom' ? '':'Uom')"
                            class="menu-item group"
                            :class="(selected === 'Uom') || (page === 'addUom' || page === 'uomList') ?
                            'menu-item-active' : 'menu-item-inactive'">
                            {{-- <svg :class="(selected === 'Uom') || (page === 'addUom' || page === 'uomList') ?
                            'menu-item-icon-active' : 'menu-item-icon-inactive'"
                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 7H7m2 3H7m2 3H7m4 2v2m3-2v2m3-2v2M4 5v14a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1h-9a1 1 0 0 1-1-1V5a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1Z" />
                            </svg> --}}

                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                UOM
                            </span>

                            <svg class="menu-item-arrow"
                                :class="[(selected === 'Uom') ? 'menu-item-arrow-active' : 'menu-item-arrow-inactive',
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
                            :class="(selected === 'Uom') ? 'block' : 'hidden'">
                            <ul :class="sidebarToggle ? 'lg:hidden' : 'flex'"
                                class="menu-dropdown mt-1 flex flex-col pl-9">
                                <li>
                                    <a wire:navigate href="{{ route('uoms.create') }}" class="menu-dropdown-item group"
                                        :class="page === 'addUom' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Create Uom
                                    </a>
                                </li>
                                <li>
                                    <a wire:navigate href="{{ route('uoms.list') }}" class="menu-dropdown-item group"
                                        :class="page === 'uomList' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Uom List
                                        <span class="absolute right-3 flex items-center gap-1">
                                            
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!-- Dropdown Menu End -->
                    </li>
                    <!-- Menu Item Uom -->
                </ul>
            </div>
