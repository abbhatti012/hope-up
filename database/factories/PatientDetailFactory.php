<?php

namespace Database\Factories;

use App\Models\PatientDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

class PatientDetailFactory extends Factory
{
    protected $model = PatientDetail::class;

    public function definition()
    {
        return [
            'user_id' => null,
            'phone_number' => $this->faker->phoneNumber,
            'dob' => $this->faker->date(),
            'gender' => $this->faker->randomElement(['male', 'female', 'kid', 'other']),
            'address' => $this->faker->address,
            'age' => $this->faker->numberBetween(1, 100),
            'blood_type' => $this->faker->randomElement(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']),
            'medical_concern' => $this->faker->optional()->sentence,
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
