<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes colocations - EasyColoc</title>

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

<body class="bg-slate-50 text-slate-900 antialiased min-h-screen">

    <!-- NAV -->
    <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-lg border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">

            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard') }}"
                   class="w-10 h-10 bg-gradient-to-tr from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-200">
                    <span class="text-white font-bold text-sm">€</span>
                </a>

                <div class="hidden sm:block">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">EasyColoc</p>
                    <p class="text-lg font-extrabold tracking-tight leading-none">Mes colocations</p>
                </div>
            </div>

            <div class="flex items-center gap-6">

                <span class="px-3 py-1 bg-indigo-50 text-indigo-600 text-xs font-bold rounded-full uppercase tracking-wider">
                    {{ Auth::user()->is_admin ? 'Global Admin' : 'Membre' }}
                </span>

                <a href="{{ route('colocations.create') }}"
                   class="px-7 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-black rounded-2xl shadow-xl shadow-indigo-200 hover:scale-[1.02] transition-all">
                    Créer
                </a>

            </div>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto px-6 py-10">

        <!-- HEADER -->
        <div class="mb-10">
            <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-2">Liste</p>
            <h1 class="text-4xl font-black tracking-tight text-slate-900">Mes colocations</h1>
            <p class="mt-3 text-slate-500 font-medium max-w-xl">
                Accède à tes colocations actives et quitte celles où tu n’es plus impliqué.
            </p>
        </div>

        <!-- Alerts -->
        @if (session('ok'))
            <div class="mb-6 glass rounded-2xl p-4 text-sm font-bold text-green-700">
                {{ session('ok') }}
            </div>
        @endif

        @if ($errors->has('leave'))
            <div class="mb-6 glass rounded-2xl p-4 text-sm font-bold text-red-700">
                {{ $errors->first('leave') }}
            </div>
        @endif

        <!-- LIST -->
        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-white overflow-hidden">
            <div class="p-8 border-b border-slate-50">
                <h3 class="text-2xl font-black tracking-tight">Colocations</h3>
                <p class="text-sm text-slate-500 font-medium mt-1">Clique sur une colocation pour l’ouvrir.</p>
            </div>

            <div class="divide-y divide-slate-50">
                @forelse($colocations as $c)
                    <div class="px-8 py-6 flex items-center justify-between hover:bg-slate-50/70 transition">

                        <a class="block" href="{{ route('colocations.show', $c) }}">
                            <p class="text-lg font-black text-slate-900 hover:text-indigo-600 transition">
                                {{ $c->name }}
                            </p>

                            <p class="text-sm text-slate-500 font-medium mt-1">
                                Status:
                                <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase
                                    {{ $c->status === 'active' ? 'bg-green-50 text-green-600' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $c->status }}
                                </span>
                            </p>
                        </a>

                        <form method="POST" action="{{ route('colocations.leave', $c) }}"
                              onsubmit="return confirm('Quitter cette colocation ?');">
                            @csrf
                            <button class="px-6 py-3 bg-red-600 text-white font-black rounded-2xl hover:bg-red-700 transition">
                                Quitter
                            </button>
                        </form>

                    </div>
                @empty
                    <div class="px-8 py-12 text-center">
                        <div class="max-w-md mx-auto">
                            <div class="w-14 h-14 rounded-3xl bg-slate-100 mx-auto flex items-center justify-center text-slate-600">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                            </div>
                            <p class="mt-4 font-black text-slate-900">Aucune colocation</p>
                            <p class="mt-2 text-sm text-slate-500 font-medium">Crée ta première colocation pour commencer.</p>

                            <a href="{{ route('colocations.create') }}"
                               class="mt-5 inline-flex items-center gap-2 px-7 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-black rounded-2xl shadow-xl shadow-indigo-200 hover:scale-[1.02] transition-all">
                                Créer une colocation
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

    </main>

    <footer class="py-10 text-center text-slate-400 text-xs font-medium">
        © 2026 EasyColoc — L'app préférée des colocations organisées.
    </footer>

</body>
</html>