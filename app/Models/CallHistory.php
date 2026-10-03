<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\FormatsDates;

class CallHistory extends Model
{
    use HasFactory, FormatsDates;
    protected $table = 'call_history';
    protected $fillable = [
        'caller_id', 
        'callee_id', 
        'call_type', 
        'status', 
        'started_at', 
        'ended_at', 
        'duration'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'duration' => 'integer'
    ];

    public function caller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'caller_id');
    }

    public function callee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'callee_id');
    }
}
