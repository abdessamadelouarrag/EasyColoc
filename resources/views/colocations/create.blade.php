<x-app-layout>
    <div class="max-w-xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Créer une colocation</h1>

        @if($errors->any())
            <div class="p-3 bg-red-100 mb-4">
                @foreach($errors->all() as $e) <div>{{ $e }}</div> @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('colocations.store') }}" class="space-y-3">
            @csrf
            <input name="name" class="w-full border rounded p-2" placeholder="Nom de la colocation" />
            <button class="px-4 py-2 bg-black text-white rounded">Créer</button>
        </form>
    </div>
</x-app-layout>