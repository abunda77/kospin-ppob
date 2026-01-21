<?php

namespace App\Http\Controllers;

use App\Models\DatabaseBackup;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BackupDatabaseController extends Controller
{
    public function index(): View
    {
        return view('pages.backup-database');
    }

    public function download(int $id): StreamedResponse
    {
        $backup = DatabaseBackup::findOrFail($id);
        $filePath = storage_path("app/{$backup->file_path}");

        if (! file_exists($filePath)) {
            abort(404, 'File backup tidak ditemukan.');
        }

        return response()->streamDownload(function () use ($filePath) {
            echo file_get_contents($filePath);
        }, $backup->file_name, [
            'Content-Type' => 'application/sql',
        ]);
    }
}
