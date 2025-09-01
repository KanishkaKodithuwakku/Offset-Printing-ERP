<?php

namespace App\Services\Reports;

use App\Models\Entry;
use App\Models\EntryItem;
use App\Models\Ledger;
use Illuminate\Support\Facades\DB;
use App\Helpers\AccountingHelper;

class LedgerEntriesService
{
    public function generate($ledgerId, $startDate = null, $endDate = null)
    {
        $ledger = Ledger::findOrFail($ledgerId);
        $opening = AccountingHelper::openingBalance($ledgerId, $startDate);
        $closing = AccountingHelper::closingBalance($ledgerId, $startDate, $endDate);

        $query = Entry::select('entries.*', 'entryitems.id as item_id', 'entryitems.dc', 'entryitems.amount', 'entryitems.ledger_id')
            ->join('entryitems', 'entries.id', '=', 'entryitems.entry_id')
            ->where('entryitems.ledger_id', $ledgerId)
            ->orderBy('entries.created_at', 'asc');

        if ($startDate) {
            $query->where('entries.date', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('entries.date', '<=', $endDate);
        }

        $entries = $query->get();

        return [
            'ledger' => $ledger,
            'opening_balance' => $opening,
            'closing_balance' => $closing,
            'entries' => $entries
        ];
    }
}
