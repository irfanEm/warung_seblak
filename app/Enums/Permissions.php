<?php

namespace App\Enums;

class Permissions
{
    // Admin Permissions
    public const MANAGE_MENUS = 'manage menus';
    public const MANAGE_CATEGORIES = 'manage categories';
    public const MANAGE_TOPPINGS = 'manage toppings';
    public const MANAGE_SPICINESS = 'manage spiciness';
    public const MANAGE_TABLES = 'manage tables';
    public const MANAGE_PROMOS = 'manage promos';
    public const MANAGE_ORDERS = 'manage orders';
    public const MANAGE_DRIVERS = 'manage drivers';
    public const VIEW_REPORTS = 'view reports';
    public const MANAGE_DELIVERY_SETTINGS = 'manage delivery settings';
    public const MANAGE_USERS = 'manage users';

    // Kasir Permissions
    public const POS_ACCESS = 'pos access';
    public const VIEW_ORDERS = 'view orders';
    public const VIEW_MENUS = 'view menus';

    // Dapur Permissions
    public const VIEW_KITCHEN = 'view kitchen';
    public const UPDATE_ORDER_STATUS = 'update order status';

    // Driver Permissions
    public const VIEW_ASSIGNED_ORDERS = 'view assigned orders';
    public const UPDATE_DELIVERY_STATUS = 'update delivery status';

    public static function all(): array
    {
        $reflectionClass = new \ReflectionClass(self::class);
        return array_values($reflectionClass->getConstants());
    }
}
