<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - EasyColoc</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 antialiased">

    <nav class="flex items-center justify-between px-6 py-6 max-w-7xl mx-auto">
        <div class="flex items-center gap-3">
            <a href="/" class="flex items-center gap-3">
                <div class="w-11 h-11 bg-gradient-to-tr from-indigo-600 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-300/40">
                    <span class="text-white font-bold text-lg">€</span>
                </div>
                <span class="text-2xl font-extrabold tracking-tight">EasyColoc</span>
            </a>
        </div>

        <div class="flex items-center space-x-5">
            <span class="text-slate-500 hidden sm:inline text-sm italic">Pas encore de compte ?</span>
            <a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:text-purple-600 transition">
                Créer un compte
            </a>
        </div>
    </nav>

    <main class="relative min-h-[calc(100vh-100px)] flex flex-col items-center justify-center px-6 pb-20">
        
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-[500px] h-[500px] bg-gradient-to-tr from-purple-500 to-blue-500 opacity-10 blur-3xl -z-10"></div>

        <div class="w-full max-w-md">
            <div class="text-center mb-10">
                <h1 class="text-4xl font-extrabold tracking-tight mb-3">Ravi de vous revoir</h1>
                <p class="text-slate-600">Connectez-vous pour gérer vos dépenses.</p>
            </div>

            <div class="bg-white/80 backdrop-blur-xl p-8 sm:p-10 rounded-[2.5rem] shadow-2xl shadow-slate-200/60 border border-white">
                
                @if (session('status'))
                    <div class="mb-4 font-medium text-sm text-green-600 bg-green-50 p-3 rounded-xl">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-bold text-slate-700 ml-1 mb-2">Adresse email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                            placeholder="jean@exemple.com"
                            class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all placeholder:text-slate-400">
                        @if($errors->has('email'))
                            <p class="mt-2 text-sm text-red-500 font-medium ml-1">{{ $errors->first('email') }}</p>
                        @endif
                    </div>

                    <div>
                        <div class="flex justify-between items-center ml-1 mb-2">
                            <label for="password" class="text-sm font-bold text-slate-700">Mot de passe</label>
                            @if (Route::has('password.request'))
                                <a class="text-xs font-semibold text-indigo-600 hover:text-purple-600" href="{{ route('password.request') }}">
                                    Oublié ?
                                </a>
                            @endif
                        </div>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                            placeholder="••••••••"
                            class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all placeholder:text-slate-400">
                        @if($errors->has('password'))
                            <p class="mt-2 text-sm text-red-500 font-medium ml-1">{{ $errors->first('password') }}</p>
                        @endif
                    </div>

                    <div class="flex items-center ml-1">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer">
                            <input id="remember_me" type="checkbox" class="rounded-lg border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500 focus:ring-offset-0 transition" name="remember">
                            <span class="ms-2 text-sm text-slate-600 font-medium">{{ __('Se souvenir de moi') }}</span>
                        </label>
                    </div>

                    <button type="submit" class="w-full py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold rounded-2xl shadow-xl shadow-indigo-200 hover:scale-[1.02] active:scale-[0.98] transition-all duration-200">
                        Se connecter
                    </button>
                </form>

                <div class="mt-8 pt-6 border-t border-slate-100 text-center">
                    <p class="text-sm text-slate-500">
                        Nouveau sur EasyColoc ? 
                        <a href="{{ route('register') }}" class="font-bold text-indigo-600 hover:underline underline-offset-4 transition">
                            Créez votre compte gratuit
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </main>

    <footer class="py-8 text-center text-slate-400 text-xs">
        © 2026 EasyColoc — L'app préférée des colocations organisées.
    </footer>

</body>
</html>