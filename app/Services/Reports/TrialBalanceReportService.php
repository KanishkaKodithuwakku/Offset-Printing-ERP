<?php
namespace App\Services\Reports;

use App\Models\Group;
use App\Models\Ledger;
use App\Models\EntryItem;

class TrialBalanceReportService
{
    public $dr_total = 0;
    public $cr_total = 0;

    public function generate()
    {
        // dd($this->buildAccountTree(null));
        return $this->buildAccountTree(null); // 0 for root
    }

    protected function buildAccountTree($parentId=null, $depth = 0)
    {
        $tree = [];

        $groups = Group::where('parent_id', $parentId)->orderBy('name')->get();

        // dd($groups);

        foreach ($groups as $group) {
            $groupNode = [
                'id' => $group->id,
                'name' => $group->name,
                'type' => 'Group',
                'depth' => $depth,
                'op_total' => 0,
                'op_total_dc' => 'D',
                'dr_total' => 0,
                'cr_total' => 0,
                'cl_total' => 0,
                'cl_total_dc' => 'D',
                'ledgers' => [],
                'children' => [],
            ];

            $ledgers = Ledger::where('group_id', $group->id)->get();

            foreach ($ledgers as $ledger) {
                $entryQuery = EntryItem::where('ledger_id', $ledger->id);

                $drTotal = $entryQuery->where('dc', 'D')->sum('amount');
                $crTotal = EntryItem::where('ledger_id', $ledger->id)->where('dc', 'C')->sum('amount');

                $opAmount = $ledger->op_balance;
                $opSigned = $ledger->op_balance_dc === 'D' ? $opAmount : -$opAmount;
                $clAmount = $opSigned + $drTotal - $crTotal;

                $cl_dc = $clAmount >= 0 ? 'D' : 'C';

                $ledgerData = [
                    'id' => $ledger->id,
                    'name' => $ledger->name,
                    'code' => $ledger->code,
                    'type' => 'Ledger',
                    'depth' => $depth + 1,
                    'op_total' => $opAmount,
                    'op_total_dc' => $ledger->op_balance_dc,
                    'dr_total' => $drTotal,
                    'cr_total' => $crTotal,
                    'cl_total' => abs($clAmount),
                    'cl_total_dc' => $cl_dc,
                ];

                $groupNode['ledgers'][] = $ledgerData;
                $groupNode['dr_total'] += $drTotal;
                $groupNode['cr_total'] += $crTotal;
                $groupNode['op_total'] += $opSigned;
            }

            $clBalance = $groupNode['op_total'] + $groupNode['dr_total'] - $groupNode['cr_total'];
            $groupNode['cl_total'] = abs($clBalance);
            $groupNode['cl_total_dc'] = $clBalance >= 0 ? 'D' : 'C';
            $groupNode['op_total'] = abs($groupNode['op_total']);
            $groupNode['op_total_dc'] = $groupNode['op_total'] >= 0 ? 'D' : 'C';

            $this->dr_total += $groupNode['dr_total'];
            $this->cr_total += $groupNode['cr_total'];

            $groupNode['children'] = $this->buildAccountTree($group->id, $depth + 1);

            $tree[] = $groupNode;
        }

        // dd($tree);

        return $tree;
    }
}
