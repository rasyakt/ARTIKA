<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    protected $table = 'audit_logs';

    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'amount',
        'payment_method',
        'changes',
        'ip_address',
        'mac_address',
        'device_name',
        'user_agent',
        'notes',
    ];

    protected $casts = [
        'changes' => 'array',
        'amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user who performed the action
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: Get logs for specific user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: Get logs for specific action
     */
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope: Get logs for date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope: Get recent logs
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Static method to log an action
     */
    public static function log($action, $modelType = null, $modelId = null, $amount = null, $paymentMethod = null, $changes = null, $notes = null)
    {
        $userAgent = request()->userAgent();
        $deviceName = self::extractDeviceName($userAgent);

        return self::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'amount' => $amount,
            'payment_method' => $paymentMethod,
            'changes' => $changes,
            'ip_address' => request()->ip(),
            'mac_address' => request()->header('X-Mac-Address'), // Will be sent from client if available
            'device_name' => $deviceName,
            'user_agent' => $userAgent,
            'notes' => $notes,
        ]);
    }

    /**
     * Extract device name from user agent
     */
    protected static function extractDeviceName($userAgent)
    {
        if (!$userAgent)
            return 'Unknown Device';

        // Detect OS
        if (preg_match('/Windows NT 10/i', $userAgent)) {
            $os = 'Windows 10';
        } elseif (preg_match('/Windows NT 11/i', $userAgent)) {
            $os = 'Windows 11';
        } elseif (preg_match('/Macintosh/i', $userAgent)) {
            $os = 'macOS';
        } elseif (preg_match('/Android/i', $userAgent)) {
            $os = 'Android';
        } elseif (preg_match('/iPhone|iPad/i', $userAgent)) {
            $os = 'iOS';
        } elseif (preg_match('/Linux/i', $userAgent)) {
            $os = 'Linux';
        } else {
            $os = 'Unknown OS';
        }

        // Detect Browser
        if (preg_match('/Edg\//i', $userAgent)) {
            $browser = 'Edge';
        } elseif (preg_match('/Chrome\//i', $userAgent)) {
            $browser = 'Chrome';
        } elseif (preg_match('/Firefox\//i', $userAgent)) {
            $browser = 'Firefox';
        } elseif (preg_match('/Safari\//i', $userAgent) && !preg_match('/Chrome/i', $userAgent)) {
            $browser = 'Safari';
        } else {
            $browser = 'Unknown Browser';
        }

        return "$os - $browser";
    }
}
