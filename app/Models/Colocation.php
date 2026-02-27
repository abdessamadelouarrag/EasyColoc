<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Colocation extends Model
{
    protected $fillable = [
        'name',
        'status',
        'owner_id'
    ];

    // Owner
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    // Members (pivot)
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('role', 'joined_at', 'left_at')
            ->withTimestamps();
    }

    // Expenses
    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    // Categories
    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }
}