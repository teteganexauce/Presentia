<?php

use App\Http\Controllers\Auth\PasswordChangeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Changement de mot de passe obligatoire (1ère connexion)
    Route::get('/password/change', [PasswordChangeController::class, 'create'])
        ->name('password.change');
    Route::put('/password/change', [PasswordChangeController::class, 'update'])
        ->name('password.change.update');
});

require __DIR__.'/auth.php';