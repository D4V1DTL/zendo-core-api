<?php

namespace App\Models;

use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser, MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name',
        'apellido_paterno',
        'apellido_materno',
        'celular',
        'email',
        'password',
        'role',
        'email_verified_at',
        'email_verification_token',
        'email_verification_expires_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'email_verification_token',
        'email_verification_expires_at',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at'             => 'datetime',
            'email_verification_expires_at' => 'datetime',
            'password'                      => 'hashed',
            'role'                          => UserRole::class,
        ];
    }

    /** Genera un token de verificación válido por 12 días. */
    public function generateVerificationToken(): string
    {
        $token = Str::random(64);
        $this->update([
            'email_verification_token'          => $token,
            'email_verification_expires_at'     => Carbon::now()->addDays(12),
        ]);
        return $token;
    }

    public function isVerificationTokenExpired(): bool
    {
        return $this->email_verification_expires_at
            && $this->email_verification_expires_at->isPast();
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->isAdmin();
    }

    public function fullName(): string
    {
        return trim("{$this->name} {$this->apellido_paterno} {$this->apellido_materno}");
    }
}
