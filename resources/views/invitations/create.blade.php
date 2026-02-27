<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inviter un membre - EasyColoc</title>

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
                    <p class="text-lg font-extrabold tracking-tight leading-none">Invitation</p>
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

    <main class="max-w-2xl mx-auto px-6 py-10">

        <!-- Header -->
        <div class="mb-8">
            <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-2">Invitation</p>
            <h1 class="text-4xl font-black tracking-tight text-slate-900">Inviter un membre</h1>
            <p class="mt-3 text-slate-500 font-medium">
                Colocation : <span class="font-black text-slate-900">{{ $colocation->name }}</span>
            </p>
        </div>

        <!-- Alerts -->
        @if(session('success'))
            <div class="mb-6 glass rounded-2xl p-4 text-sm font-bold text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 glass rounded-2xl p-4 text-sm font-bold text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Card -->
        <div class="bg-white/80 backdrop-blur-xl p-8 md:p-10 rounded-[2.5rem] shadow-2xl shadow-slate-200/60 border border-white">

            <form method="POST" action="{{ route('invitations.store', $colocation) }}" class="space-y-6">
                @csrf

                <!-- Email -->
                <div class="space-y-2">
                    <label class="text-sm font-black text-slate-700 italic">Email du membre</label>

                    <div class="relative">
                        <span class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M16 12H8m8 0l-8 0m12-7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V7a2 2 0 00-2-2z" />
                            </svg>
                        </span>

                        <input
                            name="email"
                            type="email"
                            required
                            value="{{ old('email') }}"
                            placeholder="ex: member@gmail.com"
                            class="w-full pl-14 pr-5 py-5 bg-slate-50 border border-slate-100 rounded-2xl
                                   focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none
                                   transition-all text-base font-semibold"
                        />
                    </div>

                    <p class="text-xs text-slate-500 font-medium">
                        Un lien d’invitation sera envoyé à cet email.
                    </p>
                </div>

                <!-- Actions -->
                <div class="pt-2 flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('colocations.show', $colocation) }}"
                       class="flex-1 py-4 text-center bg-slate-100 text-slate-600 font-bold rounded-[1.5rem] hover:bg-slate-200 transition">
                        Annuler
                    </a>

                    <button type="submit"
                        class="flex-[2] py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-black rounded-[1.5rem]
                               shadow-xl shadow-indigo-200 hover:scale-[1.02] active:scale-[0.98] transition-all">
                        Envoyer l’invitation
                    </button>
                </div>
            </form>
        </div>

        <!-- Tip -->
        <p class="mt-8 text-center text-slate-400 text-xs font-medium italic">
            💡 Astuce : l’utilisateur doit être connecté avec le même email pour accepter l’invitation.
        </p>

    </main>

    <footer class="py-10 text-center text-slate-400 text-xs font-medium">
        © 2026 EasyColoc — L'app préférée des colocations organisées.
    </footer>

</body>
</html>