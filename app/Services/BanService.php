<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class BanService
{
    public function ban(User $user): void
    {
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
}