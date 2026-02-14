<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierPreOrderItem extends Model
{
    protected $fillable = [
        'supplier_pre_order_id',
        'product_id',
        'unit_name',
        'pcs_per_unit',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function preOrder()
    {
        return $this->belongsTo(SupplierPreOrder::class, 'supplier_pre_order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
