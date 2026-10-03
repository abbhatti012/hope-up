<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */

    protected $model = \App\Models\UserDetail::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'profile_photo' => $this->faker->imageUrl(),
            'phone_number' => $this->faker->phoneNumber,
            'dob' => $this->faker->date(),
            'gender' => $this->faker->randomElement(['male', 'female', 'other']),
            'address' => $this->faker->address,
            'speciality' => $this->faker->word,
            'medical_license' => $this->faker->word,
            'experience' => $this->faker->numberBetween(1, 40),
            'graduation_year' => $this->faker->year,
            'degree_certificate' => $this->faker->word,
            'medical_concern' => $this->faker->paragraph,
        ];
    }
}
