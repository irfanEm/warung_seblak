<?php

namespace App\Enums;

enum Permissions: string
{
    // Admin Permissions
    case MANAGE_MENUS = 'manage menus';
    case MANAGE_CATEGORIES = 'manage categories';
    case MANAGE_TOPPINGS = 'manage toppings';
    case MANAGE_SPICINESS = 'manage spiciness';
    case MANAGE_TABLES = 'manage tables';
    case MANAGE_PROMOS = 'manage promos';
    case MANAGE_ORDERS = 'manage orders';
    case MANAGE_DRIVERS = 'manage drivers';
    case VIEW_REPORTS = 'view reports';
    case MANAGE_DELIVERY_SETTINGS = 'manage delivery settings';
    case MANAGE_USERS = 'manage users';

    // Kasir Permissions
    case POS_ACCESS = 'pos access';
    case VIEW_ORDERS = 'view orders';
    case VIEW_MENUS = 'view menus';

    // Dapur Permissions
    case VIEW_KITCHEN = 'view kitchen';
    case UPDATE_ORDER_STATUS = 'update order status';

    // Driver Permissions
    case VIEW_ASSIGNED_ORDERS = 'view assigned orders';
    case UPDATE_DELIVERY_STATUS = 'update delivery status';

    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }
}
