<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Appointment;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition()
    {
        return [
            'patient_id' => User::where('role', 'user')->inRandomOrder()->first()?->id ?? User::factory(),
            'doctor_id' => User::where('role', 'specialist')->inRandomOrder()->first()?->id ?? User::factory(),
            'appointment_date' => $this->faker->date(),
            'appointment_time' => $this->faker->time(),
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'completed', 'canceled']),
            'treatment_type' => $this->faker->randomElement(['general', 'OPD']),
            'notes' => $this->faker->optional()->sentence(),
            'created_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
        ];
    }
}
