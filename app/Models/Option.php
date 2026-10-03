<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\FormatsDates;

class Option extends Model
{
    use HasFactory, FormatsDates;

    protected $fillable = [
        'question_id',
        'option_text',
        'sequence_number'
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
