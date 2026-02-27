<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ajouter une dépense - EasyColoc</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style> body{font-family:'Plus Jakarta Sans',sans-serif;} </style>
</head>

<body class="bg-slate-50 text-slate-900 antialiased min-h-screen">

<nav class="max-w-7xl mx-auto px-6 py-6 flex items-center justify-between">
  <a href="{{ route('colocations.show', $colocation) }}" class="flex items-center gap-2 group text-slate-500 hover:text-indigo-600 transition">
    <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
    </svg>
    <span class="font-bold text-sm">Retour</span>
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

  {{-- ERRORS / OK --}}
  @if ($errors->any())
    <div class="mb-6 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-700 font-bold">
      <ul class="list-disc pl-5 space-y-1">
        @foreach ($errors->all() as $err) <li>{{ $err }}</li> @endforeach
      </ul>
    </div>
  @endif

  @if (session('ok'))
    <div class="mb-6 p-4 rounded-2xl bg-green-50 border border-green-200 text-green-700 font-bold">
      {{ session('ok') }}
    </div>
  @endif

  {{-- CATEGORY FORM (OWNER ONLY) --}}
  @if(Auth::user()->isOwnerOfColocation($colocation))
    <div class="mb-6 bg-white/80 backdrop-blur-xl p-6 rounded-[2rem] shadow-xl shadow-slate-200/60 border border-white">
      <p class="text-xs font-black uppercase tracking-widest text-slate-400 mb-3">Ajouter une catégorie</p>

      <form id="categoryForm" method="POST" action="{{ route('categories.store', $colocation) }}" class="flex gap-3">
        @csrf
        <input type="text" name="name" value="{{ old('name') }}"
               placeholder="Ex: Internet, Transport..."
               class="flex-1 px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl
                      focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none font-semibold">

        {{-- draft inputs to keep expense data --}}
        <input type="hidden" name="_draft_title" id="draft_title">
        <input type="hidden" name="_draft_amount" id="draft_amount">
        <input type="hidden" name="_draft_date" id="draft_date">
        <input type="hidden" name="_draft_payer_id" id="draft_payer_id">

        <button type="submit"
                class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-black rounded-2xl shadow-lg shadow-indigo-200 hover:scale-[1.02] transition">
          Ajouter
        </button>
      </form>

      @error('category') <p class="mt-3 text-sm font-bold text-red-600">{{ $message }}</p> @enderror
      @error('name') <p class="mt-3 text-sm font-bold text-red-600">{{ $message }}</p> @enderror
    </div>
  @endif

  {{-- EXPENSE FORM --}}
  @php $selectedCategory = old('category_id', session('category_selected')); @endphp

  <div class="bg-white/80 backdrop-blur-xl p-8 md:p-12 rounded-[2.5rem] shadow-2xl shadow-slate-200/60 border border-white">
    <form action="{{ route('expenses.store', $colocation) }}" method="POST" class="space-y-8">
      @csrf

      <div class="space-y-3">
        <label for="title" class="flex items-center gap-2 text-sm font-bold text-slate-700 ml-1 italic">
          <span class="w-2 h-2 bg-indigo-500 rounded-full"></span> C'était pour quoi ?
        </label>
        <input type="text" name="title" id="title" required value="{{ old('title') }}"
               placeholder="Ex: Courses Carrefour, Pizza night..."
               class="w-full px-6 py-5 bg-slate-50 border border-slate-100 rounded-2xl
                      focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all text-lg font-medium">
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="space-y-3">
          <label for="amount" class="text-sm font-bold text-slate-700 ml-1 italic">Combien ?</label>
          <div class="relative">
            <input type="number" step="0.01" name="amount" id="amount" required value="{{ old('amount') }}"
                   placeholder="0.00"
                   class="w-full px-6 py-5 bg-slate-50 border border-slate-100 rounded-2xl
                          focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all pl-12 text-lg font-bold">
            <span class="absolute left-6 top-1/2 -translate-y-1/2 font-black text-slate-400 text-lg">€</span>
          </div>
        </div>

        <div class="space-y-3">
          <label class="text-sm font-bold text-slate-700 ml-1 italic">Catégorie</label>

          @if($categories->count() === 0)
            <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-700 font-bold">
              Aucune catégorie. Owner doit en ajouter d’abord.
            </div>
          @endif

          <select name="category_id" required
                  class="w-full px-6 py-5 bg-slate-50 border border-slate-100 rounded-2xl font-bold">
            @foreach($categories as $category)
              <option value="{{ $category->id }}" @selected((string)$selectedCategory === (string)$category->id)>
                {{ $category->name }}
              </option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="space-y-3">
          <label for="date" class="text-sm font-bold text-slate-700 ml-1 italic">Quand ?</label>
          <input type="date" name="date" id="date" value="{{ old('date', date('Y-m-d')) }}"
                 class="w-full px-6 py-5 bg-slate-50 border border-slate-100 rounded-2xl
                        focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all font-semibold">
        </div>

        <div class="space-y-3">
          <label for="payer_id" class="text-sm font-bold text-slate-700 ml-1 italic">Qui a avancé l'argent ?</label>
          <select name="payer_id" id="payer_id" required
                  class="w-full px-6 py-5 bg-slate-50 border border-slate-100 rounded-2xl font-bold">
            @foreach($members as $member)
              <option value="{{ $member->id }}" @selected((string)old('payer_id') === (string)$member->id)>
                {{ $member->name }}
              </option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="pt-6 flex flex-col sm:flex-row gap-4">
        <a href="{{ route('colocations.show', $colocation) }}"
           class="flex-1 py-5 text-center bg-slate-100 text-slate-600 font-bold rounded-[1.5rem] hover:bg-slate-200 transition">
          Annuler
        </a>

        <button type="submit"
                class="flex-[2] py-5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-black text-lg rounded-[1.5rem]
                       shadow-xl shadow-indigo-200 hover:scale-[1.02] active:scale-[0.98] transition-all duration-200">
          Enregistrer la dépense
        </button>
      </div>
    </form>
  </div>

  <p class="mt-10 text-center text-slate-400 text-xs font-medium italic">
    💡 Astuce : La dépense sera divisée équitablement entre tous les membres actifs.
  </p>

</main>

<script>
document.getElementById('categoryForm')?.addEventListener('submit', function () {
  document.getElementById('draft_title').value = document.getElementById('title')?.value || '';
  document.getElementById('draft_amount').value = document.getElementById('amount')?.value || '';
  document.getElementById('draft_date').value = document.getElementById('date')?.value || '';
  document.getElementById('draft_payer_id').value = document.getElementById('payer_id')?.value || '';
});
</script>

</body>
</html>