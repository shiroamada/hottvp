<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config()->set('session.driver', 'array');
        \Illuminate\Support\Facades\Session::start();
    }

    protected function tearDown(): void
    {
        // Reset container bindings for commonly mocked classes
        app()->instance(\App\Repository\APIHelper::class, null);
        app()->instance('App\Repository\Admin\AuthCodeRepository', null);
        app()->instance('App\Repository\Admin\HuobiRepository', null);
        app()->instance('App\Repository\Admin\AdminUserRepository', null);
        app()->instance('App\Repository\Admin\EquipmentRepository', null);
        \Mockery::close();
        parent::tearDown();
    }
}