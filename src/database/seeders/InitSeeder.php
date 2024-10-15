<?php

namespace Database\Seeders;

use App\Features\Shared\RolePermission\Enums\PermissionEnum;
use App\Features\Shared\RolePermission\Enums\RoleEnum;
use App\Features\Shared\RolePermission\Model\Permission;
use App\Features\Shared\RolePermission\Model\Role;
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
        $permissions = PermissionEnum::getAllPermissions();

        // パーミッションをデータベースに挿入
        foreach ($permissions as $permission) {
            Permission::create([
                'id' => Str::uuid(),
                'name' => $permission,
            ]);
        }

        // RoleEnumを使用してロールを定義し、それぞれに対応する権限を定義
        $roles = [
            RoleEnum::STAFF => [PermissionEnum::VIEW_ALL_SHIPMENTS, PermissionEnum::VIEW_OWN_SHIPMENTS],
            RoleEnum::OWNER => [PermissionEnum::VIEW_OWN_SHIPMENTS],
            RoleEnum::WAREHOUSE_STAFF => [PermissionEnum::VIEW_DELIVERY_ONLY],
        ];

        // 各ロールを作成し、対応するパーミッションを関連付け
        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::create([
                'id' => Str::uuid(),
                'name' => $roleName,
            ]);

            foreach ($rolePermissions as $permissionName) {
                $permission = Permission::where('name', $permissionName)->first();
                $role->permissions()->attach($permission);
            }
        }
        $staff_user = User::create([
            'id' => Str::uuid(),
            'name' => 'staff_1',
            'email' => 'staff_1@test.com',
            'password' => Hash::make('password'),
            'role_id' => Role::where('name', RoleEnum::STAFF)->first()->id,
        ]);
        $shipment = Shipment::create([
            'id' => Str::uuid(),
            'internal_reference_name' => 'shipment_1',
            'user_id' => $staff_user->id,
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


        $shipment_2 = Shipment::create([
            'id' => Str::uuid(),
            'internal_reference_name' => 'shipment_2',
            'user_id' => $staff_user->id,
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


        $owner_user = User::create([
            'id' => Str::uuid(),
            'name' => 'owner_1',
            'email' => 'owner_1@test.com',
            'password' => Hash::make('password'),
            'role_id' => Role::where('name', RoleEnum::OWNER)->first()->id,
        ]);
        $shipment_3 = Shipment::create([
            'id' => Str::uuid(),
            'internal_reference_name' => 'shipment_3',
            'user_id' => $owner_user->id,
        ]);
        ShipmentEvent::create([
            'id' => Str::uuid(),
            'shipment_id' => $shipment_3->id,
            'event_type' => 'preparation',
            'estimated_started_at' => now(),
            'actual_started_at' => now(),
            'estimated_completion_at' => now(),
            'actual_completion_at' => now(),
        ]);
        ShipmentEvent::create([
            'id' => Str::uuid(),
            'shipment_id' => $shipment_3->id,
            'event_type' => 'port_departure',
            'estimated_started_at' => now(),
            'actual_started_at' => now(),
            'estimated_completion_at' => now(),
            'actual_completion_at' => now(),
        ]);
        ShipmentEvent::create([
            'id' => Str::uuid(),
            'shipment_id' => $shipment_3->id,
            'event_type' => 'port_arrival',
            'estimated_started_at' => now(),
            'actual_started_at' => now(),
            'estimated_completion_at' => now(),
            'actual_completion_at' => now(),
        ]);
        ShipmentEvent::create([
            'id' => Str::uuid(),
            'shipment_id' => $shipment_3->id,
            'event_type' => 'delivery',
            'estimated_started_at' => now(),
            'actual_started_at' => now(),
            'estimated_completion_at' => now(),
            'actual_completion_at' => now(),
        ]);
    }
}
