<?php

namespace Database\Factories;

use App\Models\DoctorReview;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DoctorReviewFactory extends Factory
{
    protected $model = \App\Models\DoctorReview::class;

    public function definition()
    {
        $doctors = User::where('role', 'specialist')->pluck('id')->toArray();
        $patients = User::where('role', 'user')->pluck('id')->toArray();
        
        if (!empty($doctors) && !empty($patients)) {
            $ratingTypes = ['excellent', 'very_good', 'good', 'fair', 'poor', 'bad'];
            $comments = [
                'Great doctor, very professional and caring.',
                'Excellent treatment and follow-up care.',
                'Good experience overall, would recommend.',
                'Fair treatment, room for improvement.',
                'Poor communication and bedside manner.',
                'Very knowledgeable and thorough.',
                'Professional and efficient service.',
                'Good diagnosis and treatment plan.',
                'Excellent bedside manner and care.',
                'Very satisfied with the treatment.'
            ];
            
            return [
                'doctor_id' => $this->faker->randomElement($doctors),
                'reviewer_id' => $this->faker->randomElement($patients),
                'comments' => $this->faker->randomElement($comments),
                'rating' => $this->faker->numberBetween(1, 5),
                'rating_type' => $this->faker->randomElement($ratingTypes),
                'is_approved' => $this->faker->boolean(80),
                'created_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
                'updated_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
            ];
        }
    }

    /**
     * Indicate that the review is approved.
     */
    public function approved()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_approved' => true,
            ];
        });
    }

    /**
     * Indicate that the review is pending approval.
     */
    public function pending()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_approved' => false,
            ];
        });
    }

    /**
     * Create a review with excellent rating
     */
    public function excellent()
    {
        return $this->state(function (array $attributes) {
            return [
                'rating' => 5,
                'rating_type' => 'excellent',
                'is_approved' => true,
            ];
        });
    }

    /**
     * Create a review with poor rating
     */
    public function poor()
    {
        return $this->state(function (array $attributes) {
            return [
                'rating' => 2,
                'rating_type' => 'poor',
                'is_approved' => true,
            ];
        });
    }
} 