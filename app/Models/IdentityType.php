<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IdentityType extends Model
{
    protected $fillable = [
        'name',
        'label',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the users for the identity type.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
