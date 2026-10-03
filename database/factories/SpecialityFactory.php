<?php

namespace Database\Factories;

use App\Models\Speciality;
use Illuminate\Database\Eloquent\Factories\Factory;

class SpecialityFactory extends Factory
{
    protected $model = Speciality::class;

    public function definition()
    {
        return [
            'title' => $this->faker->unique()->jobTitle,
            'is_active' => $this->faker->boolean(80), // 80% chance of being active
            'created_at' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-2 years', 'now'),
        ];
    }
}
