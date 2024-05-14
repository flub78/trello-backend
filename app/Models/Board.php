<?php

/**
 * This file is generated from a template with metadata extracted from the data model.
 * If modifications are required, it is important to consider if they should be done in the template
 * or in the generated file, in which case caution must be exerted to avoid overwritting.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Board extends Model
{
    use HasFactory;

    protected $table = 'boards';

    protected $guarded = ["read_at", "created_at", "updated_at"];

    protected $appends = ['image'];

    protected $primaryKey = 'name';
    protected $keyType = 'string';

    /**
     * Image attribute.
     */
    protected function getImageAttribute($value) {
        return 'image: ' . $this->name;
    }
}
