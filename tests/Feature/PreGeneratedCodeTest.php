<?php

use App\Models\Admin\AdminUser;
use App\Models\PreGeneratedCode;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = AdminUser::factory()->create();
    $this->actingAs($this->admin, 'admin');
});

test('import page can be displayed', function () {
    $response = $this->get(route('admin.pre_generated_codes.create'));

    $response->assertStatus(200);
    $response->assertViewHas('types', PreGeneratedCode::TYPES);
    $response->assertViewHas('vendors', PreGeneratedCode::VENDORS);
});

test('can import pre-generated codes', function () {
    $codesToImport = "CODE1\nCODE2\nCODE3";
    $data = [
        'codes' => $codesToImport,
        'type' => '30days',
        'vendor' => 'hottv',
        'remark' => 'Test import',
    ];

    $response = $this->post(route('admin.pre_generated_codes.store'), $data);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('pre_generated_codes', [
        'code' => 'CODE1',
        'type' => '30days',
        'vendor' => 'hottv',
        'remark' => 'Test import',
        'imported_by' => $this->admin->id,
        'assort_level_id' => null,
    ]);
    $this->assertDatabaseCount('pre_generated_codes', 3);
});

test('does not import duplicate codes', function () {
    PreGeneratedCode::factory()->create(['code' => 'EXISTING_CODE', 'imported_by' => $this->admin->id]);

    $codesToImport = "CODE1\nEXISTING_CODE\nCODE2";
    $data = [
        'codes' => $codesToImport,
        'type' => '30days',
        'vendor' => 'hottv',
    ];

    $this->post(route('admin.pre_generated_codes.store'), $data);

    $this->assertDatabaseCount('pre_generated_codes', 3);
    $this->assertDatabaseHas('pre_generated_codes', ['code' => 'CODE1']);
    $this->assertDatabaseHas('pre_generated_codes', ['code' => 'CODE2']);
});

test('import fails validation with missing data', function () {
    $response = $this->post(route('admin.pre_generated_codes.store'), []);

    $response->assertSessionHasErrors(['codes', 'type', 'vendor']);
});

test('index page can be displayed', function () {
    PreGeneratedCode::factory()->count(5)->create(['imported_by' => $this->admin->id]);

    $response = $this->get(route('admin.pre_generated_codes.index'));

    $response->assertStatus(200);
    $response->assertViewHas('codes');
    $viewCodes = $response->viewData('codes');
    expect($viewCodes)->toHaveCount(5);
});

test('can filter codes by status', function () {
    PreGeneratedCode::factory()->create(['code' => 'AVAILABLE_CODE', 'requested_at' => null, 'imported_by' => $this->admin->id]);
    $requester = AdminUser::factory()->create();
    PreGeneratedCode::factory()->create(['code' => 'REQUESTED_CODE', 'requested_at' => now(), 'requested_by' => $requester->id, 'imported_by' => $this->admin->id]);

    // Filter for available
    $response = $this->get(route('admin.pre_generated_codes.index', ['status' => 'available']));
    $response->assertSee('AVAILABLE_CODE');
    $response->assertDontSee('REQUESTED_CODE');

    // Filter for requested
    $response = $this->get(route('admin.pre_generated_codes.index', ['status' => 'requested']));
    $response->assertSee('REQUESTED_CODE');
    $response->assertDontSee('AVAILABLE_CODE');
});

