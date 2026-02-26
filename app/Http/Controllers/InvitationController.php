<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class InvitationController extends Controller
{
    public function store(Request $request, Colocation $colocation)
    {
        $pivot = $colocation->members()->where('users.id', Auth::id())->firstOrFail()->pivot;
        abort_unless($pivot->role === 'owner', 403);

        $data = $request->validate([
            'email' => ['required','email'],
        ]);

        $inv = Invitation::create([
            'colocation_id' => $colocation->id,
            'email' => $data['email'],
            'token' => Str::random(40),
            'status' => 'pending',
        ]);

        // Ici tu peux envoyer un email (Mail::to()->send()) si tu veux

        return back()->with('ok', 'Invitation créée. Token: '.$inv->token);
    }

    public function acceptForm(string $token)
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();
        return view('invitations.accept', compact('invitation'));
    }

    public function accept(Request $request, string $token)
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();

        if (Auth::user()->hasActiveColocation) {
            return back()->withErrors(['invite' => 'Vous avez déjà une colocation active.']);
        }

        if (Auth::user()->email !== $invitation->email) {
            return back()->withErrors(['invite' => 'Email ne correspond pas à l’invitation.']);
        }

        if ($invitation->status !== 'pending') {
            return back()->withErrors(['invite' => 'Invitation déjà traitée.']);
        }

        $colocation = $invitation->colocation;

        $colocation->members()->attach(Auth::id(), [
            'role' => 'member',
            'joined_at' => now(),
        ]);

        $invitation->update(['status' => 'accepted']);

        return redirect()->route('colocations.show', $colocation)->with('ok', 'Invitation acceptée.');
    }

    public function refuse(string $token)
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();
        $invitation->update(['status' => 'refused']);
        return redirect()->route('dashboard')->with('ok', 'Invitation refusée.');
    }
}