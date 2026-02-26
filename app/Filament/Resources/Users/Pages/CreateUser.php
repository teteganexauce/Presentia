<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Services\PasswordGenerator;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Normaliser le numéro de téléphone
        if (!empty($data['phone'])) {
            $phone = preg_replace('/\s+/', '', $data['phone']);
            if (!str_starts_with($phone, '+229')) {
                $data['phone'] = '+229' . $phone;
            } else {
                $data['phone'] = $phone;
            }
        }
        // Validation : au moins un des deux obligatoire
        if (empty($data['phone']) && empty($data['email'])) {
            Notification::make()
                ->title('Champ requis')
                ->body('Veuillez renseigner au moins un numéro de téléphone ou une adresse email.')
                ->danger()
                ->send();

            $this->halt();
        }
        // Générer le mot de passe temporaire
        $plainPassword = PasswordGenerator::generate();

        // Stocker le plain text pour l'Observer (propriété non persistée)
        $this->plainPassword = $plainPassword;

        // Hasher avant stockage en DB
        $data['password'] = Hash::make($plainPassword);
        $data['status'] = 'PENDING';

        return $data;
    }

    protected function afterCreate(): void
    {
        $record = $this->record;

        // Vérifier que le record est bien un User
        if (!$record instanceof \App\Models\User) {
            return;
        }

        // Passer le plain password à l'Observer via une propriété temporaire
        $record->plain_password = $this->plainPassword ?? null;

        // Déclencher manuellement l'Observer created()
        (new \App\Observers\UserObserver)->created($record);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
