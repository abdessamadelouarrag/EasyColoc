<x-app-layout>
    <div class="max-w-xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-2">Invitation</h1>

        @if($errors->has('invite'))
            <div class="p-3 bg-red-100 mb-4">{{ $errors->first('invite') }}</div>
        @endif

        <div class="p-4 border rounded mb-4">
            <div>Email invité: <b>{{ $invitation->email }}</b></div>
            <div>Status: {{ $invitation->status }}</div>
        </div>

        <form method="POST" action="{{ route('invitations.accept', $invitation->token) }}">
            @csrf
            <button class="px-4 py-2 bg-green-600 text-white rounded">Accepter</button>
        </form>

        <form method="POST" action="{{ route('invitations.refuse', $invitation->token) }}" class="mt-2">
            @csrf
            <button class="px-4 py-2 bg-gray-600 text-white rounded">Refuser</button>
        </form>
    </div>
</x-app-layout>