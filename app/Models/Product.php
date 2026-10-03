<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\FormatsDates;

class Product extends Model
{
    use HasFactory, FormatsDates;
    protected $fillable = [
        'name',
        'price',
    ];
}
