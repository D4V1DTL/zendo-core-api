<?php

namespace App\Filament\Widgets;

use App\Enums\UserRole;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Total usuarios', User::count())
                ->description('Registrados en el sistema')
                ->icon('heroicon-o-users')
                ->color('primary'),

            Stat::make('Clientes', User::where('role', UserRole::Cliente)->count())
                ->description('Registrados via app')
                ->icon('heroicon-o-user')
                ->color('success'),

            Stat::make('Administradores', User::where('role', UserRole::Admin)->count())
                ->description('Acceso al panel')
                ->icon('heroicon-o-shield-check')
                ->color('danger'),

            Stat::make('Sin verificar', User::whereNull('email_verified_at')->count())
                ->description('Email pendiente')
                ->icon('heroicon-o-envelope')
                ->color('warning'),
        ];
    }
}
