<?php

namespace App\Services;

use App\Models\User;
use App\Mail\EmailVerification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentNotification;
use App\Mail\RegistrationCredentials;
use App\Mail\TransactionNotification;
use App\Mail\AccountStatusNotification;
use App\Mail\PasswordChangeNotification;

class EmailService
{
    public function __construct()
    {
        // No dependencies needed as we'll use Laravel's built-in queue system
    }

    /**
     * Send registration credentials to user
     *
     * @param User $user
     * @param string $plainPassword
     * @return void
     */
    public function sendRegistrationCredentials(User $user, string $plainPassword): void
    {
        $data = [
            'name' => $user->first_name . ' ' . $user->last_name,
            'email' => $user->email,
            'password' => $plainPassword,
            'loginUrl' => url('/login'),
            'isAdmin' => $user->role === 'admin',
        ];

        try {
            // Queue the email for background processing
            // Laravel will handle the queue processing automatically
            $email = new RegistrationCredentials($data);
            Mail::to($user->email)->queue($email);
            
            Log::info('Registration email queued successfully for: ' . $user->email);
        } catch (\Exception $e) {
            Log::error('Failed to queue registration email', [
                'user_id' => $user->id ?? 'unknown',
                'email' => $user->email ?? 'unknown',
                'error' => $e->getMessage()
            ]);
            // Don't throw the exception to prevent blocking the main operation
        }
    }

    /**
     * Send block/unblock notification to user
     *
     * @param User $user
     * @param bool $isBlocked
     * @return void
     */
    public function sendBlockNotification(User $user, bool $isBlocked): void
    {
        try {
            $data = [
                'name' => $user->first_name . ' ' . $user->last_name,
                'email' => $user->email,
                'loginUrl' => url('/login'),
                'contactEmail' => config('mail.from.address'),
                'appName' => config('app.name')
            ];

            // Queue the email for background processing
            // Laravel will handle the queue processing automatically
            $email = new AccountStatusNotification($data, $isBlocked);
            Mail::to($user->email)->queue($email);

        } catch (\Exception $e) {
            Log::error('Failed to queue block notification email', [
                'user_id' => $user->id ?? 'unknown',
                'email' => $user->email ?? 'unknown',
                'error' => $e->getMessage()
            ]);
            // Don't throw the exception to prevent blocking the main operation
        }
    }

    /**
     * Send email verification for profile updates
     *
     * @param User $user
     * @param string $newEmail
     * @param string $verificationToken
     * @return void
     */
    public function sendEmailVerification(User $user, string $newEmail, string $verificationToken): void
    {
        try {
            $data = [
                'name' => $user->first_name . ' ' . $user->last_name,
                'email' => $newEmail,
                'verificationUrl' => url('/admin/verify-email/' . $verificationToken),
            ];

            // Queue the email for background processing
            $email = new EmailVerification($data);
            Mail::to($newEmail)->queue($email);
            
            Log::info('Email verification queued successfully for: ' . $newEmail);
        } catch (\Exception $e) {
            Log::error('Failed to queue email verification', [
                'user_id' => $user->id ?? 'unknown',
                'email' => $newEmail ?? 'unknown',
                'error' => $e->getMessage()
            ]);
            // Don't throw the exception to prevent blocking the main operation
        }
    }

    /**
     * Send password change notification
     *
     * @param User $user
     * @param string $ipAddress
     * @param string $userAgent
     * @return void
     */
    public function sendPasswordChangeNotification(User $user, string $ipAddress, string $userAgent): void
    {
        try {
            $data = [
                'name' => $user->first_name . ' ' . $user->last_name,
                'email' => $user->email,
                'changedAt' => now()->format('F j, Y \a\t g:i A'),
                'ipAddress' => $ipAddress,
                'userAgent' => $userAgent,
                'loginUrl' => url('/login'),
            ];

            // Queue the email for background processing
            $email = new PasswordChangeNotification($data);
            Mail::to($user->email)->queue($email);
            
            Log::info('Password change notification queued successfully for: ' . $user->email);
        } catch (\Exception $e) {
            Log::error('Failed to queue password change notification', [
                'user_id' => $user->id ?? 'unknown',
                'email' => $user->email ?? 'unknown',
                'error' => $e->getMessage()
            ]);
            // Don't throw the exception to prevent blocking the main operation
        }
    }

