<?php

namespace Database\Factories\Admin;

use App\Models\Admin\AdminUser;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Admin\AdminUser>
 */
class AdminUserFactory extends Factory
{
    /**
     * The name of the corresponding model.
     *
     * @var string
     */
    protected $model = AdminUser::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pid' => 0,
            'level_id' => 0,
            'channel_id' => 0,
            'name' => fake()->name(),
            'account' => fake()->unique()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'phone' => fake()->phoneNumber(),
            'status' => AdminUser::STATUS_ENABLE,
            'is_cancel' => 0,
            'balance' => 0.00,
            'recharge' => 0.00,
            'profit' => 0.00,
            'photo' => '',
            'remark' => '',
            'remember_token' => Str::random(10),
            'is_new' => 1,
            'is_relation' => 1,
            'type' => 1,
            'person_num' => 0,
            'try_num' => 0,
            'language' => 'en',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
