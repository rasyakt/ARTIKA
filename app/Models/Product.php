<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
     * Get all stocks for this product across branches
     */
    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }

    /**
     * Get stock for a specific branch
     */
    public function stockForBranch($branchId)
    {
        return $this->stocks()->where('branch_id', $branchId)->first();
    }

    /**
     * Get total stock across all branches
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
}

