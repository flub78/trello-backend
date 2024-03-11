<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Column extends Model
{
    use HasFactory;

    protected $table = 'columns';

    protected $guarded = [ "id", "created_at", "updated_at" ];

}
