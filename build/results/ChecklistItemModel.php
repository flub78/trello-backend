<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecklistItem extends Model
{
    use HasFactory;

    protected $table = 'checklist_items';

    protected $guarded = [ "id", "created_at", "updated_at" ];

}
