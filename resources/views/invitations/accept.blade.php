<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitation - EasyColoc</title>

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
            <h1 class="text-4xl font-black tracking-tight text-slate-900">Répondre à l’invitation</h1>
            <p class="mt-3 text-slate-500 font-medium">
                Vérifie l’email et accepte ou refuse.
            </p>
        </div>

        <!-- Error -->
        @if($errors->has('invite'))
            <div class="mb-6 glass rounded-2xl p-4 text-sm font-bold text-red-700">
                {{ $errors->first('invite') }}
            </div>
        @endif

        <!-- Card -->
        <div class="bg-white/80 backdrop-blur-xl p-8 md:p-10 rounded-[2.5rem] shadow-2xl shadow-slate-200/60 border border-white">

            <div class="space-y-3 mb-8">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-bold text-slate-500">Email invité</span>
                    <span class="text-sm font-black text-slate-900">{{ $invitation->email }}</span>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-sm font-bold text-slate-500">Status</span>

                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase
                        {{ $invitation->status === 'pending'
                            ? 'bg-amber-50 text-amber-700'
                            : ($invitation->status === 'accepted'
                                ? 'bg-green-50 text-green-700'
                                : 'bg-slate-100 text-slate-600') }}">
                        {{ $invitation->status }}
                    </span>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                <!-- Accept -->
                <form method="POST" action="{{ route('invitations.accept', $invitation->token) }}" class="flex-1">
                    @csrf
                    <button type="submit"
                        class="w-full py-4 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-black rounded-[1.5rem]
                               shadow-xl shadow-emerald-200 hover:scale-[1.02] active:scale-[0.98] transition-all">
                        Accepter
                    </button>
                </form>

                <!-- Refuse -->
                <form method="POST" action="{{ route('invitations.refuse', $invitation->token) }}" class="flex-1">
                    @csrf
                    <button type="submit"
                        class="w-full py-4 bg-slate-100 text-slate-700 font-black rounded-[1.5rem]
                               hover:bg-slate-200 transition">
                        Refuser
                    </button>
                </form>
            </div>

            <a href="{{ route('dashboard') }}"
               class="mt-8 inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-indigo-600 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Retour au dashboard
            </a>
        </div>

        <p class="mt-8 text-center text-slate-400 text-xs font-medium italic">
            💡 Astuce : tu dois être connecté avec le même email que l’invitation.
        </p>

    </main>

    <footer class="py-10 text-center text-slate-400 text-xs font-medium">
        © 2026 EasyColoc — L'app préférée des colocations organisées.
    </footer>

</body>
</html>