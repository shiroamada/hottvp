<?php

namespace Database\Factories\Admin;

use App\Models\Admin\AdminUser;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AdminUserFactory extends Factory
{
    protected $model = AdminUser::class;

    public function definition(): array
    {
        return [
            'pid' => 0,
            'level_id' => 1,
            'channel_id' => 1,
            'name' => $this->faker->name(),
            'account' => $this->faker->unique()->userName(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber,
            'password' => bcrypt('password'),
            'pwd' => 'password',
            'status' => 1,
            'is_cancel' => 0,
            'balance' => 1000,
            'recharge' => 1000,
            'profit' => 0,
            'remember_token' => Str::random(10),
        ];
    }
}