<?php

use App\DataTables\UsersDataTable;
use Illuminate\Support\Facades\Route;

Route::get('/test-datatable', function(UsersDataTable $dataTable) {
    try {
        // Test if DataTable can be instantiated
        $html = $dataTable->html();
        
        // Check if ajax URL is set
        $ajax = $html->ajax ?? null;
        
        return response()->json([
            'status' => 'success',
            'message' => 'DataTable instantiated successfully',
            'table_id' => $html->getTableId(),
            'ajax_url' => $ajax,
            'has_data' => \App\Models\User::count() > 0,
            'user_count' => \App\Models\User::count(),
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ], 500);
    }
})->middleware(['auth', 'verified', 'administrator']);
