<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - EasyColoc</title>

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
                <span class="text-white font-bold text-sm">A</span>
            </div>
            <span class="text-xl font-extrabold tracking-tight">Admin Panel</span>
        </div>

        <div class="flex items-center gap-6">

            <span class="px-3 py-1 bg-indigo-50 text-indigo-600 text-xs font-bold rounded-full uppercase tracking-wider">
                Global Admin
            </span>

            <div class="flex items-center gap-3 border-l pl-6 border-slate-200">
                <div class="text-right hidden md:block">
                    <p class="text-sm font-bold leading-none">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-slate-500 mt-1">Administrator</p>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="p-2.5 bg-slate-100 text-slate-600 rounded-xl hover:bg-red-50 hover:text-red-600 transition-all">
                        Logout
                    </button>
                </form>
            </div>

        </div>
    </div>
</nav>

<main class="max-w-7xl mx-auto px-6 py-10">

    <!-- HEADER -->
    <div class="mb-10">
        <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-2">
            Vue globale plateforme
        </p>
        <h1 class="text-4xl font-black tracking-tight text-slate-900">
            Dashboard Administrateur
        </h1>
        <p class="mt-3 text-slate-500 font-medium max-w-xl">
            Supervise l’activité globale, contrôle les comptes utilisateurs et analyse les données.
        </p>
    </div>

    <!-- STATS -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-10">

        <div class="bg-white p-7 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-white">
            <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-2">Utilisateurs</p>
            <h2 class="text-4xl font-black text-slate-900">{{ $stats['users'] }}</h2>
        </div>

        <div class="bg-white p-7 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-white">
            <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-2">Colocations</p>
            <h2 class="text-4xl font-black text-slate-900">{{ $stats['colocations'] }}</h2>
        </div>

        <div class="bg-slate-900 p-7 rounded-[2.5rem] shadow-xl shadow-slate-900/20 text-white">
            <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-2">Dépenses totales</p>
            <h2 class="text-4xl font-black">
                {{ number_format($stats['expenses_total'], 2, ',', ' ') }} €
            </h2>
        </div>

        <div class="bg-white p-7 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-white">
            <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-2">Bannis</p>
            <h2 class="text-4xl font-black text-red-600">{{ $stats['banned'] }}</h2>
        </div>

    </div>

    <!-- USERS LIST -->
    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-white overflow-hidden">

        <div class="p-8 border-b border-slate-50">
            <h3 class="text-2xl font-black tracking-tight">
                Gestion des utilisateurs
            </h3>
        </div>

        <div class="divide-y divide-slate-50">

            @foreach($users as $u)
                <div class="flex items-center justify-between px-8 py-6 hover:bg-slate-50 transition">

                    <div>
                        <p class="font-bold text-slate-900">{{ $u->name }}</p>
                        <p class="text-sm text-slate-500">{{ $u->email }}</p>

                        <div class="mt-2 flex gap-2 text-xs font-bold uppercase">

                            <span class="px-3 py-1 rounded-full
                                {{ $u->role === 'admin'
                                    ? 'bg-indigo-50 text-indigo-600'
                                    : 'bg-slate-100 text-slate-600' }}">
                                {{ $u->role }}
                            </span>

                            <span class="px-3 py-1 rounded-full
                                {{ $u->is_banned
                                    ? 'bg-red-50 text-red-600'
                                    : 'bg-green-50 text-green-600' }}">
                                {{ $u->is_banned ? 'Banni' : 'Actif' }}
                            </span>

                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.toggleBan', $u) }}">
                        @csrf
                        <button class="px-6 py-2.5 rounded-2xl font-bold text-white transition
                            {{ $u->is_banned
                                ? 'bg-green-600 hover:bg-green-700'
                                : 'bg-red-600 hover:bg-red-700' }}">
                            {{ $u->is_banned ? 'Débannir' : 'Bannir' }}
                        </button>
                    </form>

                </div>
            @endforeach

        </div>

        <div class="p-6">
            {{ $users->links() }}
        </div>

    </div>

</main>

<footer class="py-10 text-center text-slate-400 text-xs font-medium">
    © 2026 EasyColoc — Admin System
</footer>

</body>
</html>