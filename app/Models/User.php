<?php

namespace App\Models;

use App\Models\Schedule;
use App\Models\UserAnswer;
use App\Models\DoctorDetail;
use App\Models\DoctorReview;
use App\Traits\FormatsDates;
use App\Models\PatientDetail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasFactory, FormatsDates;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'is_block', 'role', 'user_token', 
        'fcm_token', 'longitude', 'latitude', 'profile_photo', 'email_verified_at', 'last_login_at', 'net_balance'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }


    /**
     * Get the doctor details associated with the user.
     */
    public function doctorDetail()
    {
        return $this->hasOne(DoctorDetail::class, 'user_id');
    }

    /**
     * Get the patient details associated with the user.
     */
    public function patientDetail()
    {
        return $this->hasOne(PatientDetail::class, 'user_id');
    }
    
    /**
     * Get the appropriate detail model based on user role
     * This is a helper method to maintain backward compatibility
     */
    public function detail()
    {
        if ($this->role === 'specialist') {
            return $this->doctorDetail();
        }
        return $this->patientDetail();
    }
    
    /**
     * Get all conversations that the user is a participant in
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function conversations()
    {
        return $this->belongsToMany(Conversation::class, 'conversation_participants')
            ->withPivot('is_admin')
            ->withTimestamps();
    }

    /**
     * Get reviews written by this user (as reviewer)
     */
    public function reviewsWritten()
    {
        return $this->hasMany(DoctorReview::class, 'reviewer_id');
    }

    /**
     * Get reviews received by this user (as doctor)
     */
    public function reviewsReceived()
    {
        return $this->hasMany(DoctorReview::class, 'doctor_id');
    }

    public function userAnswers()
    {
        return $this->hasMany(UserAnswer::class, 'user_id');
    }
}
