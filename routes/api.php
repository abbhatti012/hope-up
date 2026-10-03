<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CallController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\Api\AssessmentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('/v1/register', [AuthController::class, 'register']);
Route::post('/v1/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('v1/users')->group(function () {
        Route::get('/', [ApiController::class, 'get_users']);
        Route::get('/search', [ApiController::class, 'search_users']);
        Route::get('/{id}', [ApiController::class, 'get_user']);
        Route::post('/{id}', [AuthController::class, 'update']);
    });

    Route::prefix('v1/speciality')->group(function () {
        Route::post('/', [ApiController::class, 'specialities_store']);
        Route::get('/{id}', [ApiController::class, 'specialities_get']);
        Route::get('/', [ApiController::class, 'specialities_get']);
        Route::put('/{id}', [ApiController::class, 'specialities_update']);
    });

    Route::prefix('v1/schedule')->group(function () {
        Route::get('/', [ApiController::class, 'retrieve_schedules']);
        Route::get('/{id}', [ApiController::class, 'retrieve_schedules']);
        Route::post('/', [ApiController::class, 'schedule_store']);
        Route::put('/{id}', [ApiController::class, 'schedule_update']);
    });
    
    Route::prefix('v1/appointment')->group(function () {
        Route::get('/patient/{patient_id}', [ApiController::class, 'get_patient_appointments']);
        Route::get('/', [ApiController::class, 'retrieve_appointment']);
        Route::get('/{id}', [ApiController::class, 'retrieve_appointment']);
        Route::post('/', [ApiController::class, 'appointment_store']);
        Route::put('/{id}', [ApiController::class, 'appointment_update']);
    });

    Route::prefix('v1/chat')->group(function () {
        // Get conversations for the authenticated user
        Route::get('/conversations', [ChatController::class, 'getUserConversations']);
        // Get conversations for a specific user (admin only)
        Route::get('/users/{userId}/conversations', [ChatController::class, 'getUserConversations']);
        Route::post('/conversations', [ChatController::class, 'createConversation']);
        Route::post('/conversations/{conversationId}/messages', [ChatController::class, 'sendMessage']);
        Route::get('/conversations/{conversationId}/messages', [ChatController::class, 'getMessages']);
    });

    Route::prefix('v1/calls')->group(function () {
        Route::post('/', [CallController::class, 'initiateCall']);
        Route::post('/{callId}/accept', [CallController::class, 'acceptCall']);
        Route::post('/{callId}/reject', [CallController::class, 'rejectCall']);
        Route::post('/{callId}/end', [CallController::class, 'endCall']);
        Route::get('/', [CallController::class, 'getCallHistory']);
    });

    Route::prefix('v1/transactions')->group(function () {
        Route::post('/', [ApiController::class, 'store_transactions']);
        Route::get('/', [ApiController::class, 'get_transactions']);
        Route::get('/{id}', [ApiController::class, 'show_transactions']);
        Route::put('/{id}/status', [ApiController::class, 'updateTransactionStatus']);
    });

    // Doctor Reviews routes
    Route::prefix('v1/reviews')->group(function () {
        Route::get('/', [ReviewController::class, 'index']);
        Route::get('/{id}', [ReviewController::class, 'show']);
        Route::post('/', [ReviewController::class, 'store']);
        Route::put('/{id}', [ReviewController::class, 'update']);
        Route::delete('/{id}', [ReviewController::class, 'destroy']);
        Route::patch('/{id}/toggle-approval', [ReviewController::class, 'toggleApproval']);
        Route::get('/doctor/{doctorId}', [ReviewController::class, 'doctorReviews']);
    });

    // Subscription routes
    Route::prefix('v1/subscriptions')->group(function () {
        Route::get('/patient/{patientId}', [ApiController::class, 'getPatientSubscription']);
        Route::get('/plans', [ApiController::class, 'getSubscriptionPlans']);
        Route::post('/patient/{patientId}/upgrade', [ApiController::class, 'upgradePatientSubscription']);
        Route::post('/patient/{patientId}/cancel', [ApiController::class, 'cancelPatientSubscription']);
        Route::post('/patient/{patientId}/extend-trial', [ApiController::class, 'extendPatientTrial']);
        Route::post('/patient/{patientId}/reset', [ApiController::class, 'resetPatientSubscription']);
        Route::get('/patient/{patientId}/can-book', [ApiController::class, 'canPatientBookAppointment']);
    });

    Route::prefix('v1/assessments')->group(function () {
        Route::get('/questions', [AssessmentController::class, 'questions']);
        Route::get('/questions/{question}/options', [AssessmentController::class, 'options']);
        Route::post('/answers', [AssessmentController::class, 'submitAnswer']);
        Route::get('/user-answers', [AssessmentController::class, 'userAnswers']);
        Route::get('/user-answers/{userId}', [AssessmentController::class, 'userAnswersForUser']);
    });

    Route::prefix('v1/mhc')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\MhcController::class, 'index']);
        Route::get('/{id}', [\App\Http\Controllers\Api\MhcController::class, 'show']);
        Route::post('/', [\App\Http\Controllers\Api\MhcController::class, 'store']);
        Route::post('/{id}', [\App\Http\Controllers\Api\MhcController::class, 'update']);
        Route::patch('/{id}', [\App\Http\Controllers\Api\MhcController::class, 'update']);
        Route::delete('/{id}', [\App\Http\Controllers\Api\MhcController::class, 'destroy']);
    });
});

