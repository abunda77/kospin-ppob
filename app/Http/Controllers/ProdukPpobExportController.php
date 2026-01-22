<?php

namespace App\Http\Controllers;

use App\Exports\ProdukPpobExport;
use App\Exports\ProdukPpobTemplateExport;
use App\Models\ProdukPpob;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class ProdukPpobExportController extends Controller
{
    public function exportExcel(\Illuminate\Http\Request $request)
    {
        $subKategoriId = $request->input('sub_kategori_id');
        $subKategoriName = '';

        if ($subKategoriId) {
            $subKategori = \App\Models\SubKategori::find($subKategoriId);
            $subKategoriName = $subKategori ? \Illuminate\Support\Str::slug($subKategori->nama).'-' : 'filtered-';
        }

        $filename = 'produk-ppob-'.$subKategoriName.now()->format('Y-m-d-His').'.xlsx';

        return Excel::download(new ProdukPpobExport($subKategoriId), $filename);
    }

    public function exportPdf(\Illuminate\Http\Request $request)
    {
        $subKategoriId = $request->input('sub_kategori_id');
        $maxRecords = config('app.export_limits.pdf_max_records', 1000);

        $query = ProdukPpob::query();
        $subKategoriName = '';

        if ($subKategoriId) {
            $query->where('sub_kategori_id', $subKategoriId);
            $subKategori = \App\Models\SubKategori::find($subKategoriId);
            $subKategoriName = $subKategori ? \Illuminate\Support\Str::slug($subKategori->nama).'-' : 'filtered-';
        }

        $totalRecords = $query->count();

        // Check if total records exceeds limit
        if ($totalRecords > $maxRecords) {
            return redirect()
                ->route('produk-ppob.index')
                ->with('warning', "Total data ({$totalRecords} records) melebihi batas export PDF ({$maxRecords} records). Silakan gunakan Export Excel untuk data lengkap, atau persempit filter.");
        }

        // Use chunk to avoid memory issues even within limit
        $produks = $query->with('subKategori')
            ->orderBy('nama_produk')
            ->limit($maxRecords)
            ->get();

        $pdf = Pdf::loadView('exports.produk-ppob-pdf', [
            'produks' => $produks,
            'totalRecords' => $totalRecords,
            'exportedRecords' => $produks->count(),
            'filterApplied' => $subKategoriId ? true : false,
        ]);

        // Set paper size and orientation
        $pdf->setPaper('a4', 'landscape');

        $filename = 'produk-ppob-'.$subKategoriName.now()->format('Y-m-d-His').'.pdf';

        return $pdf->download($filename);
    }

    public function downloadTemplate()
    {
        $filename = 'template-import-produk-ppob.xlsx';

        return Excel::download(new ProdukPpobTemplateExport, $filename);
    }
}
