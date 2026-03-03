<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $colocation->name }} - EasyColoc</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .glass {
            background: rgba(255, 255, 255, .7);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border: 1px solid rgba(255, 255, 255, .75);
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 antialiased">

    <!-- NAV -->
    <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-lg border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard') ?? url('/') }}" class="w-10 h-10 bg-gradient-to-tr from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-200">
                    <span class="text-white font-bold text-sm">€</span>
                </a>
                <div class="hidden sm:block">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">EasyColoc</p>
                    <p class="text-lg font-extrabold tracking-tight leading-none">{{ $colocation->name }}</p>
                </div>
            </div>

            <div class="flex items-center gap-6">
                <span class="px-3 py-1 bg-indigo-50 text-indigo-600 text-xs font-bold rounded-full uppercase tracking-wider">
                    {{ Auth::user()->is_admin ? 'Global Admin' : 'Membre' }}
                </span>

                <div class="flex items-center gap-3 border-l pl-6 border-slate-200">
                    <a href="{{ route('profile.edit') }}"
                        class="hidden md:flex items-center gap-3 border-l pl-6 border-slate-200 group">
                        <div class="text-right">
                            <p class="text-sm font-bold leading-none group-hover:text-indigo-600 transition">
                                {{ Auth::user()->name }}
                            </p>
                            <p class="text-xs text-slate-500 mt-1">Connecté</p>
                        </div>

                        <div class="w-10 h-10 rounded-2xl bg-slate-100 flex items-center justify-center font-black text-slate-600 group-hover:bg-indigo-50 group-hover:text-indigo-600 transition">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="p-2.5 bg-slate-100 text-slate-600 rounded-xl hover:bg-red-50 hover:text-red-600 transition-all" title="Logout">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-6 py-10">

        <!-- FLASH -->
        @if (session('success'))
        <div class="mb-6 glass rounded-2xl p-4 text-sm font-bold text-green-700">
            {{ session('success') }}
        </div>
        @endif

        @if ($errors->any())
        <div class="mb-6 glass rounded-2xl p-4 text-sm font-bold text-red-700">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $err)
                <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- HEADER -->
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-6">
            <div>
                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-2">Détails colocation</p>
                <h1 class="text-4xl font-black tracking-tight text-slate-900">{{ $colocation->name }}</h1>
                <p class="mt-3 text-slate-500 font-medium max-w-xl">
                    Owner : <span class="font-bold text-slate-700">{{ $colocation->owner?->name ?? '—' }}</span>
                    • Statut :
                    <span class="font-bold {{ $colocation->status === 'active' ? 'text-green-600' : 'text-slate-600' }}">
                        {{ $colocation->status }}
                    </span>
                </p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('colocations.index') ?? url('/dashboard') }}"
                    class="px-6 py-3 bg-white text-slate-700 font-bold rounded-2xl border border-slate-200 hover:bg-slate-50 transition-all inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7 7-7M3 12h18" />
                    </svg>
                    Retour
                </a>


                @if(Auth::user()->isOwnerOfColocation($colocation))
                <a href="{{ route('expenses.create', $colocation->id) }}"
                    class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold rounded-2xl shadow-xl shadow-indigo-200 hover:scale-[1.02] transition-all inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Ajouter dépense
                </a>
                @endif
            </div>
        </div>

        <!-- STATS -->
        @php
        $membersCount = $colocation->members?->count() ?? 0;
        $ownerName = $colocation->owner?->name ?? '—';
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-10">

            <!-- Membres -->
            <div class="bg-white p-7 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-white relative overflow-hidden">
                <div class="absolute top-0 right-0 p-6 opacity-10">
                    <svg class="w-20 h-20" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M16 11c1.66 0 3-1.34 3-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zM8 11c1.66 0 3-1.34 3-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zM8 13c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4zm8 0c-.34 0-.7.02-1.07.05 1.16.84 2.07 1.97 2.07 3.45v2h7v-2c0-2.66-5.33-4-8-4z" />
                    </svg>
                </div>
                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-2">Membres</p>
                <h2 class="text-4xl font-black text-slate-900">{{ $membersCount }}</h2>
                <p class="mt-3 text-slate-500 text-sm font-medium">Participants actifs</p>
            </div>

            <!-- Owner -->
            <div class="bg-white p-7 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-white relative overflow-hidden">
                <div class="absolute top-0 right-0 p-6 opacity-10">
                    <svg class="w-20 h-20" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5zm0 2c-4.42 0-8 2-8 4.5V21h16v-2.5C20 16 16.42 14 12 14z" />
                    </svg>
                </div>
                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-2">Owner</p>
                <h2 class="text-2xl font-black text-slate-900 truncate">{{ $ownerName }}</h2>
                <p class="mt-3 text-slate-500 text-sm font-medium">Créateur de la colocation</p>
            </div>

            <!-- Dépenses (placeholder) -->
            <div class="bg-slate-900 p-7 rounded-[2.5rem] shadow-xl shadow-slate-900/20 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 p-6 opacity-10">
                    <svg class="w-20 h-20" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 1a11 11 0 1 0 11 11A11 11 0 0 0 12 1zm1 17h-2v-2h2zm1.07-7.75-.9.92A1.5 1.5 0 0 0 13 13v1h-2v-.5a3 3 0 0 1 .88-2.12l1.24-1.26a1.5 1.5 0 1 0-2.62-1H8.5a3.5 3.5 0 1 1 6.57 1.13z" />
                    </svg>
                </div>

                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-2">Dépenses</p>

                <h2 class="text-4xl font-black">
                    {{ number_format($total ?? 0, 2, ',', ' ') }} €
                </h2>

                <p class="mt-3 text-slate-300 text-sm font-medium">
                    Total des dépenses enregistrées
                </p>
            </div>

            <!-- Statut -->
            <div class="bg-white p-7 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-white relative overflow-hidden">
                <div class="absolute top-0 right-0 p-6 opacity-10">
                    <svg class="w-20 h-20" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm-1 15-5-5 1.41-1.41L11 14.17l7.59-7.59L20 8z" />
                    </svg>
                </div>
                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-2">Statut</p>
                <h2 class="text-2xl font-black {{ $colocation->status === 'active' ? 'text-green-600' : 'text-slate-700' }}">
                    {{ $colocation->status }}
                </h2>
                <p class="mt-3 text-slate-500 text-sm font-medium">État actuel</p>
            </div>
        </div>

        <!-- MEMBERS LIST -->
        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-white overflow-hidden">
            <div class="p-8 border-b border-slate-50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h3 class="text-2xl font-black tracking-tight">Membres</h3>
                    <p class="text-sm text-slate-500 font-medium mt-1">Liste des membres et leurs rôles</p>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('invitations.create', $colocation->id) ?? '#' }}"
                        class="px-5 py-2.5 bg-slate-100 text-slate-700 font-bold rounded-2xl hover:bg-slate-200 transition-all inline-flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4m8-8v16" />
                        </svg>
                        Inviter
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">

                    @php
                    $showActionColumn = Auth::user()->isOwnerOfColocation($colocation);
                    @endphp

                    <thead>
                        <tr class="bg-slate-50/50 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                            <th class="px-8 py-4">Nom</th>
                            <th class="px-8 py-4">Email</th>
                            <th class="px-8 py-4">Rôle</th>

                            @if($showActionColumn)
                            <th class="px-8 py-4 text-right">Action</th>
                            @endif

                            <th class="px-8 py-4">Reputation</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-50">
                        @forelse($colocation->members as $member)
                        <tr class="hover:bg-slate-50/80 transition-colors group">
                            <td class="px-8 py-5">{{ $member->name }}</td>

                            <td class="px-8 py-5">{{ $member->email }}</td>

                            <td class="px-8 py-5">
                                <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase
                    {{ $member->pivot->role === 'owner'
                        ? 'bg-indigo-50 text-indigo-600'
                        : 'bg-slate-100 text-slate-600' }}">
                                    {{ $member->pivot->role }}
                                </span>
                            </td>

                            @if($showActionColumn)
                            <td class="px-8 py-5 text-right">
                                @if($member->pivot->role !== 'owner')
                                <form method="POST"
                                    action="{{ route('colocations.members.remove', [$colocation, $member]) }}"
                                    onsubmit="return confirm('Retirer ce membre ?');">
                                    @csrf
                                    @method('DELETE')

                                    <button class="px-4 py-2 bg-red-50 text-red-600 font-bold rounded-xl hover:bg-red-100 transition">
                                        Retirer
                                    </button>
                                </form>
                                @endif
                            </td>
                            @endif

                            <td class="px-8 py-4">
                                <span class="px-3 py-1 rounded-lg text-[14px] font-black">
                                    {{ $member->reputation }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ $showActionColumn ? 5 : 4 }}" class="px-8 py-10 text-center">
                                Aucun membre
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>

        <!-- EXPENSES LIST -->
        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-white overflow-hidden mt-10">

            <div class="p-8 border-b border-slate-50 flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-black tracking-tight">Dépenses</h3>
                    <p class="text-sm text-slate-500 font-medium mt-1">
                        Historique des dépenses ajoutées
                    </p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                            <th class="px-8 py-4">Titre</th>
                            <th class="px-8 py-4">Catégorie</th>
                            <th class="px-8 py-4">Payé par</th>
                            <th class="px-8 py-4">Date</th>
                            <th class="px-8 py-4 text-right">Montant</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-50">
                        @forelse($colocation->expenses as $expense)
                        <tr class="hover:bg-slate-50/80 transition-colors group">

                            <!-- Title -->
                            <td class="px-8 py-5">
                                <p class="text-sm font-bold text-slate-900 group-hover:text-indigo-600">
                                    {{ $expense->title }}
                                </p>
                            </td>

                            <!-- Category -->
                            <td class="px-8 py-5">
                                <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase bg-indigo-50 text-indigo-600">
                                    {{ $expense->category->name ?? '—' }}
                                </span>
                            </td>

                            <!-- Payer -->
                            <td class="px-8 py-5">
                                <p class="text-sm font-semibold text-slate-700">
                                    {{ $expense->payer->name ?? '—' }}
                                </p>
                            </td>

                            <!-- Date -->
                            <td class="px-8 py-5">
                                <p class="text-sm text-slate-600">
                                    {{ $expense->date->format('d/m/Y') }}
                                </p>
                            </td>

                            <!-- Amount -->
                            <td class="px-8 py-5 text-right">
                                <p class="text-lg font-black text-slate-900">
                                    {{ number_format($expense->amount, 2, ',', ' ') }} €
                                </p>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-8 py-10 text-center">
                                <div class="max-w-md mx-auto">
                                    <div class="w-14 h-14 rounded-3xl bg-slate-100 mx-auto flex items-center justify-center text-slate-600">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4" />
                                        </svg>
                                    </div>
                                    <p class="mt-4 font-black text-slate-900">
                                        Aucune dépense pour le moment
                                    </p>
                                    <p class="mt-2 text-sm text-slate-500 font-medium">
                                        Ajoute une dépense pour commencer.
                                    </p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @php
        $me = Auth::id();
        $toPay = collect($settlements)->filter(fn($s) => $s['from_id'] == $me)->values();
        $toReceive = collect($settlements)->filter(fn($s) => $s['to_id'] == $me)->values();
        @endphp

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mt-10">

            <!-- À payer -->
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-white overflow-hidden">

                <div class="p-8 border-b border-slate-50">
                    <h3 class="text-2xl font-black tracking-tight">Je dois payer</h3>
                    <p class="text-sm text-slate-500 font-medium mt-1">
                        Ce que tu dois aux autres
                    </p>
                </div>

                <div class="p-8 space-y-4">
                    @forelse($toPay as $s)
                    <div class="p-4 rounded-2xl bg-red-50 border border-red-100">

                        <div class="flex items-center justify-between mb-3">
                            <div class="text-sm font-bold text-slate-900">
                                To: <span class="text-red-700 font-black">{{ $s['to'] }}</span>
                            </div>

                            <div class="text-sm font-black text-red-700">
                                {{ number_format($s['amount'], 2, ',', ' ') }} €
                            </div>
                        </div>

                        <form method="POST" action="{{ route('payments.store', $colocation) }}">
                            @csrf
                            <input type="hidden" name="to_user_id" value="{{ $s['to_id'] }}">
                            <input type="hidden" name="amount" value="{{ $s['amount'] }}">

                            <button
                                class="w-full py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-black rounded-2xl shadow-lg shadow-indigo-200 hover:scale-[1.02] active:scale-[0.98] transition-all duration-200">
                                Marquer payé
                            </button>
                        </form>

                    </div>
                    @empty
                    <div class="p-4 rounded-2xl bg-green-50 border border-green-200 text-green-700 font-bold">
                        Rien à payer
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- À recevoir -->
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-white overflow-hidden">
                <div class="p-8 border-b border-slate-50">
                    <h3 class="text-2xl font-black tracking-tight">Je dois recevoir</h3>
                    <p class="text-sm text-slate-500 font-medium mt-1">Ce qu’on te doit</p>
                </div>

                <div class="p-8 space-y-3">
                    @forelse($toReceive as $s)
                    <div class="flex items-center justify-between p-4 rounded-2xl bg-green-50 border border-green-100">
                        <div class="text-sm font-bold text-slate-900">
                            From: <span class="text-green-700 font-black">{{ $s['from'] }}</span>
                        </div>
                        <div class="text-sm font-black text-green-700">
                            {{ number_format($s['amount'], 2, ',', ' ') }} €
                        </div>
                    </div>
                    @empty
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-slate-700 font-bold">
                        Rien à recevoir
                    </div>
                    @endforelse
                </div>
            </div>

        </div>
    </main>

    <footer class="py-10 text-center text-slate-400 text-xs font-medium">
        © 2026 EasyColoc — L'app préférée des colocations organisées.
    </footer>

</body>

</html>