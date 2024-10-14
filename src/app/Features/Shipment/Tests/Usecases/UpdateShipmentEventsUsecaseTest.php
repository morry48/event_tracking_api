<?php


namespace App\Features\Shipment\Tests\Usecases;

use App\Features\Shipment\Models\Shipment;
use App\Features\Shipment\Models\ShipmentEvent;
use App\Features\Shipment\Usecases\UpdateShipmentEventsUsecase;
use Carbon\Carbon;
use Database\Seeders\TestSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class UpdateShipmentEventsUsecaseTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(TestSeeder::class);
    }

    public function testUpdateShipmentEventsSuccessfully()
    {
        $user = $this->getTestStaffUser();
        $shipment = Shipment::query()->create([
            'id' => Str::uuid(),
            'internal_reference_name' => 'original_shipment',
            'user_id' => $user->id,
        ]);
        ShipmentEvent::query()->create([
            'id' => Str::uuid(),
            'shipment_id' => $shipment->id,
            'event_type' => 'preparation',
            'estimated_started_at' => '2024-10-01 00:00:00',
            'estimated_completion_at' => '2024-10-02 00:00:00',
        ]);

        $usecase = new UpdateShipmentEventsUsecase(new Shipment(), new ShipmentEvent());
        $inputData = [
            'id' => $shipment->id,
            'internal_reference_name' => 'updated_shipment',
            'events' => [
                'preparation' => [
                    'estimated_completion_at' => '2024-10-13 21:51:27',
                    'actual_started_at' => '2024-10-12 21:51:27',
                ],
            ],
        ];

        // Act: Execute the use case
        $updatedShipment = $usecase->execute($inputData);

        // Assert: Verify the event was updated in the database
        $updatedEvent = ShipmentEvent::where('shipment_id', $shipment->id)
            ->where('event_type', 'preparation')
            ->first();

        // Use Carbon to compare the datetime values
        $this->assertEquals('updated_shipment', $updatedShipment->internal_reference_name);
        // The estimated_started_at value was not provided in the input data, so it should remain the same
        $this->assertEquals(Carbon::parse('2024-10-01 00:00:00'), $updatedEvent->estimated_started_at);
        // The estimated_completion_at value was provided in the input data, so it should be updated
        $this->assertEquals(Carbon::parse('2024-10-13 21:51:27'), $updatedEvent->estimated_completion_at);
        // The actual_started_at value was provided in the input data, so it should be updated
        $this->assertEquals(Carbon::parse('2024-10-12 21:51:27'), $updatedEvent->actual_started_at);
        // The actual_completion_at value was not provided in the input data, so it should remain null
        $this->assertEquals(null, $updatedEvent->actual_completion_at);
    }
}