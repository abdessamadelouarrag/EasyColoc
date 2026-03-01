<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\BalanceService;
use App\Models\User;


class ColocationController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $colocations = $user->colocations()
            ->wherePivotNull('left_at')
            ->where('status', 'active')
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
        $user = Auth::user();

        if (!$user->is_admin) {
            $member = $colocation->members()
                ->where('users.id', $user->id)
                ->first();

            if (!$member || $member->pivot->left_at !== null) {
                return redirect()
                    ->route('colocations.index')
                    ->withErrors(['leave' => "Vous n'êtes plus membre de cette colocation."]);
            }
        }

        $colocation->load([
            'owner',
            'categories',
            'members' => fn($q) => $q->wherePivotNull('left_at'),
            'expenses.payer',
            'expenses.category',
        ]);

        $summary = $balanceService->summary($colocation);

        return view('colocations.show', array_merge(
            compact('colocation'),
            $summary,
            ['categories' => $colocation->categories]
        ));
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

    // public function leave(Colocation $colocation)
    // {
    //     $user = Auth::user();

    //     $member = $colocation->members()
    //         ->where('users.id', $user->id)
    //         ->firstOrFail();

    //     $pivot = $member->pivot;

    //     if ($pivot->role === 'owner') {
    //         return back()->withErrors([
    //             'leave' => 'Le owner ne peut pas quitter.'
    //         ]);
    //     }

    //     $colocation->members()->updateExistingPivot($user->id, [
    //         'left_at' => now()
    //     ]);

    //     return redirect()
    //         ->route('colocations.index')
    //         ->with('ok', 'Vous avez quitté la colocation.');
    // }

    public function leave(Colocation $colocation, BalanceService $balanceService)
    {

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $member = $colocation->members()
            ->where('users.id', $user->id)
            ->firstOrFail();

        if ($member->pivot->role === 'owner') {
            return back()->withErrors([
                'leave' => 'Le owner ne peut pas quitter.'
            ]);
        }

        $summary = $balanceService->summary($colocation);
        $balance = $summary['balances'][$user->id] ?? 0;

        if ($balance < 0) {
            $user->decrement('reputation');
        } else {
            $user->increment('reputation');
        }

        $colocation->members()->updateExistingPivot($user->id, [
            'left_at' => now()
        ]);

        return redirect()
            ->route('colocations.index')
            ->with('ok', 'Vous avez quitté la colocation.');
    }

    // public function removeMember(Colocation $colocation, User $user)
    // {
    //     $auth = Auth::user();

    //     abort_unless($auth->isOwnerOfColocation($colocation), 403);

    //     $member = $colocation->members()
    //         ->where('users.id', $user->id)
    //         ->firstOrFail();

    //     $pivot = $member->pivot;

    //     if ($pivot->role === 'owner') {
    //         return back()->withErrors([
    //             'remove' => 'Impossible de retirer le owner.'
    //         ]);
    //     }

    //     $colocation->members()->updateExistingPivot($user->id, [
    //         'left_at' => now()
    //     ]);

    //     return back()->with('success', 'Membre retiré avec succès.');
    // }

    public function removeMember(Colocation $colocation, User $user, BalanceService $balanceService)
    {
        $owner = Auth::user();

        abort_unless($owner->isOwnerOfColocation($colocation), 403);

        $member = $colocation->members()
            ->where('users.id', $user->id)
            ->firstOrFail();

        if ($member->pivot->role === 'owner') {
            return back()->withErrors([
                'remove' => 'Impossible de retirer le owner.'
            ]);
        }

        $summary = $balanceService->summary($colocation);
        $balance = $summary['balances'][$user->id] ?? 0;

        if ($balance < 0) {
            $debt = abs($balance);

            \App\Models\Payment::create([
                'colocation_id' => $colocation->id,
                'from_user_id'  => $owner->id,
                'to_user_id'    => $user->id,
                'amount'        => $debt,
            ]);
        }

        $colocation->members()->updateExistingPivot($user->id, [
            'left_at' => now()
        ]);

        return back()->with('ok', 'Membre retiré.');
    }
}
