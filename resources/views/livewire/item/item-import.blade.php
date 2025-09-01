<div class="p-4 bg-white shadow rounded">
    <!-- Hidden File Input -->
    <input type="file" id="csvUpload" wire:model="file" class="hidden" accept=".csv">

    <!-- Import Button -->
    <button style="background-color:#465FFF;padding 8px 15px;" onclick="document.getElementById('csvUpload').click()"
        class="flex items-center space-x-2 bg-[#465FFF] hover:bg-[#3b4ddb] text-white font-medium px-6 py-2 rounded-md text-sm shadow-md transition duration-300 ease-in-out transform hover:scale-105">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 34 34" fill="none" stroke="currentColor"
            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
            <polyline points="7 10 12 15 17 10"></polyline>
            <line x1="12" x2="12" y1="15" y2="3"></line>
        </svg>
        <span wire:loading.remove wire:target="file">Import CSV</span>
        <span wire:loading wire:target="file">Importing...</span>

    </button>

    <!-- ✅ Show while import is in progress -->
    {{-- <div wire:loading wire:target="file" class="text-gray-500 mt-2">
       Importing... Please wait.
    </div> --}}

</div>
