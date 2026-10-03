<?php

use App\City;
use App\Category;
use App\Models\User;
use App\Services\UserService;
use App\Services\EmailService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\Admin\SearchController;
use App\Http\Controllers\DashboardStatsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'PublicController@index')->name('/');

Auth::routes();
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/home', [AdminController::class, 'index'])->name('home')->middleware('auth');
Route::get('/login', [PublicController::class, 'login'])->name('login');
Route::post('/auth-user', [PublicController::class, 'authenticate_user'])->name('auth.user');

Route::middleware('auth')->group(function () {

    // Doctors routes
    Route::prefix('doctors')->group(function () {
        Route::get('/manage-doctors', [AdminController::class, 'manage_doctors'])->name('manage.doctors');
        Route::get('/doctor-detail/{id}', [AdminController::class, 'doctor_detail'])->name('doctor-detail');
        Route::get('/dashboard', [AdminController::class, 'doctors_dashboard'])->name('doctors.dashboard');
        Route::get('/users/create', [AdminController::class, 'create'])->name('doctors.users.create');
        Route::get('/users/{user}/edit', [AdminController::class, 'edit'])->name('doctors.users.edit');
    });

    Route::post('/users', [AdminController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [AdminController::class, 'update'])->name('users.update');

    // Patients routes
    Route::prefix('patients')->group(function () {
        Route::get('/', [AdminController::class, 'manage_patients'])->name('manage.patients');
        Route::get('/dashboard', [AdminController::class, 'patients_dashboard'])->name('patients.dashboard');
        Route::get('/create', [AdminController::class, 'createPatient'])->name('patients.create');
        Route::get('/{patient}', [AdminController::class, 'showPatient'])->name('patients.show');
        Route::get('/{patient}/edit', [AdminController::class, 'editPatient'])->name('patients.edit');
        Route::delete('/{patient}', [AdminController::class, 'destroyPatient'])->name('patients.destroy');
    });

    // Admin management routes
    Route::prefix('admins')->group(function () {
        Route::get('/', [AdminController::class, 'manage_admins'])->name('manage.admins');
        Route::get('/create', [AdminController::class, 'create_admin'])->name('admin.users.create');
        Route::get('/users/{user}/edit', [AdminController::class, 'admin_edit'])->name('admin.users.edit');
    });
    
    Route::get('/block-users', [AdminController::class, 'block_users'])->name('block.all.users');
    Route::patch('users/{user}/role', [AdminController::class, 'updateRole'])->name('manage.users.updateRole');
    Route::patch('users/{user}/toggle-block', [AdminController::class, 'toggleBlock'])->name('manage.users.toggleBlock');
    Route::patch('/admin/users/{user}/toggle-block', [AdminController::class, 'toggleBlock'])->name('admin.users.toggleBlock');
    
    Route::get('/add-speciality', [AdminController::class, 'manage_speciality'])->name('manage.speciality');
    Route::get('/specialities/create', [AdminController::class, 'specialities_create'])->name('specialities.create');
    Route::post('/specialities/users', [AdminController::class, 'specialities_store'])->name('specialities.store');
    Route::get('/specialities/{speciality}/edit', [AdminController::class, 'specialities_edit'])->name('specialities.edit');
    Route::put('/specialities/{speciality}', [AdminController::class, 'specialities_update'])->name('specialities.update');
    Route::delete('/specialities/delete/{delete}', [AdminController::class, 'specialities_destroy'])->name('specialities.delete');
    Route::post('/specialities/toggle-status/{id}', [AdminController::class, 'toggleStatus'])->name('specialities.toggle-status');

    // Schedules routes
    Route::get('/schedules', [ScheduleController::class, 'index'])->name('schedules.index');
    Route::get('/schedules/list', [ScheduleController::class, 'list'])->name('schedules.list');
    Route::post('/schedules/store', [ScheduleController::class, 'storeOrUpdate'])->name('schedules.storeOrUpdate');
    Route::post('/schedules/delete', [ScheduleController::class, 'destroy'])->name('schedules.destroy');

    // Appointments routes
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('manage-appointments');
    Route::get('/appointments/export', [AppointmentController::class, 'export'])->name('export-appointments');
    Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::get('/appointments/{appointment}/edit', [AppointmentController::class, 'edit'])->name('appointments.edit');
    Route::put('/appointments/{appointment}', [AppointmentController::class, 'update'])->name('appointments.update');
    Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');
    Route::patch('appointments/{appointment}/toggle-block', [AppointmentController::class, 'toggleBlock'])->name('manage.appointments.toggleBlock');

    // Transactions routes
    Route::get('/transactions', [TransactionController::class, 'index'])->name('manage-transactions');
    Route::get('/transactions/export', [TransactionController::class, 'export'])->name('export-transactions');
    Route::get('/transactions/{transaction}/receipt', [TransactionController::class, 'generateReceipt'])->name('transactions.receipt');
    Route::post('/transactions/{transaction}/mark-paid', [TransactionController::class, 'markPaid'])->name('transactions.markPaid');

    Route::patch('transactions/{transaction}/toggle-block', [TransactionController::class, 'toggleBlock'])->name('manage.transactions.toggleBlock');
    Route::get('transactions/{transaction}/details', [TransactionController::class, 'details'])->name('transactions.details');

    // Mental Health Content routes 
    Route::get('/mental-health-content', [AdminController::class, 'manage_mhc'])->name('manage-mhc');
    Route::get('/content/create', [AdminController::class, 'content_create'])->name('content.create');
    Route::post('/content', [AdminController::class, 'content_store'])->name('content.store');
    Route::get('/content/{content}/edit', [AdminController::class, 'content_edit'])->name('content.edit');
    Route::put('/content/{content}', [AdminController::class, 'content_update'])->name('content.update');
    Route::delete('/content/{delete}', [AdminController::class, 'content_destroy'])->name('content.destroy');

    // Doctor Reviews routes
    Route::prefix('admin/reviews')->group(function () {
        Route::get('/', [\App\Http\Controllers\DoctorReviewController::class, 'index'])->name('reviews.index');
        Route::get('/create', [\App\Http\Controllers\DoctorReviewController::class, 'create'])->name('reviews.create');
        Route::post('/', [\App\Http\Controllers\DoctorReviewController::class, 'store'])->name('reviews.store');
        Route::get('/{review}', [\App\Http\Controllers\DoctorReviewController::class, 'show'])->name('reviews.show');
        Route::get('/{review}/edit', [\App\Http\Controllers\DoctorReviewController::class, 'edit'])->name('reviews.edit');
        Route::put('/{review}', [\App\Http\Controllers\DoctorReviewController::class, 'update'])->name('reviews.update');
        Route::patch('/{review}/toggle-approval', [\App\Http\Controllers\DoctorReviewController::class, 'toggleApproval'])->name('reviews.toggle-approval');
        Route::delete('/{review}', [\App\Http\Controllers\DoctorReviewController::class, 'destroy'])->name('reviews.destroy');
    });

    // Admin Profile routes
    Route::get('/admin-profile', [AdminController::class, 'profile'])->name('admin.profile');
    Route::put('/update-admin-profile', [AdminController::class, 'update_profile'])->name('change.admin.profile');
    Route::put('/update-admin-credentials', [AdminController::class, 'update_admin_credentials'])->name('change.admin.credentials');
    
    // Login Activities routes
    Route::prefix('admin/login-activities')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\LoginActivityController::class, 'index'])->name('admin.login-activities.index');
        Route::get('/user/{userId}', [\App\Http\Controllers\Admin\LoginActivityController::class, 'userActivities'])->name('admin.login-activities.user');
        Route::get('/export', [\App\Http\Controllers\Admin\LoginActivityController::class, 'export'])->name('admin.login-activities.export');
    });
    
    // Dashboard data routes
    Route::get('/appointment-stats/{range?}', [DashboardStatsController::class, 'getAppointmentStats'])->name('appointment.stats');
    Route::get('/treatment-type-stats/{range?}', [DashboardStatsController::class, 'getTreatmentTypeStats'])->name('treatment.type.stats');
    Route::get('/user-role-stats/{range?}', [DashboardStatsController::class, 'getUserRoleStats'])->name('user.role.stats');
    Route::get('/patient-gender-militant-stats/{range?}', [DashboardStatsController::class, 'getPatientGenderAndMilitantStats'])->name('patient.gender.militant.stats');
    
    // Global search routes
    Route::prefix('admin/search')->group(function () {
        Route::get('/global', [SearchController::class, 'globalSearch'])->name('admin.search.global');
        Route::get('/suggestions', [SearchController::class, 'getSuggestions'])->name('admin.search.suggestions');
    });
    
    // Block/Unblock User Routes
    Route::prefix('admin')->group(function () {
        Route::post('/users/{user}/block', function (User $user, UserService $userService) {
            $result = $userService->blockUser($user->id);
            return response()->json($result);
        })->name('admin.users.block');
    
        Route::post('/users/{user}/unblock', function (User $user, UserService $userService) {
            $result = $userService->unblockUser($user->id);
            return response()->json($result);
        })->name('admin.users.unblock');
    });

    // Assessment routes
    Route::prefix('assessments')->group(function () {
        Route::get('/user-answers', [AssessmentController::class, 'allUserAnswersList'])->name('user.answers.list');
        Route::get('/', [AssessmentController::class, 'index'])->name('assessments.index');
        Route::get('/create', [AssessmentController::class, 'create'])->name('assessments.create');
        Route::post('/', [AssessmentController::class, 'store'])->name('assessments.store');
        Route::get('/{question}', [AssessmentController::class, 'show'])->name('assessments.show');
        Route::get('/{question}/edit', [AssessmentController::class, 'edit'])->name('assessments.edit');
        Route::put('/{question}', [AssessmentController::class, 'update'])->name('assessments.update');
        Route::delete('/{question}', [AssessmentController::class, 'destroy'])->name('assessments.destroy');
        
        // User answer routes
        Route::post('/user-answer', [AssessmentController::class, 'storeUserAnswer'])->name('assessments.store-user-answer');
        Route::get('/questions/{assessmentType}', [AssessmentController::class, 'getQuestionsByType'])->name('assessments.questions-by-type');
        Route::get('/user-answers/{user}', [AssessmentController::class, 'userAnswers'])->name('user.answers');
    });
    // Web Settings routes
    Route::get('/web-settings', [AdminController::class, 'webSettings'])->name('admin.web-settings');
    Route::post('/web-settings', [AdminController::class, 'updateWebSettings'])->name('admin.web-settings.update');
});


// Test queue worker route
Route::get('/test-queue', function () {
    try {
        $queueService = app(\App\Services\QueueService::class);
        $result = $queueService->ensureWorkerIsRunning();
        
        // Queue a test job
        \App\Jobs\ProcessEmail::dispatch(
            'test@example.com',
            'Test Subject',
            'This is a test email from the queue worker.'
        )->onQueue('emails');
        
        return response()->json([
            'success' => true,
            'message' => 'Queue worker started and test job queued',
            'worker_status' => $result
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});

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

Route::get('/clear-cache', function () {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:cache');
    return 'Cleared!';
});


Route::get('/run-storage-link', function () {
    Artisan::call('storage:link');
    return 'Storage link created';
});

// Email verification route (outside auth middleware for public access)
Route::get('/admin/verify-email/{token}', [AdminController::class, 'verifyEmail'])->name('admin.verify.email');

