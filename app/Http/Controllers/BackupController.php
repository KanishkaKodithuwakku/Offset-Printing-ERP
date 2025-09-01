<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BackupController extends Controller
{
    public function backupDatabase()
{
    // Database credentials
    $dbHost = env('DB_HOST', 'localhost');
    $dbName = env('DB_DATABASE');
    $dbUser = env('DB_USERNAME');
    $dbPass = env('DB_PASSWORD');

    // The backup file name with the current timestamp
    $backupFile = storage_path("app/backups/backup_" . now()->format('Y-m-d_H-i-s') . ".sql");

    // Command to run mysqldump
    $command = "mysqldump -h $dbHost -u $dbUser -p$dbPass $dbName > $backupFile";

    // Execute the command
    $output = null;
    $resultCode = null;
    exec($command, $output, $resultCode);

    // Check if the backup was successful
    if ($resultCode === 0) {
        return response()->json(['success' => 'Backup created successfully', 'file' => $backupFile]);
    } else {
        return response()->json(['error' => 'Failed to create backup', 'output'=>$output]);
    }
}
}
