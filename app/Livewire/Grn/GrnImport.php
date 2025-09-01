<?php

namespace App\Livewire\Grn;

use Livewire\Component;
use Livewire\WithFileUploads;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Models\GrnItem;
use App\Models\Stock;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class GrnImport extends Component
{
    use WithFileUploads;

    public $file;
    public $isLoading = false;
    public function importGrnItems()
    {
        Log::info("Import function started...");

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
            Log::info("File stored at: " . $filePath);

            (new FastExcel())->import(Storage::path($filePath), function ($row) {
                Log::info("Processing row: " . json_encode($row));


                $grn_item = GrnItem::updateOrCreate(
                    [
                        'grn_id'   => $row['grn_id'], // Identify the GRN record
                        'item_id'  => $row['item_id'], // Identify the item in this GRN
                    ],
                    [
                        'brands_id'      => $row['brands_id'] ?? null,  // Keep NULL if no value
                        'quantity'       => isset($row['quantity']) ? intval($row['quantity']) : 0,
                        'cost'           => isset($row['cost']) ? floatval($row['cost']) : 0.00,
                        'selling_price'  => isset($row['selling']) ? floatval($row['selling']) : 0.00,
                        'total'          => (isset($row['quantity']) && isset($row['cost']))
                                            ? floatval($row['quantity']) * floatval($row['cost'])
                                            : 0.00, // Ensure total calculation
                    ]
                );


                if ($grn_item && $grn_item->id) {
                    Log::info("GRN Item imported: " . json_encode($grn_item));
                    // Insert or Update Stock
                    Stock::updateOrCreate(
                        ['items_id' => $grn_item->item_id], // Find by unique items_id
                        [
                            'brands_id'      => $grn_item->brands_id,
                            'qty'            => isset($row['qty']) ? intval($row['qty']) : 0,
                            'cost'           => $grn_item->cost,
                            'selling'        => $grn_item->selling,
                            'mrp'            => $grn_item->selling,
                            'p_id'           => $row['p_id'] ?? $grn_item->grn_id,
                            'f_id'           => $row['f_id'] ?? $grn_item->id,
                            'effective_date' => now(),
                            'online'         => isset($row['online']) ? intval($row['online']) : 0,
                        ]
                    );

                    Log::info("Stock updated for grn_item_id: " . $grn_item->id);
                } else {
                    Log::error("Stock update failed: grn_item not found.");
                }
            });

            Storage::delete($filePath);
            $this->dispatch('itemsUpdated');
            $this->dispatch('importFinished');
            Log::info("Import completed successfully.");
            session()->flash('message', 'Items and stock imported successfully!');
        } catch (\Exception $e) {
            Log::error("Import Error: " . $e->getMessage());
            session()->flash('error', 'Something went wrong!');
        }
    }


    public function updatedFile()
    {
        $this->importGrnItems();
        $this->dispatch('refresh');
    }

    public function render()
    {
        return view('livewire.grns.grn-import');
    }
}
