<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Log;

class DatabaseBackup extends Component
{
    public $isBackingUp = false;
    public $backupProgress = 0;
    public $backupStatus = '';
    public $downloadUrl = '';
    public $backupFileName = '';

    protected $listeners = ['startBackup'];

    public function startBackup()
    {
        $this->isBackingUp = true;
        $this->backupProgress = 0;
        $this->backupStatus = 'Starting backup...';
        $this->downloadUrl = '';
        $this->backupFileName = '';

        // Clear any previous flash messages
        session()->forget(['success', 'error']);

        try {
            // Check if backup count limit is exceeded
            $maxBackups = (int) (config('app.backup_max_count') ?? env('BACKUP_MAX_COUNT', 10));
            $backupDir = storage_path('app/backups');

            if (is_dir($backupDir)) {
                $files = glob($backupDir . '/backup_*.sql');
                $currentCount = count($files);

                if ($currentCount >= $maxBackups) {
                    $this->isBackingUp = false;
                    $this->backupProgress = 0;
                    $this->backupStatus = 'Backup count limit exceeded';
                    session()->flash('error', "Backup count limit exceeded! Maximum allowed: {$maxBackups}. Current backups: {$currentCount}. Please delete old backups from the backup list to create new ones.");
                    return;
                }
            }

            // Get database configuration
            $config = config('database.connections.mysql');

            // Create backup directory if it doesn't exist
            $backupDir = storage_path('app/backups');
            if (!is_dir($backupDir)) {
                mkdir($backupDir, 0755, true);
            }

            // Generate backup filename
            $this->backupFileName = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            $backupPath = $backupDir . '/' . $this->backupFileName;

            // Find mysqldump executable
            $mysqldump = $this->findMysqldump();

            // Build mysqldump command
            $host = $config['host'];
            $port = $config['port'] ?? '3306';

            // Construct the mysqldump command
            // Note: --password without space is intentional for security
            $command = sprintf(
                '%s --user=%s --password=%s --host=%s --port=%s --single-transaction --routines --triggers --default-character-set=utf8mb4 %s > %s 2>&1',
                escapeshellarg($mysqldump),
                escapeshellarg($config['username']),
                escapeshellarg($config['password']),
                escapeshellarg($host),
                escapeshellarg($port),
                escapeshellarg($config['database']),
                escapeshellarg($backupPath)
            );

            $this->backupStatus = 'Creating database dump...';
            $this->backupProgress = 30;

            // Execute backup command
            $process = Process::fromShellCommandline($command);
            $process->setTimeout(300); // 5 minutes timeout

            // Suppress output to avoid password in logs
            $process->setEnv([
                'MYSQL_PWD' => $config['password'] // Alternative method if direct password doesn't work
            ]);

            $process->run();

            if ($process->isSuccessful()) {
                // Verify backup file was created
                if (!file_exists($backupPath) || filesize($backupPath) == 0) {
                    throw new \Exception('Backup file was not created or is empty. Please check MySQL connection.');
                }

                $this->backupProgress = 80;
                $this->backupStatus = 'Backup completed successfully!';

                // Limit backup count
                $this->limitBackupCount();

                // Create download URL
                $this->downloadUrl = route('admin.database.backup.download', ['file' => $this->backupFileName]);

                $this->backupProgress = 100;
                $this->backupStatus = 'Ready for download';

                session()->flash('success', 'Database backup completed successfully!');

                // Redirect to backup list page
                return $this->redirect(route('admin.database.backup.list'), navigate: true);
            } else {
                $errorOutput = $process->getErrorOutput();
                $exitCode = $process->getExitCode();

                // Log the error for debugging
                Log::warning("mysqldump failed with exit code: $exitCode, error: $errorOutput");

                // Always try PHP fallback method when mysqldump fails
                $this->backupStatus = 'mysqldump failed, trying alternative PHP backup method...';
                $this->backupProgress = 40;

                // Try alternative backup using pure PHP/PDO
                if ($this->backupUsingPHP($config, $backupPath)) {
                    $this->backupProgress = 80;
                    $this->backupStatus = 'Backup completed successfully using alternative method!';

                    // Limit backup count
                    $this->limitBackupCount();

                    session()->flash('success', 'Database backup completed successfully using alternative method!');

                    // Redirect to backup list page
                    return $this->redirect(route('admin.database.backup.list'), navigate: true);
                }

                // If PHP backup also failed, show detailed error
                $errorMsg = $errorOutput ?: "mysqldump failed with exit code $exitCode";

                if (strpos($errorOutput, 'Access denied') !== false) {
                    throw new \Exception('Access denied. Please check database username and password in .env file.');
                } elseif (strpos($errorOutput, 'Unknown database') !== false) {
                    throw new \Exception('Database not found. Please check database name in .env file.');
                } else {
                    throw new \Exception('Both mysqldump and PHP backup methods failed. Error: ' . $errorMsg);
                }
            }

        } catch (\Exception $e) {
            Log::error('Database backup failed: ' . $e->getMessage());

            $errorMessage = $e->getMessage();

            // Provide helpful error messages for common issues
            if (strpos($errorMessage, 'mysqldump') !== false && strpos($errorMessage, 'not recognized') !== false) {
                $this->backupStatus = 'MySQL client tools not found. Please install MySQL client or add mysqldump to your system PATH.';
                session()->flash('error', 'MySQL client tools not found. Please install MySQL client or add mysqldump to your system PATH.');
            } else {
                $this->backupStatus = 'Backup failed: ' . $errorMessage;
                session()->flash('error', 'Backup failed: ' . $errorMessage);
            }

            // Reset progress on error
            $this->backupProgress = 0;
        } finally {
            $this->isBackingUp = false;
        }
    }

