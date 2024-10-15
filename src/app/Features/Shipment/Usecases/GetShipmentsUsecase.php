<?php

namespace App\Features\Shipment\Usecases;


use App\Features\Shipment\Enums\ShipmentEventEnum;
use App\Features\Shipment\Enums\ShipmentGetTypeEnum;
use App\Features\Shipment\Models\Shipment;

class GetShipmentsUsecase
{
    protected Shipment $shipment;

    public function __construct(Shipment $shipment)
    {
        $this->shipment = $shipment;
    }

    /**
     * @param string $user_id
     * @param $getType
     * @return array
     */
    public function execute(string $user_id, $getType): array
    {
        // eager loading the shipment events for n + 1 problem
        if ($getType === ShipmentGetTypeEnum::VIEW_ALL_SHIPMENTS) {
            $shipments = Shipment::with('shipmentEvents')->get();
        } elseif ($getType === ShipmentGetTypeEnum::VIEW_OWN_SHIPMENTS) {
            $shipments = Shipment::with('shipmentEvents')->where('user_id', $user_id)->get();
        } elseif ($getType === ShipmentGetTypeEnum::VIEW_DELIVERY_ONLY) {
            $shipments = Shipment::with([
                'shipmentEvents' => function ($query) {
                    $query->where('event_type', ShipmentEventEnum::DELIVERY);
                }
            ])->get();
        } else {
            return [];
        }


        return $shipments->map(function ($shipment) {
            $events = $shipment->shipmentEvents->map(function ($event) {
                return [
                    'event_type' => $event->event_type,
                    'actual_started_at' => $event->actual_started_at,
                    'actual_completion_at' => $event->actual_completion_at,
                ];
            });

            return [
                'id' => $shipment->id,
                'internal_reference_name' => $shipment->internal_reference_name,
                'user_id' => $shipment->user_id,
                'events' => $events,
            ];
        })->toArray();
    }
}
