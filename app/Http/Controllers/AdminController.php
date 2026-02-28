<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Services\BanService;


class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'users' => User::count(),
            'colocations' => Colocation::count(),
            'expenses_total' => Expense::sum('amount'),
            'banned' => User::where('is_banned', true)->count(),
        ];

        $users = User::latest()->paginate(20);
        return view('admin.dashboard', compact('stats', 'users'));
    }

    // public function toggleBan(User $user)
    // {
    //     if ($user->id === Auth::id()) {
    //         return back()->withErrors(['ban' => 'Impossible de bannir toi-même.']);
    //     }

    //     $user->update(['is_banned' => !$user->is_banned]);
    //     return back()->with('ok', 'Statut changé.');
    // }

    public function toggleBan(User $user, BanService $banService)
    {
        // if user is admin, optional protection
        // if ($user->role === 'admin') return back()->withErrors(['ban' => 'Impossible de bannir un admin.']);

        if ($user->is_banned) {
            // Deban
            $user->update(['is_banned' => false]);
            $banService->unban($user);

            return back()->with('ok', 'Utilisateur débanni. Il ne réintègre aucune colocation.');
        }

        // Ban
        $user->update(['is_banned' => true]);
        $banService->ban($user);

        return back()->with('ok', 'Utilisateur banni et retiré de toutes ses colocations.');
    }
}
