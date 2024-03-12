<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    use HasFactory;

    protected $table = 'boards';

    protected $guarded = [ "read_at", "created_at", "updated_at" ];

    protected $primaryKey = 'name';

}
