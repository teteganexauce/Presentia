<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendWhatsAppCredentials implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(
        public readonly User $user,
        public readonly string $plainPassword,
    ) {}

    public function handle(): void
    {
        // TODO : intégrer l'API WhatsApp Business dans NOTIF-001
        // Pour l'instant on log le message pour ne pas bloquer le développement
        Log::info('WhatsApp credentials à envoyer', [
            'phone' => $this->user->phone,
            'user_id' => $this->user->id,
            'message' => "Bonjour {$this->user->first_name}, votre mot de passe temporaire est : {$this->plainPassword}",
        ]);
    }
}
