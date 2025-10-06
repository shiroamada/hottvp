<?php

namespace Tests\Feature;

use App\Models\Admin\AdminUser;
use App\Models\Admin\Level;
use App\Models\Assort;
use App\Models\Admin\Retail;
use Database\Factories\Admin\AdminUserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create a user with level_id 3 (National Agent)
    $this->admin = AdminUserFactory::new()->create(['level_id' => 3]);
    $this->actingAs($this->admin, 'admin');
    app()->setLocale('zh_CN'); // To use the 'assorts' and 'levels' tables
});

it('can display the costing index page', function () {
    // 1. Create Assorts
    $assort1 = Assort::factory()->create(['duration' => 30]);
    $assort2 = Assort::factory()->create(['duration' => 90]);

    // 2. Create Retail prices for the parent user (which is the admin user itself)
    Retail::create(['user_id' => $this->admin->id, 'assort_id' => $assort1->id, 'money' => 100]);
    Retail::create(['user_id' => $this->admin->id, 'assort_id' => $assort2->id, 'money' => 250]);

    // 3. Create assort_levels for various agent levels for the parent user
    // User's own cost (level 3)
    DB::table('assort_levels')->insert([
        ['user_id' => $this->admin->id, 'assort_id' => $assort1->id, 'level_id' => 3, 'money' => 50],
        ['user_id' => $this->admin->id, 'assort_id' => $assort2->id, 'level_id' => 3, 'money' => 120],
    ]);
    // Diamond agent (level 4)
    DB::table('assort_levels')->insert([
        ['user_id' => $this->admin->id, 'assort_id' => $assort1->id, 'level_id' => 4, 'money' => 60],
        ['user_id' => $this->admin->id, 'assort_id' => $assort2->id, 'level_id' => 4, 'money' => 150],
    ]);
    // Gold agent (level 5)
    DB::table('assort_levels')->insert([
        ['user_id' => $this->admin->id, 'assort_id' => $assort1->id, 'level_id' => 5, 'money' => 70],
        ['user_id' => $this->admin->id, 'assort_id' => $assort2->id, 'level_id' => 5, 'money' => 180],
    ]);
     // Silver agent (level 6)
    DB::table('assort_levels')->insert([
        ['user_id' => $this->admin->id, 'assort_id' => $assort1->id, 'level_id' => 6, 'money' => 80],
        ['user_id' => $this->admin->id, 'assort_id' => $assort2->id, 'level_id' => 6, 'money' => 200],
    ]);
    // Bronze agent (level 7)
    DB::table('assort_levels')->insert([
        ['user_id' => $this->admin->id, 'assort_id' => $assort1->id, 'level_id' => 7, 'money' => 90],
        ['user_id' => $this->admin->id, 'assort_id' => $assort2->id, 'level_id' => 7, 'money' => 220],
    ]);
    // Customized (level 8)
    DB::table('assort_levels')->insert([
        ['user_id' => $this->admin->id, 'assort_id' => $assort1->id, 'level_id' => 8, 'money' => 95],
        ['user_id' => $this->admin->id, 'assort_id' => $assort2->id, 'level_id' => 8, 'money' => 240],
    ]);


    $response = $this->get(route('admin.costing.index'));

    $response->assertOk();
    $response->assertViewIs('costing.index');
    $response->assertViewHas('costingData');
});

it('returns an error if any cost is not numeric', function () {
    $assort = Assort::factory()->create();
    $level = Level::create(['level_name' => 'Test Level', 'mini_amount' => 0]);

    // Setup base data for the update method
    Retail::create(['user_id' => $this->admin->id, 'assort_id' => $assort->id, 'money' => 100]);
    DB::table('assort_levels')->insert([
        ['user_id' => $this->admin->id, 'assort_id' => $assort->id, 'level_id' => 3, 'money' => 50],
        ['user_id' => $this->admin->id, 'assort_id' => $assort->id, 'level_id' => 4, 'money' => 60],
        ['user_id' => $this->admin->id, 'assort_id' => $assort->id, 'level_id' => 5, 'money' => 70],
        ['user_id' => $this->admin->id, 'assort_id' => $assort->id, 'level_id' => 6, 'money' => 80],
        ['user_id' => $this->admin->id, 'assort_id' => $assort->id, 'level_id' => 7, 'money' => 90],
        ['user_id' => $this->admin->id, 'assort_id' => $assort->id, 'level_id' => 8, 'money' => 95],
    ]);

    $invalidData = [
        'assort_id' => $assort->id,
        'retail_price' => 'not-a-number', // Invalid input
        'your_cost' => 50,
        'diamond_agent_cost' => 60,
        'gold_agent_cost' => 70,
        'silver_agent_cost' => 80,
        'bronze_agent_cost' => 90,
        'customized_minimum_cost' => 95,
    ];

    $response = $this->post(route('admin.costing.update'), $invalidData);

    $response->assertJson(['success' => false, 'message' => 'Price for retail price must be numeric.']);
});
