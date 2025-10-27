<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BackupController extends Controller
{
    /**
     * Download database backup file
     */
    public function download(Request $request, string $file): BinaryFileResponse|Response
    {
        // Validate filename to prevent directory traversal
        $file = basename($file);

        if (!preg_match('/^backup_\d{4}-\d{2}-\d{2}_\d{2}-\d{2}-\d{2}\.sql$/', $file)) {
            abort(404, 'Invalid backup file format');
        }

        $backupPath = storage_path('app/backups/' . $file);

        if (!file_exists($backupPath)) {
            abort(404, 'Backup file not found');
        }

        return response()->download($backupPath, $file, [
            'Content-Type' => 'application/sql',
            'Content-Disposition' => 'attachment; filename="' . $file . '"',
        ]);
    }

    /**
     * List available backup files
     */
    public function list()
    {
        $backupDir = storage_path('app/backups');

        if (!is_dir($backupDir)) {
            return response()->json(['files' => []]);
        }

        $files = glob($backupDir . '/backup_*.sql');
        $fileList = [];

        foreach ($files as $file) {
            $fileList[] = [
                'name' => basename($file),
                'size' => filesize($file),
                'created' => filemtime($file),
                'download_url' => route('admin.database.backup.download', ['file' => basename($file)])
            ];
        }

        // Sort by creation time (newest first)
        usort($fileList, function($a, $b) {
            return $b['created'] - $a['created'];
        });

        return response()->json(['files' => $fileList]);
    }

    /**
     * Delete backup file
     */
    public function delete(Request $request, string $file)
    {
        // Validate filename
        $file = basename($file);

        if (!preg_match('/^backup_\d{4}-\d{2}-\d{2}_\d{2}-\d{2}-\d{2}\.sql$/', $file)) {
            abort(404, 'Invalid backup file format');
        }

        $backupPath = storage_path('app/backups/' . $file);

        if (!file_exists($backupPath)) {
            abort(404, 'Backup file not found');
        }

        if (unlink($backupPath)) {
            return response()->json(['message' => 'Backup file deleted successfully']);
        }

        return response()->json(['error' => 'Failed to delete backup file'], 500);
    }
}
