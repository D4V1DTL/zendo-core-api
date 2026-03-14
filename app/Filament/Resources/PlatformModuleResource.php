<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlatformModuleResource\Pages;
use App\Models\PlatformModule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PlatformModuleResource extends Resource
{
    protected static ?string $model           = PlatformModule::class;
    protected static ?string $navigationIcon  = 'heroicon-o-puzzle-piece';
    protected static ?string $navigationGroup = 'Plataforma';
    protected static ?int    $navigationSort  = 10;
    protected static ?string $label           = 'Módulo';
    protected static ?string $pluralLabel     = 'Módulos';

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
                        ->helperText('El ID es inmutable y está definido en el enum PlatformModule.'),

                    Forms\Components\TextInput::make('slug')
                        ->label('Slug')
                        ->disabled()
                        ->dehydrated(false)
                        ->helperText('Identificador de código. No editable.'),
                ]),

            Forms\Components\Section::make('Contenido')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Nombre')
                        ->required()
                        ->maxLength(100),

                    Forms\Components\Textarea::make('description')
                        ->label('Descripción')
                        ->required()
                        ->maxLength(255)
                        ->rows(2),

                    Forms\Components\Toggle::make('is_free')
                        ->label('¿Gratuito?')
                        ->helperText('Desactivar para marcar como módulo de pago (Plan Pro).'),
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
                    ->label('Módulo')
                    ->searchable()
                    ->sortable()
                    ->description(fn (PlatformModule $r) => $r->slug),

                Tables\Columns\TextColumn::make('description')
                    ->label('Descripción')
                    ->limit(60),

                Tables\Columns\IconColumn::make('is_free')
                    ->label('Gratuito')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-lock-closed')
                    ->trueColor('success')
                    ->falseColor('warning'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([])
            ->defaultSort('id');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPlatformModules::route('/'),
            'edit'  => Pages\EditPlatformModule::route('/{record}/edit'),
        ];
    }
}
