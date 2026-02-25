<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Colocation extends Model
{
    protected $fillable = ['name', 'status', 'owner_id'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('role', 'joined_at', 'left_at')
            ->withTimestamps();
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }
}
