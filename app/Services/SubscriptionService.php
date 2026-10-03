<?php

namespace App\Services;

use App\Models\User;
use App\Models\PatientDetail;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SubscriptionService
{
    /**
     * Initialize subscription for new patient
     */
    public function initializePatientSubscription(User $user)
    {
        if ($user->role !== 'user') {
            throw new \Exception('Only patients can have subscriptions');
        }

        $patientDetail = $user->patientDetail;
        
        if (!$patientDetail) {
            // Create patient detail if it doesn't exist
            $patientDetail = PatientDetail::create([
                'user_id' => $user->id,
            ]);
        }

        // Start free trial
        $patientDetail->startFreeTrial();

        Log::info("Free trial started for patient: {$user->id}");
        
        return $patientDetail;
    }

    /**
     * Check if patient can book appointment
     */
    public function canBookAppointment(User $user)
    {
        if ($user->role !== 'user') {
            return false;
        }

        $patientDetail = $user->patientDetail;
        
        if (!$patientDetail) {
            return false;
        }

        return $patientDetail->canBookAppointment();
    }

    /**
     * Process appointment booking and update subscription usage
     */
    public function processAppointmentBooking(User $user, Appointment $appointment)
    {
        if ($user->role !== 'user') {
            throw new \Exception('Only patients can book appointments');
        }

        $patientDetail = $user->patientDetail;
        
        if (!$patientDetail) {
            throw new \Exception('Patient details not found');
        }

        // Only allow booking if in trial or has active subscription
        if (!$patientDetail->canBookAppointment()) {
            throw new \Exception('Your subscription has ended. Please choose a plan to continue booking appointments.');
        }

        Log::info("Appointment booked for patient: {$user->id}, appointment: {$appointment->id}");
        
        return true;
    }

    /**
     * Upgrade patient subscription
     */
    public function upgradeSubscription(User $user, $plan, $amount = null, $paymentMethod = null, $transactionId = null)
    {
        if ($user->role !== 'user') {
            throw new \Exception('Only patients can upgrade subscriptions');
        }

        $patientDetail = $user->patientDetail;
        
        if (!$patientDetail) {
            throw new \Exception('Patient details not found');
        }

        $validPlans = ['basic', 'premium'];
        
        if (!in_array($plan, $validPlans)) {
            throw new \Exception('Invalid subscription plan');
        }

        $patientDetail->upgradePlan($plan, $amount, $paymentMethod, $transactionId);

        Log::info("Subscription upgraded for patient: {$user->id}, plan: {$plan}");
        
        return $patientDetail;
    }

    /**
     * Cancel patient subscription
     */
    public function cancelSubscription(User $user)
    {
        if ($user->role !== 'user') {
            throw new \Exception('Only patients can cancel subscriptions');
        }

        $patientDetail = $user->patientDetail;
        
        if (!$patientDetail) {
            throw new \Exception('Patient details not found');
        }

        $patientDetail->update([
            'subscription_status' => 'cancelled',
            'subscription_end_date' => now(),
        ]);

        Log::info("Subscription cancelled for patient: {$user->id}");
        
        return $patientDetail;
    }

    /**
     * Check and update expired subscriptions
     */
    public function checkExpiredSubscriptions()
    {
        $expiredPatients = PatientDetail::where('subscription_status', 'active')
            ->where('subscription_end_date', '<', now())
            ->get();

        foreach ($expiredPatients as $patientDetail) {
            $patientDetail->update([
                'subscription_status' => 'expired',
                'subscription_plan' => 'free',
                'monthly_appointment_limit' => 2,
            ]);

            Log::info("Subscription expired for patient: {$patientDetail->user_id}");
        }

        return $expiredPatients->count();
    }

    /**
     * Reset monthly appointment counts (run monthly)
     */
    public function resetMonthlyAppointmentCounts()
    {
        $patients = PatientDetail::where('subscription_status', 'free')
            ->get();

        foreach ($patients as $patientDetail) {
            $patientDetail->resetMonthlyAppointmentCount();
        }

        Log::info("Monthly appointment counts reset for {$patients->count()} patients");
        
        return $patients->count();
    }

    /**
     * Reset monthly appointment count for a specific patient
     */
    public function resetMonthlyAppointmentCount(User $user)
    {
        if ($user->role !== 'user') {
            throw new \Exception('Only patients can have appointment counts reset');
        }

        $patientDetail = $user->patientDetail;
        
        if (!$patientDetail) {
            throw new \Exception('Patient details not found');
        }

        $patientDetail->resetMonthlyAppointmentCount();

        Log::info("Monthly appointment count reset for patient: {$user->id}");
        
        return $patientDetail;
    }

    /**
     * Get subscription statistics
     */
    public function getSubscriptionStats()
    {
        $stats = [
            'total_patients' => PatientDetail::count(),
            'active_subscriptions' => PatientDetail::where('subscription_status', 'active')->count(),
            'free_trial_users' => PatientDetail::where('is_trial_active', true)->count(),
            'expired_subscriptions' => PatientDetail::where('subscription_status', 'expired')->count(),
            'cancelled_subscriptions' => PatientDetail::where('subscription_status', 'cancelled')->count(),
        ];

        return $stats;
    }

    /**
     * Get subscription plan details
     */
    public function getPlanDetails()
    {
        return [
            'basic' => [
                'name' => 'Basic',
                'price' => 19.99,
                'duration_months' => 1,
                'features' => ['All standard features']
            ],
            'premium' => [
                'name' => 'Premium',
                'price' => 49.99,
                'duration_months' => 12,
                'features' => ['All premium features']
            ]
        ];
    }
} 