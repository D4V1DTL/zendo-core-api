<?php

namespace App\Models;

use App\Enums\BusinessMemberRole;
use App\Enums\BusinessStatus;
use App\Enums\BusinessType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\PlatformModule;

class Business extends Model
{
    protected $fillable = [
        'owner_id', 'name', 'slug', 'ruc', 'phone',
        'address', 'type', 'status', 'logo_url',
    ];

    protected function casts(): array
    {
        return [
            'type'   => BusinessType::class,
            'status' => BusinessStatus::class,
        ];
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'business_members')
            ->withPivot(['role', 'invited_by', 'joined_at'])
            ->withTimestamps();
    }

    public function businessMembers(): HasMany
    {
        return $this->hasMany(BusinessMember::class);
    }

    public function invitations(): HasMany
    {
        return $this->hasMany(BusinessInvitation::class);
    }

    public function modules(): BelongsToMany
    {
        return $this->belongsToMany(
            PlatformModule::class,
            'business_modules',
            'business_id',
            'platform_module_id'
        )->withPivot('sort_order')->orderByPivot('sort_order');
    }
}
