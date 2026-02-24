<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait Auditable
{
    public static function bootAuditable(): void
    {
        // À la création
        static::created(function ($model) {
            self::logAudit('created', $model, [], $model->getAttributes());
        });

        // À la modification
        static::updated(function ($model) {
            $old = $model->getOriginal();
            $new = $model->getChanges();

            // Exclure les timestamps et données sensibles
            unset($old['updated_at'], $new['updated_at']);
            unset($old['password'], $new['password']);
            unset($old['remember_token'], $new['remember_token']);

            if (! empty($new)) {
                self::logAudit('updated', $model, $old, $new);
            }
        });

        // À la suppression (soft delete inclus)
        static::deleted(function ($model) {
            self::logAudit('deleted', $model, $model->getAttributes(), []);
        });
    }

    private static function logAudit(
        string $action,
        $model,
        array $oldValues,
        array $newValues
    ): void {
        // Ne pas logguer si on est en test
        if (app()->environment('testing')) {
            return;
        }

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'auditable_type' => get_class($model),
            'auditable_id' => $model->getKey(),
            'old_values' => empty($oldValues) ? null : $oldValues,
            'new_values' => empty($newValues) ? null : $newValues,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'created_at' => now(),
        ]);
    }
}
