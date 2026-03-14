<?php

namespace App\Filament\Resources\PlatformModuleResource\Pages;

use App\Filament\Resources\PlatformModuleResource;
use Filament\Resources\Pages\ListRecords;

class ListPlatformModules extends ListRecords
{
    protected static string $resource = PlatformModuleResource::class;

    // Sin botón "Crear nuevo" — los módulos solo se gestionan via seeder.
    protected function getHeaderActions(): array
    {
        return [];
    }
}
