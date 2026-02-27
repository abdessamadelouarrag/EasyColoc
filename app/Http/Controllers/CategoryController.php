<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function store(Request $request, Colocation $colocation)
    {
        abort_unless(Auth::user()->isOwnerOfColocation($colocation), 403);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:50'],
        ]);

        $exists = $colocation->categories()
            ->whereRaw('LOWER(name) = ?', [strtolower($data['name'])])
            ->exists();

        if ($exists) {
            return back()->withErrors(['category' => 'Cette catégorie existe déjà.']);
        }

        $category = $colocation->categories()->create([
            'name' => $data['name'],
        ]);

        return redirect()
            ->route('expenses.create', $colocation)
            ->with('ok', 'Catégorie ajoutée ✅')
            ->with('category_selected', $category->id)
            ->withInput([
                'title' => $request->input('_draft_title'),
                'amount' => $request->input('_draft_amount'),
                'date' => $request->input('_draft_date'),
                'payer_id' => $request->input('_draft_payer_id'),
            ]);
    }
}