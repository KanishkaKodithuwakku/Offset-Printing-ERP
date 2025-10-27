<div class="p-4 mx-auto max-w-screen-2xl">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Database Backup</h2>
        <p class="text-gray-600 dark:text-gray-400">Create a backup of your database</p>
    </div>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700">
            <div class="p-6">
                <div class="text-center mb-6">
                    <div class="mx-auto w-16 h-16 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Database Backup</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Database: <span class="font-mono bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">{{ env('DB_DATABASE') }}</span>
                    </p>
                </div>

                <!-- Backup Button -->
                <div class="text-center mb-6">
                    <button
                        wire:click="startBackup"
                        @if($isBackingUp) disabled @endif
                        class="px-6 py-3 bg-success-500 hover:bg-green-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white font-medium rounded-lg transition-colors duration-200 flex items-center justify-center mx-auto mb-3"
                    >
                        @if($isBackingUp)
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Creating Backup...
                        @else
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Start Backup
                        @endif
                    </button>

                    <!-- View Backups Link -->
                    <div class="text-center">
                        <a
                            href="{{ route('admin.database.backup.list') }}"
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            View Existing Backups
                        </a>
                    </div>
                </div>

                <!-- Progress Bar -->
                @if($isBackingUp || $backupProgress > 0)
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Progress</span>
                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ $backupProgress }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                        <div
                            class="bg-success-500 h-3 rounded-full transition-all duration-300 ease-out"
                            style="width: {{ $backupProgress }}%"
                        ></div>
                    </div>
                </div>
                @endif

                <!-- Status Message -->
                @if($backupStatus && !session()->has('error'))
                <div class="mb-6 p-4 rounded-lg {{ $isBackingUp ? 'bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800' : 'bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800' }}">
                    <div class="flex items-center">
                        @if($isBackingUp)
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        @else
                            <svg class="w-5 h-5 mr-3 text-success-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        @endif
                        <span class="text-sm {{ $isBackingUp ? 'text-blue-700 dark:text-blue-300' : 'text-green-700 dark:text-green-300' }}">
                            {{ $backupStatus }}
                        </span>
                    </div>
                </div>
                @endif

                <!-- Download Button -->
                @if($downloadUrl && !$isBackingUp)
                <div class="text-center">
                    <a
                        href="{{ $downloadUrl }}"
                        class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Download Backup
                    </a>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                        File: {{ $backupFileName }}
                    </p>
                </div>
                @endif

                <!-- Flash Messages -->
                @if (session()->has('success'))
                    <div class="mt-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm text-green-700 dark:text-green-300">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if (session()->has('error'))
                    <div class="mt-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 mr-3 text-red-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            <div class="flex-1">
                                <span class="text-sm text-red-700 dark:text-red-300">{{ session('error') }}</span>

                                @if(strpos(session('error'), 'MySQL client tools not found') !== false)
                                    <div class="mt-3 text-xs text-red-600 dark:text-red-400">
                                        <p class="font-medium mb-2">To fix this issue:</p>
                                        <ul class="list-disc list-inside space-y-1">
                                            <li><strong>Windows:</strong> Install MySQL client tools or add the MySQL bin directory to your system PATH</li>
                                            <li><strong>XAMPP:</strong> Add <code class="bg-red-100 dark:bg-red-900 px-1 rounded">C:\xampp\mysql\bin</code> to your PATH</li>
                                            <li><strong>WAMP:</strong> Add <code class="bg-red-100 dark:bg-red-900 px-1 rounded">C:\wamp64\bin\mysql\mysql8.0.21\bin</code> to your PATH</li>
                                            <li><strong>Alternative:</strong> Use phpMyAdmin or MySQL Workbench for manual backups</li>
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Backup Information -->
        <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700">
            <div class="p-6">
                <h4 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Backup Information</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="font-medium text-gray-700 dark:text-gray-300">Database:</span>
                        <span class="ml-2 font-mono bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">{{ env('DB_DATABASE') }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700 dark:text-gray-300">Host:</span>
                        <span class="ml-2">{{ config('database.connections.mysql.host') }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700 dark:text-gray-300">Backup Location:</span>
                        <span class="ml-2">{{ storage_path('app/backups') }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700 dark:text-gray-300">File Format:</span>
                        <span class="ml-2">SQL Dump</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
