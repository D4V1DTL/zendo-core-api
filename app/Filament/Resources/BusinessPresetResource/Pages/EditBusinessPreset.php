<?php

namespace App\Filament\Resources\BusinessPresetResource\Pages;

use App\Filament\Resources\BusinessPresetResource;
use Filament\Resources\Pages\EditRecord;

class EditBusinessPreset extends EditRecord
{
    protected static string $resource = BusinessPresetResource::class;

    // Sin acción "Eliminar".
    protected function getHeaderActions(): array
    {
        return [];
    }
}
