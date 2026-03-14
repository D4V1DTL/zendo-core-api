<?php

namespace App\Filament\Resources\BusinessResource\RelationManagers;

use App\Models\PlatformModule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ModulesRelationManager extends RelationManager
{
    protected static string  $relationship         = 'modules';
    protected static ?string $title                = 'Módulos instalados';
    protected static ?string $label                = 'Módulo';
    protected static ?string $pluralLabel          = 'Módulos';
    protected static ?string $recordTitleAttribute = 'name';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Hidden::make('sort_order')
                ->default(99),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->columns([
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('#')
                    ->width(50),

                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->width(60),

                Tables\Columns\TextColumn::make('name')
                    ->label('Módulo')
                    ->searchable()
                    ->description(fn (PlatformModule $r) => $r->slug),

                Tables\Columns\TextColumn::make('description')
                    ->label('Descripción')
                    ->limit(55),

                Tables\Columns\IconColumn::make('is_free')
                    ->label('Gratis')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-lock-closed')
                    ->trueColor('success')
                    ->falseColor('warning'),
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->label('Agregar módulo')
                    ->preloadRecordSelect()
                    ->recordSelectOptionsQuery(fn ($query) => $query->orderBy('id'))
                    ->recordSelectSearchColumns(['name', 'description'])
                    ->after(function (Tables\Actions\AttachAction $action) {
                        $business  = $this->getOwnerRecord();
                        $lastOrder = $business->modules()
                            ->max('sort_order') ?? 0;
                        $moduleId  = $action->getRecord()?->id;

                        if ($moduleId) {
                            $business->modules()->updateExistingPivot($moduleId, [
                                'sort_order' => $lastOrder + 1,
                            ]);
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\DetachAction::make()
                    ->label('Desinstalar'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make()->label('Desinstalar seleccionados'),
                ]),
            ]);
    }
}
