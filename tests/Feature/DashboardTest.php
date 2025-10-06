<?php

use App\Models\Admin\AdminUser;
use Database\Factories\Admin\AdminUserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery as M;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create and authenticate a user
    $this->admin = AdminUserFactory::new()->create(['level_id' => 1]); // level_id 1 for superadmin
    $this->actingAs($this->admin, 'admin');
});

afterEach(function () {
    M::close();
    // Clean up container bindings for all mocked classes
    app()->instance('App\Repository\Admin\AuthCodeRepository', null);
    app()->instance('App\Repository\Admin\HuobiRepository', null);
    app()->instance('App\Repository\Admin\AdminUserRepository', null);
    app()->instance('App\Repository\Admin\EquipmentRepository', null);
});

test('dashboard page can be displayed', function () {
    $this->withoutExceptionHandling();

    // Mock repositories using container bindings
    $authCodeRepoMock = M::mock('App\Repository\Admin\AuthCodeRepository')->makePartial();
    $this->app->instance('App\Repository\Admin\AuthCodeRepository', $authCodeRepoMock);
    $authCodeRepoMock->shouldReceive('lowerByCode')->andReturn(0);
    $authCodeRepoMock->shouldReceive('countByCode')->andReturn(0);

    $huobiRepoMock = M::mock('App\Repository\Admin\HuobiRepository')->makePartial();
    $this->app->instance('App\Repository\Admin\HuobiRepository', $huobiRepoMock);
    $huobiRepoMock->shouldReceive('expendByHuobi')->andReturn(0);
    $huobiRepoMock->shouldReceive('lowerByAddProfit')->andReturn(0);
    $huobiRepoMock->shouldReceive('lowerByProfit')->andReturn(0);
    $huobiRepoMock->shouldReceive('lowerByCode')->andReturn(0);

    $adminUserRepoMock = M::mock('App\Repository\Admin\AdminUserRepository')->makePartial();
    $this->app->instance('App\Repository\Admin\AdminUserRepository', $adminUserRepoMock);
    $adminUserRepoMock->shouldReceive('getDataByWhere')->andReturn(collect([]));
    $adminUserRepoMock->shouldReceive('getIdsByWhere')->andReturn(collect([]));
    $adminUserRepoMock->shouldReceive('find')->andReturn($this->admin);

    $equipmentRepoMock = M::mock('App\Repository\Admin\EquipmentRepository')->makePartial();
    $this->app->instance('App\Repository\Admin\EquipmentRepository', $equipmentRepoMock);
    $equipmentRepoMock->shouldReceive('findByWhere')->andReturn(null);

    $response = $this->get(route('admin.dashboard'));

    $response->assertStatus(200);
    $response->assertViewIs('admin.dashboard');
    $response->assertViewHasAll([
        'balance',
        'monthlyGeneratedCurrentMonth',
        'generatedLastMonth',
        'totalGeneratedQuantity',
        'usageHotcoinLastMonth',
        'thisMonthProfit',
        'lastMonthProfit',
        'totalProfit',
        'totalMembers',
        'activationCodePresets'
    ]);
});