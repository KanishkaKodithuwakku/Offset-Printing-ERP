<?php

namespace App\Livewire\Item;

use Livewire\Component;
use Livewire\WithFileUploads;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Models\Item;
use App\Models\Stock;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ItemImport extends Component
{
    use WithFileUploads;

    public $file;
    public $isLoading = false;
    public function importItems()
    {
        Log::info("Import function started..."); // ✅ Step 1: Check if function runs

        if (!$this->file) {
            Log::error("Import Error: No file selected!");
            session()->flash('error', 'No file selected! Please choose a valid CSV file.');
            return;
        }

        try {
            $this->validate([
                'file' => 'required|mimes:csv,txt|max:2048',
            ]);

            $filePath = $this->file->store('imports');
            Log::info("File stored at: " . $filePath); // ✅ Step 2: Log file path

            (new FastExcel())->import(Storage::path($filePath), function ($row) {
                Log::info("Processing row: " . json_encode($row)); // ✅ Step 3: Log row data

                // ✅ Insert or Update Item
                $item = Item::updateOrCreate(
                    ['item_code' => $row['item_code']], // Find by unique item_code
                    [
                        'brands_id'          => $row['brands_id'] ?? null,
                        'item_name'         => $row['item_name'] ?? null,
                        'item_description'  => $row['description'] ?? null,
                        'item_short_description' => $row['short_description'] ?? null,
                        'cost'              => isset($row['cost']) ? floatval($row['cost']) : 0.00,
                        'selling'           => isset($row['selling']) ? floatval($row['selling']) : 0.00,
                        'mrp'               => isset($row['mrp']) ? floatval($row['mrp']) : 0.00,
                        'discount'          => isset($row['discount']) ? floatval($row['discount']) : 0.00,
                        'uom'               => $row['uom'] ?? null,
                        'status'            => $row['status'] ?? 'active',
                    ]
                );

                if ($item && $item->id) {
                    Log::info("Item imported: " . json_encode($item)); // Step 4: Log item creation

                    // Insert or Update Stock
                    Stock::updateOrCreate(
                        ['items_id' => $item->id], // Find by unique items_id
                        [
                            'brands_id'      => $item->brands_id,
                            'qty'            => isset($row['qty']) ? intval($row['qty']) : 0,
                            'cost'           => $item->cost,
                            'selling'        => $item->selling,
                            'mrp'            => $item->mrp,
                            'p_id'           => $row['p_id'] ?? null,
                            'f_id'           => $row['f_id'] ?? null,
                            'effective_date' => now(),
                            'online'         => isset($row['online']) ? intval($row['online']) : 0,
                        ]
                    );

                    Log::info("Stock updated for item_id: " . $item->id); // Step 5: Log stock update
                } else {
                    Log::error("Stock update failed: Item not found.");
                }
            });

            Storage::delete($filePath);
            $this->dispatch('itemsUpdated'); // Step 6: Refresh item list
            $this->dispatch('importFinished'); // Step 7: Notify import finished
            Log::info("Import completed successfully.");
            session()->flash('message', 'Items and stock imported successfully!');
        } catch (\Exception $e) {
            Log::error("Import Error: " . $e->getMessage());
            session()->flash('error', 'Something went wrong!');
        }
    }


    public function updatedFile()
    {
        $this->importItems(); // Automatically calls importItems() after file selection
        $this->dispatch('refresh');
    }

    public function render()
    {
        return view('livewire.item.item-import');
    }
}
