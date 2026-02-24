<?php

namespace App\Models;

use App\Traits\Auditable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser, HasName
{
    use Auditable, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'email',
        'birth_date',
        'address',
        'photo',
        'password',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'birth_date' => 'date',
            'password' => 'hashed',
        ];
    }

    // ─── Accesseurs ──────────────────────────────────────────────────────────

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    // ─── Helpers de statut ───────────────────────────────────────────────────

    public function isActive(): bool
    {
        return $this->status === 'ACTIVE';
    }

    public function isPending(): bool
    {
        return $this->status === 'PENDING';
    }

    public function hasEmail(): bool
    {
        return ! empty($this->email);
    }

    public function hasPhone(): bool
    {
        return ! empty($this->phone);
    }

    /**
     * Détermine le canal préféré pour la réinitialisation du mot de passe.
     * Email prioritaire (lien natif Laravel), WhatsApp en fallback.
     */
    public function preferredResetChannel(): string
    {
        return $this->hasEmail() ? 'email' : 'whatsapp';
    }

    // ─── Relations ───────────────────────────────────────────────────────────

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'group_members')
            ->withPivot('joined_at', 'left_at')
            ->withTimestamps();
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    public function passwordResetRequests(): HasMany
    {
        return $this->hasMany(PasswordResetRequest::class);
    }

    // ─── Filament ────────────────────────────────────────────────────────────

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->status === 'ACTIVE';
    }

    /**
     * Filament v4 utilise cette méthode pour afficher le nom de l'utilisateur.
     */
    public function getFilamentName(): string
    {
        return $this->full_name;
    }
}
