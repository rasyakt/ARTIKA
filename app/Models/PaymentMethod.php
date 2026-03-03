<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = ['name', 'slug', 'is_active', 'icon', 'proof_requirement', 'sort_order'];

    const PROOF_DISABLED = 'disabled';
    const PROOF_OPTIONAL = 'optional';
    const PROOF_REQUIRED = 'required';

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }
}
