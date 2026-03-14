<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PlatformModule extends Model
{
    /**
     * El ID es fijo (definido en App\Enums\PlatformModule).
     * No se usa auto-increment para mantener IDs estables.
     */
    public $incrementing = false;

    protected $keyType = 'int';

    protected $fillable = [
        'id',
        'slug',
        'name',
        'description',
        'is_free',
    ];

    protected $casts = [
        'is_free' => 'boolean',
    ];

    public function businessPresets(): BelongsToMany
    {
        return $this->belongsToMany(
            BusinessPreset::class,
            'business_preset_modules',
            'platform_module_id',
            'business_preset_id'
        );
    }
}
