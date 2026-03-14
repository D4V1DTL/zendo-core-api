<?php

namespace App\Models;

use App\Enums\BusinessMemberRole;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusinessInvitation extends Model
{
    protected $fillable = [
        'business_id', 'email', 'role', 'token',
        'invited_by', 'expires_at', 'accepted_at',
    ];

    protected function casts(): array
    {
        return [
            'role'        => BusinessMemberRole::class,
            'expires_at'  => 'datetime',
            'accepted_at' => 'datetime',
        ];
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function inviter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_by');
    }
}
