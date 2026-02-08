<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    protected $fillable = [
        'name',
        'type',
        'value',
        'start_date',
        'end_date',
        'product_id',
        'category_id',
        'min_purchase',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'value' => 'decimal:2',
        'min_purchase' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Scope a query to only include active and non-expired promos.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now());
    }

    /**
     * Scope a query to include promos that should be deactivated (expired but still is_active).
     */
    public function scopeExpired($query)
    {
        return $query->where('is_active', true)
            ->whereDate('end_date', '<', now());
    }

    /**
     * Check if the promo is currently valid.
     */
    public function isValid()
    {
        $now = now();
        return $this->is_active &&
            \Carbon\Carbon::parse($this->start_date)->startOfDay()->lte($now) &&
            \Carbon\Carbon::parse($this->end_date)->endOfDay()->gte($now);
    }
}
