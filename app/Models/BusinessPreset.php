<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BusinessPreset extends Model
{
    /**
     * El ID es fijo (definido en App\Enums\BusinessPreset).
     */
    public $incrementing = false;

    protected $keyType = 'int';

    protected $fillable = [
        'id',
        'slug',
        'name',
        'description',
    ];

    public function modules(): BelongsToMany
    {
        return $this->belongsToMany(
            PlatformModule::class,
            'business_preset_modules',
            'business_preset_id',
            'platform_module_id'
        )
            ->withPivot('sort_order')
            ->orderByPivot('sort_order');
    }
}
