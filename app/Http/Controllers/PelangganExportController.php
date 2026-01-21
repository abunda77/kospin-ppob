<?php

namespace App\Http\Controllers;

use App\Exports\PelangganExport;
use App\Exports\PelangganTemplateExport;
use App\Models\Pelanggan;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class PelangganExportController extends Controller
{
    public function exportExcel()
    {
        $filename = 'pelanggan-'.now()->format('Y-m-d-His').'.xlsx';

        return Excel::download(new PelangganExport, $filename);
    }

    public function exportPdf()
    {
        $maxRecords = config('app.export_limits.pdf_max_records', 1000);
        $totalRecords = Pelanggan::count();

        // Check if total records exceeds limit
        if ($totalRecords > $maxRecords) {
            return redirect()
                ->route('pelanggan.index')
                ->with('warning', "Total data ({$totalRecords} records) melebihi batas export PDF ({$maxRecords} records). Silakan gunakan Export Excel untuk data lengkap, atau filter data terlebih dahulu.");
        }

        // Use chunk to avoid memory issues even within limit
        $pelanggans = Pelanggan::orderBy('nama')
            ->limit($maxRecords)
            ->get();

        $pdf = Pdf::loadView('exports.pelanggan-pdf', [
            'pelanggans' => $pelanggans,
            'totalRecords' => $totalRecords,
            'exportedRecords' => $pelanggans->count(),
        ]);

        // Set paper size and orientation
        $pdf->setPaper('a4', 'landscape');

        $filename = 'pelanggan-'.now()->format('Y-m-d-His').'.pdf';

        return $pdf->download($filename);
    }

    public function downloadTemplate()
    {
        $filename = 'template-import-pelanggan.xlsx';

        return Excel::download(new PelangganTemplateExport, $filename);
    }
}
