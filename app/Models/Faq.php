<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Faq extends Model
{
    protected $fillable = [
        'question',
        'answer',
        'category',
        'target_role',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Available FAQ categories.
     */
    public const CATEGORIES = [
        'general' => 'Umum',
        'pos' => 'Kasir / POS',
        'warehouse' => 'Gudang',
        'admin' => 'Admin',
        'manager' => 'Kepala Toko',
    ];

    /**
     * Available target roles.
     */
    public const TARGET_ROLES = [
        null => 'Semua Role',
        'cashier' => 'Kasir',
        'admin' => 'Admin',
        'manager' => 'Kepala Toko',
        'warehouse' => 'Gudang',
        'superadmin' => 'Superadmin',
    ];

    /**
     * Scope: only active FAQs.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: FAQs visible to a given role (matching target_role or null = all).
     */
    public function scopeForRole(Builder $query, ?string $role): Builder
    {
        return $query->where(function ($q) use ($role) {
            $q->whereNull('target_role')
                ->orWhere('target_role', $role);
        });
    }

    /**
     * Scope: ordered by sort_order then newest.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderByDesc('created_at');
    }

    /**
     * Get the human-readable category name.
     */
    public function getCategoryLabelAttribute(): string
    {
        return self::CATEGORIES[$this->category] ?? $this->category;
    }

    /**
     * Get the human-readable target role name.
     */
    public function getTargetRoleLabelAttribute(): string
    {
        if (is_null($this->target_role))
            return 'Semua Role';
        return self::TARGET_ROLES[$this->target_role] ?? $this->target_role;
    }
}
