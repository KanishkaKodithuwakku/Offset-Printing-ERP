<!-- Menu Group -->
<div>
    <h3 class="mb-1 text-xs uppercase leading-[20px] text-gray-400">
        <span class="menu-group-title" :class="sidebarToggle ? 'lg:hidden' : ''">
            CTP
        </span>

        {{-- <svg :class="sidebarToggle ? 'lg:block hidden' : 'hidden'" class="menu-group-icon mx-auto fill-current"
            width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd"
                d="M5.99915 10.2451C6.96564 10.2451 7.74915 11.0286 7.74915 11.9951V12.0051C7.74915 12.9716 6.96564 13.7551 5.99915 13.7551C5.03265 13.7551 4.24915 12.9716 4.24915 12.0051V11.9951C4.24915 11.0286 5.03265 10.2451 5.99915 10.2451ZM17.9991 10.2451C18.9656 10.2451 19.7491 11.0286 19.7491 11.9951V12.0051C19.7491 12.9716 18.9656 13.7551 17.9991 13.7551C17.0326 13.7551 16.2491 12.9716 16.2491 12.0051V11.9951C16.2491 11.0286 17.0326 10.2451 17.9991 10.2451ZM13.7491 11.9951C13.7491 11.0286 12.9656 10.2451 11.9991 10.2451C11.0326 10.2451 10.2491 11.0286 10.2491 11.9951V12.0051C10.2491 12.9716 11.0326 13.7551 11.9991 13.7551C12.9656 13.7551 13.7491 12.9716 13.7491 12.0051V11.9951Z"
                fill="" />
        </svg> --}}
    </h3>

    <ul class="mb-1 flex flex-col gap-1">

        @if (auth()->user()->mode === 'dispatch' || auth()->user()->mode === 'design')
        <!-- Menu Stock Summery -->
        <li>
            <a href="#" @click.prevent="selected = (selected === 'Items' ? '':'Items')" class="menu-item group" :class="(selected === 'Items') || (page === 'addItem' ||
                        page === 'itemList' || page === 'inactiveItems' || page === 'archivedItems') ?
                    'menu-item-active' : 'menu-item-inactive'">

                {{-- <svg :class="(selected === 'Items') || (page === 'addItem' ||
                        page === 'itemList' || page === 'inactiveItems' || page === 'archivedItems') ?
                    'menu-item-icon-active' : 'menu-item-icon-inactive'" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M11.665 3.75618C11.8762 3.65061 12.1247 3.65061 12.3358 3.75618L18.7807 6.97853L12.3358 10.2009C12.1247 10.3064 11.8762 10.3064 11.665 10.2009L5.22014 6.97853L11.665 3.75618ZM4.29297 8.19199V16.0946C4.29297 16.3787 4.45347 16.6384 4.70757 16.7654L11.25 20.0365V11.6512C11.1631 11.6205 11.0777 11.5843 10.9942 11.5425L4.29297 8.19199ZM12.75 20.037L19.2933 16.7654C19.5474 16.6384 19.7079 16.3787 19.7079 16.0946V8.19199L13.0066 11.5425C12.9229 11.5844 12.8372 11.6207 12.75 11.6515V20.037ZM13.0066 2.41453C12.3732 2.09783 11.6277 2.09783 10.9942 2.41453L4.03676 5.89316C3.27449 6.27429 2.79297 7.05339 2.79297 7.90563V16.0946C2.79297 16.9468 3.27448 17.7259 4.03676 18.1071L10.9942 21.5857L11.3296 20.9149L10.9942 21.5857C11.6277 21.9024 12.3732 21.9024 13.0066 21.5857L19.9641 18.1071C20.7264 17.7259 21.2079 16.9468 21.2079 16.0946V7.90563C21.2079 7.05339 20.7264 6.27429 19.9641 5.89316L13.0066 2.41453Z"
                        fill="" />
                </svg> --}}

                <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                    Stock Summery
                </span>

                <svg class="menu-item-arrow absolute right-2.5 top-1/2 -translate-y-1/2 stroke-current" :class="[(selected === 'Items') ? 'menu-item-arrow-active' :
                            'menu-item-arrow-inactive', sidebarToggle ? 'lg:hidden' : ''
                        ]" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585" stroke="" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </a>

            <!-- Dropdown Menu Start -->
            <div class="overflow-hidden transform translate" :class="(selected === 'Items') ? 'block' : 'hidden'">
                <ul :class="sidebarToggle ? 'lg:hidden' : 'flex'" class="flex flex-col mt-1 menu-dropdown pl-9">

                    <li>
                        <a wire:navigate href="{{ route('stocks') }}" class="menu-dropdown-item group" :class="page === 'itemList' ? 'menu-dropdown-item-active' :
                                    'menu-dropdown-item-inactive'">
                            Stock Summery
                        </a>
                    </li>
                </ul>
            </div>
            <!-- Dropdown Menu End -->
        </li>
        <!-- Menu Stock Summery -->
        @endif

    </ul>
</div>