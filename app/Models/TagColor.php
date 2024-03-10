<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagColor extends Model
{
    use HasFactory;

    protected $table = 'tag_colors';

    protected $guarded = [ "id", "created_at", "updated_at" ];

}
