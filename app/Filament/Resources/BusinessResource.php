<?php

namespace App\Filament\Resources;

use App\Enums\BusinessMemberRole;
use App\Enums\BusinessStatus;
use App\Enums\BusinessType;
use App\Filament\Resources\BusinessResource\Pages;
use App\Filament\Resources\BusinessResource\RelationManagers;
use App\Models\Business;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class BusinessResource extends Resource
{
    protected static ?string $model           = Business::class;
    protected static ?string $navigationIcon  = 'heroicon-o-building-storefront';
    protected static ?string $navigationGroup = 'Administracion';
    protected static ?int    $navigationSort  = 2;
    protected static ?string $label           = 'Negocio';
    protected static ?string $pluralLabel     = 'Negocios';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informacion del negocio')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Nombre del negocio')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (Forms\Set $set, ?string $state) =>
                            $set('slug', Str::slug($state ?? ''))
                        ),

                    Forms\Components\TextInput::make('slug')
                        ->label('Slug (URL)')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),

                    Forms\Components\TextInput::make('ruc')
                        ->label('RUC')
                        ->maxLength(11)
                        ->unique(ignoreRecord: true),

                    Forms\Components\TextInput::make('phone')
                        ->label('Teléfono')
                        ->maxLength(20),

                    Forms\Components\TextInput::make('address')
                        ->label('Dirección')
                        ->maxLength(255)
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Configuracion')
                ->columns(2)
                ->schema([
                    Forms\Components\Select::make('owner_id')
                        ->label('Propietario')
                        ->options(User::pluck('email', 'id'))
                        ->searchable()
                        ->required(),

                    Forms\Components\Select::make('type')
                        ->label('Tipo de negocio')
                        ->options(collect(BusinessType::cases())
                            ->mapWithKeys(fn ($t) => [$t->value => $t->label()]))
                        ->required(),

                    Forms\Components\Select::make('status')
                        ->label('Estado')
                        ->options(collect(BusinessStatus::cases())
                            ->mapWithKeys(fn ($s) => [$s->value => $s->label()]))
                        ->required()
                        ->default(BusinessStatus::Active->value),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Negocio')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Business $b) => $b->slug),

                Tables\Columns\TextColumn::make('owner.name')
                    ->label('Propietario')
                    ->searchable()
                    ->description(fn (Business $b) => $b->owner?->email),

                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->formatStateUsing(fn (BusinessType $state) => $state->label())
                    ->color('primary'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn (BusinessStatus $state) => $state->label())
                    ->color(fn (BusinessStatus $state) => match($state) {
                        BusinessStatus::Active    => 'success',
                        BusinessStatus::Inactive  => 'warning',
                        BusinessStatus::Suspended => 'danger',
                    }),

                Tables\Columns\TextColumn::make('businessMembers_count')
                    ->label('Miembros')
                    ->counts('businessMembers')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('ruc')
                    ->label('RUC')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->date('d/m/Y')
                    ->sortable()
                    ->since(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Tipo')
                    ->options(collect(BusinessType::cases())
                        ->mapWithKeys(fn ($t) => [$t->value => $t->label()])),

                Tables\Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->options(collect(BusinessStatus::cases())
                        ->mapWithKeys(fn ($s) => [$s->value => $s->label()])),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\MembersRelationManager::class,
            RelationManagers\ModulesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListBusinesses::route('/'),
            'create' => Pages\CreateBusiness::route('/create'),
            'edit'   => Pages\EditBusiness::route('/{record}/edit'),
        ];
    }
}