    /**
     * Send appointment notification to patient and doctor
     *
     * @param \App\Models\Appointment $appointment
     * @param string $action (created, updated, cancelled, completed)
     * @return void
     */
    public function sendAppointmentNotification(\App\Models\Appointment $appointment, string $action): void
    {
        try {
            $appointment->load(['patient', 'doctor']);
            
            if (!$appointment->patient || !$appointment->doctor) {
                Log::error('Appointment notification failed: Missing patient or doctor data', [
                    'appointment_id' => $appointment->id,
                    'action' => $action
                ]);
                return;
            }

            $data = [
                'appointment' => $appointment,
                'patient' => $appointment->patient,
                'doctor' => $appointment->doctor,
                'action' => $action,
                'appointmentDate' => \Carbon\Carbon::parse($appointment->appointment_date)->format('F j, Y'),
                'appointmentTime' => \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A'),
                'loginUrl' => url('/login'),
                'appName' => config('app.name'),
                'contactEmail' => config('mail.from.address')
            ];

            // Send email to patient
            $patientEmail = new AppointmentNotification($data, 'patient', $action);
            Mail::to($appointment->patient->email)->queue($patientEmail);

            // Send email to doctor
            $doctorEmail = new AppointmentNotification($data, 'doctor', $action);
            Mail::to($appointment->doctor->email)->queue($doctorEmail);
            
            Log::info('Appointment notification emails queued successfully', [
                'appointment_id' => $appointment->id,
                'action' => $action,
                'patient_email' => $appointment->patient->email,
                'doctor_email' => $appointment->doctor->email
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to queue appointment notification emails', [
                'appointment_id' => $appointment->id ?? 'unknown',
                'action' => $action,
                'error' => $e->getMessage()
            ]);
            // Don't throw the exception to prevent blocking the main operation
        }
    }

    /**
     * Send transaction notification to patient and doctor
     *
     * @param \App\Models\Transaction $transaction
     * @param string $action (created, paid, refunded, etc.)
     * @return void
     */
    public function sendTransactionNotification(\App\Models\Transaction $transaction, string $action): void
    {
        try {
            $transaction->load(['patient', 'doctor']);
            if (!$transaction->patient || !$transaction->doctor) {
                Log::error('Transaction notification failed: Missing patient or doctor data', [
                    'transaction_id' => $transaction->id,
                    'action' => $action
                ]);
                return;
            }
            $data = [
                'transaction' => $transaction,
                'patient' => $transaction->patient,
                'doctor' => $transaction->doctor,
                'transactionDate' => $transaction->created_at->format('F j, Y'),
                'transactionAmount' => $transaction->total_amount,
                'doctorAmount' => $transaction->doctor_amount,
                'currency' => $transaction->currency,
                'paymentStatus' => $transaction->payment_status,
                'paymentMethod' => $transaction->payment_method,
                'appName' => config('app.name'),
                'contactEmail' => config('mail.from.address')
            ];

            // For 'paid' action, only send to doctor if payment proof is involved
            if ($action === 'paid') {
                // Only send to doctor with payment proof
                $doctorEmail = new TransactionNotification($data, 'doctor', $action);
                Mail::to($transaction->doctor->email)->queue($doctorEmail);
                
                Log::info('Transaction paid notification sent to doctor only', [
                    'transaction_id' => $transaction->id,
                    'doctor_email' => $transaction->doctor->email
                ]);
            } else {
                // Send to both patient and doctor for other actions
                $patientEmail = new TransactionNotification($data, 'patient', $action);
                Mail::to($transaction->patient->email)->queue($patientEmail);
                
                $doctorEmail = new TransactionNotification($data, 'doctor', $action);
                Mail::to($transaction->doctor->email)->queue($doctorEmail);
                
                Log::info('Transaction notification emails queued successfully', [
                    'transaction_id' => $transaction->id,
                    'action' => $action,
                    'patient_email' => $transaction->patient->email,
                    'doctor_email' => $transaction->doctor->email
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to queue transaction notification emails', [
                'transaction_id' => $transaction->id,
                'action' => $action,
                'error' => $e->getMessage()
            ]);
        }
    }
}
