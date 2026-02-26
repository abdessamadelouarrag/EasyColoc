<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - EasyColoc</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass{
            background: rgba(255,255,255,.7);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border: 1px solid rgba(255,255,255,.75);
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 antialiased">

    <!-- NAV -->
    <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-lg border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-tr from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-200">
                    <span class="text-white font-bold text-sm">€</span>
                </div>
                <span class="text-xl font-extrabold tracking-tight hidden sm:block">EasyColoc</span>
            </div>

            <div class="flex items-center gap-6">
                <span class="px-3 py-1 bg-indigo-50 text-indigo-600 text-xs font-bold rounded-full uppercase tracking-wider">
                    {{ Auth::user()->is_admin ? 'Global Admin' : (Auth::user()->is_owner ? 'Owner' : 'Member') }}
                </span>                

                <div class="flex items-center gap-3 border-l pl-6 border-slate-200">
                    <div class="text-right hidden md:block">
                        <p class="text-sm font-bold leading-none">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-slate-500 mt-1">Score: <span class="text-green-600 font-bold">{{ $stats['reputation'] ?? '+12' }}</span></p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="p-2.5 bg-slate-100 text-slate-600 rounded-xl hover:bg-red-50 hover:text-red-600 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-6 py-10">

        <!-- HEADER -->
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-6">
            <div>
                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-2">Vue globale</p>
                <h1 class="text-4xl font-black tracking-tight text-slate-900">Dashboard</h1>
                <p class="mt-3 text-slate-500 font-medium max-w-xl">
                    Gère tes colocations, suis les dépenses, et garde une vue claire sur ton activité.
                </p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('colocations.create') }}"
                   class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold rounded-2xl shadow-xl shadow-indigo-200 hover:scale-[1.02] transition-all inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Créer une colocation
                </a>

                <a href=""
                   class="px-6 py-3 bg-white text-slate-700 font-bold rounded-2xl border border-slate-200 hover:bg-slate-50 transition-all inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    Mes colocations
                </a>
            </div>
        </div>

        <!-- STATS -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-10">

            <!-- Colocations -->
            <div class="bg-white p-7 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-white relative overflow-hidden">
                <div class="absolute top-0 right-0 p-6 opacity-10">
                    <svg class="w-20 h-20" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l9 7v13a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9l9-7z"/>
                    </svg>
                </div>
                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-2">Colocations</p>
                <h2 class="text-4xl font-black text-slate-900">{{ $stats['colocations_count'] ?? 0 }}</h2>
                <p class="mt-3 text-slate-500 text-sm font-medium">Total de tes colocations</p>
            </div>

            <!-- Membres -->
            <div class="bg-white p-7 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-white relative overflow-hidden">
                <div class="absolute top-0 right-0 p-6 opacity-10">
                    <svg class="w-20 h-20" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M16 11c1.66 0 3-1.34 3-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zM8 11c1.66 0 3-1.34 3-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zM8 13c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4zm8 0c-.34 0-.7.02-1.07.05 1.16.84 2.07 1.97 2.07 3.45v2h7v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                </div>
                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-2">Membres</p>
                <h2 class="text-4xl font-black text-slate-900">{{ $stats['members_count'] ?? 0 }}</h2>
                <p class="mt-3 text-slate-500 text-sm font-medium">Dans toutes tes colocations</p>
            </div>

            <!-- Dépenses du mois -->
            <div class="bg-slate-900 p-7 rounded-[2.5rem] shadow-xl shadow-slate-900/20 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 p-6 opacity-10">
                    <svg class="w-20 h-20" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 1a11 11 0 1 0 11 11A11 11 0 0 0 12 1zm1 17h-2v-2h2zm1.07-7.75-.9.92A1.5 1.5 0 0 0 13 13v1h-2v-.5a3 3 0 0 1 .88-2.12l1.24-1.26a1.5 1.5 0 1 0-2.62-1H8.5a3.5 3.5 0 1 1 6.57 1.13z"/>
                    </svg>
                </div>
                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-2">Dépenses du mois</p>
                <h2 class="text-4xl font-black">
                    {{ number_format($stats['month_total'] ?? 0, 2, ',', ' ') }} €
                </h2>
                <p class="mt-3 text-slate-300 text-sm font-medium">Somme totale (mois en cours)</p>
            </div>

            <!-- Invitations -->
            <div class="bg-white p-7 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-white relative overflow-hidden">
                <div class="absolute top-0 right-0 p-6 opacity-10">
                    <svg class="w-20 h-20" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2zm0 4-8 5L4 8V6l8 5 8-5z"/>
                    </svg>
                </div>
                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-2">Invitations</p>
                <h2 class="text-4xl font-black text-slate-900">{{ $stats['pending_invitations'] ?? 0 }}</h2>
                <p class="mt-3 text-slate-500 text-sm font-medium">En attente</p>
            </div>
        </div>

        <!-- LIST -->
        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-white overflow-hidden">
            <div class="p-8 border-b border-slate-50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <h3 class="text-2xl font-black tracking-tight">Mes colocations</h3>

                <div class="flex gap-2">
                    <a href=""
                       class="px-5 py-2.5 bg-slate-100 text-slate-700 font-bold rounded-2xl hover:bg-slate-200 transition-all inline-flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Nouvelle
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                            <th class="px-8 py-4">Nom</th>
                            <th class="px-8 py-4">Membres</th>
                            <th class="px-8 py-4">Statut</th>
                            <th class="px-8 py-4 text-right">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-50">
                        @forelse(($colocations ?? []) as $coloc)
                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                <td class="px-8 py-5">
                                    <p class="text-sm font-bold text-slate-900 group-hover:text-indigo-600">
                                        {{ $coloc->name }}
                                    </p>
                                    <p class="text-xs text-slate-500 font-medium mt-1">Owner: {{ $coloc->owner->name ?? '—' }}</p>
                                </td>

                                <td class="px-8 py-5">
                                    <span class="text-sm font-bold text-slate-700">
                                        {{ $coloc->users_count ?? ($coloc->users->count() ?? 0) }}
                                    </span>
                                </td>

                                <td class="px-8 py-5">
                                    <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase
                                        {{ ($coloc->status ?? 'active') === 'active' ? 'bg-green-50 text-green-600' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $coloc->status ?? 'active' }}
                                    </span>
                                </td>

                                <td class="px-8 py-5 text-right">
                                    <a href="{{ route('colocations.show', $coloc->id) }}"
                                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white font-bold rounded-2xl hover:bg-indigo-700 transition-all">
                                        Ouvrir
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-8 py-10 text-center">
                                    <div class="max-w-md mx-auto">
                                        <div class="w-14 h-14 rounded-3xl bg-slate-100 mx-auto flex items-center justify-center text-slate-600">
                                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                        </div>
                                        <p class="mt-4 font-black text-slate-900">Aucune colocation pour le moment</p>
                                        <p class="mt-2 text-sm text-slate-500 font-medium">
                                            Crée ta première colocation pour commencer.
                                        </p>
                                        <a href=""
                                           class="mt-5 inline-flex items-center gap-2 px-7 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold rounded-2xl shadow-xl shadow-indigo-200 hover:scale-[1.02] transition-all">
                                            Créer une colocation
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>

    </main>

    <footer class="py-10 text-center text-slate-400 text-xs font-medium">
        © 2026 EasyColoc — L'app préférée des colocations organisées.
    </footer>

</body>
</html>