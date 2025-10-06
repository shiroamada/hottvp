<?php

use App\Models\Admin\AdminUser;
use App\Models\AuthCode;
use Database\Factories\Admin\AdminUserFactory;
use Database\Factories\AuthCodeFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use App\Repository\APIHelper;

uses(RefreshDatabase::class);

beforeEach(function () {
    \Log::info('Transaction active before test: ' . (DB::getPdo()->inTransaction() ? 'Yes' : 'No'));
    $this->admin = AdminUserFactory::new()->create(['level_id' => 1, 'type' => 1]);
    $this->actingAs($this->admin, 'admin');
});

afterEach(function () {
    \Log::info('Transaction active after test: ' . (DB::getPdo()->inTransaction() ? 'Yes' : 'No'));
    \Mockery::close();
    app()->forgetInstance(APIHelper::class);
});

test('trial code list page can be displayed', function () {
    // Create 3 trial codes (is_try = 2)
    AuthCodeFactory::new()->count(3)->create([
        'is_try' => 2,
        'user_id' => $this->admin->id,
    ]);

    // Create 2 license codes (is_try = 1)
    AuthCodeFactory::new()->count(2)->create([
        'is_try' => 1,
        'user_id' => $this->admin->id,
    ]);

    // Mock APIHelper if used in AuthCodeRepository::list
    $mock = $this->mock(APIHelper::class);
    $mock->shouldReceive('post')->andReturn(json_encode(['data' => []]));

    $response = $this->get(route('admin.try.list'));

    $response->assertOk();
    $response->assertViewIs('trial.list');
    $response->assertViewHas('lists', function ($lists) {
        return $lists->count() === 3;
    });
});

test('trial code generation page can be displayed', function () {
    $this->admin->update(['try_num' => 10]);

    // Add mock for safety
    $mock = $this->mock(APIHelper::class);
    $mock->shouldReceive('post')->andReturn(json_encode(['data' => []]));

    $response = $this->get(route('admin.try.add'));

    $response->assertOk();
    $response->assertViewIs('trial.generate');
    $response->assertViewHas('availableTrialCodes', 10);
});

test('can generate trial codes', function () {
    $this->admin->update(['try_num' => 10]);
    $this->assertDatabaseCount('auth_codes', 0);

    $mock = $this->mock(APIHelper::class);
    $mock->shouldReceive('post')->once()->andReturn(json_encode([
        'data' => ['TESTCODE1234', 'TESTCODE5678']
    ]));

    $response = $this->post(route('admin.try.hold'), [
        'number' => 2,
        'remark' => 'Test generation',
        '_token' => csrf_token(),
    ]);

    $response->assertOk();
    $response->assertJson(['code' => 0]);

    $this->admin->refresh();
    expect($this->admin->try_num)->toBe(8);

    $this->assertDatabaseCount('auth_codes', 2);
});

test('cannot generate more trial codes than available', function () {
    $this->admin->update(['try_num' => 1]);

    // Mock APIHelper to avoid unexpected calls
    $mock = $this->mock(APIHelper::class);
    $mock->shouldReceive('post')->never();

    $response = $this->post(route('admin.try.hold'), [
        'number' => 2,
        'remark' => 'Test generation',
        '_token' => csrf_token(),
    ]);

    $response->assertOk();
    $response->assertJson([
        'code' => 1,
        'msg' => trans('authCode.exceed_num'),
    ]);

    $this->admin->refresh();
    expect($this->admin->try_num)->toBe(1);

    $this->assertDatabaseCount('auth_codes', 0);
});

test('cannot generate trial codes if stock is insufficient', function () {
    $this->admin->update(['try_num' => 10]);
    $this->assertDatabaseCount('auth_codes', 0);

    $mock = $this->mock(APIHelper::class);
    $mock->shouldReceive('post')->once()->andReturn(json_encode([
        'data' => ['TESTCODE1234', 'TESTCODE5678', 'TESTCODE9012'] // 3 codes
    ]));

    $response = $this->post(route('admin.try.hold'), [
        'number' => 4, // Request 4 codes
        'remark' => 'Test generation',
        '_token' => csrf_token(),
    ]);

    $response->assertOk();
    $response->assertJson([
        'code' => 1,
        'msg' => trans('authCode.insufficient_pregenerated_codes'),
    ]);

    $this->admin->refresh();
    expect($this->admin->try_num)->toBe(10); // Unchanged
    $this->assertDatabaseCount('auth_codes', 0); // No codes saved
});