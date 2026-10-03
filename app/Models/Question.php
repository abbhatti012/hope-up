<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\FormatsDates;

class Question extends Model
{
    use HasFactory, FormatsDates;

    protected $fillable = [
        'question_text',
        'sequence_number',
        'assessment_type'
    ];
    
    protected $casts = [
        'assessment_type' => 'string',
    ];

    public function options(): HasMany
    {
        return $this->hasMany(Option::class)
            ->orderBy('sequence_number');
    }
}
