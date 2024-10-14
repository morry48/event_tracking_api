<?php

namespace App\Features\Shipment\Usecases;

use App\Features\Shipment\Models\Shipment;
use App\Features\Shipment\Models\ShipmentEvent;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class UpdateShipmentEventsUsecase
{
    private Shipment $shipment;
    private ShipmentEvent $shipmentEvent;

    public function __construct(
        Shipment $shipment,
        ShipmentEvent $shipmentEvent
    ) {
        $this->shipment = $shipment;
        $this->shipmentEvent = $shipmentEvent;
    }

    /**
     * @param array $inputData Contains shipment ID, event data, and optional internal_reference_name.
     * @return Shipment
     */
    public function execute(array $inputData): Shipment
    {
        $shipment = $this->shipment->findOrFail($inputData['id']);

        // Update the internal reference name if it is provided
        if (isset($inputData['internal_reference_name'])) {
            $shipment->internal_reference_name = $inputData['internal_reference_name'];
            $shipment->save();
        }

        if (isset($inputData['events'])) {
            $eventsData = $inputData['events'];

            foreach ($eventsData as $eventType => $eventDetails) {
                $event = $this->shipmentEvent->where('shipment_id', $shipment->id)
                    ->where('event_type', $eventType)
                    ->first();

                if (!$event) {
                    throw new ModelNotFoundException("Shipment event with type '{$eventType}' not found.");
                }

                $event->update([
                    'actual_started_at' => $eventDetails['actual_started_at'] ?? $event->actual_started_at,
                    'actual_completion_at' => $eventDetails['actual_completion_at'] ?? $event->actual_completion_at,
                    'estimated_started_at' => $eventDetails['estimated_started_at'] ?? $event->estimated_started_at,
                    'estimated_completion_at' => $eventDetails['estimated_completion_at'] ?? $event->estimated_completion_at,
                ]);
            }
        }
        return $shipment->load('shipmentEvents'); // Load the related shipment events as well
    }
}