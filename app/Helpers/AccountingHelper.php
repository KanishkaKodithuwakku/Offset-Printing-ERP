<?php

namespace App\Helpers;

use App\Models\EntryItem;

class AccountingHelper
{
    public static function calculateWithDC(float $amount1, string $dc1, float $amount2, string $dc2): array
    {
        if ($dc1 === $dc2) {
            return [
                'amount' => round($amount1 + $amount2, 2),
                'dc' => $dc1,
            ];
        }

        if ($amount1 > $amount2) {
            return [
                'amount' => round($amount1 - $amount2, 2),
                'dc' => $dc1,
            ];
        }

        return [
            'amount' => round($amount2 - $amount1, 2),
            'dc' => $dc2,
        ];
    }

    public static function openingBalance(int $ledgerId, ?string $startDate = null): array
    {
        $ledger = \App\Models\Ledger::findOrFail($ledgerId);
        $op_total = $ledger->op_balance;
        $op_dc = $ledger->op_balance_dc;

        if (!$startDate) {
            return ['amount' => $op_total, 'dc' => $op_dc];
        }

        $dr = EntryItem::join('entries', 'entries.id', '=', 'entryitems.entry_id')
            ->where('entryitems.ledger_id', $ledgerId)
            ->where('entryitems.dc', 'D')
            ->where('entries.date', '<', $startDate)
            ->sum('entryitems.amount');

        $cr = EntryItem::join('entries', 'entries.id', '=', 'entryitems.entry_id')
            ->where('entryitems.ledger_id', $ledgerId)
            ->where('entryitems.dc', 'C')
            ->where('entries.date', '<', $startDate)
            ->sum('entryitems.amount');

        $dr_total = $op_dc === 'D' ? $op_total + $dr : $dr;
        $cr_total = $op_dc === 'C' ? $op_total + $cr : $cr;

        if ($dr_total > $cr_total) {
            return ['amount' => $dr_total - $cr_total, 'dc' => 'D'];
        } elseif ($cr_total > $dr_total) {
            return ['amount' => $cr_total - $dr_total, 'dc' => 'C'];
        }

        return ['amount' => 0, 'dc' => $op_dc];
    }

    public static function closingBalance(int $ledgerId, ?string $startDate = null, ?string $endDate = null): array
    {
        $opening = self::openingBalance($ledgerId, $startDate);

        $dr = EntryItem::join('entries', 'entries.id', '=', 'entryitems.entry_id')
            ->where('entryitems.ledger_id', $ledgerId)
            ->where('entryitems.dc', 'D');

        $cr = EntryItem::join('entries', 'entries.id', '=', 'entryitems.entry_id')
            ->where('entryitems.ledger_id', $ledgerId)
            ->where('entryitems.dc', 'C');

        if ($startDate) {
            $dr->where('entries.date', '>=', $startDate);
            $cr->where('entries.date', '>=', $startDate);
        }
        if ($endDate) {
            $dr->where('entries.date', '<=', $endDate);
            $cr->where('entries.date', '<=', $endDate);
        }

        $dr_total = $dr->sum('entryitems.amount');
        $cr_total = $cr->sum('entryitems.amount');

        if ($opening['dc'] === 'D') {
            $dr_total += $opening['amount'];
        } else {
            $cr_total += $opening['amount'];
        }

        if ($dr_total > $cr_total) {
            return ['amount' => $dr_total - $cr_total, 'dc' => 'D'];
        } elseif ($cr_total > $dr_total) {
            return ['amount' => $cr_total - $dr_total, 'dc' => 'C'];
        }

        return ['amount' => 0, 'dc' => $opening['dc']];
    }


    public static function reconciliationPending(int $ledgerId, ?string $startDate = null, ?string $endDate = null): array
    {
        // Debit pending
        $dr = EntryItem::join('entries', 'entries.id', '=', 'entryitems.entry_id')
            ->where('entryitems.ledger_id', $ledgerId)
            ->where('entryitems.dc', 'D')
            ->whereNull('entryitems.reconciliation_date')
            ->when($startDate, fn($q) => $q->where('entries.date', '>=', $startDate))
            ->when($endDate, fn($q) => $q->where('entries.date', '<=', $endDate))
            ->sum('entryitems.amount');

        // Credit pending
        $cr = EntryItem::join('entries', 'entries.id', '=', 'entryitems.entry_id')
            ->where('entryitems.ledger_id', $ledgerId)
            ->where('entryitems.dc', 'C')
            ->whereNull('entryitems.reconciliation_date')
            ->when($startDate, fn($q) => $q->where('entries.date', '>=', $startDate))
            ->when($endDate, fn($q) => $q->where('entries.date', '<=', $endDate))
            ->sum('entryitems.amount');

        return [
            'dr_total' => $dr,
            'cr_total' => $cr,
        ];
    }
}
