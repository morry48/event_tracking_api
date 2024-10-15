<?php

namespace App\Features\Shipment\Tests\Controller;

use App\Features\Shipment\Enums\ShipmentGetTypeEnum;
use App\Features\Shipment\ShipmentPolicy;
use Database\Seeders\TestSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShipmentPolicyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(TestSeeder::class);
    }

    public function testMakePermissionTypeByStaffUser()
    {
        $user = $this->getTestStaffUser();
        $this->actingAs($user);
        $shipmentPolicy = new ShipmentPolicy();
        $getType = $shipmentPolicy->makePermissionTypeByUser($user);

        $this->assertEquals(ShipmentGetTypeEnum::VIEW_ALL_SHIPMENTS, $getType);
    }

    public function testMakePermissionTypeByWarehouseStaffUser()
    {
        $user = $this->getTestWarehouseStaffUser();
        $this->actingAs($user);
        $shipmentPolicy = new ShipmentPolicy();
        $getType = $shipmentPolicy->makePermissionTypeByUser($user);

        $this->assertEquals(ShipmentGetTypeEnum::VIEW_DELIVERY_ONLY, $getType);
    }

    public function testMakePermissionTypeByOwnerUser()
    {
        $user = $this->getTestOwnerUser();
        $this->actingAs($user);
        $shipmentPolicy = new ShipmentPolicy();
        $getType = $shipmentPolicy->makePermissionTypeByUser($user);

        $this->assertEquals(ShipmentGetTypeEnum::VIEW_OWN_SHIPMENTS, $getType);
    }
}