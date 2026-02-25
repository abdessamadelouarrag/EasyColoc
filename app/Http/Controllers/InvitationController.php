<?php

namespace App\Http\Controllers;

use App\Mail\ColocationInvitationMail;
use App\Models\Colocation;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class InvitationController extends Controller
{
    public function create(Colocation $colocation)
    {
        return view('invitations.create', compact('colocation'));
    }

    public function store(Request $request, Colocation $colocation)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $existing = Invitation::where('colocation_id', $colocation->id)
            ->where('email', $data['email'])
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            return back()->withErrors(['email' => 'Une invitation est déjà en attente pour cet email.']);
        }

        $invitation = Invitation::create([
            'colocation_id' => $colocation->id,
            'email' => $data['email'],
            'token' => Str::random(48),
            'status' => 'pending',
        ]);

        Mail::to($data['email'])->send(new ColocationInvitationMail($invitation));

        return back()->with('success', 'Invitation envoyée par email.');
    }

    public function accept(string $token)
    {
        $invitation = Invitation::where('token', $token)
            ->where('status', 'pending')
            ->firstOrFail();

        $user = auth()->user();

        if (!$user || $user->email !== $invitation->email) {
            return redirect()->route('login')
                ->with('status', 'Connecte-toi avec le même email que celui invité pour accepter.');
        }

        $colocation = $invitation->colocation;

        $already = $colocation->users()->where('users.id', $user->id)->exists();
        if (!$already) {
            $colocation->users()->attach($user->id, [
                'role' => 'member',
                'joined_at' => now(),
            ]);
        }

        $invitation->update([
            'status' => 'accepted',
            'accepted_at' => now(),
        ]);

        return redirect()->route('colocations.show', $colocation)
            ->with('success', 'Bienvenue ! Tu as rejoint la colocation.');
    }
}