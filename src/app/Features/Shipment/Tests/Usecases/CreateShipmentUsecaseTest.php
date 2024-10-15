<?php

namespace App\Features\Shipment\Tests\Usecases;

use App\Features\Shipment\Models\Shipment;
use App\Features\Shipment\Models\ShipmentEvent;
use App\Features\Shipment\Usecases\CreateShipmentUsecase;
use Database\Seeders\TestSeeder;
use ErrorException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateShipmentUsecaseTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(TestSeeder::class);
    }

    public function testCreateShipmentSuccessfully()
    {
        $user = $this->getTestStaffUser();
        // Arrange: Create a new instance of CreateShipmentUsecase
        $usecase = new CreateShipmentUsecase(new Shipment(), new ShipmentEvent());

        // Input data
        $inputData = [
            'internal_reference_name' => 'test_shipment',
            'user_id' => $user->id,
            'events' => [
                'preparation' => [
                    'estimated_started_at' => '2024-10-01 00:00:00',
                    'estimated_completion_at' => '2024-10-02 00:00:00',
                    'actual_started_at' => '2024-10-01 00:00:00',
                    'actual_completion_at' => '2024-10-02 00:00:00',
                ],
                'port_departure' => [
                    'estimated_started_at' => '2024-10-02 00:00:00',
                    'estimated_completion_at' => '2024-10-03 00:00:00',
                ],
                'port_arrival' => [
                    'estimated_started_at' => now()->addDays(2),
                    'estimated_completion_at' => now()->addDays(3),
                ],
                'delivery' => [
                    'estimated_started_at' => now()->addDays(3),
                    'estimated_completion_at' => now()->addDays(4),
                ],
            ],
        ];

        // Act: Execute the use case
        $shipment = $usecase->execute($inputData);

        // Assert: Verify the shipment and its events are created correctly
        $this->assertDatabaseHas('shipments', [
            'internal_reference_name' => 'test_shipment',
        ]);

        $this->assertDatabaseHas('shipment_events', [
            'shipment_id' => $shipment->id,
            'event_type' => 'preparation',
            'estimated_started_at' => '2024-10-01 00:00:00',
            'estimated_completion_at' => '2024-10-02 00:00:00',
            'actual_started_at' => '2024-10-01 00:00:00',
            'actual_completion_at' => '2024-10-02 00:00:00',
        ]);

        $this->assertDatabaseHas('shipment_events', [
            'shipment_id' => $shipment->id,
            'event_type' => 'port_departure',
            'estimated_started_at' => '2024-10-02 00:00:00',
            'estimated_completion_at' => '2024-10-03 00:00:00',
            'actual_started_at' => null,
            'actual_completion_at' => null,
        ]);

        $this->assertDatabaseHas('shipment_events', [
            'shipment_id' => $shipment->id,
            'event_type' => 'port_arrival',
        ]);

        $this->assertDatabaseHas('shipment_events', [
            'shipment_id' => $shipment->id,
            'event_type' => 'delivery',
        ]);

        // Assert: Verify shipment has 4 events
        $this->assertCount(4, $shipment->shipmentEvents);
    }

    public function testCreateShipmentThrowsExceptionOnFailure()
    {
        // Arrange: Create a new instance of CreateShipmentUsecase
        $usecase = new CreateShipmentUsecase(new Shipment(), new ShipmentEvent());

        // Prepare input data with missing required fields to trigger an error
        $inputData = [
            'internal_reference_name' => 'invalid_shipment', // Missing 'user_id'
            'events' => [
                'preparation' => [
                    'estimated_started_at' => now(),
                    'estimated_completion_at' => now()->addDays(1),
                ]
            ],
        ];

        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('Undefined array key "user_id');

        // Act: Execute the use case, should throw exception
        $usecase->execute($inputData);
    }
}
