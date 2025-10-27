<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\Storage;

class BackupList extends Component
{
    public $backups = [];
    public $selectedBackup = null;

    protected $listeners = ['refreshBackups'];

    public function mount()
    {
        $this->loadBackups();
    }

    public function loadBackups()
    {
        $backupDir = storage_path('app/backups');

        if (!is_dir($backupDir)) {
            $this->backups = [];
            return;
        }

        $files = glob($backupDir . '/backup_*.sql');
        $backupList = [];

        foreach ($files as $file) {
            $backupList[] = [
                'name' => basename($file),
                'size' => $this->formatFileSize(filesize($file)),
                'created' => filemtime($file),
                'created_formatted' => date('Y-m-d H:i:s', filemtime($file)),
                'download_url' => route('admin.database.backup.download', ['file' => basename($file)])
            ];
        }

        // Sort by creation time (newest first)
        usort($backupList, function($a, $b) {
            return $b['created'] - $a['created'];
        });

        $this->backups = $backupList;
    }

    public function deleteBackup($filename)
    {
        $backupPath = storage_path('app/backups/' . $filename);

        if (file_exists($backupPath) && unlink($backupPath)) {
            $this->loadBackups();
            session()->flash('success', 'Backup deleted successfully');
        } else {
            session()->flash('error', 'Failed to delete backup');
        }
    }

    public function downloadBackup($filename)
    {
        return redirect()->route('admin.database.backup.download', ['file' => $filename]);
    }

    private function formatFileSize($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, 2) . ' ' . $units[$pow];
    }

    public function refreshBackups()
    {
        $this->loadBackups();
    }

    public function render()
    {
        return view('livewire.admin.backup-list')
            ->layout('layouts.app', [
                'bodyAttributes' => 'x-data="{ page: \'BackupList\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
                    x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                            $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
                    :class="{\'dark bg-gray-900\': darkMode === true}"'
            ]);
    }
}
