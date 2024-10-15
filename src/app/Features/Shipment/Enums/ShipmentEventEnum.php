<?php

namespace App\Features\Shipment\Enums;

class ShipmentEventEnum
{
    const PREPARATION = 'preparation';
    const PORT_DEPARTURE = 'port_departure';
    const PORT_ARRIVAL = 'port_arrival';
    const DELIVERY = 'delivery';

    /**
     *
     * @return array
     */
    public static function getAllEvents(): array
    {
        return [
            self::PREPARATION,
            self::PORT_DEPARTURE,
            self::PORT_ARRIVAL,
            self::DELIVERY,
        ];
    }
}
