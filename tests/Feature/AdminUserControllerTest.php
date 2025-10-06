<?php

namespace Tests\Feature;

use App\Models\Admin\AdminUser;
use App\Models\Admin\Level;
use Database\Factories\Admin\AdminUserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = AdminUserFactory::new()->create([
        'id' => 2, // Set ID to 2 to avoid super admin logic
        'password' => bcrypt('old_password'),
        'level_id' => 3, // Assuming level 3 is a common admin level
        'balance' => 1000,
        'type' => 1,
    ]);
    $this->actingAs($this->admin, 'admin');
    app()->setLocale('zh_CN'); // To use the 'levels' table

    // Create a level for the update logic
    Level::create(['id' => 3, 'level_name' => 'National Agent', 'mini_amount' => 100]);
});

it('can display the admin user index page', function () {
    $response = $this->get(route('admin.users.index'));

    $response->assertOk();
    $response->assertViewIs('admin.adminUser.index');
});

it('can display the create admin user page', function () {
    $response = $this->get(route('admin.users.create'));

    $response->assertOk();
    $response->assertViewIs('admin.adminUser.add');
});

it('can store a new admin user', function () {
    $this->admin->update(['balance' => 200]); // Ensure admin has enough balance

    $userData = [
        'name' => 'New Admin',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'level_id' => 3,
        'balance' => 150,
        'channel_id' => 1, // Assuming a default channel_id
        'type' => 1,
    ];

    $response = $this->post(route('admin.users.save'), $userData, ['X-Requested-With' => 'XMLHttpRequest']);

    $response->assertOk();
    $response->assertJson(['code' => 0]);
    $this->assertDatabaseHas('admin_users', ['name' => 'New Admin', 'level_id' => 3]);
    $this->assertDatabaseMissing('admin_users', ['password' => 'password123']); // Password should be hashed
});

it('can display the edit admin user page', function () {
    $userToEdit = AdminUserFactory::new()->create();

    $response = $this->get(route('admin.users.edit', $userToEdit->id));

    $response->assertOk();
    $response->assertViewIs('admin.adminUser.add');
    $response->assertSee($userToEdit->name);
});

it('can update an existing admin user', function () {
    $userToUpdate = AdminUserFactory::new()->create();
    $newUserData = [
        'name' => 'Updated Admin',
        'email' => 'updated@example.com',
        'phone' => '1234567890',
        'remark' => 'Updated remark',
        'balance' => 1000,
        'level_id' => 3,
    ];

    $response = $this->put(route('admin.users.update', $userToUpdate->id), $newUserData, ['X-Requested-With' => 'XMLHttpRequest']);

    $response->assertOk();
    $response->assertJson(['code' => 0]);
    $this->assertDatabaseHas('admin_users', ['id' => $userToUpdate->id, 'name' => 'Updated Admin', 'email' => 'updated@example.com']);
});

it('can delete an admin user', function () {
    $userToDelete = AdminUserFactory::new()->create();

    $response = $this->get(route('admin.users.delete', $userToDelete->id)); // Delete is a GET request in routes/admin.php

    $response->assertOk();
    $response->assertJson(['code' => 0]);
    $this->assertDatabaseMissing('admin_users', ['id' => $userToDelete->id]);
});

it('can display the user info page', function () {
    $response = $this->get(route('admin.users.userInfo'));

    $response->assertOk();
    $response->assertViewIs('profile.userInfo');
    $response->assertSee($this->admin->name);
});

it('can display the user edit page', function () {
    $response = $this->get(route('admin.users.userEdit'));

    $response->assertOk();
    $response->assertViewIs('profile.userEdit');
    $response->assertSee($this->admin->name);
});

it('can update the authenticated user\'s info', function () {
    $newInfo = [
        'name' => 'My Updated Name',
        'email' => 'myupdated@example.com',
        'phone' => '0987654321',
        'remark' => 'My updated remark',
    ];

    $response = $this->put(route('admin.users.userUpdate'), $newInfo, ['X-Requested-With' => 'XMLHttpRequest']);

    $response->assertOk();
    $response->assertJson(['code' => 0]);
    $this->admin->refresh();
    expect($this->admin->name)->toBe('My Updated Name');
    expect($this->admin->email)->toBe('myupdated@example.com');
});



it('can change the authenticated user\'s password', function () {
    $passwordData = [
        'old_password' => 'old_password',
        'password' => 'new_password123',
        'password_confirmation' => 'new_password123',
    ];

    $response = $this->post(route('admin.password.save'), $passwordData, ['X-Requested-With' => 'XMLHttpRequest']);

    $response->assertOk();
    $response->assertJson(['code' => 0]);
    $this->admin->refresh();
    expect(Hash::check('new_password123', $this->admin->password))->toBeTrue();
});
