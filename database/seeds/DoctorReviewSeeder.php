<?php

namespace Database\Seeders;

use App\Models\DoctorReview;
use App\Models\User;
use Illuminate\Database\Seeder;

class DoctorReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get doctors and patients
        $doctors = User::where('role', 'specialist')->pluck('id')->toArray();
        $patients = User::where('role', 'user')->pluck('id')->toArray();

        if (empty($doctors) || empty($patients)) {
            $this->command->info('No doctors or patients found. Skipping review creation.');
            return;
        }

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

        for ($i = 0; $i < 20; $i++) {
            DoctorReview::create([
                'doctor_id' => $doctors[array_rand($doctors)],
                'reviewer_id' => $patients[array_rand($patients)],
                'comments' => $comments[array_rand($comments)],
                'rating' => rand(1, 5),
                'rating_type' => $ratingTypes[array_rand($ratingTypes)],
                'is_approved' => rand(0, 1)
            ]);
        }

        $this->command->info('Created ' . DoctorReview::count() . ' doctor reviews.');
    }
}
