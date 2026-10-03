<?php

namespace App\Console\Commands;

use App\Services\EmailService;
use App\Models\User;
use Illuminate\Console\Command;

class TestEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test {email} {name} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test sending registration email';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(EmailService $emailService)
    {
        $user = new User([
            'name' => $this->argument('name'),
            'email' => $this->argument('email'),
        ]);

        $password = $this->argument('password');

        $emailService->sendRegistrationCredentials($user, $password);

        $this->info('Test email queued successfully!');
        $this->info('Run `php artisan queue:work` to process the queue.');

        return 0;
    }
}
