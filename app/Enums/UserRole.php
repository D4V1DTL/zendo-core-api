<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin   = 'admin';
    case Cliente = 'cliente';

    public function label(): string
    {
        return match($this) {
            UserRole::Admin   => 'Administrador',
            UserRole::Cliente => 'Cliente',
        };
    }

    public function color(): string
    {
        return match($this) {
            UserRole::Admin   => 'danger',
            UserRole::Cliente => 'success',
        };
    }
}
