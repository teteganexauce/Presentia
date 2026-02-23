<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // Identité
            $table->string('first_name');
            $table->string('last_name');

            // Identifiants de connexion
            // Les deux sont nullable en DB — la contrainte "au moins un"
            // est gérée dans la Form Request via required_without
            $table->string('phone')->nullable()->unique();
            $table->string('email')->nullable()->unique();

            $table->date('birth_date')->nullable();
            $table->string('address')->nullable();
            $table->string('photo')->nullable();

            // Auth
            $table->string('password');
            $table->rememberToken();

            // Statut du compte
            $table->enum('status', [
                'PENDING',    // première connexion pas encore faite
                'ACTIVE',     // compte actif
                'INACTIVE',   // désactivé temporairement
                'SUSPENDED',  // suspendu par l'admin
            ])->default('PENDING');

            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Index pour les recherches fréquentes
            $table->index(['status', 'created_at']);
        });

        // ⚠️ On garde ces deux tables telles quelles — gérées par Laravel
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};