<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\BalanceService;


class ColocationController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $colocations = $user->colocations()
            ->latest()
            ->get();

        return view('colocations.index', compact('colocations'));
    }

    public function create()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->hasActiveColocation()) {
            return redirect()
                ->route('colocations.index')
                ->with('error', 'Vous avez déjà une colocation active.');
        }

        return view('colocations.create');
    }

    public function store(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        abort_unless(!$user->hasActiveColocation(), 403);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
        ]);

        $colocation = Colocation::create([
            'name' => $data['name'],
            'owner_id' => $user->id,
            'status' => 'active',
        ]);

        // Attach owner to pivot table
        $colocation->members()->attach($user->id, [
            'role' => 'owner',
            'joined_at' => now(),
        ]);

        return redirect()->route('colocations.show', $colocation);
    }


    public function show(Colocation $colocation, BalanceService $balanceService)
    {
        $colocation->load(['owner', 'members', 'expenses.payer', 'expenses.category']);

        $summary = $balanceService->summary($colocation);

        return view('colocations.show', array_merge(compact('colocation'), $summary));
    }

    public function cancel(Colocation $colocation)
    {
        $user = Auth::user();

        $member = $colocation->members()
            ->where('users.id', $user->id)
            ->firstOrFail();

        $pivot = $member->pivot;

        abort_unless($pivot->role === 'owner', 403);

        $colocation->update(['status' => 'cancelled']);

        return back()->with('ok', 'Colocation annulée.');
    }

    public function leave(Colocation $colocation)
    {
        $user = Auth::user();

        $member = $colocation->members()
            ->where('users.id', $user->id)
            ->firstOrFail();

        $pivot = $member->pivot;

        if ($pivot->role === 'owner') {
            return back()->withErrors([
                'leave' => 'Le owner ne peut pas quitter.'
            ]);
        }

        $colocation->members()->updateExistingPivot($user->id, [
            'left_at' => now()
        ]);

        return redirect()
            ->route('colocations.index')
            ->with('ok', 'Vous avez quitté la colocation.');
    }
}
