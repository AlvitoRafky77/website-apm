<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUpRoles(): void
    {
        \Spatie\Permission\Models\Role::findOrCreate('admin');
        \Spatie\Permission\Models\Role::findOrCreate('b2c_customer');
        \Spatie\Permission\Models\Role::findOrCreate('b2b_customer');
    }
}
