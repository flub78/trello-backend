<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    use HasFactory;

    protected $table = 'boards';

    protected $fillable = [
        'name',
        'description',
        'email',
        'favorite',
        'read_at',
        'href',
        'image',
        'theme',
    ];
}
