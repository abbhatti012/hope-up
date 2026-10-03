<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\FormatsDates;

class Content extends Model
{
    use HasFactory, FormatsDates;
    
    protected $fillable = ['title', 'description', 'content'];
}
