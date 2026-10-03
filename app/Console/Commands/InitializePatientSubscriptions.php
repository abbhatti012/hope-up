<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\PatientDetail;
use App\Services\SubscriptionService;
use Illuminate\Console\Command;

class InitializePatientSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:initialize-patients';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize subscriptions for patients who don\'t have subscription data';

    protected $subscriptionService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(SubscriptionService $subscriptionService)
    {
        parent::__construct();
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting patient subscription initialization...');

        // Find patients without subscription data
        $patients = User::where('role', 'user')
            ->whereDoesntHave('patientDetail', function($query) {
                $query->whereNotNull('subscription_status');
            })
            ->get();

        $count = 0;
        $bar = $this->output->createProgressBar($patients->count());

        foreach ($patients as $patient) {
            try {
                $this->subscriptionService->initializePatientSubscription($patient);
                $count++;
            } catch (\Exception $e) {
                $this->error("Failed to initialize subscription for patient {$patient->id}: {$e->getMessage()}");
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Successfully initialized subscriptions for {$count} patients.");

        return 0;
    }
}
