<?php

namespace App\Services;

use Illuminate\Support\Str;

class PasswordGenerator
{
    /**
     * Génère un mot de passe temporaire alphanumérique de 10 caractères.
     * Exemple : aB3xK9mP2q
     */
    public static function generate(int $length = 10): string
    {
        return Str::password(
            length: $length,
            letters: true,
            numbers: true,
            symbols: false,
            spaces: false,
        );
    }
}
