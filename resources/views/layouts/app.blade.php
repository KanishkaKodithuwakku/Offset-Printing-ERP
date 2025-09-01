<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Chandisa') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS -->
    <link href="{{ asset('tailadmin/build/style.css') }}" rel="stylesheet">

    @livewireStyles
    <style>
        .pagination p {
            display: none;
        }
    </style>
</head>


<body {!! $bodyAttributes ?? 'x-data="{ page: \' default\', loaded: true, darkMode: false, stickyMenu: false, scrollTop:
    false }" x-init="
            darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
            $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
    :class="darkMode ? \'bg-gray-900 text-white\' : \'bg-white text-gray-900\'" ' !!} <!--=====Preloader Start=====-->
    
    
    <div x-data="{ loading: true }" x-init="window.onload = () => { setTimeout(() => loading = false, 500); }" x-show="loading"
        class="fixed inset-0 z-50 flex items-center justify-center bg-white dark:bg-gray-900 transition-all duration-300">
        <div class="h-16 w-16 border-4 border-error-500 border-t-transparent rounded-full animate-spin"></div>
    </div>


    <!-- ===== Preloader End ===== -->

    <div class="flex h-screen overflow-hidden">
        <!-- ===== Sidebar Start ===== -->
        @livewire('sidebar-menu')
        <!-- ===== Sidebar End ===== -->

        <!-- ===== Content Area Start ===== -->
        <div class="relative flex flex-col flex-1 overflow-x-hidden overflow-y-auto">
            <!-- Small Device Overlay -->
            <div id="sm-dev-overlay" @click="sidebarToggle = false" :class="sidebarToggle ? 'block lg:hidden' : 'hidden'"
                class="fixed w-full h-screen z-9 bg-gray-900/50"></div>

            <!-- ===== Header ===== -->
            @livewire('navigation-menu')

            <!-- ===== Page Content ===== -->
            <main class="p-4 sm:p-6">
                {{ $slot }}
            </main>
        </div>
        <!-- ===== Content Area End ===== -->
    </div>

    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        function clearMessage() {
            const flashMessage = document.getElementById('flash-message');
            if (flashMessage) {
                flashMessage.style.display = 'none';
            }
        }

        setTimeout(() => {
            const msg = document.getElementById('flash-message');
            if (msg) msg.innerHTML = '';
        }, 3000);
    </script>

</body>

</html>