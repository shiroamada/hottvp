<?php

use App\Models\Admin\AdminUser;

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('users can authenticate using the login screen', function () {
    $user = AdminUser::factory()->create();

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ])->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('users can not authenticate with invalid password', function () {
    $user = AdminUser::factory()->create();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ])->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

    $this->assertGuest();
});

test('users can logout', function () {
    $user = AdminUser::factory()->create();

    $response = $this->actingAs($user)->postJson('/logout');

    $this->assertGuest();
    $response->assertRedirect('/');
});
