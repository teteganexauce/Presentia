<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Vos identifiants</title>
</head>
<body style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">

    <h2 style="color: #1a56db;">Bienvenue sur EBER Platform 👋</h2>

    <p>Bonjour <strong>{{ $user->full_name }}</strong>,</p>

    <p>Votre compte a été créé par l'administrateur. Voici vos identifiants de connexion :</p>

    <div style="background: #f3f4f6; padding: 20px; border-radius: 8px; margin: 20px 0;">
        @if($user->email)
            <p><strong>Email :</strong> {{ $user->email }}</p>
        @endif
        @if($user->phone)
            <p><strong>Téléphone :</strong> {{ $user->phone }}</p>
        @endif
        <p><strong>Mot de passe temporaire :</strong>
            <span style="font-size: 18px; font-weight: bold; color: #1a56db;">
                {{ $plainPassword }}
            </span>
        </p>
    </div>

    <p style="color: #e02424;">
        ⚠️ Vous devrez changer ce mot de passe lors de votre première connexion.
    </p>

    <a href="{{ url('/login') }}"
       style="display: inline-block; background: #1a56db; color: white;
              padding: 12px 24px; border-radius: 6px; text-decoration: none;">
        Se connecter
    </a>

    <p style="margin-top: 30px; color: #6b7280; font-size: 12px;">
        Église Baptiste de l'Étoile Rouge — Plateforme Jeunesse
    </p>

</body>
</html>