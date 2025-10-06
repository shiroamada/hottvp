<?php

it('redirects from / to dashboard', function () {
    $response = $this->get('/');

    $response->assertStatus(302);
    $response->assertRedirect('/admin/dashboard');
});

