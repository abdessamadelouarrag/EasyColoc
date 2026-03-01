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
                    <p class="text-lg font-extrabold tracking-tight leading-none">Profil</p>
                </div>
            </div>

            <div class="flex items-center gap-6">
                <span class="px-3 py-1 bg-indigo-50 text-indigo-600 text-xs font-bold rounded-full uppercase tracking-wider">
                    {{ Auth::user()->is_admin ? 'Global Admin' : 'Membre' }}
                </span>

                <div class="flex items-center gap-3 border-l pl-6 border-slate-200">
                    <div class="text-right hidden md:block">
                        <p class="text-sm font-bold leading-none">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-slate-500 mt-1">{{ Auth::user()->email }}</p>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="p-2.5 bg-slate-100 text-slate-600 rounded-xl hover:bg-red-50 hover:text-red-600 transition-all" title="Logout">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto px-6 py-10">

        <!-- HEADER -->
        <div class="mb-10">
            <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-2">Compte</p>
            <h1 class="text-4xl font-black tracking-tight text-slate-900">Mon profil</h1>
            <p class="mt-3 text-slate-500 font-medium max-w-xl">
                Modifie tes informations, ton mot de passe, ou supprime ton compte.
            </p>
        </div>

        <!-- STATUS -->
        @if (session('status') === 'profile-updated')
            <div class="mb-6 glass rounded-2xl p-4 text-sm font-bold text-green-700">
                Profil mis à jour ✅
            </div>
        @endif

        @if (session('status') === 'password-updated')
            <div class="mb-6 glass rounded-2xl p-4 text-sm font-bold text-green-700">
                Mot de passe mis à jour ✅
            </div>
        @endif

        <!-- CARDS -->
        <div class="space-y-6">

            <!-- Update profile info -->
            <div class="bg-white/80 backdrop-blur-xl p-8 md:p-10 rounded-[2.5rem] shadow-2xl shadow-slate-200/60 border border-white">
                <div class="mb-6">
                    <p class="text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Informations</p>
                    <h2 class="text-2xl font-black tracking-tight">Infos du compte</h2>
                    <p class="mt-2 text-slate-500 font-medium text-sm">
                        Nom, email et vérification.
                    </p>
                </div>

                @include('profile.partials.update-profile-information-form')
            </div>

            <!-- Update password -->
            <div class="bg-white/80 backdrop-blur-xl p-8 md:p-10 rounded-[2.5rem] shadow-2xl shadow-slate-200/60 border border-white">
                <div class="mb-6">
                    <p class="text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Sécurité</p>
                    <h2 class="text-2xl font-black tracking-tight">Mot de passe</h2>
                    <p class="mt-2 text-slate-500 font-medium text-sm">
                        Mets à jour ton mot de passe.
                    </p>
                </div>

                @include('profile.partials.update-password-form')
            </div>

            <!-- Delete user -->
            <div class="bg-white/80 backdrop-blur-xl p-8 md:p-10 rounded-[2.5rem] shadow-2xl shadow-slate-200/60 border border-white">
                <div class="mb-6">
                    <p class="text-xs font-black uppercase tracking-widest text-red-500 mb-2">Danger zone</p>
                    <h2 class="text-2xl font-black tracking-tight">Supprimer le compte</h2>
                    <p class="mt-2 text-slate-500 font-medium text-sm">
                        Cette action est irréversible.
                    </p>
                </div>

                @include('profile.partials.delete-user-form')
            </div>

        </div>

        <footer class="py-10 text-center text-slate-400 text-xs font-medium">
            © 2026 EasyColoc — L'app préférée des colocations organisées.
        </footer>

    </main>

</body>
</html>