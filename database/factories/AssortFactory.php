<?php

namespace Database\Factories;

use App\Models\Assort;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssortFactory extends Factory
{
    protected $model = Assort::class;

    public function definition(): array
    {
        return [
            'assort_name' => $this->faker->word,
            'duration' => 30,
            'try_num' => 0,
        ];
    }
}
