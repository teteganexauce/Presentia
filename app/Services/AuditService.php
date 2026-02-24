<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditService
{
    /**
     * Enregistre une action manuellement.
     *
     * Exemple d'utilisation :
     * AuditService::log('login', $user);
     * AuditService::log('export', null, ['type' => 'members_csv']);
     */
    public static function log(
        string $action,
        $model = null,
        array $extra = []
    ): void {
        if (app()->environment('testing')) {
            return;
        }

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'auditable_type' => $model ? get_class($model) : null,
            'auditable_id' => $model?->getKey(),
            'old_values' => null,
            'new_values' => empty($extra) ? null : $extra,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'created_at' => now(),
        ]);
    }
}
