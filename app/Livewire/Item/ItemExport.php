<?php
namespace App\Livewire\Item;

use Livewire\Component;
use Rap2hpoutre\FastExcel\FastExcel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Item;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ItemExport extends Component
{
    public function exportExcel()
    {
        $items = Item::all();

        $filePath = 'exports/items.xlsx';
        (new FastExcel($items))->export(Storage::path($filePath));

        return response()->download(Storage::path($filePath))->deleteFileAfterSend(true);
    }

    public function exportPDF()
    {
        $items = Item::all();
        $pdf = Pdf::loadView('exports.items-pdf', compact('items'));

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'items.pdf');
    }

    public function render()
    {
        return view('livewire.item.item-export');
    }
}
