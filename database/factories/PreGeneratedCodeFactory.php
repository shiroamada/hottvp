<?php

namespace Database\Factories;

use App\Models\Admin\AdminUser;
use App\Models\PreGeneratedCode;
use Illuminate\Database\Eloquent\Factories\Factory;

class PreGeneratedCodeFactory extends Factory
{
    protected $model = PreGeneratedCode::class;

    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->regexify('[A-Z0-9]{12}'),
            'type' => '30days',
            'vendor' => 'hottv',
            'remark' => $this->faker->sentence,
            'imported_by' => AdminUser::factory(),
            'imported_at' => now(),
            'requested_by' => null,
            'requested_at' => null,
            'assort_level_id' => null,
        ];
    }
}
