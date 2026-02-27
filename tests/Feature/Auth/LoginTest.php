<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_avec_email_valide(): void
    {
        $user = User::factory()->withEmailOnly()->create([
            'status' => 'ACTIVE',
        ]);

        $response = $this->post(route('login'), [
            'identifier' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_login_avec_telephone_valide(): void
    {
        $user = User::factory()->withPhoneOnly()->create([
            'status' => 'ACTIVE',
        ]);

        $response = $this->post(route('login'), [
            'identifier' => $user->phone,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_login_identifiant_inconnu(): void
    {
        $response = $this->post(route('login'), [
            'identifier' => 'inconnu@test.com',
            'password' => 'password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('identifier');
    }

    public function test_login_mot_de_passe_incorrect(): void
    {
        $user = User::factory()->withEmailOnly()->create([
            'status' => 'ACTIVE',
        ]);

        $response = $this->post(route('login'), [
            'identifier' => $user->email,
            'password' => 'mauvais_mot_de_passe',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('identifier');
    }

    public function test_login_champ_identifier_requis(): void
    {
        $response = $this->post(route('login'), [
            'identifier' => '',
            'password' => 'password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('identifier');
    }

    public function test_rate_limiting_apres_5_tentatives(): void
    {
        $user = User::factory()->withEmailOnly()->create([
            'status' => 'ACTIVE',
        ]);

        // 5 tentatives échouées
        foreach (range(1, 5) as $attempt) {
            $this->post(route('login'), [
                'identifier' => $user->email,
                'password' => 'mauvais_mot_de_passe',
            ]);
        }

        // 6ème tentative → rate limited
        $response = $this->post(route('login'), [
            'identifier' => $user->email,
            'password' => 'mauvais_mot_de_passe',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('identifier');
    }

    public function test_session_regeneree_apres_connexion(): void
    {
        $user = User::factory()->withEmailOnly()->create([
            'status' => 'ACTIVE',
        ]);

        $response = $this->post(route('login'), [
            'identifier' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
    }

    public function test_deconnexion(): void
    {
        $user = User::factory()->withEmailOnly()->create([
            'status' => 'ACTIVE',
        ]);

        $this->actingAs($user);

        $response = $this->post(route('logout'));

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
