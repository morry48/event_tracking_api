<?php

namespace Tests;

use App\Features\Shipment\Models\Shipment;
use App\Features\User\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function getTestStaffUser()
    {
        return User::query()->where('email', 'staff_1@test.com')->first();
    }

    protected function getTestOwnerUser()
    {
        return User::query()->where('email', 'owner_1@test.com')->first();
    }

    protected function getTestWarehouseStaffUser()
    {
        return User::query()->where('email', 'warehouse_1@test.com')->first();
    }

    protected function getTestShipmentByStaff()
    {
        return Shipment::query()->where('internal_reference_name', 'shipment_1')->first();
    }

    protected function getTestShipmentByOwner()
    {
        return Shipment::query()->where('internal_reference_name', 'shipment_3')->first();
    }
}
