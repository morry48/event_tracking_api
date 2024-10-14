<?php

namespace App\Features\Shipment\Usecases;

use App\Features\Shipment\Models\Shipment;

class GetShipmentDetailsUsecase
{

    private Shipment $shipment;

    public function __construct(
        Shipment $shipment
    ) {
        $this->shipment = $shipment;
    }

    /**
     * @param string $id
     * @return array
     */
    public function execute(string $id): array
    {
        $shipment = $this->shipment->with('shipmentEvents')->findOrFail($id);

        $events = $shipment->shipmentEvents->groupBy('event_type')->map(function ($eventGroup) {
            $event = $eventGroup->first();
            return [
                'estimated_started_at' => $event->estimated_started_at,
                'actual_started_at' => $event->actual_started_at,
                'estimated_completion_at' => $event->estimated_completion_at,
                'actual_completion_at' => $event->actual_completion_at,
            ];
        });

        return [
            'id' => $shipment->id,
            'internal_reference_name' => $shipment->internal_reference_name,
            'user_id' => $shipment->user_id,
            'events' => $events,
        ];
    }
}