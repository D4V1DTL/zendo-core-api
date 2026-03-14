<?php

namespace App\Filament\Resources\PlatformModuleResource\Pages;

use App\Filament\Resources\PlatformModuleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPlatformModule extends EditRecord
{
    protected static string $resource = PlatformModuleResource::class;

    // Sin acción "Eliminar" — los módulos no se pueden borrar desde Filament.
    protected function getHeaderActions(): array
    {
        return [];
    }
}
