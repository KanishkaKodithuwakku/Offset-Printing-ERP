<aside :class="sidebarToggle ? 'translate-x-0 lg:w-[90px]' : '-translate-x-full'"
    class="sidebar fixed left-0 top-0 z-9999 flex h-screen w-[290px] flex-col overflow-y-hidden border-r border-gray-200 bg-white px-5 duration-300 ease-linear dark:border-gray-800 dark:bg-black lg:static lg:translate-x-0"
    @click.outside="sidebarToggle = false">
    <!-- SIDEBAR HEADER -->
    <div :class="sidebarToggle ? 'justify-center' : 'justify-between'"
        class="flex items-center gap-2 pt-8 sidebar-header pb-7">
        <a href="#">
            <span class="logo" :class="sidebarToggle ? 'hidden' : ''">
                <img class="dark:hidden" src="{{ asset('tailadmin/src/images/logo/logo.png') }}" width="175px;"
                    alt="Logo" />
                <img class="hidden dark:block" src="tailadmin/src/images/logo/logo-dark.png" alt="Logo"
                    width="175px;" />
            </span>

            <img class="logo-icon" :class="sidebarToggle ? 'lg:block' : 'hidden'"
                src="{{ asset('tailadmin/src/images/logo/logo.png') }}" alt="Logo" />
        </a>
    </div>
    <!-- SIDEBAR HEADER -->

    <div class="flex flex-col overflow-y-auto duration-300 ease-linear no-scrollbar">
        <!-- Sidebar Menu -->
        <nav x-data="{ selected: $persist('Dashboard') }">

            @include('livewire.menu.dashboard.index')
           
            @if (auth()->user()->mode === 'admin' || auth()->user()->mode === 'billing'|| auth()->user()->mode === 'accounts')
            @include('livewire.menu.admin.index')
            @endif

            @if (auth()->user()->mode === 'dispatch' || auth()->user()->mode === 'design')
            @include('livewire.menu.inventory-ctp.index')
            @endif
           

            @if (auth()->user()->mode === 'admin' || auth()->user()->mode === 'billing'|| auth()->user()->mode === 'accounts')
            @include('livewire.menu.inventory.index')
            @endif

            
            @include('livewire.menu.sales.index')

            @if (auth()->user()->mode === 'admin' || auth()->user()->mode === 'billing' || auth()->user()->mode === 'accounts')
            @include('livewire.menu.finance.index')
            @endif
           
            @if (auth()->user()->mode === 'admin' || auth()->user()->mode === 'billing' || auth()->user()->mode === 'accounts')
            @include('livewire.menu.mis_reports.index')
            @endif


            @include('livewire.menu.masterdata.index')


            @if (auth()->user()->mode === 'admin')
            @include('livewire.menu.settings.index')
            @endif

        </nav>
        <!-- Sidebar Menu -->

        <!-- Promo Box -->
        {{-- <div :class="sidebarToggle ? 'lg:hidden' : ''"
            class="mx-auto mb-10 w-full max-w-60 rounded-2xl bg-gray-50 px-4 py-5 text-center dark:bg-white/[0.03]">
            <h3 class="mb-2 font-semibold text-gray-900 dark:text-white">
                TailAdmin Pro
            </h3>
            <p class="mb-4 text-gray-500 text-theme-sm dark:text-gray-400">
                Get All Dashboards and 300+ UI Elements
            </p>
            <a href="https://tailadmin.com/pricing" target="_blank" rel="nofollow"
                class="flex items-center justify-center p-3 font-medium text-white rounded-lg bg-brand-500 text-theme-sm hover:bg-brand-600">
                Upgrade Plan
            </a>
        </div> --}}
        <!-- Promo Box -->
    </div>
</aside>