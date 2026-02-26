<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une dépense - EasyColoc</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 antialiased min-h-screen">

    <nav class="max-w-7xl mx-auto px-6 py-6 flex items-center justify-between">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 group text-slate-500 hover:text-indigo-600 transition">
            <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            <span class="font-bold text-sm">Retour au dashboard</span>
        </a>
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-gradient-to-tr from-indigo-600 to-purple-600 rounded-lg flex items-center justify-center shadow-md">
                <span class="text-white font-bold text-xs">€</span>
            </div>
            <span class="text-sm font-black tracking-tight">EasyColoc</span>
        </div>
    </nav>

    <main class="max-w-2xl mx-auto px-6 py-10">
        
        <div class="mb-10 text-center">
            <h1 class="text-4xl font-black tracking-tight mb-3">Nouvelle dépense</h1>
            <p class="text-slate-500 font-medium italic">Ajoutez un achat pour mettre à jour les comptes de la coloc.</p>
        </div>

        <div class="bg-white/80 backdrop-blur-xl p-8 md:p-12 rounded-[2.5rem] shadow-2xl shadow-slate-200/60 border border-white">
            <form action="{{ route('expenses.store', $colocation->id) }}" method="POST" class="space-y-8">
                @csrf

                <div class="space-y-3">
                    <label for="title" class="flex items-center gap-2 text-sm font-bold text-slate-700 ml-1 italic">
                        <span class="w-2 h-2 bg-indigo-500 rounded-full"></span>
                        C'était pour quoi ?
                    </label>
                    <input type="text" name="title" id="title" required 
                           placeholder="Ex: Courses Carrefour, Pizza night..."
                           class="w-full px-6 py-5 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all text-lg font-medium">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-3">
                        <label for="amount" class="text-sm font-bold text-slate-700 ml-1 italic">Combien ?</label>
                        <div class="relative">
                            <input type="number" step="0.01" name="amount" id="amount" required placeholder="0.00"
                                   class="w-full px-6 py-5 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all pl-12 text-lg font-bold">
                            <span class="absolute left-6 top-1/2 -translate-y-1/2 font-black text-slate-400 text-lg">€</span>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label for="category" class="text-sm font-bold text-slate-700 ml-1 italic">Catégorie</label>
                        <select name="category_id" id="category" 
                                class="w-full px-6 py-5 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all cursor-pointer font-bold text-slate-700 appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%236366f1%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpath%20d%3D%22M6%209l6%206%206-6%22%3E%3C%2Fpath%3E%3C%2Fsvg%3E')] bg-[length:20px] bg-[right_1.5rem_center] bg-no-repeat">
                            <option value="1">Alimentation</option>
                            <option value="2">Loyer/Charges</option>
                            <option value="3">Loisirs / Sorties</option>
                            <option value="4">Entretien / Travaux</option>
                            <option value="5">Autres</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-3">
                        <label for="date" class="text-sm font-bold text-slate-700 ml-1 italic">Quand ?</label>
                        <input type="date" name="date" id="date" value="{{ date('Y-m-d') }}"
                               class="w-full px-6 py-5 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all font-semibold">
                    </div>

                    <div class="space-y-3">
                        <label for="paid_by" class="text-sm font-bold text-slate-700 ml-1 italic">Qui a avancé l'argent ?</label>
                        <select name="paid_by" id="paid_by" 
                                class="w-full px-6 py-5 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all cursor-pointer font-bold text-slate-700 appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%236366f1%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpath%20d%3D%22M6%209l6%206%206-6%22%3E%3C%2Fpath%3E%3C%2Fsvg%3E')] bg-[length:20px] bg-[right_1.5rem_center] bg-no-repeat">
                        </select>
                    </div>
                </div>

                <div class="pt-6 flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('dashboard') }}" 
                       class="flex-1 py-5 text-center bg-slate-100 text-slate-600 font-bold rounded-[1.5rem] hover:bg-slate-200 transition">
                        Annuler
                    </a>
                    <button type="submit" 
                            class="flex-[2] py-5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-black text-lg rounded-[1.5rem] shadow-xl shadow-indigo-200 hover:scale-[1.02] active:scale-[0.98] transition-all duration-200">
                        Enregistrer la dépense
                    </button>
                </div>
            </form>

        </div>

        <p class="mt-10 text-center text-slate-400 text-xs font-medium italic">
            💡 Astuce : La dépense sera divisée équitablement entre tous les membres actifs.
        </p>

    </main>

</body>
</html>