<?php

namespace App\Livewire;
use Livewire\Component;

class SidebarMenu extends Component
{
    public function render()
    {

        $bodyAttributes = 'x-data="{ page: \'supplierCreate\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
        x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
        :class="{\'dark bg-gray-900\': darkMode === true}"';

        return view('livewire.menu.sidebar-menu',['bodyAttributes'=>$bodyAttributes]);
    }
}
