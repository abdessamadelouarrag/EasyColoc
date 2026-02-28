<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class BanService
{
    public function ban(User $user): void
    {
        // 1) remove user from all colocations (soft leave)
        DB::table('colocation_user')
            ->where('user_id', $user->id)
            ->whereNull('left_at')
            ->update(['left_at' => now()]);

        DB::table('sessions')->where('user_id', $user->id)->delete();

        DB::table('colocations')
            ->where('owner_id', $user->id)
            ->where('status', 'active')
            ->update(['status' => 'cancelled']);
    }

    public function unban(User $user): void
    {
        // Do nothing here on purpose:
        // we keep left_at values so user DOES NOT rejoin old colocations.
        // User becomes "no active colocation".
    }
}