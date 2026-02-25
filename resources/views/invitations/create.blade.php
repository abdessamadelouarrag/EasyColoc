<x-app-layout>
    <div class="max-w-xl mx-auto p-6">
        <h1 class="text-2xl font-bold">Inviter un membre</h1>
        <p class="text-slate-600 mt-1">Colocation: <b>{{ $colocation->name }}</b></p>

        @if(session('success'))
            <div class="mt-4 p-3 bg-green-50 text-green-700 font-bold rounded-xl">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="mt-4 p-3 bg-red-50 text-red-700 font-bold rounded-xl">
                {{ $errors->first() }}
            </div>
        @endif

        <form class="mt-6 space-y-4" method="POST" action="{{ route('invitations.store', $colocation) }}">
            @csrf

            <div>
                <label class="font-bold text-sm">Email</label>
                <input name="email" type="email" class="w-full mt-2 border rounded-xl p-3" required>
            </div>

            <button class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-2xl">
                Envoyer l’invitation
            </button>
        </form>
    </div>
</x-app-layout>