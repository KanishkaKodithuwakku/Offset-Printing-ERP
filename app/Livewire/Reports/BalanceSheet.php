<?php

namespace App\Livewire\Reports;

use Livewire\Component;
use App\Models\Group;
use App\Models\Ledger;
use App\Models\EntryItem;
use Illuminate\Support\Facades\DB;

class BalanceSheet extends Component
{
    public $assetTree = [];
    public $liabilityTree = [];
    public $totalAssets = 0;
    public $totalLiabilities = 0;
    public $profitAndLoss = 0.00;
    public $openingBalanceDiff = 0;

    public function mount()
    {
        $this->generate();
    }

    public function generate()
    {
        $this->assetTree = $this->buildAccountTree(1, 'D');  // Group ID 1 = Assets
        $this->liabilityTree = $this->buildAccountTree(2, 'C');  // Group ID 2 = Liabilities

        // dd($this->liabilityTree);

        $this->totalAssets = $this->calculateTreeTotal($this->assetTree);
        $this->totalLiabilities = $this->calculateTreeTotal($this->liabilityTree);

        // Calculate P&L
        $incomeLedgers = Ledger::where('type', 1)->pluck('id')->toArray();
        $expenseLedgers = Ledger::where('type', 0)->pluck('id')->toArray();

        // dd($expenseLedgers);

        $income = EntryItem::whereIn('ledger_id', $incomeLedgers)->where('dc', 'C')->sum('amount');
        $expense = EntryItem::whereIn('ledger_id', $expenseLedgers)->where('dc', 'D')->sum('amount');

        // dd($expense);

        $this->profitAndLoss = round($income - $expense, 2);
        // $this->profitAndLoss = round($this->totalAssets- $this->totalLiabilities, 2);

        // dd($this->profitAndLoss);

        // Calculate opening balance difference
        $totalDr = $this->totalAssets + ($this->profitAndLoss < 0 ? abs($this->profitAndLoss) : 0);
        $totalCr = $this->totalLiabilities + ($this->profitAndLoss > 0 ? $this->profitAndLoss : 0);

        //dd($this->totalAssets -$this->totalLiabilities);

        $this->openingBalanceDiff = round($totalDr - $totalCr, 2);

        //  dd($totalCr);
    }

    public function buildAccountTree($parentId, $dcType)
    {
        $result = [];
        $total = 0;

        $groups = Group::where('parent_id', $parentId)->orderBy('name')->get();

        foreach ($groups as $group) {
            $node = [
                'id' => $group->id,
                'name' => $group->name,
                'code' => $group->code,
                'type' => 'group',
                'children' => [],
                'amount' => 0,
                'amount_dc' => $dcType,
            ];

            $ledgers = Ledger::where('group_id', $group->id)->orderBy('name')->get();

            foreach ($ledgers as $ledger) {
                $amounts = EntryItem::where('ledger_id', $ledger->id)
                    ->selectRaw("
                    SUM(CASE WHEN dc = 'D' THEN amount ELSE 0 END) as dr,
                    SUM(CASE WHEN dc = 'C' THEN amount ELSE 0 END) as cr
                ")->first();

                $dr = $amounts->dr ?? 0;
                $cr = $amounts->cr ?? 0;

                // Opening balance (signed)
                $opening = (float) $ledger->op_balance;
                $openingSigned = $ledger->op_balance_dc === 'D' ? $opening : -$opening;

                // Calculate closing balance: opening + (dr - cr)
                $netMovement = $dr - $cr;
                $closingBalance = $openingSigned + $netMovement;
                $closingBalanceDC = $closingBalance >= 0 ? 'D' : 'C';

                $ledgerNode = [
                    'id' => $ledger->id,
                    'name' => $ledger->name,
                    'code' => $ledger->code,
                    'type' => 'ledger',
                    'op_balance' => $opening,
                    'op_balance_dc' => $ledger->op_balance_dc,
                    'amount' => abs($closingBalance),
                    'amount_dc' => $closingBalanceDC,
                ];

                $node['children'][] = $ledgerNode;
                $total += $closingBalance;
            }

            // Recursive: children groups
            $childGroups = $this->buildAccountTree($group->id, $dcType);
            foreach ($childGroups as $childNode) {
                $node['children'][] = $childNode;
                if (isset($childNode['amount'], $childNode['amount_dc'])) {
                    $childSigned = $childNode['amount_dc'] === 'D' ? $childNode['amount'] : -$childNode['amount'];
                    $total += $childSigned;
                }
            }

            $node['amount'] = abs($total);
            $node['amount_dc'] = $total >= 0 ? 'D' : 'C';

            $result[] = $node;
        }

        return $result;
    }






    public function calculateTreeTotal($tree)
    {
        $total = 0;
        foreach ($tree as $node) {
            if (!empty($node['children'])) {
                foreach ($node['children'] as $child) {
                    $total += $child['amount'];
                }
            }
        }
        // dd($total);
        return $total;
    }


    public function render()
    {
        $bodyAttributes = 'x-data="{ page: \'BlanceSheet\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
        x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
        :class="{\'dark bg-gray-900\': darkMode === true}"';


        $finalAssetTotal = $this->totalAssets + ($this->profitAndLoss < 0 ? abs($this->profitAndLoss) : 0) + ($this->openingBalanceDiff > 0 ? $this->openingBalanceDiff : 0);


        $totalExpences = DB::table('entryitems')
            ->join('ledgers', 'ledgers.id', '=', 'entryitems.ledger_id')
            ->join('groups', 'groups.id', '=', 'ledgers.group_id')
            ->where('groups.parent_id', 4)
            ->sum('entryitems.amount');

        $salesRevenue = DB::table('entryitems')
            ->where('ledger_id', 2)
            ->sum('entryitems.amount');

        $this->profitAndLoss = $salesRevenue - $totalExpences;

        $opdiff = Ledger::getOpeningDiff();
        $is_opdiff = $opdiff['opdiff_balance'] != 0;

        $finalLiabilityTotal = $this->totalLiabilities + ($this->profitAndLoss > 0 ? $this->profitAndLoss : 0) + $opdiff['opdiff_balance'];

        return view('livewire.reports.balance-sheet', [
            'date' => now(),
            'assets' => $this->assetTree,
            'liabilities' => $this->liabilityTree,
            'totalAssets' => $this->totalAssets,
            'totalLiabilities' => $this->totalLiabilities,
            'profitAndLoss' => $this->profitAndLoss,
            'openingBalanceDiff' => $this->openingBalanceDiff,
            'finalAssetTotal' => $finalAssetTotal,
            'finalLiabilityTotal' => $finalLiabilityTotal,
            'opdiff' => $opdiff,
            'is_opdiff' => $is_opdiff,
        ])->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
    }
}
