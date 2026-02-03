<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'expense_category_id',
        'amount',
        'notes',
        'user_id',
    ];

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }

    protected $casts = [
        'date' => 'date',
        'amount' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
