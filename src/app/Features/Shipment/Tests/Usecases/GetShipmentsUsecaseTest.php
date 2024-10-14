<?php

namespace App\Features\Shipment\Tests\Usecases;

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

    public function testGetShipmentsSuccessfully()
    {
        $usecase = new GetShipmentsUsecase(new Shipment());

        $shipments = $usecase->execute();

        $this->assertCount(2, $shipments);
        $shipment1 = $shipments->firstWhere('internal_reference_name', 'shipment_1');
        $this->assertNotNull($shipment1);
        $this->assertEquals('shipment_1', $shipment1['internal_reference_name']);
        $this->assertCount(4, $shipment1['events']);
        $shipment2 = $shipments->firstWhere('internal_reference_name', 'shipment_2');
        $this->assertNotNull($shipment2);
        $this->assertEquals('shipment_2', $shipment2['internal_reference_name']);
        $this->assertCount(4, $shipment2['events']);
    }
}