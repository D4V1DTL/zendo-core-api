<?php

namespace App\Enums;

/**
 * IDs fijos de las plantillas de negocio de Zendo.
 *
 * IMPORTANTE: nunca reutilizar un ID eliminado.
 * Cada vez que se agregue una plantilla nueva, crear un case aquí
 * y agregar el registro en BusinessPresetSeeder.
 */
enum BusinessPreset: int
{
    case Retail      = 1;
    case Restaurant  = 2;
    case Health      = 3;
    case Beauty      = 4;
    case Services    = 5;
    case Enterprise  = 6;

    public function slug(): string
    {
        return match($this) {
            self::Retail      => 'comercio',
            self::Restaurant  => 'restaurante',
            self::Health      => 'salud',
            self::Beauty      => 'belleza',
            self::Services    => 'servicios',
            self::Enterprise  => 'empresa',
        };
    }

    public function label(): string
    {
        return match($this) {
            self::Retail      => 'Bodega / Tienda',
            self::Restaurant  => 'Restaurante / Comida',
            self::Health      => 'Farmacia / Salud',
            self::Beauty      => 'Belleza / Cuidado',
            self::Services    => 'Servicios / Educación',
            self::Enterprise  => 'Empresa / Industria',
        };
    }

    public function description(): string
    {
        return match($this) {
            self::Retail      => 'Abarrotes, minimarket, tienda de retail',
            self::Restaurant  => 'Pollería, cafetería, fast food',
            self::Health      => 'Botica, clínica, consultorio médico',
            self::Beauty      => 'Peluquería, spa, salón de belleza',
            self::Services    => 'Consultoría, academia, educación',
            self::Enterprise  => 'Sucursales, manufactura, distribución',
        };
    }

    /**
     * Módulos por defecto (IDs de PlatformModule), en orden de prioridad.
     *
     * @return int[]
     */
    public function defaultModuleIds(): array
    {
        return match($this) {
            self::Retail      => [
                PlatformModule::Customers->value,
                PlatformModule::Sales->value,
                PlatformModule::Inventory->value,
            ],
            self::Restaurant  => [
                PlatformModule::Customers->value,
                PlatformModule::Sales->value,
                PlatformModule::Reports->value,
            ],
            self::Health      => [
                PlatformModule::Customers->value,
                PlatformModule::Sales->value,
                PlatformModule::Inventory->value,
                PlatformModule::Purchases->value,
            ],
            self::Beauty      => [
                PlatformModule::Customers->value,
                PlatformModule::Sales->value,
                PlatformModule::Reports->value,
            ],
            self::Services    => [
                PlatformModule::Customers->value,
                PlatformModule::Sales->value,
                PlatformModule::Reports->value,
            ],
            self::Enterprise  => [
                PlatformModule::Customers->value,
                PlatformModule::Sales->value,
                PlatformModule::Inventory->value,
                PlatformModule::Reports->value,
            ],
        };
    }
}
