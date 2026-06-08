<?php

namespace App\Traits;

use App\Models\Support\AuditLog;

trait HasAuditLog
{
    protected static function bootHasAuditLog()
    {
        static::created(function ($model) {
            static::logActivity('CREATE', $model, null, $model->getAttributes());
        });

        static::updated(function ($model) {
            static::logActivity('UPDATE', $model, $model->getOriginal(), $model->getAttributes());
        });

        static::deleted(function ($model) {
            static::logActivity('DELETE', $model, $model->getAttributes(), null);
        });
    }

    protected static function logActivity(string $action, $model, $oldValues = null, $newValues = null)
    {
        try {
            AuditLog::create([
                'user_id' => auth()->id() ?? 1, // Fallback to 1 for system/seeders
                'activity_type' => $action,
                'table_name' => $model->getTable(),
                'record_id' => $model->getKey(),
                'old_values' => $oldValues ? json_encode($oldValues) : null,
                'new_values' => $newValues ? json_encode($newValues) : null,
                'ip_address' => request()->ip() ?? '127.0.0.1',
                'user_agent' => request()->userAgent() ?? 'System',
                'activity_result' => 'SUCCESS',
            ]);
        } catch (\Exception $e) {
            // Silently fail if audit log table doesn't exist yet (e.g. during migrations)
        }
    }
}
