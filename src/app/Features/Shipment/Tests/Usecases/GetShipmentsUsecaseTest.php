<?php

namespace App\Features\Shipment\Tests\Usecases;

use App\Features\Shipment\Enums\ShipmentGetTypeEnum;
use App\Features\Shipment\Models\Shipment;
use App\Features\Shipment\Usecases\GetShipmentsUsecase;
use Database\Seeders\TestSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetShipmentsUsecaseTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(TestSeeder::class);
    }

    public function testAllGetShipmentsSuccessfullyForStaff()
    {
        $usecase = new GetShipmentsUsecase(new Shipment());

        $user = $this->getTestStaffUser();

        $shipments = $usecase->execute($user->id, ShipmentGetTypeEnum::VIEW_ALL_SHIPMENTS);
        $this->assertCount(3, $shipments);
        foreach ($shipments as $shipment) {
            $this->assertNotNull($shipment['id']);
            $this->assertNotNull($shipment['internal_reference_name']);
            $this->assertNotNull($shipment['user_id']);
            $this->assertCount(4, $shipment['events']);
        }
    }

    public function testGetOnlyOwnShipmentsSuccessfullyForOwner()
    {
        $usecase = new GetShipmentsUsecase(new Shipment());

        $user = $this->getTestOwnerUser();

        $shipments = $usecase->execute($user->id, ShipmentGetTypeEnum::VIEW_OWN_SHIPMENTS);
        $this->assertCount(1, $shipments);
        foreach ($shipments as $shipment) {
            $this->assertNotNull($shipment['id']);
            $this->assertNotNull($shipment['internal_reference_name']);
            $this->assertNotNull($shipment['user_id']);
            $this->assertCount(4, $shipment['events']);
        }
    }

    public function testGetOnlyDeliveryEventShipmentsSuccessfullyForWarehouseStaff()
    {
        $usecase = new GetShipmentsUsecase(new Shipment());

        $user = $this->getTestOwnerUser();

        $shipments = $usecase->execute($user->id, ShipmentGetTypeEnum::VIEW_DELIVERY_ONLY);
        $this->assertCount(3, $shipments);
        foreach ($shipments as $shipment) {
            $this->assertNotNull($shipment['id']);
            $this->assertNotNull($shipment['internal_reference_name']);
            $this->assertNotNull($shipment['user_id']);
            $this->assertCount(1, $shipment['events']);
            $this->assertSame('delivery', $shipment['events'][0]['event_type']);
        }
    }
}