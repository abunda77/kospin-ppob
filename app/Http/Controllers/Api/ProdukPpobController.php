<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProdukPpob;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProdukPpobController extends Controller
{
    /**
     * Get list of Produk PPOB.
     */
    public function index(Request $request): JsonResponse
    {
        $query = ProdukPpob::query()->with('subKategori');

        // Filter by Sub Kategori ID
        if ($request->has('sub_kategori_id')) {
            $query->where('sub_kategori_id', $request->input('sub_kategori_id'));
        }

        // Filter by Sub Kategori Name
        if ($request->has('sub_kategori_nama')) {
            $query->whereHas('subKategori', function ($q) use ($request) {
                $q->where('nama', 'like', '%'.$request->input('sub_kategori_nama').'%');
            });
        }

        // Filter by Kode Produk
        if ($request->has('kode')) {
            $query->where('kode', $request->input('kode'));
        }

        // Search by Nama Produk (scoped to sub category if filter is applied above)
        if ($request->has('search')) {
            $query->where('nama_produk', 'like', '%'.$request->input('search').'%');
        }

        // Filter Active Status (Optional, maybe default to active?)
        // Let's allow filtering, but maybe default to all or just active?
        // User didn't specify, but for PPOB usually we want active products.
        // Let's make it an option, defaulting to all if not specified,
        // or just let the caller decide. I'll add a check if they want to filter active.
        if ($request->has('aktif')) {
            $query->where('aktif', filter_var($request->input('aktif'), FILTER_VALIDATE_BOOLEAN));
        }

        // Pagination
        $perPage = $request->input('per_page', 15);
        $products = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Data Produk PPOB retrieved successfully.',
            'data' => $products,
        ]);
    }
}
