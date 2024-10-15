<?php

namespace App\Features\Shipment\Tests\Usecases;

use App\Features\Shipment\Enums\ShipmentGetTypeEnum;
use App\Features\Shipment\Models\Shipment;
use App\Features\Shipment\Usecases\GetShipmentDetailsUsecase;
use Database\Seeders\TestSeeder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetShipmentDetailsUsecaseTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(TestSeeder::class);
    }

    public function testGetShipmentDetailsSuccessfullyForAllEvent()
    {
        $usecase = new GetShipmentDetailsUsecase(new Shipment());

        $shipment = $this->getTestShipmentByStaff();
        $shipmentDetails = $usecase->execute($shipment->id, ShipmentGetTypeEnum::VIEW_ALL_SHIPMENTS);

        $this->assertEquals($shipment->id, $shipmentDetails['id']);
        $this->assertEquals($shipment->internal_reference_name, $shipmentDetails['internal_reference_name']);
        $this->assertCount(4, $shipmentDetails['events']);
        $preparationEvent = $shipmentDetails['events']['preparation'];
        $this->assertNotNull($preparationEvent['estimated_started_at']);
        $this->assertNotNull($preparationEvent['actual_started_at']);
        $this->assertNotNull($preparationEvent['estimated_completion_at']);
        $this->assertNotNull($preparationEvent['actual_completion_at']);
    }

    public function testGetShipmentDetailsSuccessfullyForWarehouseStaff()
    {
        $usecase = new GetShipmentDetailsUsecase(new Shipment());

        $shipment = $this->getTestShipmentByStaff();
        $shipmentDetails = $usecase->execute($shipment->id, ShipmentGetTypeEnum::VIEW_DELIVERY_ONLY);

        $this->assertEquals($shipment->id, $shipmentDetails['id']);
        $this->assertEquals($shipment->internal_reference_name, $shipmentDetails['internal_reference_name']);
        $this->assertCount(1, $shipmentDetails['events']);
        $preparationEvent = $shipmentDetails['events']['delivery'];
        $this->assertNotNull($preparationEvent['estimated_started_at']);
        $this->assertNotNull($preparationEvent['actual_started_at']);
        $this->assertNotNull($preparationEvent['estimated_completion_at']);
        $this->assertNotNull($preparationEvent['actual_completion_at']);
    }

    public function testShipmentNotFoundThrowsException()
    {
        $usecase = new GetShipmentDetailsUsecase(new Shipment());

        $this->expectException(ModelNotFoundException::class);

        $usecase->execute('nonexistent_id', ShipmentGetTypeEnum::VIEW_DELIVERY_ONLY);
    }
}
