<?php

namespace App\Livewire\Reports;

use Livewire\Component;
use App\Services\Reports\ProfitLossReportService;

class ProfitLossReport extends Component
{
    public function render()
    {

        $bodyAttributes = 'x-data="{ page: \'ProfitAndLoss\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
        x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
        :class="{\'dark bg-gray-900\': darkMode === true}"';

        $reportService = new ProfitLossReportService();
        $pandl = $reportService->generate();

        return view('livewire.reports.profit-loss-report', [
            'pandl' => $pandl,
        ])->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
