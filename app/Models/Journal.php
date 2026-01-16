<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    protected $fillable = [
        'transaction_id',
        'type',
        'account_name',
        'amount',
        'description',
    ];
}
