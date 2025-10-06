<?php

namespace Database\Factories;

use App\Models\Admin\AdminUser;
use App\Models\AuthCode;
use App\Models\Assort;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuthCodeFactory extends Factory
{
    protected $model = AuthCode::class;

    public function definition(): array
    {
        return [
            'assort_id' => 1,
            'user_id' => AdminUser::factory(),
            'auth_code' => $this->faker->unique()->regexify('[A-Z0-9]{12}'),
            'remark' => $this->faker->sentence,
            'status' => 0,
            'is_try' => 1, // Default to license code
            'num' => 1,
            'type' => 1,
            'profit' => 0,
            'expire_at' => now()->addDays(30),
        ];
    }
}
