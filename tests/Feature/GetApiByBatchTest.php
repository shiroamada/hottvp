<?php

use App\Models\PreGeneratedCode;
use Database\Factories\Admin\AdminUserFactory;
use Illuminate\Support\Facades\Log;
use Mockery as M;
use App\Repository\APIHelper;

// Test case and RefreshDatabase are configured in tests/Pest.php

beforeEach(function () {
    // Authenticate as an admin user for guard 'admin'
    $this->admin = AdminUserFactory::new()->create();
    $this->actingAs($this->admin, 'admin');
    $this->originalConfig = config('app.pre_generated_codes_enabled');
});

afterEach(function () {
    config(['app.pre_generated_codes_enabled' => $this->originalConfig]);
    M::close();
    app()->instance(\App\Repository\APIHelper::class, null);
});

it('uses pre-generated codes when toggle is enabled', function () {
    config(['app.pre_generated_codes_enabled' => true]);

    // Create 3 available pre-generated codes
    PreGeneratedCode::factory(
        [
            'type' => '30days',
            'vendor' => 'wowtv',
            'requested_by' => null
        ]
    )->count(3)->create();

    // Ensure API is NOT called by mocking the overload and forbidding calls
    $apiMock = $this->mock(APIHelper::class);
    $apiMock->shouldNotReceive('post');

    $result = getApiByBatch(['number' => 3, 'day' => 30]);

    expect($result)->toBeArray();
    expect(count($result))->toBe(3);
    foreach ($result as $codeData) {
        expect($codeData)->toHaveKeys(['code','type','vendor','source']);
        expect($codeData['source'])->toBe('PreGeneratedCode');
        expect(strlen($codeData['code']))->toBe(12);
    }

    // All selected codes should be marked as requested
    expect(PreGeneratedCode::whereNotNull('requested_by')->count())->toBe(3);
});

it('falls back to API when toggle is disabled and API succeeds', function () {
    config(['app.pre_generated_codes_enabled' => false]);

    // Mock API to return 2 codes successfully
    $payload = json_encode(['data' => ['ABCDEFGHIJKL','MNOPQRSTUVWX']]);
    $apiMock = $this->mock(APIHelper::class);
    $apiMock->shouldReceive('post')->once()->andReturn($payload);

    $result = getApiByBatch(['number' => 2, 'day' => 90]);

    expect($result)->toBeArray();
    expect(count($result))->toBe(2);
    foreach ($result as $codeData) {
        expect($codeData['source'])->toBe('API');
        expect(strlen($codeData['code']))->toBe(12);
    }
});

it('retries API 3 times then falls back to pre-generated codes when disabled', function () {
    config(['app.pre_generated_codes_enabled' => false]);

    // Prepare some pre-generated codes for fallback
    PreGeneratedCode::factory(
          [
            'type' => '30days',
            'vendor' => 'wowtv',
            'requested_by' => null
        ]
    )->count(2)->create();

    // Mock API to fail (return null) for each retry
    $apiMock = $this->mock(APIHelper::class);
    $apiMock->shouldReceive('post')->times(3)->andReturn(null);

    // Spy on logs to assert warnings
    Log::spy();

    $result = getApiByBatch(['number' => 2, 'day' => 30]);

    // Should have used pre-generated codes
    expect($result)->toBeArray();
    expect(count($result))->toBe(2);
    foreach ($result as $codeData) {
        expect($codeData['source'])->toBe('PreGeneratedCode');
    }

    // Assert a warning log about API retries
    Log::shouldHaveReceived('warning')
        ->withArgs(function ($message, $context = []) {
            return is_string($message) && str_contains($message, 'API retries exhausted');
        })->atLeast()->once();
});

it('logs and notifies admin when pre-generated pool is insufficient and returns only available', function () {
    config(['app.pre_generated_codes_enabled' => true]);

    // Only 1 code available locally but request 2
    PreGeneratedCode::factory(
          [
            'type' => '30days',
            'vendor' => 'wowtv',
            'requested_by' => null
        ]
    )->count(1)->create();

    // API should not be called at all when toggle enabled
    $apiMock = $this->mock(APIHelper::class);
    $apiMock->shouldNotReceive('post');

    // Spy on logs to assert notification
    Log::spy();

    $result = getApiByBatch(['number' => 2, 'day' => 30]);

    // Only 1 available should be returned
    expect(count($result))->toBe(1);
    expect($result[0]['source'])->toBe('PreGeneratedCode');

    // Assert the notification warning was logged
    Log::shouldHaveReceived('warning')
        ->withArgs(function ($message, $context = []) {
            return is_string($message) && str_contains($message, 'PreGeneratedCode insufficient');
        })->atLeast()->once();
});
