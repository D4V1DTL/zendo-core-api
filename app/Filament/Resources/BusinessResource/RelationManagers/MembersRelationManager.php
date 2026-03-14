<?php

namespace App\Filament\Resources\BusinessResource\RelationManagers;

use App\Enums\BusinessMemberRole;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class MembersRelationManager extends RelationManager
{
    protected static string $relationship = 'businessMembers';
    protected static ?string $title       = 'Miembros del negocio';
    protected static ?string $label       = 'Miembro';
    protected static ?string $pluralLabel = 'Miembros';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('user_id')
                ->label('Usuario')
                ->options(User::pluck('email', 'id'))
                ->searchable()
                ->required(),

            Forms\Components\Select::make('role')
                ->label('Rol en el negocio')
                ->options(collect(BusinessMemberRole::cases())
                    ->mapWithKeys(fn ($r) => [$r->value => $r->label()]))
                ->required()
                ->default(BusinessMemberRole::Cashier->value),

            Forms\Components\DateTimePicker::make('joined_at')
                ->label('Fecha de ingreso')
                ->default(now()),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nombre')
                    ->searchable()
                    ->description(fn ($record) => $record->user?->email),

                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('role')
                    ->label('Rol')
                    ->badge()
                    ->formatStateUsing(fn (BusinessMemberRole $state) => $state->label())
                    ->color(fn (BusinessMemberRole $state) => $state->color()),

                Tables\Columns\IconColumn::make('user.email_verified_at')
                    ->label('Email verificado')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('joined_at')
                    ->label('Se unió')
                    ->date('d/m/Y')
                    ->since()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->label('Rol')
                    ->options(collect(BusinessMemberRole::cases())
                        ->mapWithKeys(fn ($r) => [$r->value => $r->label()])),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Agregar miembro')
                    ->successNotification(
                        Notification::make()->title('Miembro agregado')->success()
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Cambiar rol'),
                Tables\Actions\DeleteAction::make()->label('Remover'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Remover seleccionados'),
                ]),
            ])
            ->defaultSort('joined_at', 'desc');
    }
}
