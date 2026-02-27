<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Services\AuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class PasswordChangeController extends Controller
{
    public function create(): View
    {
        return view('auth.password-change');
    }

    public function update(ChangePasswordRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Mettre à jour le mot de passe et activer le compte
        $user->update([
            'password' => Hash::make($request->validated('password')),
            'status'   => 'ACTIVE',
        ]);

        // Log d'audit
        AuditService::log('password_changed', $user);

        // Invalider la session et forcer une reconnexion
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('status', 'Mot de passe mis à jour. Veuillez vous reconnecter.');
    }
}