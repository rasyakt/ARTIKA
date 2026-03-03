<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Traits\Auditable;

class Supplier extends Model
{
    use Auditable;

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

    public function preOrders(): HasMany
    {
        return $this->hasMany(\App\Models\SupplierPreOrder::class);
    }
}
