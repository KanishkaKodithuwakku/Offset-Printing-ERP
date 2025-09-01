<?php
namespace App\Livewire\Uom;

use Livewire\Component;
use Illuminate\Support\Facades\Storage;

class DotMatrixPrint extends Component
{
    public $invoiceId;

    public function print()
    {
        $ESC = chr(27);
        $INIT = $ESC . "@";
        $A5_PAPER = $ESC . "C" . chr(35); // approx. 5.8 inches = 35 lines
        $LF = chr(10);
        $FF = chr(12); // Form feed

        // Example content
        $content = "Company Name\n";
        $content .= "Address Line 1\nAddress Line 2\n";
        $content .= "--------------------------\n";
        $content .= "Invoice #: 10001\nDate: " . now()->format('Y-m-d') . "\n";
        $content .= "Item         Qty Price\n";
        $content .= "Pen          2   50.00\n";
        $content .= "Notebook     1  100.00\n";
        $content .= "--------------------------\n";
        $content .= "Total:          200.00\n";
        $content .= $FF;

        // Combine ESC commands
        $printData = $INIT . $A5_PAPER . $content;

        // Save to file (public_path used for easier access)
        $filename = public_path('dotmatrix-print.txt');
        file_put_contents($filename, $printData);

        // Optional: Send to printer (Windows command, adjust path/printer name)
        // Uncomment this only if running on Windows with correct access
        // exec("print /D:LPT1 " . escapeshellarg($filename));

        session()->flash('message', 'Print file generated successfully.');
    }

    public function render()
    {
        return view('livewire.dot-matrix-print');
    }
}
