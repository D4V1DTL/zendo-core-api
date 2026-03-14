<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BusinessPresetResource\Pages;
use App\Filament\Resources\BusinessPresetResource\RelationManagers;
use App\Models\BusinessPreset;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BusinessPresetResource extends Resource
{
    protected static ?string $model           = BusinessPreset::class;
    protected static ?string $navigationIcon  = 'heroicon-o-squares-2x2';
    protected static ?string $navigationGroup = 'Plataforma';
    protected static ?int    $navigationSort  = 11;
    protected static ?string $label           = 'Plantilla';
    protected static ?string $pluralLabel     = 'Plantillas de negocio';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Identificación')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('id')
                        ->label('ID (fijo)')
                        ->disabled()
                        ->dehydrated(false)
                        ->helperText('Inmutable — definido en el enum BusinessPreset.'),

                    Forms\Components\TextInput::make('slug')
                        ->label('Slug')
                        ->disabled()
                        ->dehydrated(false)
                        ->helperText('Identificador de código. No editable.'),
                ]),

            Forms\Components\Section::make('Contenido')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Nombre de la plantilla')
                        ->required()
                        ->maxLength(100),

                    Forms\Components\Textarea::make('description')
                        ->label('Descripción')
                        ->required()
                        ->maxLength(255)
                        ->rows(2),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->width(60),

                Tables\Columns\TextColumn::make('name')
                    ->label('Plantilla')
                    ->searchable()
                    ->sortable()
                    ->description(fn (BusinessPreset $r) => $r->slug),

                Tables\Columns\TextColumn::make('description')
                    ->label('Descripción')
                    ->limit(55),

                Tables\Columns\TextColumn::make('modules_count')
                    ->label('Módulos')
                    ->counts('modules')
                    ->badge()
                    ->color('primary'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([])
            ->defaultSort('id');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ModulesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBusinessPresets::route('/'),
            'edit'  => Pages\EditBusinessPreset::route('/{record}/edit'),
        ];
    }
}
