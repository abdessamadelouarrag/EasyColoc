<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    protected $fillable = ['name','email','password','role','reputation','is_banned'];

    public function colocationsOwned(): HasMany
    {
        return $this->hasMany(Colocation::class, 'owner_id');
    }

    public function colocations(): BelongsToMany
    {
        return $this->belongsToMany(Colocation::class)
            ->withPivot(['role','joined_at','left_at'])
            ->withTimestamps();
    }

    public function activeMembership()
    {
        return $this->colocations()
            ->wherePivotNull('left_at')
            ->where('colocations.status', 'active');
    }

    public function hasActiveColocation(): bool
    {
        return $this->colocations()
        ->where('status', 'active')
        ->exists();
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
    
    public function isOwnerOfColocation(Colocation $colocation): bool
{
    if ($colocation->owner_id === $this->id) return true;

    return $colocation->members()
        ->where('users.id', $this->id)
        ->wherePivot('role', 'owner')
        ->exists();
}
}