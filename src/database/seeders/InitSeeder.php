<?php

namespace Database\Seeders;

use App\Features\Shipment\Models\Shipment;
use App\Features\Shipment\Models\ShipmentEvent;
use App\Features\User\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class InitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'id' => Str::uuid(),
            'name' => 'staff_1',
            'email' => 'staff_1@test.com',
            'password' => Hash::make('password'),  // パスワードをハッシュ化して保存
        ]);
        $shipment = Shipment::create([
            'id' => Str::uuid(),
            'internal_reference_name' => 'shipment_1',
            'user_id' => $user->id,
        ]);
        ShipmentEvent::create([
            'id' => Str::uuid(),
            'shipment_id' => $shipment->id,
            'event_type' => 'preparation',
            'estimated_started_at' => now(),
            'actual_started_at' => now(),
            'estimated_completion_at' => now(),
            'actual_completion_at' => now(),
        ]);
        ShipmentEvent::create([
            'id' => Str::uuid(),
            'shipment_id' => $shipment->id,
            'event_type' => 'port_departure',
            'estimated_started_at' => now(),
            'actual_started_at' => now(),
            'estimated_completion_at' => now(),
            'actual_completion_at' => now(),
        ]);
        ShipmentEvent::create([
            'id' => Str::uuid(),
            'shipment_id' => $shipment->id,
            'event_type' => 'port_arrival',
            'estimated_started_at' => now(),
            'actual_started_at' => now(),
            'estimated_completion_at' => now(),
            'actual_completion_at' => now(),
        ]);
        ShipmentEvent::create([
            'id' => Str::uuid(),
            'shipment_id' => $shipment->id,
            'event_type' => 'delivery',
            'estimated_started_at' => now(),
            'actual_started_at' => now(),
            'estimated_completion_at' => now(),
            'actual_completion_at' => now(),
        ]);
    }
}
