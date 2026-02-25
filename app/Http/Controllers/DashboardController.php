<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $colocations = $user->colocations()
            ->with('owner')
            ->withCount('users')
            ->latest()
            ->get();

        $stats = [
            'colocations_count' => $colocations->count(),
            'members_count' => $colocations->sum('users_count'),
            'reputation' => $user->reputation ?? '+0',
            'pending_invitations' => 0,
            'month_total' => 0,
        ];

        return view('dashboard', compact('colocations', 'stats'));
    }
}