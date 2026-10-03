<?php

namespace App\Console\Commands;

use App\Services\SubscriptionService;
use Illuminate\Console\Command;

class ResetMonthlyAppointmentCounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:reset-monthly-counts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset monthly appointment counts for all patients';

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
        $this->info('Starting monthly appointment count reset...');

        try {
            $count = $this->subscriptionService->resetMonthlyAppointmentCounts();
            $this->info("Successfully reset monthly appointment counts for {$count} patients.");
        } catch (\Exception $e) {
            $this->error("Failed to reset monthly appointment counts: {$e->getMessage()}");
            return 1;
        }

        return 0;
    }
}
