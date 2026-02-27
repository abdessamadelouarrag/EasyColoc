<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer une colocation - EasyColoc</title>

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
                    <p class="text-lg font-extrabold tracking-tight leading-none">Nouvelle colocation</p>
                </div>
            </div>

            <div class="flex items-center gap-6">
                <span class="px-3 py-1 bg-indigo-50 text-indigo-600 text-xs font-bold rounded-full uppercase tracking-wider">
                    {{ Auth::user()->is_admin ? 'Global Admin' : 'Membre' }}
                </span>

                <a href="{{ route('colocations.index') }}"
                   class="px-6 py-3 bg-white text-slate-700 font-bold rounded-2xl border border-slate-200 hover:bg-slate-50 transition-all">
                    Mes colocations
                </a>
            </div>

        </div>
    </nav>

    <main class="max-w-xl mx-auto px-6 py-10">

        <!-- HEADER -->
        <div class="mb-10 text-center">
            <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-2">Création</p>
            <h1 class="text-4xl font-black tracking-tight text-slate-900">Créer une colocation</h1>
            <p class="mt-3 text-slate-500 font-medium italic">
                Tu deviens automatiquement <span class="font-black text-slate-900">Owner</span>.
            </p>
        </div>

        <!-- Errors -->
        @if($errors->any())
            <div class="mb-6 glass rounded-2xl p-4 text-sm font-bold text-red-700">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- FORM CARD -->
        <div class="bg-white/80 backdrop-blur-xl p-8 md:p-10 rounded-[2.5rem] shadow-2xl shadow-slate-200/60 border border-white">

            <form method="POST" action="{{ route('colocations.store') }}" class="space-y-6">
                @csrf

                <div class="space-y-2">
                    <label class="text-sm font-black text-slate-700 italic">Nom de la colocation</label>

                    <input
                        name="name"
                        value="{{ old('name') }}"
                        required
                        placeholder="Ex: Coloc Safi 2026"
                        class="w-full px-6 py-5 bg-slate-50 border border-slate-100 rounded-2xl
                               focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none
                               transition-all text-base font-semibold"
                    />

                    <p class="text-xs text-slate-500 font-medium">
                        Astuce : choisis un nom simple (ville, année, quartier…).
                    </p>
                </div>

                <div class="pt-2 flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('dashboard') }}"
                       class="flex-1 py-4 text-center bg-slate-100 text-slate-600 font-bold rounded-[1.5rem] hover:bg-slate-200 transition">
                        Annuler
                    </a>

                    <button type="submit"
                        class="flex-[2] py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-black rounded-[1.5rem]
                               shadow-xl shadow-indigo-200 hover:scale-[1.02] active:scale-[0.98] transition-all">
                        Créer la colocation
                    </button>
                </div>
            </form>

        </div>

        <p class="mt-8 text-center text-slate-400 text-xs font-medium italic">
            💡 Après la création, tu pourras inviter des membres et ajouter des dépenses.
        </p>

    </main>

    <footer class="py-10 text-center text-slate-400 text-xs font-medium">
        © 2026 EasyColoc — L'app préférée des colocations organisées.
    </footer>

</body>
</html>