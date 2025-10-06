<?php

namespace Tests\Feature;

use App\Models\Admin\AdminUser;
use Database\Factories\Admin\AdminUserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Assort;
use App\Models\AssortLevel;
use App\Repository\APIHelper;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = AdminUserFactory::new()->create(['id' => 1]);
    $this->actingAs($this->admin, 'admin');
});

it('can display the license code list page', function () {
    $response = $this->get(route('admin.license.list'));

    $response->assertOk();
    $response->assertViewIs('license.list');
});

it('can display the license code generation page', function () {
    $response = $this->get(route('admin.license.generate'));

    $response->assertOk();
    $response->assertViewIs('license.generate');
});

it('can generate license codes', function () {
    app()->setLocale('zh_CN');

    // 1. Setup
    $user = AdminUserFactory::new()->create([
        'level_id' => 4,
        'balance' => 100.00,
        'type' => 1,
    ]);
    $this->actingAs($user, 'admin');

    $assort = Assort::factory()->create();

    AssortLevel::create([
        'user_id' => $user->id,
        'level_id' => 4,
        'assort_id' => $assort->id,
        'money' => 10.00,
    ]);

    $this->mock(APIHelper::class)
        ->shouldReceive('post')
        ->once()
        ->andReturn(json_encode([
            'data' => ['CODE12345678', 'CODE87654321']
        ]));

    // 2. Action
    $response = $this->post(route('admin.code.save'), [
        'assort_id' => $assort->id,
        'number' => 2,
        'mini_money' => 10.00, // This seems to be a required parameter from my previous reading of the save method
        'remark' => 'Test generation',
    ]);

    // 3. Assertions
    $response->assertOk();
    $response->assertJson(['code' => 0]);

    $this->assertDatabaseCount('auth_codes', 2);

    $user->refresh();
    expect($user->balance)->toEqual(80.00);

    $this->assertDatabaseCount('huobis', 1);
});
