<x-app-layout>
    <div class="max-w-6xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Admin Dashboard</h1>

        <div class="grid grid-cols-4 gap-3 mb-6">
            <div class="p-4 border rounded">Users: {{ $stats['users'] }}</div>
            <div class="p-4 border rounded">Colocations: {{ $stats['colocations'] }}</div>
            <div class="p-4 border rounded">Total dépenses: {{ $stats['expenses_total'] }}</div>
            <div class="p-4 border rounded">Bannis: {{ $stats['banned'] }}</div>
        </div>

        @if(session('ok'))
            <div class="p-3 bg-green-100 mb-4">{{ session('ok') }}</div>
        @endif
        @if($errors->has('ban'))
            <div class="p-3 bg-red-100 mb-4">{{ $errors->first('ban') }}</div>
        @endif

        <div class="space-y-2">
            @foreach($users as $u)
                <div class="p-3 border rounded flex items-center justify-between">
                    <div>
                        <div class="font-semibold">{{ $u->name }} ({{ $u->email }})</div>
                        <div class="text-sm text-gray-600">role={{ $u->role }} | banned={{ $u->is_banned ? 'yes' : 'no' }}</div>
                    </div>
                    <form method="POST" action="{{ route('admin.toggleBan', $u) }}">
                        @csrf
                        <button class="px-3 py-2 bg-black text-white rounded">
                            {{ $u->is_banned ? 'Débannir' : 'Bannir' }}
                        </button>
                    </form>
                </div>
            @endforeach
        </div>

        <div class="mt-4">{{ $users->links() }}</div>
    </div>
</x-app-layout>