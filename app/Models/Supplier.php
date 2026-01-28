<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'last_purchase_at',
    ];

    protected $casts = [
        'last_purchase_at' => 'datetime',
    ];

    public function purchases(): HasMany
    {
        return $this->hasMany(\App\Models\SupplierPurchase::class);
    }
}
