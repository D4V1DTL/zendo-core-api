<?php

namespace App\Enums;

/**
 * IDs fijos de los módulos de la plataforma Zendo.
 *
 * IMPORTANTE: nunca reutilizar un ID eliminado.
 * Cada vez que se agregue un módulo nuevo, crear un case aquí
 * y agregar el registro en PlatformModuleSeeder.
 */
enum PlatformModule: int
{
    case Customers    = 1;
    case Sales        = 2;
    case Inventory    = 3;
    case Purchases    = 4;
    case Reports      = 5;
    case Invoicing    = 6;
    case Employees    = 7;
    case CashRegister = 8;
    case Quotes       = 9;
    case Suppliers    = 10;
    case Appointments = 11;
    case Marketing    = 12;
    case Delivery     = 13;
    case Loyalty      = 14;
    case Tables       = 15;
    case Accounting   = 16;
    case Discounts    = 17;

    public function slug(): string
    {
        return match($this) {
            self::Customers    => 'clientes',
            self::Sales        => 'ventas',
            self::Inventory    => 'inventario',
            self::Purchases    => 'compras',
            self::Reports      => 'reportes',
            self::Invoicing    => 'facturacion',
            self::Employees    => 'empleados',
            self::CashRegister => 'caja',
            self::Quotes       => 'cotizaciones',
            self::Suppliers    => 'proveedores',
            self::Appointments => 'agenda',
            self::Marketing    => 'marketing',
            self::Delivery     => 'delivery',
            self::Loyalty      => 'fidelizacion',
            self::Tables       => 'mesas',
            self::Accounting   => 'contabilidad',
            self::Discounts    => 'descuentos',
        };
    }

    public function label(): string
    {
        return match($this) {
            self::Customers    => 'Clientes',
            self::Sales        => 'Ventas',
            self::Inventory    => 'Inventario',
            self::Purchases    => 'Compras',
            self::Reports      => 'Reportes',
            self::Invoicing    => 'Facturación electrónica',
            self::Employees    => 'Empleados',
            self::CashRegister => 'Caja registradora',
            self::Quotes       => 'Cotizaciones',
            self::Suppliers    => 'Proveedores',
            self::Appointments => 'Agenda y citas',
            self::Marketing    => 'Marketing',
            self::Delivery     => 'Delivery',
            self::Loyalty      => 'Fidelización',
            self::Tables       => 'Mesas y comandas',
            self::Accounting   => 'Contabilidad',
            self::Discounts    => 'Descuentos y cupones',
        };
    }

    public function description(): string
    {
        return match($this) {
            self::Customers    => 'Gestiona tu cartera de clientes',
            self::Sales        => 'Registra ventas y cobros',
            self::Inventory    => 'Control de stock y productos',
            self::Purchases    => 'Pedidos a proveedores',
            self::Reports      => 'Análisis y estadísticas',
            self::Invoicing    => 'Boletas y facturas electrónicas SUNAT',
            self::Employees    => 'Gestión de personal y roles',
            self::CashRegister => 'Control de apertura y cierre de caja',
            self::Quotes       => 'Genera cotizaciones y presupuestos',
            self::Suppliers    => 'Directorio y gestión de proveedores',
            self::Appointments => 'Agenda de citas y reservas',
            self::Marketing    => 'Campañas y comunicación con clientes',
            self::Delivery     => 'Gestión de pedidos y repartos',
            self::Loyalty      => 'Puntos, stamps y recompensas',
            self::Tables       => 'Gestión de mesas y órdenes en salón',
            self::Accounting   => 'Ingresos, egresos y balance',
            self::Discounts    => 'Cupones, promos y descuentos',
        };
    }

    public function isFree(): bool
    {
        return match($this) {
            self::Customers, self::Sales, self::Inventory,
            self::Purchases,  self::Reports => true,
            default => false,
        };
    }
}
