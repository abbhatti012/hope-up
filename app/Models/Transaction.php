<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\FormatsDates;

class Transaction extends Model
{
    use HasFactory, FormatsDates;

    protected $fillable = [
        'patient_id', 'doctor_id', 'appointment_id',
        'payment_method', 'total_amount', 'currency',
        'payment_status', 'notes', 'doctor_amount', 'is_paid', 'payment_proof'
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'doctor_amount' => 'decimal:2',
        'is_paid' => 'boolean',
    ];
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
    
    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }
}
