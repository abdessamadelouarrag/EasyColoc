<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyColoc - Simplifiez vos comptes en colocation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 antialiased">

    <!-- NAVBAR -->
    <nav class="flex items-center justify-between px-6 py-6 max-w-7xl mx-auto">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 bg-gradient-to-tr from-indigo-600 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-300/40">
                <span class="text-white font-bold text-lg">€</span>
            </div>
            <span class="text-2xl font-extrabold tracking-tight">EasyColoc</span>
        </div>

        <div class="flex items-center space-x-5">
            <a href="{{ route('login') }}" class="font-medium text-slate-600 hover:text-indigo-600 transition">
                Connexion
            </a>
            <a href="{{ route('register') }}" class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:scale-105 transition-all duration-200">
                S'inscrire
            </a>
        </div>
    </nav>

    <!-- HERO -->
    <main class="relative max-w-7xl mx-auto px-6 pt-20 pb-32 text-center">

        <!-- Background gradient blur -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[600px] bg-gradient-to-tr from-indigo-500 to-purple-500 opacity-20 blur-3xl -z-10"></div>

        <div class="inline-flex items-center rounded-full px-4 py-1.5 text-sm font-medium text-indigo-600 bg-indigo-100 mb-8">
            Nouveau : Gestion intelligente des dépenses 💸
        </div>
        
        <h1 class="text-5xl md:text-7xl font-extrabold leading-tight mb-8">
            Gérez vos comptes
            <br/>
            <span class="bg-gradient-to-r from-indigo-600 via-purple-600 to-blue-600 bg-clip-text text-transparent">
                sans prise de tête.
            </span>
        </h1>
        
        <p class="text-lg md:text-xl text-slate-600 max-w-2xl mx-auto mb-12 leading-relaxed">
            EasyColoc suit automatiquement les dépenses de votre colocation et équilibre les dettes en quelques secondes.
        </p>

        <div class="flex flex-col sm:flex-row justify-center items-center gap-5">
            <a href="#" class="w-full sm:w-auto px-10 py-4 bg-slate-900 text-white font-semibold rounded-2xl shadow-xl hover:scale-105 transition-all">
                Démarrer gratuitement
            </a>
            <a href="#features" class="w-full sm:w-auto px-10 py-4 bg-white border border-slate-200 text-slate-700 font-semibold rounded-2xl hover:bg-slate-100 transition">
                Voir les fonctionnalités
            </a>
        </div>

        <!-- MOCKUP CARD -->
        <div class="mt-24 max-w-5xl mx-auto">
            <div class="bg-white/70 backdrop-blur-xl p-3 rounded-3xl shadow-2xl border border-slate-200">
                <img src="https://images.unsplash.com/photo-1554224155-169641357599?auto=format&fit=crop&q=80&w=1200"
                     class="rounded-2xl"
                     alt="Dashboard EasyColoc">
            </div>
        </div>

    </main>

    <!-- FEATURES -->
    <section id="features" class="py-28 bg-white">
        <div class="max-w-7xl mx-auto px-6">

            <div class="text-center mb-20">
                <h2 class="text-4xl font-extrabold mb-4">Pourquoi EasyColoc ?</h2>
                <p class="text-slate-600 max-w-2xl mx-auto">
                    Une solution moderne pensée pour simplifier la vie en colocation.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">

                <div class="p-8 rounded-3xl bg-slate-50 hover:shadow-xl transition">
                    <div class="w-14 h-14 bg-gradient-to-tr from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center text-white mb-6">
                        +
                    </div>
                    <h3 class="text-xl font-bold mb-3">Ajout ultra rapide</h3>
                    <p class="text-slate-600">
                        Enregistrez une dépense en quelques secondes seulement.
                    </p>
                </div>

                <div class="p-8 rounded-3xl bg-slate-50 hover:shadow-xl transition">
                    <div class="w-14 h-14 bg-gradient-to-tr from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center text-white mb-6">
                        ∑
                    </div>
                    <h3 class="text-xl font-bold mb-3">Calcul intelligent</h3>
                    <p class="text-slate-600">
                        Notre système équilibre automatiquement les remboursements.
                    </p>
                </div>

                <div class="p-8 rounded-3xl bg-slate-50 hover:shadow-xl transition">
                    <div class="w-14 h-14 bg-gradient-to-tr from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center text-white mb-6">
                        ✔
                    </div>
                    <h3 class="text-xl font-bold mb-3">Transparence totale</h3>
                    <p class="text-slate-600">
                        Tout le monde voit clairement les soldes en temps réel.
                    </p>
                </div>

            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="py-12 text-center text-slate-500 text-sm">
        © 2026 EasyColoc — Conçu pour simplifier la vie en colocation.
    </footer>

</body>
</html>