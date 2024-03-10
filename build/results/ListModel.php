<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class List extends Model
{
    use HasFactory;

    protected $table = 'lists';

    protected $guarded = [ "id", "created_at", "updated_at" ];

}
