<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    protected $fillable = [
        'invoice_no',
        'user_id',
        'subtotal',
        'discount',
        'total_amount',
        'payment_method',
        'cash_amount',
        'change_amount',
        'status',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'cash_amount' => 'decimal:2',
        'change_amount' => 'decimal:2',
    ];

    /**
     * Boot the model and generate invoice number
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            if (empty($transaction->invoice_no)) {
                $transaction->invoice_no = 'INV-' . date('Ymd') . '-' . str_pad(static::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    /**
     * Get the user (cashier) for this transaction
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    /**
     * Get all items for this transaction
     */
    public function items(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }

    /**
     * Get returns for this transaction
     */
    public function returns(): HasMany
    {
        return $this->hasMany(ReturnTransaction::class, 'transaction_id');
    }

    /**
     * Get the total amount already refunded for this transaction
     */
    public function getTotalRefundedAttribute(): float
    {
        return (float) $this->returns()->where('status', 'approved')->sum('total_refund');
    }

    /**
     * Check if the transaction has been fully returned
     */
    public function getIsFullyReturnedAttribute(): bool
    {
        return $this->status === 'returned' || ($this->total_amount > 0 && $this->total_refunded >= $this->total_amount);
    }

    /**
     * Get the total returned quantity for a specific product in this transaction
     */
    public function getReturnedQuantity($productId): int
    {
        $total = 0;
        foreach ($this->returns as $return) {
            if ($return->status === 'approved' && is_array($return->items)) {
                foreach ($return->items as $item) {
                    if ((int) $item['product_id'] === (int) $productId) {
                        $total += (int) $item['quantity'];
                    }
                }
            }
        }
        return $total;
    }

    /**
     * Scope for today's transactions
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }
}
