<x-app-layout>
    <div class="max-w-4xl mx-auto p-6">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-bold">Mes colocations</h1>
            <a class="px-4 py-2 bg-black text-white rounded" href="{{ route('colocations.create') }}">Créer</a>
        </div>

        @if (session('ok'))
            <div class="p-3 bg-green-100 mb-4">{{ session('ok') }}</div>
        @endif

        @if ($errors->has('leave'))
            <div class="p-3 bg-red-100 mb-4">{{ $errors->first('leave') }}</div>
        @endif

        <div class="space-y-3">
            @foreach($colocations as $c)
                <div class="p-4 border rounded flex items-center justify-between">
                    <a class="block" href="{{ route('colocations.show', $c) }}">
                        <div class="font-semibold">{{ $c->name }}</div>
                        <div class="text-sm text-gray-600">Status: {{ $c->status }}</div>
                    </a>

                    <form method="POST" action="{{ route('colocations.leave', $c) }}"
                          onsubmit="return confirm('Quitter cette colocation ?');">
                        @csrf
                        <button class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                            Quitter
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>