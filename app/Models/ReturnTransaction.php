<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReturnTransaction extends Model
{
    protected $table = 'returns';

    protected $fillable = [
        'return_no',
        'transaction_id',
        'user_id',
        'branch_id',
        'items',
        'total_refund',
        'reason',
        'status',
    ];

    protected $casts = [
        'items' => 'array',
        'total_refund' => 'decimal:2',
    ];

    /**
     * Boot the model and generate return number
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($return) {
            if (empty($return->return_no)) {
                $return->return_no = 'RET-' . date('Ymd') . '-' . str_pad(static::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    /**
     * Get the original transaction
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * Get the user who processed the return
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the branch where return was processed
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
