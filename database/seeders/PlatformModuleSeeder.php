<?php

namespace Database\Seeders;

use App\Enums\PlatformModule as PlatformModuleEnum;
use App\Models\PlatformModule;
use Illuminate\Database\Seeder;

class PlatformModuleSeeder extends Seeder
{
    /**
     * Los módulos se crean usando los IDs del enum PlatformModule.
     * Para agregar un módulo nuevo:
     *  1. Agrega el case en App\Enums\PlatformModule con el próximo ID libre.
     *  2. Agrega la entrada en el array de abajo.
     *  3. Corre: php artisan db:seed --class=PlatformModuleSeeder
     */
    public function run(): void
    {
        foreach (PlatformModuleEnum::cases() as $module) {
            PlatformModule::updateOrCreate(
                ['id' => $module->value],
                [
                    'slug'        => $module->slug(),
                    'name'        => $module->label(),
                    'description' => $module->description(),
                    'is_free'     => $module->isFree(),
                ]
            );
        }
    }
}
