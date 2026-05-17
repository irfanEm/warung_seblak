<?php

namespace App\Http\Controllers;

use App\Domain\Table\Models\Table;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class TablePrintController extends Controller
{
    public function print(int $tableId)
    {
        $table = Table::with('outlet')->findOrFail($tableId);

        if (!$table->qr_code_image_path) {
            return back()->with([
                'message' => 'QR Code belum di-generate untuk meja ini. Silakan generate terlebih dahulu.',
                'message_type' => 'error'
            ]);
        }

        // Ukuran 10x10 cm dalam point (1 cm = 28.346 pt) -> 283.46
        $pdf = Pdf::loadView('pdf.table-label', compact('table'))
            ->setPaper([0, 0, 283.46, 283.46], 'portrait');

        return $pdf->stream("label-meja-{$table->table_number}.pdf");
    }
}
