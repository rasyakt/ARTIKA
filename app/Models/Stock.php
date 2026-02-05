<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = [
        'product_id',
        'quantity',
        'min_stock',
        'expired_at',
        'batch_no',
    ];

    protected $casts = [
        'expired_at' => 'date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
