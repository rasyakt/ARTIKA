<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    protected $fillable = [
        'barcode',
        'name',
        'category_id',
        'price',
        'cost_price',
        'description',
        'image',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the category for this product
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get all stocks for this product
     */
    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }

    /**
     * Get the first stock record (singular)
     */
    public function stock(): HasOne
    {
        return $this->hasOne(Stock::class);
    }

    /**
     * Get total stock
     */
    public function getTotalStockAttribute()
    {
        return $this->stocks()->sum('quantity');
    }

    /**
     * Get profit margin
     */
    public function getProfitMarginAttribute()
    {
        if ($this->cost_price == 0)
            return 0;
        return (($this->price - $this->cost_price) / $this->cost_price) * 100;
    }
    /**
     * Get all purchases from suppliers for this product
     */
    public function supplierPurchases(): HasMany
    {
        return $this->hasMany(SupplierPurchase::class);
    }
}

