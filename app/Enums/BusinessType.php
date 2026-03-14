<?php

namespace App\Enums;

enum BusinessType: string
{
    case Retail      = 'comercio';
    case Restaurant  = 'restaurante';
    case Beauty      = 'belleza';
    case Health      = 'salud';
    case Services    = 'servicios';
    case Enterprise  = 'empresa';

    public function label(): string
    {
        return match($this) {
            self::Retail      => 'Comercio y tiendas',
            self::Restaurant  => 'Restaurantes y comida',
            self::Beauty      => 'Belleza y cuidado personal',
            self::Health      => 'Salud y farmacia',
            self::Services    => 'Servicios y educación',
            self::Enterprise  => 'Empresas e industria',
        };
    }
}
