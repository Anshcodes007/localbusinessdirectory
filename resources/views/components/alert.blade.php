@if (session('success'))
    <div class="mb-4 rounded-lg bg-green-100 border border-green-400 text-green-800 px-4 py-3">
        {{ session('success') }}
    </div>
@endif
@if (session('error'))
    <div class="mb-4 rounded-lg bg-red-100 border border-red-400 text-red-800 px-4 py-3">
        {{ session('error') }}
    </div>
@endif
@if ($errors->any())
    <div class="mb-4 rounded-lg bg-red-100 border border-red-400 text-red-800 px-4 py-3">
        <ul class="list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
