<x-app-layout>
    <div class="max-w-4xl mx-auto p-6">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-bold">Mes colocations</h1>
            <a class="px-4 py-2 bg-black text-white rounded" href="{{ route('colocations.create') }}">Créer</a>
        </div>

        @if (session('ok'))
            <div class="p-3 bg-green-100 mb-4">{{ session('ok') }}</div>
        @endif

        <div class="space-y-3">
            @foreach($colocations as $c)
                <a class="block p-4 border rounded" href="{{ route('colocations.show', $c) }}">
                    <div class="font-semibold">{{ $c->name }}</div>
                    <div class="text-sm text-gray-600">Status: {{ $c->status }}</div>
                </a>
            @endforeach
        </div>
    </div>
</x-app-layout>