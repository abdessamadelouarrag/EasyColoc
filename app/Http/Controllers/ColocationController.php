<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ColocationController extends Controller
{
    public function create()
    {
        return view('colocations.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $userId = Auth::id();

        $coloc = DB::transaction(function () use ($data, $userId) {

            $coloc = Colocation::create([
                'name' => $data['name'],
                'status' => 'active',
                'owner_id' => $userId,
            ]);

            $coloc->users()->attach($userId, [
                'role' => 'owner',
                'joined_at' => now(),
            ]);

            return $coloc;
        });

        return redirect()->route('colocations.show', $coloc->id)
            ->with('success', 'Colocation créée avec succès.');
    }

    public function show(Colocation $colocation)
    {
        $colocation->load(['owner', 'users']);
        return view('colocations.show', compact('colocation'));
    }
}