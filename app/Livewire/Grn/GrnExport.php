<?php
namespace App\Livewire\Grn;

use App\Models\GrnItem;
use Livewire\Component;
use Rap2hpoutre\FastExcel\FastExcel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Item;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GrnExport extends Component
{
    public function exportExcel()
    {
        $items = GrnItem::all();

        $filePath = 'exports/items.xlsx';
        (new FastExcel($items))->export(Storage::path($filePath));

        return response()->download(Storage::path($filePath))->deleteFileAfterSend(true);
    }

    public function exportPDF()
    {
        $items = GrnItem::all();
        $pdf = Pdf::loadView('exports.grn_items-pdf', compact('items'));

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'grn_items.pdf');
    }

    public function render()
    {
        return view('livewire.grns.grn-export');
    }
}
