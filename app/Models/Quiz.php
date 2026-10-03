<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\FormatsDates;

class Quiz extends Model
{
    use HasFactory, FormatsDates;

    protected $fillable = [
        'title',
        'description',
        'category',
        'total_questions',
        'time_limit_minutes',
        'is_active'
    ];

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class)
            ->orderBy('sequence_number');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }
}
