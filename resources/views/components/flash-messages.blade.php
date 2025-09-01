{{-- resources/views/components/flash-messages.blade.php --}}
@if (session()->has('success'))
  <div class="bg-success-100 text-success-700 px-4 py-2 rounded mb-4">
    {{ session('success') }}
  </div>
@endif

@if (session()->has('error'))
  <div class="bg-error-100 text-error-700 px-4 py-2 rounded mb-4">
    {{ session('error') }}
  </div>
@endif

@if ($errors->any())
  <div class="bg-error-100 text-error-700 px-4 py-2 rounded mb-4">
    <ul class="list-disc list-inside">
      @foreach ($errors->all() as $e)
        <li>{{ $e }}</li>
      @endforeach
    </ul>
  </div>
@endif
