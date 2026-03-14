<?php

namespace App\Filament\Resources\BusinessPresetResource\Pages;

use App\Filament\Resources\BusinessPresetResource;
use Filament\Resources\Pages\ListRecords;

class ListBusinessPresets extends ListRecords
{
    protected static string $resource = BusinessPresetResource::class;

    // Sin botón "Crear" — las plantillas solo se crean via seeder.
    protected function getHeaderActions(): array
    {
        return [];
    }
}
