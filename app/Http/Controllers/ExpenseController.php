<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ExpenseController extends Controller
{
    public function store(Request $request, Colocation $colocation)
    {
        abort_unless($colocation->members()->where('users.id', Auth::id())->exists(), 403);

        $data = $request->validate([
            'title' => ['required','string','max:120'],
            'amount' => ['required','numeric','min:0.01'],
            'date' => ['required','date'],
            'category_id' => ['required','exists:categories,id'],
        ]);

        Expense::create([
            'colocation_id' => $colocation->id,
            'payer_id' => Auth::id(),
            'category_id' => $data['category_id'],
            'title' => $data['title'],
            'amount' => $data['amount'],
            'date' => $data['date'],
        ]);

        return back()->with('ok', 'Dépense ajoutée.');
    }
}