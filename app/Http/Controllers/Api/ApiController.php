<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Schedule;
use App\Models\Speciality;
use App\Models\WebSetting;
use App\Models\Appointment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Services\EmailService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Services\SubscriptionService;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\TransactionController;

class ApiController extends \App\Http\Controllers\BaseController
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function specialities_store(Request $request)
    {
        $result = $this->userService->storeSpeciality($request);

        if (!$result['success']) {
            return response()->json([
                'error' => 'Error creating speciality!',
                'message' => $result['error']
            ], 500);
        }

        return response()->json([
            'message' => 'Speciality created successfully!',
            'data' => $result['data']
        ], 200);
    }

    public function specialities_get($id = 0)
    {
        $result = $id ? Speciality::find($id) : Speciality::all();

        if (!$result) {
            return response()->json([
                'data' => []
            ], 500);
        }

        return response()->json([
            'data' => $result
        ], 200);
    }

    public function specialities_update(Request $request, $id)
    {
        $result = $this->userService->updateSpeciality($request, $id);

        if (!$result['success']) {
            return response()->json([
                'error' => 'Error updating speciality!',
                'message' => $result['error']
            ], 500);
        }

        return response()->json([
            'message' => 'Speciality updated successfully!',
            'data' => $result['data']
        ], 200);
    }
    public function retrieve_schedules($id = null)
    {
        if ($id) {
            $schedule = Schedule::with('user')->find($id);
            if (!$schedule) {
                return response()->json(['success' => false, 'message' => 'Schedule not found.'], 404);
            }
            return response()->json(['success' => true, 'schedule' => $schedule], 200);
        }

        $schedules = Schedule::with('user')->paginate(100);
        return response()->json(['success' => true, 'schedules' => $schedules], 200);
    }

    public function schedule_store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'end_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'event' => 'required|string'
        ]);

        $schedule = Schedule::create([
            'user_id' => $request->user_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'event' => $request->event
        ]);

        return response()->json(['success' => true, 'schedule' => $schedule], 201);
    }

    public function schedule_update(Request $request, $id)
    {
        $schedule = Schedule::find($id);
        if (!$schedule) {
            return response()->json(['success' => false, 'message' => 'Schedule not found.'], 404);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'end_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'event' => 'required|string'
        ]);

        $schedule->update([
            'user_id' => $request->user_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'event' => $request->event
        ]);

        return response()->json(['success' => true, 'schedule' => $schedule], 200);
    }

    public function retrieve_appointment($id = null)
    {
        if ($id) {
            $appointment = Appointment::with(['patient.detail', 'doctor.detail'])->find($id);
            if (!$appointment) {
                return response()->json(['success' => false, 'message' => 'Appointment not found.'], 404);
            }
            return response()->json(['success' => true, 'appointment' => $appointment], 200);
        }

        $appointments = Appointment::with(['patient.detail', 'doctor.detail'])->paginate(100);
        return response()->json(['success' => true, 'appointments' => $appointments], 200);
    }

    public function appointment_store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:users,id',
            'doctor_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'status' => 'sometimes|in:pending,confirmed,completed,canceled',
            'treatment_type' => 'sometimes|in:general,OPD',
            'notes' => 'nullable|string'
        ]);
    
        $patient = User::find($request->patient_id);
        if (!$patient || $patient->role !== 'user') {
            return response()->json(['success' => false, 'message' => 'The provided patient ID does not belong to a valid patient.'], 400);
        }
    
        $doctor = User::find($request->doctor_id);
        if (!$doctor || $doctor->role !== 'specialist') {
            return response()->json(['success' => false, 'message' => 'The provided doctor ID does not belong to a valid doctor.'], 400);
        }

        // Check subscription before booking
        $subscriptionService = app(\App\Services\SubscriptionService::class);
        
        if (!$subscriptionService->canBookAppointment($patient)) {
            return response()->json([
                'success' => false, 
                'message' => 'Your subscription has ended. Please choose a plan to continue booking appointments.',
                'subscription_required' => true
            ], 403);
        }
    
        $appointment = Appointment::create([
            'patient_id' => $request->patient_id,
            'doctor_id' => $request->doctor_id,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'status' => $request->status ?? 'pending',
            'treatment_type' => $request->treatment_type ?? 'general',
            'notes' => $request->notes
        ]);

        // Process subscription usage
        try {
            $subscriptionService->processAppointmentBooking($patient, $appointment);
        } catch (\Exception $e) {
            // Log the error but don't fail the appointment creation
            Log::error("Failed to process subscription for appointment: " . $e->getMessage());
        }

        // Send email notifications
        try {
            $emailService = app(EmailService::class);
            $emailService->sendAppointmentNotification($appointment, 'created');
        } catch (\Exception $e) {
            Log::error('Failed to send appointment creation email: ' . $e->getMessage());
        }
    
        return response()->json(['success' => true, 'appointment' => $appointment], 201);
    }    

    public function appointment_update(Request $request, $id)
    {
        $appointment = Appointment::find($id);
        if (!$appointment) {
            return response()->json(['success' => false, 'message' => 'Appointment not found.'], 404);
        }

        $request->validate([
            'patient_id' => 'sometimes|exists:users,id',
            'doctor_id' => 'sometimes|exists:users,id',
            'appointment_date' => 'sometimes|date',
            'appointment_time' => 'sometimes',
            'status' => 'sometimes|in:pending,confirmed,completed,canceled',
            'treatment_type' => 'sometimes|in:general,OPD',
            'notes' => 'nullable|string'
        ]);

        $patient = User::find($request->patient_id);
        if (!$patient || $patient->role !== 'user') {
            return response()->json(['success' => false, 'message' => 'The provided patient ID does not belong to a valid patient.'], 400);
        }
    
        $doctor = User::find($request->doctor_id);
        if (!$doctor || $doctor->role !== 'specialist') {
            return response()->json(['success' => false, 'message' => 'The provided doctor ID does not belong to a valid doctor.'], 400);
        }

        $appointment->update(array_filter($request->only([
            'patient_id',
            'doctor_id',
            'appointment_date',
            'appointment_time',
            'status',
            'treatment_type',
            'notes'
        ])));

        // Send email notifications
        try {
            $emailService = app(EmailService::class);
            $emailService->sendAppointmentNotification($appointment, 'updated');
        } catch (\Exception $e) {
            Log::error('Failed to send appointment update email: ' . $e->getMessage());
        }

        return response()->json(['success' => true, 'appointment' => $appointment], 200);
    }

    public function get_patient_appointments($patient_id)
    {
        // Ensure that the provided user exists and is a patient
        $patient = User::find($patient_id);
        if (!$patient || $patient->role !== 'user') {
            return response()->json([
                'success' => false,
                'message' => 'The provided patient ID does not belong to a valid patient.'
            ], 400);
        }

        // Fetch appointments related to the patient with doctor & patient relations eager loaded
        $appointments = Appointment::with(['patient.detail', 'doctor.detail'])
            ->where('patient_id', $patient_id)
            ->paginate(100);

        return response()->json([
            'success' => true,
            'appointments' => $appointments
        ], 200);
    }

    public function get_transactions()
    {
        return $this->handleErrors(function () {
            $transactions = Transaction::with(['patient', 'doctor', 'appointment'])->paginate(100);
            return response()->json($transactions);
        });
    }

    public function show_transactions($id)
    {
        return $this->handleErrors(function () use ($id) {
            $transaction = Transaction::with(['patient', 'doctor', 'appointment'])->find($id);
            if (!$transaction) {
                return response()->json(['message' => 'Transaction not found'], 404);
            }
            return response()->json($transaction);
        });
    }

    public function store_transactions(Request $request)
    {
        return $this->handleErrors(function () use ($request) {
            $request->validate([
                'patient_id' => 'required|exists:users,id',
                'doctor_id' => 'required|exists:users,id',
                'appointment_id' => 'required|exists:appointments,id',
                'payment_method' => 'required|string',
                'total_amount' => 'required|numeric',
                'currency' => 'required|string|size:3',
                'payment_status' => 'required|in:pending,completed,failed,refunded',
                'notes' => 'nullable|string',
                'source' => 'required|in:appointment,subscription',
            ]);

            $adminCommission = WebSetting::first()->admin_commission ?? 0;
            $totalAmount = $request->total_amount;
            
            // Set doctor_amount based on source
            if (strtolower($request->source) === 'subscription') {
                $doctorAmount = 0; // No doctor payment for subscriptions
            } else {
                $doctorAmount = $totalAmount - ($totalAmount * ($adminCommission / 100));
            }

            $transactionData = $request->all();
            $transactionData['doctor_amount'] = $doctorAmount;

            $transaction = Transaction::create($transactionData);

            // Add total_amount to superadmin's net_balance when transaction is created
            if ($transaction->total_amount > 0) {
                $superadmin = User::where('role', 'superadmin')->first();
                if ($superadmin) {
                    $superadmin->net_balance = ($superadmin->net_balance ?? 0) + $transaction->total_amount;
                    $superadmin->save();
                }
            }

            // Deduct total_amount from patient's net_balance when transaction is created
            $patient = User::find($transaction->patient_id);
            if ($patient) {
                $patient->net_balance = ($patient->net_balance ?? 0) - $transaction->total_amount;
                $patient->save();
            }
            // Send transaction created email
            try {
                $emailService = app(EmailService::class);
                $emailService->sendTransactionNotification($transaction, 'created');
            } catch (\Exception $e) {
                Log::error('Failed to send transaction creation email: ' . $e->getMessage());
            }
            return response()->json(['message' => 'Transaction created successfully', 'transaction' => $transaction], 201);
        });
    }

    // Update transaction status
    public function updateTransactionStatus(Request $request, $id)
    {
        return $this->handleErrors(function () use ($request, $id) {
            $request->validate([
                'payment_status' => 'required|in:pending,completed,failed,refunded,cancelled',
            ]);

            $transaction = Transaction::find($id);
            if (!$transaction) {
                return response()->json(['message' => 'Transaction not found'], 404);
            }

            $transaction->update(['payment_status' => $request->payment_status]);

            // Add total_amount to superadmin's net_balance when status becomes completed
            if ($request->payment_status === 'completed' && $transaction->total_amount > 0) {
                $superadmin = User::where('role', 'superadmin')->first();
                if ($superadmin) {
                    $superadmin->net_balance = ($superadmin->net_balance ?? 0) + $transaction->total_amount;
                    $superadmin->save();
                }
            }

            // Handle refunds - deduct commission from superadmin and doctor_amount from doctor
            if ($request->payment_status === 'refunded') {
                $superadmin = User::where('role', 'superadmin')->first();
                if ($superadmin && $transaction->total_amount > 0) {
                    // Deduct commission amount (total_amount - doctor_amount) from superadmin
                    $commissionAmount = $transaction->total_amount - ($transaction->doctor_amount ?? 0);
                    $superadmin->net_balance = ($superadmin->net_balance ?? 0) - $commissionAmount;
                    $superadmin->save();
                }

                // Deduct doctor_amount from doctor's net_balance
                if ($transaction->doctor && $transaction->doctor_amount > 0) {
                    $doctor = $transaction->doctor;
                    $doctor->net_balance = ($doctor->net_balance ?? 0) - $transaction->doctor_amount;
                    $doctor->save();
                }

                // Add total_amount back to patient's net_balance (refund to patient)
                if ($transaction->patient && $transaction->total_amount > 0) {
                    $patient = $transaction->patient;
                    $patient->net_balance = ($patient->net_balance ?? 0) + $transaction->total_amount;
                    $patient->save();
                }
            }

            // Send transaction status update email
            try {
                $emailService = app(EmailService::class);
                if ($request->payment_status === 'completed') {
                    $emailService->sendTransactionNotification($transaction, 'paid');
                } elseif ($request->payment_status === 'refunded') {
                    $emailService->sendTransactionNotification($transaction, 'refunded');
                } else {
                    $emailService->sendTransactionNotification($transaction, 'updated');
                }
            } catch (\Exception $e) {
                Log::error('Failed to send transaction status update email: ' . $e->getMessage());
            }
            return response()->json(['message' => 'Transaction status updated successfully', 'transaction' => $transaction]);
        });
    }
    public function get_users(Request $request)
    {
        $query = User::with(['doctorDetail', 'patientDetail'])
            ->whereNotIn('role', ['admin', 'superadmin'])
            ->when($request->filled('role'), function($q) use ($request) {
                $q->where('role', $request->role);
            })
            ->when($request->filled('speciality') && $request->role === 'specialist', function($q) use ($request) {
                $q->whereHas('doctorDetail', function($q) use ($request) {
                    $q->where('speciality', 'like', '%' . $request->speciality . '%');
                });
            });

        // Apply sorting
        if ($request->filled('sort_by')) {
            $sortField = $request->sort_by === 'speciality' ? 'doctorDetail.speciality' : $request->sort_by;
            $sortDirection = $request->input('sort_dir', 'asc');
            $query->orderBy($sortField, $sortDirection);
        }

        $perPage = $request->input('per_page', 20);
        $users = $query->paginate($perPage);

        // Transform the response
        $users->getCollection()->transform(function ($user) {
            $user->detail = $user->role === 'specialist' ? $user->doctorDetail : $user->patientDetail;
            
            if ($user->role === 'user' && $user->patientDetail) {
                $user->subscription = [
                    'status' => $user->patientDetail->subscription_status,
                    'plan' => $user->patientDetail->subscription_plan,
                    'start_date' => $user->patientDetail->subscription_start_date,
                    'end_date' => $user->patientDetail->subscription_end_date,
                    'trial_ends_at' => $user->patientDetail->trial_ends_at,
                    'is_trial_active' => $user->patientDetail->is_trial_active,
                    'amount' => $user->patientDetail->subscription_amount,
                    'payment_method' => $user->patientDetail->subscription_payment_method,
                ];
            }
            
            unset($user->doctorDetail);
            unset($user->patientDetail);
            return $user;
        });

        $totalUsers = User::count();

        return response()->json([
            'total_users' => $totalUsers,
            'data' => $users
        ]);
    }
    public function search_users(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2',
            'role' => 'nullable|in:specialist,user,patient'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $query = User::with(['doctorDetail', 'patientDetail'])
            ->where(function($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->name . '%')
                  ->orWhere('last_name', 'like', '%' . $request->name . '%')
                  ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ['%' . $request->name . '%']);
            });

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        
        $query->whereNotIn('role', ['admin', 'superadmin']);
        
        $users = $query->get();

        // Transform the response
        $users->transform(function ($user) {
            $user->detail = $user->role === 'specialist' ? $user->doctorDetail : $user->patientDetail;
            
            if ($user->role === 'user' && $user->patientDetail) {
                $user->subscription = [
                    'status' => $user->patientDetail->subscription_status,
                    'plan' => $user->patientDetail->subscription_plan,
                    'start_date' => $user->patientDetail->subscription_start_date,
                    'end_date' => $user->patientDetail->subscription_end_date,
                    'trial_ends_at' => $user->patientDetail->trial_ends_at,
                    'is_trial_active' => $user->patientDetail->is_trial_active,
                    'amount' => $user->patientDetail->subscription_amount,
                    'payment_method' => $user->patientDetail->subscription_payment_method,
                ];
            }
            
            unset($user->doctorDetail);
            unset($user->patientDetail);
            return $user;
        });

        return response()->json([
            'success' => true,
            'data' => $users,
            'count' => $users->count()
        ]);
    }

    public function get_user($value)
    {
        $query = User::with(['doctorDetail', 'patientDetail']);
        
        if (is_numeric($value)) {
            $user = $query->find($value);
        } else {
            $user = $query->where('user_token', $value)->first();
        }
    
        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }
        
        // Add the appropriate detail based on user role
        $user->detail = $user->role === 'specialist' ? $user->doctorDetail : $user->patientDetail;
        
        // Add subscription information for patients
        if ($user->role === 'user' && $user->patientDetail) {
            $user->subscription = [
                'status' => $user->patientDetail->subscription_status,
                'plan' => $user->patientDetail->subscription_plan,
                'start_date' => $user->patientDetail->subscription_start_date,
                'end_date' => $user->patientDetail->subscription_end_date,
                'trial_ends_at' => $user->patientDetail->trial_ends_at,
                'is_trial_active' => $user->patientDetail->is_trial_active,
                'amount' => $user->patientDetail->subscription_amount,
                'payment_method' => $user->patientDetail->subscription_payment_method,
            ];
        }
        
        unset($user->doctorDetail, $user->patientDetail);
    
        return response()->json([
            'data' => $user
        ]);
    }

    // Subscription API Methods
    public function getPatientSubscription($patientId)
    {
        $patient = User::find($patientId);
        if (!$patient || $patient->role !== 'user') {
            return response()->json([
                'success' => false,
                'message' => 'Patient not found'
            ], 404);
        }

        $patientDetail = $patient->patientDetail;
        if (!$patientDetail) {
            return response()->json([
                'success' => false,
                'message' => 'Patient details not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'subscription' => [
                'status' => $patientDetail->subscription_status,
                'plan' => $patientDetail->subscription_plan,
                'start_date' => $patientDetail->subscription_start_date,
                'end_date' => $patientDetail->subscription_end_date,
                'trial_ends_at' => $patientDetail->trial_ends_at,
                'is_trial_active' => $patientDetail->is_trial_active,
                'amount' => $patientDetail->subscription_amount,
                'payment_method' => $patientDetail->subscription_payment_method,
            ]
        ]);
    }

    public function getSubscriptionPlans()
    {
        $subscriptionService = app(SubscriptionService::class);
        $plans = $subscriptionService->getPlanDetails();

        return response()->json([
            'success' => true,
            'plans' => $plans
        ]);
    }

    public function upgradePatientSubscription(Request $request, $patientId)
    {
        $request->validate([
            'plan' => 'required|in:basic,premium',
            'amount' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string',
            'transaction_id' => 'nullable|string',
        ]);

        $patient = User::find($patientId);
        if (!$patient || $patient->role !== 'user') {
            return response()->json([
                'success' => false,
                'message' => 'Patient not found'
            ], 404);
        }

        $subscriptionService = app(\App\Services\SubscriptionService::class);

        try {
            $patientDetail = $subscriptionService->upgradeSubscription(
                $patient,
                $request->plan,
                $request->amount,
                $request->payment_method,
                $request->transaction_id
            );

            return response()->json([
                'success' => true,
                'message' => 'Subscription upgraded successfully',
                'subscription' => [
                    'status' => $patientDetail->subscription_status,
                    'plan' => $patientDetail->subscription_plan,
                    'start_date' => $patientDetail->subscription_start_date,
                    'end_date' => $patientDetail->subscription_end_date,
                    'amount' => $patientDetail->subscription_amount,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function cancelPatientSubscription($patientId)
    {
        $patient = User::find($patientId);
        if (!$patient || $patient->role !== 'user') {
            return response()->json([
                'success' => false,
                'message' => 'Patient not found'
            ], 404);
        }

        $subscriptionService = app(\App\Services\SubscriptionService::class);

        try {
            $patientDetail = $subscriptionService->cancelSubscription($patient);

            return response()->json([
                'success' => true,
                'message' => 'Subscription cancelled successfully',
                'subscription' => [
                    'status' => $patientDetail->subscription_status,
                    'plan' => $patientDetail->subscription_plan,
                    'end_date' => $patientDetail->subscription_end_date,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function canPatientBookAppointment($patientId)
    {
        $patient = User::find($patientId);
        if (!$patient || $patient->role !== 'user') {
            return response()->json([
                'success' => false,
                'message' => 'Patient not found'
            ], 404);
        }

        $subscriptionService = app(\App\Services\SubscriptionService::class);
        $canBook = $subscriptionService->canBookAppointment($patient);

        return response()->json([
            'success' => true,
            'can_book' => $canBook,
            'message' => $canBook ? 'Patient can book appointment' : 'Appointment Subscription Ended'
        ]);
    }
}