<?php

namespace Tests\Feature;

use App\Models\Admin\AdminUser;
use App\Models\Admin\Huobi;
use Database\Factories\Admin\AdminUserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = AdminUserFactory::new()->create();
    $this->actingAs($this->admin, 'admin');
});

it('can display the huobi index page', function () {
    $response = $this->get(route('admin.huobi.index'));

    $response->assertOk();
    $response->assertViewIs('admin.huobi.index');
});

it('can store a new huobi record', function () {
    $userData = AdminUserFactory::new()->create();
    $huobiData = [
        'user_id' => $userData->id,
        'event' => 'Test Event',
        'money' => 100.50,
        'status' => 1, // 1 for recharge
    ];

    $response = $this->post(route('admin.huobi.save'), $huobiData);

    $response->assertOk();
    $response->assertJson(['code' => 0]);
    $this->assertDatabaseHas('huobis', $huobiData);
});

it('can update an existing huobi record', function () {
    $userData = AdminUserFactory::new()->create();
    $huobi = Huobi::create([ 
        'user_id' => $userData->id,
        'event' => 'Old Event',
        'money' => 50.00,
        'status' => 0,
    ]);
    $newHuobiData = [
        'user_id' => $userData->id,
        'event' => 'Updated Event',
        'money' => 75.25,
        'status' => 1,
    ];

    $response = $this->put(route('admin.huobi.update', $huobi->id), $newHuobiData);

    $response->assertOk();
    $response->assertJson(['code' => 0]);
    $this->assertDatabaseHas('huobis', ['id' => $huobi->id, 'event' => 'Updated Event', 'money' => 75.25, 'status' => 1]);
    $this->assertDatabaseMissing('huobis', ['event' => 'Old Event']);
});

it('can delete a huobi record', function () {
    $userData = AdminUserFactory::new()->create();
    $huobi = Huobi::create([
        'user_id' => $userData->id,
        'event' => 'Event to Delete',
        'money' => 10.00,
        'status' => 0,
    ]);

    $response = $this->delete(route('admin.huobi.delete', $huobi->id));

    $response->assertOk();
    $response->assertJson(['code' => 0]);
    $this->assertDatabaseMissing('huobis', ['id' => $huobi->id]);
});
