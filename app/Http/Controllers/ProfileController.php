<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Services\BalanceService;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    // public function edit(Request $request): View
    // {
    //     return view('profile.edit', [
    //         'user' => $request->user(),
    //     ]);
    // }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }


    public function edit(Request $request, BalanceService $balanceService)
    {
        $user = $request->user();

        // حيث عندك rule: غير colocation وحدة active
        $activeColoc = $user->colocations()->where('status', 'active')->first();

        $debts = null;

        if ($activeColoc) {
            $summary = $balanceService->summary($activeColoc);

            $toPay = collect($summary['settlements'])->filter(fn($s) => $s['from_id'] == $user->id)->values();
            $toReceive = collect($summary['settlements'])->filter(fn($s) => $s['to_id'] == $user->id)->values();

            $debts = [
                'colocation' => $activeColoc,
                'balance' => $summary['balances'][$user->id] ?? 0,
                'toPay' => $toPay,
                'toReceive' => $toReceive,
            ];
        }

        return view('profile.edit', compact('debts'));
    }
}
