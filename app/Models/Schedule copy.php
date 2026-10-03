<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\FormatsDates;

class Schedule extends Model
{
    use HasFactory, FormatsDates;

    protected $fillable = [
        'user_id',
        'parent_id',
        'date',
        'end_date',
        'start_time',
        'end_time',
        'event',
        'notes',
        'is_recurring',
        'recurring_days'
    ];

    protected $casts = [
        'is_recurring' => 'boolean',
        'recurring_days' => 'array',
        'date' => 'date',
        'end_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'parent_id' => 'integer'
    ];
    
    protected $with = ['user'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function parent()
    {
        return $this->belongsTo(Schedule::class, 'parent_id');
    }
    
    public function children()
    {
        return $this->hasMany(Schedule::class, 'parent_id');
    }
    
    public function isRecurring()
    {
        return $this->is_recurring || $this->parent_id !== null;
    }
    
    public function isParent()
    {
        return $this->is_recurring && $this->parent_id === null;
    }
}
