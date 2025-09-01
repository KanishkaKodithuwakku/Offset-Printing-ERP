<?php

namespace App\Services\Reports;

use App\Models\Entry;
use App\Models\EntryItem;
use App\Models\Ledger;
use Illuminate\Support\Facades\DB;

class LedgerStatementService
{
    public function generate($ledgerId, $startDate = null, $endDate = null)
    {
        $ledger = Ledger::findOrFail($ledgerId);

        // Opening Balance
        $opening = $this->openingBalance($ledgerId, $startDate);

        // Closing Balance
        $closing = $this->closingBalance($ledgerId, $startDate, $endDate);

        // Entries with related entryitems
        $query = Entry::select(
                'entries.*',
                'entryitems.id as item_id',
                'entryitems.amount',
                'entryitems.dc',
                'entryitems.reconciliation_date',
                'entryitems.ledger_id as entry_ledger_id'
            )
            ->join('entryitems', 'entries.id', '=', 'entryitems.entry_id')
            ->where('entryitems.ledger_id', $ledgerId)
            ->orderBy('entries.date', 'asc');

        if ($startDate) {
            $query->where('entries.date', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('entries.date', '<=', $endDate);
        }

        $entries = $query->get();

        return [
            'ledger' => $ledger,
            'entries' => $entries,
            'opening' => $opening,
            'closing' => $closing,
        ];
    }

    public function openingBalance($id, $startDate = null)
    {
        $ledger = Ledger::findOrFail($id);

        $op_total = $ledger->op_balance;
        $op_dc = $ledger->op_balance_dc;

        if (!$startDate) {
            return ['amount' => $op_total, 'dc' => $op_dc];
        }

        $dr = EntryItem::join('entries', 'entries.id', '=', 'entryitems.entry_id')
            ->where('entryitems.ledger_id', $id)
            ->where('entryitems.dc', 'D')
            ->where('entries.date', '<', $startDate)
            ->sum('entryitems.amount');

        $cr = EntryItem::join('entries', 'entries.id', '=', 'entryitems.entry_id')
            ->where('entryitems.ledger_id', $id)
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

    public function closingBalance($id, $startDate = null, $endDate = null)
    {
        $opening = $this->openingBalance($id, $startDate);

        $dr = EntryItem::join('entries', 'entries.id', '=', 'entryitems.entry_id')
            ->where('entryitems.ledger_id', $id)
            ->where('entryitems.dc', 'D');

        $cr = EntryItem::join('entries', 'entries.id', '=', 'entryitems.entry_id')
            ->where('entryitems.ledger_id', $id)
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
}
