<?php

namespace App\Faker;

use Faker\Provider\Base;

class CustomProvider extends Base
{
    public function csv_string(int $number)
    {
        // Generate and return some custom fake data ...
        return '"red", "green", "blue", "yellow"';
    }
}
