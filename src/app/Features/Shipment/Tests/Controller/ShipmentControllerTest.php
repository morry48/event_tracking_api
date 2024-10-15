<?php

namespace App\Features\Shipment\Tests\Controller;

use Database\Seeders\TestSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ShipmentControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(TestSeeder::class);
    }

    public function testIndex()
    {
        $user = $this->getTestStaffUser();
        Sanctum::actingAs($user, ['*']);
        $response = $this->get('/api/shipments');
        $response->assertStatus(200);
    }

    public function testIndexNotLogin()
    {
        $response = $this->getJson('/api/shipments');
        $response->assertStatus(401);
    }

    public function testStore()
    {
        $user = $this->getTestStaffUser();
        Sanctum::actingAs($user, ['*']);
        $response = $this->postJson('/api/shipments', [
            'internal_reference_name' => 'create_shipment',
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
                    'estimated_started_at' => '2024-10-02 00:00:00',
                    'estimated_completion_at' => '2024-10-02 00:00:00',
                ],
                'delivery' => [
                    'estimated_started_at' => '2024-10-02 00:00:00',
                    'estimated_completion_at' => '2024-10-02 00:00:00',
                ],
            ],
        ]);
        $response->assertStatus(201);
    }

    public function testShowAsStaff()
    {
        $user = $this->getTestStaffUser();
        $staff_shipment_id = $this->getTestShipmentByStaff()->id;
        Sanctum::actingAs($user, ['*']);
        $response = $this->get("/api/shipments/{$staff_shipment_id}");
        $response->assertStatus(200);

        $owner_shipment_id = $this->getTestShipmentByOwner()->id;
        Sanctum::actingAs($user, ['*']);
        $response = $this->get("/api/shipments/{$owner_shipment_id}");
        $response->assertStatus(200);
    }


    public function testShowAsOwnerOnlySelf()
    {
        $user = $this->getTestOwnerUser();
        $owner_shipment_id = $this->getTestShipmentByOwner()->id;
        Sanctum::actingAs($user, ['*']);
        $response = $this->get("/api/shipments/{$owner_shipment_id}");
        $response->assertStatus(200);
    }

    public function testProhibitShowAsOwnerForOthers()
    {
        $user = $this->getTestOwnerUser();
        $staff_shipment_id = $this->getTestShipmentByStaff()->id;
        Sanctum::actingAs($user, ['*']);
        $response = $this->get("/api/shipments/{$staff_shipment_id}");
        $response->assertStatus(403);
    }

    public function testUpdateAsStaffAll()
    {
        $user = $this->getTestStaffUser();
        $staff_shipment_id = $this->getTestShipmentByStaff()->id;
        Sanctum::actingAs($user, ['*']);
        $response = $this->putJson("/api/shipments/{$staff_shipment_id}", [
            'internal_reference_name' => 'update_shipment',
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
                    'estimated_started_at' => '2024-10-02 00:00:00',
                    'estimated_completion_at' => '2024-10-02 00:00:00',
                ],
                'delivery' => [
                    'estimated_started_at' => '2024-10-02 00:00:00',
                    'estimated_completion_at' => '2024-10-02 00:00:00',
                ],
            ],
        ]);
        $response->assertStatus(200);

        $owner_shipment_id = $this->getTestShipmentByOwner()->id;
        Sanctum::actingAs($user, ['*']);
        $response = $this->putJson("/api/shipments/{$owner_shipment_id}", [
            'internal_reference_name' => 'update_shipment1',
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
                    'estimated_started_at' => '2024-10-02 00:00:00',
                    'estimated_completion_at' => '2024-10-02 00:00:00',
                ],
                'delivery' => [
                    'estimated_started_at' => '2024-10-02 00:00:00',
                    'estimated_completion_at' => '2024-10-02 00:00:00',
                ],
            ],
        ]);
        $response->assertStatus(200);
    }

    public function testProhibitUpdateAsOwnerForOthers()
    {
        $user = $this->getTestOwnerUser();
        $staff_shipment_id = $this->getTestShipmentByStaff()->id;
        Sanctum::actingAs($user, ['*']);
        $response = $this->putJson("/api/shipments/{$staff_shipment_id}", [
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
                    'estimated_started_at' => '2024-10-02 00:00:00',
                    'estimated_completion_at' => '2024-10-02 00:00:00',
                ],
                'delivery' => [
                    'estimated_started_at' => '2024-10-02 00:00:00',
                    'estimated_completion_at' => '2024-10-02 00:00:00',
                ],
            ],
        ]);
        $response->assertStatus(403);
    }

    public function testUpdateAsOwnerOnlySelf()
    {
        $user = $this->getTestOwnerUser();
        $owner_shipment_id = $this->getTestShipmentByOwner()->id;
        Sanctum::actingAs($user, ['*']);
        $response = $this->putJson("/api/shipments/{$owner_shipment_id}", [
            'internal_reference_name' => 'update_shipment',
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
                    'estimated_started_at' => '2024-10-02 00:00:00',
                    'estimated_completion_at' => '2024-10-02 00:00:00',
                ],
                'delivery' => [
                    'estimated_started_at' => '2024-10-02 00:00:00',
                    'estimated_completion_at' => '2024-10-02 00:00:00',
                ],
            ],
        ]);
        $response->assertStatus(200);
    }

    public function testUpdateAsWarehouseStaffOnlyDeliveryEvent()
    {
        $user = $this->getTestWarehouseStaffUser();
        $owner_shipment_id = $this->getTestShipmentByOwner()->id;
        Sanctum::actingAs($user, ['*']);
        $response = $this->putJson("/api/shipments/{$owner_shipment_id}", [
            'events' => [
                'delivery' => [
                    'estimated_started_at' => '2024-10-02 00:00:00',
                    'estimated_completion_at' => '2024-10-02 00:00:00',
                ],
            ],
        ]);
        $response->assertStatus(200);
    }

    public function testProhibitUpdateAsWarehouseStaffOtherEvents()
    {
        $user = $this->getTestWarehouseStaffUser();
        $owner_shipment_id = $this->getTestShipmentByOwner()->id;
        Sanctum::actingAs($user, ['*']);
        $response = $this->putJson("/api/shipments/{$owner_shipment_id}", [
            'events' => [
                'port_arrival' => [
                    'estimated_started_at' => '2024-10-02 00:00:00',
                    'estimated_completion_at' => '2024-10-02 00:00:00',
                ],
                'delivery' => [
                    'estimated_started_at' => '2024-10-02 00:00:00',
                    'estimated_completion_at' => '2024-10-02 00:00:00',
                ],
            ],
        ]);
        $response->assertStatus(403);
    }
}
