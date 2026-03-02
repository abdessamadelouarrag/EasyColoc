<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - EasyColoc</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass {
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
                <p class="text-lg font-extrabold tracking-tight leading-none">Profil</p>
            </div>
        </div>

        <div class="flex items-center gap-6">

            <!-- Reputation -->
            <div class="hidden md:flex items-center gap-3 px-5 py-2 rounded-2xl
                {{ Auth::user()->reputation >= 0
                    ? 'bg-green-50 border border-green-200 text-green-700'
                    : 'bg-red-50 border border-red-200 text-red-700' }}">
                <span class="text-xs font-black uppercase tracking-widest">Reputation</span>
                <span class="text-lg font-black">{{ Auth::user()->reputation }}</span>
            </div>

            <!-- User -->
            <div class="flex items-center gap-3 border-l pl-6 border-slate-200">
                <div class="text-right hidden md:block">
                    <p class="text-sm font-bold leading-none">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-slate-500 mt-1">{{ Auth::user()->email }}</p>
                </div>

                <div class="w-10 h-10 rounded-2xl bg-slate-100 flex items-center justify-center font-black text-slate-600">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="p-2.5 bg-slate-100 text-slate-600 rounded-xl hover:bg-red-50 hover:text-red-600 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

<main class="max-w-5xl mx-auto px-6 py-14">

    <!-- HEADER -->
    <div class="mb-12">
        <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-2">Compte</p>
        <h1 class="text-4xl font-black tracking-tight">Paramètres du profil</h1>
        <p class="mt-3 text-slate-500 font-medium max-w-xl">
            Gère tes informations personnelles et la sécurité de ton compte.
        </p>
    </div>

    <!-- SUCCESS -->
    @if (session('status'))
        <div class="mb-6 glass rounded-2xl p-4 text-sm font-bold text-green-700">
            Mise à jour effectuée avec succès ✅
        </div>
    @endif

    <div class="space-y-8">

        <!-- PROFILE INFO -->
        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-white p-10">
            <div class="mb-8">
                <p class="text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Informations</p>
                <h2 class="text-2xl font-black">Détails du compte</h2>
            </div>

            <div class="space-y-6">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <!-- PASSWORD -->
        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-white p-10">
            <div class="mb-8">
                <p class="text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Sécurité</p>
                <h2 class="text-2xl font-black">Mot de passe</h2>
            </div>

            <div class="space-y-6">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <!-- DELETE ACCOUNT -->
        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-red-100/60 border border-red-100 p-10">
            <div class="mb-8">
                <p class="text-xs font-black uppercase tracking-widest text-red-500 mb-2">Danger zone</p>
                <h2 class="text-2xl font-black">Supprimer le compte</h2>
                <p class="text-sm text-slate-500 mt-2">
                    Cette action est irréversible. Toutes vos données seront supprimées.
                </p>
            </div>

            @include('profile.partials.delete-user-form')
        </div>

    </div>

    <footer class="mt-16 text-center text-slate-400 text-xs font-medium">
        © 2026 EasyColoc — L'app préférée des colocations organisées.
    </footer>

</main>

</body>
</html>