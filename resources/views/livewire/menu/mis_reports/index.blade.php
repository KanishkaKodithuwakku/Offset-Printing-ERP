            <!-- Menu Group -->
            <div>
                <h3 class="mb-1 text-xs uppercase leading-[20px] text-gray-400">
                    <span class="menu-group-title" :class="sidebarToggle ? 'lg:hidden' : ''">
                        MIS REPORTS
                    </span>
                </h3>
                <ul class="flex flex-col gap-1 mb-1">
                    <!-- General Report Dropdown -->
                    <li>
                        <a href="#" @click.prevent="selected = (selected === 'GeneralReport' ? '' : 'GeneralReport')" class="menu-item group"
                            :class="(selected === 'GeneralReport') ? 'menu-item-active' : 'menu-item-inactive'">
                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                General Report
                            </span>
                            <svg class="menu-item-arrow absolute right-2.5 top-1/2 -translate-y-1/2 stroke-current"
                                :class="[(selected === 'GeneralReport') ? 'menu-item-arrow-active' : 'menu-item-arrow-inactive', sidebarToggle ? 'lg:hidden' : '']"
                                width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585" stroke="" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>
                        <!-- Dropdown Menu Start -->
                        <div class="overflow-hidden transform translate" :class="(selected === 'GeneralReport') ? 'block' : 'hidden'">
                            <ul :class="sidebarToggle ? 'lg:hidden' : 'flex'" class="flex flex-col mt-1 menu-dropdown pl-9">



                                <li>
                                    <a wire:navigate href="{{ route('customer-outstanding') }}" class="menu-dropdown-item group"
                                        :class="page === 'CustomerOutstandingReport' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Outstanding Report
                                        <span class="absolute flex items-center gap-1 right-3">
                                            <span class="menu-dropdown-badge"
                                                :class="page === 'CustomerOutstandingReport' ? 'menu-dropdown-badge-active' :
                                                    'menu-dropdown-badge-inactive'">

                                            </span>
                                        </span>
                                    </a>
                                </li>

                                <li>
                                    <a wire:navigate href="{{ route('reports.age-analysis') }}" class="menu-dropdown-item group"
                                        :class="page === 'AgeAnalysisReport' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Age Analysis Report
                                        <span class="absolute flex items-center gap-1 right-3">
                                            <span class="menu-dropdown-badge"
                                                :class="page === 'AgeAnalysisReport' ? 'menu-dropdown-badge-active' :
                                                    'menu-dropdown-badge-inactive'">

                                            </span>
                                        </span>
                                    </a>
                                </li>

                                <li>
                                    <a wire:navigate href="{{ route('mis-reports.used-plates-report') }}" class="menu-dropdown-item group"
                                        :class="page === 'UsedPlatesReport' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Plate Consumption Detail Report
                                        <span class="absolute flex items-center gap-1 right-3">
                                            <span class="menu-dropdown-badge"
                                                :class="page === 'UsedPlatesReport' ? 'menu-dropdown-badge-active' :
                                                    'menu-dropdown-badge-inactive'">

                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a wire:navigate href="{{ route('mis-reports.plate-summary-report') }}" class="menu-dropdown-item group"
                                        :class="page === 'PlateSummaryReport' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Plate Consumption Summary Report
                                        <span class="absolute flex items-center gap-1 right-3">
                                            <span class="menu-dropdown-badge"
                                                :class="page === 'PlateSummaryReport' ? 'menu-dropdown-badge-active' :
                                                    'menu-dropdown-badge-inactive'">

                                            </span>
                                        </span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                        <!-- Dropdown Menu End -->
                    </li>
                    <!-- Sales Report Dropdown -->
                    <li>
                        <a href="#" @click.prevent="selected = (selected === 'SalesReport' ? '' : 'SalesReport')" class="menu-item group"
                            :class="(selected === 'SalesReport') ? 'menu-item-active' : 'menu-item-inactive'">
                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                Sales Report
                            </span>
                            <svg class="menu-item-arrow absolute right-2.5 top-1/2 -translate-y-1/2 stroke-current"
                                :class="[(selected === 'SalesReport') ? 'menu-item-arrow-active' : 'menu-item-arrow-inactive', sidebarToggle ? 'lg:hidden' : '']"
                                width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585" stroke="" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>
                        <!-- Dropdown Menu Start -->
                        <div class="overflow-hidden transform translate" :class="(selected === 'SalesReport') ? 'block' : 'hidden'">
                            <ul :class="sidebarToggle ? 'lg:hidden' : 'flex'" class="flex flex-col mt-1 menu-dropdown pl-9">
                                <li>
                                    <a wire:navigate href="{{ route('mis-reports.sales-datewise-report') }}" class="menu-dropdown-item group">
                                        Date Wise Report Listing
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="overflow-hidden transform translate" :class="(selected === 'SalesReport') ? 'block' : 'hidden'">
                            <ul :class="sidebarToggle ? 'lg:hidden' : 'flex'" class="flex flex-col mt-1 menu-dropdown pl-9">
                                <li>
                                    <a wire:navigate href="{{ route('mis-reports.customer-wise-sales-report') }}" class="menu-dropdown-item group">
                                         Customer Wise Sales Report
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!-- Dropdown Menu End -->
                    </li>
                </ul>
            </div>