    private function findMysqldump()
    {
        $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';

        if ($isWindows) {
            // Windows paths for mysqldump
            $possiblePaths = [
                'C:\\xampp\\mysql\\bin\\mysqldump.exe',
                'C:\\wamp64\\bin\\mysql\\mysql8.0.21\\bin\\mysqldump.exe',
                'C:\\wamp\\bin\\mysql\\mysql8.0.21\\bin\\mysqldump.exe',
                'C:\\Program Files\\MySQL\\MySQL Server 8.0\\bin\\mysqldump.exe',
                'C:\\Program Files\\MySQL\\MySQL Server 5.7\\bin\\mysqldump.exe',
                'C:\\Program Files (x86)\\MySQL\\MySQL Server 8.0\\bin\\mysqldump.exe',
                'C:\\Program Files (x86)\\MySQL\\MySQL Server 5.7\\bin\\mysqldump.exe',
                'C:\\laragon\\bin\\mysql\\mysql-8.0.30-winx64\\bin\\mysqldump.exe',
                'mysqldump.exe', // Try from PATH
                'mysqldump' // Try from PATH without extension
            ];

            foreach ($possiblePaths as $path) {
                if (strpos($path, '.exe') !== false || strpos($path, 'mysqldump') !== false) {
                    if (strpos($path, '\\') !== false && file_exists($path)) {
                        return $path;
                    } elseif (strpos($path, 'mysqldump') !== false) {
                        // Check if mysqldump is available in PATH
                        $output = shell_exec('where mysqldump 2>nul');
                        if ($output) {
                            return trim(explode("\n", $output)[0]);
                        }
                    }
                }
            }
        } else {
            // Unix/Linux/Mac paths for mysqldump
            $possiblePaths = [
                '/usr/bin/mysqldump',
                '/usr/local/bin/mysqldump',
                '/opt/lampp/bin/mysqldump',
                '/Applications/XAMPP/xamppfiles/bin/mysqldump',
                '/usr/local/mysql/bin/mysqldump',
                'mysqldump' // Try from PATH
            ];

            foreach ($possiblePaths as $path) {
                if ($path === 'mysqldump') {
                    // Check if mysqldump is available in PATH
                    $output = shell_exec('command -v mysqldump 2>/dev/null');
                    if ($output) {
                        return trim($output);
                    }
                } else {
                    if (is_executable($path)) {
                        return $path;
                    }
                }
            }
        }

        // Fallback
        return $isWindows ? 'mysqldump.exe' : 'mysqldump';
    }

    /**
     * Limit the number of backup files by deleting oldest ones
     */
    private function limitBackupCount()
    {
        try {
            $maxBackups = (int) (config('app.backup_max_count') ?? env('BACKUP_MAX_COUNT', 10));
            $backupDir = storage_path('app/backups');

            if (!is_dir($backupDir)) {
                return;
            }

            // Get all backup files
            $files = glob($backupDir . '/backup_*.sql');

            if (count($files) <= $maxBackups) {
                return;
            }

            // Sort by modification time (oldest first)
            usort($files, function($a, $b) {
                return filemtime($a) - filemtime($b);
            });

            // Delete oldest files beyond the limit
            $filesToDelete = array_slice($files, 0, count($files) - $maxBackups);

            foreach ($filesToDelete as $file) {
                if (file_exists($file)) {
                    unlink($file);
                    Log::info("Deleted old backup: " . basename($file));
                }
            }

            if (count($filesToDelete) > 0) {
                Log::info("Backup count limited to {$maxBackups}. Deleted " . count($filesToDelete) . " old backup(s).");
            }
        } catch (\Exception $e) {
            Log::error('Error limiting backup count: ' . $e->getMessage());
        }
    }

