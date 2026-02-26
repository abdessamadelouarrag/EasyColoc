<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer une colocation - EasyColoc</title>
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
                        <p class="text-xs text-slate-500 mt-1">Score: <span class="text-green-600 font-bold">+12</span></p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="p-2.5 bg-slate-100 text-slate-600 rounded-xl hover:bg-red-50 hover:text-red-600 transition-all">
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

        <!-- HEADER -->
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-6">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-11 h-11 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100">
                        <!-- Icon: Home/Group -->
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10.5 12 3l9 7.5V21a1.5 1.5 0 0 1-1.5 1.5H4.5A1.5 1.5 0 0 1 3 21v-10.5z" />
                        </svg>
                    </div>
                    <p class="text-sm font-bold text-slate-500 uppercase tracking-widest">Nouvelle colocation</p>
                </div>
                <h1 class="text-4xl font-black tracking-tight text-slate-900">Créer une colocation</h1>
                <p class="mt-3 text-slate-500 font-medium max-w-xl">
                    Donne un nom à ta colocation. Ensuite tu pourras inviter des membres et commencer à ajouter des dépenses.
                </p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('colocations.index') }}"
                    class="px-6 py-3 bg-white text-slate-700 font-bold rounded-2xl border border-slate-200 hover:bg-slate-50 transition-all inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Mes colocations
                </a>
            </div>
        </div>

        <!-- CONTENT -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- FORM -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-white overflow-hidden">
                    <div class="p-8 border-b border-slate-50">
                        <h2 class="text-2xl font-black tracking-tight">Informations</h2>
                        <p class="mt-2 text-sm text-slate-500 font-medium">
                            Un nom clair aide tous les membres à reconnaître la colocation.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('colocations.store') }}" class="p-8 space-y-6">
                        @csrf

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Nom de la colocation</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 7.5V20a1.5 1.5 0 0 0 1.5 1.5h13A1.5 1.5 0 0 0 20 20V7.5M9 21V12h6v9M3 7.5 12 3l9 4.5" />
                                    </svg>
                                </span>
                                <input
                                    type="text"
                                    name="name"
                                    value="{{ old('name') }}"
                                    placeholder="Ex: Coloc Safi Centre"
                                    class="w-full pl-12 pr-4 py-4 rounded-2xl bg-slate-50 border border-slate-200 outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-300 font-semibold"
                                    required />
                            </div>

                            @error('name')
                            <p class="mt-2 text-sm font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="glass rounded-2xl p-5">
                            <!-- ... نفس المحتوى ... -->
                        </div>

                        <div class="flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-end pt-2">
                            <a href="{{ route('colocations.index') }}"
                                class="px-6 py-3 bg-slate-100 text-slate-700 font-bold rounded-2xl hover:bg-slate-200 transition-all text-center">
                                Annuler
                            </a>

                            <button type="submit"
                                class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold rounded-2xl shadow-xl shadow-indigo-200 hover:scale-[1.02] transition-all inline-flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Créer la colocation
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- SIDE CARD -->
            <div class="space-y-8">
                <div class="bg-slate-900 rounded-[2.5rem] p-8 text-white shadow-xl shadow-slate-900/20 relative overflow-hidden">
                    <div class="absolute right-0 bottom-0 opacity-10">
                        <svg class="w-64 h-64 -mb-24 -mr-24" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                        </svg>
                    </div>

                    <div class="relative z-10">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-white/10 border border-white/10 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m7 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-black">Conseils rapides</h3>
                        </div>

                        <div class="mt-5 space-y-4 text-sm text-slate-300 font-semibold">
                            <div class="flex gap-3">
                                <svg class="w-5 h-5 text-indigo-300 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01" />
                                </svg>
                                <p>Utilise un nom qui inclut le lieu ou le groupe (ex: “Coloc YouCode Safi”).</p>
                            </div>
                            <div class="flex gap-3">
                                <svg class="w-5 h-5 text-indigo-300 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H2v-2a4 4 0 015-3.87m10 0a4 4 0 00-8 0m8 0V6a3 3 0 00-3-3H10a3 3 0 00-3 3v8.13" />
                                </svg>
                                <p>Après création, invite tes membres via email ou lien/token.</p>
                            </div>
                            <div class="flex gap-3">
                                <svg class="w-5 h-5 text-indigo-300 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-12v2m0 16v2" />
                                </svg>
                                <p>Crée d’abord la colocation, ensuite seulement commence les dépenses.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-white p-8">
                    <h3 class="text-xl font-black flex items-center gap-2">
                        <span class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 11h10M7 15h6M5 21h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                        </span>
                        Règles par défaut
                    </h3>

                    <div class="mt-5 space-y-3 text-sm text-slate-600 font-semibold">
                        <p class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                            Statut : <span class="text-slate-900 font-black">Active</span>
                        </p>
                        <p class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                            Owner : <span class="text-slate-900 font-black">{{ Auth::user()->name }}</span>
                        </p>
                        <p class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                            Dépenses : ajoutables après invitation
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <footer class="py-10 text-center text-slate-400 text-xs font-medium">
        © 2026 EasyColoc — L'app préférée des colocations organisées.
    </footer>

</body>

</html>