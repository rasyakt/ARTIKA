<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HeldTransaction extends Model
{
    protected $fillable = [
        'user_id',
        'items',
        'subtotal',
        'discount',
        'total',
        'note',
    ];

    protected $casts = [
        'items' => 'array',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    /**
     * Get the user (cashier) who held this transaction
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the customer for this held transaction
     */
    // Customer relation removed — held transactions no longer reference Customer model.
}
