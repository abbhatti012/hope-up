<?php

namespace App\Models;

use App\Models\User;
use App\Traits\FormatsDates;
use App\Models\ManageSpeciality;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\DoctorDetailFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DoctorDetail extends Model
{
    use HasFactory, FormatsDates;

    protected $fillable = [
        'user_id',
        'phone_number',
        'dob',
        'gender',
        'about',
        'address',
        'speciality_id',
        'medical_license',
        'experience',
        'graduation_year',
        'degree_certificate',
    ];

    protected $hidden = ['speciality_id'];
    protected $appends = ['speciality'];
    
    protected static function newFactory()
    {
        return \Database\Factories\DoctorDetailFactory::new();
    }

    public function speciality()
    {
        return $this->belongsTo(Speciality::class, 'speciality_id');
    }
    
    // For backward compatibility
    public function specialityRelation()
    {
        return $this->belongsTo(Speciality::class, 'speciality_id');
    }
    
    public function getSpecialityAttribute()
    {
        if (!$this->relationLoaded('speciality')) {
            $this->load('speciality');
        }
        return $this->getRelation('speciality');
    }
    
    public function getSpecialityTitleAttribute()
    {
        return $this->speciality ? $this->speciality->title : 'General';
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
