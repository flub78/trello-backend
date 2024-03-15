<?php
/**
 * This file is generated from a template with metadata extracted from the data model.
 * If modifications are required, it is important to consider if they should be done in the template
 * or in the generated file, in which case caution must be exerted to avoid overwritting.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class {{class}} extends Model
{
    use HasFactory;

    protected $table = '{{table}}';

    protected $guarded = [ {{#cg}}  guarded {{/cg}} ];

    {{#cg}}  primary_key_declaration {{/cg}}
}
