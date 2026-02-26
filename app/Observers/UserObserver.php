<?php

namespace App\Observers;

use App\Jobs\SendEmailCredentials;
use App\Jobs\SendWhatsAppCredentials;
use App\Models\User;

class UserObserver
{
    /**
     * Déclenché après la création d'un utilisateur.
     * Dispatche le Job d'envoi des identifiants selon le canal disponible.
     */
    public function created(User $user): void
    {
        // Le mot de passe plain text est stocké temporairement sur le modèle
        // pour être passé au Job — il n'est jamais persisté en DB
        $plainPassword = $user->plain_password ?? null;

        if (! $plainPassword) {
            return;
        }

        if ($user->hasEmail()) {
            // Email prioritaire
            SendEmailCredentials::dispatch($user, $plainPassword);
        } else {
            // WhatsApp en fallback
            SendWhatsAppCredentials::dispatch($user, $plainPassword);
        }
    }
}
