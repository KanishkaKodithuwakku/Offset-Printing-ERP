<?php

namespace App\Services\Reports;

use App\Models\Group;
use App\Models\Ledger;
use App\Models\EntryItem;

class ProfitLossReportService
{
    // public function generate($onlyOpening = false, $startDate = null, $endDate = null)
    // {
    //     $pandl = [];

    //     // Gross Expenses (affects_gross = 1, group_id = 4)
    //     $pandl['gross_expenses'] = $this->buildAccountList(4, 1, $onlyOpening, $startDate, $endDate);
    //     $pandl['gross_expense_total'] = $this->sumClosing($pandl['gross_expenses'], 'D');

    //     // Gross Incomes (affects_gross = 1, group_id = 3)
    //     $pandl['gross_incomes'] = $this->buildAccountList(3, 1, $onlyOpening, $startDate, $endDate);
    //     $pandl['gross_income_total'] = $this->sumClosing($pandl['gross_incomes'], 'C');

    //     $pandl['gross_pl'] = $pandl['gross_income_total'] - $pandl['gross_expense_total'];
    //     $pandl['gross_expense_final'] = $pandl['gross_pl'] >= 0 ? $pandl['gross_expense_total'] + $pandl['gross_pl'] : $pandl['gross_expense_total'];
    //     $pandl['gross_income_final'] = $pandl['gross_pl'] < 0 ? $pandl['gross_income_total'] + $pandl['gross_pl'] : $pandl['gross_income_total'];

    //     // Net Expenses (affects_gross = 0, group_id = 4)
    //     $pandl['net_expenses'] = $this->buildAccountList(4, 0, $onlyOpening, $startDate, $endDate);
    //     $pandl['net_expense_total'] = $this->sumClosing($pandl['net_expenses'], 'D');

    //     // Net Incomes (affects_gross = 0, group_id = 3)
    //     $pandl['net_incomes'] = $this->buildAccountList(3, 0, $onlyOpening, $startDate, $endDate);
    //     $pandl['net_income_total'] = $this->sumClosing($pandl['net_incomes'], 'C');

    //     $pandl['net_pl'] = $pandl['gross_pl'] + ($pandl['net_income_total'] - $pandl['net_expense_total']);

    //     // dd([
    //     //     'gross_expenses' => $pandl['gross_expenses'],
    //     //     'gross_incomes' => $pandl['gross_incomes'],
    //     //     'net_expenses' => $pandl['net_expenses'],
    //     //     'net_incomes' => $pandl['net_incomes'],
    //     // ]);


    //     return $pandl;
    // }

    // protected function buildAccountList($parentId, $affectsGross, $onlyOpening, $startDate, $endDate)
    // {
    //     $tree = [];

    //     $groups = Group::where('parent_id', $parentId)
    //         ->when($affectsGross !== null, fn($q) => $q->where('affects_gross', $affectsGross))
    //         ->orderBy('name')
    //         ->get();

    //     foreach ($groups as $group) {
    //         $groupNode = [
    //             'id' => $group->id,
    //             'name' => $group->name,
    //             'type' => 'group',
    //             'children' => [],
    //             'amount' => 0,
    //             'amount_dc' => 'D',
    //         ];

    //         $ledgers = Ledger::where('group_id', $group->id)->orderBy('name')->get();

    //         foreach ($ledgers as $ledger) {
    //             $query = EntryItem::where('ledger_id', $ledger->id);

    //             if (!$onlyOpening) {
    //                 if ($startDate) $query->whereDate('created_at', '>=', $startDate);
    //                 if ($endDate) $query->whereDate('created_at', '<=', $endDate);
    //             }

    //             $amounts = $query->selectRaw("
    //                     SUM(CASE WHEN dc = 'D' THEN amount ELSE 0 END) as dr,
    //                     SUM(CASE WHEN dc = 'C' THEN amount ELSE 0 END) as cr
    //                 ")->first();

    //             $op = $onlyOpening ? (float) $ledger->op_balance : 0;
    //             $opSigned = $ledger->op_balance_dc === 'D' ? $op : -$op;

    //             $net = $opSigned + ($amounts->dr ?? 0) - ($amounts->cr ?? 0);

    //             $groupNode['children'][] = [
    //                 'id' => $ledger->id,
    //                 'name' => $ledger->name,
    //                 'code' => $ledger->code,
    //                 'type' => 'ledger',
    //                 'amount' => abs($net),
    //                 'amount_dc' => $net >= 0 ? 'D' : 'C',
    //             ];

    //             $groupNode['amount'] += $net;
    //         }

    //         // Recurse into subgroups
    //         $groupNode['children'] = array_merge(
    //             $groupNode['children'],
    //             $this->buildAccountList($group->id, $affectsGross, $onlyOpening, $startDate, $endDate)
    //         );

    //         $groupNode['amount_dc'] = $groupNode['amount'] >= 0 ? 'D' : 'C';
    //         $groupNode['amount'] = abs($groupNode['amount']);

    //         $tree[] = $groupNode;
    //     }

    //     return $tree;
    // }

