<?php

namespace Database\Seeders;

use App\Features\Shipment\Models\Shipment;
use App\Features\Shipment\Models\ShipmentEvent;
use App\Features\User\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'id' => 'ec654765-c040-49ff-8d71-4e77cebaab5e',
            'name' => 'staff_1',
            'email' => 'staff_1@test.com',
            'password' => Hash::make('password'),
        ]);
        $shipment_1 = Shipment::create([
            'id' => Str::uuid(),
            'internal_reference_name' => 'shipment_1',
            'user_id' => $user->id,
        ]);
        ShipmentEvent::create([
            'id' => Str::uuid(),
            'shipment_id' => $shipment_1->id,
            'event_type' => 'preparation',
            'estimated_started_at' => now(),
            'actual_started_at' => now(),
            'estimated_completion_at' => now(),
            'actual_completion_at' => now(),
        ]);
        ShipmentEvent::create([
            'id' => Str::uuid(),
            'shipment_id' => $shipment_1->id,
            'event_type' => 'port_departure',
            'estimated_started_at' => now(),
            'actual_started_at' => now(),
            'estimated_completion_at' => now(),
            'actual_completion_at' => now(),
        ]);
        ShipmentEvent::create([
            'id' => Str::uuid(),
            'shipment_id' => $shipment_1->id,
            'event_type' => 'port_arrival',
            'estimated_started_at' => now(),
            'actual_started_at' => now(),
            'estimated_completion_at' => now(),
            'actual_completion_at' => now(),
        ]);
        ShipmentEvent::create([
            'id' => Str::uuid(),
            'shipment_id' => $shipment_1->id,
            'event_type' => 'delivery',
            'estimated_started_at' => now(),
            'actual_started_at' => now(),
            'estimated_completion_at' => now(),
            'actual_completion_at' => now(),
        ]);
        $shipment_2 = Shipment::create([
            'id' => Str::uuid(),
            'internal_reference_name' => 'shipment_2',
            'user_id' => $user->id,
        ]);
        ShipmentEvent::create([
            'id' => Str::uuid(),
            'shipment_id' => $shipment_2->id,
            'event_type' => 'preparation',
            'estimated_started_at' => now(),
            'actual_started_at' => now(),
            'estimated_completion_at' => now(),
            'actual_completion_at' => now(),
        ]);
        ShipmentEvent::create([
            'id' => Str::uuid(),
            'shipment_id' => $shipment_2->id,
            'event_type' => 'port_departure',
            'estimated_started_at' => now(),
            'actual_started_at' => now(),
            'estimated_completion_at' => now(),
            'actual_completion_at' => now(),
        ]);
        ShipmentEvent::create([
            'id' => Str::uuid(),
            'shipment_id' => $shipment_2->id,
            'event_type' => 'port_arrival',
            'estimated_started_at' => now(),
            'actual_started_at' => now(),
            'estimated_completion_at' => now(),
            'actual_completion_at' => now(),
        ]);
        ShipmentEvent::create([
            'id' => Str::uuid(),
            'shipment_id' => $shipment_2->id,
            'event_type' => 'delivery',
            'estimated_started_at' => now(),
            'actual_started_at' => now(),
            'estimated_completion_at' => now(),
            'actual_completion_at' => now(),
        ]);
    }
}
