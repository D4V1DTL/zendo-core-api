<?php

namespace App\Models;

use App\Enums\BusinessMemberRole;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusinessMember extends Model
{
    protected $fillable = [
        'business_id', 'user_id', 'role', 'invited_by', 'joined_at',
    ];

    protected function casts(): array
    {
        return [
            'role'      => BusinessMemberRole::class,
            'joined_at' => 'datetime',
        ];
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function invitedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_by');
    }
}
