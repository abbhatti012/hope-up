<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => bcrypt('1234567890'),
            'role' => $this->faker->randomElement(['user', 'specialist']),
            'profile_photo' => 'default.png',
            'is_block' => false,
            'remember_token' => \Str::random(10),
            'user_token' => \Str::uuid(),
            'fcm_token' => \Str::random(32),
            'longitude' => $this->faker->longitude,
            'latitude' => $this->faker->latitude,
            'net_balance' => $this->faker->randomFloat(2, 0, 1000),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
