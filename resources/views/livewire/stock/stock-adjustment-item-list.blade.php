<div>


    @if ($adjustment)
        <div class="p-4 bg-white shadow rounded-lg">
            <h4 class="font-semibold mb-4 text-normal text-gray-800">Adjustment Items for Adjustment</h4>



            <!-- Display a success message -->
            @if (session('message'))
                <div
                    class="rounded-xl border border-success-500 bg-success-50 p-4 dark:border-success-500/30 dark:bg-success-500/15">
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
                                {{ session('message') }}
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

            <table class="min-w-full mt-4 table-auto border-collapse">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-sm text-gray-500 border text-left">Item Name</th>
                        <th class="px-4 py-2 text-sm text-gray-500 border text-left">Item Code</th>
                        <th class="px-4 py-2 text-sm text-gray-500 border text-left">Pre Qty</th>
                        <th class="px-4 py-2 text-sm text-gray-500 border text-left">Adjustment Qty</th>
                        <th class="px-4 py-2 text-sm text-gray-500 border text-left">Remark</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($adjustment->adjustmentItems as $item)
                        <tr>
                            <td class="px-4 py-2 text-xs border">{{ $item->item->item_name }}</td>
                            <td class="px-4 py-2 text-xs border">{{ $item->item->item_code }}</td>
                            <td class="px-4 py-2 text-xs border">{{ $item->pre_qty }}</td>
                            <td class="px-4 py-2 text-xs border">{{ $item->quantity }}</td>
                            <td class="px-4 py-2 text-xs border">{{ $item->remark }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-gray-500">No items to display. Please click on a valid adjustment row.</p>
    @endif

    <!-- Approve Button -->
    <div class="mt-4">
        @if ($adjustment->status != 'approved')
            <!-- Show button if not already approved -->
            <button wire:click="approveAdjustment"
                class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-md text-xs shadow-md transition duration-300 ease-in-out transform hover:scale-105"
                style="background-color:#465FFF;">Approve
                Adjustment</button>
        
        @endif
    </div>
</div>
</div>
