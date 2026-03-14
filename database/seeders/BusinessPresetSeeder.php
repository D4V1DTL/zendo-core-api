<?php

namespace Database\Seeders;

use App\Enums\BusinessPreset as BusinessPresetEnum;
use App\Models\BusinessPreset;
use Illuminate\Database\Seeder;

class BusinessPresetSeeder extends Seeder
{
    /**
     * Para agregar una plantilla nueva:
     *  1. Agrega el case en App\Enums\BusinessPreset con el próximo ID libre.
     *  2. Define slug(), label(), description() y defaultModuleIds() en el enum.
     *  3. Corre: php artisan db:seed --class=BusinessPresetSeeder
     */
    public function run(): void
    {
        foreach (BusinessPresetEnum::cases() as $preset) {
            $record = BusinessPreset::updateOrCreate(
                ['id' => $preset->value],
                [
                    'slug'        => $preset->slug(),
                    'name'        => $preset->label(),
                    'description' => $preset->description(),
                ]
            );

            // Sincronizar módulos con sort_order según el orden del array.
            $moduleIds = $preset->defaultModuleIds();
            $syncData  = [];

            foreach ($moduleIds as $index => $moduleId) {
                $syncData[$moduleId] = ['sort_order' => $index + 1];
            }

            $record->modules()->sync($syncData);
        }
    }
}
