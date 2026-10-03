<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\FormatsDates;

class Schedule extends Model
{
    use HasFactory, FormatsDates;

    protected $fillable = ['user_id', 'start_date', 'end_date', 'start_time', 'end_time', 'event'];
    
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
