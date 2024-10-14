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

    protected function getTestShipment()
    {
        return Shipment::query()->first();
    }
}
