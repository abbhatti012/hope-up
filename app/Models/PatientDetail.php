<?php

namespace App\Models;

use App\Models\User;
use App\Traits\FormatsDates;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\PatientDetailFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class PatientDetail extends Model
{
    use HasFactory, FormatsDates;
    
    protected static function newFactory()
    {
        return \Database\Factories\PatientDetailFactory::new();
    }

    protected $fillable = [
        'user_id',
        'phone_number',
        'dob',
        'gender',
        'address',
        'age',
        'blood_type',
        'medical_concern',
        'is_ex_military',
        // Subscription fields
        'subscription_status',
        'subscription_plan',
        'subscription_start_date',
        'subscription_end_date',
        'trial_ends_at',
        'is_trial_active',
        'subscription_amount',
        'subscription_payment_method',
        'subscription_transaction_id',
    ];

    protected $casts = [
        'dob' => 'date',
        'is_ex_military' => 'boolean',
        'is_trial_active' => 'boolean',
        'subscription_start_date' => 'datetime',
        'subscription_end_date' => 'datetime',
        'trial_ends_at' => 'datetime',
        'subscription_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Calculate age from date of birth
     */
    public function calculateAge()
    {
        if (!$this->dob) {
            return null;
        }
        
        // Ensure dob is a Carbon instance
        $dob = $this->dob;
        if (is_string($dob)) {
            $dob = Carbon::parse($dob);
        }
        
        return now()->diffInYears($dob);
    }

    /**
     * Get the calculated age or stored age
     */
    public function getCalculatedAgeAttribute()
    {
        if ($this->age) {
            return $this->age;
        }
        
        return $this->calculateAge();
    }

    /**
     * Check if patient has active subscription
     */
    public function hasActiveSubscription()
    {
        return $this->subscription_status === 'active' && 
               $this->subscription_end_date && 
               $this->subscription_end_date->isFuture();
    }

    /**
     * Check if patient is in trial period
     */
    public function isInTrialPeriod()
    {
        return $this->is_trial_active && 
               $this->trial_ends_at && 
               $this->trial_ends_at->isFuture();
    }

    /**
     * Check if patient can book appointment
     */
    public function canBookAppointment()
    {
        // Allow booking if in trial or has active subscription
        return $this->isInTrialPeriod() || $this->hasActiveSubscription();
    }

    /**
     * Start free trial for new patient
     */
    public function startFreeTrial()
    {
        $this->update([
            'subscription_status' => 'free',
            'subscription_plan' => 'free',
            'is_trial_active' => true,
            'trial_ends_at' => now()->addMonth(),
            'subscription_start_date' => now(),
        ]);
    }

    /**
     * Upgrade subscription plan
     */
    public function upgradePlan($plan, $amount = null, $paymentMethod = null, $transactionId = null)
    {
        $durations = [
            'basic' => 1,
            'premium' => 12,
        ];

        $months = $durations[$plan] ?? 1;

        $this->update([
            'subscription_plan' => $plan,
            'subscription_status' => 'active',
            'subscription_start_date' => now(),
            'subscription_end_date' => now()->addMonths($months),
            'is_trial_active' => false,
            'subscription_amount' => $amount,
            'subscription_payment_method' => $paymentMethod,
            'subscription_transaction_id' => $transactionId,
        ]);
    }
}
