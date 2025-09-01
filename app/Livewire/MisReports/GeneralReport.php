<?php

namespace App\Livewire\MisReports;

use Livewire\Component;

class GeneralReport extends Component
{

    public function render()
    {
         $bodyAttributes = 'x-data="{ page: \'generealreport\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
        x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
        :class="{\'dark bg-gray-900\': darkMode === true}"';
        return view('livewire.mis-reports.general-report')->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
