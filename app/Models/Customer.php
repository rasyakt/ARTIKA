<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'points',
        'member_since',
    ];

    protected $casts = [
        'member_since' => 'date',
        'points' => 'integer',
    ];

    /**
     * Get all transactions for this customer
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get all held transactions for this customer
     */
    public function heldTransactions(): HasMany
    {
        return $this->hasMany(HeldTransaction::class);
    }
}
