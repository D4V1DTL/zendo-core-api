<?php

namespace App\Filament\Resources;

use App\Enums\UserRole;
use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon  = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Administracion';
    protected static ?int    $navigationSort  = 1;
    protected static ?string $label           = 'Usuario';
    protected static ?string $pluralLabel     = 'Usuarios';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informacion personal')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Nombre completo')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('email')
                        ->label('Correo electronico')
                        ->email()
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),

                    Forms\Components\TextInput::make('password')
                        ->label('Contrasena')
                        ->password()
                        ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                        ->dehydrated(fn ($state) => filled($state))
                        ->required(fn (string $context) => $context === 'create')
                        ->helperText('Dejar vacio para mantener la actual')
                        ->maxLength(255),

                    Forms\Components\DateTimePicker::make('email_verified_at')
                        ->label('Email verificado')
                        ->helperText('Dejar vacio si no esta verificado'),
                ]),

            Forms\Components\Section::make('Rol y acceso')
                ->schema([
                    Forms\Components\Select::make('role')
                        ->label('Rol')
                        ->options(collect(UserRole::cases())->mapWithKeys(
                            fn (UserRole $role) => [$role->value => $role->label()]
                        ))
                        ->required()
                        ->default(UserRole::Cliente->value)
                        ->helperText('Admin: accede al panel. Cliente: usa la app.'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('role')
                    ->label('Rol')
                    ->badge()
                    ->formatStateUsing(fn (UserRole $state) => $state->label())
                    ->color(fn (UserRole $state) => $state->color()),

                Tables\Columns\IconColumn::make('email_verified_at')
                    ->label('Verificado')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Registro')
                    ->date('d/m/Y H:i')
                    ->sortable()
                    ->since(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->label('Rol')
                    ->options(collect(UserRole::cases())->mapWithKeys(
                        fn (UserRole $role) => [$role->value => $role->label()]
                    )),

                Tables\Filters\TernaryFilter::make('email_verified_at')
                    ->label('Email verificado')
                    ->nullable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('verify')
                    ->label('Verificar email')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->hidden(fn (User $record) => $record->email_verified_at !== null)
                    ->action(function (User $record) {
                        $record->update(['email_verified_at' => now()]);
                        Notification::make()
                            ->title('Email verificado correctamente')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\DeleteAction::make()
                    ->hidden(fn (User $record) => $record->id === Auth::id()),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit'   => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
