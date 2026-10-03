<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Appointment;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\WebSetting;

class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    
    protected $model = \App\Models\Transaction::class;

    public function definition()
    {
        $totalAmount = $this->faker->randomFloat(2, 50, 500);
        $source = $this->faker->randomElement(['appointment', 'subscription']);
        
        // Set doctor_amount based on source
        if ($source === 'subscription') {
            $doctorAmount = 0; // No doctor payment for subscriptions
        } else {
            $adminCommission = WebSetting::first()->admin_commission ?? 10;
            $doctorAmount = $totalAmount - ($totalAmount * ($adminCommission / 100));
        }
        
        return [
            'transaction_id' => $this->faker->unique()->uuid,
            'patient_id' => User::where('role', 'user')->inRandomOrder()->first()?->id ?? User::factory(),
            'doctor_id' => User::where('role', 'specialist')->inRandomOrder()->first()?->id ?? User::factory(),
            'appointment_id' => Appointment::factory(),
            'payment_method' => $this->faker->randomElement(['stripe', 'paypal']),
            'total_amount' => $totalAmount,
            'doctor_amount' => $doctorAmount,
            'currency' => $this->faker->randomElement(['GHS']),
            'payment_status' => $this->faker->randomElement(['pending', 'completed', 'failed', 'refunded', 'cancelled']),
            'notes' => $this->faker->text(100),
            'source' => $source,
            'created_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
        ];
    }
}
