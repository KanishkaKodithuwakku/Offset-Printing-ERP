<?php

namespace App\Livewire\Reports;
use App\Services\Reports\TrialBalanceReportService;
use Livewire\Component;
use App\Helpers\helpers;



class TrialBalanceReport extends Component
{
    public $accounts = [];
    public $dr_total = 0;
    public $cr_total = 0;

    public function mount()
    {
        $service = new TrialBalanceReportService;

        $this->accounts = $service->generate();
        $this->dr_total = $service->dr_total;
        $this->cr_total = $service->cr_total;
    }

    public function render()
    {
         $bodyAttributes = 'x-data="{ page: \'TrialBalanceReport\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
        x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
        :class="{\'dark bg-gray-900\': darkMode === true}"';

        return view('livewire.reports.trial-balance-report')->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
