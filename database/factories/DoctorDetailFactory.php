<?php

namespace Database\Factories;

use App\Models\DoctorDetail;
use App\Models\Speciality;
use Illuminate\Database\Eloquent\Factories\Factory;

class DoctorDetailFactory extends Factory
{
    protected $model = DoctorDetail::class;

    public function definition()
    {
        return [
            'user_id' => null,
            'phone_number' => $this->faker->phoneNumber,
            'dob' => $this->faker->date(),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'address' => $this->faker->address,
            'speciality_id' => function () {
                return Speciality::inRandomOrder()->first()->id;
            },
            'medical_license' => $this->faker->bothify('??###??##'),
            'experience' => $this->faker->numberBetween(1, 30),
            'graduation_year' => $this->faker->year('-5 years'),
            'degree_certificate' => 'certificates/' . $this->faker->uuid . '.pdf',
            'about' => $this->faker->paragraph,
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
