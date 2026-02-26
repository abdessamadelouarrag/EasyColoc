<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Colocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CategoryController extends Controller
{
    public function store(Request $request, Colocation $colocation)
    {
        $pivot = $colocation->members()->where('users.id', Auth::id())->firstOrFail()->pivot;
        abort_unless($pivot->role === 'owner', 403);

        $data = $request->validate([
            'name' => ['required','string','max:80'],
        ]);

        Category::create([
            'colocation_id' => $colocation->id,
            'name' => $data['name'],
        ]);

        return back()->with('ok', 'Catégorie ajoutée.');
    }

    public function destroy(Category $category)
    {
        $colocation = $category->colocation;
        $pivot = $colocation->members()->where('users.id', Auth::id())->firstOrFail()->pivot;
        abort_unless($pivot->role === 'owner', 403);

        $category->delete();
        return back()->with('ok', 'Catégorie supprimée.');
    }
}