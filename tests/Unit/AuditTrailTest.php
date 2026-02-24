<?php

namespace Tests\Unit;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditTrailTest extends TestCase
{
    use RefreshDatabase;

    public function test_ne_loggue_pas_en_environnement_de_test(): void
    {
        // Le trait Auditable skip le log en environnement testing
        User::factory()->create();

        $this->assertSame(0, AuditLog::count());
    }

    public function test_audit_log_peut_etre_cree_manuellement(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $log = AuditLog::create([
            'user_id' => $user->id,
            'action' => 'login',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'test-agent',
            'created_at' => now(),
        ]);

        $this->assertSame(1, AuditLog::count());
        $this->assertSame('login', $log->action);
        $this->assertSame($user->id, $log->user_id);
    }

    public function test_audit_log_a_une_relation_user(): void
    {
        $user = User::factory()->create();

        $log = AuditLog::create([
            'user_id' => $user->id,
            'action' => 'created',
            'created_at' => now(),
        ]);

        $this->assertSame($user->id, $log->user->id);
    }
}
