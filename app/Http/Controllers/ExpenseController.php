<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ExpenseController extends Controller
{
    public function create(Colocation $colocation)
    {
        abort_unless(Auth::user()->isOwnerOfColocation($colocation), 403);

        $categories = $colocation->categories;
        $members = $colocation->members;

        return view('expenses.create', compact('colocation', 'categories', 'members'));
    }

    public function store(Request $request, Colocation $colocation)
    {
        abort_unless(Auth::user()->isOwnerOfColocation($colocation), 403);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'date' => ['required', 'date'],

            // payer لازم يكون member فـ نفس colocation
            'payer_id' => [
                'required',
                Rule::exists('colocation_user', 'user_id')->where('colocation_id', $colocation->id),
            ],

            // category لازم تكون ديال نفس colocation
            'category_id' => [
                'required',
                Rule::exists('categories', 'id')->where('colocation_id', $colocation->id),
            ],
        ]);

        Expense::create([
            'colocation_id' => $colocation->id,
            'payer_id' => $data['payer_id'],
            'category_id' => $data['category_id'],
            'title' => $data['title'],
            'amount' => $data['amount'],
            'date' => $data['date'],
        ]);

        return redirect()
            ->route('colocations.show', $colocation)
            ->with('ok', 'Dépense ajoutée avec succès.');
    }
}