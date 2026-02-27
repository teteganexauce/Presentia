<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasswordChangeTest extends TestCase
{
    use RefreshDatabase;

    public function test_utilisateur_pending_redirige_vers_changement_mot_de_passe(): void
    {
        $user = User::factory()->withEmailOnly()->create([
            'status' => 'PENDING',
        ]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertRedirect(route('password.change'));
    }

    public function test_utilisateur_actif_accede_au_dashboard(): void
    {
        $user = User::factory()->withEmailOnly()->create([
            'status' => 'ACTIVE',
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk();
    }

    public function test_page_changement_mot_de_passe_accessible(): void
    {
        $user = User::factory()->withEmailOnly()->create([
            'status' => 'PENDING',
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user)
            ->get(route('password.change'))
            ->assertOk();
    }

    public function test_changement_mot_de_passe_valide(): void
    {
        $user = User::factory()->withEmailOnly()->create([
            'status' => 'PENDING',
        ]);

        $this->actingAs($user)
            ->put(route('password.change.update'), [
                'password' => 'NouveauMdp@2026',
                'password_confirmation' => 'NouveauMdp@2026',
            ])
            ->assertRedirect(route('login'));

        $this->assertEquals('ACTIVE', $user->fresh()->status);
    }

    public function test_changement_mot_de_passe_trop_faible(): void
    {
        $user = User::factory()->withEmailOnly()->create([
            'status' => 'PENDING',
        ]);

        $this->actingAs($user)
            ->put(route('password.change.update'), [
                'password' => 'faible',
                'password_confirmation' => 'faible',
            ])
            ->assertSessionHasErrors('password');

        $this->assertEquals('PENDING', $user->fresh()->status);
    }

    public function test_changement_mot_de_passe_sans_majuscule(): void
    {
        $user = User::factory()->withEmailOnly()->create([
            'status' => 'PENDING',
        ]);

        $this->actingAs($user)
            ->put(route('password.change.update'), [
                'password' => 'motdepasse@2026',
                'password_confirmation' => 'motdepasse@2026',
            ])
            ->assertSessionHasErrors('password');
    }

    public function test_changement_mot_de_passe_confirmation_incorrecte(): void
    {
        $user = User::factory()->withEmailOnly()->create([
            'status' => 'PENDING',
        ]);

        $this->actingAs($user)
            ->put(route('password.change.update'), [
                'password' => 'NouveauMdp@2026',
                'password_confirmation' => 'AutreMdp@2026',
            ])
            ->assertSessionHasErrors('password');
    }

    public function test_statut_active_apres_changement_reussi(): void
    {
        $user = User::factory()->withEmailOnly()->create([
            'status' => 'PENDING',
        ]);

        $this->actingAs($user)
            ->put(route('password.change.update'), [
                'password' => 'NouveauMdp@2026',
                'password_confirmation' => 'NouveauMdp@2026',
            ]);

        $this->assertEquals('ACTIVE', $user->fresh()->status);
    }
}
