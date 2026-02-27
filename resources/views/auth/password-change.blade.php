<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        Bienvenue ! Pour sécuriser votre compte, vous devez définir un nouveau mot de passe avant de continuer.
    </div>

    <form method="POST" action="{{ route('password.change.update') }}">
        @csrf
        @method('PUT')

        <!-- Nouveau mot de passe -->
        <div>
            <x-input-label for="password" :value="__('Nouveau mot de passe')" />
            <x-text-input
                id="password"
                class="block mt-1 w-full"
                type="password"
                name="password"
                required
                autocomplete="new-password"
            />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
            <p class="mt-1 text-xs text-gray-500">
                Minimum 8 caractères, 1 majuscule, 1 chiffre, 1 caractère spécial.
            </p>
        </div>

        <!-- Confirmation -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirmer le mot de passe')" />
            <x-text-input
                id="password_confirmation"
                class="block mt-1 w-full"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
            />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Définir mon mot de passe') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>