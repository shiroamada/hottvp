<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
// use Illuminate\Foundation\Testing\RefreshDatabase;
// use Illuminate\Foundation\Testing\WithoutMiddleware; // Commented out to allow session middleware to run

abstract class TestCase extends BaseTestCase
{
    // use RefreshDatabase;
    // // use WithoutMiddleware; // Commented out to allow session middleware to run // Commented out to allow session middleware to run

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure session is started for tests that require it
        // This is often needed when WithoutMiddleware is used, but session is still required
        // for authentication, validation errors, etc.
        config()->set('session.driver', 'array');
        \Illuminate\Support\Facades\Session::start();

        // If you want to disable exception handling for all tests, keep this line.
        // Otherwise, remove it to allow exceptions to be thrown during tests.
        $this->withoutExceptionHandling();
    }

    //
}
