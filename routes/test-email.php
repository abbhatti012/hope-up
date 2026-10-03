<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Services\EmailService;

// Test email route
Route::get('/test-email', function () {
    try {
        $user = new User([
            'name' => 'Test User',
            'email' => 'ab.pk012@gmail.com',
        ]);

        $password = 'testpassword';

        // Send email
        app(EmailService::class)->sendRegistrationCredentials($user, $password);

        // Check if we're using queue or sync
        $queueDriver = config('queue.default');
        $message = 'Test email sent successfully! ';
        $message .= $queueDriver === 'sync' 
            ? 'Email was sent synchronously.' 
            : 'Email was queued. Make sure to run `php artisan queue:work` to process the queue.';

        return response()->json([
            'success' => true,
            'message' => $message,
            'queue_driver' => $queueDriver,
            'email' => $user->email,
            'timestamp' => now()->toDateTimeString()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to send test email',
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});
