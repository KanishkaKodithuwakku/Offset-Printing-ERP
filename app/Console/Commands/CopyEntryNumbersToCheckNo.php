<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Entry;
use App\Models\EntryItem;
use Illuminate\Support\Facades\DB;

class CopyEntryNumbersToCheckNo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'entries:copy-numbers-to-check-no';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copy numbers that are 6 digits or more from entries.number to entries.check_no and entryitems.check_no';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to copy numbers (6+ digits) to check_no column...');
        
        // Get all entries where number is 6 digits or more and check_no is empty
        // We'll filter by character length (including leading zeros)
        $entries = Entry::whereNotNull('number')
            ->where(function($query) {
                $query->whereNull('check_no')
                      ->orWhere('check_no', '');
            })
            ->get()
            ->filter(function($entry) {
                // Check if number string length is 6 or more characters
                $numberStr = (string) $entry->number;
                return strlen($numberStr) >= 6 && is_numeric($numberStr);
            });
        
        $this->info("Found {$entries->count()} entries to process...");
        
        if ($entries->count() === 0) {
            $this->info('No entries found to update.');
            return 0;
        }
        
        $bar = $this->output->createProgressBar($entries->count());
        $bar->start();
        
        $entriesUpdated = 0;
        $entryitemsUpdated = 0;
        
        foreach ($entries as $entry) {
            $bar->advance();
            
            // Check if number is numeric and 6 digits or more
            $number = $entry->number;
            $numberLength = strlen((string) $number);
            
            if ($numberLength >= 6 && is_numeric($number)) {
                // Update entry check_no
                $entry->check_no = (string) $number;
                $entry->save();
                $entriesUpdated++;
                
                // Update all related entryitems
                $entryitems = EntryItem::where('entry_id', $entry->id)
                    ->where(function($query) {
                        $query->whereNull('check_no')
                              ->orWhere('check_no', '');
                    })
                    ->get();
                
                foreach ($entryitems as $entryitem) {
                    $entryitem->check_no = (string) $number;
                    $entryitem->save();
                    $entryitemsUpdated++;
                }
            }
        }
        
        $bar->finish();
        $this->newLine();
        
        $this->info("✅ Updated {$entriesUpdated} entries");
        $this->info("✅ Updated {$entryitemsUpdated} entryitems");
        $this->info('Copy completed successfully!');
        
        return 0;
    }
}
