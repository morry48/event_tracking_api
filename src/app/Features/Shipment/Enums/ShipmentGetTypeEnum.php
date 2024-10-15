<?php

namespace App\Features\Shipment\Enums;

class ShipmentGetTypeEnum
{
    const VIEW_ALL_SHIPMENTS = 'view_all_shipments';
    const VIEW_OWN_SHIPMENTS = 'view_own_shipments';
    const VIEW_DELIVERY_ONLY = 'view_delivery_only';

    /**
     * @return array
     */
    public static function getAllPermissions(): array
    {
        return [
            self::VIEW_ALL_SHIPMENTS,
            self::VIEW_OWN_SHIPMENTS,
            self::VIEW_DELIVERY_ONLY,
        ];
    }
}