<?php

namespace App\Features\Shipment\Usecases;

use App\Features\Shipment\Exception\SaveShipmentException;
use App\Features\Shipment\Models\Shipment;
use App\Features\Shipment\Models\ShipmentEvent;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateShipmentUsecase
{
    protected Shipment $shipment;
    private ShipmentEvent $shipmentEvent;

    public function __construct(
        Shipment $shipment,
        ShipmentEvent $shipmentEvent
    ) {
        $this->shipment = $shipment;
        $this->shipmentEvent = $shipmentEvent;
    }

    /**
     * @param array $inputData
     * @return Shipment
     * @throws SaveShipmentException
     */
    public function execute(array $inputData): Shipment
    {
        try {
            // start transaction
            return DB::transaction(function () use ($inputData) {
                $shipment = $this->shipment->create([
                    'id' => Str::uuid(),
                    'internal_reference_name' => $inputData['internal_reference_name'],
                    'user_id' => $inputData['user_id'],
                ]);

                if (!$shipment) {
                    throw new SaveShipmentException('Failed to create shipment.');
                }

                foreach ($inputData['events'] as $eventType => $eventData) {
                    $event = $shipment->shipmentEvents()->create([
                        'id' => Str::uuid(),
                        'shipment_id' => $shipment->id,
                        'event_type' => $eventType,
                        'estimated_started_at' => $eventData['estimated_started_at'] ?? null,
                        'estimated_completion_at' => $eventData['estimated_completion_at'] ?? null,
                        'actual_started_at' => $eventData['actual_started_at'] ?? null,
                        'actual_completion_at' => $eventData['actual_completion_at'] ?? null,
                    ]);

                    if (!$event) {
                        throw new SaveShipmentException('Failed to create event: ' . $eventType);
                    }
                }

                return $shipment->load('shipmentEvents');
            });
        } catch (QueryException $e) {
            throw new SaveShipmentException('Database error occurred: ' . $e->getMessage());
        }
    }
}
