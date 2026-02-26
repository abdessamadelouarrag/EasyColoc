<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class PaymentController extends Controller
{
    public function store(Request $request, Colocation $colocation)
    {
        abort_unless($colocation->members()->where('users.id', Auth::id())->exists(), 403);

        $data = $request->validate([
            'to_user_id' => ['required','exists:users,id'],
            'amount' => ['required','numeric','min:0.01'],
        ]);

        Payment::create([
            'colocation_id' => $colocation->id,
            'from_user_id' => Auth::id(),
            'to_user_id' => $data['to_user_id'],
            'amount' => $data['amount'],
        ]);

        return back()->with('ok', 'Paiement enregistré.');
    }
}