    /**
     * Backup database using pure PHP/PDO (fallback method when mysqldump fails)
     */
    private function backupUsingPHP($config, $backupPath)
    {
        $handle = null;
        try {
            $this->backupStatus = 'Using PHP backup method...';
            $this->backupProgress = 45;

            $handle = fopen($backupPath, 'w+');

            if (!$handle) {
                Log::error('Failed to open backup file for writing');
                return false;
            }

            // Write SQL file header
            fwrite($handle, "-- Database Backup (PHP/PDO Method)\n");
            fwrite($handle, "-- Generated: " . date('Y-m-d H:i:s') . "\n");
            fwrite($handle, "-- Database: {$config['database']}\n\n");
            fwrite($handle, "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n");
            fwrite($handle, "START TRANSACTION;\n");
            fwrite($handle, "SET time_zone = \"+00:00\";\n\n");

            $this->backupProgress = 50;
            $this->backupStatus = 'Connecting to database...';

            // Connect to database
            $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset=utf8mb4";
            $pdo = new \PDO($dsn, $config['username'], $config['password']);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            $this->backupProgress = 55;
            $this->backupStatus = 'Retrieving tables...';

            // Get all tables
            $tables = $pdo->query("SHOW TABLES")->fetchAll(\PDO::FETCH_COLUMN);
            $totalTables = count($tables);

            if ($totalTables === 0) {
                fwrite($handle, "\nCOMMIT;\n");
                fclose($handle);
                return true;
            }

            $this->backupStatus = "Backing up {$totalTables} tables...";

            $tableIndex = 0;
            foreach ($tables as $table) {
                $tableIndex++;
                $progress = 55 + (int)(($tableIndex / $totalTables) * 40);
                $this->backupProgress = min($progress, 90);
                $this->backupStatus = "Backing up table $tableIndex of $totalTables: $table";

                fwrite($handle, "\n-- --------------------------------------------------------\n");
                fwrite($handle, "-- Table structure for `{$table}`\n");
                fwrite($handle, "-- --------------------------------------------------------\n\n");
                fwrite($handle, "DROP TABLE IF EXISTS `{$table}`;\n\n");

                // Get table structure
                $createTable = $pdo->query("SHOW CREATE TABLE `{$table}`")->fetch();
                fwrite($handle, $createTable['Create Table'] . ";\n\n");

                // Get table data in batches to handle large tables
                $offset = 0;
                $batchSize = 1000;

                do {
                    $stmt = $pdo->query("SELECT * FROM `{$table}` LIMIT $batchSize OFFSET $offset");
                    $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                    if (count($rows) === 0) {
                        break;
                    }

                    if ($offset === 0) {
                        fwrite($handle, "\n-- Dumping data for table `{$table}`\n");
                        fwrite($handle, "LOCK TABLES `{$table}` WRITE;\n");
                    }

                    // Get column names (only once per table)
                    if ($offset === 0) {
                        $columns = array_keys($rows[0]);
                    }

                    foreach ($rows as $row) {
                        $values = array_map(function($value) use ($pdo) {
                            if ($value === null) {
                                return 'NULL';
                            }
                            return $pdo->quote($value);
                        }, array_values($row));

                        $columnsList = '`' . implode('`, `', $columns) . '`';
                        $valuesList = implode(', ', $values);

                        fwrite($handle, "INSERT INTO `{$table}` ({$columnsList}) VALUES ({$valuesList});\n");
                    }

                    $offset += $batchSize;

                } while (count($rows) === $batchSize);

                if ($offset > 0) {
                    fwrite($handle, "UNLOCK TABLES;\n\n");
                }
            }

            fwrite($handle, "\nCOMMIT;\n");
            fclose($handle);
            $handle = null;

            // Verify file was created and has content
            if (file_exists($backupPath) && filesize($backupPath) > 0) {
                $fileSize = filesize($backupPath);
                Log::info("Backup created successfully. Size: " . number_format($fileSize / 1024, 2) . " KB");
                return true;
            }

            return false;
        } catch (\Exception $e) {
            Log::error('PHP backup method failed: ' . $e->getMessage());
            if ($handle !== null && is_resource($handle)) {
                fclose($handle);
            }
            return false;
        }
    }

    public function downloadBackup()
    {
        if ($this->downloadUrl) {
            return redirect($this->downloadUrl);
        }
    }

    public function render()
    {
        return view('livewire.admin.database-backup')
            ->layout('layouts.app', [
                'bodyAttributes' => 'x-data="{ page: \'DatabaseBackup\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
                    x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                            $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
                    :class="{\'dark bg-gray-900\': darkMode === true}"'
            ]);
    }
}
