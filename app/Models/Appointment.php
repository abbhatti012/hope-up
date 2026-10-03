<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\FormatsDates;

class Appointment extends Model
{
    use HasFactory, FormatsDates;

    // Status Constants
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'canceled';

    protected $fillable = [
        'patient_id', 'doctor_id', 'appointment_date', 'appointment_time', 'status', 'notes', 'treatment_type'
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'appointment_time' => 'datetime',
        'status' => 'string'
    ];

    protected $attributes = [
        'status' => self::STATUS_PENDING
    ];

    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_CONFIRMED => 'Confirmed',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_CANCELLED => 'Canceled'
        ];
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function getAppointmentDateAttribute($value)
    {
        if ($value) {
            return \Carbon\Carbon::parse($value)->format('Y-m-d');
        }
        return $value;
    }

    public function getAppointmentTimeAttribute($value)
    {
        if ($value) {
            return \Carbon\Carbon::parse($value)->format('H:i:s');
        }
        return $value;
    }
}
