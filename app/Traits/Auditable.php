<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

trait Auditable
{
    public static function bootAuditable()
    {
        static::created(function ($model) {
            $model->logAudit('created');
        });

        static::updated(function ($model) {
            $model->logAudit('updated');
        });

        static::deleted(function ($model) {
            $model->logAudit('deleted');
        });
    }

    protected function logAudit($action)
    {
        $changes = null;

        if ($action === 'updated') {
            $changes = [
                'before' => array_intersect_key($this->getOriginal(), $this->getDirty()),
                'after' => $this->getDirty(),
            ];

            // Remove timestamps and sensitive fields
            unset($changes['before']['updated_at'], $changes['after']['updated_at']);
            if (isset($changes['before']['password']))
                $changes['before']['password'] = '******';
            if (isset($changes['after']['password']))
                $changes['after']['password'] = '******';

            // If no relevant changes, don't log
            if (empty($changes['before']) && empty($changes['after'])) {
                return;
            }
        } elseif ($action === 'created') {
            $changes = ['after' => $this->getAttributes()];
            unset($changes['after']['updated_at'], $changes['after']['created_at']);
            if (isset($changes['after']['password']))
                $changes['after']['password'] = '******';
        } elseif ($action === 'deleted') {
            $changes = ['before' => $this->getOriginal()];
            unset($changes['before']['updated_at'], $changes['before']['created_at']);
        }

        AuditLog::log(
            $action . '_' . strtolower(class_basename($this)),
            class_basename($this),
            $this->id,
            null,
            null,
            $changes,
            "Automatic audit log for {$action} operation on " . class_basename($this)
        );
    }
}
