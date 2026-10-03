<?php

use App\Models\User;
use App\Models\Option;
use App\Models\Question;
use App\Models\Speciality;
use App\Models\UserAnswer;
use App\Models\Appointment;
use App\Models\Transaction;
use App\Models\DoctorDetail;
use App\Models\DoctorReview;
use App\Models\PatientDetail;
use Illuminate\Database\Seeder;
use Database\Seeders\AdminSeeder;
use Illuminate\Support\Facades\DB;
use App\Services\SubscriptionService;
use Database\Seeders\OptionsTableSeeder;
use Database\Seeders\QuestionsTableSeeder;
use Database\Seeders\UserAnswersTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        Appointment::truncate();
        Speciality::truncate();
        Transaction::truncate();
        DoctorReview::truncate();
        User::truncate();
        DoctorDetail::truncate();
        PatientDetail::truncate();
        Question::truncate();
        Option::truncate();
        UserAnswer::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $this->call(AdminSeeder::class);
        
        Speciality::factory(200)->create();

        $users = User::factory(200)->create();

        foreach ($users as $user) {
            if ($user->role === 'specialist') {
                DoctorDetail::factory()->create([
                    'user_id' => $user->id,
                ]);
            }

            if ($user->role === 'user') {
                PatientDetail::factory()->create([
                    'user_id' => $user->id,
                ]);
                
                // Initialize subscription for seeded patients
                $subscriptionService = app(SubscriptionService::class);
                try {
                    $subscriptionService->initializePatientSubscription($user);
                } catch (\Exception $e) {
                    // Log the error but don't fail the seeding
                    \Illuminate\Support\Facades\Log::error("Failed to initialize subscription for seeded patient {$user->id}: " . $e->getMessage());
                }
            }
        }

        Appointment::factory(200)->create();
        Transaction::factory(200)->create();
        DoctorReview::factory(200)->create();

        // Custom assessment/question seeders
        $this->call(QuestionsTableSeeder::class);
        $this->call(OptionsTableSeeder::class);
        $this->call(UserAnswersTableSeeder::class);
    }
}
