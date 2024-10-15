<?php

namespace App\Features\Shared\RolePermission\Enums;

class RoleEnum
{
    const STAFF = 'staff';
    const OWNER = 'owner';
    const WAREHOUSE_STAFF = 'warehouse_staff';

    public static function getAllRoles(): array
    {
        return [
            self::STAFF,
            self::OWNER,
            self::WAREHOUSE_STAFF,
        ];
    }
}
