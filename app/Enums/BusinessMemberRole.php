<?php

namespace App\Enums;

enum BusinessMemberRole: string
{
    case Owner      = 'owner';
    case Admin      = 'admin';
    case Accountant = 'accountant';
    case Cashier    = 'cashier';
    case Viewer     = 'viewer';

    public function label(): string
    {
        return match($this) {
            self::Owner      => 'Propietario',
            self::Admin      => 'Administrador',
            self::Accountant => 'Contador',
            self::Cashier    => 'Cajero',
            self::Viewer     => 'Solo lectura',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Owner      => 'warning',
            self::Admin      => 'primary',
            self::Accountant => 'info',
            self::Cashier    => 'success',
            self::Viewer     => 'gray',
        };
    }
}
