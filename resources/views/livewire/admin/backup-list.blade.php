<div class="p-4 mx-auto max-w-screen-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg">
    @if (session('success'))
    <div class="mb-2 rounded-xl border border-success-500 bg-success-50 p-4 dark:border-success-500/30 dark:bg-success-500/15">
        <div class="flex items-start gap-3">
            <div class="-mt-0.5 text-success-500">
                <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M3.70186 12.0001C3.70186 7.41711 7.41711 3.70186 12.0001 3.70186C16.5831 3.70186 20.2984 7.41711 20.2984 12.0001C20.2984 16.5831 16.5831 20.2984 12.0001 20.2984C7.41711 20.2984 3.70186 16.5831 3.70186 12.0001ZM12.0001 1.90186C6.423 1.90186 1.90186 6.423 1.90186 12.0001C1.90186 17.5772 6.423 22.0984 12.0001 22.0984C17.5772 22.0984 22.0984 17.5772 22.0984 12.0001C22.0984 6.423 17.5772 1.90186 12.0001 1.90186ZM15.6197 10.7395C15.9712 10.388 15.9712 9.81819 15.6197 9.46672C15.2683 9.11525 14.6984 9.11525 14.347 9.46672L11.1894 12.6243L9.6533 11.0883C9.30183 10.7368 8.73198 10.7368 8.38051 11.0883C8.02904 11.4397 8.02904 12.0096 8.38051 12.3611L10.553 14.5335C10.7217 14.7023 10.9507 14.7971 11.1894 14.7971C11.428 14.7971 11.657 14.7023 11.8257 14.5335L15.6197 10.7395Z"
                        fill="" />
                </svg>
            </div>
            <div>
                <h4 class="mb-1 text-sm font-semibold text-gray-800 dark:text-white/90">
                    Success
                </h4>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ session('success') }}
                </p>
            </div>
        </div>
    </div>
@elseif (session('error'))
    <div class="rounded-xl border border-red-500 bg-red-50 p-4 dark:border-red-500/30 dark:bg-red-500/10">
        <div class="flex items-start gap-3">
            <div class="-mt-0.5 text-red-500">
                <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M12 3C7.031 3 3 7.031 3 12s4.031 9 9 9 9-4.031 9-9-4.031-9-9-9Zm0 16c-3.866 0-7-3.134-7-7s3.134-7 7-7 7 3.134 7 7-3.134 7-7 7Zm-.75-11a.75.75 0 0 1 1.5 0v4.5a.75.75 0 0 1-1.5 0V8Zm0 6.75a.75.75 0 1 1 1.5 0 .75.75 0 0 1-1.5 0Z"
                        fill="" />
                </svg>
            </div>
            <div>
                <h4 class="mb-1 text-sm font-semibold text-gray-800 dark:text-white/90">
                    Error
                </h4>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ session('error') }}
                </p>
            </div>
        </div>
    </div>
@endif
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-base font-semibold text-gray-800 dark:text-white">Database Backups</h2>

        </div>
        <a
            href="{{ route('admin.database.backup') }}"
            class="px-4 py-2 bg-brand-500 hover:bg-success-500 text-white font-medium text-xs rounded-lg transition-colors duration-200 flex items-center"
        >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Create New Backup
        </a>
    </div>

    @if (count($backups) > 0)
        <div class="bg-white dark:bg-gray-800  border border-gray-200 dark:border-gray-700 shadow-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y  divide-gray-200 dark:divide-gray-700  ">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Backup File
                            </th>
                            <th class="px-6 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Size
                            </th>
                            <th class="px-6 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Created
                            </th>
                            <th class="px-6 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($backups as $backup)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-2 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-gray-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <span class="text-sm font-normal text-gray-600 dark:text-white font-mono">
                                            {{ $backup['name'] }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $backup['size'] }}
                                </td>
                                <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $backup['created_formatted'] }}
                                </td>
                                <td class="px-6 py-2 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button
                                            wire:click="downloadBackup('{{ $backup['name'] }}')"
                                            class="text-gray-600 hover:text-brand-500 flex items-center"
                                            title="Download"
                                        >
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>

                                        </button>
                                        <button
                                            wire:click="deleteBackup('{{ $backup['name'] }}')"
                                            wire:confirm="Are you sure you want to delete this backup?"
                                            class="text-gray-600 hover:text-error-500  flex items-center"
                                            title="Delete"
                                        >
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>

                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No backups found</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating a new database backup.</p>
            <div class="mt-6">
                <a
                    href="{{ route('admin.database.backup') }}"
                    class="inline-flex items-center px-6 py-3 bg-brand-500 hover:bg-blue-700 text-white font-medium text-base rounded-lg transition-colors duration-200"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Create Database Backup
                </a>
            </div>
        </div>
    @endif

</div>
