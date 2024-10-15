<?php

namespace App\Features\Shipment;

use App\Features\Shared\RolePermission\Enums\PermissionEnum;
use App\Features\Shared\RolePermission\Enums\RoleEnum;
use App\Features\Shipment\Enums\ShipmentEventEnum;
use App\Features\Shipment\Models\Shipment;
use App\Features\User\Models\User;

class ShipmentPolicy
{
    public function makePermissionTypeByUser(User $user): ?string
    {
        if ($user->hasPermission(PermissionEnum::VIEW_ALL_SHIPMENTS)) {
            return PermissionEnum::VIEW_ALL_SHIPMENTS;
        }

        if ($user->hasPermission(PermissionEnum::VIEW_OWN_SHIPMENTS)) {
            return PermissionEnum::VIEW_OWN_SHIPMENTS;
        }

        if ($user->hasPermission(PermissionEnum::VIEW_DELIVERY_ONLY)) {
            return PermissionEnum::VIEW_DELIVERY_ONLY;
        }

        return null;
    }

    /**
     * Determine if the user can view the shipment.
     *
     * @param User $user
     * @param string $shipment_user_id
     * @return bool
     */
    public function hasAccessToShipment(User $user, string $shipment_user_id): bool
    {
        // Staff can view all shipments
        if ($user->hasPermission(PermissionEnum::VIEW_ALL_SHIPMENTS)) {
            return true;
        }

        // todo fix view conditions for warehouse staff(users that only has access to a limited amount of shipments, and can only see)
        if ($user->role->name === RoleEnum::WAREHOUSE_STAFF) {
            return true;
        }

        // Owners can view only their own shipments
        if ($user->role->name === RoleEnum::OWNER) {
            return $user->id === $shipment_user_id;
        }

        // Otherwise, deny access
        return false;
    }

    /**
     *
     * @param User $user
     * @param array $inputData
     * @return bool
     */
    public function CanUpdateToShipment(User $user, array $inputData): bool
    {
        if ($user->role->name === RoleEnum::STAFF) {
            return true;
        }

        if ($user->role->name === RoleEnum::OWNER &&
            $user->id === Shipment::query()->find($inputData['id'])->user_id) {
            return true;
        }

        // Warehouse staff can update only delivery events
        if ($user->role->name === RoleEnum::WAREHOUSE_STAFF) {
            foreach ($inputData['events'] as $eventType => $event) {
                if ($eventType !== ShipmentEventEnum::DELIVERY) {
                    return false;
                }
            }
            return true;
        }

        // Otherwise, deny access
        return false;
    }
}
