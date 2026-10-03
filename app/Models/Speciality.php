<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\FormatsDates;

class Speciality extends Model
{
    use HasFactory, FormatsDates;
    
    protected static function newFactory()
    {
        return \Database\Factories\SpecialityFactory::new();
    }

    protected $table = 'manage_speciality';

    protected $fillable = ['id','title', 'is_active'];
}