    // protected function sumClosing(array $items, string $dc): float
    // {
    //     $total = 0;
    //     foreach ($items as $item) {
    //         // Only sum ledger items, skip group-level totals
    //         if ($item['type'] === 'ledger') {
    //             $sign = $item['amount_dc'] === $dc ? 1 : -1;
    //             $total += $sign * $item['amount'];
    //         }

    //         // Recursively check children
    //         if (!empty($item['children'])) {
    //             $total += $this->sumClosing($item['children'], $dc);
    //         }
    //     }
    //     return abs($total);
    // }




    public function generate($onlyOpening = false, $startDate = null, $endDate = null)
    {
        $pandl = [];

        // Gross Expenses (affects_gross = 1, group_id = 4)
        $pandl['gross_expenses'] = $this->buildAccountList(4, 1, $onlyOpening, $startDate, $endDate);
        $pandl['gross_expense_total'] = $this->sumClosing($pandl['gross_expenses'], 'D');

        // Gross Incomes (affects_gross = 1, group_id = 3)
        $pandl['gross_incomes'] = $this->buildAccountList(3, 1, $onlyOpening, $startDate, $endDate);
        $pandl['gross_income_total'] = $this->sumClosing($pandl['gross_incomes'], 'C');

        $pandl['gross_pl'] = $pandl['gross_income_total'] - $pandl['gross_expense_total'];
        $pandl['gross_expense_final'] = $pandl['gross_pl'] >= 0 ? $pandl['gross_expense_total'] + $pandl['gross_pl'] : $pandl['gross_expense_total'];
        $pandl['gross_income_final'] = $pandl['gross_pl'] < 0 ? $pandl['gross_income_total'] + abs($pandl['gross_pl']) : $pandl['gross_income_total'];

        // Net Expenses (affects_gross = 0, group_id = 4)
        $pandl['net_expenses'] = $this->buildAccountList(4, 0, $onlyOpening, $startDate, $endDate);
        $pandl['net_expense_total'] = $this->sumClosing($pandl['net_expenses'], 'D');

        // Net Incomes (affects_gross = 0, group_id = 3)
        $pandl['net_incomes'] = $this->buildAccountList(3, 0, $onlyOpening, $startDate, $endDate);
        $pandl['net_income_total'] = $this->sumClosing($pandl['net_incomes'], 'C');

        $pandl['net_pl'] = $pandl['gross_pl'] + ($pandl['net_income_total'] - $pandl['net_expense_total']);

        return $pandl;
    }

    protected function buildAccountList($parentId, $affectsGross, $onlyOpening, $startDate, $endDate)
    {
        $tree = [];

        $groups = Group::where('parent_id', $parentId)
            ->when($affectsGross !== null, fn($q) => $q->where('affects_gross', $affectsGross))
            ->orderBy('name')
            ->get();

        foreach ($groups as $group) {
            $groupNode = [
                'id' => $group->id,
                'name' => $group->name,
                'type' => 'group',
                'children' => [],
                'amount' => 0,
                'amount_dc' => 'D',
            ];

            $ledgers = Ledger::where('group_id', $group->id)->orderBy('name')->get();

            foreach ($ledgers as $ledger) {
                $query = EntryItem::where('ledger_id', $ledger->id);

                if (!$onlyOpening) {
                    if ($startDate) $query->whereDate('created_at', '>=', $startDate);
                    if ($endDate) $query->whereDate('created_at', '<=', $endDate);
                }

                $amounts = $query->selectRaw("SUM(CASE WHEN dc = 'D' THEN amount ELSE 0 END) as dr, SUM(CASE WHEN dc = 'C' THEN amount ELSE 0 END) as cr")->first();

                $op = $onlyOpening ? (float) $ledger->op_balance : 0;
                $opSigned = $ledger->op_balance_dc === 'D' ? $op : -$op;

                $net = $opSigned + ($amounts->dr ?? 0) - ($amounts->cr ?? 0);

                $groupNode['children'][] = [
                    'id' => $ledger->id,
                    'name' => $ledger->name,
                    'code' => $ledger->code,
                    'type' => 'ledger',
                    'amount' => abs($net),
                    'amount_dc' => $net >= 0 ? 'D' : 'C',
                ];

                $groupNode['amount'] += $net;
            }

            // Recursively include sub-groups
            $groupNode['children'] = array_merge(
                $groupNode['children'],
                $this->buildAccountList($group->id, $affectsGross, $onlyOpening, $startDate, $endDate)
            );

            $groupNode['amount_dc'] = $groupNode['amount'] >= 0 ? 'D' : 'C';
            $groupNode['amount'] = abs($groupNode['amount']);

            $tree[] = $groupNode;
        }

        return $tree;
    }

    protected function sumClosing(array $items, string $dc): float
    {
        $total = 0;
        foreach ($items as $item) {
            if ($item['type'] === 'ledger') {
                $sign = $item['amount_dc'] === $dc ? 1 : -1;
                $total += $sign * $item['amount'];
            }
            if (!empty($item['children'])) {
                $total += $this->sumClosing($item['children'], $dc);
            }
        }
        return abs($total);
    }
}
