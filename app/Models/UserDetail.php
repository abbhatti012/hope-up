<?php

namespace App\Models;

use App\Models\User;
use App\Models\Speciality;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\FormatsDates;

class UserDetail extends Model
{
    use HasFactory, FormatsDates;

    protected $fillable = [
        'user_id',
        'profile_photo',
        'phone_number',
        'dob',
        'gender',
        'address',
        'speciality_id',
        'medical_license',
        'experience',
        'graduation_year',
        'degree_certificate',
        'medical_concern',
    ];

    protected $hidden = ['speciality_id'];
    protected $appends = ['speciality'];

    public function getSpecialityAttribute()
    {
        return $this->specialityRelation ? $this->specialityRelation->title : null;
    }

    public function specialityRelation()
    {
        return $this->belongsTo(Speciality::class, 'speciality_id')->select(['id', 'title']);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
